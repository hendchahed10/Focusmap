<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amities', function (Blueprint $table) {
            $table->string('login1', 50);
            $table->string('login2', 50);
            $table->primary(['login1', 'login2']);
            $table->foreign('login1')
                  ->references('login')
                  ->on('utilisateur')
                  ->onDelete('cascade');
            $table->foreign('login2')
                  ->references('login')
                  ->on('utilisateur')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amities');
    }
}
