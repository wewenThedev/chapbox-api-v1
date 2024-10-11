<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
