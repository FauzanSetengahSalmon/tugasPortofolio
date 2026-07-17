<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = ['profile_id', 'institution', 'degree', 'period', 'description', 'order'];
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}