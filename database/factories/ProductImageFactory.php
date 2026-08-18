<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ProductImage::class;

    public function definition(): array
    {
        // Tạo file ảnh giả lập test upload trong Storage
        $file = UploadedFile::fake()->create(fake()->uuid() . '.jpg', 300, 'image/jpeg');
        $path = $file->store('products', 'public');

        return [
            'product_id' => Product::factory(),
            'image_path' => $path,
            'is_primary' => false,
        ];
    }
}
