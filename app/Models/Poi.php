<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poi extends Model
{
    use HasFactory;
    protected $table = 'poi';
    protected $guarded = [];

    public function dzongkhag_name()
    {
        return $this->hasOne('App\Models\Dzongkhag','id','dzongkhag');
    }

    public function dungkhag_name()
    {
        return $this->hasOne('App\Models\Dungkhag','id','dungkhag');
    }

    public function gewog_name()
    {
        return $this->hasOne('App\Models\Gewog','id','gewog');
    }

    public function village_name()
    {
        return $this->hasOne('App\Models\Village','id','village');
    }

    public function region_name()
    {
        return $this->hasOne('App\Models\Region','id','region');
    }

    public function theme_name()
    {
         return $this->hasOne('App\Models\ThemeCategory','id','theme_category');
    }

    public function attraction_category_name()
    {
         return $this->hasOne('App\Models\AttractionCategoryModel','id','attraction_category');
    }
}
