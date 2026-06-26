<?php
/**
 * Class Email Templates Info Block
 *
 * @package Email Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Mailtpl_Info_Block_Control' ) ) {
	/**
	 * Class Email Templates info block control
	 */
	class WP_Customize_Mailtpl_Info_Block_Control extends WP_Customize_Control {
		/**
		 * Control type
		 *
		 * @var string
		 */
		public $type = 'mailtpl_info_block';

		/**
		 * Render content
		 */
		public function render_content() {
			?>
			<label>
				<h3 class="customize-control-title test"><?php echo esc_html( $this->label ); ?></h3>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_attr( $this->description ); ?></span>
				<?php endif; ?>
			</label>
			<?php
		}
	}
}
