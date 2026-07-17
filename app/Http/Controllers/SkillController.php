<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillRequest;
use App\Models\Profile;
use App\Models\Skill;

class SkillController extends Controller
{
    public function store(SkillRequest $request)
    {
        $profile = Profile::first();
        $data = $request->validated();
        $data['profile_id'] = $profile->id;
        $data['order'] = $profile->skills()->max('order') + 1;
        Skill::create($data);
        return redirect()->route('home', ['edit' => 1])->with('success', 'Skill berhasil ditambahkan.');
    }

    public function update(SkillRequest $request, Skill $skill)
    {
        $skill->update($request->validated());
        return redirect()->route('home', ['edit' => 1])->with('success', 'Skill berhasil diperbarui.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();
        return redirect()->route('home', ['edit' => 1])->with('success', 'Skill berhasil dihapus.');
    }
}