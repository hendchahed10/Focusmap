<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotivationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('objectif_id')->constrained('objectifs')->onDelete('cascade');
            $table->text('contenu');
            $table->timestamp('moment')->nullable();
            $table->timestamps(); // <-- Ajoute created_at et updated_at automatiquement
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motivations');
    }
}
