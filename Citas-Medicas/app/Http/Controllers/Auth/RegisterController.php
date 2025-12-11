<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\http\Request;
use illuminate\Support\Facades\Hash;

class RegisterController extends Controller 
{ 
    /**
     * 
     * Mostrar formulario de registro
     * 
     */

    public function show ()
    {
        return view ('auth.register');
    }

    /**
     * 
     * Procesar el registro de un nuevo usuario
     * 
     */

    public function register (Request $request)
    {
        //Validar datos del formulario 
        $validated = $request ->validate 
        ([
            'name' => 'required|string|max:255',
            'email' => 'requiered|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'

        ]);

        //Crear nuevo usuario
        $user = User:: create 
        ([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make ($validated['password']),
            'role' => 'paciente'
        ]);

        //Redirigir al usuario a la pagina de login con un mensaje de exito

        //auth()->login($user);
        //return redirect()-> route ('dashboard')-> with ('success', 'Registro exitoso. Bienvenido!');
    }


}