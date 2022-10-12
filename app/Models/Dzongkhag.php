<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dzongkhag extends Model
{
    use HasFactory;
    protected $table = 'dzongkhag';
    protected $guarded = [];

    public function region_name()
    {
        return $this->hasOne('App\Models\Region','id','region_id');
    }
    
}
