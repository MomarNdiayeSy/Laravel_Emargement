<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    protected $table = 'cours';
    protected $fillable = ['nom', 'description', 'heure_debut', 'heure_fin', 'salle_id', 'professeur_id'];
    protected $casts = [
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime',
    ];

    // Relations
//    public function salle()
//    {
//        return $this->belongsTo(Salle::class);
//    }
    public function salle()
    {
        return $this->belongsTo(Salle::class, 'salle_id');
    }

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function emargements()
    {
        return $this->hasMany(Emargement::class);
    }
}
