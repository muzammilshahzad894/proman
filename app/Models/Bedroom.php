<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bedroom extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'title',
        'display_order',
    ];
}
