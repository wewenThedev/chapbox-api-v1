<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Address::class;
    
    public function definition(): array
    {
        // Villes principales au Bénin où se trouvent des supermarchés
        $cities = [
            'Cotonou',
            'Porto-Novo',
            'Parakou',
            'Abomey-Calavi',
            'Natitingou'
        ];

        return [
            'name' => fake()->company() . ' Supermarket', // Ex: "Supermarché Casiano"
            'fullAddress' => fake()->streetAddress() . ', ' . fake()->randomElement($cities) . ', Bénin',
            
            // Plage de coordonnées GPS couvrant le Bénin
            'latitude' => fake()->randomFloat(8, 6.30000000, 12.40000000), // Latitude Bénin
            'longitude' => fake()->randomFloat(8, 0.60000000, 3.80000000), // Longitude Bénin
            
            'user_id' => null // À adapter si lié à un utilisateur
        ];
    }
}
