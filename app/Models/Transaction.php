<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'emetteur_id',
        'receveur_id',
        'distributeur_id',
        'agent_id',
        'type',
        'montant',
        'frais',
        'annule',
        'statut',
        'expires_at',
    ];

    // Relation avec l'utilisateur émetteur
    public function emetteur()
    {
        return $this->belongsTo(User::class, 'emetteur_id');
    }

    // Relation avec l'utilisateur receveur
    public function receveur()
    {
        return $this->belongsTo(User::class, 'receveur_id');
    }

    // Relation avec le distributeur
    public function distributeur()
    {
        return $this->belongsTo(User::class, 'distributeur_id');
    }

    // Relation avec l'agent
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
