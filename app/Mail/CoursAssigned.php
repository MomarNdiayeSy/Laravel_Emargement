<?php
namespace App\Mail;

use App\Models\Cours;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoursAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $cours;
    public $action;

    public function __construct(Cours $cours, $action = 'created')
    {
        $this->cours = $cours;
        $this->action = $action;
    }

    public function build()
    {
        $subject = match ($this->action) {
            'created' => 'Nouveau cours assigné',
            'updated' => 'Mise à jour d’un cours',
            'deleted' => 'Cours supprimé',
            default => 'Notification de cours',
        };

        return $this->subject($subject)
            ->view('emails.cours_assigned')
            ->with([
                'cours' => $this->cours,
                'action' => $this->action,
            ]);
    }
}
