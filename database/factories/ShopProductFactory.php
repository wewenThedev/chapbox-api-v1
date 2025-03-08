<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ShopProduct;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShopProduct>
 */
class ShopProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        do{
            $shopId = Shop::inRandomOrder()->first()->id;
            $productId = Product::inRandomOrder()->first()->id;
        }while(ShopProduct::where('shop_id', $shopId)->where('product_id', $productId)->exists());

        return [
            'shop_id' => $shopId,
            'product_id' => $productId,
            'price' => $this->faker->randomFloat(2, 500, 25000),
            'stock' => $this->faker->numberBetween(0, 5),
        ];
    }
}
