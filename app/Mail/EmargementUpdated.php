<?php
namespace App\Mail;

use App\Models\Emargement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmargementUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $emargement;

    public function __construct(Emargement $emargement)
    {
        $this->emargement = $emargement;
    }

    public function build()
    {
        return $this->subject('Mise à jour de votre émargement')
            ->view('emails.emargement_updated');
    }
}
