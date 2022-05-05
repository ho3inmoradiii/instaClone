<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware'=>['guest:api']],function (){
    Route::post('register','Auth\RegisterController@register');
    Route::post('verification/verify/{user}','Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend','Auth\VerificationController@resend');
});

Route::get('test',function (){
    return response()->json(['message'=>'this is a test']);
});

