<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'description', 'type', 'supermarket_id', 'discount'];

    public function supermarket()
    {
        return $this->belongsTo(Supermarket::class);
    }
}
