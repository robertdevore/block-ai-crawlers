# Block AI Crawlers

Block AI Crawlers is a free WordPress® plugin that allows you to block known AI crawlers from accessing your website. 

It integrates seamlessly with WordPress settings to provide a simple interface for managing crawler blocking via `robots.txt`.

## Features

- Adds a checkbox in **Settings > Reading** to enable or disable AI crawler blocking.
- Dynamically updates `robots.txt` to disallow 75+ known AI crawlers.
- Provides an accessible and familiar user experience for WordPress administrators.

## Installation

1. Download the plugin from the [GitHub repository](https://github.com/robertdevore/block-ai-crawlers/).
2. Upload the plugin files to your `/wp-content/plugins/block-ai-crawlers` directory, or install the plugin through the WordPress® Plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Navigate to **Settings > Reading** to configure the plugin.

## Usage

1. Go to **Settings > Reading**.
2. Enable the "Block AI Crawlers" checkbox.
3. Save your changes. The `robots.txt` file will now include rules to block AI crawlers.

## Development

### Prerequisites

- WordPress 5.5 or higher.
- PHP 7.4 or higher.

### Updating the Plugin

The plugin includes automatic update functionality via the Plugin Update Checker library. Updates will pull from the main branch.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.

## License

This plugin is licensed under the GPL-2.0+ license. See the [LICENSE](http://www.gnu.org/licenses/gpl-2.0.txt) file for more details.