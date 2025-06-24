<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDealBid extends Model
{
    use HasFactory;

    // belongs to category deal
    public function categoryDeal()
    {
        return $this->belongsTo(CategoryDeal::class);
    }

    // belongs to buyer
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
