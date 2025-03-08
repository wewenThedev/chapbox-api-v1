<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id', 
        'method_id', 
        'paid_at', 
        'status', 
        'code_promo_id', 
        'total_paid', 
        'details', 
        'invoice_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function code_promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
