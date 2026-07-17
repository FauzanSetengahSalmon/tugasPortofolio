<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $profile = Profile::first();

        $experiences = $profile?->experiences ?? collect();
        $educations = $profile?->educations ?? collect();
        $projects = $profile?->projects ?? collect();
        $skills = $profile?->skills ?? collect();

        $editMode = $request->query('edit') == 1;

        return view('portfolio', compact(
            'profile',
            'experiences',
            'educations',
            'projects',
            'skills',
            'editMode'
        ));
    }
}
