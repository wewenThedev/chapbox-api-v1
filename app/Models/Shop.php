<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['city', 'phone', 'address_id', 'supermarket_id', 'shop_manager_id'];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function supermarket()
    {
        return $this->belongsTo(Supermarket::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'shop_manager_id');
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'shop_media');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'shop_product')->withPivot('price', 'stock')->withTimestamps();
    }
}
