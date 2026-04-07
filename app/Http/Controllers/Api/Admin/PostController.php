<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $posts = Post::query()
            ->with(['author', 'category', 'tags'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->latest()
            ->paginate(10);

        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request): PostResource
    {
        $post = Post::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->input('category_id'),
            'title' => $request->string('title'),
            'slug' => $request->input('slug'),
            'excerpt' => $request->input('excerpt'),
            'body' => $request->input('body'),
            'featured_image' => $request->input('featured_image'),
            'status' => $request->input('status'),
            'is_featured' => $request->boolean('is_featured', false),
            'allow_comments' => $request->boolean('allow_comments', true),
            'published_at' => $request->input('published_at'),
            'seo_meta' => $request->input('seo_meta'),
        ]);

        $post->tags()->sync($request->input('tag_ids', []));
        $post->load(['author', 'category', 'tags']);

        return new PostResource($post);
    }

    public function show(Post $post): PostResource
    {
        $post->load(['author', 'category', 'tags']);

        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $post->update([
            'category_id' => $request->input('category_id'),
            'title' => $request->string('title'),
            'slug' => $request->input('slug'),
            'excerpt' => $request->input('excerpt'),
            'body' => $request->input('body'),
            'featured_image' => $request->input('featured_image'),
            'status' => $request->input('status'),
            'is_featured' => $request->boolean('is_featured', false),
            'allow_comments' => $request->boolean('allow_comments', true),
            'published_at' => $request->input('published_at'),
            'seo_meta' => $request->input('seo_meta'),
        ]);

        $post->tags()->sync($request->input('tag_ids', []));
        $post->load(['author', 'category', 'tags']);

        return new PostResource($post);
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.',
        ]);
    }
}
