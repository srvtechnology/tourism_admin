<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostModel extends Model
{
    use HasFactory;
    protected $table = 'blog_post';
    protected $guarded = [];

    public function getPostDetails(){
        return $this->hasOne('App\Models\BlogCategoryModel', 'id', 'blog_category_id');
    }
    

}
