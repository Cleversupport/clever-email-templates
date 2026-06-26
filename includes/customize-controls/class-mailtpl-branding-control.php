<?php
/**
 * Mail Template Branding Control.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Branding_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Class Mail Template Branding Control.
	 */
	class Mailtpl_Branding_Control extends WP_Customize_Control {
		/**
		 * Control type.
		 *
		 * @var string $type
		 */
		public $type = 'mailtpl-branding-control';

        /**
         * Media.
         *
		 * @var array $media Media.
		 */
        public $media = array();

		/**
         * Buttons.
         *
		 * @var array $buttons Buttons.
		 */
        public $buttons = array();

		/**
		 * Mailtpl_Branding_Control constructor.
		 */
		public function to_json() {
			parent::to_json();
			$this->json['id']           = $this->id;
            $this->json['label']        = $this->label;
            $this->json['description']  = $this->description;

            foreach ( $this->media as $key => $media ) {
                $this->json['media'][$key] = $media;
            }
            foreach ( $this->buttons as $key => $button ) {
                $this->json['buttons'][$key] = $button;
            }
		}

        protected function render_content() {}

		/**
		 * Render control.
		 */
		protected function content_template() {
			?>
            <li id="customize-control-{{ data.id }}" class="customize-control customize-control-{{ data.control_type }}">
                <# if ( data.label ) { #>
                    <span class="customize-control-title">
                        <span>{{{ data.label }}}</span>
                    </span>
                <# } #>

                <# if ( data.media.youtube ) { #>
                <iframe width="{{ data.media.youtube.width }}" height="{{ data.media.youtube.height }}" src="{{ data.media.youtube.src }}" title="{{ data.media.youtube.title }}" frameborder="{{ data.media.youtube.frameborder }}" allow="{{ data.media.youtube.allow }}" {{ data.media.youtube.allowfullscreen ? 'allowfullscreen' : '' }} ></iframe>
                <# } #>

                <# if ( data.description ) { #>
                    <span class="description customize-control-description">{{{ data.description }}}</span>
                <# } #>

                <# if ( data.buttons.learn_more ) { #>
                    <a style="width: 100px; height: 40px; display: block; background: blueviolet; text-align: center; color: #fff; line-height: 40px;" href="{{ data.buttons.learn_more.link }}" target="_blank">{{ data.buttons.learn_more.title }}</a>
                <# } #>
            </li>
            <?php
		}
	}
}
