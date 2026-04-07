<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PostController as PublicPostController;
use App\Http\Controllers\Public\PageController as PublicPageController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/posts', [PublicPostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PublicPostController::class, 'show'])->name('posts.show');
Route::get('/pages/{slug}', [PublicPageController::class, 'show'])->name('pages.show');

Route::view('/admin/{any?}', 'admin')->where('any', '.*');
