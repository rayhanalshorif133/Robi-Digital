<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetAOCTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('get_a_o_c_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('apiKey')->nullable();
            $table->string('username')->nullable();
            $table->string('spTransID')->nullable();
            $table->string('description')->nullable();
            $table->string('currency')->nullable();
            $table->string('amount')->nullable();
            $table->string('onBehalfOf')->nullable();
            $table->string('purchaseCategoryCode')->nullable();
            $table->string('refundPurchaseCategoryCode')->nullable();
            $table->string('referenceCode')->nullable();
            $table->string('channel')->nullable();
            $table->string('operator')->nullable();
            $table->string('taxAmount')->nullable();
            $table->string('callbackURL')->nullable();
            $table->string('contactInfo')->nullable();
            $table->string('contentURL')->nullable();
            $table->string('isSubscription')->nullable();
            $table->string('subscriptionID')->nullable();
            $table->string('subscriptionName')->nullable();
            $table->string('subscriptionDuration')->nullable();
            $table->string('unSubURL')->nullable();
            $table->string('isWallet')->nullable();
            $table->string('isWebDeepLink')->nullable();
            $table->string('callbackAppDeepLink')->nullable();
            $table->string('msisdn')->nullable();
            $table->string('email')->nullable();
            $table->string('isMobileAppAPI')->nullable();
            $table->string('tacMSISDN')->nullable();
            $table->string('productId')->nullable();
            $table->string('renewalCharge')->nullable();
            $table->string('requireOTP')->nullable();
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
        Schema::dropIfExists('get_a_o_c_tokens');
    }
}
