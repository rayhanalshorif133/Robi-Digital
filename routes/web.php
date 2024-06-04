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

Route::get('callback', [CallBackController::class, 'callback'])->name('callback');


Route::get('hit_log/sent/{id?}', [HitLogController::class, 'sent'])->name('hit_log.sent');
    
Route::get('renew-log/{id?}', [RenewController::class, 'index'])->name('renew-log.index');


