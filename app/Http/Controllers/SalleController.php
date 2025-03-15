<?php
namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }
        $salles = Salle::all();
        return view('salles.index', compact('salles'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }
        return view('salles.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate(['libelle' => 'required|string|max:255|unique:salles']);
        Salle::create($request->only('libelle'));
        return redirect()->route('admin.salles.index')->with('success', 'Salle ajoutée avec succès.');
    }

    public function edit(Salle $salle)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }
        return view('salles.edit', compact('salle'));
    }

    public function update(Request $request, Salle $salle)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate(['libelle' => 'required|string|max:255|unique:salles,libelle,' . $salle->id]);
        $salle->update($request->only('libelle'));
        return redirect()->route('admin.salles.index')->with('success', 'Salle mise à jour avec succès.');
    }

    public function destroy(Salle $salle)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        $salle->delete();
        return redirect()->route('admin.salles.index')->with('success', 'Salle supprimée avec succès.');
    }
}
