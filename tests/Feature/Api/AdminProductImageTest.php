<?php

namespace Tests\Feature\Api;

use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminProductImageTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Coffee', 'slug' => 'coffee']);
        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Black Coffee',
            'slug' => 'black-coffee',
            'type' => ProductType::DRINK,
            'price' => 20000,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_upload_a_product_image_to_the_public_disk(): void
    {
        Storage::fake('public');
        Sanctum::actingAs($this->admin);

        $response = $this->post('/api/admin/products/'.$this->product->id.'/images', [
            'image' => $this->fakePng('coffee.png'),
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.is_primary', true);

        $image = ProductImage::firstOrFail();
        $this->assertSame($this->product->id, $image->product_id);
        Storage::disk('public')->assertExists($image->image_path);
    }

    public function test_uploading_primary_image_replaces_the_existing_primary_image(): void
    {
        Storage::fake('public');
        ProductImage::create([
            'product_id' => $this->product->id,
            'image_path' => 'product-images/old.png',
            'is_primary' => true,
        ]);
        Sanctum::actingAs($this->admin);

        $this->post('/api/admin/products/'.$this->product->id.'/images', [
            'image' => $this->fakePng('new.png'),
            'is_primary' => true,
        ])->assertCreated();

        $this->assertDatabaseCount('product_images', 2);
        $this->assertSame(1, ProductImage::where('is_primary', true)->count());
    }

    public function test_admin_can_delete_an_image_and_its_local_file(): void
    {
        Storage::fake('public');
        $path = 'product-images/'.$this->product->id.'/coffee.png';
        Storage::disk('public')->put($path, 'image-content');
        $image = ProductImage::create([
            'product_id' => $this->product->id,
            'image_path' => $path,
            'is_primary' => true,
        ]);
        Sanctum::actingAs($this->admin);

        $this->delete('/api/admin/products/'.$this->product->id.'/images/'.$image->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_a_non_admin_cannot_manage_product_images(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'user']));

        $this->post('/api/admin/products/'.$this->product->id.'/images', [
            'image' => $this->fakePng('coffee.png'),
        ])->assertForbidden();
    }

    private function fakePng(string $name): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $name,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScL72QAAAABJRU5ErkJggg=='),
        );
    }
}
