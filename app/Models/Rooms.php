<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $guarded = [];

    public function hotel_name()
    {
    	return $this->hasOne('App\Models\Hotel','id','hotel_id');
    }
}
