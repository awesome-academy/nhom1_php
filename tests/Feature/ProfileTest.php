<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('profile.edit'));

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'phone' => '0123456789',
            'address' => 'Old Address',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'New Name',
                'phone' => '0987654321',
                'address' => 'New Address 123',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit'));

        $user->refresh();

        $this->assertSame('New Name', $user->name);
        $this->assertSame('0987654321', $user->phone);
        $this->assertSame('New Address 123', $user->address);
    }

    public function test_email_cannot_be_updated(): void
    {
        $user = User::factory()->create([
            'email' => 'original@brewandbite.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'Updated Name',
                'email' => 'hacked_email@example.com', 
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit'));

        $user->refresh();

        $this->assertSame('original@brewandbite.com', $user->email);
    }

    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $imageContent = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        $file = UploadedFile::fake()->createWithContent('avatar.jpg', $imageContent);

        $response = $this
            ->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'Avatar User',
                'avatar' => $file,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit'));

        $user->refresh();

        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_old_avatar_is_deleted_when_new_avatar_is_uploaded(): void
    {
        Storage::fake('public');

        $imageContent = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        $oldFile = UploadedFile::fake()->createWithContent('old_avatar.jpg', $imageContent);
        $oldPath = $oldFile->store('avatars', 'public');

        $user = User::factory()->create([
            'avatar' => $oldPath,
        ]);

        $newFile = UploadedFile::fake()->createWithContent('new_avatar.jpg', $imageContent);
        
        $response = $this
            ->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'Avatar User',
                'avatar' => $newFile,
            ]);

        $response->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertFalse(Storage::disk('public')->exists($oldPath));
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete(route('profile.destroy'), [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertSoftDeleted($user);
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit'))
            ->delete(route('profile.destroy'), [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect(route('profile.edit'));

        $this->assertNotNull($user->fresh());
    }
}
