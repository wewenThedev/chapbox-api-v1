<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ShopProduct;


use Illuminate\Support\Facades\DB;
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

     protected $model = ShopProduct::class;
    public function definition(): array
    {

        return DB::transaction(function () {
            $shopId = $this->faker->randomElement([25, 30]);
            $productId = $this->faker->numberBetween(40, 50);
    
            // Verrouillage pour éviter les conflits
            $exists = ShopProduct::lockForUpdate()
                ->where('shop_id', $shopId)
                ->where('product_id', $productId)
                ->exists();
    
            if ($exists) {
                throw new \Exception("Collision détectée pour $shopId-$productId");
            }

        /*do{
            $shopId = Shop::inRandomOrder()->first()->id;
            $productId = Product::inRandomOrder()->first()->id;
        }while(ShopProduct::where('shop_id', $shopId)->where('product_id', $productId)->exists());
*/
        return [
            'shop_id' => $shopId,
            'product_id' => $productId,
            'price' => $this->faker->randomFloat(2, 500, 25000),
            'stock' => $this->faker->numberBetween(0, 100),
        ];
        /*return [
        'shop_id'    => Shop::factory(),
         'product_id' => Product::factory(),
         'price'      => $this->faker->randomFloat(2, 500, 25000),
         'stock'      => $this->faker->numberBetween(0, 100),
        ];*/
    });
    }
}
