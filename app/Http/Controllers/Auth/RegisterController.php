<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use App\Mail\RegisterUser;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        $user['token'] = str_random(30);
        // insert token in user for email registration
        DB::table('users_activation')->insert(['id_user' => $user->id , 'token' => $user['token']]);

        // Send mail to the user
        \Mail::to($user)->send(new RegisterUser($user));

        // $this->guard()->login($user);
        // return $this->registered($request, $user)
        //                 ?: redirect($this->redirectPath());
        // if($this->registered($request, $user)) {
            return redirect()->to('login')->with('success',"We sent activation code. Please check your mail.");
        // }
    }
    public function userActivation($token) {
        $check = DB::table('users_activation')->where('token',$token)->first();
        if(!is_null($check)) {
            $user = User::find($check->id_user);
            if($user->is_activated == 1) {
                return redirect()->to('login')->with('success', 'You are already activated');
            }
            $user->update(['is_activated' => 1]);
            DB::table('users_activation')->where('token',$token)->delete(); 

            return redirect()->to('login')->with('success', "Account Activated Successfully");
        } else {
            return redirect()->to('login')->with('warning', 'Your token is invalid');
        }
    }
}
