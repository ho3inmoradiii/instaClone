<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePasswordRequest;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Eloquent\UserRepository;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function updateProfile(StoreProfileRequest $request)
    {
        $validation = $request->validated();
        $location = new Point($request->location['latitude'],$request->location['longitude']);

        $validation['available_to_hire'] = $request->available_to_hire;

        $user = auth()->user();
        $user->update($validation);

        $user->location = $location;
        $user->save();

        return new UserResource($user);
    }

    public function updatePassword(StorePasswordRequest $request)
    {
        $validation = $request->validated();
        $validation['password'] = bcrypt($validation['password']);

        $request->user()->update($validation);

        return response()->json(['message' => 'رمز عبور شما با موفقیت تغییر پیدا کرد'],200);
    }
}
