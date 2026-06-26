<?php
/**
 * Class Email Templates Panels
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Panels' ) && class_exists( 'WP_Customize_Panel' ) ) {
	/**
	 * Class Email templates panels
	 */
	class Mailtpl_Panels extends WP_Customize_Panel {
		/**
		 * Panel object.
		 *
		 * @var mixed $panel
		 */
		public $panel;

		/**
		 * Feature tag new|pro|anything else.
		 *
		 * @var string $tag
		 */
		public $tag = '';

		/**
		 * Panel type
		 *
		 * @var string $type
		 */
		// public $type = 'mailtpl-panel';

		/**
		 * Converting array to JSON.
		 *
		 * @return array
		 */
		public function json() {
			$array                   = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel' ) );
			$array['title']          = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content']        = $this->get_content();
			$array['active']         = $this->active();
			$array['instanceNumber'] = $this->instance_number;
			$array['tag']            = $this->tag;
			return $array;
		}

		/**
		 * Render template.
		 */
		public function render_template() {
			?>
			<li id="accordion-panel-{{ data.id }}" class="accordion-section control-section control-panel control-panel-{{ data.type }}">
				<h3 class="accordion-section-title" tabindex="0">
					{{ data.title }}
					<span class="screen-reader-text"><?php esc_attr_e( 'Press return or enter to open this panel' ); ?></span>
				</h3>
				<ul class="accordion-sub-container control-panel-content"></ul>
			</li>
			<?php
		}

		/**
		 * Content template.
		 */
		public function content_template() {
			?>
			<li class="panel-meta customize-info accordion-section <# if ( ! data.description ) { #> cannot-expand<# } #>">
				<button class="customize-panel-back" tabindex="-1"><span class="screen-reader-text"><?php esc_attr_e( 'Back' ); ?></span></button>
				<div class="accordion-section-title">
				<span class="preview-notice">
				<?php
				// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
				printf(
					/* translators: %s: The site/panel title in the Customizer. */
					__( 'You are customizing %s' ),
					'<strong class="panel-title">{{ data.title }}</strong>'
				);
				// phpcs:enable
				?>
				</span>
					<# if ( data.description ) { #>
					<button type="button" class="customize-help-toggle dashicons dashicons-editor-help" aria-expanded="false"><span class="screen-reader-text"><?php esc_attr_e( 'Help' ); ?></span></button>
					<# } #>
				</div>
				<# if ( data.description ) { #>
				<div class="description customize-panel-description">
					{{{ data.description }}}
				</div>
				<# } #>

				<div class="customize-control-notifications-container"></div>
			</li>
			<?php
		}
	}
}
