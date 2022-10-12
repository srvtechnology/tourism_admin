<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsSubCategory extends Model
{
    use HasFactory;
    protected $table = 'cms_sub_category';
    protected $guarded = [];

    public function category_name()
    {
        return $this->hasOne('App\Models\CmsCategory','id','category_id');
    }

    public function sub_subcategory_name()
    {
        return $this->hasMany('App\Models\CmsSubSubCategory','sub_category_id','id');
    }
}
