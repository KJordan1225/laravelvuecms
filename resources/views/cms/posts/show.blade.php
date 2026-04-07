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

        @if($post->allow_comments)
            <div class="comment-box">
                <h2>Leave a Comment</h2>

                <form method="POST" action="{{ route('posts.comments.store', $post->slug) }}">
                    @csrf

                    @guest
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div>
                                <label>Name</label>
                                <input type="text" name="author_name" class="form-control" value="{{ old('author_name') }}">
                                @error('author_name') <div class="form-error">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label>Email</label>
                                <input type="email" name="author_email" class="form-control" value="{{ old('author_email') }}">
                                @error('author_email') <div class="form-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endguest

                    <div style="margin-top:16px;">
                        <label>Comment</label>
                        <textarea name="body" class="form-control" rows="5">{{ old('body') }}</textarea>
                        @error('body') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div style="margin-top:16px;">
                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                    </div>
                </form>

                @if($post->comments->count())
                    <div style="margin-top:28px;">
                        <h3>Comments</h3>

                        @foreach($post->comments as $comment)
                            <div style="border-top:1px solid #e5e7eb;padding:16px 0;">
                                <strong>{{ $comment->author_name }}</strong>
                                <div class="public-meta">{{ $comment->created_at?->format('M d, Y g:i A') }}</div>
                                <p>{{ $comment->body }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
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
                            <div class="public-meta">{{ optional($item->published_at)->format('M d, Y') }}</div>
                            <p>{{ $item->excerpt }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection