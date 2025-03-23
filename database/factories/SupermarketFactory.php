<?php

namespace Database\Factories;



use App\Models\Supermarket;
use App\Models\Address;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supermarket>
 */
class SupermarketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Supermarket::class;

    public function definition()
    {
       return [
         'name'             => $this->faker->company,
         'description'      => $this->faker->paragraph,
         'denomination'     => $this->faker->word,
         'rccm'             => $this->faker->bothify('RC##??'),
         'ifu'              => $this->faker->bothify('IFU-####'),
         'website'          => $this->faker->url,
         'address_id'       => Address::factory(),
         'logo_id'          => null,
         'market_manager_id'=> User::factory(),
       ];
    }
}
