<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // 🔥 Cek apakah input itu email atau NIS
        $loginType = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'nis';

        if (Auth::attempt([$loginType => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            // redirect ke dashboard
            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('Data login tidak cocok dengan catatan kami.'),
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
