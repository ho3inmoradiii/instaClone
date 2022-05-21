<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\Eloquent\UserRepository;
use App\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    protected $users;

    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        //$this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        $this->users = $users;
    }

    public function verify(Request $request, User $user){
        // check if url is valid signed url
        if (! URL::hasValidSignature($request)){
            return response()->json(['errors'=>[
                'message' => 'لینک فعالسازی نامعتبر است'
            ]],422);
        }

        // check if url is valid signed url
        if ($user->hasVerifiedEmail()){
            return response()->json(['errors'=>[
                'message' => 'ایمیل مورد نظر فعال شده است'
            ]],422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'ایمیل مورد نظر با موفقیت فعال شد'],200);
    }

    public function resend(Request $request){
        $this->validate($request, [
            'email' => ['email','required']
        ]);

        $user = $this->users->findWhereFirst('email',$request->email);

        $user = User::where('email',$request->email)->first();
        if (! $user){
            return response()->json(['errors'=>[
                'email' => 'کاربری با این ایمیل یافت نشد'
            ]],422);
        }

        // check if url is valid signed url
        if ($user->hasVerifiedEmail()){
            return response()->json(['errors'=>[
                'message' => 'ایمیل مورد نظر فعال شده است'
            ]],422);
        }

        $user->sendEmailVerificationNotification();
        return response()->json(['status' => 'ایمیل فعالسازی مجدد ارسال شد']);
    }
}
