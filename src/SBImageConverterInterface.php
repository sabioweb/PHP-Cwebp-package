<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter;

use Sabioweb\WebPConverter\SBConversionOptions;

/**
 * Interface for image converter implementations.
 */
interface SBImageConverterInterface
{
    /**
     * Converts an image file to WebP format.
     *
     * @param string $inputPath Path to the input image file
     * @param string $outputPath Path to the output WebP file
     * @param SBConversionOptions|null $options Optional conversion options
     * @return void
     * @throws \Sabioweb\WebPConverter\Exceptions\SBFileNotFoundException
     * @throws \Sabioweb\WebPConverter\Exceptions\SBInvalidImageException
     * @throws \Sabioweb\WebPConverter\Exceptions\SBConversionFailedException
     */
    public function convert(string $inputPath, string $outputPath, ?SBConversionOptions $options = null): void;
}

