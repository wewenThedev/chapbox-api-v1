<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ShoppingDetails;
use App\Models\User;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', // ID de l'utilisateur
        'device_id', // ID de l'appareil
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shoppingDetails()
    {
        return $this->hasMany(ShoppingDetails::class);
    }

    public function products(){
        return $this->hasManyThrough(Product::class, ShoppingDetails::class, 'shopping_details_id', 'product_id');
        //return $this->hasMany(Product::class);

    }

    /*
    public function products(){
        return $this->hasMany(ShopProduct::class);
    }*/

    
}
