<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'guest_firstname',
        'guest_lastname',
        'guest_phone',
        'guest_email',
        'total_ht',
        'total_ttc',
        'ordering_date',
        'shipping_date',
        'shipping_address',
        'status'
    ];

    public function shoppingDetails()
    {
        return $this->hasMany(ShoppingDetails::class);
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

    /*public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }*/

    public function calculateTotalTtc(){
        if($this->recovery_mode === 'delivery'){
            return $this->total_ht + 1000;
        }else if($this->recovery_mode === 'pickup'){
            return (1.05)*$this->total_ht;
        }
    }

    //$this->total_ht = $order->total_amount + $order->delivery_fee;

    /*$total_ht = $order->total_amount + $order->delivery_fee;

// Récupérer la méthode de paiement choisie
$paymentMethod = PaymentMethod::find($request->payment_method_id);

// Calculer les frais supplémentaires en fonction de la méthode de paiement
$paymentFee = $total_ht * $paymentMethod->fee_rate;

// Calculer le montant final
$finalAmount = $total_ht + $paymentFee;

// Mettre à jour le montant final dans la commande
$order->update([
    'final_amount' => $finalAmount,
    'payment_method_id' => $paymentMethod->id,
]);

Return response()->json([
    'final_amount' => $finalAmount,
    'payment_fee' => $paymentFee,
    'payment_method' => $paymentMethod->name,
]);
*/
}
