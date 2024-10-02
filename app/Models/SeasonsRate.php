<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Base;

class SeasonsRate extends Base
{
    use HasFactory;
    
    protected $fillable = [
        'uuid',
        'season_id',
        'property_id',
        'daily_rate',
        'monthly_rate',
        'deposit',
    ];
    
	public function property($property_id='')
	{	
		return $this->hasOne( 'App\Models\Property',  'porperty_id'); 
			
	}
}
