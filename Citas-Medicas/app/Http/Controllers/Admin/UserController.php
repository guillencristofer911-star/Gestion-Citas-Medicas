<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;

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

    public function destroy(User $user)
    {
        // Soft delete o deshabilitar
        $user->update(['active' => false]);
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario desactivado');
    }
}
