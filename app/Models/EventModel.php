<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventModel extends Model
{
    use HasFactory;
    protected $table = 'events';
	protected $guarded = [];

    

    public function getRegionDetails(){
        return $this->hasOne('App\Models\Region', 'id', 'region_id');
    }

	 public function getGewogDetails(){
        return $this->hasOne('App\Models\Gewog', 'id', 'gewog_id');
    }


	 public function getDzongkhagDetails(){
        return $this->hasOne('App\Models\Dzongkhag', 'id', 'dzongkhag_id');
    }

	public function getDungkhagDetails(){
        return $this->hasOne('App\Models\Dungkhag', 'id', 'dungkhag_id');
    }


    public function getVillageDetails(){
        return $this->hasOne('App\Models\Village', 'id', 'village_id');
    }


    public function getEventCategoryDetails(){
        return $this->hasOne('App\Models\EventCategoryModel', 'id', 'event_category_id');
    }
}
