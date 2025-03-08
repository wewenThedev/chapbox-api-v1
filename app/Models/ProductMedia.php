<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_id', 'media_id'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function media(){
        return $this->belongsTo(Media::class, 'media_id');;
    }
}
