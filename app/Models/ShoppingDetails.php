<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['cart_id', 'shop_id', 'product_id', 'added_at', 'quantity', 'cost'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
