<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SuggestionHistoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_view_history(): void
    {
        $response = $this->getJson('/api/suggestions/me');

        $response->assertUnauthorized();
    }

    public function test_user_can_view_own_suggestions_history(): void
    {
        $this->user->suggestions()->create(['content' => 'First suggestion', 'status' => 'pending']);
        $this->user->suggestions()->create(['content' => 'Second suggestion', 'status' => 'reviewed']);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/suggestions/me');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    public function test_history_is_sorted_newest_first(): void
    {
        $this->travelTo(now()->subDays(2));
        $oldSuggestion = $this->user->suggestions()->create([
            'content' => 'Old suggestion',
        ]);
        $this->travelBack();

        $newSuggestion = $this->user->suggestions()->create([
            'content' => 'New suggestion',
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/suggestions/me');

        $response->assertOk();

        $this->assertEquals($newSuggestion->id, $response->json('data.0.id'));
        $this->assertEquals($oldSuggestion->id, $response->json('data.1.id'));
    }

    public function test_user_cannot_see_suggestions_of_other_users(): void
    {
        $anotherUser = User::factory()->create();
        $anotherUser->suggestions()->create(['content' => 'Another user suggestion']);

        $this->user->suggestions()->create(['content' => 'My suggestion']);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/suggestions/me');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $this->assertEquals('My suggestion', $response->json('data.0.content'));
    }

    public function test_history_response_structure_and_pagination(): void
    {
        $this->user->suggestions()->create(['content' => 'Test structure']);

        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/suggestions/me');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'content',
                    'status',
                    'admin_note',
                    'created_at',
                    'updated_at',
                ],
            ],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
        ]);
    }

    public function test_user_without_suggestions_receives_empty_collection(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/suggestions/me');

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
        $response->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
    }
}
