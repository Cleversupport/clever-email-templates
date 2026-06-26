<?php
/**
 * A class that extends WP_Customize_Setting so we can access
 * the protected updated method when importing options.
 *
 * @since 0.3
 *
 * @package Email Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mailtpl_Woomail_Import_Option' ) && class_exists( 'WP_Customize_Setting' ) ) {
	/**
	 * Class Mailtpl_Woomail_Import_Option
	 */
	class Mailtpl_Woomail_Import_Option extends WP_Customize_Setting {

		/**
		 * Import an option value for this setting.
		 *
		 * @param mixed $value The option value.
		 *
		 * @return void
		 * @since 0.3
		 */
		public function import( $value ) {
			$this->update( $value );
		}
	}
}
