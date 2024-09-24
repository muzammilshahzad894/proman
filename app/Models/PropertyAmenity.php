<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class PropertyAmenity extends Base
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'property_id',
        'amenity_id',
        'value',
    ];
}
