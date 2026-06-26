<?php
/**
 * File: class-mailtpl-toggle-control.php
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Toggle_Switch_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Class Email Templates Toggle Control
	 */
	class Mailtpl_Toggle_Switch_Control extends WP_Customize_Control {
		/**
		 * Email templates toggle control
		 *
		 * @var string
		 */
		public $type = 'mailtpl-toggle-switch-control';

		/**
		 * Enqueue scripts
		 */
		public function enqueue() {
			wp_enqueue_style( 'mailtpl-toggle-switch-control', MAILTPL_PLUGIN_URL . 'assets/css/controls/customizer-toggle-switch-control.css', array(), MAILTPL_VERSION, 'all' );
			wp_enqueue_script( 'mailtpl-toggle-switch-control-js-', MAILTPL_PLUGIN_URL . 'assets/js/controls/toggle-switch.js', array( 'customize-controls' ), MAILTPL_VERSION, true );
	
		// 	$css = '
		// 	.disabled-control-title {
		// 		color: #a0a5aa;
		// 	}
		// 	input[type=checkbox].tgl-light:checked + .tgl-btn {
		// 		background: #0085ba;
		// 	}
		// 	input[type=checkbox].tgl-light + .tgl-btn {
		// 	  background: #a0a5aa;
		// 	}
		// 	input[type=checkbox].tgl-light + .tgl-btn:after {
		// 	  background: #f7f7f7;
		// 	}

		// 	input[type=checkbox].tgl-ios:checked + .tgl-btn {
		// 	  background: #0085ba;
		// 	}

		// 	input[type=checkbox].tgl-flat:checked + .tgl-btn {
		// 	  border: 4px solid #0085ba;
		// 	}
		// 	input[type=checkbox].tgl-flat:checked + .tgl-btn:after {
		// 	  background: #0085ba;
		// 	}

		// ';
		// wp_add_inline_style( 'customizer-toggle-switch-control-css', $css );
		
		}

		

		/**
		 * Converting array to JSON Api
		 */
		public function to_json() {
			parent::to_json();

			$this->json['id']          = $this->id;
			$this->json['label']       = $this->label;
			$this->json['description'] = $this->description;
		}

		/**
		 * Overriding render content.
		 */
		public function render_content() {  }



		public function content_template() {
			

			?>
			<label>
				<div style="display:flex;flex-direction: row;justify-content: flex-start; align-items: center;">
				<span class="customize-control-title">{{ data.label }}</span>
				
					<input id="cb<?php echo esc_attr( $this->instance_number ); ?>" type="checkbox" class="customToggle mailtpl-toggle-light tgl tgl-light" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> checked="<?php checked( $this->value() ); ?>"/>
					<label for="cb<?php echo esc_attr( $this->instance_number ); ?>" class="tgl-btn"></label>
					
						<span class="description customize-control-description">{{ data.description }}</span>
				
				
				</div>
				
			</label>

			<?php

					}







		/**
		 * Content template.
		 */
		/*





		public function content_template() {
			?>
			<label>
				<div style="display: flex; justify-content: space-between; align-items: center;">
					<span class="customize-control-title">{{ data.label }}</span>
					<input type="checkbox" id="{{ data.id }}" class="mailtpl-toggle mailtpl-toggle-light" 
						value="1" {{ data.value ? 'checked' : '' }} />
					<label for="{{ data.id }}" class="mailtpl-toggle-btn"></label>
					<# if ( data.description ) { #>
						<span class="description customize-control-description">{{ data.description }}</span>
					<# } #>
				</div>
			</label>
			<?php
		}
		*/

	}
}
