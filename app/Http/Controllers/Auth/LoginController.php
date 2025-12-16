<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show(): View 
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse 
    {
        // Validar datos
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Autenticar
        if (Auth::attempt($credentials)) {
          
            $request->session()->regenerate();
            
          
            return redirect()->route('dashboard')
                ->with('success', 'Inicio de sesión exitoso.');
        }

        // Error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Cierre de sesión exitoso.');
    }
}
