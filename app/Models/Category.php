<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // sub categories
    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // parent category
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');  
    }

    // sub_categories with parent
    public function subcategories()
    {
        return $this->hasMany(SubCategory::class);
    }   
}
