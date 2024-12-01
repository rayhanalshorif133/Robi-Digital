<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargeStatusResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charge_status_responses', function (Blueprint $table) {
            $table->id();
            $table->string('aocTransID')->nullable();
            $table->string('chargeMode')->nullable();
            $table->string('transactionOperationStatus')->nullable();
            $table->string('totalAmountCharged')->nullable();
            $table->string('clientCorrelator')->nullable();
            $table->string('msisdn')->nullable();
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
        Schema::dropIfExists('charge_status_responses');
    }
}
