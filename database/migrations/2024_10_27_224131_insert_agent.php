<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InsertAgent extends Migration
{
    public function up()
    {
        User::create([
            'prenom' => 'Moustapha',
            'nom' => 'Diop',
            'telephone' => '771871325',
            'email' => 'moustapha@gmail.com',
            'adresse' => 'Grand-Yoff',
            'carte_identite' => '8232893743',
            'date_naissance' => '2001-05-12',
            'password' => Hash::make('password123'),
            'blocked' => false,
            'role' => 'agent',
        ]);
    }

    public function down()
    {
        // User::where('telephone', '779871625')->delete();
    }
}
