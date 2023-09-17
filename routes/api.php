<?php

use App\Http\Controllers\Api\NDTVController;
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

Route::get('getToken/{description?}', [NDTVController::class, 'getToken'])->name('getToken');
Route::post('callback', [NDTVController::class, 'callback'])->name('callback');
Route::get('chargeWithTAC/{aocTransID}/{msisdn}', [NDTVController::class, 'chargeWithTAC'])->name('chargeWithTAC');
Route::get('requestNewTAC/{aocTransID}/{msisdn}', [NDTVController::class, 'requestNewTAC'])->name('requestNewTAC');
Route::post('directCharge/', [NDTVController::class, 'directCharge'])->name('directCharge');
Route::get('requestDirectChargeTAC/{aocTransID}/{msisdn}', [NDTVController::class, 'requestDirectChargeTAC'])->name('requestDirectChargeTAC');
Route::post('directChargeWithStepDown/', [NDTVController::class, 'directChargeWithStepDown'])->name('directChargeWithStepDown');
Route::get('chargeStatus/{aocTransID}/{msisdn}/{status}', [NDTVController::class, 'chargeStatus'])->name('chargeStatus');
Route::post('renewSubscription/', [NDTVController::class, 'renewSubscription'])->name('renewSubscription');
Route::post('cancelSubscription/', [NDTVController::class, 'cancelSubscription'])->name('cancelSubscription');
Route::post('subscriptionStatus/', [NDTVController::class, 'subscriptionStatus'])->name('subscriptionStatus');
Route::post('refund/', [NDTVController::class, 'refund'])->name('refund');
Route::post('refundStatus/', [NDTVController::class, 'refundStatus'])->name('refundStatus');
