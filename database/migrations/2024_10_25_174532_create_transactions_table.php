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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('emettteur_id')->nullable(); // Client pour un transfert
        $table->unsignedBigInteger('receveur_id')->nullable(); // Client pour un transfert
        $table->unsignedBigInteger('distributeur_id'); // Distributeur pour dépôt ou retrait
        $table->unsignedBigInteger('agent_id')->nullable(); // Agent pour dépôt illimité ou annulation
        $table->enum('type', ['depot', 'retrait', 'transfert']);
        $table->decimal('mountant', 15, 2);
        $table->decimal('frais', 15, 2)->default(0); // Frais de 2% pour transfert
        $table->boolean('annule')->default(false); // Annulé ou non
        $table->string('statut')->default('completed'); // Peut être "completed" ou "pending"
        //$table->timestamp('created_at')->useCurrent();
        $table->timestamp('expires_at')->nullable(); // Pour annulation dans les 30 minutes
        $table->timestamps();

        $table->foreign('emettteur_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('receveur_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('distributeur_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
