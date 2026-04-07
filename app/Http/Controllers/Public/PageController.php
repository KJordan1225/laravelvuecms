<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\Seo;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('cms.pages.show', [
            'title' => Seo::title($page->seo_meta, $page->title),
            'metaDescription' => Seo::description($page->seo_meta, ''),
            'page' => $page,
        ]);
    }
}
