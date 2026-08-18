<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_get_category_list()
    {
        $response = $this->actingAs($this->adminUser, 'admin')
            ->getJson('/admin/categories');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_admin_can_create_category_with_auto_slug()
    {
        $payload = [
            'name' => 'Bánh Ngọt Cao Cấp',
            'description' => 'Test description',
        ];

        $response = $this->actingAs($this->adminUser, 'admin')
            ->postJson('/admin/categories', $payload);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('data.slug', 'banh-ngot-cao-cap');

        $this->assertDatabaseHas('categories', [
            'name' => 'Bánh Ngọt Cao Cấp',
            'slug' => 'banh-ngot-cao-cap',
        ]);
    }

    public function test_cannot_delete_category_with_products()
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->adminUser, 'admin')
            ->deleteJson("/admin/categories/{$category->id}");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['message' => 'Cannot delete category that contains products.']);
    }

    public function test_cannot_set_category_child_as_its_parent()
    {
        $parent = Category::factory()->create(['name' => 'Parent']);
        $child = Category::factory()->create(['name' => 'Child', 'parent_id' => $parent->id]);

        $response = $this->actingAs($this->adminUser, 'admin')
            ->putJson("/admin/categories/{$parent->id}", [
                'parent_id' => $child->id,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['parent_id']);
    }
}