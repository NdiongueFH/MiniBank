<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // Assurez-vous d'importer le trait ici


class User extends Authenticatable
{
    use HasFactory, Notifiable; // Ajout du trait Notifiable

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

    protected static function boot()
    {
        parent::boot();

        // Générer le numéro de compte avant de créer un utilisateur
        static::creating(function ($user) {
            $user->num_compte = self::generateAccountNumber($user->role);
            $user->password = bcrypt($user->password); // Hacher le mot de passe
        });
    }

    private static function generateAccountNumber($role)
    {
        return strtoupper(substr($role, 0, 3)) . date('Y') . rand(1000, 9999);
    }

    // Dans le modèle User
    public function compte()
    {
        return $this->hasOne(Compte::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'distributeur_id');
    }

}


