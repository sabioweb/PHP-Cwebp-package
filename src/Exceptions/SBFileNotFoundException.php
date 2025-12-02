<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter\Exceptions;

/**
 * Exception thrown when a file is not found.
 */
final class SBFileNotFoundException extends SBWebPConverterException
{
    public static function fileNotFound(string $path): self
    {
        return new self(
            sprintf('File not found: %s', $path)
        );
    }

    public static function directoryNotFound(string $path): self
    {
        return new self(
            sprintf('Directory not found: %s', $path)
        );
    }
}

