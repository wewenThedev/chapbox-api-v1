<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supermarket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'denomination', 'rccm', 'ifu', 'website', 'address_id', 'logo_id', 'market_manager_id'];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'market_manager_id');
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }
}
