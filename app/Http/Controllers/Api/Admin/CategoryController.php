<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->withCount('posts')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = Category::create([
            'name' => $request->string('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'sort_order' => $request->integer('sort_order', 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $category->loadCount('posts');

        return new CategoryResource($category);
    }

    public function show(Category $category): CategoryResource
    {
        $category->loadCount('posts');

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $category->update([
            'name' => $request->string('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'sort_order' => $request->integer('sort_order', 0),
            'is_active' => $request->boolean('is_active', false),
        ]);

        $category->loadCount('posts');

        return new CategoryResource($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.',
        ]);
    }
}
