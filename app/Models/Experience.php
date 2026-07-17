<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['profile_id', 'position', 'institution', 'period', 'description', 'order'];
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}