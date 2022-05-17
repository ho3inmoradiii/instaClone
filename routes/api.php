<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('me','Auth\LoginController@getMe');
Route::get('designs','User\DesignController@index');
Route::get('users','User\UserController@index');

Route::group(['middleware' => ['auth:api']],function (){
    Route::post('logout','Auth\LoginController@logout');
    Route::put('settings/profile','User\SettingsController@updateProfile');
    Route::put('settings/password','User\SettingsController@updatePassword');
    Route::post('designs','User\UploadController@upload');
    Route::put('designs/{id}','User\DesignController@update');
    Route::delete('designs/{id}','User\DesignController@destroy');
});

Route::group(['middleware'=>['guest:api']],function (){
    Route::post('register','Auth\RegisterController@register');
    Route::post('verification/verify/{user}','Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend','Auth\VerificationController@resend');
    Route::post('login','Auth\LoginController@login');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset','Auth\ResetPasswordController@reset');
});




