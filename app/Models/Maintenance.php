<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class Maintenance extends Base
{
    use HasFactory;
    
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
