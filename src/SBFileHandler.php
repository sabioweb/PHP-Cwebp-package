<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter;

use Sabioweb\WebPConverter\Exceptions\SBConversionFailedException;
use Sabioweb\WebPConverter\Exceptions\SBFileNotFoundException;

/**
 * Handles file operations for conversion.
 */
final readonly class SBFileHandler
{
    /**
     * Ensures the output directory exists.
     *
     * @param string $outputPath Path to the output file
     * @throws SBConversionFailedException
     */
    public function ensureOutputDirectory(string $outputPath): void
    {
        $directory = dirname($outputPath);
        if ($directory === '' || $directory === '.') {
            return;
        }

        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw SBConversionFailedException::writeError(
                    $outputPath,
                    'Failed to create output directory'
                );
            }
        }
    }

    /**
     * Writes image data to file.
     *
     * @param string $outputPath Path to the output file
     * @param resource $imageResource GD image resource
     * @param SBConversionOptions $options Conversion options
     * @throws SBConversionFailedException
     */
    public function writeWebPFile(string $outputPath, $imageResource, SBConversionOptions $options): void
    {
        $this->ensureOutputDirectory($outputPath);

        $quality = $options->isLossless() ? 100 : $options->getQuality();
        $success = @imagewebp($imageResource, $outputPath, $quality);

        if (!$success) {
            throw SBConversionFailedException::writeError($outputPath, 'Failed to write WebP file');
        }

        if (!file_exists($outputPath)) {
            throw SBConversionFailedException::writeError($outputPath, 'Output file was not created');
        }
    }

    /**
     * Cleans up image resource.
     *
     * @param resource|null $imageResource GD image resource
     */
    public function cleanupImageResource($imageResource): void
    {
        if (is_resource($imageResource)) {
            imagedestroy($imageResource);
        }
    }
}

