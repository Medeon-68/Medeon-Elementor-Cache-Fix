# Fix Elementor CSS Race Condition

A WordPress must-use plugin that fixes a known Elementor race condition causing intermittent missing CSS on pages built with Elementor Theme Builder templates.

## The Problem

Elementor has a documented race condition ([GitHub #32226](https://github.com/elementor/elementor/issues/32226)) where CSS `<link>` tags are randomly missing from the HTML output. This causes pages to load without styles — a broken layout that appears intermittently and is difficult to reproduce.

### Root Cause

When two HTTP requests arrive simultaneously (browser prefetch, crawlers, concurrent users), both trigger Elementor's CSS regeneration for the same post. The first request performs an `INSERT` into `wp_postmeta`, and the second attempts an `UPDATE` with identical data. Since no rows are affected by the `UPDATE`, WordPress skips the `wp_cache_delete()` call in `update_metadata()`. The subsequent `get_post_meta()` then reads stale data from the object cache — returning an empty `status` — which causes Elementor to skip enqueueing the CSS `<link>` tag entirely.

If a page caching layer (LiteSpeed Cache, W3 Total Cache, WP Rocket, etc.) caches this broken HTML, all visitors see the unstyled page until the cache is manually purged.

### Symptoms

- Pages randomly lose all Elementor styling
- The issue is intermittent — refreshing or purging cache sometimes fixes it, sometimes doesn't
- CSS files exist on disk (not 404), but the `<link>` tags referencing them are missing from the HTML
- The problem is worse on sites with any traffic, crawlers, or page cache enabled
- Staging sites with no traffic typically don't exhibit the bug

## The Fix

This mu-plugin hooks into WordPress's `get_post_metadata` filter and clears the object cache for post meta immediately before Elementor reads the `_elementor_css` meta key. This ensures `get_post_meta()` always returns fresh data from the database instead of stale cached data left behind by the race condition.

```php
add_filter( 'get_post_metadata', function ( $value, $object_id, $meta_key ) {
    if ( '_elementor_css' === $meta_key ) {
        wp_cache_delete( $object_id, 'post_meta' );
    }
    return $value;
}, 1, 3 );
```

## Installation

See [INSTALL.md](INSTALL.md) for detailed instructions.

**Quick start:**

1. Download `fix-elementor-css-race.php`
2. Upload to `wp-content/mu-plugins/` on your server
3. Regenerate Elementor CSS and purge all caches

No activation needed — must-use plugins load automatically.

## Compatibility

- WordPress 5.0+
- Elementor 3.x (any version)
- Works with all page caching plugins (LiteSpeed Cache, W3 Total Cache, WP Rocket, WP Super Cache, etc.)
- Works with or without persistent object cache (Redis, Memcached)
- Safe to use alongside Elementor CSS Regenerator and similar plugins

## Performance Impact

Negligible. The `wp_cache_delete()` call only fires when `_elementor_css` meta is read, which happens once per Elementor template per page load. The cost of one extra database read per template is far smaller than the cost of serving broken pages.

## References

- [Elementor GitHub Issue #32226](https://github.com/elementor/elementor/issues/32226) — Original race condition report and analysis
- [WordPress.org: Missing Elementor CSS pages when using page cache](https://wordpress.org/support/topic/missing-elementor-css-pages-when-using-page-cache/) — Community discussion
- [WordPress.org: Missing stylesheets — race condition?](https://wordpress.org/support/topic/missing-stylesheets-referenced-in-html-race-condition-2/) — Earlier report of the same issue

## License

GPL-2.0-or-later
