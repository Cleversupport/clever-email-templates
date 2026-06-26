<?php
/**
 * All mail functions will go in here
 *
 * @link       https://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Mailtpl
 * @subpackage Mailtpl/includes
 * @author     wpexperts
 */

if ( ! class_exists( 'Mailtpl_Mailer' ) ) {
	/**
	 * Class Mailtpl_Mailer.
	 */
	class Mailtpl_Mailer {

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
		 * dynamic property
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      mixed  
		 */
		private $opts;


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
			$this->opts        = Mailtpl::opts();
		}

		/**
		 * Send html emails instead of text plain
		 *
		 * @param string $type Type.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function set_content_type( $type ) {
			if ( 'text/html' !== $type ) {
				// If not html, work with content and filter it.
				add_filter( 'mailtpl_email_content', 'wp_kses_post', 50 );
				$this->add_content_filters();
			}
			return 'text/html';
		}

		/**
		 * Send Email to All the SMTP Plugins
		 *
		 * @param array $args Expected args.
		 *
		 * @since 1.0.0
		 */
		public function send_email( $args ) {

			do_action( 'mailtpl_send_email', $args, $this );

			if ( empty( $args['message'] ) ) {
				return $args;
			}

			// Skip if this email was already processed by Profile Builder handler
			if ( isset( $args['_mailtpl_pb_processed'] ) ) {
				return $args;
			}

			/**
			 * Filter to disable Email Templates for specific emails.
			 * @param bool  $disabled Whether to disable Email Templates. Default false.
			 * @param array $args The email arguments (to, subject, message, headers, attachments).
			 */
			if ( apply_filters( 'mailtpl_disable_for_email', false, $args ) ) {
				return $args;
			}

			// Detect full HTML emails (Elementor, builders, etc.)
			$has_full_html = stripos( $args['message'], '<html' ) !== false;

			$user_email = isset( $args['to'] ) ? $args['to'] : get_option( 'admin_email' );

			$skip_template = false;
			if ( $has_full_html ) {
				// Check backtrace for plugins that build their own complete HTML emails
				// (e.g. Elementor, FluentCRM) to avoid double-wrapping.
				$skip_plugins = apply_filters( 'mailtpl_skip_template_plugins', array( 'elementor', 'fluent-crm', 'fluentcrm' ) );
				$backtrace    = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 10 );
				foreach ( $backtrace as $trace ) {
					if ( ! isset( $trace['file'] ) ) {
						continue;
					}
					foreach ( $skip_plugins as $plugin_slug ) {
						if ( strpos( $trace['file'], $plugin_slug ) !== false ) {
							$skip_template = true;
							break 2;
						}
					}
				}
			}

			if ( $skip_template ) {
				// Elementor already outputs complete HTML – skip Email Templates wrapper.
				$args['message'] = $this->prepare_html_for_delivery(
					$this->replace_placeholders(
						$args['message'],
						$user_email
					)
				);
				$args = $this->ensure_html_mail_headers( $args );

				return $args;
			}

			// Normal behavior for WP system / plain emails or MemberPress
			$temp_message = $this->add_template(
				apply_filters( 'mailtpl_email_content', $args['message'] )
			);

			$args['message'] = $this->prepare_html_for_delivery(
				$this->replace_placeholders( $temp_message, $user_email )
			);
			$args = $this->ensure_html_mail_headers( $args );

			return $args;
		}

		/**
		 * Track Profile Builder processed emails
		 * 
		 * @var array
		 */
		private static $pb_processed_emails = array();
		/**
		 * Handle Profile Builder emails using their wppb_mail filter
		 *
		 * @param array $atts Email attributes from Profile Builder
		 * @param string $context Profile Builder email context
		 * @return array Modified email attributes
		 * @since 1.0.0
		 */
		public function handle_profile_builder_mail( $atts, $context = null ) {
			if ( ! is_array( $atts ) || empty( $atts['message'] ) ) {
				return $atts;
			}
			$user_email = isset( $atts['to'] ) ? $atts['to'] : get_option( 'admin_email' );
			// Check if message has full HTML wrapper from Profile Builder
			$has_full_html = stripos( $atts['message'], '<html' ) !== false;
			if ( $has_full_html ) {
				// Extract the content from Profile Builder's HTML wrapper
				$body_content = $atts['message'];
				// Remove the HTML wrapper that Profile Builder added
				$body_content = preg_replace('/<html[^>]*>.*?<body[^>]*>/is', '', $body_content);
				$body_content = preg_replace('/<\/body>.*?<\/html>/is', '', $body_content);
				$body_content = trim( $body_content );
			} else {
				$body_content = $atts['message'];
			}
			// Apply Email Templates
			$temp_message = $this->add_template(
				apply_filters( 'mailtpl_email_content', $body_content )
			);
			$atts['message'] = $this->prepare_html_for_delivery(
				$this->replace_placeholders( $temp_message, $user_email )
			);
			// Mark this email as processed by Profile Builder to prevent double processing
			$email_hash = md5( $atts['to'] . $atts['subject'] . $atts['message'] );
			self::$pb_processed_emails[ $email_hash ] = true;
			// Also add a temporary filter to mark wp_mail args
			add_filter( 'wp_mail', array( $this, 'mark_profile_builder_email' ), 1 );
			return $atts;
		}
		/**
		 * Mark wp_mail arguments as Profile Builder processed
		 *
		 * @param array $args wp_mail arguments
		 * @return array Modified arguments
		 */
		public function mark_profile_builder_email( $args ) {
			// Check if this matches a Profile Builder processed email
			$email_hash = md5( $args['to'] . $args['subject'] . $args['message'] );
			if ( isset( self::$pb_processed_emails[ $email_hash ] ) ) {
				$args['_mailtpl_pb_processed'] = true;
				// Remove this email from our tracking and remove the filter
				unset( self::$pb_processed_emails[ $email_hash ] );
				remove_filter( 'wp_mail', array( $this, 'mark_profile_builder_email' ), 1 );
			}
			return $args;
		}


		/**
		 * Add content filters
		 */
		private function add_content_filters() {
			add_filter( 'mailtpl_email_content', 'wptexturize' );
			add_filter( 'mailtpl_email_content', 'convert_chars' );
			add_filter( 'mailtpl_email_content', 'wpautop' );
		}


		/**
		 * Normalize generated HTML before other mail/deliverability plugins inspect it.
		 *
		 * Site Mailer/other mail handlers can treat raw new lines in HTML as line breaks
		 * when the content type is not present in the wp_mail args yet. Removing structural
		 * new lines prevents <br> tags from being injected inside table-based email markup.
		 *
		 * @param string $message Email HTML.
		 * @return string
		 */
		private function prepare_html_for_delivery( $message ) {
			if ( ! is_string( $message ) || '' === $message ) {
				return $message;
			}

			$looks_like_html = false !== stripos( $message, '<html' )
				|| false !== stripos( $message, '<body' )
				|| false !== stripos( $message, '<table' );

			if ( ! $looks_like_html ) {
				return $message;
			}

			$message = str_replace( array( "\r\n", "\r" ), "\n", $message );
			$message = preg_replace( '/[ \t]*\n+[ \t]*/', ' ', $message );
			$message = preg_replace( '/>\s+</', '><', $message );

			return trim( $message );
		}

		/**
		 * Add HTML Content-Type directly to wp_mail args.
		 *
		 * Relying only on wp_mail_content_type can be too late for deliverability plugins
		 * that inspect wp_mail args before WordPress runs the content type filter.
		 *
		 * @param array $args wp_mail args.
		 * @return array
		 */
		private function ensure_html_mail_headers( $args ) {
			if ( ! is_array( $args ) ) {
				return $args;
			}

			$headers = isset( $args['headers'] ) ? $args['headers'] : array();

			if ( is_string( $headers ) ) {
				$headers = preg_split( '/\r\n|\r|\n/', $headers );
			}

			if ( ! is_array( $headers ) ) {
				$headers = array();
			}

			$headers = array_filter(
				$headers,
				function( $header ) {
					return 0 !== stripos( trim( (string) $header ), 'Content-Type:' );
				}
			);

			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			$args['headers'] = array_values( $headers );

			return $args;
		}

		/**
		 * Send a test email to admin email
		 *
		 * @since 1.0.0
		 */
		public function send_test_email() {
			if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'mailtpl-send-test-mail' ) ) {
				if ( isset( $_POST['email_type'] ) ) {
					if ( 'wordpress_standard_email' === sanitize_text_field( wp_unslash( $_POST['email_type'] ) ) ) {
						ob_start();
						include_once apply_filters( 'mailtpl_customizer_template_message', MAILTPL_PLUGIN_DIR . 'templates/default/includes/email-body.php' );
						$message = ob_get_contents();
						ob_end_clean();
						$subject      = __( 'WP Email Templates', 'email-templates' );
						$email_sanded = wp_mail(
							get_bloginfo( 'admin_email' ),
							$subject,
							$message
						);

						if ( $email_sanded ) {
							wp_send_json_success(
								array(
									'email_sanded' => 'true',
									'message'      => __( 'Email sent successfully', 'email-templates' ),
								),
								200
							);
						}
					}

					$email_type    = sanitize_text_field( wp_unslash( $_POST['email_type'] ) );
					$preview_order = isset( $_POST['preview_order'] ) ? sanitize_text_field( wp_unslash( $_POST['preview_order'] ) ) : '';

					if ( class_exists( 'Mailtpl_Woomail_Preview' ) ) {
						$content = Mailtpl_Woomail_Preview::get_preview_email( true, get_bloginfo( 'admin_email' ), $email_type, $preview_order );
					
						if ( $content ) {
							wp_send_json_success(
								array(
									'email_sanded' => 'true',
									'message'      => __( 'Email sent successfully', 'email-templates' ),
								),
								200
							);
						}
					} else {
						// Optional: respond with failure if class doesn't exist
						wp_send_json_error(
							array(
								'email_sanded' => 'false',
								'message'      => __( 'Mailtpl_Woomail_Preview class not found. Email not sent.', 'email-templates' ),
							),
							400
						);
					}
					
				}
			}
		}

		/**
		 * Add template to plain mail
		 *
		 * @param string $email Mail to be send.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		private function add_template( $email ) {
			do_action( 'mailtpl_add_template', $email, $this );

			$template_file = apply_filters( 'mailtpl_customizer_template', MAILTPL_PLUGIN_DIR . "/admin/templates/default.php" );
			ob_start();
			include $template_file;
			$template = ob_get_contents();
			ob_end_clean();
			return apply_filters( 'mailtpl_return_template', str_replace( '%%MAILCONTENT%%', $email, $template ) );
		}

		/**
		 * Replace placeholders
		 *
		 * @param string $email Mail to be send.
		 * @param string $user_email Get destination email.
		 * Passed to the filters in case users needs something.
		 *
		 * @return string
		 */
		private function replace_placeholders( $email, $user_email = '' ) {

			$to_replace = apply_filters(
				'emailtpl_placeholders',
				array(
					'##SITEURL###'         => get_option( 'siteurl' ),
					'%%BLOG_URL%%'         => get_option( 'siteurl' ),
					'%%HOME_URL%%'         => get_option( 'home' ),
					'%%BLOG_NAME%%'        => get_option( 'blogname' ),
					'%%BLOG_DESCRIPTION%%' => get_option( 'blogdescription' ),
					'%%ADMIN_EMAIL%%'      => get_option( 'admin_email' ),
					'%%DATE%%'             => date_i18n( get_option( 'date_format' ) ),
					'%%TIME%%'             => date_i18n( get_option( 'time_format' ) ),
					'%%USER_EMAIL%%'       => $user_email,
				),
				$user_email
			);

			foreach ( $to_replace as $placeholder => $var ) {
				if ( is_array( $var ) ) {
					do {
						$var = reset( $var );
					} while ( is_array( $var ) );
				}
				$email = str_replace( $placeholder, $var, $email );
			}

			return $email;
		}

		/**
		 * Sets email's From email
		 *
		 * @param string $email Email.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function set_from_email( $email ) {
			if ( empty( $this->opts['from_email'] ) ) {
				return $email;
			}
			return $this->opts['from_email'];
		}

		/**
		 * Sets email's From name
		 *
		 * @param string $name Name.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function set_from_name( $name ) {
			if ( empty( $this->opts['from_name'] ) ) {
				return $name;
			}
			return $this->opts['from_name'];
		}

		/**
		 * Clear retrieve password message for wrong html tag
		 *
		 * @param string $message Message.
		 *
		 * @return mixed
		 */
		public function clean_retrieve_password( $message ) {
			return make_clickable( preg_replace( '@<(http[^> ]+)>@', '$1', $message ) );
		}

		/**
		 * This way we fully removed html added by gravity forms. Only possible on versions  2.2.1.5 or above
		 *
		 * @since 1.2.2
		 * @return string
		 */
		public function gform_template() {
			return '{message}';
		}
	}
}
