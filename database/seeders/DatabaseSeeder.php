<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tag;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'CMS Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        $news = Category::firstOrCreate(
            ['slug' => 'news'],
            ['name' => 'News', 'description' => 'News category', 'is_active' => true]
        );

        $tech = Category::firstOrCreate(
            ['slug' => 'technology'],
            ['name' => 'Technology', 'description' => 'Technology category', 'is_active' => true]
        );

        $laravel = Tag::firstOrCreate(
            ['slug' => 'laravel'],
            ['name' => 'Laravel']
        );

        $vue = Tag::firstOrCreate(
            ['slug' => 'vue'],
            ['name' => 'Vue']
        );

        $post = Post::firstOrCreate(
            ['slug' => 'welcome-to-your-cms'],
            [
                'user_id' => $admin->id,
                'category_id' => $news->id,
                'title' => 'Welcome to Your CMS',
                'excerpt' => 'Your new Laravel and Vue CMS is ready.',
                'body' => '<p>This is your first CMS post.</p>',
                'status' => 'published',
                'is_featured' => true,
                'allow_comments' => true,
                'published_at' => now(),
                'seo_meta' => [
                    'meta_title' => 'Welcome to Your CMS',
                    'meta_description' => 'Starter content for the CMS.',
                ],
            ]
        );

        $post->tags()->syncWithoutDetaching([$laravel->id, $vue->id]);

        Page::firstOrCreate(
            ['slug' => 'about'],
            [
                'user_id' => $admin->id,
                'title' => 'About',
                'body' => '<p>This is the about page.</p>',
                'template' => 'default',
                'status' => 'published',
                'published_at' => now(),
                'seo_meta' => [
                    'meta_title' => 'About',
                    'meta_description' => 'About this CMS.',
                ],
            ]
        );

        Setting::setValue('site_name', 'My Laravel CMS', 'general');
        Setting::setValue('site_tagline', 'A CMS powered by Laravel, Vue 3, and Pinia', 'general');
        Setting::setValue('homepage_title', 'Welcome to My CMS', 'general');
    }
}