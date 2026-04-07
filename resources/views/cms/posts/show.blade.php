@extends('layouts.cms')

@section('content')
    <article class="public-content">
        <div class="public-meta">
            {{ $post->author?->name }} · {{ optional($post->published_at)->format('M d, Y') }}
            @if($post->category)
                · {{ $post->category->name }}
            @endif
        </div>

        <h1>{{ $post->title }}</h1>

        @if($post->featured_image)
            <div style="margin: 20px 0;">
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}">
            </div>
        @endif

        @if($post->excerpt)
            <p style="font-size: 18px; color: #475569;">
                {{ $post->excerpt }}
            </p>
        @endif

        <div style="margin-top: 24px;">
            {!! $post->body !!}
        </div>

        @if($post->tags->count())
            <div class="tag-list">
                @foreach($post->tags as $tag)
                    <span class="tag-pill">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </article>

    @if($relatedPosts->count())
        <section style="margin-top: 28px;">
            <h2>Related Posts</h2>

            <div class="public-grid">
                @foreach($relatedPosts as $item)
                    <article class="public-card">
                        @if($item->featured_image)
                            <div class="public-card-image">
                                <img src="{{ $item->featured_image }}" alt="{{ $item->title }}">
                            </div>
                        @endif

                        <div class="public-card-body">
                            <h3>
                                <a href="{{ route('posts.show', $item->slug) }}">{{ $item->title }}</a>
                            </h3>
                            <div class="public-meta">
                                {{ optional($item->published_at)->format('M d, Y') }}
                            </div>
                            <p>{{ $item->excerpt }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
