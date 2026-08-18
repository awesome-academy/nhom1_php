<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 999),
            'type' => fake()->randomElement(['food', 'drink']),
            'price' => fake()->randomElement([35000, 45000, 50000, 55000, 60000]),
            'stock_quantity' => fake()->numberBetween(20, 100),
            'description' => "【Product Summary】\n" . fake()->sentence(10) . "\n\n【Mô tả chi tiết】\n" . fake()->paragraph(4),
            'is_active' => true,
        ];
    }
}
