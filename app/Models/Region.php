<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $table = 'regions';
    protected $guarded = [];

    public function dzongkhags()
    {
        return $this->hasMany('App\Models\Dzongkhag','region_id','id');
    }
}
