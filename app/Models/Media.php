<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'medias';

    protected $fillable = ['name', 'url', 'type', 'description'];

    public function users()
    {
        return $this->hasMany(User::class, 'picture_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_media');
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_media');
    }
}
