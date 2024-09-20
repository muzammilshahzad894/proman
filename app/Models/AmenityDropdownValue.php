<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmenityDropdownValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'amenity_id',
        'value',
    ];
}
