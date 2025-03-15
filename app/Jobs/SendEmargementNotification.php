<?php
namespace App\Jobs;

use App\Models\Emargement;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmargementNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emargement;

    /**
     * Créer une nouvelle instance du Job.
     */
    public function __construct(Emargement $emargement)
    {
        $this->emargement = $emargement;
    }

    /**
     * Exécuter le Job.
     */
    public function handle()
    {
        // Créer une notification pour le professeur
        Notification::create([
            'message' => "Émargement mis à jour : Cours {$this->emargement->cours->nom}, Statut : {$this->emargement->statut} le {$this->emargement->date->format('d/m/Y H:i')}",
            'destinataire_id' => $this->emargement->professeur_id,
            'date_envoi' => now(),
        ]);
    }
}
