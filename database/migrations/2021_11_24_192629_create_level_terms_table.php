<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('level_id')->unsigned();
            $table->integer('step');
            $table->integer('to_level');
            $table->integer('amount');
            $table->integer('coin');
            $table->integer('count');
            $table->integer('status');
            $table->timestamps();
            $table->foreign('level_id')->references('id')->on('levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_terms');
    }
}
