<?php

namespace App\Http\Controllers;

use App\Models\CSMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CSChatController extends Controller
{
    public function messages()
    {
        $query = CSMessage::query();
        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_token', session()->getId());
        }
        
        return response()->json($query->orderBy('created_at', 'asc')->get());
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $sessionId = session()->getId();
        $userId = auth()->id();
      
        if (!$userId) {
            $chatCount = CSMessage::where('session_token', $sessionId)
                ->where('is_bot', false) // Hitung pesan dari user saja
                ->count();

            if ($chatCount >= 5) {
                return response()->json([
                    'error' => 'limit_reached',
                    'message' => 'Anda telah mencapai batas chat. Silakan login untuk melanjutkan obrolan tanpa batas!'
                ], 403);
            }
        }

        // Save User Message
        try {
            $userMsg = CSMessage::create([
                'user_id' => auth()->id(), // Akan otomatis NULL jika logout
                'session_token' => session()->getId(),
                'message' => $request->message,
                'is_bot' => false,
            ]);
        } catch (\Exception $e) {
            \Log::error("Gagal simpan pesan: " . $e->getMessage());
            return response()->json(['error' => 'database_error', 'message' => $e->getMessage()], 500);
        }
      
      //dd($userMsg);

        // Call Gemini
        $apiKey = 'API-Gemini-Flash-2';
        $systemPrompt = $this->getSystemPrompt();
        
        // Include some recent history for context
        $history = CSMessage::where(function($q) use ($userId, $sessionId) {
                        if ($userId) $q->where('user_id', $userId);
                        else $q->where('session_token', $sessionId);
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get()
                    ->reverse();
        
        $historyText = "";
        foreach($history as $h) {
            $role = $h->is_bot ? "Asisten" : "User";
            $historyText .= "{$role}: {$h->message}\n";
        }

        $fullPrompt = $systemPrompt . "\n\nRIWAYAT CHAT TERAKHIR:\n" . $historyText . "\n\nUser baru saja bertanya: " . $request->message;

        $botReply = "Maaf, saya sedang mengalami kendala. Bisa diulangi?";
        try {
            $response = Http::timeout(15)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $fullPrompt]]]
                ]
            ]);

            if ($response->successful()) {
                $botReply = $response->json('candidates.0.content.parts.0.text', $botReply);
                // Clean markdown
                $botReply = str_replace(['**', '*'], '', $botReply);
            }
        } catch (\Exception $e) {
            \Log::error("CS Chat AI Error: " . $e->getMessage());
            $botReply = "Maaf, asisten AI sedang beristirahat sejenak. Silakan coba lagi nanti atau hubungi Admin.";
        }

        // Save Bot Reply
        $botMsg = CSMessage::create([
            'user_id' => $userId,
            'session_token' => $sessionId,
            'message' => trim($botReply),
            'is_bot' => true,
        ]);

        return response()->json(['reply' => $botMsg->message]);
    }

       private function getSystemPrompt()
    {
        $trainings = \App\Models\Training::where('status', 'aktif')->take(10)->get();
        $catalog = "Daftar Pelatihan Aktif:\n";
        foreach($trainings as $t) {
            $price = $t->price > 0 ? "Rp " . number_format($t->price, 0, ',', '.') : "Gratis";
            $catalog .= "- {$t->title} ({$t->category}): {$price}. Link: " . url('/pelatihan/' . $t->slug) . "\n";
        }

        return "
        Anda adalah 'Asisten AI Customer Service' sekaligus 'Konsultan Pendidikan' untuk platform 'Akademi Guru KKA'.

        PERAN ANDA:
        1. Sebagai CS: Membantu seputar platform, pendaftaran, dan sertifikat.
        2. Sebagai Asisten Umum: Menjawab pertanyaan umum, memberikan tips mengajar, inspirasi metode pembelajaran, atau pengetahuan umum lainnya untuk mendukung produktivitas guru.

        PENGETAHUAN PLATFORM (Prioritas untuk bantuan teknis):
        - Akademi Guru KKA: Platform pelatihan guru untuk peningkatan kompetensi di Indonesia.
        - Jenis Pelatihan: E-Course (Mandiri) dan Pelatihan Live (Zoom).
        - Alur: Cari Pelatihan -> Daftar & Bayar -> Dashboard Guru -> Selesaikan Sesi -> Unduh Sertifikat.
        - Sertifikat: Otomatis terbit jika presensi cukup, unduh di menu 'Sertifikat'.

        KATALOG PELATIHAN SAAT INI:
        {$catalog}

        KONTAK ADMIN (Berikan jika user mengalami kendala pembayaran/akses yang tidak bisa Anda selesaikan):
        WhatsApp Admin: 08123456789 (Jam operasional 08:00 - 20:00 WIB).

        INSTRUKSI RESPON:
        - Jawab dengan bahasa Indonesia yang ramah, profesional, dan suportif.
        - FLEKSIBILITAS: Jika user bertanya hal di luar platform (misal: 'Bagaimana cara membuat RPP yang menarik?'), jawablah dengan pengetahuan luas Anda sebagai AI, namun tetap selipkan motivasi agar mereka terus belajar di platform ini.
        - Jika merekomendasikan pelatihan dari katalog, sertakan link lengkapnya.
        - DILARANG menggunakan format markdown seperti bintang (**) atau bold di dalam teks.
        - Gunakan baris baru (newline) agar teks mudah dibaca di layar HP/Chat.
        - Jika pertanyaan tidak etis atau melanggar kebijakan, tolak dengan sopan.
        ";
    }
}
