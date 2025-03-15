<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emargement extends Model
{
//    protected $fillable = ['date', 'statut', 'professeur_id', 'cours_id', 'description'];
    protected $fillable = ['cours_id', 'professeur_id', 'statut', 'date', 'description', 'valide_par_admin'];
    protected $casts = [
        'date' => 'datetime',
    ];

    // Relations
    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
}
