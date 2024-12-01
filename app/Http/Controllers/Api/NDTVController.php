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
use App\Models\Subscriber;
use App\Models\ServiceProviderInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class NDTVController extends Controller
{
    public function getToken(Request $request, $keyword = null)
    {



        $request->method() == 'POST' ? $keyword = $request->keyword : $keyword = $keyword;

        if($keyword == null){
            return  $this->respondWithError("Keyword are required", [
                'keyword' => 'required',
            ]);
        }

        $service = Service::where('keyword', $keyword)->first();
        $serviceProviderInfo = ServiceProviderInfo::first();


       

        // basedURL
        $subscriptionID = $this->getSubscriptionID();
        $spTransID = $this->getSPTransID();
       

        
        
        
        
        
        
        
        $getAOCToken = new GetAOCToken();
        $getAOCToken->apiKey = $service->api_key;
        $getAOCToken->username = $service->username;
        $getAOCToken->keyword = $service->keyword;
        $getAOCToken->spTransID = $spTransID;
        $getAOCToken->channel = $service->channel;
        $getAOCToken->description = $service->name;
        $getAOCToken->onBehalfOf = $service->on_behalf_of;
        $getAOCToken->purchaseCategoryCode = $service->purchase_category_code;
        $getAOCToken->refundPurchaseCategoryCode = $service->purchase_category_code;
        $getAOCToken->referenceCode = $service->reference_code;
        $getAOCToken->isSubscription = true;
        $getAOCToken->subscriptionID = $subscriptionID;
        $getAOCToken->subscriptionName = $service->subscription_name;
        $getAOCToken->subscriptionDuration = $service->subs_duration;
        $getAOCToken->unSubURL = $service->un_sub_url;
        $getAOCToken->save();
        
        $callback = url('callback/' . $getAOCToken->id);
        
        $getAOCToken->callbackURL = $callback;
        $getAOCToken->currency = 'BDT';
        $getAOCToken->amount = $service->charge;
        $getAOCToken->operator = 'Robi';
        $getAOCToken->taxAmount = '0.1';
        $getAOCToken->contactInfo = 'cservice@b2m-tech.com';
        $getAOCToken->save();

        $tokenInfos = [
            'apiKey' => $service->api_key,
            'username' => $service->username,
            'spTransID' => $spTransID,
            'description' => $service->name,
            'onBehalfOf' => $service->on_behalf_of,
            'purchaseCategoryCode' => $service->purchase_category_code,
            'refundPurchaseCategoryCode' => $service->purchase_category_code,
            'referenceCode' => $service->reference_code,
            'channel' => $service->channel,
            'isSubscription' => true,
            'subscriptionID' => $subscriptionID,
            'subscriptionName' => $service->subscription_name,
            'subscriptionDuration' => $service->subs_duration,
            'unSubURL' => $service->un_sub_url,
            'callbackURL' => $callback,
            'msisdn' => '',
            'currency' => 'BDT',
            'amount' => $service->charge,
            'operator' => 'Robi',
            'taxAmount' => '0.1',
            'contactInfo' => 'cservice@b2m-tech.com',
        ];


        $response = Http::post($serviceProviderInfo->aoc_getAOCToken_url, $tokenInfos);
        $response = json_decode($response);
        if ($response) {
            
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
                'redirectURL' => url('/api/redirect/' . $response->data->aocTransID),
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
        $getAOCTokenResponse = GetAOCTokenResponse::where('aocTransID', $aocTransID)->first();
        $getAOCToken = GetAOCToken::where('id', $getAOCTokenResponse->get_aoc_token_id)->first();
        $parameters = [
            'apiKey' => $getAOCToken->apiKey,
            'aocTransID' => $aocTransID,
            'username' => $getAOCToken->username
        ];
        $url = $serviceProviderInfo->aoc_endpoint_url . '/chargeStatus';
        $response = Http::post($url,$parameters);
        $response = json_decode($response);

        
        if($response->data->errorCode != 00){
            $erroe_msg = $response->data->errorMessage;
            $substringToFind = "Service is deactivated";
    
            if (strpos($erroe_msg, $substringToFind) !== false) {
                $erroe_msg = 'You cannot subscribe at this moment, your number is blacklisted.';
            }
    
            return response()->json([
                'status'   => false,
                'errors'  => true,
                'message'  => "Charge status",
                'code' => $response->data->errorCode,
                'data'     => $erroe_msg
            ], 203);
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


        $response->data->spTransID = $getAOCToken->spTransID;


        return response()->json([
            'status'   => true,
            'errors'  => false,
            'message'  => 'Charge status',
            'code' => $response->data->errorCode,
            'data'     => $response->data
        ], 200);
    }




    
}
