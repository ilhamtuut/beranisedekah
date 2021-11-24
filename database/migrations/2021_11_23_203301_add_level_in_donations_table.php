<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLevelInDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->integer('from_level')->default(0)->after('json_data');
            $table->integer('to_level')->default(0)->after('from_level');
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
