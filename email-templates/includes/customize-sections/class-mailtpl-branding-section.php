<?php
/**
 * Mail Template Branding Section
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Branding_Section' ) && class_exists( 'WP_Customize_Section' ) ) {
	/**
	 * Class Mail Template Branding Section.
	 */
	class Mailtpl_Branding_Section extends WP_Customize_Section {
		/**
		 * Section type.
		 *
		 * @var string $type
		 */
		// public $type = '';

		// /**
        //  * Tag.
        //  *
		//  * @var string
		//  */
        // public $tag;

		/**
         * Converting array to json APi.
		 * @return array
		 */
        // public function json() {
        //     return array_merge(
        //         parent::json(),
        //         array(
        //             'tag' => $this->tag,
        //         )
        //     );
        // }

		/**
		 * Render section.
		 */
		protected function render_template() {
			?>
            <li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}">
                <h3 class="accordion-section-title" tabindex="0">
                    {{ data.title }}
                    <span style="background: blueviolet; padding: 10px; float: right; border-radius: 33px; width: 40px;  height: 10px; display: inline-block; line-height: 10px; font-size: 10px; text-align: center; color: #fff;">{{ data.tag }}</span>
                    <span style="display: table;clear: both;"></span>
                    <span class="screen-reader-text"><?php _e( 'Press return or enter to open this section' ); ?></span>
                </h3>
                <ul class="accordion-section-content">
                    <li class="customize-section-description-container section-meta <# if ( data.description_hidden ) { #>customize-info<# } #>">
                        <div class="customize-section-title">
                            <button class="customize-section-back" tabindex="-1">
                                <span class="screen-reader-text"><?php _e( 'Back' ); ?></span>
                            </button>
                            <h3>
							<span class="customize-action">
								{{{ data.customizeAction }}}
							</span>
                                {{ data.title }}
                                <span class="mailtpl-badge">{{ data.tag }}</span>
                                <!-- <span style="background: blueviolet; padding: 10px; float: right; border-radius: 33px; width: 40px;  height: 10px; display: inline-block; line-height: 10px; font-size: 10px; text-align: center; color: #fff;">{{ data.tag }}</span> -->
                                <span style="display: table;clear: both;"></span>
                            </h3>
                            <# if ( data.description && data.description_hidden ) { #>
                            <button type="button" class="customize-help-toggle dashicons dashicons-editor-help" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Help' ); ?></span></button>
                            <div class="description customize-section-description">
                                {{{ data.description }}}
                            </div>
                            <# } #>

                            <div class="customize-control-notifications-container"></div>
                        </div>

                        <# if ( data.description && ! data.description_hidden ) { #>
                        <div class="description customize-section-description">
                            {{{ data.description }}}
                        </div>
                        <# } #>
                    </li>
                </ul>
            </li>
			<?php
		}
	}
}
