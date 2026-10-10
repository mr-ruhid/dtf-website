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

        $videoUrl = $result['secure_url'];
        $publicId = $result['public_id'];

        return [
            'public_id' => $publicId,
            'url' => $videoUrl,
            'thumbnail' => $this->buildThumbnailFromVideoUrl($videoUrl),
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

    protected function buildThumbnailFromVideoUrl(string $videoUrl): string
    {
        $url = str_replace('/video/upload/', '/video/upload/so_0,w_600,h_400,c_fill,q_auto/', $videoUrl);

        $url = preg_replace('/\.(mp4|mov|webm|avi|mkv)(\?.*)?$/i', '.jpg', $url);

        return $url;
    }
}