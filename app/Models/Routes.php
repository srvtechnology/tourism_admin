<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routes extends Model
{
    use HasFactory;

    protected $table = 'routes';
    protected $guarded = [];

    public function departure_airport()
    {
    	return $this->hasOne('App\Models\Airports','id','departure_airport_id');
    }

    public function arrival_airport()
    {
    	return $this->hasOne('App\Models\Airports','id','arrival_airport_id');
    }

    public function departure_airline()
    {
    	return $this->hasOne('App\Models\Airlines','id','departure_airline_id');
    }

    public function arrival_airline()
    {
    	return $this->hasOne('App\Models\Airlines','id','arrival_airline_id');
    }
}
