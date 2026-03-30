<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(int $trainingId)
    {
        $user = auth()->user();
        $registration = $user->registrations()
            ->where('training_id', $trainingId)
            ->whereIn('status', ['aktif', 'selesai'])
            ->firstOrFail();

        $training = $registration->training;

        $query = ChatMessage::where('training_id', $trainingId)->with(['user', 'parent.user']);
        
        // E-Course chats are strictly private (only user and bot)
        if ($training->is_ecourse) {
            $query->where('user_id', $user->id);
        }

        $messages = $query->orderBy('created_at', 'asc')->paginate(50);

        return view('guru.pelatihan.chat', compact('training', 'messages'));
    }

    public function store(Request $request, int $trainingId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'reply_to_id' => 'nullable|exists:chat_messages,id'
        ]);

        $user = auth()->user();
        $registration = $user->registrations()->where('training_id', $trainingId)->whereIn('status', ['aktif', 'selesai'])->firstOrFail();
        $training = $registration->training;

        $msg = ChatMessage::create([
            'training_id' => $trainingId,
            'user_id' => $user->id,
            'message' => $request->message,
            'reply_to_id' => $request->reply_to_id,
        ]);

        // Integrate Gemini AI Bot (Always trigger if E-Course, otherwise only on @kka)
        $triggerAi = $training->is_ecourse || str_contains(strtolower($request->message), '@kka');

        if ($triggerAi) {
            $apiKey = 'AIzaSyAsLWTL-hqnnmGMScHaoj94F2v5rlvyllg';
            $prompt = $request->message;
            
            // Build Context Layer
            $latestTrainings = \App\Models\Training::where('status', 'aktif')->latest()->take(5)->get();
            $trainingContext = "Daftar Pelatihan Aktif saat ini:\n";
            foreach ($latestTrainings as $t) {
                $type = $t->is_ecourse ? 'E-Course' : 'Live Event';
                $price = $t->pricing_type === 'free' ? 'Gratis' : ($t->pricing_type === 'donasi' ? 'Donasi Sukarela' : 'Rp' . number_format($t->price, 0, ',', '.'));
                $trainingContext .= "- {$t->title} ({$type}, Harga: {$price}, Link: " . url('/pelatihan/' . $t->slug) . ")\n";
            }
            if ($latestTrainings->isEmpty()) {
                $trainingContext .= "*(Saat ini belum ada pelatihan aktif)*\n";
            }
            
            $persona = $training->is_ecourse 
                ? "Sebagai Tutor Pribadi AI untuk materi '{$training->title}', peran Anda adalah membimbing member ini secara interaktif, eksklusif, dan cerdas. "
                : "Sebagai Asisten AI KKA (Academy Guru KKA), peran Anda adalah asisten virtual yang cerdas, ahli dalam bidang pendidikan dan pedagogik, ramah, dan informatif. ";

            $systemInstruction = $persona
                               . "Gunakan bahasa Indonesia yang profesional namun santai. "
                               . "PENTING: JANGAN gunakan format markdown seperti bintang (* atau **) dalam jawaban Anda. Jawab dengan teks biasa (plain text) saja.\n\n"
                               . "Berikut adalah data real-time platform kita sebagai 'konteks lokal' jika relevan:\n\n"
                               . "Kontak Admin CS: Nomor WhatsApp 08123456789 (Jam Kerja 08.00 - 17.00 WIB)\n"
                               . "Website Utama: " . url('/') . "\n\n"
                               . $trainingContext . "\n\n"
                               . "INSTRUKSI UTAMA: "
                               . "Gunakan pengetahuan umum, wawasan ilmu pendidikan, dan kepintaran Anda seluas-luasnya untuk menjawab pertanyaan member secara mendalam dan berbobot. Jangan cuma berputar-putar di data platform di atas. Posisikan diri Anda sebagai mentor cerdas untuk guru.\n\n"
                               . "Pertanyaan Member: " . $prompt;

            $response = \Illuminate\Support\Facades\Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $systemInstruction]]]
                ]
            ]);

            if ($response->successful()) {
                $botText = $response->json('candidates.0.content.parts.0.text', 'Maaf, sistem AI sedang sibuk. Silakan coba beberapa saat lagi.');
                
                // Force strip markdown bold and italic formatting that Gemini naturally generates
                $botText = str_replace(['**', '*'], '', $botText);

                ChatMessage::create([
                    'training_id' => $trainingId,
                    'user_id' => $user->id, // Use current user ID to bypass DB constraint, UI relies on is_bot flag
                    'message' => trim($botText),
                    'reply_to_id' => $msg->id,
                    'is_bot' => true,
                ]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json($msg->load(['user', 'parent.user']));
        }

        return back();
    }

    public function fetch(int $trainingId, Request $request)
    {
        $afterId = $request->query('after', 0);
        
        $user = auth()->user();
        $registration = $user->registrations()->where('training_id', $trainingId)->whereIn('status', ['aktif', 'selesai'])->first();
        if (!$registration) return response()->json([]);

        $training = $registration->training;

        $query = ChatMessage::where('training_id', $trainingId)
            ->where('id', '>', $afterId)
            ->with(['user', 'parent.user']);

        if ($training->is_ecourse) {
            $query->where('user_id', $user->id);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }
}
