<?php
/**
 * Class Email Templates send email control.
 *
 * @package Email Templates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Mailtpl_Send_Email_Control' ) ) {
	/**
	 * Email templates send email control.
	 */
	class WP_Customize_Mailtpl_Send_Email_Control extends WP_Customize_Control {
		/**
		 * Control type
		 *
		 * @var string $type
		 */
		public $type = 'mailtpl_send_email';

		/**
		 * Render content.
		 */
		public function render_content() {
			?>
			<span class="customize-control-title">
				<?php echo esc_html( $this->label ); ?>
			</span>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_attr( $this->description ); ?></span>
			<?php endif; ?>
			<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" id="_customize-input-<?php echo esc_attr( $this->id ); ?>" <?php $this->input_attrs(); ?> <?php $this->link(); ?>>
			<div style="padding: 10px;"><?php esc_attr_e( 'Settings must be saved to send preview email.', 'email-templates' ); ?></div>
			<input type="button" class="button button-primary mailtpl-woomail-button" name="mailtpl-woomail-send-email" value="<?php esc_attr_e( 'Send Email', 'email-templates' ); ?>" />
			<div style="padding: 10px;"><?php esc_attr_e( 'Some emails will not work correctly with the mockup order. It is best to use a real order for sending preview emails.', 'email-templates' ); ?></div>
			<?php
		}
	}
}
