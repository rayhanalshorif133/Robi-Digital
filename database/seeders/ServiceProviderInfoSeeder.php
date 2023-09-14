<?php

namespace Database\Seeders;

use App\Models\ServiceProviderInfo;
use Illuminate\Database\Seeder;

class ServiceProviderInfoSeeder extends Seeder
{
    public function __construct()
    {
        $this->run();
    }


    public function run()
    {
        $serviceProviderInfo = new ServiceProviderInfo();
        $serviceProviderInfo->aoc_endpoint_url = 'https://sandbox.mife-aoc.com/api';
        $serviceProviderInfo->aoc_redirection_url = 'http://sandbox.mife-aoc.com/api/aoc?aocToken=';
        $serviceProviderInfo->sp_username = 'B2MYoga';
        $serviceProviderInfo->sp_api_key = 'dswdUAW5ZIDZZ7LC';
        $serviceProviderInfo->save();
    }
}
