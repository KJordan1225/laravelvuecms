<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $settings = Setting::query()
            ->orderBy('group')
            ->orderBy('key')
            ->get();

        return SettingResource::collection($settings);
    }

    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        foreach ($request->input('settings', []) as $item) {
            Setting::updateOrCreate(
                ['key' => $item['key']],
                [
                    'group' => $item['group'] ?? 'general',
                    'value' => is_array($item['value'] ?? null)
                        ? json_encode($item['value'])
                        : ($item['value'] ?? null),
                    'type' => $item['type'] ?? 'text',
                ]
            );
        }

        $settings = Setting::query()
            ->orderBy('group')
            ->orderBy('key')
            ->get();

        return response()->json([
            'message' => 'Settings updated successfully.',
            'data' => SettingResource::collection($settings),
        ]);
    }
}
