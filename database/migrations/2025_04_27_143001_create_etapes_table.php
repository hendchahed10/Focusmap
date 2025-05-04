<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtapesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etapes', function (Blueprint $table) {
            $table->id(); 
            $table->string('titre', 255);
            $table->string('description', 255)->nullable();
            $table->boolean('completed');
            $table->unsignedBigInteger('objectif_id')->nullable();
            $table->foreign('objectif_id')
                  ->references('id')
                  ->on('objectif') 
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etapes');
    }
}
