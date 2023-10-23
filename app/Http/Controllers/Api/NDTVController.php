<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChargeStatusResponse;
use App\Models\ChargeStatusResponseRaw;
use App\Models\GetAOCToken;
use App\Models\GetAOCTokenLog;
use App\Models\GetAOCTokenResponse;
use App\Models\HitLog;
use App\Models\Service;
use App\Models\ServiceProviderInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class NDTVController extends Controller
{
    public function getToken(Request $request, $keyword = null)
    {


        $request->method() == 'POST' ? $keyword = $request->keyword : $keyword = $keyword;
        $service = Service::where('keyword', $keyword)->first();
        $serviceProviderInfo = ServiceProviderInfo::first();

        // basedURL
        $callback = env('APP_URL') . '/callback';
        $subscriptionID = $this->getSubscriptionID();
        $spTransID = $this->getSPTransID();
        $unSubURL = env('APP_URL') . '/api/cancelSubscription/' . $spTransID . '/+8801818401065';

        $subscriptionDuration = 2;
        if($service->validity == 'monthly'){
            $subscriptionDuration = 30;
        }elseif($service->validity == 'weekly'){
            $subscriptionDuration = 7;
        }

        $tokenInfos = [
            'apiKey' => $serviceProviderInfo->sp_api_key,
            'username' => $serviceProviderInfo->sp_username,
            'spTransID' => $spTransID,
            'description' => $service->name,
            'onBehalfOf' => $service->on_behalf_of,
            'purchaseCategoryCode' => $service->purchase_category_code,
            'refundPurchaseCategoryCode' => $service->purchase_category_code,
            'referenceCode' => $service->reference_code,
            'channel' => $service->channel,
            'isSubscription' => true,
            'subscriptionID' => $subscriptionID,
            'subscriptionName' => $service->type,
            'subscriptionDuration' => $subscriptionDuration,
            'unSubURL' => $unSubURL,
            'callbackURL' => $callback,
            'msisdn' => '',
            'currency' => 'BDT',
            'amount' => $service->charge,
            'operator' => 'Robi',
            'taxAmount' => '0.1',
            'contactInfo' => 'tushar@b2m-tech.com',
        ];

        $response = Http::post($serviceProviderInfo->aoc_getAOCToken_url, $tokenInfos);
        $response = json_decode($response);
        if ($response) {
            $getAOCToken = GetAOCToken::create($tokenInfos);

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


            $sendData = [
                'aocTransID' => $response->data->aocTransID,
                'spTransID' => $getAOCToken->spTransID,
                'redirectURL' => env('APP_URL') . '/api/redirect/' . $response->data->aocTransID,
            ];

            // HitLog
            $hitLog = new HitLog();
            $hitLog->get_aoc_token_id = $getAOCToken->id;
            $hitLog->keyword = $keyword;
            $hitLog->date = date('Y-m-d');
            $hitLog->time = date('H:i:s');
            $hitLog->postBack_send_data = json_encode($sendData);
            $hitLog->save();

           

            return $this->respondWithSuccess("Token successfully fetched", $sendData);
            
        } else {
            return $this->respondWithError("Something went wrong!");
        }
    }
    
    public function redirect($aocTransID){
        $serviceProviderInfo = ServiceProviderInfo::first();
        $getAOCTokenResponse = GetAOCTokenResponse::where('aocTransID', $aocTransID)->first();
        $redirectTo = $serviceProviderInfo->aoc_redirection_url . $getAOCTokenResponse->aocToken;
        return redirect($redirectTo);
    }

    public function getSubscriptionID()
    {
        $getSubscriptionID = 'B2MSub_' . $this->generateRandomString(6);
        $hasSubscriptionID = GetAOCToken::where('subscriptionID', $getSubscriptionID)->first();
        if ($hasSubscriptionID) {
            $this->getSubscriptionID();
        }
        return $getSubscriptionID;
    }
    
    public function getSPTransID()
    {
        $getSPTransID = 'B2M' . $this->generateRandomString(6);
        $hasSPTransID = GetAOCToken::where('spTransID', $getSPTransID)->first();
        if ($hasSPTransID) {
            $this->getSPTransID();
        }
        return $getSPTransID;
    }

    public function chargeStatus($aocTransID)
    {
        $serviceProviderInfo = ServiceProviderInfo::first();
        $parameters = [
            'apiKey' => $serviceProviderInfo->sp_api_key,
            'aocTransID' => $aocTransID,
            'username' => $serviceProviderInfo->sp_username
        ];
        $url = $serviceProviderInfo->aoc_endpoint_url . '/chargeStatus';
        $response = Http::post($url,$parameters);
        $response = json_decode($response);

        if($response->data->errorCode != 00){
            return $this->respondWithError("Charge status", $response->data->errorMessage);
        }
        $chargeStatus = new ChargeStatusResponse();
        $chargeStatus->aocTransID = $aocTransID;
        $chargeStatus->chargeMode = $response->data->chargeMode;
        $chargeStatus->transactionOperationStatus = $response->data->transactionOperationStatus;
        $chargeStatus->totalAmountCharged = $response->data->totalAmountCharged;
        $chargeStatus->clientCorrelator = $response->data->clientCorrelator;
        $chargeStatus->msisdn = $response->data->msisdn;
        $chargeStatus->errorCode = $response->data->errorCode;
        $chargeStatus->errorMessage = $response->data->errorMessage;
        $chargeStatus->save();

        ChargeStatusResponseRaw::create([
            'charge_status_response_id' => $chargeStatus->id,
            'data' => json_encode($response->data),
        ]);
        return $this->respondWithSuccess("Charge status", $response->data);
    }

    
}
