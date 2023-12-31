<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectCollection;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserProjectsController extends Controller
{
    public function index(Request $request, User $user): ProjectCollection
    {
       
        $this->authorize('view', $user);
        

        $search = $request->get('search', '');

        $projects = $user
            ->projects()
            ->search($search)
            ->latest()
            ->paginate();

        return new ProjectCollection($projects);
    }

    public function store(Request $request, User $user): ProjectResource
    {
        if (!Auth::authenticate()) {
            return new JsonResponse([
                'message' => [trans('auth.failed')]
            ], Response::HTTP_UNAUTHORIZED);
        }
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'xml_board' => ['required', 'max:255', 'string'],
        ]);

        $project = $user->projects()->create($validated);

        return new ProjectResource($project);
    }
}
