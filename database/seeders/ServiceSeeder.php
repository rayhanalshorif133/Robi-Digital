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

            // for ($i=0; $i < 30; $i++) { 
            //     $service = new Service();
            //     $service->name = 'ND_'. $i .'_TV_' . $this->generateRandomString(4);
            //     $service->type = 'subscription';
            //     $service->keyword = $this->getKeyword();
            //     $service->validity = 'daily';
            //     $service->purchase_category_code = 'Game';
            //     $service->reference_code = 'Game';
            //     $service->channel = 'WEB';
            //     $service->on_behalf_of = 'Apigate_AOC-B2M';
            //     $service->save();
            // }

    }

    public function getKeyword()
    {
        $keyword = $this->generateRandomString(10);
        $service = Service::where('keyword', $keyword)->first();
        if($service){
            $this->getKeyword();
        }
        return $keyword;
    }

    public function generateRandomString($length = 25) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
