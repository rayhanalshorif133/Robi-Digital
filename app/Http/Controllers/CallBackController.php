<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use App\Models\HitLog;
use App\Models\Service;
use App\Models\GetAOCToken;
use App\Models\GetAOCTokenResponse;
use Illuminate\Http\Request;

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
            
            $redirect = $service->redirect_url . "?aocTransID=" . $request->aocTransID;

            return redirect($redirect);
        } catch (\Throwable $th) {
            return $this->respondWithError('Server Error', $th->getMessage(), 500);
        }
    }
}
