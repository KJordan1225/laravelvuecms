@extends('layouts.cms')

@section('content')
    <section class="hero">
        <h1>{{ $homepageTitle }}</h1>
        <p>{{ $siteTagline }}</p>
    </section>

    @if($featuredPosts->count())
        <section style="margin-bottom: 28px;">
            <h2>Featured Posts</h2>
            <div class="public-grid">
                @foreach($featuredPosts as $post)
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
                @endforeach
            </div>
        </section>
    @endif

    <section>
        <h2>Latest Posts</h2>
        <div class="public-grid">
            @foreach($latestPosts as $post)
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
                            @if($post->category)
                                · {{ $post->category->name }}
                            @endif
                        </div>

                        <p>{{ $post->excerpt }}</p>

                        @if($post->tags->count())
                            <div class="tag-list">
                                @foreach($post->tags as $tag)
                                    <span class="tag-pill">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
