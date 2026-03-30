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

        $messages = ChatMessage::where('training_id', $trainingId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        $training = $registration->training;

        return view('guru.pelatihan.chat', compact('training', 'messages'));
    }

    public function store(Request $request, int $trainingId)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $user = auth()->user();
        $user->registrations()->where('training_id', $trainingId)->whereIn('status', ['aktif', 'selesai'])->firstOrFail();

        $msg = ChatMessage::create([
            'training_id' => $trainingId,
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        if ($request->wantsJson()) {
            return response()->json($msg->load('user'));
        }

        return back();
    }

    public function fetch(int $trainingId, Request $request)
    {
        $after = $request->query('after', 0);
        $messages = ChatMessage::where('training_id', $trainingId)
            ->where('id', '>', $after)
            ->with('user')
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }
}
