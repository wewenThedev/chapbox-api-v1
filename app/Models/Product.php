<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'brand_id', 'description', 'weight', 'category_id', 'container_type'];
    //protected $table = 'products';

    public function description(){
        return $this->name.' '.$this->brand->name.' '.$this->weight;
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'product_media');
    }

    public function shops(){
        return $this->belongsToMany(Shop::class, 'shop_product')->withPivot('price', 'stock')->withTimestamps();
    }
    

}
