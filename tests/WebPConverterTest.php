<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter\Tests;

use Sabioweb\WebPConverter\SBWebPConverter;
use Sabioweb\WebPConverter\SBConversionOptions;
use Sabioweb\WebPConverter\Exceptions\SBFileNotFoundException;
use Sabioweb\WebPConverter\Exceptions\SBInvalidImageException;
use Sabioweb\WebPConverter\Exceptions\SBConversionFailedException;
use PHPUnit\Framework\TestCase;

final class WebPConverterTest extends TestCase
{
    private SBWebPConverter $converter;
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->converter = new SBWebPConverter();
        $this->tempDir = sys_get_temp_dir() . '/webp_converter_test_' . uniqid();
        mkdir($this->tempDir, 0755, true);
    }

    protected function tearDown(): void
    {
        $this->cleanupTempFiles();
        parent::tearDown();
    }

    private function cleanupTempFiles(): void
    {
        if (is_dir($this->tempDir)) {
            $files = glob($this->tempDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            @rmdir($this->tempDir);
        }
    }

    private function createTestImage(string $path, int $type = IMAGETYPE_JPEG): void
    {
        $image = imagecreatetruecolor(100, 100);
        $color = imagecolorallocate($image, 255, 0, 0);
        imagefilledrectangle($image, 0, 0, 100, 100, $color);

        match ($type) {
            IMAGETYPE_JPEG => imagejpeg($image, $path, 90),
            IMAGETYPE_PNG => imagepng($image, $path),
            IMAGETYPE_GIF => imagegif($image, $path),
            IMAGETYPE_BMP => imagebmp($image, $path),
            default => throw new \InvalidArgumentException('Unsupported image type'),
        };

        imagedestroy($image);
    }

    public function testConvertJpegToWebP(): void
    {
        $inputPath = $this->tempDir . '/test.jpg';
        $outputPath = $this->tempDir . '/test.webp';

        $this->createTestImage($inputPath, IMAGETYPE_JPEG);
        $this->converter->convert($inputPath, $outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    public function testConvertPngToWebP(): void
    {
        $inputPath = $this->tempDir . '/test.png';
        $outputPath = $this->tempDir . '/test.webp';

        $this->createTestImage($inputPath, IMAGETYPE_PNG);
        $this->converter->convert($inputPath, $outputPath);

        $this->assertFileExists($outputPath);
    }

    public function testConvertGifToWebP(): void
    {
        $inputPath = $this->tempDir . '/test.gif';
        $outputPath = $this->tempDir . '/test.webp';

        $this->createTestImage($inputPath, IMAGETYPE_GIF);
        $this->converter->convert($inputPath, $outputPath);

        $this->assertFileExists($outputPath);
    }

    public function testConvertWithCustomQuality(): void
    {
        $inputPath = $this->tempDir . '/test.jpg';
        $outputPath = $this->tempDir . '/test.webp';

        $this->createTestImage($inputPath);
        $options = SBConversionOptions::create()->setQuality(50);
        $this->converter->convert($inputPath, $outputPath, $options);

        $this->assertFileExists($outputPath);
    }

    public function testConvertWithLosslessMode(): void
    {
        $inputPath = $this->tempDir . '/test.png';
        $outputPath = $this->tempDir . '/test.webp';

        $this->createTestImage($inputPath, IMAGETYPE_PNG);
        $options = SBConversionOptions::create()->setLossless(true);
        $this->converter->convert($inputPath, $outputPath, $options);

        $this->assertFileExists($outputPath);
    }

    public function testConvertThrowsExceptionWhenFileNotFound(): void
    {
        $this->expectException(SBFileNotFoundException::class);
        $this->converter->convert('/nonexistent/file.jpg', $this->tempDir . '/output.webp');
    }

    public function testConvertThrowsExceptionForInvalidImage(): void
    {
        $invalidFile = $this->tempDir . '/invalid.txt';
        file_put_contents($invalidFile, 'not an image');

        try {
            $this->expectException(SBInvalidImageException::class);
            $this->converter->convert($invalidFile, $this->tempDir . '/output.webp');
        } finally {
            @unlink($invalidFile);
        }
    }

    public function testConvertCreatesOutputDirectory(): void
    {
        $inputPath = $this->tempDir . '/test.jpg';
        $outputPath = $this->tempDir . '/subdir/output.webp';

        $this->createTestImage($inputPath);
        $this->converter->convert($inputPath, $outputPath);

        $this->assertFileExists($outputPath);
        $this->assertDirectoryExists(dirname($outputPath));
    }
}

