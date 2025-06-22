<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDeal extends Model
{
    use HasFactory;

    // belongsToMany relationship with Category
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
