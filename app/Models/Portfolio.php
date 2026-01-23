<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'link',
        'sort_order',
        'color_index',
    ];

    public function locations()
    {
        return $this->belongsToMany(\App\Models\Location::class, 'location_portfolio');
    }
}


