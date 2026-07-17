<?php

use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('home');

Route::put('/profile/{profile}', [ProfileController::class, 'update'])->name('profile.update');

Route::post('/experiences', [ExperienceController::class, 'store'])->name('experiences.store');
Route::put('/experiences/{experience}', [ExperienceController::class, 'update'])->name('experiences.update');
Route::delete('/experiences/{experience}', [ExperienceController::class, 'destroy'])->name('experiences.destroy');

Route::post('/educations', [EducationController::class, 'store'])->name('educations.store');
Route::put('/educations/{education}', [EducationController::class, 'update'])->name('educations.update');
Route::delete('/educations/{education}', [EducationController::class, 'destroy'])->name('educations.destroy');

Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
Route::put('/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');