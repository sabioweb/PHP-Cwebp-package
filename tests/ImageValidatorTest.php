<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter\Tests;

use Sabioweb\WebPConverter\SBImageValidator;
use Sabioweb\WebPConverter\Exceptions\SBFileNotFoundException;
use Sabioweb\WebPConverter\Exceptions\SBInvalidImageException;
use PHPUnit\Framework\TestCase;

final class ImageValidatorTest extends TestCase
{
    private SBImageValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new SBImageValidator();
    }

    public function testValidateGdExtension(): void
    {
        $this->expectNotToPerformAssertions();
        $this->validator->validateGdExtension();
    }

    public function testValidateImageThrowsExceptionWhenFileNotFound(): void
    {
        $this->expectException(SBFileNotFoundException::class);
        $this->validator->validateImage('/path/to/nonexistent.jpg');
    }

    public function testValidateImageThrowsExceptionForUnsupportedFormat(): void
    {
        $tempFile = sys_get_temp_dir() . '/test.txt';
        file_put_contents($tempFile, 'not an image');

        try {
            $this->expectException(SBInvalidImageException::class);
            $this->validator->validateImage($tempFile);
        } finally {
            @unlink($tempFile);
        }
    }

    public function testGetImageTypeReturnsCorrectType(): void
    {
        // Create a simple test image
        $tempFile = sys_get_temp_dir() . '/test.jpg';
        $image = imagecreatetruecolor(10, 10);
        imagejpeg($image, $tempFile);
        imagedestroy($image);

        try {
            $imageType = $this->validator->getImageType($tempFile);
            $this->assertEquals(IMAGETYPE_JPEG, $imageType);
        } finally {
            @unlink($tempFile);
        }
    }
}

