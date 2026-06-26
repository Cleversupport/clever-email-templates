<?php
/**
 * File: class-mailtpl-send-mail-customize-control.php
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Send_Mail_Customize_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Class WP_Send_Mail_Customize_Control
	 */
	class Mailtpl_Send_Mail_Customize_Control extends WP_Customize_Control {
		/**
		 * Control type
		 *
		 * @var string
		 */
		public $type = 'mailtpl-send-mail';

		/**
		 * Enqueue scripts.
		 */
		public function enqueue() {

			wp_enqueue_script( 'mailtpl-send-email-control', MAILTPL_PLUGIN_URL . 'assets/js/controls/send-email-control.js', array( 'customize-controls' ), MAILTPL_VERSION, true, );
			$l10n               = array(
				'_wpnonce' => wp_create_nonce( 'mailtpl-send-test-mail' ),
			);
			$l10n['email_type'] = 'wordpress_standard_email';
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'open-email-template' ) ) {
				if ( isset( $_GET['email_type'] ) ) {
					$l10n['email_type'] = sanitize_text_field( wp_unslash( $_GET['email_type'] ) );
					if ( 'wordpress_standard_email' !== $_GET['email_type'] ) {
						if ( isset( $_GET['preview_order'] ) ) {
							$l10n['preview_order'] = sanitize_text_field( wp_unslash( $_GET['preview_order'] ) );
						}
					}
				}
			}
			wp_localize_script(
				'mailtpl-send-email-control',
				'mailtpl_sendemail_object',
				$l10n
			);
		}

		/**
		 * Convert array to JSON Api.
		 */
		public function to_json() {
			parent::to_json();

			$this->json['id']           = $this->id;
			$this->json['label']        = esc_attr__( 'Send Email', 'email-templates' );
			$this->json['email_sanded'] = esc_attr__( 'Email has been sent !', 'email-templates' );
			$this->json['loader_icon']  = esc_url( admin_url( 'images/spinner.gif' ) );
		}

		/**
		 * Render the control's content.
		 */
		public function render_content() {}

		/**
		 * Content template
		 */
		public function content_template() {
			?>
			<label for="send-mail-{{ data.id }}">
				<button class="button button-primary" data-mailtpl-type="send-email" id="send-mail-{{ data.id }}" tabindex="0">
					{{ data.label }}
				</button>
				<img id="mailtpl-spinner" src="{{ data.loader_icon }}" alt="{{ data.loader_icon }}" style="display:none;"/>
				<span id="mailtpl-success" style="display:none;">
					{{ data.email_sanded }}
				</span>
				<# if ( data.description ) { #>
					<p>
						<span class="description customize-control-description">
							{{ data.description }}
						</span>
					</p>
				<# } #>
			</label>
			<?php
		}
	}
}
