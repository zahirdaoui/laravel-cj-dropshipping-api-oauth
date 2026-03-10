<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CJAuthController;
use App\Http\Controllers\CjOrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/cj/connect', [CJAuthController::class, 'redirect'])
        ->name('cj.redirect');

Route::get('/cj/callback', [CJAuthController::class, 'callback'])
        ->name('cj.callback');

Route::get('/cj-test', function () {
    return view('dashboard');
})->name('cj-test');

Route::middleware('auth')->group(function () {

    Route::get('/cj/connect', [CJAuthController::class, 'redirect'])
        ->name('cj.redirect');

    Route::get('/cj/callback', [CJAuthController::class, 'callback'])
        ->name('cj.callback');
    Route::post('/cj/logout', [CJAuthController::class, 'logoutDeleteToken'])
    ->name('cj.logout');

     Route::get('/cj/send-order', [CjOrderController::class, 'sendOrderPage']);
     Route::post('/cj/send-order', [CjOrderController::class, 'sendTestOrder']);

});


require __DIR__.'/auth.php';
