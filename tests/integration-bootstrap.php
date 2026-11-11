<?php

/**
 * @author    Sina Kuhestani <sinakuhestani@gmail.com>
 * @copyright 2026 Sina Kuhestani
 * @link      https://github.com/vertex-wp/framework
 * @license   See the LICENSE file in the project root.
 */

declare(strict_types=1);

$testsDirectory = getenv('WP_TESTS_DIR') ?: '/tmp/wordpress-tests-lib';
$wordpressBootstrap = $testsDirectory . '/includes/bootstrap.php';
$wordpressFunctions = $testsDirectory . '/includes/functions.php';
$polyfillsDirectory = getenv('WP_TESTS_PHPUNIT_POLYFILLS_PATH');

if (!is_file($wordpressBootstrap) || !is_file($wordpressFunctions)) {
    throw new RuntimeException(
        'The WordPress test suite was not found. Set WP_TESTS_DIR to its directory.'
    );
}

if (!is_string($polyfillsDirectory) || !is_file($polyfillsDirectory . '/phpunitpolyfills-autoload.php')) {
    throw new RuntimeException(
        'The PHPUnit Polyfills library was not found. Set WP_TESTS_PHPUNIT_POLYFILLS_PATH to its directory.'
    );
}

define('WP_TESTS_PHPUNIT_POLYFILLS_PATH', $polyfillsDirectory);

require_once $wordpressFunctions;

tests_add_filter(
    'muplugins_loaded',
    static function (): void {
        require_once __DIR__ . '/bootstrap.php';
    }
);

require $wordpressBootstrap;
