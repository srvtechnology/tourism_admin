<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $table = 'activity';
    protected $guarded = [];

    public function region_name()
    {
        return $this->hasOne('App\Models\Region','id','region_id');
    }

    public function dungkhag_name()
    {
        return $this->hasOne('App\Models\Dungkhag','id','dungkhag_id');
    }

    public function dzongkhag_name()
    {
        return $this->hasOne('App\Models\Dungkhag','id','dzongkhag_id');
    }

    public function gewog_name()
    {
        return $this->hasOne('App\Models\Gewog','id','gewog_id');
    }

    public function Village_name()
    {
        return $this->hasOne('App\Models\Village','id','village');
    }

    public function category_name()
    {
        return $this->hasOne('App\Models\ActivityCategory','id','category_id');
    }

    public function subcategory_name()
    {
        return $this->hasOne('App\Models\ActivitySubCategory','id','subcategory_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ActivityImage','activity_id','id');
    }

    public function videos()
    {
        return $this->hasMany('App\Models\ActivityVideo','activity_id','id');
    }

}
