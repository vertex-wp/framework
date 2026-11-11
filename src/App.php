<?php

/**
 * @author    Sina Kuhestani <sinakuhestani@gmail.com>
 * @copyright 2026 Sina Kuhestani (EphpicMan)
 * @link      https://github.com/ephpicman GitHub
 * @link      https://www.linkedin.com/in/ephpicman LinkedIn
 * @license   See the LICENSE file in the project root.
 */

declare(strict_types=1);

namespace Vertex;

final class App
{
    /** @var App|null */
    private static ?App $instance = null;
    /** @var array<int, string> */
    private array $plugins = [];
    /** @var array<int, string> */
    private array $themes = [];

    public static function getInstance(): App
    {
        return self::$instance ??= new self();
    }

    private function __construct() {}

    public function registerPlugin(string $path): void
    {
        $this->plugins[] = $path;
    }

    public function registerTheme(string $path): void
    {
        $this->themes[] = $path;
    }

    public function run(): void
    {
        // Application orchestration will be implemented here.
    }

    public function terminate(): void
    {
        // Final request handling will be implemented here.
    }
}
