<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addresses';
    protected $fillable = ['name', 'fullAddress', 'latitude', 'longitude', 'user_id'];

    public function supermarkets()
    {
        return $this->hasMany(Supermarket::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
