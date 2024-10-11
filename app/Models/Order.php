<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['shopping_details_id', 'user_id', 'guest_firstname', 'guest_lastname', 'guest_phone', 'guest_email', 'total_ht', 'total_ttc', 'ordering_date', 'shipping_date', 'shipping_address', 'status'];

    public function shoppingDetails()
    {
        return $this->belongsTo(ShoppingDetails::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}
