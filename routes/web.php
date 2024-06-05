<?php

use App\Http\Controllers\HitLogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CallBackController;
use App\Http\Controllers\ServiceProviderInfoController;
use App\Http\Controllers\RenewController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


Route::get('/clear', function () {
    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
  	return 'clear';
});


Route::get('/', function () {
    // return view('welcome');
    if(Auth::check()){
        return redirect()->route('dashboard');
    }else{
        return redirect()->route('login');
    }
});

Auth::routes();

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

Route::resource('service', ServiceController::class);
Route::resource('service-provider-info', ServiceProviderInfoController::class);

Route::get('callback/{subscription_id}', [CallBackController::class, 'callback'])->name('callback');


Route::get('hit_log/sent/{id?}', [HitLogController::class, 'sent'])->name('hit_log.sent');
Route::get('/check-data', [HitLogController::class, 'checkData'])->name('check-data');
Route::get('/charge-log', [HitLogController::class, 'chargeLog'])->name('charge-log');
    
Route::get('renew-log/{id?}', [RenewController::class, 'index'])->name('renew-log.index');


