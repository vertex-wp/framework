# Vertex Framework

A lightweight PHP framework for WordPress plugins and themes.

## Requirements

- PHP 8.0 or later
- WordPress 6.3 or later

## Installation

Place the framework in the product's `vertex/` directory and the product source in `src/`.

## Bootstrap

Require `vertex/bootstrap.php` from the product's main file, then register the product:

```php
VertexLoader::register(__DIR__, 'ExamplePlugin\\', '1.4.0');
```

Framework classes use the `Vertex\\Framework` namespace.
