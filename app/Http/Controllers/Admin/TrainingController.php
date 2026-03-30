<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::withCount(['registrations as participants_count' => fn($q) => $q->whereIn('status', ['aktif', 'selesai'])])
            ->latest()->paginate(15);
        return view('admin.pelatihan.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.pelatihan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pricing_type' => 'required|in:free,berbayar,donasi',
            'price' => 'required_if:pricing_type,berbayar|numeric|min:0',
            'facility_price' => 'required_if:pricing_type,donasi|numeric|min:0',
            'status' => 'required|in:draft,aktif,mendatang,selesai',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'google_drive_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
            'min_attendance_percent' => 'required|integer|min:0|max:100',
            'thumbnail' => 'nullable|image|max:2048',
            'facilities_released' => 'boolean',
            'is_ecourse' => 'boolean',
        ]);

        $validated['facilities_released'] = $request->has('facilities_released');
        $validated['is_ecourse'] = $request->has('is_ecourse');

        $validated['created_by'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('trainings', 'public');
        }

        Training::create($validated);

        return redirect()->route('admin.pelatihan.index')->with('success', 'Pelatihan berhasil dibuat.');
    }

    public function edit(Training $pelatihan)
    {
        return view('admin.pelatihan.edit', ['training' => $pelatihan]);
    }

    public function update(Request $request, Training $pelatihan)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pricing_type' => 'required|in:free,berbayar,donasi',
            'price' => 'nullable|numeric|min:0',
            'facility_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,aktif,mendatang,selesai',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'google_drive_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
            'min_attendance_percent' => 'required|integer|min:0|max:100',
            'thumbnail' => 'nullable|image|max:2048',
            'facilities_released' => 'boolean',
            'is_ecourse' => 'boolean',
        ]);

        $validated['facilities_released'] = $request->has('facilities_released');
        $validated['is_ecourse'] = $request->has('is_ecourse');

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('trainings', 'public');
        }

        $pelatihan->update($validated);

        return redirect()->route('admin.pelatihan.index')->with('success', 'Pelatihan berhasil diperbarui.');
    }

    public function downloadRekapPeserta(int $id)
    {
        $training = \App\Models\Training::findOrFail($id);
        $sessions = $training->sessions()->orderBy('session_number')->get();
        $registrations = $training->registrations()->with(['user.attendances' => function($q) use ($sessions) {
            $q->whereIn('session_id', $sessions->pluck('id'));
        }])->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pelatihan.rekap-peserta', compact('training', 'sessions', 'registrations'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('Rekap_Peserta_' . \Illuminate\Support\Str::slug($training->title) . '.pdf');
    }

    public function destroy(Training $pelatihan)
    {
        $pelatihan->delete();
        return redirect()->route('admin.pelatihan.index')->with('success', 'Pelatihan berhasil dihapus.');
    }

    public function duplicateAsEcourse(int $id)
    {
        $original = Training::with('sessions')->findOrFail($id);
        
        $newTraining = $original->replicate();
        $newTraining->title = $original->title . ' (E-Course)';
        $newTraining->slug = null;
        $newTraining->is_ecourse = true;
        // Keep the original dates to bypass NOT NULL DB constraints,
        // the UI ignores these dates when is_ecourse is true.
        $newTraining->status = 'draft';
        $newTraining->save();

        foreach ($original->sessions as $session) {
            $newSession = $session->replicate();
            $newSession->training_id = $newTraining->id;
            // Keep original session_date, start_time, end_time to bypass DB constraints.
            $newSession->zoom_link = null;
            $newSession->save();
        }

        return redirect()->route('admin.pelatihan.edit', $newTraining->id)
            ->with('success', 'Berhasil diduplikasi menjadi E-Course. Silakan sesuaikan video/materi kursus.');
    }
}
