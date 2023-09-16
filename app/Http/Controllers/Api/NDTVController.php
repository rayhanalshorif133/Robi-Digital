<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GetAOCToken;
use App\Models\GetAOCTokenLog;
use App\Models\GetAOCTokenResponse;
use App\Models\ServiceProviderInfo;
use Illuminate\Support\Facades\Http;


class NDTVController extends Controller
{
    public function getToken()
    {

        $serviceProviderInfo = ServiceProviderInfo::first();
        $tokenInfos = [
            'apiKey' => $serviceProviderInfo->sp_api_key,
            'username' => $serviceProviderInfo->sp_username,
            'spTransID' => $this->getSPTransID(),
            'description' => 'sdacsdc',
            'currency' => 'BDT',
            'amount' => '0.01',
            'onBehalfOf' => 'Apigate_AOC-B2M',
            'purchaseCategoryCode' => 'Game',
            'refundPurchaseCategoryCode' => 'Game',
            'referenceCode' => 'Game',
            'channel' => 'WEB',
            'operator' => 'Robi',
            'taxAmount' => '0.1',
            'callbackURL' => 'https://www.google.com',
            'contactInfo' => 'rayhan@b2m-tech.com',
        ];

        $getAOCToken = GetAOCToken::create($tokenInfos);
        if($getAOCToken){
            $response = Http::post($serviceProviderInfo->aoc_getAOCToken_url, $tokenInfos);
            $response = json_decode($response);

            $aocTokenResponse = GetAOCTokenResponse::create([
                'get_aoc_token_id' => $getAOCToken->id,
                'aocToken' => $response->data->aocToken,
                'aocTransID' => $response->data->aocTransID,
                'errorCode' => $response->data->errorCode,
                'errorMessage' => $response->data->errorMessage,
            ]);

            GetAOCTokenLog::create([
                'get_aoc_token_id' => $getAOCToken->id,
                'get_aoc_token_response_id' => $aocTokenResponse->id,
                'request_raw_data' => json_encode($tokenInfos),
                'response_raw_data' => json_encode($response->data),
            ]);

            return $this->respondWithSuccess("ok", $response->data);
        }else{
            return $this->respondWithError("Something went wrong!");
        }
    }

    public function getSPTransID(){
        $getSPTransID = 'B2M' . $this->generateRandomString(6);
        $hasSPTransID = GetAOCToken::where('spTransID', $getSPTransID)->first();
        if($hasSPTransID){ 
            $this->getSPTransID();
        }
        return $getSPTransID;
    }
}
