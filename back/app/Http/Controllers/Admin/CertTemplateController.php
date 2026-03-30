<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;

class CertTemplateController extends Controller
{
    public function index()
    {
        $templates = CertificateTemplate::with('training')->latest()->paginate(15);
        return view('admin.sertifikat.index', compact('templates'));
    }

    public function create() { return view('admin.sertifikat.create'); }

    public function store(Request $request)
    {
        $v = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'name' => 'required|string|max:255',
            'front_image' => 'required|image|max:5120',
            'back_image' => 'nullable|image|max:5120',
            'name_x' => 'required|numeric', 'name_y' => 'required|numeric',
            'name_font_size' => 'required|integer|min:8|max:72',
            'name_color' => 'required|string',
        ]);

        $frontPath = $request->file('front_image')->store('cert-templates', 'public');
        $backPath = $request->hasFile('back_image') ? $request->file('back_image')->store('cert-templates', 'public') : null;

        CertificateTemplate::create([
            'training_id' => $v['training_id'], 'name' => $v['name'],
            'front_image' => $frontPath, 'back_image' => $backPath,
            'name_position' => ['x' => $v['name_x'], 'y' => $v['name_y'], 'fontSize' => $v['name_font_size'], 'color' => $v['name_color']],
        ]);

        return redirect()->route('admin.cert-template.index')->with('success', 'Template sertifikat berhasil dibuat.');
    }

    public function edit(CertificateTemplate $sertifikat_template)
    {
        return view('admin.sertifikat.edit', ['template' => $sertifikat_template]);
    }

    public function update(Request $request, CertificateTemplate $sertifikat_template)
    {
        $v = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'name' => 'required|string|max:255',
            'front_image' => 'nullable|image|max:5120',
            'back_image' => 'nullable|image|max:5120',
            'name_x' => 'required|numeric', 'name_y' => 'required|numeric',
            'name_font_size' => 'required|integer|min:8|max:72',
            'name_color' => 'required|string',
        ]);

        $data = [
            'training_id' => $v['training_id'], 'name' => $v['name'],
            'name_position' => ['x' => $v['name_x'], 'y' => $v['name_y'], 'fontSize' => $v['name_font_size'], 'color' => $v['name_color']],
        ];

        if ($request->hasFile('front_image')) {
            $data['front_image'] = $request->file('front_image')->store('cert-templates', 'public');
        }
        if ($request->hasFile('back_image')) {
            $data['back_image'] = $request->file('back_image')->store('cert-templates', 'public');
        }

        $sertifikat_template->update($data);

        return redirect()->route('admin.cert-template.index')->with('success', 'Template sertifikat berhasil diperbarui.');
    }

    public function destroy(CertificateTemplate $sertifikat_template)
    {
        $sertifikat_template->delete();
        return back()->with('success', 'Template berhasil dihapus.');
    }

    public function preview(int $id)
    {
        // TODO: Generate preview
        return back()->with('success', 'Preview akan segera tersedia.');
    }
}
