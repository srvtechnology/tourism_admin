<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeCategory extends Model
{
    use HasFactory;

    protected $table = 'theme_category';
    protected $guarded = [];
}
