<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver; 
use Intervention\Image\Format; // Required for formatting in v4
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageService
{
    protected $manager;

    public function __construct()
    {
        // Correct static initialization for v4
        $this->manager = ImageManager::usingDriver(Driver::class);
    }

    /**
     * Processes an image, creates 3 sizes, and saves them to storage.
     * Returns the base path to be saved in the database.
     */
    public function processAndSave(UploadedFile $file, int $productId): string
    {
        $baseFilename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time();
        $directory = "public/products/{$productId}";

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        $sizes = [
            'large' => 1080,
            'medium' => 500,
            'thumb' => 150,
        ];

        $dbPath = "products/{$productId}/{$baseFilename}";

        foreach ($sizes as $suffix => $size) {
            // FIX 1: Use decodePath() instead of read() in v4
            // FIX 2: Encode using encodeUsingFormat() with Format::JPEG
            $image = $this->manager->decodePath($file->getRealPath())
                                   ->cover($size, $size)
                                   ->encodeUsingFormat(Format::JPEG, quality: 85);

            $fullPath = storage_path("app/{$directory}/{$baseFilename}_{$suffix}.jpg");
            
            // save() is called directly on the EncodedImage object returned from encodeUsingFormat()
            $image->save($fullPath);
        }

        return $dbPath;
    }

    /**
     * Deletes all sizes of a product image.
     */
    public function deleteImages(string $basePath)
    {
        $sizes = ['large', 'medium', 'thumb'];
        foreach ($sizes as $suffix) {
            Storage::delete("public/{$basePath}_{$suffix}.jpg");
        }
    }
}