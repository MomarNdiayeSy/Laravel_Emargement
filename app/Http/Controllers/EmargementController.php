<?php
namespace App\Http\Controllers;

use App\Mail\EmargementUpdated;
use App\Models\Emargement;
use App\Models\Cours;
use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendEmargementNotification;
use Illuminate\Support\Facades\Mail;

class EmargementController extends Controller
{
    public function index()
    {
        $query = Emargement::with(['cours.salle']);
        if (auth()->user()->role === 'professeur') {
            $query->where('professeur_id', auth()->id());
        }
        $emargements = $query->get();

        // Compter les émargements en attente pour l'admin
        $pendingEmargementsCount = (auth()->user()->role === 'admin')
            ? Emargement::where('valide_par_admin', false)->count()
            : 0;

        return view('emargements.index', compact('emargements', 'pendingEmargementsCount'));
    }

    public function show($id)
    {
        if (!is_numeric($id)) {
            abort(404, 'Identifiant d’émargement invalide.');
        }
        $emargement = Emargement::with('cours')->findOrFail($id);
        if (auth()->user()->role === 'professeur' && $emargement->professeur_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }
        return view('emargements.show', compact('emargement'));
    }

    public function create()
    {
        if (auth()->user()->role === 'professeur') {
            $coursQuery = Cours::where('professeur_id', auth()->id())->with('salle');
            $existingEmargements = Emargement::select('cours_id')->pluck('cours_id')->toArray();
            $cours = $coursQuery->whereNotIn('id', $existingEmargements)->get();
            $professeurs = null;
        } else {
            $cours = Cours::with('salle')->get();
            $professeurs = User::where('role', 'professeur')->get();
        }

        $coursProfesseurOptions = [];
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire') {
            $existingEmargements = Emargement::select('professeur_id', 'cours_id')->get()
                ->map(fn($e) => "{$e->professeur_id}-{$e->cours_id}")
                ->toArray();

            foreach ($cours as $cour) {
                $professeur = $cour->professeur;
                if ($professeur) {
                    $value = "{$professeur->id}-{$cour->id}";
                    if (!in_array($value, $existingEmargements)) {
                        $salle = $cour->salle ? $cour->salle->libelle : 'N/A';
                        $date = $cour->heure_debut->format('d/m/Y H:i');
                        $coursProfesseurOptions[] = [
                            'value' => $value,
                            'label' => "professeur {$professeur->prenom} {$professeur->nom} -- {$cour->nom} -- Salle {$salle} -- {$date}",
                        ];
                    }
                }
            }
        }

        return view('emargements.create', compact('cours', 'professeurs', 'coursProfesseurOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cours_professeur' => 'required_if:role,admin,gestionnaire|string',
            'cours_id' => 'required_if:role,professeur|exists:cours,id',
            'statut' => 'required|in:pending,présent,absent', // Validation incluant 'pending'
        ]);

        if (auth()->user()->role === 'professeur') {
            $cours = Cours::findOrFail($request->cours_id);
            $professeur_id = auth()->id();
            if ($cours->professeur_id !== auth()->id()) {
                abort(403, 'Accès non autorisé.');
            }
            $data = [
                'cours_id' => $request->cours_id,
                'professeur_id' => $professeur_id,
                'statut' => 'pending', // Forcé à 'pending' pour les professeurs
                'date' => now(),
                'valide_par_admin' => false,
            ];
        } else { // admin ou gestionnaire
            [$professeur_id, $cours_id] = explode('-', $request->cours_professeur);
            $cours = Cours::findOrFail($cours_id);
            $professeur_id = (int) $professeur_id;
            $data = [
                'cours_id' => $cours_id,
                'professeur_id' => $professeur_id,
                'statut' => $request->statut,
                'date' => now(),
                'valide_par_admin' => auth()->user()->role === 'admin' ? true : false,
            ];
            if (auth()->user()->role === 'admin') {
                $professeur = User::findOrFail($professeur_id);
                $data['description'] = "professeur {$professeur->prenom} {$professeur->nom} -- {$cours->nom}";
            }
        }

        $emargement = Emargement::create($data);

        if ($emargement->valide_par_admin) {
            SendEmargementNotification::dispatch($emargement);
            Mail::to($emargement->professeur->email)->send(new EmargementUpdated($emargement));
        }

        $redirectRoute = match (auth()->user()->role) {
            'admin' => 'admin.emargements.index',
            'professeur' => 'professeur.emargements.index',
            'gestionnaire' => 'gestionnaire.emargements.index',
            default => 'emargements.index',
        };
        return redirect()->route($redirectRoute)->with('success', 'Émargement créé. En attente de validation si créé par un professeur.');
    }

    public function edit($id)
    {
        if (auth()->user()->role === 'professeur') {
            $emargement = Emargement::where('professeur_id', auth()->id())->findOrFail($id);
            $cours = Cours::where('professeur_id', auth()->id())->with('salle')->get();
            $professeurs = null;
        } else {
            $emargement = Emargement::findOrFail($id);
            $cours = Cours::with('salle')->get();
            $professeurs = User::where('role', 'professeur')->get();
        }

        $coursProfesseurOptions = [];
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire') {
            foreach ($cours as $cour) {
                $professeur = $cour->professeur;
                if ($professeur) {
                    $salle = $cour->salle ? $cour->salle->libelle : 'N/A';
                    $date = $cour->heure_debut->format('d/m/Y H:i');
                    $coursProfesseurOptions[] = [
                        'value' => "{$professeur->id}-{$cour->id}",
                        'label' => "professeur {$professeur->prenom} {$professeur->nom} -- {$cour->nom} -- {$salle} -- {$date}",
                    ];
                }
            }
        }

        return view('emargements.edit', compact('emargement', 'cours', 'professeurs', 'coursProfesseurOptions'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role === 'professeur') {
            $emargement = Emargement::where('professeur_id', auth()->id())->findOrFail($id);
        } else {
            $emargement = Emargement::findOrFail($id);
        }

        $request->validate([
            'cours_professeur' => 'required_if:role,admin,gestionnaire|string',
            'cours_id' => 'required_if:role,professeur|exists:cours,id',
            'statut' => 'required|in:pending,présent,absent',
        ]);

        if (auth()->user()->role === 'professeur') {
            $cours = Cours::findOrFail($request->cours_id);
            $professeur_id = auth()->id();
            if ($cours->professeur_id !== auth()->id()) {
                abort(403, 'Accès non autorisé.');
            }
            $data = [
                'cours_id' => $request->cours_id,
                'professeur_id' => $professeur_id,
                'statut' => 'pending',
                'valide_par_admin' => false,
            ];
        } else { // admin ou gestionnaire
            [$professeur_id, $cours_id] = explode('-', $request->cours_professeur);
            $cours = Cours::findOrFail($cours_id);
            $professeur_id = (int) $professeur_id;
            $data = [
                'cours_id' => $cours_id,
                'professeur_id' => $professeur_id,
                'statut' => $request->statut,
                'valide_par_admin' => auth()->user()->role === 'admin' ? true : $emargement->valide_par_admin,
            ];
            if (auth()->user()->role === 'admin') {
                $professeur = User::findOrFail($professeur_id);
                $data['description'] = "professeur {$professeur->prenom} {$professeur->nom} -- {$cours->nom}";
            }
        }

        $emargement->update($data);

        if ($emargement->valide_par_admin) {
            SendEmargementNotification::dispatch($emargement);
            Mail::to($emargement->professeur->email)->send(new EmargementUpdated($emargement));
        }

        $redirectRoute = match (auth()->user()->role) {
            'admin' => 'admin.emargements.index',
            'professeur' => 'professeur.emargements.index',
            'gestionnaire' => 'gestionnaire.emargements.index',
            default => 'emargements.index',
        };
        return redirect()->route($redirectRoute)->with('success', 'Émargement mis à jour. En attente de validation si modifié par un professeur.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role === 'professeur') {
            abort(403, 'Accès non autorisé.');
        }
        $emargement = Emargement::findOrFail($id);
        $emargement->delete();

        $redirectRoute = auth()->user()->role === 'admin' ? 'admin.emargements.index' : 'gestionnaire.emargements.index';
        return redirect()->route($redirectRoute)->with('success', 'Émargement supprimé.');
    }

    public function validateEmargement(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        $emargement = Emargement::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:présent,absent', // L’admin choisit le statut final
        ]);

        $emargement->update([
            'statut' => $request->statut,
            'valide_par_admin' => true,
        ]);

        SendEmargementNotification::dispatch($emargement);
        Mail::to($emargement->professeur->email)->send(new EmargementUpdated($emargement));

        return redirect()->route('admin.emargements.index')->with('success', 'Émargement validé avec succès. Notification envoyée au professeur.');
    }
}
