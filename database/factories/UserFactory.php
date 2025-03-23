<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
       return [
           'firstname'    => $this->faker->firstName,
           'lastname'     => $this->faker->lastName,
           'username'     => $this->faker->unique()->userName,
           'phone'        => $this->faker->unique()->regexify('(229)(6[25-9]|7[0-9])[0-9]{6}'),
           'email'        => $this->faker->unique()->safeEmail,
           'email_verified_at' => now(),
           'password'     => bcrypt('password'),
           'profile_id'   => Profile::inRandomOrder()->first()->id, // <-- Clé ici
           'picture_id'   => null,
            //'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
