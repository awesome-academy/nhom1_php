<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SuggestionCreateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_create_suggestion(): void
    {
        $response = $this->postJson('/api/suggestions', [
            'content' => 'This is a suggestion.',
        ]);

        $response->assertUnauthorized();
    }

    public function test_user_can_create_suggestion(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/suggestions', [
            'content' => 'This is a valid suggestion.',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('content', 'This is a valid suggestion.');
        $response->assertJsonPath('status', 'pending');

        $this->assertDatabaseHas('suggestions', [
            'user_id' => $this->user->id,
            'content' => 'This is a valid suggestion.',
            'status' => 'pending',
            'admin_note' => null,
            'reviewed_by' => null,
        ]);
    }

    public function test_missing_content_fails_validation(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/suggestions', []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_content_not_string_fails_validation(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/suggestions', [
            'content' => 12345,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_empty_content_fails_validation(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/suggestions', [
            'content' => '',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['content']);
    }

    public function test_payload_spoofing_does_not_override_fields(): void
    {
        $anotherUser = User::factory()->create();
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/suggestions', [
            'content' => 'Spoofing attempt',
            'user_id' => $anotherUser->id,
            'status' => 'reviewed',
            'admin_note' => 'Hacked note',
            'reviewed_by' => $anotherUser->id,
        ]);

        $response->assertCreated();

        // Verify it used the authenticated user and default values
        $this->assertDatabaseHas('suggestions', [
            'user_id' => $this->user->id,
            'content' => 'Spoofing attempt',
            'status' => 'pending',
            'admin_note' => null,
            'reviewed_by' => null,
        ]);

        $this->assertDatabaseMissing('suggestions', [
            'user_id' => $anotherUser->id,
        ]);
        $this->assertDatabaseMissing('suggestions', [
            'status' => 'reviewed',
        ]);
    }
}
