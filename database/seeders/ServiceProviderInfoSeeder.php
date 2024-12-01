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
        $serviceProviderInfo->aoc_endpoint_url = 'https://robi-prod.mife-aoc.com/api';
        $serviceProviderInfo->aoc_redirection_url = 'http://robi.mife-aoc.com/api/aoc?aocToken=';
        $serviceProviderInfo->aoc_getAOCToken_url = 'https://robi-prod.mife-aoc.com/api/getAOCToken';
        $serviceProviderInfo->sp_username = 'NDTVYoga';
        $serviceProviderInfo->sp_api_key = 'Y9ovgzyXlsmLWrjA';
        $serviceProviderInfo->save();
    }
}
