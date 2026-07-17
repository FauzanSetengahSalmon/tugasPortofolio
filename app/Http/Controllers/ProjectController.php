<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function store(ProjectRequest $request)
    {
        $profile = Profile::first();
        $data = $request->validated();
        $data['profile_id'] = $profile->id;
        $data['order'] = $profile->projects()->max('order') + 1;
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }
        Project::create($data);
        return redirect()->route('home', ['edit' => 1])->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }
        $project->update($data);
        return redirect()->route('home', ['edit' => 1])->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }
        $project->delete();
        return redirect()->route('home', ['edit' => 1])->with('success', 'Proyek berhasil dihapus.');
    }
}