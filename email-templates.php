<?php
/**
 * Email Templates
 *
 * @link              https://github.com/Cleversupport/clever-email-templates
 * @since             2.0
 * @package           Mailtpl
 *
 * @wordpress-plugin
 * Plugin Name:       Clever Email Templates
 * Plugin URI:        https://github.com/Cleversupport/clever-email-templates
 * GitHub Plugin URI: https://github.com/Cleversupport/clever-email-templates
 * Description:       Site Mailer compatible HTML email templates for WordPress.
 * Version:           1.5.16
 * Requires at least: 4.8
 * Requires PHP:      7.1
 * Tested up to:      7.0
 * Author:            WPExperts.io, Clever
 * Author URI:        https://github.com/Cleversupport
 * Update URI:        https://github.com/Cleversupport/clever-email-templates
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       email-templates
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MAILTPL_VERSION', '1.5.16' );
define( 'MAILTPL_PLUGIN_FILE', __FILE__ );
define( 'MAILTPL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MAILTPL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MAILTPL_PLUGIN_HOOK', basename( __DIR__ ) . '/' . basename( __FILE__ ) );
define( 'MAILTPL_WOOMAIL_PATH', realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR );

require_once MAILTPL_PLUGIN_DIR . 'includes/functions.php';
require_once MAILTPL_PLUGIN_DIR . 'class-mailtpl-woomail-composer.php';
require_once MAILTPL_PLUGIN_DIR . 'includes/class-mailtpl-plugin-check.php';
require_once MAILTPL_PLUGIN_DIR . 'includes/class-mailtpl-github-updater.php';

new Mailtpl_GitHub_Updater( MAILTPL_PLUGIN_FILE, MAILTPL_VERSION );

mailtpl_email_templates();

// @todo development filter after done remove this filter.
add_filter( 'mailtpl_woomail_is_dedicated_for_woocommerce_active', '__return_true' );
