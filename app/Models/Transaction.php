<?php

// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'distributeur_id',
        'agent_id',
        'type',
        'mountant',
        'frais',
        'annule',
        'statut',
        'expires_at'
    ];

    public function distributeur()
    {
        return $this->belongsTo(User::class, 'distributeur_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}


