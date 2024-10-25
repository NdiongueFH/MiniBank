<?php

// app/Models/Compte.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'solde'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
