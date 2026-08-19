<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        // 1. Tạo tài khoản admin test
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
        ]);

        // 2. Chạy CategorySeeder để lấy danh mục mẫu có sẵn
        $this->seed(CategorySeeder::class);
        $this->category = Category::whereNotNull('parent_id')->first() ?? Category::first();
    }

    /** Helper đăng nhập Admin trên cả 2 guard web và admin để đảm bảo không bị 302 */
    protected function authenticateAdmin()
    {
        return $this->actingAs($this->adminUser, 'admin')
                    ->actingAs($this->adminUser, 'web');
    }

    /** 1. Test xem danh sách sản phẩm và lọc */
    public function test_admin_can_view_product_list_with_filters(): void
    {
        Product::create([
            'category_id' => $this->category->id,
            'name' => 'Matcha Latte Uji',
            'slug' => 'matcha-latte-uji',
            'type' => 'drink',
            'price' => 50000,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $response = $this->authenticateAdmin()->get(route('admin.products.index', [
            'search' => 'Matcha',
            'type' => 'drink',
        ]));

        $response->assertOk();
        $response->assertSee('Matcha Latte Uji');
    }

    /** 2. Test tạo sản phẩm mới kèm Primary Image và Gallery */
    public function test_admin_can_create_product_with_multiple_images(): void
    {
        $imageContent = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        $primaryImg = UploadedFile::fake()->createWithContent('primary.jpg', $imageContent);
        $gallery1 = UploadedFile::fake()->createWithContent('gallery1.jpg', $imageContent);
        $gallery2 = UploadedFile::fake()->createWithContent('gallery2.jpg', $imageContent);

        $payload = [
            'category_id' => $this->category->id,
            'name' => 'Cà phê Muối Biển',
            'type' => 'drink',
            'price' => 45000,
            'stock_quantity' => 50,
            'description' => 'Mô tả cà phê muối đậm đà',
            'is_active' => 1,
            'primary_image' => $primaryImg,
            'gallery_images' => [$gallery1, $gallery2],
        ];

        $response = $this->authenticateAdmin()->post(route('admin.products.store'), $payload);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Cà phê Muối Biển']);

        $product = Product::where('name', 'Cà phê Muối Biển')->first();
        $this->assertCount(3, $product->images);

        $primaryRecord = $product->images()->where('is_primary', true)->first();
        $this->assertNotNull($primaryRecord);
        $this->assertTrue(Storage::disk('public')->exists($primaryRecord->image_path));
    }

    /** 3. Test validate bắt buộc khi thiếu dữ liệu */
    public function test_create_product_validation_errors(): void
    {
        $response = $this->authenticateAdmin()->post(route('admin.products.store'), [
            'name' => '',
            'price' => -5000,
        ]);

        $response->assertSessionHasErrors(['category_id', 'name', 'type', 'price', 'stock_quantity']);
    }

    /** 4. Test xóa 1 ảnh lẻ trong Gallery */
    public function test_admin_can_delete_single_gallery_image(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Cold Brew Test',
            'slug' => 'cold-brew-test',
            'type' => 'drink',
            'price' => 45000,
            'stock_quantity' => 20,
            'is_active' => true,
        ]);

        $imageContent = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        $file = UploadedFile::fake()->createWithContent('sub.jpg', $imageContent);
        $path = $file->store('products', 'public');
        $image = $product->images()->create(['image_path' => $path, 'is_primary' => false]);

        $response = $this->authenticateAdmin()
            ->delete(route('admin.products.images.destroy', [$product, $image]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
        $this->assertFalse(Storage::disk('public')->exists($path));
    }

    /** 5. Test xóa sản phẩm thì xóa sạch file ảnh trong Storage */
    public function test_delete_product_removes_all_associated_files(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Croissant Test',
            'slug' => 'croissant-test',
            'type' => 'food',
            'price' => 35000,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $imageContent = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        $file = UploadedFile::fake()->createWithContent('croissant.jpg', $imageContent);
        $path = $file->store('products', 'public');

        $product->images()->create([
            'image_path' => $path,
            'is_primary' => true,
        ]);

        $response = $this->authenticateAdmin()->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertSoftDeleted($product);    
        $this->assertFalse(Storage::disk('public')->exists($path));
    }
}