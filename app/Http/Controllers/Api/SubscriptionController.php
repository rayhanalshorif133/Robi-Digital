<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GetAOCToken;
use App\Models\ServiceProviderInfo;
use Illuminate\Support\Facades\Http;


class SubscriptionController extends Controller
{
    // renewSubscription
    public function renewSubscription($spTransID = null, $msisdn = null)
    {


        if ($spTransID == null || $msisdn == null) {

            $data = [
                'spTransID' => 'required',
                'msisdn' => 'required',
            ];
            return $this->respondWithError('spTransID and msisdn are required',$data);
        }


        $serviceProviderInfo = ServiceProviderInfo::first();
        $getAOCToken = GetAOCToken::where('spTransID', $spTransID)->first();

        $NDTVController = new NDTVController();
        $spTransID = $NDTVController->getSPTransID();

        $parameters = [
            'apiKey' => $serviceProviderInfo->sp_api_key,
            'username' => $serviceProviderInfo->sp_username,
            'spTransID' => $spTransID,
            'amount' => $serviceProviderInfo->sp_amount,
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

        if($response->data->errorCode != 00){
            $data = [
                'spTransID' => $spTransID,
                'msisdn' => $msisdn,
                'subscriptionID' => $getAOCToken->subscriptionID
            ];
             
            return $this->respondWithSuccess('Subscription already renewed. Please try again later.',$data);
        }

        $getAOCToken->spTransID = $spTransID;
        $getAOCToken->save();
        return $this->respondWithSuccess('Subscription Renewed Successfully', $response->data);
    }

    // cancelSubscription
    public function cancelSubscription($spTransID = null, $msisdn = null)
    {
        try {

            $redirectPortalUrl = 'https://yoga.ndtvdcb.com/web/simato/lifestyle/list-videos.php?cat=Yoga&key=yogaSutra';

            if ($spTransID == null || $msisdn == null) {
                return redirect($redirectPortalUrl);
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
            if($response->data->errorCode != 00){
                $data = [
                    'spTransID' => $spTransID,
                    'msisdn' => $msisdn,
                ];
                return $this->respondWithSuccess('Subscription already cancelled. Please re-subscribe.',$data);
                // return redirect($redirectPortalUrl);
            }
            // return redirect($redirectPortalUrl);
            return $this->respondWithSuccess('Subscription Cancelled Successfully', $response->data);
        } catch (\Throwable $th) {
            return $this->respondWithError('Something went wrong', $th->getMessage());
        }
    }

    // subscriptionStatus
    public function subscriptionStatus($spTransID = null, $msisdn = null)
    {

        if ($spTransID == null || $msisdn == null) {
            $data = [
                'spTransID' => 'required',
                'msisdn' => 'required',
            ];
            return $this->respondWithError('spTransID and msisdn are required',$data);
        }

        $serviceProviderInfo = ServiceProviderInfo::first();
        $getAOCToken = GetAOCToken::where('spTransID', $spTransID)->first();
        $parameters = [
            'username' => $serviceProviderInfo->sp_username,
            'apiKey' => $serviceProviderInfo->sp_api_key,
            'msisdn' => $msisdn,
            'operator' => 'Robi',
            'subscriptionID' =>  $getAOCToken->subscriptionID,
        ];
        $url = $serviceProviderInfo->aoc_endpoint_url . '/subscriptionStatus';
        $response = Http::post($url, $parameters);
        $response = json_decode($response);


        return $this->respondWithSuccess('Subscription Status', $response->data);
    }
}
