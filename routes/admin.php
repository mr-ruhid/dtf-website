<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TwoFactorController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('web')->group(function () {

    Route::get('/', function () {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');

        Route::get('2fa', [TwoFactorController::class, 'show'])->name('2fa.show');
        Route::post('2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');
        Route::post('2fa/resend', [TwoFactorController::class, 'resend'])->name('2fa.resend');
    });

    Route::middleware('admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::put('profile/two-factor', [ProfileController::class, 'toggleTwoFactor'])->name('profile.two-factor');

        Route::resource('sliders', SliderController::class)->except(['show']);

        Route::post('sliders/{slider}/items', [SliderController::class, 'storeItem'])->name('sliders.items.store');
        Route::put('sliders/{slider}/items/{item}', [SliderController::class, 'updateItem'])->name('sliders.items.update');
        Route::delete('sliders/{slider}/items/{item}', [SliderController::class, 'destroyItem'])->name('sliders.items.destroy');
    });

});
