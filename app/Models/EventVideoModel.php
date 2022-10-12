<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventVideoModel extends Model
{
    use HasFactory;

    protected $table = 'event_videos';
	protected $guarded = [];


    public function getEventDetails(){
        return $this->hasOne('App\Models\EventModel', 'id', 'event_id');
    }
}
