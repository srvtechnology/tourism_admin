<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourItinerary extends Model
{
    use HasFactory;

    protected $table = 'tour_itinerary';
    protected $guarded = [];

    public function category_name()
    {
    	return $this->hasOne('App\Models\TourCategory','id','category_id');
    }

    public function region_name()
    {
    	return $this->hasOne('App\Models\Region','id','region_id');
    }
}
