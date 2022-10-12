<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityCategory extends Model
{
    use HasFactory;

    protected $table = 'activity_category';
    protected $guarded = [];

    public function subcategory()
    {
        return $this->hasMany('App\Models\ActivitySubCategory','category_id','id');
    }

}

