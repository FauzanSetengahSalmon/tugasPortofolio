<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'name',
        'headline',
        'bio',
        'avatar',
        'email',
        'linkedin',
        'github',
    ];
    public function experiences()
    {
        return $this->hasMany(Experience::class)->orderBy('order');
    }
    public function educations()
    {
        return $this->hasMany(Education::class)->orderBy('order');
    }
    public function projects()
    {
        return $this->hasMany(Project::class)->orderBy('order');
    }
    public function skills()
    {
        return $this->hasMany(Skill::class)->orderBy('order');
    }
}
