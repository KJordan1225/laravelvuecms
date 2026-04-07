<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $pages = Page::query()
            ->with('author')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10);

        return PageResource::collection($pages);
    }

    public function store(StorePageRequest $request): PageResource
    {
        $page = Page::create([
            'user_id' => $request->user()->id,
            'title' => $request->string('title'),
            'slug' => $request->input('slug'),
            'body' => $request->input('body'),
            'template' => $request->input('template', 'default'),
            'status' => $request->input('status'),
            'published_at' => $request->input('published_at'),
            'seo_meta' => $request->input('seo_meta'),
        ]);

        $page->load('author');

        return new PageResource($page);
    }

    public function show(Page $page): PageResource
    {
        $page->load('author');

        return new PageResource($page);
    }

    public function update(UpdatePageRequest $request, Page $page): PageResource
    {
        $page->update([
            'title' => $request->string('title'),
            'slug' => $request->input('slug'),
            'body' => $request->input('body'),
            'template' => $request->input('template', 'default'),
            'status' => $request->input('status'),
            'published_at' => $request->input('published_at'),
            'seo_meta' => $request->input('seo_meta'),
        ]);

        $page->load('author');

        return new PageResource($page);
    }

    public function destroy(Page $page): JsonResponse
    {
        $page->delete();

        return response()->json([
            'message' => 'Page deleted successfully.',
        ]);
    }
}
