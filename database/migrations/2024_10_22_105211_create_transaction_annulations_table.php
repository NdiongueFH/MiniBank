<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('transaction_annulations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('transaction_id'); // La transaction annulée
        $table->unsignedBigInteger('client_id'); // Le client qui demande l'annulation
        $table->unsignedBigInteger('agent_id')->nullable(); // L'agent qui valide l'annulation
        $table->timestamp('requested_at')->useCurrent(); // Heure de la demande d'annulation
        $table->timestamp('cancelled_at')->nullable(); // Heure d'annulation
        $table->timestamps();

        $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_annulations');
    }
};
