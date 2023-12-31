<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Project;
use App\Models\Indicator;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectIndicatorsTest extends TestCase
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
    public function it_gets_project_indicators(): void
    {
        $project = Project::factory()->create();
        $indicators = Indicator::factory()
            ->count(2)
            ->create([
                'project_id' => $project->id,
            ]);

        $response = $this->getJson(
            route('api.projects.indicators.index', $project)
        );

        $response->assertOk()->assertSee($indicators[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_project_indicators(): void
    {
        $project = Project::factory()->create();
        $data = Indicator::factory()
            ->make([
                'project_id' => $project->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.projects.indicators.store', $project),
            $data
        );

        unset($data['project_id']);

        $this->assertDatabaseHas('indicators', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $indicator = Indicator::latest('id')->first();

        $this->assertEquals($project->id, $indicator->project_id);
    }
}
