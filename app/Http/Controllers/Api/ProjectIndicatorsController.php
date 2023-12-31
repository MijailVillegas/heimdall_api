<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\IndicatorResource;
use App\Http\Resources\IndicatorCollection;

class ProjectIndicatorsController extends Controller
{
    public function index(
        Request $request,
        Project $project
    ): IndicatorCollection {
        $this->authorize('view', $project);

        $search = $request->get('search', '');

        $indicators = $project
            ->indicators()
            ->search($search)
            ->latest()
            ->paginate();

        return new IndicatorCollection($indicators);
    }

    public function store(Request $request, Project $project): IndicatorResource
    {
        $this->authorize('create', Indicator::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'value' => ['required', 'numeric'],
        ]);

        $indicator = $project->indicators()->create($validated);

        return new IndicatorResource($indicator);
    }
}
