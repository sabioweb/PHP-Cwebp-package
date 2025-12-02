# sabioweb/webp-converter (WebP Converter)

A pure PHP package for converting images to WebP format. This package provides a simple and efficient way to convert JPEG, PNG, GIF, and BMP images to WebP format using PHP's GD extension.

## Features

- Convert JPEG, PNG, GIF, and BMP images to WebP
- Configurable quality settings (0-100)
- Lossless conversion mode
- Metadata preservation (when possible)
- Comprehensive error handling
- PSR-4 autoloading
- PHP 8.3+ compatible

## Installation

Install via Composer:

```bash
composer require sabioweb/webp-converter
```

## Requirements

- PHP 8.3 or higher
- GD extension enabled

## Quick Start

```php
<?php

use Sabioweb\WebPConverter\SBWebPConverter;
use Sabioweb\WebPConverter\SBConversionOptions;

require 'vendor/autoload.php';

// Create converter instance
$converter = new SBWebPConverter();

// Convert image with default settings
$converter->convert('input.jpg', 'output.webp');

// Convert with custom quality
$options = SBConversionOptions::create()
    ->setQuality(85);

$converter->convert('input.png', 'output.webp', $options);

// Convert with lossless mode
$options = SBConversionOptions::create()
    ->setLossless(true);

$converter->convert('input.png', 'output.webp', $options);
```

## Usage

### Basic Conversion

```php
use Sabioweb\WebPConverter\WebPConverter;

$converter = new SBWebPConverter();
$converter->convert('path/to/image.jpg', 'path/to/output.webp');
```

### Advanced Options

```php
use Sabioweb\WebPConverter\SBWebPConverter;
use Sabioweb\WebPConverter\SBConversionOptions;

$converter = new SBWebPConverter();

$options = SBConversionOptions::create()
    ->setQuality(90)
    ->setLossless(false)
    ->preserveMetadata(true);

$converter->convert('input.png', 'output.webp', $options);
```

### Error Handling

```php
use Sabioweb\WebPConverter\SBWebPConverter;
use Sabioweb\WebPConverter\Exceptions\SBInvalidImageException;
use Sabioweb\WebPConverter\Exceptions\SBConversionFailedException;
use Sabioweb\WebPConverter\Exceptions\SBFileNotFoundException;

try {
    $converter = new SBWebPConverter();
    $converter->convert('input.jpg', 'output.webp');
} catch (SBFileNotFoundException $e) {
    echo "File not found: " . $e->getMessage();
} catch (SBInvalidImageException $e) {
    echo "Invalid image: " . $e->getMessage();
} catch (SBConversionFailedException $e) {
    echo "Conversion failed: " . $e->getMessage();
}
```

## API Reference

### SBWebPConverter

Main converter class.

#### Methods

- `convert(string $inputPath, string $outputPath, ?ConversionOptions $options = null): void`
  - Converts an image file to WebP format
  - Parameters:
    - `$inputPath`: Path to input image file
    - `$outputPath`: Path to output WebP file
    - `$options`: Optional conversion options

### SBConversionOptions

Configuration options for image conversion.

#### Methods

- `create(): self` - Creates a new instance
- `setQuality(int $quality): self` - Sets quality (0-100, default: 80)
- `setLossless(bool $lossless): self` - Enables/disables lossless mode
- `preserveMetadata(bool $preserve): self` - Preserves metadata when possible
- `getQuality(): int` - Gets current quality setting
- `isLossless(): bool` - Checks if lossless mode is enabled
- `shouldPreserveMetadata(): bool` - Checks if metadata preservation is enabled

## Supported Formats

- **Input**: JPEG, PNG, GIF, BMP
- **Output**: WebP

## License

MIT License. See [LICENSE](LICENSE) file for details.

## Documentation

For Persian documentation, see [README.fa.md](README.fa.md).

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

