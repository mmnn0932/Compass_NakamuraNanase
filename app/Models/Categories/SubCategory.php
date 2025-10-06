<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories\MainCategory;
use App\Models\Posts\Post;

class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];
    public function mainCategory(){
        return $this->belongsTo(MainCategory::class);
        // リレーションの定義
    }

    public function posts(){
        return $this->belongsToMany(
        Post::class, 'post_sub_categories', 'sub_category_id', 'post_id');
        // リレーションの定義
    }
}
