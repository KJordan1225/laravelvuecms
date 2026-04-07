@extends('layouts.cms')

@section('content')
    <article class="public-content">
        <h1>{{ $page->title }}</h1>

        <div style="margin-top: 24px;">
            {!! $page->body !!}
        </div>
    </article>
@endsection



