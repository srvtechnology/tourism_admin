<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gewog extends Model
{
    use HasFactory;

    protected $table = 'gewog';
    protected $guarded = [];

    public function dzongkhag_name()
    {
        return $this->hasOne('App\Models\Dzongkhag','id','dzongkhag_id');
    }

    public function dunkhag_name()
    {
        return $this->hasOne('App\Models\Dungkhag','id','dungkhag_id');
    }
}
