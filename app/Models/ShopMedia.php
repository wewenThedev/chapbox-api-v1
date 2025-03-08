<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ShopMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['shop_id', 'media_id'];

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function media(){
        return $this->belongsTo(Media::class, 'media_id');;
    }
}
