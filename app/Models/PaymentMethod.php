<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'description', 
        'logo_id', 
        'terms_conditions', 
        'fees'
    ];

    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
