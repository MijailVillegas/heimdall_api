<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Project;
use App\Models\Thumbnail;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectThumbnailsTest extends TestCase
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
    public function it_gets_project_thumbnails(): void
    {
        $project = Project::factory()->create();
        $thumbnails = Thumbnail::factory()
            ->count(2)
            ->create([
                'project_id' => $project->id,
            ]);

        $response = $this->getJson(
            route('api.projects.thumbnails.index', $project)
        );

        $response->assertOk()->assertSee($thumbnails[0]->image);
    }

    /**
     * @test
     */
    public function it_stores_the_project_thumbnails(): void
    {
        $project = Project::factory()->create();
        $data = Thumbnail::factory()
            ->make([
                'project_id' => $project->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.projects.thumbnails.store', $project),
            $data
        );

        unset($data['project_id']);

        $this->assertDatabaseHas('thumbnails', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $thumbnail = Thumbnail::latest('id')->first();

        $this->assertEquals($project->id, $thumbnail->project_id);
    }
}
