<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetAOCTokenResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('get_a_o_c_token_responses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('get_aoc_token_id')->unsigned();
            $table->string('aocToken')->nullable();
            $table->string('aocTransID')->nullable();
            $table->string('errorCode')->nullable();
            $table->string('errorMessage')->nullable();
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
        Schema::dropIfExists('get_a_o_c_token_responses');
    }
}
