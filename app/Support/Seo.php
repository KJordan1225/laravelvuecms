<?php

namespace App\Support;

class Seo
{
    public static function title(?array $seoMeta, string $fallback): string
    {
        return $seoMeta['meta_title'] ?? $fallback;
    }

    public static function description(?array $seoMeta, string $fallback = ''): string
    {
        return $seoMeta['meta_description'] ?? $fallback;
    }
}
