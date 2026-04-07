@extends('layouts.cms')

@section('content')
    <section style="margin-bottom: 24px;">
        <h1>All Posts</h1>
        <p class="muted">Browse the latest published content.</p>
    </section>

    <div class="public-grid">
        @forelse($posts as $post)
            <article class="public-card">
                @if($post->featured_image)
                    <div class="public-card-image">
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}">
                    </div>
                @endif

                <div class="public-card-body">
                    <h3>
                        <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                    </h3>

                    <div class="public-meta">
                        {{ $post->author?->name }} · {{ optional($post->published_at)->format('M d, Y') }}
                    </div>

                    <p>{{ $post->excerpt }}</p>
                </div>
            </article>
        @empty
            <div class="empty-state">No posts available.</div>
        @endforelse
    </div>

    <div style="margin-top: 28px;">
        {{ $posts->links() }}
    </div>
@endsection
