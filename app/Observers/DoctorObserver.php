<?php

namespace App\Observers;

use App\Models\Doctor;

class DoctorObserver
{

    public function created(Doctor $doctor): void
    {
        if ($doctor->user->role != 'doctor'){
            $doctor->user->update([
                'role' => 'doctor',
                'active' => true
            ]);
        }
    }



    public function deleting(Doctor $doctor): void
    {

        if ($doctor->user){ 
            $doctor->user->update([
            'role' => 'patient',
            'active' => false
        ]);
        }
       
    }


    public function restored(Doctor $doctor): void
    {
        if ($doctor->user){
            $doctor->user->update ([
                'role' => 'doctor',
                'active' => true
            ]);
        }
    }

    public function forceDeleted(Doctor $doctor): void
    {
        //
    }
}
