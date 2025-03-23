<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use App\Models\HitLog;
use App\Models\Service;
use App\Models\GetAOCToken;
use App\Models\GetAOCTokenResponse;
use App\Models\ChargeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Subscriber;
use App\Models\SubUnSubLog;


class CallBackController extends Controller
{
    // https://rd.b2mwap.com/callback/26?aocTransID=TR6639302919

    public function callback(Request $request, $get_a_o_c_token_id)
    {
        try {





            $data = $request->all();

            $callback = new Callback();
            $callback->aocTransID = $data['aocTransID'];
            $callback->raw_data = json_encode($request->all());
            $callback->save();


            // find Get AOC TOken
            $getAOCToken = GetAOCToken::select()->where('id', $get_a_o_c_token_id)->first();
            $service = Service::where('keyword', $getAOCToken->keyword)->first();

            $callback->keyword = $getAOCToken->keyword;
            $callback->save();



            $redirect = $service->redirect_url . "?aocTransID=" . $request->aocTransID;

            // GET and SET MSISDN
            $url = url('api/chargeStatus/' . $request->aocTransID);
            $res = Http::get($url);
            $res = $res->json();


            
            
            
            
            
            $callback->code = $res['code'];
            $callback->message = $res['data'];

            if ($res['code'] == '00') {
                $msisdn = $res['data']['msisdn'];
                $charged = $res['data']['totalAmountCharged'];


                $subs = Subscriber::where('msisdn', $msisdn)
                    ->where('keyword', $getAOCToken->keyword)->first();

                if (!$subs) {
                    $subs = new Subscriber();
                }

                $subs->keyword = $getAOCToken->keyword;
                $subs->subscriptionID = $getAOCToken->subscriptionID;
                $subs->spTransID = $getAOCToken->spTransID;
                $subs->charge = $charged;
                $subs->subscriptionDuration = $service->subs_duration;
                $subs->subs_date = date('Y-m-d H:i:s');
                $subs->unsubs_date = null;
                $subs->status = 1;
                $subs->msisdn = $msisdn;
                $subs->flag = 'success';
                $subs->save();


                $subUn = new SubUnSubLog();
                $subUn->msisdn = $subs->msisdn;
                $subUn->keyword = $subs->keyword;
                $subUn->status = $subs->status;
                $subUn->subscriptionID = $subs->subscriptionID;
                $subUn->flag = 'sub';
                $subUn->opt_date = date('Y-m-d');
                $subUn->opt_time = date('H:i:s');
                $subUn->save();


                $chargeLog  = new ChargeLog();
                $chargeLog->spTransID = $subs->spTransID;
                $chargeLog->msisdn = $subs->msisdn;
                $chargeLog->keyword = $subs->keyword;
                $chargeLog->amount = $charged;
                $chargeLog->type = 'subs';
                $chargeLog->charge_date = date('Y-m-d');  // Make sure this matches the date format in the database
                $chargeLog->save();

                if ($msisdn) {
                    $getAOCToken->msisdn = $msisdn;
                    $getAOCToken->isSubscription = true;
                    $callback->status = 1;
                    $callback->msisdn = $msisdn;
                }
            } else {
                $getAOCToken->msisdn = null;
                $getAOCToken->isSubscription = false;
                $callback->status = 0;
            }

            $callback->save();
            $getAOCToken->save();
            return redirect($redirect);

        } catch (\Throwable $th) {
            return $this->respondWithError('Server Error', $th->getMessage(), 500);
        }
    }
}
