<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Base;

class Season extends Base
{
    private $propertyid;
    
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'title',
        'from_month',
        'from_day',
        'to_month',
        'to_day',
        'type',
        'show_on_frontend',
        'allow_weekly_rates',
        'allow_monthly_rates',
        'balance_payment_days',
        'minimum_nights',
        'display_order',
    ];

    public function abc()
    {
        return $this->belongsToMany('App\Models\Property', 'seasons_rates', 'season_id', 'property_id')
            ->withPivot('daily_rate', 'weekly_rate', 'monthly_rate', 'deposit')
            ->withTimestamps();
    }

    public function rates($value = '')
    {
        $this->propertyid = $value;
        return $this->abc;
    }
}
