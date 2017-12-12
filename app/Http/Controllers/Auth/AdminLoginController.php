<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdminLoginController extends Controller
{
	public function __construct() {
		$this->middleware('guest:admin');
	}
    public function showLoginForm() {
	    	return view('auth.admin-login');
    }
    public function login(Request $request) {
    	// 1 validate the form data
    		$this->validate($request, [
    			'email'    => 'required|email',
    			'password' =>  'required|min:6'
    		]);
    	// 2. Attempto Login Admin
    		if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
    			//3 if success then redirect to the intended location
    			return redirect()->intended(route('admin.dashboard'));
    		}

    	//4. if ! success then redirect to thr login form with data
    		return redirect()->back()->withInput($request->only('email', 'remember'));
    }
}
