<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use App\Models\HitLog;
use App\Models\Service;
use App\Models\GetAOCToken;
use App\Models\GetAOCTokenResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Subscriber;
use App\Models\SubUnSubLog;


class CallBackController extends Controller
{
    // https://rd.b2mwap.com/callback/5?aocTransID=TR6639069258

    public function callback(Request $request, $subscription_id)
    {
        try {



            $data = $request->all();

            $callback = new Callback();
            $callback->aocTransID = $data['aocTransID'];
            $callback->raw_data = json_encode($request->all());
            $callback->save();


            // Subsription
            $GET_SUBS = Subscriber::select()->where('id',$subscription_id)->first();
            $GET_SUBS->aocTransID = $request->aocTransID;
            $GET_SUBS->subs_date = date('Y-m-d H:i:s');

            $service = Service::where('keyword', $GET_SUBS->keyword)->first();
            $getAOCToken = GetAOCToken::where('spTransID', $GET_SUBS->spTransID)->first();
            
            
            $redirect = $service->redirect_url . "?aocTransID=" . $request->aocTransID;

            // GET and SET MSISDN
            $url = url('api/chargeStatus/' . $request->aocTransID);
            $res = Http::get($url);
            $res = $res->json();
            
           
            if($res['code'] == '00'){
                $msisdn = $res['data']['msisdn'];
                $charged = $res['data']['totalAmountCharged'];
                $GET_SUBS->charge = $charged;

                if($msisdn){
                    $getAOCToken->msisdn = $msisdn;                    
                    $getAOCToken->isSubscription = true;
                }
            }else{
                    $getAOCToken->msisdn = null;                    
                    $getAOCToken->isSubscription = false;                    
            }


             // check Subscription
            $urlSubscriptionCheckStatus = url('api/subscriptionStatus/' . $GET_SUBS->spTransID . '/' . $GET_SUBS->msisdn);
            $response = Http::get($urlSubscriptionCheckStatus);
            $response = $response->json();

            if($response['status'] == true && $response['data']['status'] == 'subscribed'){
                $GET_SUBS->status = 1;
                $GET_SUBS->msisdn = $response['data']['msisdn'];
                $getAOCToken->msisdn = $response['data']['msisdn'];  
                $GET_SUBS->flag = 'Success from API';
            }else{
                $GET_SUBS->status = 0;
                $GET_SUBS->flag = 'Failed to Charge (API)';
            }
            $GET_SUBS->save();
            $getAOCToken->save();

 

            // sub and unSub logs
            $subUn = new SubUnSubLog();
            $subUn->msisdn = $GET_SUBS->msisdn;
            $subUn->keyword = $GET_SUBS->keyword;
            $subUn->status = $GET_SUBS->status;
            $subUn->subscriptionID = $GET_SUBS->subscriptionID;
            $subUn->flag = 'sub';
            $subUn->opt_date = date('Y-m-d');
            $subUn->opt_time = date('H:i:s');
            $subUn->save();


            return redirect($redirect);
        } catch (\Throwable $th) {
            return $this->respondWithError('Server Error', $th->getMessage(), 500);
        }
    }
}
