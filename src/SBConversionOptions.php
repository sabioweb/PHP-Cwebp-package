<?php

declare(strict_types=1);

namespace Sabioweb\WebPConverter;

/**
 * Value object for conversion options.
 */
final readonly class SBConversionOptions
{
    private const int DEFAULT_QUALITY = 80;
    private const int MIN_QUALITY = 0;
    private const int MAX_QUALITY = 100;

    private function __construct(
        private int $quality = self::DEFAULT_QUALITY,
        private bool $lossless = false,
        private bool $preserveMetadata = false
    ) {
        if ($quality < self::MIN_QUALITY || $quality > self::MAX_QUALITY) {
            throw new \InvalidArgumentException(
                sprintf('Quality must be between %d and %d, got %d', self::MIN_QUALITY, self::MAX_QUALITY, $quality)
            );
        }
    }

    /**
     * Creates a new instance with default options.
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Sets the quality for conversion (0-100).
     */
    public function setQuality(int $quality): self
    {
        return new self($quality, $this->lossless, $this->preserveMetadata);
    }

    /**
     * Enables or disables lossless mode.
     */
    public function setLossless(bool $lossless): self
    {
        return new self($this->quality, $lossless, $this->preserveMetadata);
    }

    /**
     * Enables or disables metadata preservation.
     */
    public function preserveMetadata(bool $preserve): self
    {
        return new self($this->quality, $this->lossless, $preserve);
    }

    /**
     * Gets the quality setting.
     */
    public function getQuality(): int
    {
        return $this->quality;
    }

    /**
     * Checks if lossless mode is enabled.
     */
    public function isLossless(): bool
    {
        return $this->lossless;
    }

    /**
     * Checks if metadata preservation is enabled.
     */
    public function shouldPreserveMetadata(): bool
    {
        return $this->preserveMetadata;
    }
}

