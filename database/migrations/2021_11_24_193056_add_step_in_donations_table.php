<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepInDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->integer('step')->default(0)->after('json_data');
            $table->integer('coin')->default(0)->after('step');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donations', function (Blueprint $table) {
            //
        });
    }
}
