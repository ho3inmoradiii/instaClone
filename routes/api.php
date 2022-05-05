<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware'=>['guest:api']],function (){
    Route::post('register','Auth\RegisterController@register');
});

Route::get('test',function (){
    return response()->json(['message'=>'this is a test']);
});

