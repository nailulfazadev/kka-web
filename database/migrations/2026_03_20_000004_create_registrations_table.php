<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'aktif', 'selesai', 'batal'])->default('pending');
            $table->boolean('certificate_eligible')->default(false);
            $table->boolean('facility_paid')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'training_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
