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
Route::get('chargeWithTAC/{aocTransID}', [NDTVController::class, 'chargeWithTAC'])->name('chargeWithTAC');
