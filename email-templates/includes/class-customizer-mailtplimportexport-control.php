<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
	exit;
}
/**
 * This is a customized version of https://wordpress.org/plugins/customizer-export-import/
 */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Kwdimportexport_Control' ) ) {
	class WP_Customize_Kwdimportexport_Control extends WP_Customize_Control {
		public $type = 'kwdimportexport';

		public function render_content() {
			?>
			<span class="customize-control-title">
				<?php _e( 'Export', 'email-templates' ); ?>
			</span>
			<span class="description customize-control-description">
				<?php _e( 'Click the button below to export the customization settings for this plugin.', 'email-templates' ); ?>
			</span>
			<input type="button" class="button button-primary mailtpl-woomail-export mailtpl-woomail-button" name="mailtpl-woomail-export-button" value="<?php esc_attr_e( 'Export', 'email-templates' ); ?>" />

			<hr class="mailtpl-woomail-hr" />

			<span class="customize-control-title">
				<?php _e( 'Import', 'email-templates' ); ?>
			</span>
			<span class="description customize-control-description">
				<?php _e( 'Upload a file to import customization settings for this plugin.', 'email-templates' ); ?>
			</span>
			<div class="mailtpl-woomail-import-controls">
				<input type="file" name="mailtpl-woomail-import-file" class="mailtpl-woomail-import-file" />
				<?php wp_nonce_field( 'mailtpl-woomail-importing', 'mailtpl-woomail-import' ); ?>
			</div>
			<div class="mailtpl-woomail-uploading"><?php _e( 'Uploading...', 'email-templates' ); ?></div>
			<input type="button" class="button button-primary mailtpl-woomail-import mailtpl-woomail-button" name="mailtpl-woomail-import-button" value="<?php esc_attr_e( 'Import', 'email-templates' ); ?>" />
			<?php
		}
	}
}