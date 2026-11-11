<?php

/**
 * @author    Sina Kuhestani <sinakuhestani@gmail.com>
 * @copyright 2026 Sina Kuhestani
 * @link      https://github.com/vertex-wp/framework
 * @license   See the LICENSE file in the project root.
 */

declare(strict_types=1);

use Vertex\WordPress\DateQueryArg;
use Vertex\WordPress\MetaQueryArg;
use Vertex\WordPress\PostQueryArg;
use Vertex\WordPress\TaxQueryArg;

final class PostQueryArgIntegrationTest extends WP_UnitTestCase
{
    public function testPostQueryArgBuildsArgumentsAcceptedByWordPressForMetaAndTaxonomyQueries(): void
    {
        $term = wp_insert_term('Vertex integration', 'category');

        self::assertNotInstanceOf(WP_Error::class, $term);

        $matchingPostId = self::factory()->post->create([
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_title' => 'Matching Vertex post',
        ]);
        $unmatchedPostId = self::factory()->post->create([
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_title' => 'Unmatched Vertex post',
        ]);

        update_post_meta($matchingPostId, 'integration_flag', 'enabled');
        update_post_meta($unmatchedPostId, 'integration_flag', 'disabled');
        wp_set_object_terms($matchingPostId, (int) $term['term_id'], 'category');

        $args = PostQueryArg::make()
            ->postType('post')
            ->postStatus('publish')
            ->metaQuery(
                MetaQueryArg::make()->where('integration_flag', 'enabled')
            )
            ->taxQuery(
                TaxQueryArg::make()->where('category', [(int) $term['term_id']])
            )
            ->toArray();

        $query = new WP_Query($args);

        self::assertSame(
            [$matchingPostId],
            array_map('intval', wp_list_pluck($query->posts, 'ID'))
        );
        self::assertNotContains($unmatchedPostId, wp_list_pluck($query->posts, 'ID'));
    }

    public function testPostQueryArgBuildsArgumentsAcceptedByWordPressForDateQueries(): void
    {
        $olderPostId = self::factory()->post->create([
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_date' => '2023-06-15 12:00:00',
        ]);
        $newerPostId = self::factory()->post->create([
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_date' => '2024-06-15 12:00:00',
        ]);

        $args = PostQueryArg::make()
            ->includeIds([$olderPostId, $newerPostId])
            ->dateQuery(
                DateQueryArg::make()->after('2024-01-01', true)
            )
            ->orderBy('date')
            ->order('ASC')
            ->toArray();

        $query = new WP_Query($args);

        self::assertSame(
            [$newerPostId],
            array_map('intval', wp_list_pluck($query->posts, 'ID'))
        );
    }
}
