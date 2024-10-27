<?php

// app/Models/Client.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'prenom',
        'nom',
        'telephone',
        'email',
        'num_compte',
        'adresse',
        'carte_identite',
        'photo',
        'date_naissance',
        'password',
        'blocked',
        'role',
    ];

    // Relations
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'receveur_id');
    }

    // Relation pour les transactions envoyées (émetteur)
    public function sentTransactions()
    {
        return $this->hasMany(Transaction::class, 'emettteur_id');
    }
    
}
