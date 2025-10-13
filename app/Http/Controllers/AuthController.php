<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',  
            'password' => 'required|string',
        ], [
            'email.required' => 'Email atau NIS harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        $email = $request->email;
        $password = $request->password;

       
        $user = User::where('email', $email)
                    ->orWhere('nis', $email)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email/NIS atau password salah.'
            ])->onlyInput('email');
        }

        
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email/NIS atau password salah.'
            ])->onlyInput('email');
        }

        
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        
        if ($user->isAdmin()) {
            return redirect()->intended('dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
        } else {
            return redirect()->intended('dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }
}