<?php
/**
 * Class Email templates woomail plugin check
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Plugin_Check' ) ) {
	/**
	 * Class Mailtpl_Woomail_Plugin_Check.
	 */
	class Mailtpl_Plugin_Check {
		/**
		 * Getting all active plugins.
		 *
		 * @var null $active_plugins
		 */
		private static $active_plugins;

		/**
		 * Class initialize.
		 */
		public static function init() {

			self::$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( is_multisite() ) {
				self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			}
			self::$active_plugins = apply_filters( 'active_plugins', self::$active_plugins );
		}

		/**
		 * Is WooCommerce is active.
		 *
		 * @param string $plugin_name Plugin name.
		 *
		 * @return bool
		 */
		public static function active_check( $plugin_name ) {

			if ( ! self::$active_plugins ) {
				self::init();
			}
			return in_array( $plugin_name, self::$active_plugins, true ) || array_key_exists( $plugin_name, self::$active_plugins );
		}
	}
}
