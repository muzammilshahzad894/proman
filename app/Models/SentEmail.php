<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class SentEmail extends Base
{
    use HasFactory;
    
    protected $table = 'sentemails';

    protected $fillable = [
        'uuid',
        'sentto',
        'gemail',
        'guest',
        'subject',
        'status',
        'attachment',
        'body',
        'eemail',
        'reservation_id',
        'sentto',
    ];
}
