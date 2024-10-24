<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;  
 use Illuminate\Database\Eloquent\Relations\HasMany;  
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasFactory, HasRoles;  

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

    // Définir la relation avec les transactions
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}