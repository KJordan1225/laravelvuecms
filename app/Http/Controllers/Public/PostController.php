<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->published()
            ->with(['category', 'author', 'tags'])
            ->latest('published_at')
            ->paginate(12);

        return view('cms.posts.index', compact('posts'));
    }

    public function show(string $slug): View
    {
        $post = Post::query()
            ->published()
            ->with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPosts = Post::query()
            ->published()
            ->with(['category', 'author'])
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($query) => $query->where('category_id', $post->category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('cms.posts.show', compact('post', 'relatedPosts'));
    }
}
