<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelToDestination extends Model
{
    use HasFactory;
    protected $table = 'hotel_accessible_destination';
    protected $guarded = [];

    public function destination_name()
    {
    	return $this->hasOne('App\Models\Destination','id','destination_id');
    }
}
