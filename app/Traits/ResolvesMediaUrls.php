<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ResolvesMediaUrls
{
    /**
     * Resolve a relative or storage media path into an accessible public URL/path.
     */
    protected function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return $path;
        }

        // External URLs
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Already formatted with /storage/
        if (Str::startsWith($path, '/storage/')) {
            return $path;
        }

        // Starts with storage/
        if (Str::startsWith($path, 'storage/')) {
            return '/'.$path;
        }

        // Uploaded into subfolders of storage/app/public/
        if (Str::startsWith($path, [
            'products/',
            'artists/',
            'artworks/',
            'editorials/',
            'lookbooks/',
            'site-media/',
            'avatars/',
        ])) {
            return '/storage/'.$path;
        }

        // Public directory assets like 'footage-baju.jpg' or '/footage-baju.jpg'
        return Str::startsWith($path, '/') ? $path : '/'.$path;
    }
}
