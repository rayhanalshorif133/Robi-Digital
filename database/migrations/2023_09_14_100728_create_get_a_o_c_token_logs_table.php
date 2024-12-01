<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetAOCTokenLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('get_a_o_c_token_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('get_aoc_token_id')->unsigned();
            $table->bigInteger('get_aoc_token_response_id')->unsigned();
            $table->json('request_raw_data')->nullable();
            $table->json('response_raw_data')->nullable();
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
        Schema::dropIfExists('get_a_o_c_token_logs');
    }
}
