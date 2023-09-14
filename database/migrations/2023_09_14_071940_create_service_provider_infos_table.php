<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\DatabaseSeeder;

class CreateServiceProviderInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_provider_infos', function (Blueprint $table) {
            $table->id();
            $table->string('aoc_endpoint_url');
            $table->string('aoc_redirection_url');
            $table->string('aoc_getAOCToken_url');
            $table->string('sp_username');
            $table->string('sp_api_key');
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
        Schema::dropIfExists('service_provider_infos');
    }
}
