<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => config('services.cloudinary.cloud_name'),
                    'api_key' => config('services.cloudinary.api_key'),
                    'api_secret' => config('services.cloudinary.api_secret'),
                ],
                'url' => [
                    'secure' => true,
                ],
            ])
        );
    }

    public function uploadVideo(string $filePath, string $folder = 'gallery'): array
    {
        $result = $this->cloudinary->uploadApi()->upload($filePath, [
            'resource_type' => 'video',
            'folder' => $folder,
        ]);

        return [
            'public_id' => $result['public_id'],
            'url' => $result['secure_url'],
            'thumbnail' => $this->cloudinary->imageTag($result['public_id'], [
                'resource_type' => 'video',
                'format' => 'jpg',
            ]) ? $this->thumbnailUrl($result['public_id']) : null,
            'duration' => $result['duration'] ?? null,
            'format' => $result['format'] ?? null,
        ];
    }

    public function deleteVideo(string $publicId): bool
    {
        try {
            $this->cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => 'video',
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function thumbnailUrl(string $publicId): string
    {
        return $this->cloudinary->image($publicId, [
            'resource_type' => 'video',
            'format' => 'jpg',
            'transformation' => [
                'width' => 600,
                'height' => 400,
                'crop' => 'fill',
                'quality' => 'auto',
            ],
        ]);
    }
}
