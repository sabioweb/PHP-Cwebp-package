<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter\Exceptions;

/**
 * Exception thrown when an invalid image file is provided.
 */
final class SBInvalidImageException extends SBWebPConverterException
{
    public static function unsupportedFormat(string $format): self
    {
        return new self(
            sprintf('Unsupported image format: %s. Supported formats are: JPEG, PNG, GIF, BMP.', $format)
        );
    }

    public static function invalidFile(string $path): self
    {
        return new self(
            sprintf('Invalid image file: %s. The file may be corrupted or not a valid image.', $path)
        );
    }

    public static function fileTooLarge(string $path, int $maxSize): self
    {
        return new self(
            sprintf('Image file is too large: %s. Maximum allowed size: %d bytes.', $path, $maxSize)
        );
    }

    public static function gdNotAvailable(): self
    {
        return new self(
            'GD extension is not available. Please enable GD extension in your PHP configuration.'
        );
    }
}

