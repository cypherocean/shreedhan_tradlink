<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RechargeController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('command/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "config, cache, and view cleared successfully";
});

Route::get('command/config', function() {
    Artisan::call('config:cache');
    return "config cache successfully";
});

Route::get('command/key', function() {
    Artisan::call('key:generate');
    return "Key generate successfully";
});

Route::get('command/migrate', function() {
    Artisan::call('migrate:refresh');
    return "Database migration generated";
});

Route::get('command/seed', function() {
    Artisan::call('db:seed');
    return "Database seeding generated";
});

Route::group(['middleware' => ['prevent-back-history']], function(){
    Route::group(['middleware' => ['guest']], function () {
        Route::get('/', [AuthController::class, 'login'])->name('login');
        Route::post('sign-in', [AuthController::class, 'signIn'])->name('signIn');

        Route::get('forget-password', 'AuthController@forget_password')->name('forget.password');
        Route::post('password-forget', 'AuthController@password_forget')->name('password.forget');
        Route::get('reset-password/{string}', 'AuthController@reset_password')->name('reset.password');
        Route::post('recover-password', 'AuthController@recover_password')->name('recover.password');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('user_theme/{id}', [AuthController::class, 'setTheme'])->name('setTheme');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [DashboardController::class, 'profileUpdate'])->name('profile.update');
        Route::post('/get-upcoming-recharges', [DashboardController::class, 'getUpcomingRecharges'])->name('dashboard.upcoming-recharge');

        /** Users Routes */
            Route::any('users', [UsersController::class, 'index'])->name('users');
            Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
            Route::post('users/insert', [UsersController::class, 'insert'])->name('users.insert');
            Route::get('users/view/{id?}', [UsersController::class, 'view'])->name('users.view');
            Route::get('users/edit/{id?}', [UsersController::class, 'edit'])->name('users.edit');
            Route::patch('users/update', [UsersController::class, 'update'])->name('users.update');
            Route::post('users/change-status', [UsersController::class, 'change_status'])->name('users.change.status');
        /** Users Routes */

        /** Recharge Routes */
            Route::any('recharge', [RechargeController::class, 'index'])->name('recharge');
            Route::post('recharge/get_user_details', [RechargeController::class, 'getUserDetails']);
            Route::post('recharge/insert', [RechargeController::class, 'insert'])->name('recharge.insert');
            Route::get('recharge/view/{id?}', [RechargeController::class, 'view'])->name('recharge.view');
            Route::get('recharge/edit/{id?}', [RechargeController::class, 'edit'])->name('recharge.edit');
            Route::patch('recharge/update', [RechargeController::class, 'update'])->name('recharge.update');
            Route::post('recharge/change-status', [RechargeController::class, 'changeStatus'])->name('recharge.change.status');
            Route::post('recharge/repeat-recharge', [RechargeController::class, 'repeatRecharge'])->name('recharge.repeat-recharge');
        /** Recharge Routes */
    });

    Route::get("{path}", function(){ return redirect()->route('login'); })->where('path', '.+');
});