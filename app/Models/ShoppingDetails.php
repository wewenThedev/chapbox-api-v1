<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Collection;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Shop;

use App\Models\ShopProduct;
use App\Models\Order;


class ShoppingDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cart_id',
        'order_id', 
        'shop_id', 
        'product_id', 
        'added_at', 
        'quantity', 
        'cost'
    ];

    protected $table = 'shopping_details';

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function calculateCost(){
        //dd($this);
        //$productAdded = ShopProduct::select('price')->where('product_id', $this->product_id)->where('shop_id', $this->shop_id)->get('price');
        $productAdded = ShopProduct::select('price')->where('product_id', $this->product_id)->where('shop_id', $this->shop_id)->first();
        //dd($productAdded);
        if($productAdded instanceof Collection){
            //dd($productAdded->pluck('price')[0]);
            $productAddedPrice = $productAdded->pluck('price')[0];
        }else{
            $productAddedPrice = $productAdded->price;
        }
        //dd($productAddedPrice);
        //check if quantity < 0 before to calculate
        return $this->quantity*$productAddedPrice;
    }

    public function getProductsInCart(){
        $shoppingDetailsToIgnore = Order::where('shopping_details_id','in',ShoppingDetails::where('cart_id', $this->cart_id))->get('shopping_details_id');
        dd($shoppingDetailsToIgnore);
        //vérifier le product.id
        $instanceToConsider = ShoppingDetails::where('id', 'not in', $shoppingDetailsToIgnore)->get(['shop_id', 'product.id']);
        dd($instanceToConsider);
        $result = ShopProduct::where('product_id', $instanceToConsider->product_id)->where('shop_id', $instanceToConsider->shop_id);
        dd($result);
        return $result;
    }

    public function getShopForCart($cart_id){
        return ShoppingDetails::where('cart_id', $cart_id)->get('shop_id');
    }
}
