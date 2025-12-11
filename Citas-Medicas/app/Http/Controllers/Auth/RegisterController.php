<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;


class RegisterController extends Controller 
{ 

    public function show () : View
    {
        return view ('auth.register');
    }



    public function store (Request $request) : RedirectResponse
    {
        //Validar datos del formulario 
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        //Crear nuevo usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'patient',
        ]); 

        event (new Registered($user));

        return redirect()-> route ('login')-> with ('success', 'Registro exitoso. Por favor, inicie sesión.');
    }


}