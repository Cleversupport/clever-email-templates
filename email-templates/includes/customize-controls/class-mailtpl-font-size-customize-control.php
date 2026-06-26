<?php
/**
 * File: fontsize customize control.php
 *
 * @deprecated class-mailtpl-font-size-customize-control.php
 *
 * @package Email Templates
 */

_deprecated_file( __FILE__, '1.4.4', 'class-mailtpl-range-control.php', 'This file has been deprecated' );

if ( ! class_exists( 'Mailtpl_Font_Size_Customize_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * WP_Font_Size_Customize_Control.
	 */
	class Mailtpl_Font_Size_Customize_Control extends WP_Customize_Control {
		/**
		 * Control Type.
		 *
		 * @var string $type
		 */
		public $type = 'mailtpl_font_size';

		/**
		 * Input attrs.
		 *
		 * @var array $input_attrs
		 */
		public $input_attrs = array();

		/**
		 * Converting array to JSON api.
		 */
		public function to_json() {
			parent::to_json();

			$this->json['input_attr']['min'] = $this->input_attrs['min'];
			$this->json['input_attr']['max'] = $this->input_attrs['max'];
		}

		/**
		 * Render the control's content.
		 */
		protected function render_content() {
			$range_min = '1';
			$range_max = '100';
			if ( 'mailtpl_body_size' === $this->id ) {
				$range_min = '320';
				$range_max = '1280';
			}
			?>
			<label>
				<span class="customize-control-title">
				<?php echo esc_attr( $this->label ); ?>
				</span>
				<div class="font_value"><?php echo esc_attr( $this->value() ); ?></div>
				<input <?php $this->link(); ?> type="range" min="<?php echo absint( $range_min ); ?>" max="<?php echo absint( $range_max ); ?>" step="1" value="<?php echo esc_attr( $this->value() ); ?>" class="mailtpl_range" />
				<?php if ( ! empty( $this->description ) ) : ?>
					<p><span class="description customize-control-description"><?php echo esc_attr( $this->description ); ?></span></p>
				<?php endif; ?>
			</label>
			<?php
		}

		/**
		 * Rendering content template.
		 */
		public function content_template() {}
	}
}
