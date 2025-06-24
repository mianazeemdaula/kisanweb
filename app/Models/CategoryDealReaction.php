<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDealReaction extends Model
{
    use HasFactory;

    // belongs to category deal
    public function categoryDeal()
    {
        return $this->belongsTo(CategoryDeal::class);
    }

    // belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
