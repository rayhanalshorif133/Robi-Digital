<?php

use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_key');
            $table->string('name');
            $table->string('type')->enum('subscription', 'on-demand')->default('subscription');
            $table->string('validity')->enum('daily', 'weekly', 'monthly')->default('daily');
            $table->timestamps();
        });

        $dbSeeder = new DatabaseSeeder();
        $dbSeeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
