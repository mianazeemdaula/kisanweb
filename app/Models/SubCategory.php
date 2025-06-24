<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id','name_ur','slug'];
    protected $casts = [
        'category_id' => 'integer',
    ];

    /**
     * Get the category that owns the subcategory.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the deals for the subcategory.
     */
    public function deals()
    {
        return $this->hasMany(CategoryDeal::class);
    }
    
}
