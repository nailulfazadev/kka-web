<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::with(['user', 'training', 'payments'])->whereIn('status', ['aktif', 'selesai', 'pending']);
        if ($request->filled('training_id')) $query->where('training_id', $request->training_id);
        $registrations = $query->latest()->paginate(20);
        $trainings = \App\Models\Training::orderBy('title')->get();
        return view('admin.peserta.index', compact('registrations', 'trainings'));
    }
}
