<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Mail\ResetPasswordNotification;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['nom', 'prenom', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    // Relations
    public function cours()
    {
        return $this->hasMany(Cours::class, 'professeur_id');
    }

    public function emargements()
    {
        return $this->hasMany(Emargement::class, 'professeur_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'destinataire_id');
    }

    public function sendPasswordResetNotification($token)
    {
        $url = url(route('password.reset', ['token' => $token, 'email' => $this->email], false));
        Mail::to($this->email)->send(new ResetPasswordNotification($url));
    }
}
