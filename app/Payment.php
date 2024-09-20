<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const CREDIT_CARD = 'credit_card';

    /**
     * @var array
     */
    protected $guarded = ['image','resume'];
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function gift_order()
    {
        return $this->belongsTo(GiftOrder::class,'gift_order_id');
    }

    public function refunds()
    {
        return $this->hasMany('App\Models\TourReservationRefund','payment_id');
    }
    public function scheduledRefunds()
    {
        return $this->hasMany('App\ScheduledRefund','payment_id');
    }

    public function is_refunded()
    {
        return $this->refunds->sum('amount')==$this->amount?1:0;
    }
    public function  alreadyCaptured()
    {
        if($this->payment_method == 'credit_card_auth' || $this->payment_method == 'Capture Auth Amount' )
        {
            $payments = Payment::where('reservation_id',$this->reservation_id)->where('payment_method','Capture Auth Amount')->count();
            if($payments>0)
            {
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public function human_payment_method()
    {
        return ucwords(str_replace("_", " ", $this->payment_method));
    }
}
