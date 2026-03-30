<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('pricing_type', ['free', 'berbayar', 'donasi'])->default('free');
            $table->decimal('price', 10, 0)->default(0);
            $table->decimal('facility_price', 10, 0)->default(0);
            $table->enum('status', ['draft', 'aktif', 'mendatang', 'selesai'])->default('draft');
            $table->string('google_drive_link')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('min_attendance_percent')->default(80);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
