<?php

namespace Tests\Feature\Api;

use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminSuggestionListTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_suggestions(): void
    {
        $response = $this->getJson('/api/admin/suggestions');

        $response->assertStatus(401);
    }

    public function test_non_admin_cannot_view_suggestions(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/admin/suggestions');

        $response->assertStatus(403);
    }

    public function test_admin_can_view_all_suggestions_newest_first(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reviewer = User::factory()->create(['role' => 'admin']);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->travelTo(now()->subDay());
        $olderSuggestion = Suggestion::create([
            'user_id' => $user1->id,
            'content' => 'Old suggestion content',
            'status' => 'pending',
        ]);
        $this->travelBack();

        $newerSuggestion = Suggestion::create([
            'user_id' => $user2->id,
            'content' => 'New suggestion content',
            'status' => 'reviewed',
            'reviewed_by' => $reviewer->id,
        ]);

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/suggestions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user' => ['id', 'name', 'email'],
                        'content',
                        'status',
                        'admin_note',
                        'reviewer',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links',
                'meta',
            ])
            ->assertJsonCount(2, 'data');

        // Check order (newest first)
        $this->assertEquals($newerSuggestion->id, $response->json('data.0.id'));
        $this->assertEquals($olderSuggestion->id, $response->json('data.1.id'));

        // Check user data structure
        $this->assertEquals($user2->id, $response->json('data.0.user.id'));
        $this->assertEquals($user2->name, $response->json('data.0.user.name'));
        $this->assertEquals($user2->email, $response->json('data.0.user.email'));

        // Check reviewer data (newer has reviewer)
        $this->assertNotNull($response->json('data.0.reviewer'));
        $this->assertEquals($reviewer->id, $response->json('data.0.reviewer.id'));
        $this->assertEquals($reviewer->name, $response->json('data.0.reviewer.name'));
        $this->assertEquals($reviewer->email, $response->json('data.0.reviewer.email'));

        // Check older has no reviewer
        $this->assertNull($response->json('data.1.reviewer'));
    }

    public function test_admin_receives_empty_paginated_collection_when_there_are_no_suggestions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/suggestions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ])
            ->assertJsonCount(0, 'data');
    }
}
