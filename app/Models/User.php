<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
}
