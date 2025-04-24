<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        // Check user role after successful authentication
        if (auth()->user()->role == 'admin') {
            return RouteServiceProvider::HOME;
        } else if (auth()->user()->role == 'karyawan') {
            return '/scanqr';  // Route to QR code scanning page
        }
        
        // Default fallback redirect
        return RouteServiceProvider::HOME;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role == 'admin') {
            return redirect()->intended(RouteServiceProvider::HOME);
        } else if ($user->role == 'karyawan') {
            return redirect()->intended('/scanqr');
        }
        
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}