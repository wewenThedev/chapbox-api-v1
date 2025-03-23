<?php

namespace Database\Factories;


use App\Models\ShoppingDetails;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShoppingDetails>
 */
class ShoppingDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ShoppingDetails::class;

    public function definition()
    {
       return [
         'cart_id'    => Cart::factory(),
         // Si tu veux associer les ShoppingDetails à des commandes existantes,
         // tu peux utiliser Order::factory() ou éventuellement laisser cela null pour des paniers non validés.
         'order_id'   => null,
         'shop_id'    => Shop::factory(),
         'product_id' => Product::factory(),
         'added_at'   => $this->faker->dateTime,
         'quantity'   => $this->faker->numberBetween(1, 5),
         'cost'       => $this->faker->randomFloat(2, 1, 100),
       ];
    }
}
