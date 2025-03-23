<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GetAOCToken;
use App\Models\Service;
use App\Models\RenewSubscription;
use App\Models\ChargeLog;
use App\Models\ServiceProviderInfo;
use App\Models\Subscriber;
use App\Models\SubUnSubLog;
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
            return $this->respondWithError('spTransID and msisdn are required', $data);
        }


        if (substr($msisdn, 0, 3) != '+88') {
            $msisdn = '+88' . substr($msisdn, 2, 12);
        }






        $serviceProviderInfo = ServiceProviderInfo::first();
        $getAOCToken = GetAOCToken::where('spTransID', $spTransID)->first();
        $subscriber = Subscriber::where('spTransID', $spTransID)->first();



        if ($getAOCToken == null) {
            $data = [
                'spTransID' => $spTransID,
                'msisdn' => $msisdn,
            ];

            $renewSubscription = new RenewSubscription();
            $renewSubscription->old_spTransID = $spTransID;
            $renewSubscription->msisdn = $msisdn;
            $renewSubscription->response_message = 'spTransID not found';
            $renewSubscription->save();

            return $this->respondWithError('spTransID not found', $data);
        }


        $NDTVController = new NDTVController();
        $spTransID = $NDTVController->getSPTransID();


        $parameters = [
            'apiKey' => $getAOCToken->apiKey,
            'username' => $getAOCToken->username,
            'spTransID' => $spTransID,
            'amount' => $getAOCToken->amount,
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
        $GET_MSISDN = $msisdn;
        $url = $serviceProviderInfo->aoc_endpoint_url . '/renewSubscription';
        $response = Http::post($url, $parameters);
        $response = json_decode($response);


        $subscriptionName = $getAOCToken->subscriptionName;
        $subscriptionName = substr($subscriptionName, 5); // Start from the 6th character
        $subscriptionName = strtolower($subscriptionName); // Convert to lowercase




        $keyword = $subscriber->keyword;

        $service = Service::where('keyword', 'like', '%' . $keyword . '%')->first();

        $renewSubscription = new RenewSubscription();
        $renewSubscription->msisdn = $GET_MSISDN;
        $renewSubscription->amount = $service->charge;
        $renewSubscription->old_spTransID = $getAOCToken->spTransID;
        $renewSubscription->new_spTransID = $spTransID;
        $renewSubscription->sent_raw_parameter = json_encode($parameters);
        $renewSubscription->subscription_id = $getAOCToken->subscriptionID;
        $renewSubscription->url = $url;
        $renewSubscription->keyword = $service->keyword;
        $renewSubscription->response = json_encode($response);
        $renewSubscription->response_data = json_encode($response->data);
        $renewSubscription->response_message = $response->data->errorMessage;
        $renewSubscription->response_code = $response->data->errorCode;
        $renewSubscription->save();

        // re new spTransID
        $getAOCToken->spTransID = $spTransID;
        $getAOCToken->save();

        $subscriber->spTransID = $spTransID;

        if(!$subscriber->msisdn){
            $subscriber->msisdn = $GET_MSISDN;
        }
        
        $subscriber->save();




        if ($response->data->errorCode != 00) {
            $data = [
                'errorCode' => $response->data->errorCode,
                'spTransID' => $spTransID,
                'msisdn' => $msisdn,
                'subscriptionID' => $getAOCToken->subscriptionID
            ];

            return $this->respondWithError($response->data->errorMessage, $data);
        } else {
            $chargeLog  = new ChargeLog();
            $chargeLog->spTransID = $spTransID;
            $chargeLog->msisdn = $GET_MSISDN;
            $chargeLog->keyword = $keyword;
            $chargeLog->amount = $service->charge;
            $chargeLog->type = 'renew';
            $chargeLog->charge_date = date('Y-m-d');  // Make sure this matches the date format in the database
            $chargeLog->save();
        }
        return $this->respondWithSuccess('Subscription Renewed Successfully', $response->data);

        
    }

    // cancelSubscription
    public function cancelSubscription($spTransID = null, $msisdn = null)
    {
        try {


            if ($spTransID == null) {
                return $this->respondWithError('Sptrans ID is required');
            }



            // $spTransID = null, $msisdn = null
            $getSubs = Subscriber::select()
                ->where('spTransID', $spTransID)
                ->where('status', 1)
                ->orderBy('created_at', 'DESC')
                ->first();



            if (!$getSubs) {
                $data = [
                    'spTransID' => $spTransID,
                ];
                return $this->respondWithSuccess('Subscription already cancelled. Please re-subscribe.', $data);
            }


            $serviceProviderInfo = ServiceProviderInfo::first();
            $getAOCToken = GetAOCToken::where('spTransID', $spTransID)->first();
            $parameters = [
                'username' => $getAOCToken->username,
                'apiKey' => $getAOCToken->apiKey,
                'spTransID' => $spTransID,
                'operator' => 'Robi',
                'msisdn' => $getSubs->msisdn,
                'subscriptionID' => $getAOCToken->subscriptionID,
            ];
            $url = $serviceProviderInfo->aoc_endpoint_url . '/cancelSubscription';
            $response = Http::post($url, $parameters);
            $response = json_decode($response);
            $getAOCToken->isSubscription = 0;
            $getAOCToken->save();

            if ($response->data->errorCode != 00) {
                $data = [
                    'spTransID' => $spTransID,
                    'msisdn' => $subs->msisdn,
                ];
                return $this->respondWithSuccess('Subscription already cancelled. Please re-subscribe.', $data);
            }


            $getSubs->status = 0;
            $getSubs->subs_date = null;
            $getSubs->unsubs_date = date('Y-m-d H:i:s');
            $getSubs->save();



            $subUn = new SubUnSubLog();
            $subUn->msisdn = $getSubs->msisdn;
            $subUn->keyword = $getSubs->keyword;
            $subUn->status = $getSubs->status;
            $subUn->subscriptionID = $getSubs->subscriptionID;
            $subUn->flag = 'unsub';
            $subUn->opt_date = date('Y-m-d');
            $subUn->opt_time = date('H:i:s');
            $subUn->save();

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
            return $this->respondWithError('spTransID and msisdn are required', $data);
        }

        $serviceProviderInfo = ServiceProviderInfo::first();
        $getAOCToken = GetAOCToken::where('spTransID', $spTransID)->first();
        $parameters = [
            'username' => $getAOCToken->username,
            'apiKey' => $getAOCToken->apiKey,
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
