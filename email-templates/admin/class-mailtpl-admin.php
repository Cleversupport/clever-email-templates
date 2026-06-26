<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Mailtpl
 * @subpackage Mailtpl/admin
 * @author     wpexperts
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Admin' ) ) {
	/**
	 * Email templates admin
	 */
	class Mailtpl_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string $plugin_name       The name of this plugin.
		 * @param      string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

            add_action( 'admin_init', array( $this, 'redirect_to_customizer' ) );
		}

        public function redirect_to_customizer() {
            if ( isset( $_GET['page'] ) && 'email-template' === $_GET['page'] ) {
                wp_redirect( admin_url( self::get_customizer_link() ) );
            }
        }

		/**
		 * Create the wp-admin menu link
		 */
		public function add_menu_link() {

			add_menu_page(
				esc_html__( 'Email Templates', 'email-templates' ),
				esc_html__( 'Email Templates', 'email-templates' ),
				'manage_options',
				'email-template',
				'__return_null',
				'dashicons-email'
			);
		}


		
		public function init_smtp_recommendation() {
			if ( ! class_exists( 'Recommend_Post_SMTP_Admin_Notice' ) ) {
				require_once MAILTPL_PLUGIN_DIR . 'admin/post-smtp-notice/recommend-post-smtp-admin-notice.php';
				$recommend_smtp_admin_notice = Recommend_Post_SMTP_Admin_Notice::get_instance();
				$recommend_smtp_admin_notice->set_plugin_info( 'email-templates', 'gif' );
			}
			require_once MAILTPL_PLUGIN_DIR . 'admin/post-smtp-notice/recommend-post-smtp-loader.php';
	
			// Unique ID per plugin (slug, name, etc.)
			$recommend_smtp = recommend_smtp_loader(
				'email-template',     // unique plugin ID
				'email-templates',   // your plugin slug
				true,            // show admin notice
				'email-template',           // parent menu
				'gif'            // logo format
			);
		}

		/**
		 * If we are in our template strip everything out and leave it clean
		 *
		 * @since 1.0.0
		 */
		public function remove_all_actions() {
			global $wp_scripts, $wp_styles;

			$exceptions = array(
				'mailtpl-js',
				'jquery',
				'query-monitor',
				'mailtpl-front-js',
				'customize-preview',
				'customize-controls',
			);

			if ( is_object( $wp_scripts ) && isset( $wp_scripts->queue ) && is_array( $wp_scripts->queue ) ) {
				foreach ( $wp_scripts->queue as $handle ) {
					if ( in_array( $handle, $exceptions, true ) ) {
						continue;
					}
					wp_dequeue_script( $handle );
				}
			}

			if ( is_object( $wp_styles ) && isset( $wp_styles->queue ) && is_array( $wp_styles->queue ) ) {
				foreach ( $wp_styles->queue as $handle ) {
					if ( in_array( $handle, $exceptions, true ) ) {
						continue;
					}
					wp_dequeue_style( $handle );
				}
			}

			// Now remove actions.
			$action_exceptions = array(
				'wp_print_footer_scripts',
				'wp_admin_bar_render',
			);

			// No core action in header.
			remove_all_actions( 'wp_header' );

			global $wp_filter;
			foreach ( $wp_filter['wp_footer'] as $priority => $handle ) {
				if ( in_array( key( $handle ), $action_exceptions, true ) ) {
					continue;
				}
				unset( $wp_filter['wp_footer'][ $priority ] );
			}
		}

		/**
		 * Function that handle all the wp pointers logic and enqueue files
		 *
		 * @since 1.0.1
		 */
		public function wp_pointers() {

			$screen    = get_current_screen();
			$screen_id = $screen->id;

			// Get pointers for this screen.
			$pointers = apply_filters( 'mailtpl_admin_pointers_' . $screen_id, array() );

			if ( ! $pointers || ! is_array( $pointers ) ) {
				return;
			}

			// Get dismissed pointers.
			$dismissed      = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
			$valid_pointers = array();

			// Check pointers and remove dismissed ones.
			foreach ( $pointers as $pointer_id => $pointer ) {

				// Sanity check.
				if ( in_array( $pointer_id, $dismissed, true ) || empty( $pointer ) || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) ) {
					continue;
				}

				$pointer['pointer_id'] = $pointer_id;

				// Add the pointer to $valid_pointers array.
				$valid_pointers['pointers'][] = $pointer;
			}

			// No valid pointers? Stop here.
			if ( empty( $valid_pointers ) ) {
				return;
			}

			// Add pointers style to queue.
			wp_enqueue_style( 'wp-pointer' );

			// Add pointers script to queue. Add custom script.
			wp_enqueue_script( 'mailtpl-pointer', MAILTPL_PLUGIN_URL . '/admin/js/mailtpl-pointer.js', array( 'wp-pointer' ), MAILTPL_VERSION, true );

			// Add pointer options to script.
			wp_localize_script( 'mailtpl-pointer', 'mailtpl_pointer', $valid_pointers );
		}

		/**
		 * Register our pointers
		 *
		 * @param array $pointers Array.
		 *
		 * @return mixed
		 */
		public function add_wp_pointer( $pointers ) {
			$pointers['mailtpl_welcome'] = array(
				'target'  => '#menu-appearance',
				'options' => array(
					'content'  => sprintf(
						'<h3> %s </h3> <p> %s </p>',
						__( 'Email Templates', 'email-templates' ),
						__( 'Now you can edit your email template right in the Appearance menu', 'email-templates' )
					),
					'position' => array(
						'edge'  => 'top',
						'align' => 'middle',
					),
				),
			);
			return $pointers;
		}

		/**
		 * Add our template to Easy Digital Downloads
		 *
		 * @param array $templates Templates.
		 */
		public function add_edd_template( $templates ) {
			$templates['mailtpl'] = 'Email Template Plugin';
			return $templates;
		}

		/**
		 * We need to hook into edd_email_send_before to change get_template to 'none' before it sends so we don't loose formatting
		 */
		public function edd_get_template() {
			add_filter( 'edd_email_template', array( $this, 'set_edd_template' ) );
		}

		/**
		 * We change edd_template as we are using an html template to avoid all the get_template_parts that are taken care now by our plugin
		 *
		 * @return string
		 */
		public function set_edd_template() {
			return 'none';
		}

		/**
		 * WooCommerce Integration.
		 * We first remove our autoformatting as woocommerce will also add it
		 * Then we remove their template header and footer to use ours
		 *
		 * @param object $wc_emails on WC_Emails class.
		 */
		public function woocommerce_integration( $wc_emails ) {
			remove_filter( 'mailtpl_email_content', 'wptexturize' );
			remove_filter( 'mailtpl_email_content', 'convert_chars' );
			remove_filter( 'mailtpl_email_content', 'wpautop' );
			remove_action( 'woocommerce_email_header', array( $wc_emails, 'email_header' ) );
		}

		/**
		 * WooCommerce preview link
		 *
		 * @param array $settings Settings.
		 *
		 * @return array
		 */
		public function woocommerce_preview_link( $settings ) {
            // phpcs:disable Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed
            // phpcs:disable Squiz.PHP.DisallowSizeFunctionsInLoops.Found
            // phpcs:disable Generic.PHP.ForbiddenFunctions.FoundWithAlternative
			for ( $i = 0; $i < sizeof( $settings ); $i++ ) {
                // phpcs:enable
				if ( isset( $settings[ $i ]['id'] ) && 'email_template_options' === $settings[ $i ]['id'] ) {
					$settings[ $i ]['desc'] = sprintf(
						// Translators: %s for customizer link.
						__( 'This section lets you customize the WooCommerce emails. <a href="%1$s" target="_blank">Click here to preview your email template</a>.', 'woocommerce' ),
						self::get_customizer_link()
					);
				}
			}
			return $settings;
		}

		/**
		 * Simple function to generate link for customizer
		 *
		 * @return string
		 */
		public static function get_customizer_link() {
			return add_query_arg(
				array(
					'url'             => rawurlencode( site_url( '/?mailtpl_display=true&email_type=wordpress_standard_email&is_woo_mail=false&_wpnonce=' . wp_create_nonce( 'open-email-template' ) ) ),
					'return'          => rawurlencode( admin_url() ),
					'mailtpl_display' => 'true',
					'email_type'      => 'wordpress_standard_email',
					'_wpnonce'        => wp_create_nonce( 'open-email-template' ),
					'is_woo_mail'     => 'false',
				),
				'customize.php'
			);
		}
	}
}
