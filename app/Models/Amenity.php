<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class Amenity extends Base
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'title',
        'type',
        'group',
        'display_order',
        'option',
    ];
}
