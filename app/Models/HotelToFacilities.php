<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelToFacilities extends Model
{
    use HasFactory;

    protected $table = 'hotel_to_facilities';
    protected $guarded = [];
}
