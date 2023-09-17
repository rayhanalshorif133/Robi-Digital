<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GetAOCToken;
use App\Models\GetAOCTokenLog;
use App\Models\GetAOCTokenResponse;
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
        $callback = env('APP_URL') . '/api/callback';
        $subscriptionID = $this->getSubscriptionID();
        $unSubURL = env('APP_URL') . '/api/unsubscribe/' . $subscriptionID;

        $subscriptionDuration = 2;
        if($service->validity == 'monthly'){
            $subscriptionDuration = 30;
        }elseif($service->validity == 'weekly'){
            $subscriptionDuration = 7;
        }

        $tokenInfos = [
            'apiKey' => $serviceProviderInfo->sp_api_key,
            'username' => $serviceProviderInfo->sp_username,
            'spTransID' => $this->getSPTransID(),
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
            'currency' => 'BDT',
            'amount' => '0.01',
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

            $redirectTo = $serviceProviderInfo->aoc_redirection_url . $response->data->aocToken;
            return Http::get($redirectTo);
        } else {
            return $this->respondWithError("Something went wrong!");
        }
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

    public function chargeWithTAC($aocTransID, $msisdn, $tac)
    {
        $serviceProviderInfo = ServiceProviderInfo::first();
        $parameters = [
            'apiKey' => $serviceProviderInfo->sp_api_key,
            'aocTransID' => $aocTransID,
            'username' => $serviceProviderInfo->sp_username,
            'msisdn' => $msisdn,
            'tac' => $tac,
            'transactionOperationStatus' => 'Charged',
            'totalAmountCharged' => 0.01,
        ];
        $url = $serviceProviderInfo->aoc_chargeWithTAC_url . '/api/chargeWithTAC';
        $response = Http::post($url);
        $response = json_decode($response);

        return $this->respondWithSuccess("Charge with TAC", $response);
    }

    
}
