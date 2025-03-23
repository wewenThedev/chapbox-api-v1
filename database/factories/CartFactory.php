<?php

namespace Database\Factories;


use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Cart::class;

    public function definition()
    {
        return [
            // Si l'utilisateur est connecté, on associe un user_id, sinon on peut laisser null
            'user_id'   => $this->faker->unique()->optional()->randomElement(User::pluck('id')->toArray()),
            'device_id' => $this->faker->uuid,
        ];
    }
}
