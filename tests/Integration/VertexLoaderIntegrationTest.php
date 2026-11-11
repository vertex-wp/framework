<?php

/**
 * @author    Sina Kuhestani <sinakuhestani@gmail.com>
 * @copyright 2026 Sina Kuhestani
 * @link      https://github.com/vertex-wp/framework
 * @license   See the LICENSE file in the project root.
 */

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

final class VertexLoaderIntegrationTest extends WP_UnitTestCase
{
    public function testLoaderRegistersFrameworkAndProductAutoloaders(): void
    {
        $productPath = __DIR__ . '/../fixtures/loader-product';

        VertexLoader::register(
            $productPath,
            'VertexIntegrationProduct\\',
            '1.0.0'
        );

        self::assertNotFalse(
            has_action('after_setup_theme', [VertexLoader::class, 'initialize'])
        );

        VertexLoader::initialize();

        self::assertTrue(class_exists(\Vertex\IntegrationFixture::class));
        self::assertTrue(class_exists(\VertexIntegrationProduct\Plugin::class));
    }
}
