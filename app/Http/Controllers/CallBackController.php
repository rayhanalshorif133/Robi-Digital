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


class CallBackController extends Controller
{
    public function callback(Request $request)
    {
        try {
            $data = $request->all();
            $callback = new Callback();
            $callback->aocTransID = $data['aocTransID'];
            $callback->raw_data = json_encode($request->data);
            $callback->save();
            
            $getAOCTokenRes = GetAOCTokenResponse::where('aocTransID', $request->aocTransID)->first();
            $getHitLog = HitLog::select()->where('get_aoc_token_id', $getAOCTokenRes->get_aoc_token_id)->first();
            $service = Service::where('keyword', $getHitLog->keyword)->first();        
            $getAOCToken = GetAOCToken::where('id', $getAOCTokenRes->get_aoc_token_id)->first();
            
            
            $redirect = $service->redirect_url . "?aocTransID=" . $request->aocTransID;

            // GET and SET MSISDN
            $url = url('api/chargeStatus/' . $request->aocTransID);
            $res = Http::get($url);
            $res = $res->json();
            if($res['code'] == '00'){
                $msisdn = $res['data']['msisdn'];
                $getAOCToken->msisdn = $msisdn;
                $getAOCToken->save();
            }

            return redirect($redirect);
        } catch (\Throwable $th) {
            return $this->respondWithError('Server Error', $th->getMessage(), 500);
        }
    }
}
