<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivitySubCategory extends Model
{
    use HasFactory;

    protected $table = 'activity_sub_category';
    protected $guarded = [];

    public function category_name()
    {
        return $this->hasOne('App\Models\ActivityCategory','id','category_id');
    }
}
