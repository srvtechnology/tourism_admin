<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourToFlight extends Model
{
    use HasFactory;
    protected $table = 'tour_to_flight';
    protected $guarded = [];
}
