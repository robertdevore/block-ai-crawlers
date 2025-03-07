<?php

 /**
  * The plugin bootstrap file
  *
  * @link              https://robertdevore.com
  * @since             1.0.0
  * @package           Block_AI_Crawlers
  *
  * @wordpress-plugin
  *
  * Plugin Name: Block AI Crawlers
  * Description: Adds an option to block known AI crawlers via the robots.txt file
  * Plugin URI:  https://github.com/robertdevore/block-ai-crawlers/
  * Version:     1.1.2
  * Author:      Robert DeVore
  * Author URI:  https://robertdevore.com/
  * License:     GPL-2.0+
  * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
  * Text Domain: block-ai-crawlers
  * Domain Path: /languages
  * Update URI:  https://github.com/robertdevore/block-ai-crawlers/
  */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define constants.
define( 'BLOCK_AI_CRAWLERS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BLOCK_AI_CRAWLERS_VERSION', '1.1.2' );

// Add the Plugin Update Checker.
require 'vendor/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/robertdevore/block-ai-crawlers/',
    __FILE__,
    'block-ai-crawlers'
);

// Set the branch that contains the stable release.
$myUpdateChecker->setBranch( 'main' );

// Check if Composer's autoloader is already registered globally.
if ( ! class_exists( 'RobertDevore\WPComCheck\WPComPluginHandler' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use RobertDevore\WPComCheck\WPComPluginHandler;

new WPComPluginHandler( plugin_basename( __FILE__ ), 'https://robertdevore.com/why-this-plugin-doesnt-support-wordpress-com-hosting/' );

/**
 * Load plugin text domain for translations
 * 
 * @since  1.1.0
 * @return void
 */
function block_ai_crawlers_load_textdomain() {
    load_plugin_textdomain( 
        'block-ai-crawlers',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages/'
    );
}
add_action( 'plugins_loaded', 'block_ai_crawlers_load_textdomain' );

/**
 * Class Block_Ai_Crawlers
 * 
 * This class provides functionality to block AI crawlers from accessing the site.
 * It integrates with WordPress settings to allow users to enable or disable
 * the blocking feature. When enabled, known AI crawlers are disallowed via robots.txt.
 *
 * Features:
 * - Adds a checkbox in the Settings > Reading section to enable AI crawler blocking.
 * - Modifies the robots.txt output dynamically to disallow known AI crawlers.
 *
 * Usage:
 * - Activate the plugin.
 * - Navigate to Settings > Reading and enable "Block AI Crawlers".
 */
class Block_Ai_Crawlers {

    private $option_name = 'block_ai_crawlers_enabled';

    private $ai_crawlers = [];

    public function __construct() {
        $this->ai_crawlers = apply_filters('block_ai_crawlers_crawler_list', [
            'ChatGPT-User',
            'BingAI',
            'OpenAI',
            'AnthropicAI',
            'JasperAI',
            'AI Content Detector',
            'AI SEO Crawler',
            'Grammarly',
            'Copyscape',
            'QuillBot',
            'Writesonic',
            'Hypotenuse AI',
            'CopyAI',
            'Frase AI',
            'ContentBot',
            'DeepAI',
            'Inferkit',
            'Sudowrite',
            'AI Writer',
            'INK Editor',
            'Scalenut',
            'Writecream',
            'ZimmWriter',
            'ScalenutBot',
            'Contentedge',
            'Rytr',
            'Anyword',
            'Wordtune',
            'WordAI',
            'Spin Rewriter',
            'Neural Text',
            'Writescope',
            'Simplified AI',
            'Text Blaze',
            'OpenText AI',
            'DeepL',
            'SaplingAI',
            'Copymatic',
            'AI Dungeon',
            'Narrative Device',
            'TextCortex',
            'AI21 Labs',
            'WriterZen',
            'Outwrite',
            'SEO Content Machine',
            'CrawlQ AI',
            'SlickWrite',
            'ProWritingAid',
            'Hemingway Editor',
            'Content Harmony',
            'Content King',
            'RobotSpider',
            'ContentAtScale',
            'Surfer AI',
            'INKforall',
            'ClearScope',
            'MarketMuse',
            'NeuralSEO',
            'Conversion AI',
            'Content Samurai',
            'Vidnami AI',
            'Kafkai',
            'Paraphraser.io',
            'Spinbot',
            'Articoolo',
            'AI Article Writer',
            'SEO Robot',
            'AI Search Engine',
            'Automated Writer',
            'ScriptBook',
            'Keyword Density AI',
            'MetaTagBot',
            'Content Optimizer',
            'Page Analyzer AI'
        ] );

        add_action( 'admin_init', [ $this, 'register_setting' ] );
        add_action( 'do_robots', [ $this, 'modify_robots' ] );
    }

    /**
     * Register the setting in Reading Settings.
     *
     * This function adds a new setting field to the WordPress Reading Settings page.
     * It allows users to enable or disable the blocking of AI crawlers.
     *
     * @since  1.0.0
     * @return void
     */
    public function register_setting() {
        register_setting( 'reading', $this->option_name );

        add_settings_field(
            $this->option_name,
            esc_html__( 'AI crawlers visibility', 'block-ai-crawlers' ),
            [ $this, 'render_setting_field' ],
            'reading'
        );
    }

    /**
     * Render the setting field.
     *
     * Outputs the HTML for the "Block AI Crawlers" checkbox in the Reading Settings page.
     * If the setting is enabled, the checkbox will be checked.
     *
     * @since  1.0.0
     * @return void
     */
    public function render_setting_field() {
        $value = get_option( $this->option_name, 0 );
        $html  = '<fieldset>';
        $html .= '<legend class="screen-reader-text"><span>' . esc_html__( 'Block AI Crawlers', 'block-ai-crawlers' ) . '</span></legend>';
        $html .= '<label for="' . esc_attr( $this->option_name ) . '"><input name="' . esc_attr( $this->option_name ) . '" type="checkbox" id="' . esc_attr( $this->option_name ) . '" value="1" ' . checked( 1, $value, false ) . ' /> ' . esc_html__( 'Block AI Crawlers from crawling your site', 'block-ai-crawlers' ) . '</label>';
        $html .= '<p class="description">' . esc_html__( 'Prevent known AI crawlers from crawling your site.', 'block-ai-crawlers' ) . '</p>';
        $html .= '</fieldset>';
        echo $html;
    }

    /**
     * Modify robots.txt rules if the setting is enabled.
     *
     * Dynamically appends `Disallow` rules to the `robots.txt` file for each AI crawler
     * in the predefined list if the blocking option is enabled.
     *
     * @since  1.0.0
     * @return void
     */
    public function modify_robots() {
        if ( get_option( $this->option_name ) ) {
            foreach ( $this->ai_crawlers as $crawler ) {
                echo "User-agent: {$crawler}\n";
                echo "Disallow: /\n";
            }
        }
    }
}

new Block_Ai_Crawlers();
