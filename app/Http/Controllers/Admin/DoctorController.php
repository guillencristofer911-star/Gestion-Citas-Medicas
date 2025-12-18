<?php

namespace App\Http\Controllers\Admin;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class DoctorController extends Controller
{



    public function index()
    {
        $doctors = Doctor::with('user')->paginate(20);
        return view('admin.doctors.index', compact('doctors'));
    }


    public function create()
    {
        return view('admin.doctors.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|max:20',
            'specialty' => 'required|string|max:255',
        ]);

        $user = User::create ([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'password'=> Hash::make($validated['password']),
            'role'=>'doctor',
        ]);

        $doctor = Doctor::create([
            'user_id'=>$user->id,
            'specialty'=>$validated['specialty'],
            'phone'=> $validated['phone'],
            'license_number'=> $validated['license_number'],
            'active'=> true,
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor creado exitosamente.');
    }


    public function edit(string $id)
    {
        return view('admin.doctors.edit', compact ('doctor'));
        
    }


    public function update(Request $request, Doctor $doctor)
    {
        try {
            // Si solo envía active (para activar/desactivar)
            if ($request->has('active')) {
                $doctor->update(['active' => $request->boolean('active')]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Médico ' . ($request->boolean('active') ? 'activado' : 'desactivado') . ' exitosamente'
                ]);
            }

            // Validación normal para edición completa
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'specialty' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'license_number' => 'required|string|max:50',
            ]);

            $doctor->user->update(['name' => $validated['name']]);
            $doctor->update([
                'specialty' => $validated['specialty'],
                'phone' => $validated['phone'],
                'license_number' => $validated['license_number'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Médico actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }






    public function destroy(Doctor $doctor)
    {
        $doctor->update(['active' => false]);
        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor desactivado exitosamente.');
    }



    
}
