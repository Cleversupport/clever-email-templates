<?php
/**
 * Class Email Templates template load control.
 *
 * @package Email Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Mailtpl_Template_Load_Control' ) ) {
	/**
	 * Class Email templates template load control
	 */
	class WP_Customize_Mailtpl_Template_Load_Control extends WP_Customize_Control {
		/**
		 * Control type
		 *
		 * @var string $type
		 */
		public $type = 'mailtpl_template_load';

		/**
		 * Render content.
		 */
		public function render_content() {

			$name = 'mailtpl-woomail-prebuilt-template';
			?>

			<div style="padding-bottom: 20px;">
				<span style="color:#0e9cd1"><strong>NEW!</strong></span>
				<h2 style="margin-top:0; padding: 5px 0;">Free Fluid Template</h2>
				Download Here
			</div>

			<span class="customize-control-title">
				<?php esc_attr_e( 'Load Template', 'email-templates' ); ?>
			</span>

			<div class="mailtpl-template-woomail-load-controls">
				<div id="input_<?php echo esc_attr( $this->id ); ?>" class="image-radio-select">
				<?php foreach ( $this->choices as $value => $label ) : ?>
					<label class="<?php echo esc_attr( $this->id ) . esc_attr( $value ); ?> image-radio-select-item" data-image-value="<?php echo esc_attr( $value ); ?>">
						<img src="<?php echo esc_url( MAILTPL_PLUGIN_URL . $label ); ?>" alt="<?php echo esc_attr( $value ); ?>" title="<?php echo esc_attr( $value ); ?>">
					</label>
				<?php endforeach; ?>
				</div>
				<input type="hidden" value="<?php echo esc_attr( $this->value() ); ?>" id="mailtpl-woomail-prebuilt-template" name="mailtpl-woomail-prebuilt-template">
				<?php wp_nonce_field( 'mailtpl-woomail-importing-template', 'mailtpl-woomail-import-template' ); ?>
			</div>

			<div class="mailtpl-woomail-loading"><?php esc_attr_e( 'Loading and Saving...', 'email-templates' ); ?></div>

			<input type="button" class="button button-primary mailtpl-woomail-button" name="mailtpl-woomail-template-button" value="<?php esc_attr_e( 'Load Template', 'email-templates' ); ?>" />
			<?php
		}
	}
}
