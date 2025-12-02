# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-01-01

### Added
- Initial release of WebP Converter package
- Support for converting JPEG, PNG, GIF, and BMP images to WebP format
- Configurable quality settings (0-100)
- Lossless conversion mode
- Metadata preservation support (when possible)
- Comprehensive error handling with custom exceptions
- PSR-4 autoloading support
- PHPUnit test suite
- Complete documentation in English and Persian

### Features
- `WebPConverter` class for image conversion
- `ConversionOptions` value object for conversion settings
- `ImageValidator` for input validation
- `FileHandler` for file operations
- Custom exception classes for error handling
- Support for PHP 8.3+

