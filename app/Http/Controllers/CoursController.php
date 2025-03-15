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
            // Pas de restriction pour gestionnaire ou admin, ils voient tous les cours
        } else {
            abort(403, 'Accès non autorisé.');
        }
        $cours = $query->get();
        $role = auth()->user()->role; // Passer le rôle à la vue
        return view('cours.index', compact('cours', 'role'));
    }

    public function show($id)
    {
        $cours = Cours::with(['professeur', 'salle'])->findOrFail($id);
        if (auth()->user()->role === 'professeur' && $cours->professeur_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        } elseif (auth()->user()->role === 'gestionnaire' || auth()->user()->role === 'admin') {
            // Pas de restriction pour gestionnaire ou admin
        }
        else {
//            abort(403, 'Accès non autorisé.');
        }
        $role = auth()->user()->role;
        return view('cours.show', compact('cours', 'role'));
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
            'heure_debut' => 'required|date',
            'heure_fin' => 'required|date|after:heure_debut',
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

        $cours = Cours::create($request->all());
        SendCoursNotification::dispatch($cours);
        Mail::to($cours->professeur->email)->send(new CoursAssigned($cours, 'created'));
        Mail::to(auth()->user()->email)->send(new CoursAssigned($cours, 'created'));

        $redirectRoute = auth()->user()->role === 'gestionnaire' ? 'gestionnaire.cours.index' : 'admin.cours.index';
        return redirect()->route($redirectRoute)->with('success', 'Cours créé et notifications envoyées.');
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
