<?php
/**
 * This is a customized version of https://wordpress.org/plugins/customizer-export-import/
 *
 * @package Email Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Mailtpl_Import_Export_Control' ) ) {
	/**
	 * Email Templates Import Export Control
	 */
	class WP_Customize_Mailtpl_Import_Export_Control extends WP_Customize_Control {
		/**
		 * Control Type
		 *
		 * @var string
		 */
		public $type = 'mailtpl_import_export';

		/**
		 * Render content.
		 */
		public function render_content() {
			?>
			<span class="customize-control-title">
				<?php esc_attr_e( 'Export', 'email-templates' ); ?>
			</span>
			<span class="description customize-control-description">
				<?php esc_attr_e( 'Click the button below to export the customization settings for this plugin.', 'email-templates' ); ?>
			</span>
			<input type="button" class="button button-primary mailtpl-woomail-export mailtpl-woomail-button" name="mailtpl-woomail-export-button" value="<?php esc_attr_e( 'Export', 'email-templates' ); ?>" />

			<hr class="mailtpl-woomail-hr" />

			<span class="customize-control-title">
				<?php esc_attr_e( 'Import', 'email-templates' ); ?>
			</span>
			<span class="description customize-control-description">
				<?php esc_attr_e( 'Upload a file to import customization settings for this plugin.', 'email-templates' ); ?>
			</span>
			<div class="mailtpl-woomail-import-controls">
				<input type="file" name="mailtpl-woomail-import-file" class="mailtpl-woomail-import-file" />
				<?php wp_nonce_field( 'mailtpl-woomail-importing', 'mailtpl-woomail-import' ); ?>
			</div>
			<div class="mailtpl-woomail-uploading"><?php esc_attr_e( 'Uploading...', 'email-templates' ); ?></div>
			<input type="button" class="button button-primary mailtpl-woomail-import mailtpl-woomail-button" name="mailtpl-woomail-import-button" value="<?php esc_attr_e( 'Import', 'email-templates' ); ?>" />
			<?php
		}
	}
}
