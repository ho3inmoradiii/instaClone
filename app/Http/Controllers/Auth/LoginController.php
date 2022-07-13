<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function attemptLogin(Request $request)
    {
        $token = $this->guard()->attempt($this->credentials($request));
        if (! $token){
            return false;
        }

        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return false;
        }

        $this->guard()->setToken($token);
        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        $token = (string)$this->guard()->getToken();
        $exiration = $this->guard()->getPayload()->get('exp');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $exiration
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return response()->json(['errors' => [
                'verification' => 'شما باید ایمیل خود را فعال کنید'
            ]],422);
        }
        throw ValidationException::withMessages([
            $this->username() => 'ایمیل یا پسورد وارد شده نامعتبر است'
        ]);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        return response()->json(['message'=>'خروج موفقیت آمیز بود.']);
    }

    public function getMe(){
        if (auth()->check()){
            $user = auth()->user();
            return new UserResource($user);
            //return response()->json(['user' => auth()->user()],200);
        }
        return response()->json(null,401);
    }
}
