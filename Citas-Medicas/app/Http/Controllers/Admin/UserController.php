<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            // Si solo envía active (para activar)
            if ($request->has('active')) {
                $user->update(['active' => $request->boolean('active')]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario ' . ($request->boolean('active') ? 'activado' : 'desactivado') . ' exitosamente'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Operación no permitida'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->update(['active' => false]);
            
            return response()->json([
                'success' => true,
                'message' => 'Usuario desactivado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
