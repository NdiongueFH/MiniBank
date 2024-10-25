<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Compte;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class User extends Authenticatable
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

    // app/Models/User.php
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'distributeur_id');
    }

    public function compte()
    {
        return $this->hasOne(Compte::class, 'user_id');
    }

}


