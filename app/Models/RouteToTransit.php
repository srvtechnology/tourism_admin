<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteToTransit extends Model
{
    use HasFactory;

    protected $table = 'route_to_transit';
    protected $guarded = [];

    public function airport_name()
    {
    	return $this->hasOne('App\Models\Airports','id','airport_id');
    }

    public function airline_name()
    {
    	return $this->hasOne('App\Models\Airlines','id','airline_id');
    }
}
