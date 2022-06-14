<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotel';
    protected $guarded = [];

    public function gewog()
    {
    	return $this->hasOne('App\Models\Gewog','id','gewog_id');
    }

    public function dzongkhag()
    {
    	return $this->hasOne('App\Models\Dzongkhag','id','dzongkhag_id');
    }

    public function selectedDestination()
    {
        return $this->hasMany('App\Models\HotelToDestination','hotel_id','id');
    }
}
