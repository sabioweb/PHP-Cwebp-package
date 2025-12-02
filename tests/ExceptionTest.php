<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter\Tests;

use Sabioweb\WebPConverter\Exceptions\SBFileNotFoundException;
use Sabioweb\WebPConverter\Exceptions\SBInvalidImageException;
use Sabioweb\WebPConverter\Exceptions\SBConversionFailedException;
use PHPUnit\Framework\TestCase;

final class ExceptionTest extends TestCase
{
    public function testFileNotFoundException(): void
    {
        $exception = SBFileNotFoundException::fileNotFound('/path/to/file.jpg');
        $this->assertInstanceOf(SBFileNotFoundException::class, $exception);
        $this->assertStringContainsString('File not found', $exception->getMessage());
    }

    public function testInvalidImageExceptionUnsupportedFormat(): void
    {
        $exception = SBInvalidImageException::unsupportedFormat('tiff');
        $this->assertInstanceOf(SBInvalidImageException::class, $exception);
        $this->assertStringContainsString('Unsupported image format', $exception->getMessage());
    }

    public function testInvalidImageExceptionGdNotAvailable(): void
    {
        $exception = SBInvalidImageException::gdNotAvailable();
        $this->assertInstanceOf(SBInvalidImageException::class, $exception);
        $this->assertStringContainsString('GD extension', $exception->getMessage());
    }

    public function testConversionFailedException(): void
    {
        $exception = SBConversionFailedException::conversionError('/path/to/image.jpg', 'Test reason');
        $this->assertInstanceOf(SBConversionFailedException::class, $exception);
        $this->assertStringContainsString('Failed to convert', $exception->getMessage());
    }
}

