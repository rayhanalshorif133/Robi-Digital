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
            // $data = $request->all();

            // $callback = new Callback();
            // $callback->transactionOperationStatus = $data['transactionOperationStatus'];
            // $callback->totalAmountCharged = $data['totalAmountCharged'];
            // $callback->msisdn = $data['msisdn'];
            // $callback->aocTransID = $data['aocTransID'];
            // $callback->clientCorrelator = $data['clientCorrelator'];
            // $callback->chargeMode = $data['chargeMode'];
            // $callback->expiryDate = $data['expiryDate'];
            // $callback->subscriptionID = $data['subscriptionID'];
            // $callback->errorCode = $data['errorCode'];
            // $callback->errorMessage = $data['errorMessage'];
            // $callback->save();

            // CallbackRaw::create([
            //     'callback_id' => $callback->id,
            //     'data' => json_encode($request->data),
            // ]);

            $redirect = env('APP_URL') . '/api/chargeStatus/' . $request->aocTransID;
            return redirect($redirect);
        } catch (\Throwable $th) {
            return $this->respondWithError('Server Error', $th->getMessage(), 500);
        }
    }
}
