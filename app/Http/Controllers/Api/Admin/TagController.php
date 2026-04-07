<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTagRequest;
use App\Http\Requests\Admin\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $tags = Tag::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10);

        return TagResource::collection($tags);
    }

    public function store(StoreTagRequest $request): TagResource
    {
        $tag = Tag::create([
            'name' => $request->string('name'),
            'slug' => $request->input('slug'),
        ]);

        return new TagResource($tag);
    }

    public function show(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }

    public function update(UpdateTagRequest $request, Tag $tag): TagResource
    {
        $tag->update([
            'name' => $request->string('name'),
            'slug' => $request->input('slug'),
        ]);

        return new TagResource($tag);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully.',
        ]);
    }
}
