<?php

namespace App\Http\Controllers\Admin;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with('user')->paginate(20);
        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.doctors.edit', compact ('doctor'));
        
    }

    /**
     * Update the specified resource in storage.
     */
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





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->update(['active' => false]);
        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor desactivado exitosamente.');
    }

        /**
     * Alternar estado inactivo/activo 
     */

    public function toggleStatus(Request $request, Doctor $doctor)
    {

        $validate = $request->validate([
            'active' => 'required|boolean',
            'section' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $doctor->update ([
                'active' => $validate['active']
            ]); 
            if ($doctor->user){
                $doctor->user->update([
                    'active' => $validate['active']
                ]);
            }

            DB::commit();

            $message = $validate['active']
            ? 'Médico activado exitosamente.'
            : 'Médico desactivado exitosamente.';

            $section = $validate['section'] ?? 'doctors';
            return redirect()
            ->route('admin.dashboard', ['section' => $section])
             ->with('success', $message);
            

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
            ->back()
            ->with('error', 'Error al actualizar el estado del médico: ' . $e->getMessage());
        }
    }
}
