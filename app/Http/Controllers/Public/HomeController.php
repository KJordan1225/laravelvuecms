<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredPosts = Post::query()
            ->published()
            ->with(['category', 'author', 'tags'])
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(6)
            ->get();

        $latestPosts = Post::query()
            ->published()
            ->with(['category', 'author', 'tags'])
            ->latest('published_at')
            ->take(12)
            ->get();

        $siteName = Setting::getValue('site_name', 'My Laravel CMS');
        $siteTagline = Setting::getValue('site_tagline', 'A modern CMS');
        $homepageTitle = Setting::getValue('homepage_title', 'Welcome');

        return view('cms.home', [
            'title' => $siteName,
            'metaDescription' => $siteTagline,
            'siteName' => $siteName,
            'siteTagline' => $siteTagline,
            'homepageTitle' => $homepageTitle,
            'featuredPosts' => $featuredPosts,
            'latestPosts' => $latestPosts,
        ]);
    }
}
