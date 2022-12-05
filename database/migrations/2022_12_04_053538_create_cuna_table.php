<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuna', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->text('description');
            $table->string('sensor1')->nullable();
            $table->string('sensor2')->nullable();
            $table->string('sensor3')->nullable();
            $table->string('sensor4')->nullable();
            $table->string('sensor5')->nullable();
            $table->string('sensor6')->nullable();
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
        Schema::dropIfExists('cuna');
    }
};
