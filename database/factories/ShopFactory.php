<?php

namespace Database\Factories;


use App\Models\Shop;
use App\Models\Address;
use App\Models\Supermarket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Shop::class;

    public function definition()
    {
        $cities = [
            'Cotonou',
            'Porto-Novo',
            'Parakou',
            'Abomey-Calavi',
            'Natitingou'
        ];
       return [
         'city'             => fake()->randomElement($cities),
         'phone'            => $this->faker->regexify('(229)(6[25-9]|7[0-9])[0-9]{6}'),
         'address_id'       => Address::factory(),
         'supermarket_id'   => Supermarket::factory(),
         'shop_manager_id'  => User::factory(),
       ];
    }
}
