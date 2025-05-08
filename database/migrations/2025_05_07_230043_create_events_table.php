<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            
            // Clé étrangère vers utilisateurs (string)
            $table->string('utilisateur_login');
            
            // Clé étrangère  vers objectifs
            $table->foreignId('objectif_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->string('color')->default('#3788d8');
            $table->timestamps();

            // Contrainte de clé étrangère pour utilisateur
            $table->foreign('utilisateur_login')
                  ->references('login')
                  ->on('utilisateurs')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['utilisateur_login']);
            $table->dropForeign(['objectif_id']);
        });
        
        Schema::dropIfExists('events');
    }
};