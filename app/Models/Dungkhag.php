<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dungkhag extends Model
{
    use HasFactory;
    protected $table = 'dungkhag';
    protected $guarded = [];

    public function dzongkhag_name()
    {
        return $this->hasOne('App\Models\Dzongkhag','id','dzongkhag_id');
    }
}
