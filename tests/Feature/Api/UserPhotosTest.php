<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Photo;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPhotosTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_user_photos(): void
    {
        $user = User::factory()->create();
        $photos = Photo::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.photos.index', $user));

        $response->assertOk()->assertSee($photos[0]->image);
    }

    /**
     * @test
     */
    public function it_stores_the_user_photos(): void
    {
        $user = User::factory()->create();
        $data = Photo::factory()
            ->make([
                'user_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.photos.store', $user),
            $data
        );

        unset($data['user_id']);

        $this->assertDatabaseHas('photos', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $photo = Photo::latest('id')->first();

        $this->assertEquals($user->id, $photo->user_id);
    }
}
