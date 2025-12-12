<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $role = $user->role ?? 'patient';

        switch ($role) {
            case 'patient':
                return view('dashboard.patient.index', ['user' => $user]);
            
            case 'doctor':
                return view('dashboard.doctor.index', ['user' => $user]);
            
            case 'admin':
                return view('dashboard.admin.index', ['user' => $user]);
            
            default:
                abort(403, 'Rol no autorizado');
        }
    }
}
