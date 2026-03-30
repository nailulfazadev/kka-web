<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Training;
use App\Models\Session;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin
        $admin = User::firstOrCreate(['email' => 'admin@akademiguru.id'], [
            'name' => 'Admin KKA',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // 2. Pelatihan GRATIS (Status: Aktif)
        $freeTraining = Training::create([
            'title' => 'Pemanfaatan AI dalam Kelas Modern',
            'slug' => Str::slug('Pemanfaatan AI dalam Kelas Modern') . '-' . rand(100, 999),
            'description' => 'Pelatihan gratis mengenai penggunaan ChatGPT dan Gemini untuk membantu guru menyusun RPP secara otomatis.',
            'thumbnail' => 'trainings/default-1.jpg',
            'pricing_type' => 'free',
            'price' => 0,
            'facility_price' => 0,
            'status' => 'aktif',
            'google_drive_link' => 'https://drive.google.com/drive/folders/example-free',
            'whatsapp_link' => 'https://chat.whatsapp.com/invite/example-free',
            'created_by' => $admin->id,
            'start_date' => now(),
            'end_date' => now()->addDays(3),
            'min_attendance_percent' => 100, // Wajib hadir penuh untuk gratis
            'facilities_released' => false,
        ]);

        // Sesi untuk Pelatihan Gratis
        Session::create([
            'training_id' => $freeTraining->id,
            'session_number' => 1,
            'title' => 'Pengenalan Prompt Engineering untuk Guru',
            'session_date' => now(),
            'start_time' => '13:00',
            'end_time' => '15:00',
            'zoom_link' => 'https://zoom.us/j/free-session',
        ]);

        // 3. Pelatihan BERBAYAR (Status: Mendatang)
        $paidTraining = Training::create([
            'title' => 'Workshop Intensif Kurikulum Merdeka Belajar',
            'slug' => Str::slug('Workshop Intensif Kurikulum Merdeka Belajar') . '-' . rand(100, 999),
            'description' => 'Pendalaman materi penyusunan Modul Ajar dan P5 dengan pendampingan langsung sampai mahir.',
            'thumbnail' => 'trainings/default-2.jpg',
            'pricing_type' => 'berbayar',
            'price' => 150000,
            'facility_price' => 0,
            'status' => 'mendatang',
            'google_drive_link' => 'https://drive.google.com/drive/folders/example-paid',
            'whatsapp_link' => 'https://chat.whatsapp.com/invite/example-paid',
            'created_by' => $admin->id,
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(10),
            'min_attendance_percent' => 80,
            'facilities_released' => false,
        ]);

        // Sesi untuk Pelatihan Berbayar
        Session::create([
            'training_id' => $paidTraining->id,
            'session_number' => 1,
            'title' => 'Bedah Struktur Kurikulum Merdeka',
            'session_date' => now()->addDays(7),
            'start_time' => '09:00',
            'end_time' => '11:00',
            'zoom_link' => 'https://zoom.us/j/paid-session',
        ]);
    }
}