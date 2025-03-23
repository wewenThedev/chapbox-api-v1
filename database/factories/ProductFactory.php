<?php

namespace Database\Factories;



use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition()
    {
       return [
         'name'         => $this->faker->word,
         //'brand_id'     => Brand::inRandomOrder()->first()->id,
         'brand_id'     => $this->faker->numberBetween(30, 43),
         'description'  => $this->faker->paragraph,
         'weight'       => $this->faker->randomFloat(2, 100, 2000), // en grammes
         'category_id'  => Category::factory(),
         'container_type' => $this->faker->randomElement(['bottle', 'box', 'bag', 'pack']),
       ];
    }
}
