<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
    return view('auth.login');
 }
 public function doLogin(Request $request){
     $user=User::where('email',$request->email)->first();
    //  dd($user);
     $check=Auth::attempt(['email'=>$request->email,'password'=>$request->password]);
    if($check){

        Auth::login($user);
        return redirect('admin/dashboard');
        
    }
    else
    {
        return redirect()->back();
    }

 }
 public function logout(){
    Auth::logout();
    return redirect('admin/login');
 }
}
