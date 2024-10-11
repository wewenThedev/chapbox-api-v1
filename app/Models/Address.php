<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'fullAddress', 'latitude', 'longitude'];

    public function supermarkets()
    {
        return $this->hasMany(Supermarket::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }
}
