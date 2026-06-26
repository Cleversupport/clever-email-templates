<?php
/**
 * This is a customized version of https://github.com/soderlind/class-customizer-range-value-control
 *
 * @package Email Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Mailtpl_Range_Value_Control' ) ) {
	/**
	 * Email templates range value control.
	 */
	class WP_Customize_Mailtpl_Range_Value_Control extends WP_Customize_Control {
		/**
		 * Control type.
		 *
		 * @var string $type
		 */
		public $type = 'range_value';

		/**
		 * Enqueue scripts/styles.
		 *
		 * @since 3.4.0
		 */
		public function enqueue() {
			wp_enqueue_script( 'customizer-range-value-control', MAILTPL_PLUGIN_URL . 'assets/js/controls/customizer-range-value-control.js', array( 'jquery' ), MAILTPL_VERSION, true );
			wp_enqueue_style( 'customizer-range-value-control', MAILTPL_PLUGIN_URL . 'assets/css/controls/customizer-range-value-control.css', array(), MAILTPL_VERSION );
		}

		/**
		 * Render the control's content.
		 *
		 * @author wpexperts
		 * @version 1.2.0
		 */
		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<div class="range-slider"  style="width:100%; display:flex;flex-direction: row;justify-content: flex-start;">
					<span  style="width:100%; flex: 1 0 0; vertical-align: middle;"><input class="range-slider__range" type="range" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->input_attrs(); ?> <?php $this->link(); ?>>
					<span class="range-slider__value">0</span></span>
				</div>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_attr( $this->description ); ?></span>
				<?php endif; ?>
			</label>
			<?php
		}
	}
}
