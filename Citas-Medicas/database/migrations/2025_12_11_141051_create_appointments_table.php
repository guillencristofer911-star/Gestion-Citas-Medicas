<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->dateTime('appointment_date_time');
            $table->enum('status', [
                'pending', 'confirmed', 'cancelled', 'attended'
            ])->default('pending');
            $table->text('consultation_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('patient_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('doctors')
                  ->onDelete('cascade');

            // Indexes for faster queries
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('status');
            $table->unique(['doctor_id', 'appointment_date_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
