<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';
    
    protected $fillable = [
        'name'
    ];

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(\App\Models\Classroom::class, 'classroom_student');
    }
} 