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

    /**
     * Buscar/filtrar usuarios (AJAX)
     */
    public function search(Request $request)
    {
        $query = User::query();

        // Buscar por nombre
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Buscar por email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filtrar por rol
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtrar por estado
        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active'));
        }

        $users = $query->orderBy('name')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'active' => $user->active ?? true,
                ];
            }),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }
}
