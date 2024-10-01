<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;
use DateTime;

class Reservation extends Base
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'edited',
        'from_admin',
        'property_id',
        'customer_id',
        'is_an_owner_reservation',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'adults',
        'children',
        'pets',
        'arrival',
        'departure',
        'special_rate',
        'special_rate_note',
        'is_non_profit_reservation',
        'is_add_pet_fee',
        'sales_tax',
        'cancelled',
        'lodgers_tax',
        'pet_fee',
        'cleaning_fee',
        'lodging_amount',
        'total_amount',
        'notes',
        'housekeeper_id',
        'status',
        'send_email',
        'customer_card',
        'customer_profile',
        'customer_payment_profile',
    ];
    
	public function property()
	{
		return $this->belongsTo('App\Models\Property');
	}

	public function customer()
	{
		return $this->belongsTo('App\User', 'customer_id', 'id');
	}

	public function owner()
	{
		return $this->belongsTo('App\User', 'customer_id', 'id');
	}

	public function housekeeper()
	{
		return $this->belongsTo('App\Models\Housekeeper', 'housekeeper_id');
	}

	public function total_price()
	{
		return $this->payments->first()->total;		
	}

	public function deposited_price()
	{
		$payments=$this->payments()->where('payment_mode','!=',"")->get();
		$total_deposited=0;
		foreach ($payments as $payment) {
			$total_deposited=$total_deposited+$payment->amount_deposited;
			
		}
		return $total_deposited-$this->total_refunds();
	}

	public function basic_payment()/*this first payment will must be reocrded*/
	{
		return $this->payments->first();		
	}

	public function payments()
	{
		return $this->hasMany('App\Models\Payment', 'reservation_id', 'id');
	}

	public function getNumberOfDays($arrival, $departure)
	{
		$date1 = new DateTime($arrival);
		$date2 = new DateTime($departure);
		$days  = $date2->diff($date1)->format('%a');
		return $days;
	}
	public function refunds() {
		return $this->hasMany('App\Models\Refund');
	}

	public function total_refunds() {
		return $this->refunds->sum('amount');
	}
	public function due_amount()
	{
		return $this->total_amount-$this->deposited_price();
	}
	public function reminder_due_date()
	{
		$season = getCurrentSeason($this->arrival, $this->departure);
		$balance_days=2;
		if ($season) {
			$balance_days=$season->balance_payment_days+3;
		}
		return date('Y-m-d', strtotime("-$balance_days day", strtotime($this->arrival)));
	}
	public function auto_payment_due_date()
	{
		$season = getCurrentSeason($this->arrival, $this->departure);
		$balance_days=1;
		if ($season) {
			$balance_days=$season->balance_payment_days;
		}
		return date('Y-m-d', strtotime("-$balance_days day", strtotime($this->arrival)));
	}
}
