<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PhotoResource;
use App\Http\Resources\PhotoCollection;

class UserPhotosController extends Controller
{
    public function index(Request $request, User $user): PhotoCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $photos = $user
            ->photos()
            ->search($search)
            ->latest()
            ->paginate();

        return new PhotoCollection($photos);
    }

    public function store(Request $request, User $user): PhotoResource
    {
        $this->authorize('create', Photo::class);

        $validated = $request->validate([
            'image' => ['nullable', 'image', 'max:1024'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $photo = $user->photos()->create($validated);

        return new PhotoResource($photo);
    }
}
