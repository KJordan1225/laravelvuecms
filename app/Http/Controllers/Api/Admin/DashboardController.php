<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'stats' => [
                'posts_total' => Post::count(),
                'posts_published' => Post::where('status', 'published')->count(),
                'posts_draft' => Post::where('status', 'draft')->count(),
                'pages_total' => Page::count(),
                'pages_published' => Page::where('status', 'published')->count(),
                'categories_total' => Category::count(),
                'tags_total' => Tag::count(),
            ],
            'recent_posts' => Post::query()
                ->with(['author', 'category'])
                ->latest()
                ->take(5)
                ->get()
                ->map(fn (Post $post) => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'status' => $post->status,
                    'author' => $post->author?->name,
                    'category' => $post->category?->name,
                    'created_at' => $post->created_at?->toDateTimeString(),
                ]),
            'recent_pages' => Page::query()
                ->with('author')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn (Page $page) => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'status' => $page->status,
                    'author' => $page->author?->name,
                    'created_at' => $page->created_at?->toDateTimeString(),
                ]),
        ]);
    }
}