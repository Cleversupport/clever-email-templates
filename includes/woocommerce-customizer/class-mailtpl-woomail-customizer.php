<?php
/**
 * Customizer class.
 *
 * @package Mailtpl WooCommerce Email Composer
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Setup
 *  Heavily borrowed from rightpress Decorator
 */
if ( ! class_exists( 'Mailtpl_Woomail_Customizer' ) ) {
	/**
	 * Class Mailtpl_Woomail_Customizer
	 */
	class Mailtpl_Woomail_Customizer {
		/**
		 * Panels Added
		 *
		 * @var array
		 */
		private static $panels_added = array();

		/**
		 * Sections added
		 *
		 * @var array
		 */
		private static $sections_added = array();

		/**
		 * Css suffixes
		 *
		 * @var null
		 */
		private static $css_suffixes = null;

		/**
		 * Customizer URL
		 *
		 * @var null
		 */
		public static $customizer_url = null;

		/**
		 * Instance
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Instance Control
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Class constructor
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			// Add support for third party emails.
			add_action( 'init', array( $this, 'add_third_party_emails' ), 5 );

			// Ajax handler.
			add_action( 'wp_ajax_mailtpl_woomail_send_email', array( $this, 'ajax_send_email' ) );

			// Only proceed if this is own request.
			if ( ! Mailtpl_Woomail_Composer::is_own_customizer_request() && ! Mailtpl_Woomail_Composer::is_own_preview_request() ) {
				return;
			}

			// Add user capability.
			add_filter( 'user_has_cap', array( $this, 'add_customize_capability' ), 99 );

			// Remove unrelated components.
			add_filter( 'customize_loaded_components', array( $this, 'remove_unrelated_components' ), 99, 2 );

			// Changes the publish text to save.
			add_filter( 'gettext', array( $this, 'change_publish_button' ), 10, 2 );

			// This filters in woocommerce edits that are not saved while the preview refreshes.
			add_action( 'init', array( $this, 'get_customizer_options_override_ready' ) );

			// WP-Multilang.
			add_filter( 'wpm_customizer_url', array( $this, 'force_fix_wp_multilang' ), 10 );

			// Unhook divi front end.
			add_action( 'woomail_footer', array( $this, 'unhook_divi' ), 10 );

			// Unhook LifterLMS front end.
			add_action( 'woomail_footer', array( $this, 'unhook_lifter' ), 10 );

			// Unhook Flatsome js.
			add_action( 'customize_preview_init', array( $this, 'unhook_flatsome' ), 50 );

		}
		/**
		 * Add Emails into the previewer.
		 */
		public function add_third_party_emails() {
			/**
				Looking for Structure that looks like this:
				array(
					'email_type' => 'email_example_slug',
					'email_name' => 'Email Example',
					'email_class' => 'Custom_WC_Email_Extend',
					'email_heading' => __( 'Placeholder for Heading', 'plugin' ),
				);
			*/
			$add_email_previews = apply_filters( 'mailtpl_woocommerce_email_previews', array() );
			if ( ! empty( $add_email_previews ) && is_array( $add_email_previews ) ) {
				foreach ( $add_email_previews as $email_item ) {
					if ( isset( $email_item['email_type'] ) && ! empty( $email_item['email_type'] ) && isset( $email_item['email_name'] ) && ! empty( $email_item['email_name'] ) ) {
						add_filter(
							'mailtpl_woomail_email_types',
							function( $types ) use ( $email_item ) {
								$types[ $email_item['email_type'] ] = $email_item['email_name'];
								return $types;
							}
						);
					}
					if ( isset( $email_item['email_type'] ) && ! empty( $email_item['email_type'] ) && isset( $email_item['email_class'] ) && ! empty( $email_item['email_class'] ) ) {
						add_filter(
							'mailtpl_woomail_email_type_class_name_array',
							function( $types ) use ( $email_item ) {
								$types[ $email_item['email_type'] ] = $email_item['email_class'];
								return $types;
							}
						);
					}
					if ( isset( $email_item['email_type'] ) && ! empty( $email_item['email_type'] ) && isset( $email_item['email_heading'] ) && ! empty( $email_item['email_heading'] ) ) {
						add_filter(
							'mailtpl_woomail_email_settings_default_values',
							function( $placeholders ) use ( $email_item ) {
								$placeholders[ $email_item['email_type'] . '_heading' ] = $email_item['email_heading'];
								return $placeholders;
							}
						);
					}
				}
			}
		}

		/**
		 * Unhook Divi front end.
		 *
		 * @param string $url the customizer url.
		 */
		public function force_fix_wp_multilang( $url ) {
			return add_query_arg( array( 'mailtpl-woomail-preview' => '1' ), home_url( '/' ) );
		}
		/**
		 * Unhook flatsome front end.
		 */
		public function unhook_flatsome() {
			// Unhook flatsome issue.
			wp_dequeue_style( 'flatsome-customizer-preview' );
			wp_dequeue_script( 'flatsome-customizer-frontend-js' );
		}
		/**
		 * Unhook lifter front end.
		 */
		public function unhook_lifter() {
			// Unhook LLMs issue.
			wp_dequeue_script( 'llms' );
		}
		/**
		 * Unhook Divi front end.
		 */
		public function unhook_divi() {
			// Divi Theme issue.
			remove_action( 'wp_footer', 'et_builder_get_modules_js_data' );
			remove_action( 'et_customizer_footer_preview', 'et_load_social_icons' );
		}

		/**
		 * Get customizer Options
		 */
		public function get_customizer_options_override_ready() {
			foreach ( Mailtpl_Woomail_Settings::get_email_types() as $key => $value ) {
				add_filter( 'option_woocommerce_' . $key . '_settings', array( $this, 'customizer_woo_options_override' ), 99, 2 );
			}
		}

		/**
		 * Customizer Woo Options
		 *
		 * @param array  $value Value.
		 * @param string $option Options.
		 */
		public function customizer_woo_options_override( $value = array(), $option = '' ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( isset( $_POST['customized'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				$post_values = json_decode( stripslashes_deep( sanitize_text_field( wp_unslash( $_POST['customized'] ) ) ), true );
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				if ( isset( $_POST['customized'] ) && ! empty( $post_values ) ) {
					if ( is_array( $post_values ) ) {
						foreach ( $post_values as $key => $current_value ) {
							if ( strpos( $key, $option ) !== false ) {
								$subkey           = str_replace( $option, '', $key );
								$subkey           = str_replace( '[', '', rtrim( $subkey, ']' ) );
								$value[ $subkey ] = $current_value;
							}
						}
					}
				}
			}
			return $value;
		}

		/**
		 * Change publish button text.
		 *
		 * @param string $translation Translated text.
		 * @param string $text Default text.
		 *
		 * @return string
		 */
		public function change_publish_button( $translation, $text ) {

			if ( 'Publish' === $text ) {
				return __( 'Save', 'email-templates' );
			} elseif ( 'Published' === $text ) {
				return __( 'Saved', 'email-templates' );
			}

			return $translation;
		}

		/**
		 * Add customizer capability
		 *
		 * @access public
		 *
		 * @param array $capabilities Capabilities.
		 *
		 * @return array
		 */
		public function add_customize_capability( $capabilities ) {
			// Remove filter (circular reference).
			remove_filter( 'user_has_cap', array( $this, 'add_customize_capability' ), 99 );

			// Add customize capability for admin user if this is own customizer request.
			if ( Mailtpl_Woomail_Composer::is_admin() && Mailtpl_Woomail_Composer::is_own_customizer_request() ) {
				$capabilities['customize'] = true;
			}

			// Add filter.
			add_filter( 'user_has_cap', array( $this, 'add_customize_capability' ), 99 );

			// Return capabilities.
			return $capabilities;
		}

		/**
		 * Get Customizer URL
		 */
		public static function get_customizer_url() {
			if ( is_null( self::$customizer_url ) ) {
				self::$customizer_url = add_query_arg(
					array(
						'mailtpl-woomail-customize' => '1',
						'url'                       => rawurlencode( add_query_arg( array( 'mailtpl-woomail-preview' => '1' ), home_url( '/' ) ) ),
						'return'                    => rawurlencode( Mailtpl_Woomail_Woo::get_email_settings_page_url() ),
					),
					admin_url( 'customize.php' )
				);
			}

			return self::$customizer_url;
		}

		/**
		 * Change site name for customizer
		 *
		 * @param string $name Name of site.
		 *
		 * @return string
		 */
		public function change_site_name( $name ) {
			return __( 'WooCommerce Emails', 'email-templates' );
		}

		/**
		 * Remove unrelated components
		 *
		 * @access public
		 * @param array  $components Components.
		 * @param object $wp_customize WP Customizer.
		 *
		 * @return array
		 */
		public function remove_unrelated_components( $components, $wp_customize ) {
			// Iterate over components.
			foreach ( $components as $component_key => $component ) {

				// Check if current component is own component.
				if ( ! self::is_own_component( $component ) ) {
					unset( $components[ $component_key ] );
				}
			}

			// Return remaining components.
			return $components;
		}

		/**
		 * Check if current component is own component
		 *
		 * @access public
		 * @param string $component Component.
		 * @return bool
		 */
		public static function is_own_component( $component ) {
			return false;
		}

		/**
		 * Enqueue Customizer scripts
		 *
		 * @access public
		 * @return void
		 */
		public function enqueue_customizer_scripts() {
			// Enqueue Customizer script.
			wp_enqueue_style( 'mailtpl-woomail-customizer-styles', MAILTPL_PLUGIN_URL . '/assets/css/customizer-styles.css', array(), MAILTPL_VERSION );
			wp_enqueue_script( 'mailtpl-woomail-customizer-scripts', MAILTPL_PLUGIN_URL . '/assets/js/customizer-scripts.js', array( 'jquery', 'customize-controls' ), MAILTPL_VERSION, true );

			// Send variables to Javascript.
			wp_localize_script(
				'mailtpl-woomail-customizer-scripts',
				'mailtpl_woomail',
				array(
					'ajax_url'        => admin_url( 'admin-ajax.php' ),
					'customizer_url'  => self::get_customizer_url(),
					'responsive_mode' => self::opt( 'responsive_mode' ),
					'labels'          => array(
						'reset'              => __( 'Reset', 'email-templates' ),
						'customtitle'        => __( 'Woocommerce Emails', 'email-templates' ),
						'send_confirmation'  => __( 'Are you sure you want to send an email?', 'email-templates' ),
						'sent'               => __( 'Email Sent!', 'email-templates' ),
						'failed'             => __( 'Email failed, make sure you have a working email server for your site.', 'email-templates' ),
						'reset_confirmation' => __( 'Are you sure you want to reset all changes made to your WooCommerce emails?', 'email-templates' ),
					),
				)
			);
			// Localize.
			wp_localize_script(
				'mailtpl-woomail-customizer-scripts',
				'email_templates_l10n',
				array(
					'emptyImport'      => __( 'Please choose a file to import.', 'customizer-export-import' ),
					'confrim_override' => __( 'WARNING: This will override all of your current settings. Are you sure you want to do that? We suggest geting an export of your current settings incase you want to revert back.', 'customizer-export-import' ),
				)
			);

			// Config.
			wp_localize_script(
				'mailtpl-woomail-customizer-scripts',
				'email_templates_config',
				array(
					'customizerURL' => admin_url( 'customize.php?mailtpl-woomail-customize=1&url=' . rawurlencode( add_query_arg( array( 'mailtpl-woomail-preview' => '1' ), site_url( '/' ) ) ) ),
					'exportNonce'   => wp_create_nonce( 'mailtpl-woomail-exporting' ),
				)
			);
		}

		/**
		 * Get value for use in templates
		 *
		 * @access public
		 * @param string $key Object key.
		 * @param string $selector selector.
		 * @return string
		 */
		public static function opt( $key, $selector = null ) {
			// Get raw value.
			$stored_value = self::get_stored_value( $key, Mailtpl_Woomail_Settings::get_default_value( $key ) );

			// Prepare value.
			$value = self::prepare( $key, $stored_value, $selector );

			// Allow developers to override.
			return apply_filters( 'mailtpl_woomail_option_value', $value, $key, $selector, $stored_value );
		}

		/**
		 * Get value stored in database
		 *
		 * @access public
		 * @param string $key the setting key.
		 * @param string $default the setting defaut.
		 *
		 * @return string
		 */
		public static function get_stored_value( $key, $default = '' ) {
			// Get all stored values.
			$stored = (array) get_option( 'mailtpl_woomail', array() );

			// Check if value exists in stored values array.
			if ( ! empty( $stored ) && isset( $stored[ $key ] ) ) {
				return $stored[ $key ];
			}

			// Stored value not found, use default value.
			return $default;
		}

		/**
		 * Prepare value for use in HTML
		 *
		 * @param string $key Key.
		 * @param string $value Value.
		 * @param string $selector Selector.
		 *
		 * @return string
		 */
		public static function prepare( $key, $value, $selector = null ) {
			// Append CSS suffix to value.
			$value .= self::get_css_suffix( $key );

			// Special case for shadow.
			if ( 'shadow' === $key ) {
				$value = '0 ' . ( $value > 0 ? 1 : 0 ) . 'px ' . ( $value * 4 ) . 'px ' . $value . 'px rgba(0,0,0,0.1) !important';
			}

			// Special case for border width 0.
			if ( 'border_width_right' === $key && '0px' === $value ) {
				$background = get_option( 'woocommerce_email_background_color' );
				$value      = '0px solid ' . $background . ' !important';
			}
			if ( 'border_width_left' === $key && '0px' === $value ) {
				$background = get_option( 'woocommerce_email_background_color' );
				$value      = '0px solid ' . $background . ' !important';
			}
			if ( 'border_width_bottom' === $key && '0px' === $value ) {
				$background = get_option( 'woocommerce_email_background_color' );
				$value      = '0px solid ' . $background . ' !important';
			}
			if ( 'border_width' === $key && '0px' === $value ) {
				$background = get_option( 'woocommerce_email_background_color' );
				$value      = '0px solid ' . $background . ' !important';
			}

			// Font family.
			if ( substr( $key, -11 ) === 'font_family' ) {
				$value = isset( Mailtpl_Woomail_Settings::$font_family_mapping[ $value ] ) ? Mailtpl_Woomail_Settings::$font_family_mapping[ $value ] : $value;
			}

			// Return prepared value.
			return $value;
		}

		/**
		 * Get CSS suffix by key or all CSS suffixes
		 *
		 * @access public
		 * @param string $key Key.
		 * @return mixed
		 */
		public static function get_css_suffix( $key = null ) {
			// Define CSS suffixes.
			if ( null === self::$css_suffixes ) {
				self::$css_suffixes = array(
					'email_padding'                   => 'px',
					'email_padding_bottom'            => 'px',
					'content_padding_top'             => 'px',
					'content_padding_bottom'          => 'px',
					'content_padding'                 => 'px',

					'content_width'                   => 'px',
					'content_inner_width'             => 'px',
					'border_width'                    => 'px',
					'border_width_right'              => 'px',
					'border_width_bottom'             => 'px',
					'border_width_left'               => 'px',
					'border_radius'                   => 'px !important',

					'btn_border_width'                => 'px',
					'btn_size'                        => 'px',
					'btn_left_right_padding'          => 'px',
					'btn_top_bottom_padding'          => 'px',
					'btn_border_radius'               => 'px',

					'header_image_maxwidth'           => 'px',
					'header_image_padding_top_bottom' => 'px',

					'header_padding_top'              => 'px',
					'header_padding_bottom'           => 'px',
					'header_padding_left_right'       => 'px',
					'heading_font_size'               => 'px',
					'heading_line_height'             => 'px',
					'subtitle_font_size'              => 'px',
					'subtitle_line_height'            => 'px',

					'font_size'                       => 'px',
					'line_height'                     => 'px',

					'h2_font_size'                    => 'px',
					'h2_line_height'                  => 'px',
					'h2_separator_height'             => 'px',
					'h2_padding_top'                  => 'px',
					'h2_margin_bottom'                => 'px',
					'h2_padding_bottom'               => 'px',
					'h2_margin_top'                   => 'px',
					'h3_font_size'                    => 'px',
					'h3_line_height'                  => 'px',

					'addresses_border_width'          => 'px',
					'addresses_padding'               => 'px',

					'footer_top_padding'              => 'px',
					'footer_bottom_padding'           => 'px',
					'footer_left_right_padding'       => 'px',
					'footer_font_size'                => 'px',
					'footer_social_title_size'        => 'px',
					'footer_social_top_padding'       => 'px',
					'footer_social_bottom_padding'    => 'px',
					'footer_social_border_width'      => 'px',

					'footer_credit_top_padding'       => 'px',
					'footer_credit_bottom_padding'    => 'px',

					'items_table_border_width'        => 'px',
					'items_table_separator_width'     => 'px',
					'items_table_padding'             => 'px',
					'items_table_padding_left_right'  => 'px',
				);
			}

			// Return single suffix.
			if ( isset( $key ) ) {
				return isset( self::$css_suffixes[ $key ] ) ? self::$css_suffixes[ $key ] : '';
			} else {
				return self::$css_suffixes;
			}
		}

		/**
		 * Reset to default values via Ajax request
		 *
		 * @access public
		 * @return void
		 */
		public function ajax_send_email() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( empty( $_REQUEST['wp_customize'] ) || 'on' !== $_REQUEST['wp_customize'] || empty( $_REQUEST['action'] ) || 'mailtpl_woomail_send_email' !== $_REQUEST['action'] || empty( $_REQUEST['recipients'] ) ) {
				exit;
			}

			// Check if user is allowed to send email.
			if ( ! Mailtpl_Woomail_Composer::is_admin() ) {
				exit;
			}

			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$recipients = wc_clean( wp_unslash( $_REQUEST['recipients'] ) );
			// phpcs:enable
			$content = Mailtpl_Woomail_Preview::get_preview_email( true, $recipients );

			echo wp_kses_post( $content );
		}

		/**
		 * Get static styles
		 *
		 * @access public
		 * @return string
		 */
		public static function get_static_styles() {
			return '.order-items-light table.td .td {
	border: 0;
}
.order-items-light table.td {
	border: 0;
}
.order-items-light tr th.td {
	font-weight:bold;
}
.order-items-light tr .td {
	text-align:center !important;
}
.order-items-light tr .td:first-child, .order-items-light .order-info-split-table td:first-child {
	padding-' . ( is_rtl() ? 'right' : 'left' ) . ': 0 !important;
	text-align: ' . ( is_rtl() ? 'right' : 'left' ) . ' !important;
}
.order-items-light tr .td:last-child, .order-items-light .order-info-split-table td:last-child{
	padding-' . ( is_rtl() ? 'left' : 'right' ) . ': 0 !important;
	text-align:' . ( is_rtl() ? 'left' : 'right' ) . ' !important;
}
.title-style-behind  #template_container h2 {
	border-top:0 !important;
	border-bottom:0 !important;
}
.title-style-above #template_container h2 {
	border-bottom:0 !important;
}
.title-style-below #template_container h2 {
	border-top:0 !important;
}';
		}

	}

	Mailtpl_Woomail_Customizer::get_instance();

}
