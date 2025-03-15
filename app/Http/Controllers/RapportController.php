<?php
namespace App\Http\Controllers;

use App\Models\Emargement;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Pour PDF
use Maatwebsite\Excel\Facades\Excel; // Pour Excel
use App\Exports\EmargementsExport; // À créer pour Excel

class RapportController extends Controller
{
    public function index(Request $request)
    {
        $query = Emargement::with('cours');
        if (auth()->user()->role === 'professeur') {
            $query->where('professeur_id', auth()->id());
        }
        if ($request->date_debut) {
            $query->where('created_at', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->where('created_at', '<=', $request->date_fin . ' 23:59:59');
        }
        if ($request->professeur_id && (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire')) {
            $query->where('professeur_id', $request->professeur_id);
        }

        $emargements = $query->get();
        $professeurs = (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire')
            ? User::where('role', 'professeur')->get()
            : null;

        return view('rapports.index', compact('emargements', 'professeurs'));
    }

    public function statistiques(Request $request)
    {
        $query = Emargement::with('cours');
        if (auth()->user()->role === 'professeur') {
            $query->where('professeur_id', auth()->id());
        }
        if ($request->date_debut) {
            $query->where('created_at', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->where('created_at', '<=', $request->date_fin . ' 23:59:59');
        }
        if ($request->professeur_id && (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire')) {
            $query->where('professeur_id', $request->professeur_id);
        }

        $stats = $query->selectRaw('statut, COUNT(*) as count')->groupBy('statut')->get();
        $labels = $stats->pluck('statut')->toArray();
        $data = $stats->pluck('count')->toArray();
        $professeurs = (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire')
            ? User::where('role', 'professeur')->get()
            : null;

        return view('rapports.statistiques', compact('stats', 'labels', 'data', 'professeurs'));
    }

    public function exportPdf(Request $request)
    {
        $query = Emargement::with('cours');
        if (auth()->user()->role === 'professeur') {
            $query->where('professeur_id', auth()->id());
        }
        if ($request->date_debut) {
            $query->where('created_at', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->where('created_at', '<=', $request->date_fin . ' 23:59:59');
        }
        if ($request->professeur_id && (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire')) {
            $query->where('professeur_id', $request->professeur_id);
        }

        $emargements = $query->get();
        $pdf = Pdf::loadView('rapports.pdf', compact('emargements'));
        return $pdf->download('rapport_emargements.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = Emargement::with('cours');
        if (auth()->user()->role === 'professeur') {
            $query->where('professeur_id', auth()->id());
        }
        if ($request->date_debut) {
            $query->where('created_at', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->where('created_at', '<=', $request->date_fin . ' 23:59:59');
        }
        if ($request->professeur_id && (auth()->user()->role === 'admin' || auth()->user()->role === 'gestionnaire')) {
            $query->where('professeur_id', $request->professeur_id);
        }

        $emargements = $query->get();
        return Excel::download(new EmargementsExport($emargements), 'rapport_emargements.xlsx');
    }
}
