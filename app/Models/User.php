<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;  
 use Illuminate\Database\Eloquent\Relations\HasMany;  
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'prenom',
        'nom',
        'telephone', // Champ utilisé pour l'authentification
        'email',
        'num_compte',
        'adresse',
        'carte_identite',
        'photo',
        'date_naissance',
        'num_compte',
        'adresse',
        'carte_identite',
        'photo',
        'date_naissance',
        'password',
        'blocked',
        'role',
    ];

    /**
     * Boot method pour ajouter des comportements par défaut lors de la création d'un utilisateur.
     */
    protected static function boot()
    {
        parent::boot();

        // Générer le numéro de compte avant de créer un utilisateur
        static::creating(function ($user) {
            $user->num_compte = self::generateAccountNumber($user->role);
            

        });
    }

    /**
     * Générer un numéro de compte unique basé sur le rôle de l'utilisateur.
     */
    private static function generateAccountNumber($role)
    {
        return strtoupper(substr($role, 0, 3)) . date('Y') . rand(1000, 9999);
    }

}


