<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('callbacks', function (Blueprint $table) {
            $table->id();
            $table->string('transactionOperationStatus')->nullable();
            $table->string('totalAmountCharged')->nullable();
            $table->string('msisdn')->nullable();
            $table->string('aocTransID')->nullable();
            $table->string('clientCorrelator')->nullable();
            $table->string('chargeMode')->nullable();
            $table->string('expiryDate')->nullable();
            $table->string('subscriptionID')->nullable();
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
        Schema::dropIfExists('callbacks');
    }
}
