<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminProductVariantTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
        $this->product = Product::factory()->create();
    }

    public function test_guest_cannot_manage_product_variants(): void
    {
        $this->postJson("/api/admin/products/{$this->product->id}/variants", $this->payload())
            ->assertUnauthorized();
    }

    public function test_non_admin_cannot_manage_product_variants(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson("/api/admin/products/{$this->product->id}/variants", $this->payload())
            ->assertForbidden();
    }

    public function test_admin_can_create_a_product_variant(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson("/api/admin/products/{$this->product->id}/variants", $this->payload());

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Size L')
            ->assertJsonPath('data.variant_group', 'size')
            ->assertJsonPath('data.extra_price', '5000.00');

        $this->assertDatabaseHas('product_variants', [
            'product_id' => $this->product->id,
            'name' => 'Size L',
            'variant_group' => 'size',
            'extra_price' => 5000,
        ]);
    }

    public function test_variant_payload_is_validated(): void
    {
        Sanctum::actingAs($this->admin);

        $this->postJson("/api/admin/products/{$this->product->id}/variants", [
            'name' => str_repeat('a', 101),
            'variant_group' => 'invalid',
            'extra_price' => -1,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'variant_group', 'extra_price']);
    }

    public function test_admin_cannot_update_a_variant_from_another_product(): void
    {
        Sanctum::actingAs($this->admin);
        $variant = ProductVariant::create([
            'product_id' => Product::factory()->create()->id,
            ...$this->payload(),
        ]);

        $this->putJson("/api/admin/products/{$this->product->id}/variants/{$variant->id}", [
            ...$this->payload(),
            'name' => 'Size XL',
        ])->assertNotFound();

        $this->assertDatabaseHas('product_variants', ['id' => $variant->id, 'name' => 'Size L']);
    }

    public function test_admin_can_update_and_delete_a_product_variant(): void
    {
        Sanctum::actingAs($this->admin);
        $variant = ProductVariant::create([
            'product_id' => $this->product->id,
            ...$this->payload(),
        ]);

        $this->putJson("/api/admin/products/{$this->product->id}/variants/{$variant->id}", [
            'name' => '70% Sugar',
            'variant_group' => 'sugar',
            'extra_price' => 0,
        ])->assertOk()
            ->assertJsonPath('data.name', '70% Sugar');

        $this->deleteJson("/api/admin/products/{$this->product->id}/variants/{$variant->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('product_variants', ['id' => $variant->id]);
    }

    /** @return array{name: string, variant_group: string, extra_price: int} */
    private function payload(): array
    {
        return [
            'name' => 'Size L',
            'variant_group' => 'size',
            'extra_price' => 5000,
        ];
    }
}
