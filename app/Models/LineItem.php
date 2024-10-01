<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class LineItem extends Base
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'title',
        'type',
        'percentage_apply_type',
        'value',
        'display_order',
    ];

    public function lineItemAmount($lodging_amount_base = 0)
    {
        $amount = 0;

        if ($this->type == 'Fixed') {
            $amount =  $this->value;
        }
        
        if ($this->type == 'Percentage' && $this->value != 0) {
            $amount = $lodging_amount_base * $this->value / 100;
        }
        return $amount;
    }
}
