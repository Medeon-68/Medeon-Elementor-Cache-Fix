# Installation Guide

## Requirements

- WordPress 5.0 or higher
- Elementor (any 3.x version)
- FTP/SFTP access or a file manager plugin (e.g. WP File Manager PRO)

## Step 1 — Download

Download `fix-elementor-css-race.php` from this repository.

## Step 2 — Upload

Upload the file to `wp-content/mu-plugins/` on your WordPress server.

If the `mu-plugins` folder does not exist, create it:

```
wp-content/
├── mu-plugins/
│   └── fix-elementor-css-race.php   ← place here
├── plugins/
├── themes/
└── uploads/
```

### Upload methods

**WP File Manager PRO (if installed):**
1. Open WP File Manager in WordPress admin
2. Navigate to `wp-content/`
3. Create folder `mu-plugins` if it doesn't exist
4. Open `mu-plugins/` and upload `fix-elementor-css-race.php`

**FTP/SFTP:**
1. Connect to your server
2. Navigate to `/public_html/wp-content/mu-plugins/` (create if needed)
3. Upload `fix-elementor-css-race.php`

**WP-CLI:**
```bash
mkdir -p wp-content/mu-plugins
cp fix-elementor-css-race.php wp-content/mu-plugins/
```

## Step 3 — Clear caches

1. Go to **Elementor → Tools** and click **Regenerate CSS & Data**
2. Purge all page cache (LiteSpeed Cache → Purge All, or equivalent)
3. Clear browser cache or test in incognito mode

## Verification

The plugin is active immediately — no activation step needed. To verify:

1. Go to **WordPress Admin → Plugins → Must-Use**
2. You should see "Fix Elementor CSS Race Condition" listed
3. Visit several pages that previously had broken styles — they should now load correctly on every request

## Uninstallation

Delete `wp-content/mu-plugins/fix-elementor-css-race.php` from your server. No database cleanup needed.
