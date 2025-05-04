<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objectifs', function (Blueprint $table) {
            $table->id(); 
            $table->string('titre', 255);
            $table->string('description', 255)->nullable();
            $table->date('deadline')->nullable();
            $table->enum('visibilité', ['privé', 'amis', 'public']);
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->string('utilisateur_login', 50)->nullable();
            $table->foreign('utilisateur_login')
                  ->references('login')
                  ->on('utilisateur')
                  ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objectifs');
    }
}
