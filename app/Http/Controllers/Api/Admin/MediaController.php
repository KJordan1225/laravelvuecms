<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $media = Media::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($q) use ($search) {
                    $q->where('filename', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('alt_text', 'like', "%{$search}%")
                        ->orWhere('mime_type', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return MediaResource::collection($media);
    }

    public function store(StoreMediaRequest $request): MediaResource
    {
        $file = $request->file('file');
        $disk = 'public';
        $folder = 'uploads/' . now()->format('Y/m');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, $disk);

        $media = Media::create([
            'user_id' => $request->user()->id,
            'disk' => $disk,
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'alt_text' => $request->input('alt_text'),
            'title' => $request->input('title'),
        ]);

        return new MediaResource($media);
    }

    public function destroy(Media $media): JsonResponse
    {
        if (Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        $media->delete();

        return response()->json([
            'message' => 'Media deleted successfully.',
        ]);
    }
}
