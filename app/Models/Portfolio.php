<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'link',
        'sort_order',
    ];

    public function locations()
    {
        return $this->belongsToMany(\App\Models\Location::class, 'location_portfolio');
    }
}


