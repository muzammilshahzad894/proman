<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class Payment extends Base
{
    use HasFactory;
    
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function refunds()
    {
        return $this->hasMany('App\Models\Refund');
    }
    
    public function without_refund()
    {
        return $this->amount_deposited - $this->refunds()->sum('amount');
    }
}
