<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExperienceRequest;
use App\Models\Experience;
use App\Models\Profile;

class ExperienceController extends Controller
{
    public function store(ExperienceRequest $request)
    {
        $profile = Profile::first();
        $data = $request->validated();
        $data['profile_id'] = $profile->id;
        $data['order'] = $profile->experiences()->max('order') + 1;
        Experience::create($data);
        return redirect()->route('home', ['edit' => 1])->with('success', 'Pengalaman berhasil ditambahkan.');
    }

    public function update(ExperienceRequest $request, Experience $experience)
    {
        $experience->update($request->validated());
        return redirect()->route('home', ['edit' => 1])->with('success', 'Pengalaman berhasil diperbarui.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();
        return redirect()->route('home', ['edit' => 1])->with('success', 'Pengalaman berhasil dihapus.');
    }
}