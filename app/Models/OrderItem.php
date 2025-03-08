<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_items';

    /**
     * Relation avec la commande.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relation pour associer les produits.
     */
    public function shoppingDetail()
    {
        return $this->belongsTo(ShoppingDetails::class);
    }
}
