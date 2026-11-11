<?php

/**
 * @author    Sina Kuhestani <sinakuhestani@gmail.com>
 * @copyright 2026 Sina Kuhestani (EphpicMan)
 * @link      https://github.com/ephpicman GitHub
 * @link      https://www.linkedin.com/in/ephpicman LinkedIn
 * @license   See the LICENSE file in the project root.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

if (!class_exists('VertexLoader', false)) {
    final class VertexLoader
    {
        /** @var array<string, array{framework_version: string, path: string}> */
        private static array $availableFrameworkInstances = [];
        /** @var array<string, string> */
        private static array $products = [];
        private static bool $isFinalizationHookRegistered = false;
        private static bool $initialized = false;

        public static function register(string $pluginPath, string $pluginNamespace, string $frameworkVersion): void
        {
            if (self::$initialized)
                return;

            $pluginNamespace = trim($pluginNamespace, '\\');
            if ('' === $pluginNamespace)
                return;

            $pluginNamespace .= '\\';

            $pluginPath = realpath($pluginPath);
            if (false === $pluginPath)
                return;

            $frameworkPath = "{$pluginPath}/vertex";
            $productPath = "{$pluginPath}/src";

            if (
                !is_dir("{$frameworkPath}/src") ||
                !is_dir($productPath) ||
                !preg_match('/^\d+\.\d+\.\d+(?:-[0-9A-Za-z.-]+)?(?:\+[0-9A-Za-z.-]+)?$/', $frameworkVersion)
            ) {
                return;
            }

            self::$availableFrameworkInstances[$frameworkPath] = [
                'framework_version' => $frameworkVersion,
                'path' => $frameworkPath,
            ];

            self::$products[$pluginNamespace] = $productPath;

            if (self::$isFinalizationHookRegistered)
                return;

            self::$isFinalizationHookRegistered = true;

            add_action('after_setup_theme', [self::class, 'initialize'], PHP_INT_MAX);
        }

        public static function initialize(): void
        {
            if (self::$initialized || empty(self::$availableFrameworkInstances))
                return;

            $availableFrameworkInstances = array_values(self::$availableFrameworkInstances);

            usort(
                $availableFrameworkInstances,
                static fn(array $a, array $b): int => version_compare($b['framework_version'], $a['framework_version'])
            );

            $selected = $availableFrameworkInstances[0];
            $frameworkSourcePath = $selected['path'] . '/src';

            if (!is_dir($frameworkSourcePath))
                return;

            self::$initialized = true;

            spl_autoload_register(
                static function (string $class) use ($frameworkSourcePath): void {
                    self::autoloadClass($class, ['Vertex\\' => $frameworkSourcePath]);
                }
            );

            foreach (self::$products as $pluginNamespace => $src) {
                spl_autoload_register(
                    static function (string $class) use ($pluginNamespace, $src): void {
                        self::autoloadClass($class, array(
                            $pluginNamespace => $src,
                        ));
                    }
                );
            }
        }

        private static function autoloadClass(string $class, array $prefixes): void
        {
            foreach ($prefixes as $prefix => $directory) {
                if (0 !== strpos($class, $prefix))
                    continue;

                $relativeClass = substr($class, strlen($prefix));
                $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass);
                $file = $directory . DIRECTORY_SEPARATOR . $relativePath . '.php';

                if (is_file($file) && is_readable($file))
                    require_once $file;

                return;
            }
        }
    }

    add_action('wp_loaded', static function (): void {
        Vertex\App::getInstance()->run();
    });
}

/*
 * Register the loader from the product's main plugin file, not here.
 *
 * The product must keep its directories named /vertex and /src.
 * Pass only the product root path; the loader resolves both directories internally.
 *
 * Example (in the main plugin file):
 *
 * VertexLoader::register(
 *     __DIR__,
 *     'ExamplePlugin\\',
 *     '1.4.0',
 * );
 */
