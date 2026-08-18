<?php

namespace Tests\Feature\Api;

use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminSuggestionUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $user;

    protected Suggestion $suggestion;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
        $this->suggestion = Suggestion::create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'content' => 'Original content',
        ]);
    }

    public function test_guest_cannot_update_suggestion(): void
    {
        $response = $this->putJson("/api/admin/suggestions/{$this->suggestion->id}", [
            'status' => 'reviewed',
        ]);

        $response->assertStatus(401);
    }

    public function test_non_admin_cannot_update_suggestion(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->putJson("/api/admin/suggestions/{$this->suggestion->id}", [
            'status' => 'reviewed',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('suggestions', [
            'id' => $this->suggestion->id,
            'status' => 'pending',
        ]);
    }

    public function test_missing_suggestion_returns_not_found(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->putJson('/api/admin/suggestions/999999', [
            'status' => 'reviewed',
        ]);

        $response->assertStatus(404);
    }

    public function test_status_is_required(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/admin/suggestions/{$this->suggestion->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_status_must_be_a_review_result(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/admin/suggestions/{$this->suggestion->id}", [
            'status' => 'pending',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);

        $response2 = $this->putJson("/api/admin/suggestions/{$this->suggestion->id}", [
            'status' => 'invalid',
        ]);

        $response2->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_admin_note_must_be_a_string_when_present(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/admin/suggestions/{$this->suggestion->id}", [
            'status' => 'reviewed',
            'admin_note' => 12345, // invalid type
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['admin_note']);
    }

    public function test_admin_can_review_suggestion(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/admin/suggestions/{$this->suggestion->id}", [
            'status' => 'reviewed',
            'admin_note' => 'Looks good',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('suggestions', [
            'id' => $this->suggestion->id,
            'status' => 'reviewed',
            'admin_note' => 'Looks good',
            'reviewed_by' => $this->admin->id,
        ]);

        $this->assertEquals($this->admin->id, $response->json('reviewer.id'));
    }

    public function test_admin_can_reject_suggestion_with_nullable_note(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/admin/suggestions/{$this->suggestion->id}", [
            'status' => 'rejected',
            'admin_note' => null,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('suggestions', [
            'id' => $this->suggestion->id,
            'status' => 'rejected',
            'admin_note' => null,
            'reviewed_by' => $this->admin->id,
        ]);
    }

    public function test_update_payload_cannot_change_server_managed_fields(): void
    {
        Sanctum::actingAs($this->admin);

        $maliciousUser = User::factory()->create();
        $maliciousAdmin = User::factory()->create(['role' => 'admin']);

        $response = $this->putJson("/api/admin/suggestions/{$this->suggestion->id}", [
            'status' => 'reviewed',
            'user_id' => $maliciousUser->id,
            'content' => 'Modified content',
            'reviewed_by' => $maliciousAdmin->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('suggestions', [
            'id' => $this->suggestion->id,
            'status' => 'reviewed',
            'user_id' => $this->user->id, // Remains original
            'content' => 'Original content', // Remains original
            'reviewed_by' => $this->admin->id, // Set to acting admin, not malicious
        ]);
    }
}
