<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('me','Auth\LoginController@getMe');


Route::get('designs','User\DesignController@index');
Route::get('designs/{id}','User\DesignController@findDesign');


Route::get('users','User\UserController@index');


Route::get('teams/slug/{slug}','Team\TeamController@findBySlug');

Route::group(['middleware' => ['auth:api']],function (){
    Route::post('logout','Auth\LoginController@logout');
    Route::put('settings/profile','User\SettingsController@updateProfile');
    Route::put('settings/password','User\SettingsController@updatePassword');

    //Design crud
    Route::post('designs','User\UploadController@upload');
    Route::put('designs/{id}','User\DesignController@update');
    Route::delete('designs/{id}','User\DesignController@destroy');

    //Comment crud
    Route::post('designs/{id}/comment','User\CommentController@store');
    Route::put('comment/{id}','User\CommentController@update');
    Route::delete('comment/{id}','User\CommentController@destroy');

    //like and unlike
    Route::post('designs/{id}/like','User\DesignController@like');
    Route::get('designs/{id}/liked','User\DesignController@checkIfUserHasLiked');

    //Teams Route
    Route::post('teams','Team\TeamController@store');
    Route::get('teams/{id}','Team\TeamController@findById');
    Route::get('teams','Team\TeamController@index');
    Route::get('users/teams','Team\TeamController@fetchUserTeams');
    Route::put('teams/{id}','Team\TeamController@update');
    Route::delete('teams/{id}','Team\TeamController@destroy');
    Route::delete('teams/{team_id}/users/{user_id}','Team\TeamController@removeFromTeam');

    //Invitation
    Route::post('invitations/{teamID}','Team\InvitationController@invite');
    Route::post('invitations/{id}/resend','Team\InvitationController@resend');
    Route::post('invitations/{id}/respond','Team\InvitationController@respond');
    Route::delete('invitations/{id}','Team\InvitationController@destroy');

    //Chats
    Route::post('chats' , 'Chats\ChatController@sendMessage');
    Route::get('chats' , 'Chats\ChatController@getUserChats');
    Route::get('chats/{id}/messages' , 'Chats\ChatController@getChatMessages');
    Route::put('chats/{id}/markAsRead' , 'Chats\ChatController@markAsRead');
    Route::delete('messages/{id}' , 'Chats\ChatController@destroyMessage');
});

Route::group(['middleware'=>['guest:api']],function (){
    Route::post('register','Auth\RegisterController@register');
    Route::post('verification/verify/{user}','Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend','Auth\VerificationController@resend');
    Route::post('login','Auth\LoginController@login');
    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset','Auth\ResetPasswordController@reset');
});




