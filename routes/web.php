<?php

use App\Http\Controllers\HitLogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CallBackController;
use App\Http\Controllers\ServiceProviderInfoController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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


Route::prefix('hit_log')
    ->name('hit_log.')
    ->group(function () {
        Route::get('sent/{id?}', [HitLogController::class, 'sent'])->name('sent');
        Route::get('received', [HitLogController::class, 'received'])->name('received');
    });


