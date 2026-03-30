<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Training;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $trainings = Training::with('sessions')->orderBy('title')->get();
        $attendances = collect();
        $selectedTraining = null;

        if ($request->filled('training_id')) {
            $selectedTraining = Training::with(['sessions', 'registrations.user'])->find($request->training_id);
            if ($selectedTraining) {
                $sessionIds = $selectedTraining->sessions->pluck('id');
                $attendances = Attendance::whereIn('session_id', $sessionIds)->with(['user', 'session'])->get()->groupBy('user_id');
            }
        }

        return view('admin.presensi.index', compact('trainings', 'attendances', 'selectedTraining'));
    }

    public function export(Request $request)
    {
        // TODO: Integrate Maatwebsite/Excel
        return back()->with('success', 'Fitur export akan segera tersedia.');
    }
}
