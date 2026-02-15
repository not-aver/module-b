<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)){
            if(Auth::user()->role !== 'admin'){
                Auth::logout();
                return back()->withErrors([
                    'email' => 'У вас нет прав доступа к админ панели.',
                ]);
            }
            $request->session()->regenerate();
            return redirect()->intended(route('admin.courses.index'));
        }

        return back()->withErrors([
            'email' => 'Предоставленные учетные данные не найдены.'
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
    
}
