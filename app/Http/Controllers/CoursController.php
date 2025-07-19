<?php
namespace App\Http\Controllers;

use App\Models\Cours;
use Illuminate\Http\Request;
use App\Jobs\SendCoursNotification;
use App\Mail\CoursAssigned;
use Illuminate\Support\Facades\Mail;

class CoursController extends Controller
{

    public function index()
    {
        $query = Cours::with(['professeur', 'salle']);
        if (auth()->user()->role === 'professeur') {
            $query->where('professeur_id', auth()->id());
        } elseif (auth()->user()->role === 'gestionnaire' || auth()->user()->role === 'admin') {
            // Pas de restriction pour gestionnaire ou admin
        } else {
            abort(403, 'Accès non autorisé.');
        }
        $cours = $query->get();
        $role = auth()->user()->role;

        // Si professeur, calculer les nouveaux cours et mettre à jour notified_at une seule fois
        if ($role === 'professeur') {
            $lastLogin = auth()->user()->last_login_at ?? '1970-01-01';
            $newCours = $cours->whereNull('notified_at')
                ->where('created_at', '>', $lastLogin);

            // Mettre à jour notified_at pour les nouveaux cours à cette première connexion
            if ($newCours->isNotEmpty()) {
                Cours::whereIn('id', $newCours->pluck('id'))->update(['notified_at' => now()]);
            }

            $newCoursCount = $newCours->count();
        } else {
            $newCoursCount = 0;
        }

        return view('cours.index', compact('cours', 'role', 'newCoursCount'));
    }

    // ... (show et store inchangés sauf ajustement mineur ci-dessous)

    public function show($id)
    {
        $cours = Cours::with(['professeur', 'salle'])->findOrFail($id);
        if (auth()->user()->role === 'professeur' && $cours->professeur_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        } elseif (auth()->user()->role === 'gestionnaire' || auth()->user()->role === 'admin') {
            // Pas de restriction pour gestionnaire ou admin
        }

        // On garde is_new pour d'autres usages éventuels, mais pas pour le badge
        if (auth()->user()->role === 'professeur' && $cours->is_new) {
            $cours->update(['is_new' => false]);
        }

        $role = auth()->user()->role;
        return view('cours.show', compact('cours', 'role'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'professeur_id' => 'required|exists:users,id',
            'salle_id' => 'required|exists:salles,id',
            'description' => 'nullable|string',
            'heure_debut' => [
                'required',
                'date',
                'after_or_equal:' . now()->startOfDay()->toDateTimeString(),
                function ($attribute, $value, $fail) {
                    $heureDebut = \Carbon\Carbon::parse($value);
                    if ($heureDebut->isToday() && $heureDebut->isPast()) {
                        $fail('L’heure de début ne peut pas être une heure passée pour aujourd’hui.');
                    }
                },
            ],
            'heure_fin' => [
                'required',
                'date',
                'after:heure_debut',
            ],
        ], [
            'heure_debut.after_or_equal' => 'La date et l’heure de début doivent être aujourd’hui ou dans le futur.',
            'heure_fin.after' => 'L’heure de fin doit être postérieure à l’heure de début.',
        ]);

        $conflitSalle = Cours::where('salle_id', $request->salle_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->exists();

        if ($conflitSalle) {
            return back()->withErrors(['salle_id' => 'Cette salle est déjà occupée à cette heure et cette date.']);
        }

        $conflitProfesseur = Cours::where('professeur_id', $request->professeur_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->exists();

        if ($conflitProfesseur) {
            return back()->withErrors(['professeur_id' => 'Ce professeur a déjà un cours à cette heure.']);
        }

        $cours = Cours::create($request->all() + ['is_new' => true, 'notified_at' => null]);
        SendCoursNotification::dispatch($cours);
        Mail::to($cours->professeur->email)->send(new CoursAssigned($cours, 'created'));
        Mail::to(auth()->user()->email)->send(new CoursAssigned($cours, 'created'));

        $redirectRoute = auth()->user()->role === 'gestionnaire' ? 'gestionnaire.cours.index' : 'admin.cours.index';
        return redirect()->route($redirectRoute)->with('success', 'Cours créé et notifications envoyées.');
    }
    public function create()
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        $professeurs = \App\Models\User::where('role', 'professeur')->get();
        $salles = \App\Models\Salle::all();
        $role = auth()->user()->role;
        return view('cours.create', compact('professeurs', 'salles', 'role'));
    }



    public function edit($id)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }
        $cours = Cours::findOrFail($id);
        $professeurs = \App\Models\User::where('role', 'professeur')->get();
        $salles = \App\Models\Salle::all();
        $role = auth()->user()->role;
        return view('cours.edit', compact('cours', 'professeurs', 'salles', 'role'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }

        $cours = Cours::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'professeur_id' => 'required|exists:users,id',
            'salle_id' => 'required|exists:salles,id',
            'description' => 'nullable|string',
            'heure_debut' => 'required|date',
            'heure_fin' => 'required|date|after:heure_debut',
        ]);

        $conflitSalle = Cours::where('salle_id', $request->salle_id)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->exists();

        if ($conflitSalle) {
            return back()->withErrors(['salle_id' => 'Cette salle est déjà occupée à cette heure et cette date.']);
        }

        $conflitProfesseur = Cours::where('professeur_id', $request->professeur_id)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->exists();

        if ($conflitProfesseur) {
            return back()->withErrors(['professeur_id' => 'Ce professeur a déjà un cours à cette heure.']);
        }

        $cours->update($request->all());
        SendCoursNotification::dispatch($cours);
        Mail::to($cours->professeur->email)->send(new CoursAssigned($cours, 'updated'));
        Mail::to(auth()->user()->email)->send(new CoursAssigned($cours, 'updated'));

        $redirectRoute = auth()->user()->role === 'gestionnaire' ? 'gestionnaire.cours.index' : 'admin.cours.index';
        return redirect()->route($redirectRoute)->with('success', 'Cours mis à jour et notifications envoyées.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'gestionnaire') {
            abort(403, 'Accès non autorisé.');
        }

        $cours = Cours::findOrFail($id);
        SendCoursNotification::dispatch($cours);
        Mail::to($cours->professeur->email)->send(new CoursAssigned($cours, 'deleted'));
        Mail::to(auth()->user()->email)->send(new CoursAssigned($cours, 'deleted'));
        $cours->delete();

        $redirectRoute = auth()->user()->role === 'gestionnaire' ? 'gestionnaire.cours.index' : 'admin.cours.index';
        return redirect()->route($redirectRoute)->with('success', 'Cours supprimé et notifications envoyées.');
    }
}
