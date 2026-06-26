<?php
/**
 * Class Email templates range control
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Range_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Class Mailtpl_Range_Control
	 */
	class Mailtpl_Range_Control extends WP_Customize_Control {
		/**
		 * Email template control type
		 *
		 * @var string $type
		 */
		public $type = 'mailtpl-range-control';

		/**
		 * Input attrs
		 *
		 * @var array $input_attrs
		 */
		public $input_attrs = array();

		/**
		 * WP Enqueue scripts.
		 */
		public function enqueue() {
			wp_enqueue_script(
				'mailtpl-customizer-range-control',
				MAILTPL_PLUGIN_URL . 'assets/js/controls/customizer-range-control.js',
				array( 'customize-controls' ),
				MAILTPL_VERSION,
				true,
			);
		}

		/**
		 * Converting array to JSON api
		 */
		public function to_json() {
			parent::to_json();
			$this->json['input_attrs']['min'] = absint( $this->input_attrs['min'] );
			$this->json['input_attrs']['max'] = absint( $this->input_attrs['max'] );
			if ( isset( $this->input_attrs['step'] ) ) {
				$step = $this->input_attrs['step'];
			} else {
				$step = true;
			}
			$this->json['input_attrs']['step'] = absint( $step );
			$this->json['value']               = $this->value();
			$this->json['link']                = $this->get_link();
			$this->json['id']                  = $this->id;
		}

		/**
		 * Overriding render content.
		 */
		protected function render_content() {}

		/**
		 * Content template.
		 */
		protected function content_template() {
			?>
			<label for="range-{{ data.id }}">
				<span class="customize-control-title">
					{{ data.label }}
				</span>
				<div class="range-slider-wrapper">
					<span class="range-slider-value">
						<span>{{ data.value }}</span>
					</span>
					<input 
						type="range" 
						data-mailtpl-type="range" 
						min="{{ data.input_attrs.min }}" 
						max="{{ data.input_attrs.max }}" 
						step="{{ data.input_attrs.step }}" 
						value="{{ data.value }}" 
						class="mailtpl-range" 
						id="range-{{ data.id }}" 
					/>
					<input 
						type="number" 
						data-mailtpl-type="number" 
						min="{{ data.input_attrs.min }}" 
						max="{{ data.input_attrs.max }}" 
						step="{{ data.input_attrs.step }}" 
						value="{{ data.value }}" 
						class="mailtpl-number" 
						style="width: 60px; margin-left: 10px;" 
					/>
				</div>
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
