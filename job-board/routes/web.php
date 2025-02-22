<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\MyJobApplicationController;
use App\Http\Controllers\MyJobController;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Route;

Route::get('', fn() => to_route('jobs.index'));

Route::resource('jobs', JobOfferController::class)
    ->only(['index', 'show']);

Route::get('login', fn() => to_route('auth.create'))->name('login');
Route::resource('auth', AuthController::class)
    ->only(['create', 'store']);

// it'more safe to use `delete` instead `get` for `logout` 
// beacuse protect user from csrf forgery attacks
// and also we actuly trying to delete user session and
// we not change server with get method
Route::delete('logout', fn() => to_route('auth.destroy'))->name('logout');
Route::delete('auth', [AuthController::class, 'destroy'])
    ->name('auth.destroy');

Route::middleware('auth')->group(function(){
    Route::resource('jobs.application', JobApplicationController::class)
        ->only(['create', 'store']);

    Route::resource('my-job-applications', MyJobApplicationController::class)
        ->only(['index', 'destroy']);

    Route::resource('employer', EmployerController::class)
        ->only(['create', 'store']);

    Route::middleware('employer')
        ->resource('my-jobs', MyJobController::class);
});