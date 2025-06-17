<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'display_name'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}
