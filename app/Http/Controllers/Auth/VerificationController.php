<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
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

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // $this->projects = Auth::user()->projects;
            // $this->middleware('auth');
            $this->middleware('signed')->only('verify');
            $this->middleware('throttle:6,1')->only('verify', 'resend');
            // dd(auth()->user());

            if(auth()->check()){
                if(auth()->user()->is_handicapper==1){
                    $this->redirectTo = '/handicapper/thankyou';
                }
                else{
                    $this->redirectTo = '/bettor/thankyou';
                }
            }
            return $next($request);
        });
    }

    public function verify(Request $request){

        $user = User::find($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($user->markEmailAsVerified())
            event(new Verified($user));
        Auth::loginUsingId($user->id);
        if(auth()->user()->is_handicapper==1){
            $this->redirectTo = '/handicapper/thankyou';
        }
        else{
            $this->redirectTo = '/bettor/thankyou';
        }
        return redirect($this->redirectPath())->with('verified', true);
    }


}
