<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Alignment;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\FontFactory;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeLabelService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = ImageManager::usingDriver(
            Driver::class
        );
    }

    public function generate(
        string $businessName,
        string $productName,
        string $barcodeText
    ): string {
        /*
         * Label dimensions.
         */
        $width = 408;
        $height = 240;

        /*
         * 1. Create blank white canvas.
         */
        $image = $this->manager
            ->createImage(
                $width,
                $height
            )
            ->fill('ffffff');

        /*
         * 2. Insert logo.
         */
        $logoPath = storage_path(
            'app/public/img/logo_label_black.png'
        );

        if (file_exists($logoPath)) {
            $logo = $this->manager->decodePath(
                $logoPath
            );

            $logo->scaleDown(
                height: 45
            );

            $image->insert(
                $logo,
                x: 0,
                y: 10,
                alignment: Alignment::TOP
            );
        }

        /*
         * Font.
         */
        $fontPath = storage_path(
            'app/private/fonts/Roboto-Regular.ttf'
        );

        if (!file_exists($fontPath)) {
            throw new Exception(
                "Missing font file for label generation at: {$fontPath}"
            );
        }

        /*
         * 3. Business name.
         */
        $image->text(
            $businessName,
            204,
            65,
            function (FontFactory $font) use ($fontPath) {
                $font->filepath(
                    $fontPath
                );

                $font->size(
                    18
                );

                $font->color(
                    '000000'
                );

                $font->align(
                    Alignment::CENTER,
                    Alignment::TOP
                );
            }
        );

        /*
         * 4. Product name.
         */
        $image->text(
            $productName,
            204,
            90,
            function (FontFactory $font) use ($fontPath) {
                $font->filepath(
                    $fontPath
                );

                $font->size(
                    16
                );

                $font->color(
                    '000000'
                );

                $font->align(
                    Alignment::CENTER,
                    Alignment::TOP
                );

                $font->wrap(
                    360
                );

                $font->lineHeight(
                    1.15
                );
            }
        );

        /*
         * 5. Generate Code 128 barcode.
         */
        $generator = new BarcodeGeneratorPNG();

        $barcodeBinary = $generator->getBarcode(
            $barcodeText,
            BarcodeGeneratorPNG::TYPE_CODE_128,
            2,
            50
        );

        /*
         * Convert generated PNG into an
         * Intervention Image instance.
         */
        $barcodeImage = $this->manager->decodeBinary(
            $barcodeBinary
        );

        /*
         * Insert barcode centered horizontally.
         */
        $image->insert(
            $barcodeImage,
            x: 0,
            y: 35,
            alignment: Alignment::BOTTOM
        );

        /*
         * 6. Barcode human-readable text.
         */
        $image->text(
            $barcodeText,
            204,
            215,
            function (FontFactory $font) use ($fontPath) {
                $font->filepath(
                    $fontPath
                );

                $font->size(
                    16
                );

                $font->color(
                    '000000'
                );

                $font->align(
                    Alignment::CENTER,
                    Alignment::TOP
                );
            }
        );

        /*
         * 7. Encode final image as PNG.
         */
        $encoded = $image->encodeUsingFormat(
            Format::PNG
        );

        /*
         * 8. Store using Laravel's public disk.
         *
         * This writes to:
         *
         * storage/app/public/barcodes/
         */
        $fileName = "{$barcodeText}.png";

        $filePath = "barcodes/{$fileName}";

        $saved = Storage::disk('public')->put(
            $filePath,
            (string) $encoded
        );

        if (!$saved) {
            throw new Exception(
                "Unable to save barcode label: {$filePath}"
            );
        }

        /*
         * Optional verification.
         */
        if (!Storage::disk('public')->exists($filePath)) {
            throw new Exception(
                "Barcode label was written but could not be found: {$filePath}"
            );
        }

        return $filePath;
    }
}