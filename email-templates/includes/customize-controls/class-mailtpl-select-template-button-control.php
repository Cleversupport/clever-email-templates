<?php
/**
 * Email templates select template button
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Select_Template_Button_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Class Email templates select template button control
	 */
	class Mailtpl_Select_Template_Button_Control extends WP_Customize_Control {
		/**
		 * Email templates control type
		 *
		 * @var string $type
		 */
		public $type = 'mailtpl-select-template-button';

		/**
		 * Converting array to JSON api.
		 */
		public function to_json() {
			$selected_email_type    = 'mockup';
			$selected_preview_order = 'wordpress_standard_email';
			parent::to_json();

			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'open-email-template' ) ) {
				if ( isset( $_GET['email_type'] ) ) {
					$selected_email_type = sanitize_text_field( wp_unslash( $_GET['email_type'] ) );
				}
				if ( isset( $_GET['preview_order'] ) ) {
					$selected_preview_order = sanitize_text_field( wp_unslash( $_GET['preview_order'] ) );
				}
			}
			$this->json['id']          = $this->id;
			$this->json['link']        = $this->get_link();
			$this->json['value']       = $this->value();
			$this->json['button_text'] = esc_attr__( 'Open Template', 'email-templates' );
			$this->json['email_type']  = array(
				'label'       => esc_attr__( 'Email Type', 'email-templates' ),
				'description' => esc_html__( 'Select email type', 'email-templates' ),
				'choices'     => mailtpl_get_email_templates(),
				'selected'    => $selected_email_type,
			);
			if ( mailtpl_is_woocommerce_active() ) {
				$this->json['preview_order'] = array(
					'label'       => esc_attr__( 'Preview Order', 'email-templates' ),
					'description' => esc_html__( 'Select preview order', 'email-templates' ),
					'choices'     => Mailtpl_Woomail_Settings::get_order_ids(),
					'selected'    => $selected_preview_order,
				);
			}
		}

		/**
		 * Customizer button Enqueue scripts.
		 */
		public function enqueue() {
			wp_enqueue_script( 'mailtpl-select-template-button', MAILTPL_PLUGIN_URL . 'assets/js/controls/select-templates.js', array( 'customize-controls' ), MAILTPL_VERSION, true );
			wp_localize_script(
				'mailtpl-select-template-button',
				'mailtpl_select_template_button',
				array(
					'home_url'      => home_url( '/?mailtpl_display=true' ),
					'customize_url' => admin_url( '/customize.php?mailtpl_display=true' ),
					'_wpnonce'      => wp_create_nonce( 'open-email-template' ),
				)
			);
		}

		/**
		 * Overriding render content.
		 */
		public function render_content() {}

		/**
		 * Content template.
		 */
		public function content_template() {
			?>
			<div class="mailtpl--template-selection-container">

				<# if ( data.email_type ) { #>
					<label for="select-email-type--{{ data.id }}">
						<# if ( data.email_type.label ) { #>
						<span class="customize-control-title">{{ data.email_type.label }}</span>
						<# } #>
						<# if ( data.email_type.description ) { #>
						<p>
							<span class="description customize-control-description">
								{{ data.email_type.description }}
							</span>
						</p>
						<# } #>
						<select class="mailtpl-template-email-type" name="select-email-type--{{ data.id }}" id="select-email-type--{{ data.id }}">
							<# jQuery.each( data.email_type.choices, function( _k, v_ ) { #>
							<# selected = data.email_type.selected === _k ? 'selected': ''; #>
							<option {{ selected }} value="{{ _k }}">{{ v_ }}</option>
							<# } ); #>
						</select>
					</label>
				<# } #>

				<br>

				<# if ( data.preview_order ) { #>
					<label for="preview-order--{{ data.id }}">
						<# if ( data.preview_order.label ) { #>
							<span class="customize-control-title">{{ data.preview_order.label }}</span>
						<# } #>
						<# if ( data.preview_order.description ) { #>
						<p>
							<span class="description customize-control-description">
								{{ data.preview_order.description }}
							</span>
						</p>
						<# } #>
						<select class="mailtpl-template-preview-order" name="preview-order--{{ data.id }}" id="preview-order--{{ data.id }}">
							<# jQuery.each( data.preview_order.choices, function( _k, v_ ) { #>
								<# selected = data.preview_order.selected == _k ? 'selected': ''; #>
								<option {{ selected }} value="{{ _k }}">{{ v_ }}</option>
							<# } ); #>
						</select>
					</label>
				<# } #>

				<br>

				<label for="select-template--{{ data.id }}">
					<# if ( data.label ) { #>
						<span class="customize-control-title">{{ data.label }}</span>
					<# } #>
					<button id="select-template--{{ data.id }}" class="button button-primary mailtpl-open-template">{{ data.button_text }}</button>
					<# if ( data.description ) { #>
					<p>
						<span class="description customize-control-description">
							{{ data.description }}
						</span>
					</p>
					<# } #>
				</label>
			</div>
			<?php
		}
	}
}
