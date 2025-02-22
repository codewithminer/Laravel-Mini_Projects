<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function create()
    {
        return view('auth.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credential = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if(Auth::attempt($credential, $remember)){
            return redirect()->intended('/');
        }else{
            return redirect()->back()
                ->with('error', 'Invalid credentials');
        }

    }

    public function destroy()
    {
        Auth::logout();

        request()->session()->invalidate(); //clear session data
        // regenerate csrf token to make sure that all the forms 
        // that were loaded before the user signed out can't be successfully sent.
        request()->session()->regenerateToken(); 

        return redirect('/');
    }
}
