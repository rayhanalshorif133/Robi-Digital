<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function __construct()
    {
        $this->run();
    }

    public function run()
    {

            $service = new Service();
            $service->name = 'NDTV Yoga';
            $service->type = 'subscription';
            $service->keyword = 'NY';
            $service->validity = 'daily';
            $service->purchase_category_code = 'Game';
            $service->reference_code = 'Game';
            $service->channel = 'WEB';
            $service->on_behalf_of = 'Apigate_AOC-B2M';
            $service->save();

    }

    function generateRandomString($length = 25) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
