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
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key'    => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret'),
                ],
                'url' => [
                    'secure' => true,
                ],
            ])
        );
    }

    /**
     * Upload file ke Cloudinary
     */
    public function upload($filePath, string $folder = 'ppdb-documents')
    {
        $result = $this->cloudinary->uploadApi()->upload($filePath, [
            'folder' => $folder,
            'resource_type' => 'auto',
        ]);

        return [
            'url'       => $result['secure_url'],
            'public_id' => $result['public_id'],
        ];
    }

    /**
     * Hapus file dari Cloudinary
     */
    public function delete(string $publicId)
    {
        return $this->cloudinary->uploadApi()->destroy($publicId);
    }
}
