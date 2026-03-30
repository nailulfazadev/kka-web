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
        // 1. Admin & Guru tetap
        $admin = User::firstOrCreate(['email' => 'admin@akademiguru.id'], [
            'name' => 'Admin KKA',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $guru = User::firstOrCreate(['email' => 'guru@akademiguru.id'], [
            'name' => 'Budi Santoso, S.Pd.',
            'password' => bcrypt('password'),
            'role' => 'guru',
            'phone' => '081234567890',
            'school' => 'SDN 01 Jakarta',
            'nuptk' => '1234567890',
            'email_verified_at' => now(),
        ]);

        // 2. Daftar 20 Judul Pelatihan untuk Variasi
        $titles = [
            'Metodologi Pembelajaran Diferensiasi',
            'Pemanfaatan AI dalam Kelas Modern',
            'Psikologi Anak & Remaja Sekolah',
            'Workshop Kurikulum Merdeka Belajar',
            'Manajemen Kelas Inklusif',
            'Teknik Asesmen Diagnostik Efektif',
            'Pembuatan Media Pembelajaran Canva',
            'Strategi Literasi dan Numerasi Dasar',
            'Penyusunan Modul Ajar Interaktif',
            'Public Speaking untuk Tenaga Pengajar',
            'Optimalisasi Google Workspace for Education',
            'Penerapan Project Based Learning (PjBL)',
            'Disiplin Positif di Lingkungan Sekolah',
            'Pengembangan Konten Video Pembelajaran',
            'Pemanfaatan Quizizz & Kahoot Pro',
            'Etika Digital dan Keamanan Siber Guru',
            'Workshop Penulisan Karya Ilmiah Guru',
            'Konseling Dasar untuk Wali Kelas',
            'Implementasi P5 yang Menyenangkan',
            'Analisis Data Hasil Belajar Siswa'
        ];

        // 3. Loop generate 20 Pelatihan
        foreach ($titles as $index => $title) {
            $pricingTypes = ['free', 'berbayar', 'donasi'];
            $type = $pricingTypes[$index % 3];
            
            // Logika Status: 5 Selesai, 10 Aktif, 5 Mendatang
            $status = 'aktif';
            if ($index < 5) $status = 'selesai';
            if ($index > 14) $status = 'mendatang';

            $training = Training::create([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . rand(100, 999),
                'description' => "Deskripsi lengkap untuk pelatihan $title. Pelatihan ini dirancang untuk meningkatkan kompetensi guru di era digital dengan pemateri profesional.",
                'thumbnail' => 'trainings/default-' . ($index % 5 + 1) . '.jpg',
                'pricing_type' => $type,
                'price' => ($type == 'berbayar') ? rand(50, 250) * 1000 : 0,
                'facility_price' => ($type == 'donasi') ? 50000 : 0,
                'status' => $status,
                'google_drive_link' => 'https://drive.google.com/drive/folders/example-' . $index,
                'whatsapp_link' => 'https://chat.whatsapp.com/invite/example-' . $index,
                'created_by' => $admin->id,
                'start_date' => now()->addDays($index - 5), // Variasi tanggal
                'end_date' => now()->addDays($index + 5),
                'min_attendance_percent' => 80,
                'facilities_released' => ($status == 'selesai') ? true : false,
            ]);

            // 4. Generate 3 Sesi per Pelatihan
            for ($s = 1; $s <= 3; $s++) {
                Session::create([
                    'training_id' => $training->id,
                    'session_number' => $s,
                    'title' => "Materi $s: " . $title . " Part $s",
                    'session_date' => $training->start_date->addDays($s - 1),
                    'start_time' => '09:00',
                    'end_time' => '11:00',
                    'zoom_link' => 'https://zoom.us/j/meeting-' . Str::random(10),
                ]);
            }
        }
    }
}