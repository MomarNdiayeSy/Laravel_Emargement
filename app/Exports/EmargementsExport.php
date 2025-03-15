<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class EmargementsExport implements FromCollection, WithHeadings
{
    protected $emargements;

    public function __construct($emargements)
    {
        $this->emargements = $emargements;
    }

    public function collection()
    {
        return $this->emargements->map(function ($emargement) {
            return [
                'Date' => $emargement->created_at->format('d/m/Y H:i'),
                'Professeur' => $emargement->professeur->prenom . ' ' . $emargement->professeur->nom,
                'Cours' => $emargement->cours->nom,
                'Statut' => $emargement->statut,
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'Professeur', 'Cours', 'Statut'];
    }
}
