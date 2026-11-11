# Vertex

A lightweight PHP framework for WordPress plugins and themes.

## Requirements

- PHP 8.0 or later
- WordPress 6.3 or later

## Directory structure

Install the framework in the product's `vertex/` directory. Keep product-specific PHP classes in `src/`.

```text
example-plugin/
├── src/
│   └── Plugin.php
├── vertex/
│   ├── bootstrap.php
│   └── src/
│       ├── App.php
│       ├── Utils/
│       └── WordPress/
└── example-plugin.php
```

## Bootstrap

Require the framework bootstrap file from the product's main plugin or theme file, then register the product's root directory, namespace, and version:

```php
require_once __DIR__ . '/vertex/bootstrap.php';

VertexLoader::register(
    __DIR__,
    'ExamplePlugin\\',
    '1.4.0',
);
```

The loader resolves the framework and product source directories automatically. The expected directory names are `vertex/` and `src/`.

## Namespaces

All framework classes use the `Vertex` root namespace. Sub-namespaces reflect the source directory structure:

- `Vertex\App`
- `Vertex\Utils\ArrayUtil`
- `Vertex\Utils\NumberUtil`
- `Vertex\WordPress\PostQueryArg`

Import framework classes directly from these namespaces:

```php
use Vertex\App;
use Vertex\WordPress\PostQueryArg;

$app = App::getInstance();
```

Product classes should use their own product namespace, such as `ExamplePlugin\`.

## Autoloading

`VertexLoader` registers PSR-4-style autoloaders for the selected framework version and each registered product namespace. The framework namespace prefix is `Vertex\`, mapped to `vertex/src/`.

## Compatibility

This namespace change is a breaking change for products using the former `Vertex\Framework` namespace. Update all framework imports and type references to the corresponding `Vertex` namespace.
