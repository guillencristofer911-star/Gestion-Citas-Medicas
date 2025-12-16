<?php

namespace App\Http\Controllers\Admin;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'nullable|string|min:8|max:20',
                'specialty' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'license_number' => 'required|string|max:50|unique:doctors',
            ]);

            // Crear usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? 'password123'),
                'role' => 'doctor',
                'active' => true,
            ]);

            // Crear doctor
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialty' => $validated['specialty'],
                'phone' => $validated['phone'],
                'license_number' => $validated['license_number'],
                'active' => true,
            ]);

            // Si es petición AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Médico creado exitosamente',
                    'data' => [
                        'id' => $doctor->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'specialty' => $doctor->specialty,
                        'phone' => $doctor->phone,
                        'license_number' => $doctor->license_number,
                        'active' => $doctor->active,
                    ]
                ]);
            }

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor creado exitosamente.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Obtener datos completos de un doctor (AJAX)
     */
    public function show(Doctor $doctor)
    {
        $doctor->load('user');
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'email' => $doctor->user->email,
                'specialty' => $doctor->specialty,
                'phone' => $doctor->phone,
                'license_number' => $doctor->license_number,
                'biography' => $doctor->biography,
                'photo_url' => $doctor->photo_url,
                'active' => $doctor->active,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('admin.doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        try {
            // Si solo envía active (para activar/desactivar)
            if ($request->has('active') && !$request->has('name')) {
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
                'license_number' => 'required|string|max:50|unique:doctors,license_number,' . $doctor->id,
            ]);

            // Actualizar usuario
            $doctor->user->update(['name' => $validated['name']]);
            
            // Actualizar doctor
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
     * Buscar/filtrar doctores (AJAX)
     */
    public function search(Request $request)
    {
        $query = Doctor::with('user');

        // Filtrar por especialidad
        if ($request->filled('specialty')) {
            $query->where('specialty', 'like', '%' . $request->specialty . '%');
        }

        // Buscar por nombre
        if ($request->filled('name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // Filtrar por estado
        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active'));
        }

        $doctors = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $doctors->map(function($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->user->name,
                    'email' => $doctor->user->email,
                    'specialty' => $doctor->specialty,
                    'phone' => $doctor->phone,
                    'active' => $doctor->active,
                ];
            }),
            'pagination' => [
                'current_page' => $doctors->currentPage(),
                'last_page' => $doctors->lastPage(),
                'per_page' => $doctors->perPage(),
                'total' => $doctors->total(),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage (Desactivar)
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->update(['active' => false]);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Médico desactivado exitosamente'
            ]);
        }
        
        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor desactivado exitosamente.');
    }
}
