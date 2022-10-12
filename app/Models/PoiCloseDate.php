<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoiCloseDate extends Model
{
    use HasFactory;
    protected $table = 'poi_close_date';
    protected $guarded = [];
}
