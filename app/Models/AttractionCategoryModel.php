<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttractionCategoryModel extends Model
{
    use HasFactory;

    protected $table = 'attraction_category';
    protected $guarded = [];
}
