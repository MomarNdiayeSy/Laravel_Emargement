<?php
namespace App\Jobs;

use App\Models\Cours;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCoursNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cours;

    public function __construct(Cours $cours)
    {
        $this->cours = $cours;
    }

    public function handle()
    {
        // Message personnalisé avec plus de détails
        $message = "Vous avez été assigné(e) à un nouveau cours : '{$this->cours->nom}'. " .
            "Date : {$this->cours->heure_debut->format('d/m/Y')} de {$this->cours->heure_debut->format('H:i')} " .
            "à {$this->cours->heure_fin->format('H:i')}. " .
            "Salle : " . ($this->cours->salle ? $this->cours->salle->libelle : 'non définie') . ".";

        Notification::create([
            'message' => $message,
            'destinataire_id' => $this->cours->professeur_id,
            'date_envoi' => now(),
        ]);
    }
}
