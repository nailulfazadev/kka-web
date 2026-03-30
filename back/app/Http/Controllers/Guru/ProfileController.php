<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('guru.profil', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'school' => 'nullable|string|max:255',
            'nuptk' => 'nullable|string|max:30',
        ]);

        auth()->user()->update($request->only('name', 'phone', 'school', 'nuptk'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
