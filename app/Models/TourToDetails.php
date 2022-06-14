<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourToDetails extends Model
{
    use HasFactory;
    protected $table = 'tour_to_details';
    protected $guarded = [];

    public function destination_name()
    {
    	return $this->hasOne('App\Models\Destination','id','destination');
    }
}
