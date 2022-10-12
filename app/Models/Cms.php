<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    use HasFactory;
    protected $table = 'cms';
    protected $guarded = [];

    public function category_name()
    {
        return $this->hasOne('App\Models\CmsCategory','id','category_id');
    }

    public function subcategory_name()
    {
        return $this->hasOne('App\Models\CmsSubCategory','id','sub_category_id');
    }

    public function sub_subcategory_name()
    {
        return $this->hasOne('App\Models\CmsSubSubCategory','id','sub_sub_category_id');
    }
}
