<?php
use Illuminate\Support\Facades\Route;

/** Auth関連 */
Route::get('login', 'Admin\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Admin\Auth\LoginController@login');
Route::post('logout', 'Admin\Auth\LoginController@logout')->name('logout');

Route::prefix('password')->group(function() {
    Route::get('reset', 'Admin\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('email', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('reset/{token}', 'Admin\Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('reset', 'Admin\Auth\ResetPasswordController@reset')->name('password.update');
});


/** 通常 */
Route::middleware('auth:admin')->group(function() {
    Route::get('/', 'Admin\DashboardController@index')->name('admin.dashboard');
});