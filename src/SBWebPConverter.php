<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter;

use Sabioweb\WebPConverter\Exceptions\SBConversionFailedException;
use Sabioweb\WebPConverter\Exceptions\SBFileNotFoundException;
use Sabioweb\WebPConverter\Exceptions\SBInvalidImageException;

/**
 * Main converter class for converting images to WebP format.
 */
final class SBWebPConverter implements SBImageConverterInterface
{
    public function __construct(
        private readonly SBImageValidator $validator = new SBImageValidator(),
        private readonly SBFileHandler $fileHandler = new SBFileHandler()
    ) {
        $this->validator->validateGdExtension();
    }

    /**
     * Converts an image file to WebP format.
     *
     * @param string $inputPath Path to the input image file
     * @param string $outputPath Path to the output WebP file
     * @param SBConversionOptions|null $options Optional conversion options
     * @return void
     * @throws SBFileNotFoundException
     * @throws SBInvalidImageException
     * @throws SBConversionFailedException
     */
    public function convert(string $inputPath, string $outputPath, ?SBConversionOptions $options = null): void
    {
        $options = $options ?? SBConversionOptions::create();

        $this->validator->validateImage($inputPath);
        $imageType = $this->validator->getImageType($inputPath);

        $imageResource = $this->loadImage($inputPath, $imageType);
        if ($imageResource === false) {
            throw SBConversionFailedException::conversionError($inputPath, 'Failed to load image');
        }

        try {
            $this->fileHandler->writeWebPFile($outputPath, $imageResource, $options);
        } finally {
            $this->fileHandler->cleanupImageResource($imageResource);
        }
    }

    /**
     * Loads an image resource based on image type.
     *
     * @param string $inputPath Path to the input image
     * @param int $imageType IMAGETYPE_* constant
     * @return resource|false GD image resource or false on failure
     * @throws SBConversionFailedException
     */
    private function loadImage(string $inputPath, int $imageType)
    {
        return match ($imageType) {
            IMAGETYPE_JPEG => $this->loadJpeg($inputPath),
            IMAGETYPE_PNG => $this->loadPng($inputPath),
            IMAGETYPE_GIF => $this->loadGif($inputPath),
            IMAGETYPE_BMP, IMAGETYPE_WBMP => $this->loadBmp($inputPath),
            default => throw SBConversionFailedException::conversionError(
                $inputPath,
                sprintf('Unsupported image type: %d', $imageType)
            ),
        };
    }

    /**
     * Loads a JPEG image.
     *
     * @param string $inputPath Path to the JPEG file
     * @return resource|false GD image resource or false on failure
     */
    private function loadJpeg(string $inputPath)
    {
        $image = @imagecreatefromjpeg($inputPath);
        if ($image === false) {
            throw SBConversionFailedException::conversionError($inputPath, 'Failed to load JPEG image');
        }

        return $image;
    }

    /**
     * Loads a PNG image.
     *
     * @param string $inputPath Path to the PNG file
     * @return resource|false GD image resource or false on failure
     */
    private function loadPng(string $inputPath)
    {
        $image = @imagecreatefrompng($inputPath);
        if ($image === false) {
            throw SBConversionFailedException::conversionError($inputPath, 'Failed to load PNG image');
        }

        // Preserve transparency for PNG
        imagealphablending($image, false);
        imagesavealpha($image, true);

        return $image;
    }

    /**
     * Loads a GIF image.
     *
     * @param string $inputPath Path to the GIF file
     * @return resource|false GD image resource or false on failure
     */
    private function loadGif(string $inputPath)
    {
        $image = @imagecreatefromgif($inputPath);
        if ($image === false) {
            throw SBConversionFailedException::conversionError($inputPath, 'Failed to load GIF image');
        }

        // Preserve transparency for GIF
        $transparentIndex = imagecolortransparent($image);
        if ($transparentIndex >= 0) {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        return $image;
    }

    /**
     * Loads a BMP image.
     *
     * @param string $inputPath Path to the BMP file
     * @return resource|false GD image resource or false on failure
     */
    private function loadBmp(string $inputPath)
    {
        $image = @imagecreatefrombmp($inputPath);
        if ($image === false) {
            throw SBConversionFailedException::conversionError($inputPath, 'Failed to load BMP image');
        }

        return $image;
    }
}

