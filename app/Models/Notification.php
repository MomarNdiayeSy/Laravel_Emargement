<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['message', 'destinataire_id', 'date_envoi'];
    protected $casts = [
        'date_envoi' => 'datetime',
    ];

    // Relations
    public function destinataire()
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }
}
