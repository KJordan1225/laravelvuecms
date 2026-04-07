<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $post = Post::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        abort_unless($post->allow_comments, 403);

        $data = $request->validate([
            'author_name' => ['required_without:user_id', 'nullable', 'string', 'max:255'],
            'author_email' => ['required_without:user_id', 'nullable', 'email', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'author_name' => auth()->check() ? auth()->user()->name : $data['author_name'],
            'author_email' => auth()->check() ? auth()->user()->email : $data['author_email'],
            'body' => $data['body'],
            'is_approved' => false,
        ]);

        return back()->with('success', 'Comment submitted and awaiting approval.');
    }
}
