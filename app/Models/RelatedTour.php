<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatedTour extends Model
{
    use HasFactory;
    protected $table = 'related_tour';
    protected $guarded = [];

    public function tour_name()
    {
        return $this->hasOne('App\Models\TourItinerary','id','related_tour_id')->select('id','name');
    }
}
