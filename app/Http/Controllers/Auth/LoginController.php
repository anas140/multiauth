<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout','userLogout');
    }
    public function userLogout() {
        Auth::guard('web')->logout();
        return redirect('/');
    }
    public function login(Request $request)
    {
    //     $this->validateLogin($request);

    //     // If the class is using the ThrottlesLogins trait, we can automatically throttle
    //     // the login attempts for this application. We'll key this by the username and
    //     // the IP address of the client making these requests into this application.
    //     if ($this->hasTooManyLoginAttempts($request)) {
    //         $this->fireLockoutEvent($request);

    //         return $this->sendLockoutResponse($request);
    //     }

    //     if ($this->attemptLogin($request)) {
    //         return $this->sendLoginResponse($request);
    //     }

    //     // If the login attempt was unsuccessful we will increment the number of attempts
    //     // to login and redirect the user back to the login form. Of course, when this
    //     // user surpasses their maximum number of attempts they will get locked out.
    //     $this->incrementLoginAttempts($request);

    //     return $this->sendFailedLoginResponse($request);
    // }
        $this->validate($request, [
                'email'    => 'required|email',
                'password' =>  'required|min:6'
            ]);
        // 2. Attempto Login Admin
            if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                if(auth()->user()->is_activated == '0'){
                    Auth::guard('web')->logout();
                    return back()->with('warning',"First please active your account.");
                }
                //3 if success then redirect to the intended location

                return redirect()->intended(route('admin.dashboard'));
            }

        //4. if ! success then redirect to thr login form with data
            return redirect()->back()->withInput($request->only('email', 'remember'));
}
}
