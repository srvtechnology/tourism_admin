<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourToHotel extends Model
{
    use HasFactory;
    protected $table = 'tour_to_hotel';
    protected $guarded = [];

    public function hotel_name()
    {
    	return $this->hasOne('App\Models\Hotel','id','hotel_name')->select(['id','name']);
    }
}
