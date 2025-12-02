<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter;

use Sabioweb\WebPConverter\Exceptions\SBFileNotFoundException;
use Sabioweb\WebPConverter\Exceptions\SBInvalidImageException;

/**
 * Validates image files before conversion.
 */
final readonly class SBImageValidator
{
    private const array SUPPORTED_FORMATS = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
    private const int DEFAULT_MAX_FILE_SIZE = 50 * 1024 * 1024; // 50MB

    public function __construct(
        private int $maxFileSize = self::DEFAULT_MAX_FILE_SIZE
    ) {
    }

    /**
     * Validates that GD extension is available.
     *
     * @throws SBInvalidImageException
     */
    public function validateGdExtension(): void
    {
        if (!extension_loaded('gd')) {
            throw SBInvalidImageException::gdNotAvailable();
        }

        if (!function_exists('imagewebp')) {
            throw SBInvalidImageException::gdNotAvailable();
        }
    }

    /**
     * Validates an image file.
     *
     * @param string $filePath Path to the image file
     * @throws SBFileNotFoundException
     * @throws SBInvalidImageException
     */
    public function validateImage(string $filePath): void
    {
        $this->validateFileExists($filePath);
        $this->validateFileSize($filePath);
        $this->validateImageFormat($filePath);
        $this->validateImageContent($filePath);
    }

    /**
     * Validates that the file exists.
     *
     * @param string $filePath Path to the file
     * @throws SBFileNotFoundException
     */
    private function validateFileExists(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw SBFileNotFoundException::fileNotFound($filePath);
        }

        if (!is_file($filePath)) {
            throw SBInvalidImageException::invalidFile($filePath);
        }
    }

    /**
     * Validates file size.
     *
     * @param string $filePath Path to the file
     * @throws SBInvalidImageException
     */
    private function validateFileSize(string $filePath): void
    {
        $fileSize = filesize($filePath);
        if ($fileSize === false) {
            throw SBInvalidImageException::invalidFile($filePath);
        }

        if ($fileSize > $this->maxFileSize) {
            throw SBInvalidImageException::fileTooLarge($filePath, $this->maxFileSize);
        }
    }

    /**
     * Validates image format.
     *
     * @param string $filePath Path to the image file
     * @throws SBInvalidImageException
     */
    private function validateImageFormat(string $filePath): void
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (!in_array($extension, self::SUPPORTED_FORMATS, true)) {
            throw SBInvalidImageException::unsupportedFormat($extension);
        }
    }

    /**
     * Validates image content by attempting to read it.
     *
     * @param string $filePath Path to the image file
     * @throws SBInvalidImageException
     */
    private function validateImageContent(string $filePath): void
    {
        $imageInfo = @getimagesize($filePath);
        if ($imageInfo === false) {
            throw SBInvalidImageException::invalidFile($filePath);
        }

        $mimeType = $imageInfo['mime'] ?? '';
        $supportedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/x-ms-bmp',
        ];

        if (!in_array($mimeType, $supportedMimeTypes, true)) {
            throw SBInvalidImageException::unsupportedFormat($mimeType);
        }
    }

    /**
     * Gets the image type constant for GD functions.
     *
     * @param string $filePath Path to the image file
     * @return int IMAGETYPE_* constant
     * @throws SBInvalidImageException
     */
    public function getImageType(string $filePath): int
    {
        $imageInfo = @getimagesize($filePath);
        if ($imageInfo === false) {
            throw SBInvalidImageException::invalidFile($filePath);
        }

        return $imageInfo[2] ?? IMAGETYPE_UNKNOWN;
    }
}

