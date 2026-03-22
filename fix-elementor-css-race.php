<?php
/**
 * Plugin Name: Fix Elementor CSS Race Condition
 * Description: Clears stale object cache before Elementor reads _elementor_css post meta, preventing missing <link> tags caused by a known race condition (GitHub #32226).
 * Version: 1.0.0
 * Author: Medeon
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'get_post_metadata', function ( $value, $object_id, $meta_key ) {
	if ( '_elementor_css' === $meta_key ) {
		wp_cache_delete( $object_id, 'post_meta' );
	}
	return $value;
}, 1, 3 );
