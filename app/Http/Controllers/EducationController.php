<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationRequest;
use App\Models\Education;
use App\Models\Profile;

class EducationController extends Controller
{
    public function store(EducationRequest $request)
    {
        $profile = Profile::first();
        $data = $request->validated();
        $data['profile_id'] = $profile->id;
        $data['order'] = $profile->educations()->max('order') + 1;
        Education::create($data);
        return redirect()->route('home', ['edit' => 1])->with('success', 'Pendidikan berhasil ditambahkan.');
    }

    public function update(EducationRequest $request, Education $education)
    {
        $education->update($request->validated());
        return redirect()->route('home', ['edit' => 1])->with('success', 'Pendidikan berhasil diperbarui.');
    }

    public function destroy(Education $education)
    {
        $education->delete();
        return redirect()->route('home', ['edit' => 1])->with('success', 'Pendidikan berhasil dihapus.');
    }
}