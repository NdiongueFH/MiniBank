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
    Schema::create('bonuses', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('distributeur_id');
        $table->decimal('montant_bonus', 15, 2); // 1% de chaque transaction
        $table->timestamps();

        $table->foreign('distributeur_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
};
