<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
    protected $table = 'village';
    protected $guarded = [];

    public function dzongkhag_name()
    {
        return $this->hasOne('App\Models\Dzongkhag','id','dzongkhag_id');
    }

    public function dungkhag_name()
    {
        return $this->hasOne('App\Models\Dungkhag','id','dunkhag_id');
    }

    public function gewog_name()
    {
        return $this->hasOne('App\Models\Gewog','id','gewog_id');
    }

}
