<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoiImages extends Model
{
    use HasFactory;

    protected $table = 'poi_image';
    protected $guarded = [];
}
