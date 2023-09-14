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

        for ($i = 0; $i < 20; $i++) {
            $service = new Service();
            $service->service_key = $this->generateRandomString(5);
            $service->name = 'Service ' . $i;
            $service->type = 'subscription';
            $service->validity = 'daily';
            $service->save();
        }

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
