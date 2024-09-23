<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class Housekeeper extends Base
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'address1',
        'address2',
        'city',
        'state',
        'zip_code',
        'phone',
        'notes',
        'display_order',
    ];
}
