<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use App\Models\CallbackRaw;
use Illuminate\Http\Request;

class CallBackController extends Controller
{
    public function callback(Request $request)
    {
        try {
            $data = $request->data;

            $callback = new Callback();
            $callback->transactionOperationStatus = $data['transactionOperationStatus'];
            $callback->totalAmountCharged = $data['totalAmountCharged'];
            $callback->msisdn = $data['msisdn'];
            $callback->aocTransID = $data['aocTransID'];
            $callback->clientCorrelator = $data['clientCorrelator'];
            $callback->chargeMode = $data['chargeMode'];
            $callback->expiryDate = $data['expiryDate'];
            $callback->subscriptionID = $data['subscriptionID'];
            $callback->errorCode = $data['errorCode'];
            $callback->errorMessage = $data['errorMessage'];
            $callback->save();

            CallbackRaw::create([
                'callback_id' => $callback->id,
                'data' => json_encode($request->data),
            ]);

            return response()->json([
                'status'   => true,
                'errors'  => false,
                'message'  => 'Callback has been received successfully',
            ], 200);
        } catch (\Throwable $th) {
            return $this->respondWithError('Server Error', $th->getMessage(), 500);
        }
    }
}
