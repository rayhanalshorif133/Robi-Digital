<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHitLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('get_aoc_token_id')->constrained('get_a_o_c_tokens');
            $table->string('keyword');
            $table->text('postBack_send_data');
            $table->string('date');
            $table->string('time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hit_logs');
    }
}
