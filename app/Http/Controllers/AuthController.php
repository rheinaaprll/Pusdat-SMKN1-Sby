<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        $request->validate([
            'nisn_nip' => 'required',
            'password' => 'required'
        ]);

        $loginInput = $request->nisn_nip;
        $password = $request->password;

        // Karena kolom nisn_nip bisa berisi Angka (NIP) atau Huruf (Username pendek),
        if (Auth::attempt(['nisn_nip' => $loginInput, 'password' => $password])) {
            
            $request->session()->regenerate(); // Keamanan (Session Fixation)

            $role = Auth::user()->role;
            
            // Arahkan user sesuai Role-nya
            if ($role === 'koordinator') {
                return redirect()->intended('/koordinator/dashboard');
            } elseif ($role === 'teknisi') {
                return redirect()->intended('/teknisi/dashboard');
            } else {
                return redirect()->intended('/dashboard'); 
            }
        }

        // salah ID/Username atau salah Password
        return back()->with('error', 'Akun tidak ditemukan atau Password salah! Periksa kembali ID/Username Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}