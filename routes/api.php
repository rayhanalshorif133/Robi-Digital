<?php

use App\Http\Controllers\Api\CallBackController;
use App\Http\Controllers\Api\NDTVController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// getToken Method only supports GET and POST
Route::match(['get', 'post'], 'getToken/{keyword?}', [NDTVController::class, 'getToken'])->name('getToken');

Route::get('redirect/{aocTransID}', [NDTVController::class, 'redirect'])->name('redirect');

Route::post('callback', [CallBackController::class, 'callback'])->name('callback');
Route::get('chargeStatus/{aocTransID}', [NDTVController::class, 'chargeStatus'])->name('chargeStatus');


Route::get('renewSubscription/{spTransID}/{msisdn}', [SubscriptionController::class, 'renewSubscription'])->name('renewSubscription');
Route::get('cancelSubscription/{spTransID}/{msisdn}', [SubscriptionController::class, 'cancelSubscription'])->name('cancelSubscription');
Route::get('subscriptionStatus/{subscriptionID}/{msisdn}', [SubscriptionController::class, 'subscriptionStatus'])->name('subscriptionStatus');


// Route::get('chargeWithTAC/{aocTransID}/{msisdn}', [NDTVController::class, 'chargeWithTAC'])->name('chargeWithTAC');
// Route::get('requestNewTAC/{aocTransID}/{msisdn}', [NDTVController::class, 'requestNewTAC'])->name('requestNewTAC');
// Route::post('directCharge/', [NDTVController::class, 'directCharge'])->name('directCharge');
// Route::get('requestDirectChargeTAC/{aocTransID}/{msisdn}', [NDTVController::class, 'requestDirectChargeTAC'])->name('requestDirectChargeTAC');
// Route::post('directChargeWithStepDown/', [NDTVController::class, 'directChargeWithStepDown'])->name('directChargeWithStepDown');
// Route::post('subscriptionStatus/', [NDTVController::class, 'subscriptionStatus'])->name('subscriptionStatus');
// Route::post('refund/', [NDTVController::class, 'refund'])->name('refund');
// Route::post('refundStatus/', [NDTVController::class, 'refundStatus'])->name('refundStatus');
