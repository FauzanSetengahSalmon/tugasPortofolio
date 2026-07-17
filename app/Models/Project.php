<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['profile_id', 'name', 'description', 'link', 'tech_stack', 'thumbnail', 'order'];
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
    public function techStackArray(): array
    {
        return $this->tech_stack ? array_map('trim', explode(',', $this->tech_stack)) : [];
    }
}