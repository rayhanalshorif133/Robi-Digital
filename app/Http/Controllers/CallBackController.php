<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use App\Models\CallbackRaw;
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
            $redirect = env('APP_URL') . '/api/chargeStatus/' . $request->aocTransID;
            return redirect($redirect);
        } catch (\Throwable $th) {
            return $this->respondWithError('Server Error', $th->getMessage(), 500);
        }
    }
}
