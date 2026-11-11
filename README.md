# Vertex

Vertex is a lightweight PHP framework for WordPress plugins and themes. It provides a minimal application registry, PSR-4-style class loading and reusable WordPress query-argument builders.

## Requirements

- PHP 8.0 or later
- WordPress 6.3 or later

The framework must be loaded from within a WordPress request because its bootstrap and query classes rely on WordPress.

## Installation

Place the framework in the product's `vertex/` directory. Keep product-specific classes in `src/`.

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

Require the framework bootstrap file from the main plugin or theme file, then register the product root, product namespace and the version of the bundled Vertex framework:

```php
require_once __DIR__ . '/vertex/bootstrap.php';

VertexLoader::register(
    __DIR__,
    'ExamplePlugin\',
    '1.4.0',
);
```

The loader resolves the `vertex/` and `src/` directories relative to the product root. The third argument is the bundled Vertex framework version, not the product version. When multiple products load separate framework copies in the same request, the loader selects the copy with the highest framework version. Register products during normal plugin loading, before WordPress fires `after_setup_theme`.

A product entry point can then register its application with the framework:

```php
namespace ExamplePlugin;

use Vertex\App;

App::getInstance()->registerPlugin(__DIR__);
```

## Namespaces

All framework classes use the `Vertex` root namespace. Sub-namespaces mirror the source directory structure:

| Namespace | Purpose |
|---|---|
| `Vertex\App` | Application instance and product registration |
| `Vertex\Utils` | General-purpose utilities |
| `Vertex\WordPress` | WordPress query argument builders |

Examples:

```php
use Vertex\Utils\ArrayUtil;
use Vertex\Utils\NumberUtil;
use Vertex\WordPress\PostQueryArg;

$ids = ArrayUtil::normalizeUniqueStrings(['one', '', 'one', 'two']);
$id = NumberUtil::absint('-42');

$query = PostQueryArg::make()
    ->postType('article')
    ->postStatus('publish')
    ->postsPerPage(10)
    ->orderBy('date')
    ->order('DESC');

$args = $query->toArray();

$queryResult = new \WP_Query($args);
```

## Query argument builders

Classes in `Vertex\WordPress` build argument arrays; they do not execute WordPress queries. Pass the returned array to the corresponding WordPress API.

Available builders:

- `CommentQueryArg` for `WP_Comment_Query`
- `DateQueryArg` for date-query clauses
- `MetaQueryArg` for metadata-query clauses
- `NetworkQueryArg` for multisite network queries
- `PostQueryArg` for `WP_Query`
- `SiteQueryArg` for multisite site queries
- `TaxQueryArg` for taxonomy-query clauses
- `TermQueryArg` for `WP_Term_Query`
- `UserQueryArg` for `WP_User_Query`

Builders support constructor hydration through `make(array $queryArray)`, fluent methods and conversion through `toArray()`. Nested date, metadata and taxonomy conditions can be composed with their respective query builder classes.

## Autoloading

`VertexLoader` registers the `Vertex\` namespace against `vertex/src/` and each product namespace against its `src/` directory. Framework class files must follow PSR-4-style namespace-to-directory mapping.

The loader compares the explicitly registered bundled framework versions and selects the highest one when multiple products load separate copies of Vertex in the same request.

## Project Policies

- [Code of Conduct](CODE_OF_CONDUCT.md)
- [Contributing](CONTRIBUTING.md)
- [Governance](GOVERNANCE.md)
- [Accessibility](ACCESSIBILITY.md)
- [Security Policy](SECURITY.md)
- [Support](SUPPORT.md)
- [Changelog](CHANGELOG.md)

## Development

Run the unit suite with:

```bash
php phpunit.phar --configuration phpunit.xml
```

The integration suite uses the official WordPress PHPUnit test library and a configured test database. Install the WordPress test library, set `WP_TESTS_DIR` to its directory, then run:

```bash
php phpunit.phar --configuration phpunit.integration.xml
```

The GitHub Actions workflow runs the unit suite across the supported PHP and PHPUnit matrix. It also runs WordPress integration tests against the minimum supported WordPress version and the latest release on representative PHP versions. The coverage job publishes its report in the workflow summary.
