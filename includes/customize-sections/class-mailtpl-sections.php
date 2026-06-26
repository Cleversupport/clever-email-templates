<?php
/**
 * Class Email Templates sections
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Sections' ) || class_exists( 'WP_Customize_Section' ) ) {
	/**
	 * Class Email templates Section.
	 */
	class Mailtpl_Sections extends WP_Customize_Section {
		/**
		 * Section object
		 *
		 * @var mixed $section;
		 */
		public $section;

		/**
		 * Section type.
		 *
		 * @var string $type
		 */
		public $type = 'mailtpl-section';

		/**
		 * Converting array to JSON.
		 *
		 * @return array
		 */
		public function json() {
			$array                   = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section' ) );
			$array['title']          = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content']        = $this->get_content();
			$array['active']         = $this->active();
			$array['instanceNumber'] = $this->instance_number;

			if ( $this->panel ) {
				$array['customizeAction'] = sprintf(
					// Translators: %1$s for section title.
					esc_attr__( 'Customizing &#9656; %1$s', 'email-templates' ),
					esc_html( $this->manager->get_panel( $this->panel )->title )
				);
			} else {
				$array['customizeAction'] = 'Customizing';
			}
			return $array;
		}
	}
}
