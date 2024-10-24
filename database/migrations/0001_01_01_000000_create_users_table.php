<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('prenom');  // Prénom de l'utilisateur
            $table->string('nom');   // Nom de famille de l'utilisateur
            $table->string('telephone')->unique(); // Numéro de téléphone unique
            $table->string('email')->unique()->nullable(); // Email unique, optionnel
            $table->string('num_compte')->unique()->nullable();
            $table->string('adresse');  // Adresse de l'utilisateur
            $table->string('carte_identite')->unique(); // Numéro de carte d'identité unique
            $table->string('photo')->nullable(); // URL ou chemin de la photo de l'utilisateur
            $table->date('date_naissance'); // Date de naissance de l'utilisateur
            $table->string('password'); // Mot de passe (hashé)
            $table->boolean('blocked')->default(false); // État du compte (bloqué ou non)
            $table->enum('role', ['agent', 'distributeur', 'client']); // Rôle de l'utilisateur
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}