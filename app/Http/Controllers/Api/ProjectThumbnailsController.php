<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ThumbnailResource;
use App\Http\Resources\ThumbnailCollection;

class ProjectThumbnailsController extends Controller
{
    public function index(
        Request $request,
        Project $project
    ): ThumbnailCollection {
        $this->authorize('view', $project);

        $search = $request->get('search', '');

        $thumbnails = $project
            ->thumbnails()
            ->search($search)
            ->latest()
            ->paginate();

        return new ThumbnailCollection($thumbnails);
    }

    public function store(Request $request, Project $project): ThumbnailResource
    {
        $this->authorize('create', Thumbnail::class);

        $validated = $request->validate([
            'image' => ['nullable', 'image', 'max:1024'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $thumbnail = $project->thumbnails()->create($validated);

        return new ThumbnailResource($thumbnail);
    }
}
