<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

   
    //  * Where to redirect users after login.
     
    protected function redirectTo()
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            return '/login';
        }

        return $user->defaultPostLoginPath() ?? '/login';
    }

    protected function authenticated(Request $request, $user)
    {
        if (! $user instanceof User) {
            Auth::logout();

            return redirect()->route('login');
        }

        $path = $user->defaultPostLoginPath();
        if ($path) {
            return redirect()->intended($path);
        }

        Auth::logout();

        return redirect()->route('login')->withErrors([
            'email' => 'No admin module access has been assigned. Ask a super admin to enable permissions for your account.',
        ]);
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function logout(Request $request)
    {
        $redirect = '/login'; // default

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($redirect);
    }
}
