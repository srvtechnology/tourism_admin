<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelToVideos extends Model
{
    use HasFactory;
    protected $table = 'hotel_to_videos';
    protected $guarded = [];
}
