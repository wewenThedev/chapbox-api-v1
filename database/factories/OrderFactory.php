<?php

namespace Database\Factories;


use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Order::class;

    public function definition()
    {
       return [
         'user_id'         => $this->faker->optional()->randomElement(\App\Models\User::pluck('id')->toArray()) ?: User::factory(),
         'guest_firstname' => $this->faker->firstName,
         'guest_lastname'  => $this->faker->lastName,
         'guest_phone'     => $this->faker->regexify('(229)(6[25-9]|7[0-9])[0-9]{6}'),
         'guest_email'     => $this->faker->safeEmail,
         'total_ht'        => $this->faker->randomFloat(2, 10, 1000),
         'total_ttc'       => $this->faker->randomFloat(2, 10, 1000),
         'ordering_date'   => $this->faker->dateTime,
         'shipping_date'   => $this->faker->optional()->dateTime,
         'recovery_mode'   => $this->faker->randomElement(['pickup', 'delivery']),
         'shipping_address'=> $this->faker->address,
         'status'          => $this->faker->randomElement(['pending', 'processing', 'failed', 'successful', 'canceled']),
       ];
    }
}
