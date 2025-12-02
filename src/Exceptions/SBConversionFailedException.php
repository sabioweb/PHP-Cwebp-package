<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter\Exceptions;

/**
 * Exception thrown when image conversion fails.
 */
final class SBConversionFailedException extends SBWebPConverterException
{
    public static function conversionError(string $inputPath, string $reason = ''): self
    {
        $message = sprintf('Failed to convert image: %s', $inputPath);
        if ($reason !== '') {
            $message .= sprintf('. Reason: %s', $reason);
        }

        return new self($message);
    }

    public static function writeError(string $outputPath, string $reason = ''): self
    {
        $message = sprintf('Failed to write output file: %s', $outputPath);
        if ($reason !== '') {
            $message .= sprintf('. Reason: %s', $reason);
        }

        return new self($message);
    }
}

