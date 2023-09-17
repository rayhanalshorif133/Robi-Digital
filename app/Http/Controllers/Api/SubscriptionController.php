<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GetAOCToken;
use App\Models\GetAOCTokenResponse;
use App\Models\ServiceProviderInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\isNull;

class SubscriptionController extends Controller
{
    // renewSubscription
    public function renewSubscription($spTransID = 0, $msisdn = 0)
    {


        if ($spTransID == 0 || $msisdn == 0) {
            return $this->respondWithError('spTransID and msisdn are required');
        }


        $serviceProviderInfo = ServiceProviderInfo::first();
        $getAOCToken = GetAOCToken::where('spTransID', $spTransID)->first();

        $parameters = [
            'apiKey' => $serviceProviderInfo->sp_api_key,
            'username' => $serviceProviderInfo->sp_username,
            'spTransID' => $spTransID,
            'description' => $getAOCToken->description,
            'currency' => $getAOCToken->currency,
            'onBehalfOf' => $getAOCToken->onBehalfOf,
            'purchaseCategoryCode' => $getAOCToken->purchaseCategoryCode,
            'referenceCode' => $getAOCToken->referenceCode,
            'channel' => $getAOCToken->channel,
            'taxAmount' => $getAOCToken->taxAmount,
            'msisdn' => $msisdn,
            'operator' => $getAOCToken->operator,
            'subscriptionID' => $getAOCToken->subscriptionID,
            'unSubURL' => $getAOCToken->unSubURL,
            'contactInfo' => $getAOCToken->contactInfo,
        ];
        $url = $serviceProviderInfo->aoc_endpoint_url . '/renewSubscription';
        $response = Http::post($url, $parameters);
        $response = json_decode($response);
        return $this->respondWithSuccess('Subscription Renewed Successfully', $response);
    }

    // cancelSubscription
    public function cancelSubscription($spTransID = 0, $msisdn = 0)
    {
        try {
            if ($spTransID == 0 || $msisdn == 0) {
                return $this->respondWithError('spTransID and msisdn are required');
            }
            $serviceProviderInfo = ServiceProviderInfo::first();
            $getAOCToken = GetAOCToken::where('spTransID', $spTransID)->first();
            $parameters = [
                'username' => $serviceProviderInfo->sp_username,
                'apiKey' => $serviceProviderInfo->sp_api_key,
                'spTransID' => $spTransID,
                'operator' => 'Robi',
                'msisdn' => $msisdn,
                'subscriptionID' => $getAOCToken->subscriptionID,
            ];
            $url = $serviceProviderInfo->aoc_endpoint_url . '/cancelSubscription';
            $response = Http::post($url, $parameters);
            $response = json_decode($response);
            return $this->respondWithSuccess('Subscription cancelled Successfully', $response);
        } catch (\Throwable $th) {
            return $this->respondWithError('Something went wrong', $th->getMessage());
        }
    }

    // subscriptionStatus
    public function subscriptionStatus($subscriptionID = 0, $msisdn = 0)
    {

        if ($subscriptionID == 0 || $msisdn == 0) {
            return $this->respondWithError('subscriptionID and msisdn are required');
        }

        $serviceProviderInfo = ServiceProviderInfo::first();
        $parameters = [
            'username' => $serviceProviderInfo->sp_username,
            'apiKey' => $serviceProviderInfo->sp_api_key,
            'msisdn' => $msisdn,
            'operator' => 'Robi',
            'subscriptionID' => $subscriptionID,
        ];
        $url = $serviceProviderInfo->aoc_endpoint_url . '/subscriptionStatus';
        $response = Http::post($url, $parameters);
        $response = json_decode($response);
        return $this->respondWithSuccess('Subscription Status', $response);
    }
}
