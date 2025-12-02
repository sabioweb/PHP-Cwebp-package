<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter\Tests;

use Sabioweb\WebPConverter\SBConversionOptions;
use PHPUnit\Framework\TestCase;

final class ConversionOptionsTest extends TestCase
{
    public function testCreateReturnsDefaultOptions(): void
    {
        $options = SBConversionOptions::create();

        $this->assertEquals(80, $options->getQuality());
        $this->assertFalse($options->isLossless());
        $this->assertFalse($options->shouldPreserveMetadata());
    }

    public function testSetQuality(): void
    {
        $options = SBConversionOptions::create()->setQuality(90);

        $this->assertEquals(90, $options->getQuality());
    }

    public function testSetQualityThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        SBConversionOptions::create()->setQuality(150);
    }

    public function testSetLossless(): void
    {
        $options = SBConversionOptions::create()->setLossless(true);

        $this->assertTrue($options->isLossless());
    }

    public function testPreserveMetadata(): void
    {
        $options = SBConversionOptions::create()->preserveMetadata(true);

        $this->assertTrue($options->shouldPreserveMetadata());
    }

    public function testOptionsAreImmutable(): void
    {
        $options1 = SBConversionOptions::create()->setQuality(85);
        $options2 = $options1->setQuality(90);

        $this->assertEquals(85, $options1->getQuality());
        $this->assertEquals(90, $options2->getQuality());
    }
}

