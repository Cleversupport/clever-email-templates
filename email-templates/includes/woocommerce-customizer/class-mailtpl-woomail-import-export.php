<?php
/**
 * Class mailtpl woomail import export.php
 *
 * @package Email Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mailtpl_Woomail_Import_Export' ) ) {
	/**
	 * Class Mailtpl_Woomail_Import_Export
	 */
	class Mailtpl_Woomail_Import_Export {
		/**
		 * Instance
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Woo core options.
		 *
		 * @var string[]
		 */
		private static $woo_core_options = array(
			'woocommerce_email_header_image',
			'woocommerce_email_footer_text',
			'woocommerce_email_body_background_color',
			'woocommerce_email_text_color',
			'woocommerce_email_background_color',
			'woocommerce_new_order_settings[heading]',
			'woocommerce_new_order_settings[subject]',

			'woocommerce_cancelled_order_settings[heading]',
			'woocommerce_customer_processing_order_settings[heading]',
			'woocommerce_customer_completed_order_settings[heading]',
			'woocommerce_customer_refunded_order_settings[heading_full]',
			'woocommerce_customer_refunded_order_settings[heading_partial]',

			'woocommerce_customer_on_hold_order_settings[heading]',
			'woocommerce_customer_invoice_settings[heading]',
			'woocommerce_customer_invoice_settings[heading_paid]',
			'woocommerce_failed_order_settings[heading]',
			'woocommerce_customer_new_account_settings[heading]',
			'woocommerce_customer_note_settings[heading]',
			'woocommerce_customer_reset_password_settings[heading]',

			'woocommerce_cancelled_order_settings[subject]',
			'woocommerce_customer_processing_order_settings[subject]',
			'woocommerce_customer_completed_order_settings[subject]',

			'woocommerce_customer_refunded_order_settings[subject_full]',
			'woocommerce_customer_refunded_order_settings[subject_partial]',

			'woocommerce_customer_on_hold_order_settings[subject]',

			'woocommerce_customer_invoice_settings[subject]',
			'woocommerce_customer_invoice_settings[subject_paid]',

			'woocommerce_failed_order_settings[subject]',
			'woocommerce_customer_new_account_settings[subject]',
			'woocommerce_customer_note_settings[subject]',
			'woocommerce_customer_reset_password_settings[subject]',
		);

		/**
		 * Prebuilt Options.
		 *
		 * @var array
		 */
		private static $prebuilt_options = array();

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

			// Only proceed if this is own request.
			if ( ! Mailtpl_Woomail_Composer::is_own_customizer_request() && ! Mailtpl_Woomail_Composer::is_own_preview_request() ) {
				return;
			}

			add_action( 'customize_register', array( $this, 'import_export_requests' ), 999999 );
			add_action( 'customize_controls_print_scripts', array( $this, 'controls_print_scripts' ) );

		}

		/**
		 * Check to see if we need to do an export or import.
		 *
		 * @param object $wp_customize An instance of WP_Customize_Manager.
		 * @return void
		 */
		public static function import_export_requests( $wp_customize ) {
			// Check if user is allowed to change values.
			if ( ! Mailtpl_Woomail_Composer::is_admin() ) {
				exit;
			}

//			phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_REQUEST['mailtpl-woomail-export'] ) ) {
				self::export_woomail( $wp_customize );
			}

//			phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_REQUEST['mailtpl-woomail-import'] ) && isset( $_FILES['mailtpl-woomail-import-file'] ) ) {
				self::import_woomail( $wp_customize );
			}

//			phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_REQUEST['mailtpl-woomail-import-template'] ) ) {
				self::import_woomail_template( $wp_customize );
			}

		}

		/**
		 * Export woomail settings.
		 *
		 * @access private
		 * @param object $wp_customize An instance of WP_Customize_Manager.
		 * @return void
		 */
		private static function export_woomail( $wp_customize ) {
			if ( ! isset( $_REQUEST['mailtpl-woomail-export'] ) ) {
				return;
			}
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mailtpl-woomail-export'] ) ), 'mailtpl-woomail-exporting' ) ) {
				return;
			}

			$template = 'mailtpl-woomail-Composer';
			$charset  = get_option( 'blog_charset' );
			$data     = array(
				'template' => $template,
				'options'  => array(),
			);

			// Get options from the Customizer API.
			$settings = $wp_customize->settings();

			foreach ( $settings as $key => $setting ) {
				if ( stristr( $key, 'mailtpl_woomail' ) || in_array( $key, self::$woo_core_options, true ) ) {
					// to prevent issues we don't want to export the order id.
					if ( 'mailtpl_woomail[preview_order_id]' !== $key ) {
						$data['options'][ $key ] = $setting->value();
					}
				}
			}

			// Set the download headers.
			header( 'Content-disposition: attachment; filename=mailtpl-woomail-Composer-export.dat' );
			header( 'Content-Type: application/octet-stream; charset=' . $charset );

			// Serialize the export data.
            // phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
            // phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			echo wp_kses_post( base64_encode( serialize( $data ) ) );
            // phpcs:enable

			// Start the download.
			die();
		}
		/**
		 * Imports uploaded Mailtpl woo email settings
		 *
		 * @access private
		 * @param object $wp_customize An instance of WP_Customize_Manager.
		 * @return void
		 */
		private static function import_woomail( $wp_customize ) {
			if ( ! isset( $_REQUEST['mailtpl-woomail-import'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mailtpl-woomail-import'] ) ), 'mailtpl-woomail-importing' ) ) {
				return;
			}
			// Make sure WordPress upload support is loaded.
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				include_once ABSPATH . 'wp-admin/includes/file.php';
			}

			// Load the export/import option class.
			include_once MAILTPL_WOOMAIL_PATH . 'includes/class-mailtpl-woomail-import-option.php';

			// Setup global vars.
			global $wp_customize;
			global $mailtpl_woomail_import_error;

			// Setup internal vars.
			$mailtpl_woomail_import_error = false;
			$template                     = 'mailtpl-woomail-Composer';
			$overrides                    = array(
				'test_form' => false,
				'test_type' => false,
				'mimes'     => array( 'dat' => 'text/plain' ),
			);

            // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotValidated
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$file = wp_handle_upload( $_FILES['mailtpl-woomail-import-file'], $overrides );
            // phpcs:enable

			// Make sure we have an uploaded file.
			if ( isset( $file['error'] ) ) {
				$mailtpl_woomail_import_error = $file['error'];
				return;
			}
			if ( ! file_exists( $file['file'] ) ) {
				$mailtpl_woomail_import_error = __( 'Error importing settings! Please try again.', 'email-templates' );
				return;
			}

			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$raw = file_get_contents( $file['file'] );

            // phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
            // phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
			$data = unserialize( base64_decode( $raw ) );
            // phpcs:enable
			if ( 'array' !== gettype( $data ) || ! isset( $data['template'] ) ) {
				$data = self::mb_unserialize( $raw );
			}
			// Remove the uploaded file.
			unlink( $file['file'] );

			// Data checks.
			if ( 'array' !== gettype( $data ) ) {
				$mailtpl_woomail_import_error = __( 'Error importing settings! Please check that you uploaded an email customizer export file.', 'email-templates' );
				return;
			}
			if ( ! isset( $data['template'] ) ) {
				$mailtpl_woomail_import_error = __( 'Error importing settings! Please check that you uploaded an email customizer export file.', 'email-templates' );
				return;
			}
			if ( $data['template'] !== $template ) {
				$mailtpl_woomail_import_error = __( 'Error importing settings! The settings you uploaded are not for the Mailtpl Woomail Composer.', 'email-templates' );
				return;
			}

			// Import custom options.
			if ( isset( $data['options'] ) ) {

				foreach ( $data['options'] as $option_key => $option_value ) {

					$option = new Mailtpl_Woomail_Import_Option(
						$wp_customize,
						$option_key,
						array(
							'default'    => '',
							'type'       => 'option',
							'capability' => Mailtpl_Woomail_Composer::get_admin_capability(),
						)
					);

					$option->import( $option_value );
				}
			}

			// Call the customize_save action.
			do_action( 'customize_save', $wp_customize );

			// Call the customize_save_after action.
			do_action( 'customize_save_after', $wp_customize );

			wp_safe_redirect( Mailtpl_Woomail_Customizer::get_customizer_url() );

			exit;
		}
		/**
		 * Mulit-byte Unserialize
		 *
		 * UTF-8 will screw up a serialized string
		 *
		 * @access private
		 * @param string $string string.
		 *
		 * @return string
		 */
		private static function mb_unserialize( $string ) {
			$string2 = preg_replace_callback(
				'!s:(\d+):"(.*?)";!s',
				function( $m ) {
					$len    = strlen( $m[2] );
					$result = "s:$len:\"{$m[2]}\";";
					return $result;

				},
				$string
			);
            // phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
            // phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
			return @unserialize( $string2 );
            // phpcs:enable
		}

		/**
		 * Imports prebuilt Mailtpl woo email settings
		 *
		 * @access private
		 * @param object $wp_customize An instance of WP_Customize_Manager.
		 * @return void
		 */
		private static function import_woomail_template( $wp_customize ) {
			if ( ! isset( $_REQUEST['mailtpl-woomail-import-template'] ) ) {
				return;
			}
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mailtpl-woomail-import-template'] ) ), 'mailtpl-woomail-importing-template' ) ) {
				return;
			}
			// Load the export/import option class.
			include_once MAILTPL_WOOMAIL_PATH . 'includes/class-mailtpl-woomail-import-option.php';

			// Setup global vars.
			global $wp_customize;
			global $mailtpl_woomail_import_error;

			// Setup internal vars.
			$mailtpl_woomail_import_error = false;
			$template                     = 'mailtpl-woomail-Composer';

            // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$prebuilt = sanitize_text_field( wp_unslash( $_REQUEST['mailtpl-woomail-prebuilt-template'] ) );
            // phpcs:enable

			$raw_data = self::prebuilt( $prebuilt );

            // phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
            // phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
			$data = @unserialize( $raw_data );
            // phpcs:enable

			// Data checks.
			if ( 'array' !== gettype( $data ) ) {
				$mailtpl_woomail_import_error = __( 'Error importing settings! The template you selected is not found.', 'email-templates' );
				return;
			}
			if ( ! isset( $data['template'] ) ) {
				$mailtpl_woomail_import_error = __( 'Error importing settings! The template you selected is not valid.', 'email-templates' );
				return;
			}
			if ( $data['template'] !== $template ) {
				$mailtpl_woomail_import_error = __( 'Error importing settings! The template you selected is not valid.', 'email-templates' );
				return;
			}

			// Import custom options.
			if ( isset( $data['options'] ) ) {

				foreach ( $data['options'] as $option_key => $option_value ) {

					$option = new Mailtpl_Woomail_Import_Option(
						$wp_customize,
						$option_key,
						array(
							'default'    => '',
							'type'       => 'option',
							'capability' => Mailtpl_Woomail_Composer::get_admin_capability(),
						)
					);

					$option->import( $option_value );
				}
			}

			// Call the customize_save action.
			do_action( 'customize_save', $wp_customize );

			// Call the customize_save_after action.

			wp_safe_redirect( Mailtpl_Woomail_Customizer::get_customizer_url() );

			exit;
		}
		/**
		 * Prints error scripts for the control.
		 *
		 * @since 0.1
		 * @return void
		 */
		public static function controls_print_scripts() {
			global $mailtpl_woomail_import_error;

			if ( $mailtpl_woomail_import_error ) {
				?>
				<script> alert("' . $mailtpl_woomail_import_error . '"); </script>
				<?php
			}
		}
		/**
		 * Get value for prebuilt
		 *
		 * @access public
		 * @param string $key Prebuilt value key.
		 * @return string
		 */
		public static function prebuilt( $key ) {
			if ( isset( self::$prebuilt_options[ $key ] ) ) {
				$data = self::$prebuilt_options[ $key ];
			} else {
				$data = null;
			}

			// Allow developers to override with there templates.
			return apply_filters( 'mailtpl_woomail_template_data', $data, $key );
		}
	}
	Mailtpl_Woomail_Import_Export::get_instance();
}
