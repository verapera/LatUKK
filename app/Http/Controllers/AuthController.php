<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function showlogin(){
        return view('login');
    }
    public function login(Request $request){
       $val = $request->validate([
        'username' => 'required|string',
        'password' => 'required|min:6',
       ]);
       if (Auth::attempt($val)) {
        # code...
        $request->session()->regenerate();
        return redirect()->intended('/');
       } else {
        # code...
        return redirect()->route('login')->with('error','Login failed, check your credentials!');
    }
}
public function logout(){
    Auth::logout();
    return redirect()->route('login')->with('success','You have been logged out!');
    }
}
