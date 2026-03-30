<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Training;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(int $id)
    {
        $training = Training::with('sessions')->findOrFail($id);
        return view('admin.pelatihan.jadwal', compact('training'));
    }

    public function store(Request $request, int $id)
    {
        $validated = $request->validate([
            'session_number' => 'required|integer|min:1',
            'title' => 'nullable|string|max:255',
            'session_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'zoom_link' => 'nullable|url',
            'recording_link' => 'nullable|url',
            'facilities' => 'nullable|array',
            'facilities.*' => 'nullable|string',
        ]);
        
        if (isset($validated['facilities'])) {
            $validated['facilities'] = array_values(array_filter($validated['facilities']));
        }
        $validated['training_id'] = $id;

        Session::create($validated);
        return back()->with('success', 'Sesi berhasil ditambahkan.');
    }

    public function update(Request $request, int $sessionId)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'session_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'zoom_link' => 'nullable|url',
            'recording_link' => 'nullable|url',
            'facilities' => 'nullable|array',
            'facilities.*' => 'nullable|string',
        ]);

        if (isset($validated['facilities'])) {
            $validated['facilities'] = array_values(array_filter($validated['facilities']));
        }

        $session = Session::findOrFail($sessionId);
        $session->update($validated);
        return back()->with('success', 'Sesi berhasil diperbarui.');
    }

    public function destroy(int $sessionId)
    {
        Session::findOrFail($sessionId)->delete();
        return back()->with('success', 'Sesi berhasil dihapus.');
    }
}
