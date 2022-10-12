<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategoryModel extends Model
{
    use HasFactory;
    protected $table = 'event_category';
	protected $guarded = [];
}
