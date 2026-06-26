<?php
/**
 * Email Templates Main file.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Mailtpl_Woomail_Composer' ) ) {
	/**
	 * Class Mailtpl_Woomail_Composer.
	 */
	class Mailtpl_Woomail_Composer {
		/**
		 * Instance Control
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * User Role
		 *
		 * @var null
		 */
		private static $admin_capability = null;

		/**
		 * Overide Var
		 *
		 * @var null
		 */
		private static $overwrite_options = null;

		/**
		 * Whether WooCommerce email hooks are disabled for the currently-rendering email.
		 *
		 * @var bool
		 */
		private $woo_email_disabled = false;

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
		 * Construct
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
			add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded_woomail' ) );
            add_action( 'in_plugin_update_message-' . plugin_basename( MAILTPL_PLUGIN_FILE ), array( $this, 'upgrade_notice' ) );
		}
        public function upgrade_notice( $plugin ) {
            if ( version_compare( '1.5.4', MAILTPL_VERSION, '>' ) ) {
                echo '<br><b>' . esc_attr__( 'Please note that the latest update of our WordPress plugin has inherited WooCommerce customizer settings into the WordPress customizer.<br>
                However, we would like to bring to your attention that any custom email styles you may have set up in WooCommerce could be lost due to this update.<br>
                We recommend that you take note of any customizations you have made to your WooCommerce email styles before updating the plugin.<br>
                We apologize for any inconvenience caused, and thank you for your understanding.', 'email-templates' ) . '</b>';
            }
            do_action( 'mailtpl_upgrade_notice', $plugin );
        }

		/**
		 * Function on plugins loaded
		 */
		public function on_plugins_loaded() {
			require plugin_dir_path( __FILE__ ) . 'includes/class-mailtpl.php';
			$plugin = Mailtpl::instance();
			$plugin->run();
		}

		/**
		 * Load all WooCommerce dependencies.
		 */
		public function on_plugins_loaded_woomail() {
			if ( mailtpl_is_woocommerce_active() ) {
				include_once MAILTPL_WOOMAIL_PATH . 'includes/woocommerce-customizer/class-mailtpl-woomail-settings.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/woocommerce-customizer/class-mailtpl-woomail-customizer.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/woocommerce-customizer/class-mailtpl-woomail-import-export.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/woocommerce-customizer/class-mailtpl-woomail-preview.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/woocommerce-customizer/class-mailtpl-woomail-woo.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/customize-controls/class-wp-customize-mailtpl-range-value-control.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/customize-controls/class-wp-customize-mailtpl-template-load-control.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/customize-controls/class-wp-customize-mailtpl-send-email-control.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/customize-controls/class-wp-customize-mailtpl-repeater-control.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/customize-controls/class-wp-customize-mailtpl-info-block-control.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/customize-controls/class-wp-customize-mailtpl-import-export-control.php';
				include_once MAILTPL_WOOMAIL_PATH . 'includes/customize-controls/class-wp-customize-mailtpl-toggle-switch-control.php';

				add_action( 'after_setup_theme', array( $this, 'on_init' ), 80 );
			}
		}

		/**
		 * Trigger Load on init.
		 */
		public function on_init() {
			if ( mailtpl_dedicated_for_woocommerce_active( 'is_settings' ) || is_customize_preview() ) {
				// Remove WC's default email header lazily (at send time, priority 0) instead of
				// calling WC()->mailer() here. Calling it early forces WC to instantiate and lock
				// in all registered email classes before third-party plugins (e.g. Tribe/TEC) have
				// had a chance to register theirs, breaking their email delivery entirely.
				add_action(
					'woocommerce_email_header',
					function() {
						if ( function_exists( 'WC' ) && WC()->mailer() ) {
							remove_action( 'woocommerce_email_header', array( WC()->mailer(), 'email_header' ) );
						}
					},
					0
				);

				add_action( 'woocommerce_email_header', array( $this, 'maybe_disable_woo_for_email' ), 1, 2 );
				add_action( 'woocommerce_email_footer', array( $this, 'reset_woo_email_disabled' ), 1 );
				add_action( 'woocommerce_email_header', array( $this, 'add_email_header' ), 20, 2 );
				add_filter( 'woocommerce_locate_template', array( $this, 'filter_locate_template' ), 10, 3 );
				add_filter( 'woocommerce_email_format_string', array( $this, 'add_extra_placeholders' ), 20, 2 );
				add_action( 'mailtpl_woomailemail_details', array( $this, 'email_main_text_area' ), 10, 4 );
				add_action( 'mailtpl_woomailemail_text', array( $this, 'email_main_text_area_no_order' ), 10, 1 );
				add_action( 'mailtpl_woomailemail_footer', array( $this, 'email_footer_content' ), 100 );
				add_filter( 'woocommerce_email_order_items_args', array( $this, 'add_wc_order_email_args_images' ), 10 );
				add_filter( 'woocommerce_email_footer_text', array( $this, 'email_footer_replace_year' ) );
				add_filter( 'woocommerce_email_setup_locale', array( $this, 'switch_to_site_locale' ) );
				add_filter( 'woocommerce_email_restore_locale', array( $this, 'restore_to_user_locale' ) );
			}
		}

		/**
		 * Detect whether the current WooCommerce email should be skipped by this plugin.
		 *
		 * Fires at priority 1 on woocommerce_email_header, before the plugin's own
		 * header (priority 20) and before any templates are located.
		 *
		 * Users can hook mailtpl_disable_woo_for_email to opt out for specific emails:
		 *
		 *   add_filter( 'mailtpl_disable_woo_for_email', function( $disabled, $email ) {
		 *       if ( $email && strpos( get_class( $email ), 'Tribe' ) !== false ) {
		 *           return true;
		 *       }
		 *       return $disabled;
		 *   }, 10, 2 );
		 *
		 * @param string    $email_heading The email heading text.
		 * @param \WC_Email $email         The WooCommerce email object.
		 */
		public function maybe_disable_woo_for_email( $email_heading, $email ) {
			$this->woo_email_disabled = (bool) apply_filters( 'mailtpl_disable_woo_for_email', false, $email );

			if ( $this->woo_email_disabled && function_exists( 'WC' ) && WC()->mailer() ) {
				// Restore WooCommerce's original header since we removed it in on_init().
				add_action( 'woocommerce_email_header', array( WC()->mailer(), 'email_header' ), 10, 2 );
			}
		}

		/**
		 * Reset the per-email disabled flag after each email finishes rendering.
		 */
		public function reset_woo_email_disabled() {
			if ( $this->woo_email_disabled && function_exists( 'WC' ) && WC()->mailer() ) {
				remove_action( 'woocommerce_email_header', array( WC()->mailer(), 'email_header' ), 10, 2 );
			}
			$this->woo_email_disabled = false;
		}

		/**
		 * Filter callback to replace {year} in email footer
		 *
		 * @param  string $string Email footer text.
		 * @return string         Email footer text with any replacements done.
		 */
		public function email_footer_replace_year( $string ) {
			return str_replace( '{year}', gmdate( 'Y' ), $string );
		}

		/**
		 * Add a notice about woocommerce being needed.
		 *
		 * @param array $args the order detials args.
		 */
		public function add_wc_order_email_args_images( $args ) {
			$product_photo = mailtpl_get_options( 'order_image_image', '' );
			$size          = mailtpl_get_options( 'order_items_image_size', '' );
			if ( 'show' === $product_photo ) {
				$args['show_image'] = true;
				if ( '100x100' === $size ) {
					$args['image_size'] = array( 100, 100 );
				} elseif ( '150x150' === $size ) {
					$args['image_size'] = array( 150, 150 );
				} elseif ( '40x40' === $size ) {
					$args['image_size'] = array( 40, 40 );
				} elseif ( '50x50' === $size ) {
					$args['image_size'] = array( 50, 50 );
				} elseif ( 'woocommerce_thumbnail' === $size ) {
					$args['image_size'] = 'woocommerce_thumbnail';
				} else {
					$args['image_size'] = array( 100, 50 );
				}
			}
			return $args;
		}

		/**
		 * Set up the footer content
		 */
		public function email_footer_content() {
			$content_width             = mailtpl_get_options( 'body_size', '680' );
			$footer_padding_top        = mailtpl_get_options( 'footer_padding_top', '15' );
			$footer_padding_bottom     = mailtpl_get_options( 'footer_padding_bottom', '15' );
			$footer_padding_left_right = mailtpl_get_options( 'footer_padding_left_right', '15' );
			$footer_font_size          = mailtpl_get_options( 'footer_text_size', '12' );
			$footer_text_color         = mailtpl_get_options( 'footer_text_color', '#777777' );
			$footer_background_color   = mailtpl_get_options( 'footer_bg', '#eeeeee' );
			$footer_font_family        = mailtpl_get_options( 'footer_font_style', 'Arial' );
			$footer_font_weight        = mailtpl_get_options( 'footer_font_weight', '100' );
			if ( empty( $content_width ) ) {
				$content_width = '600';
			}

			$content_width = str_replace( 'px', '', $content_width );
			$social_enable = Mailtpl_Woomail_Customizer::opt( 'footer_social_enable' );
			$social_links  = Mailtpl_Woomail_Customizer::opt( 'footer_social_repeater' );
			$social_links  = json_decode( $social_links );
			?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_footer_container" style="
				padding: <?php echo esc_attr( $footer_padding_top ); ?>px <?php echo esc_attr( $footer_padding_left_right ); ?>px <?php echo esc_attr( $footer_padding_bottom ); ?>px <?php echo esc_attr( $footer_padding_left_right ); ?>px;
				font-size:<?php echo esc_attr( $footer_font_size ); ?>px;
				color:<?php echo sanitize_hex_color( $footer_text_color ); ?>;
				background:<?php echo sanitize_hex_color( $footer_background_color ); ?>;
				font-family: <?php echo esc_attr( $footer_font_family ); ?>;
				font-weight: <?php echo esc_attr( $footer_font_weight ); ?>;
			">
				<tr>
					<td valign="top" align="center">
						<table border="0" cellpadding="10" cellspacing="0" width="<?php echo esc_attr( $content_width ); ?>" id="template_footer">
							<tr>
								<td valign="top" id="template_footer_inside">
									<table border="0" cellpadding="10" cellspacing="0" width="100%">
										<?php if ( $social_enable && ! empty( $social_links ) && is_array( $social_links ) ) { ?>
											<tr>
												<td valign="top">
													<table id="footersocial" border="0" cellpadding="10" cellspacing="0" width="100%">
														<tr>
															<?php
															$items = count( $social_links );
															foreach ( $social_links as $social_link ) {
																?>
																<td valign="middle" style="text-align:center; width:<?php echo esc_attr( round( 100 / $items, 2 ) ); ?>%">
																	<a href="<?php echo esc_url( $social_link->link ); ?>" class="ft-social-link" style="display:block; text-decoration: none;">
																		<?php
																		if ( 'customizer_repeater_image' === $social_link->choice ) {
																			echo '<img src="' . esc_attr( $social_link->image_url ) . '" width="24" style="vertical-align: bottom;">';
																		} elseif ( 'customizer_repeater_icon' === $social_link->choice ) {
																			$img_string = str_replace( 'mailtpl-woomail-', '', $social_link->icon_value );
																			if ( isset( $social_link->icon_color ) && ! empty( $social_link->icon_color ) ) {
																				$color = $social_link->icon_color;
																			} else {
																				$color = 'black';
																			}
																			echo '<img alt="' . esc_attr( $img_string ) . '" src="' . esc_attr( MAILTPL_PLUGIN_URL . 'assets/images/' . $color . '/' . $img_string ) . '.png" width="24" style="vertical-align: bottom;">';
																		}
																		?>
																		<span class="ft-social-title"><?php echo esc_html( $social_link->title ); ?></span>
																	</a>
																</td>
																<?php
															}
															?>
														</tr>
													</table>
												</td>
											</tr>
										<?php } ?>
										<tr>
											<td valign="top">
												<table border="0" cellpadding="10" cellspacing="0" width="100%">
													<tr>
														<td colspan="2" valign="middle" id="credit" style="
																font-size:<?php echo esc_attr( $footer_font_size ); ?>px;
																font-weight: <?php echo esc_attr( $footer_font_weight ); ?>;
																color: <?php echo sanitize_hex_color( $footer_text_color ); ?>;
														">
															<?php
															echo wp_kses(
																wpautop(
																	wptexturize(
																		apply_filters(
																			'woocommerce_email_footer_text',
																			get_option( 'woocommerce_email_footer_text' )
																		)
																	)
																),
																array()
															);
															?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?php
		}

		/**
		 * Check if WooCommerce settings need to be overwritten and custom styles applied
		 * This is true when plugin is active and at least one custom option is stored in the database
		 *
		 * @access public
		 * @return bool
		 */
		public static function overwrite_options() {

			// Check if any settings were saved.
			if ( null === self::$overwrite_options ) {
				$option = get_option( 'mailtpl_woomail', array() );

				self::$overwrite_options = ! empty( $option );
			}

			// Return result.
			return self::$overwrite_options;
		}

		/**
		 * Hook in main text areas for customized emails
		 *
		 * @param  WC_Order $order   the order object.
		 * @param  boolean  $sent_to_admin if sent to admin.
		 * @param  boolean  $plain_text if plan text.
		 * @param  object   $email the Email object.
		 * @return void
		 */
		public function email_main_text_area( $order, $sent_to_admin, $plain_text, $email ) {
			$key    = $email->id;
			$button = mailtpl_get_options( 'enable_buttons', false );
            $display_button = sprintf(
                    '<p class="button-container" style="%1$s">
                                <a class="btn" href="%2$s" style="%3$s">%4$s</a>
                            </p>',
                    'margin: 0 0 16px;
                    font-family: ' . esc_attr( mailtpl_get_options( 'button_font_family', '' ) ) . ';
                    font-weight: ' . absint( mailtpl_get_options( 'button_font_weight', '' ) ) . ';
                    padding: ' . absint( mailtpl_get_options( 'button_padding_top_bottom', '' ) ) . 'px ' . absint( mailtpl_get_options( 'button_padding_left_right', '' ) ) . 'px;',
                    esc_url( $order->get_checkout_payment_url() ),
                    'font-style: normal;
                    text-decoration: none;
                    padding: ' . absint( mailtpl_get_options( 'button_padding_top_bottom', '' ) ) . 'px ' . absint( mailtpl_get_options( 'button_padding_left_right', '' ) ) . 'px;
                    background-color: ' . sanitize_hex_color( mailtpl_get_options( 'button_background_color', '' ) ) . ';
                    color: ' . sanitize_hex_color( mailtpl_get_options( 'button_text_color', '' ) ) . ';
                    border-style: ' . esc_attr( mailtpl_get_options( 'button_border_style', '' ) ) . ';
                    border-width: ' . absint( mailtpl_get_options( 'button_border_width', '' ) ) . 'px;
                    font-family: ' . esc_attr( mailtpl_get_options( 'button_font_family', '' ) ) . ';
                    font-weight: ' . absint( mailtpl_get_options( 'button_font_weight', '' ) ) . ';
                    font-size: ' . absint( mailtpl_get_options( 'button_font_size', '' ) ) . 'px;
                    border-radius: ' . absint( mailtpl_get_options( 'button_border_radius', '' ) ) . 'px;',
                    __( 'Pay for this order', 'email-templates' )
            );
			if ( 'customer_refunded_order' === $key ) {
				if ( $email->partial_refund ) {
					$body_text = Mailtpl_Woomail_Customizer::opt( $key . '_body_partial' );
				} else {
					$body_text = Mailtpl_Woomail_Customizer::opt( $key . '_body_full' );
				}
			} elseif ( 'customer_partially_refunded_order' === $key ) {
                $body_text = Mailtpl_Woomail_Customizer::opt( 'customer_refunded_order_body_partial' );
            } elseif ( 'customer_invoice' === $key ) {
				if ( $order->has_status( 'pending' ) ) {
					$body_text = Mailtpl_Woomail_Customizer::opt( $key . '_body' );
					if ( $button ) {
						$pay_link = $display_button;
					} else {
						$pay_link = '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay for this order', 'email-templates' ) . '</a>';
					}
					$body_text = str_replace( '{invoice_pay_link}', $pay_link, $body_text );
				} else {
					$body_text = Mailtpl_Woomail_Customizer::opt( $key . '_body_paid' );
				}
			} elseif ( 'customer_renewal_invoice' === $key ) {
				if ( $order->has_status( 'pending' ) ) {
					$body_text = Mailtpl_Woomail_Customizer::opt( $key . '_body' );
					if ( $button ) {
						$pay_link = $display_button;
					} else {
						$pay_link = '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay Now &raquo;', 'email-templates' ) . '</a>';
					}
					$body_text = str_replace( '{invoice_pay_link}', $pay_link, $body_text );
				} else {
					$body_text = Mailtpl_Woomail_Customizer::opt( $key . '_body_failed' );
					if ( $button ) {
						$pay_link = $display_button;
					} else {
						$pay_link = '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay Now &raquo;', 'email-templates' ) . '</a>';
					}
					$body_text = str_replace( '{invoice_pay_link}', $pay_link, $body_text );
				}
			} elseif ( 'customer_payment_retry' === $key ) {
				$body_text = Mailtpl_Woomail_Customizer::opt( $key . '_body' );
				if ( $button ) {
					$pay_link = $display_button;
				} else {
					$pay_link = '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay Now &raquo;', 'email-templates' ) . '</a>';
				}
				$body_text = str_replace( '{invoice_pay_link}', $pay_link, $body_text );
			} else {
				$body_text = Mailtpl_Woomail_Customizer::opt( $key . '_body' );
			}

			$body_text = str_replace( '{site_title}', get_bloginfo( 'name', 'display' ), $body_text );
			$body_text = str_replace( '{site_address}', wp_parse_url( home_url(), PHP_URL_HOST ), $body_text );
			$body_text = str_replace( '{site_url}', wp_parse_url( home_url(), PHP_URL_HOST ), $body_text );

			if ( $order ) {
				$user_id = (int) get_post_meta( $order->get_id(), '_customer_user', true );
				if ( 0 === $user_id ) {
					$user_id = 'guest';
				}
				$body_text = str_replace( '{order_date}', wc_format_datetime( $order->get_date_created() ), $body_text );
				$body_text = str_replace( '{order_number}', $order->get_order_number(), $body_text );
				$body_text = str_replace( '{customer_first_name}', $order->get_billing_first_name(), $body_text );
				$body_text = str_replace( '{customer_last_name}', $order->get_billing_last_name(), $body_text );
				$body_text = str_replace( '{customer_full_name}', $order->get_formatted_billing_full_name(), $body_text );
				$body_text = str_replace( '{customer_company}', $order->get_billing_company(), $body_text );
				$body_text = str_replace( '{customer_email}', $order->get_billing_email(), $body_text );
				$body_text = str_replace( '{customer_username}', self::get_username_from_id( $user_id ), $body_text );
			}
			$body_text = apply_filters( 'mailtpl_woomail_order_body_text', $body_text, $order, $sent_to_admin, $plain_text, $email );
			$body_text = wpautop( $body_text );
			echo wp_kses_post( $body_text );
		}

		/**
		 * Get username from user id.
		 *
		 * @param string $id the user id.
		 * @access public
		 * @return string
		 */
		public static function get_username_from_id( $id ) {
			if ( empty( $id ) || 'guest' === $id ) {
				return __( 'Guest', 'email-templates' );
			}
			$user = get_user_by( 'id', $id );
			if ( is_object( $user ) ) {
				$username = $user->user_login;
			} else {
				$username = __( 'Guest', 'email-templates' );
			}
			return $username;
		}

		/**
		 * Filter Subtitle for Placeholders
		 *
		 * @param string $subtitle the email subtitle.
		 * @param object $email the email object.
		 * @access public
		 * @return string
		 */
		public static function filter_subtitle( $subtitle, $email ) {
			// Check for placeholders.
			$subtitle = str_replace( '{site_title}', get_bloginfo( 'name', 'display' ), $subtitle );
			$subtitle = str_replace( '{site_address}', wp_parse_url( home_url(), PHP_URL_HOST ), $subtitle );
			$subtitle = str_replace( '{site_url}', wp_parse_url( home_url(), PHP_URL_HOST ), $subtitle );
			if ( is_a( $email->object, 'WP_User' ) ) {
				$first_name = get_user_meta( $email->object->ID, 'billing_first_name', true );
				if ( empty( $first_name ) ) {
					// Fall back to user display name.
					$first_name = $email->object->display_name;
				}

				$last_name = get_user_meta( $email->object->ID, 'billing_last_name', true );
				if ( empty( $last_name ) ) {
					// Fall back to user display name.
					$last_name = $email->object->display_name;
				}

				$full_name = get_user_meta( $email->object->ID, 'formatted_billing_full_name', true );
				if ( empty( $full_name ) ) {
					// Fall back to user display name.
					$full_name = $email->object->display_name;
				}
				$subtitle = str_replace( '{customer_first_name}', $first_name, $subtitle );
				$subtitle = str_replace( '{customer_last_name}', $last_name, $subtitle );
				$subtitle = str_replace( '{customer_full_name}', $full_name, $subtitle );
				$subtitle = str_replace( '{customer_username}', $email->user_login, $subtitle );
				$subtitle = str_replace( '{customer_email}', $email->object->user_email, $subtitle );

			} elseif ( is_a( $email->object, 'WC_Order' ) ) {
				$user_id = (int) get_post_meta( $email->object->get_id(), '_customer_user', true );
				if ( 0 === $user_id ) {
					$user_id = 'guest';
				}
				$subtitle = str_replace( '{order_date}', wc_format_datetime( $email->object->get_date_created() ), $subtitle );
				$subtitle = str_replace( '{order_number}', $email->object->get_order_number(), $subtitle );
				$subtitle = str_replace( '{customer_first_name}', $email->object->get_billing_first_name(), $subtitle );
				$subtitle = str_replace( '{customer_last_name}', $email->object->get_billing_last_name(), $subtitle );
				$subtitle = str_replace( '{customer_full_name}', $email->object->get_formatted_billing_full_name(), $subtitle );
				$subtitle = str_replace( '{customer_company}', $email->object->get_billing_company(), $subtitle );
				$subtitle = str_replace( '{customer_email}', $email->object->get_billing_email(), $subtitle );
				$subtitle = str_replace( '{customer_username}', self::get_username_from_id( $user_id ), $subtitle );
			} elseif ( is_a( $email->object, 'WC_Product' ) ) {
				$subtitle = str_replace( '{product_title}', $email->object->get_title(), $subtitle );
			}

			return $subtitle;
		}

		/**
		 * Hook in main text areas for customized emails.
		 *
		 * @param object $email the email object.
		 * @access public
		 * @return void
		 */
		public function email_main_text_area_no_order( $email ) {

			// Get Email ID.
			$key = $email->id;

			$body_text = Mailtpl_Woomail_Customizer::opt( $key . '_body' );
			// Check for placeholders.
			$body_text = str_replace( '{site_title}', get_bloginfo( 'name', 'display' ), $body_text );
			$body_text = str_replace( '{site_address}', wp_parse_url( home_url(), PHP_URL_HOST ), $body_text );
			$body_text = str_replace( '{site_url}', wp_parse_url( home_url(), PHP_URL_HOST ), $body_text );
			if ( is_a( $email->object, 'WP_User' ) ) {

				$first_name = get_user_meta( $email->object->ID, 'billing_first_name', true );
				if ( empty( $first_name ) ) {
					$first_name = get_user_meta( $email->object->ID, 'first_name', true );
					if ( empty( $first_name ) ) {
						// Fall back to user display name.
						$first_name = $email->object->display_name;
					}
				}

				$last_name = get_user_meta( $email->object->ID, 'billing_last_name', true );
				if ( empty( $last_name ) ) {
					$last_name = get_user_meta( $email->object->ID, 'last_name', true );
					if ( empty( $last_name ) ) {
						// Fall back to user display name.
						$last_name = $email->object->display_name;
					}
				}

				$full_name = get_user_meta( $email->object->ID, 'formatted_billing_full_name', true );
				if ( empty( $full_name ) ) {
					// Fall back to user display name.
					$full_name = $email->object->display_name;
				}
				$body_text = str_replace( '{customer_first_name}', $first_name, $body_text );
				$body_text = str_replace( '{customer_last_name}', $last_name, $body_text );
				$body_text = str_replace( '{customer_full_name}', $full_name, $body_text );
				$body_text = str_replace( '{customer_username}', $email->user_login, $body_text );
				$body_text = str_replace( '{customer_email}', $email->object->user_email, $body_text );
			} elseif ( is_a( $email->object, 'WC_Product' ) ) {
				$body_text = str_replace( '{product_title}', $email->object->get_title(), $body_text );
				$body_text = str_replace( '{product_link}', $email->object->get_permalink(), $body_text );
			}

			$body_text = apply_filters( 'mailtpl_woomail_no_order_body_text', $body_text, $email );

			// auto wrap text.
			$body_text = wpautop( $body_text );

			echo wp_kses_post( $body_text );
		}

		/**
		 * Filter through strings to add support for extra placeholders
		 *
		 * @param string $string string of text.
		 * @param object $email  the email object.
		 * @access public
		 * @return string
		 */
		public function add_extra_placeholders( $string, $email ) {

			if ( is_a( $email->object, 'WP_User' ) ) {
				$first_name = get_user_meta( $email->object->ID, 'billing_first_name', true );
				if ( empty( $first_name ) ) {
					$first_name = get_user_meta( $email->object->ID, 'first_name', true );
					if ( empty( $first_name ) ) {
						// Fall back to user display name.
						$first_name = $email->object->display_name;
					}
				}

				$last_name = get_user_meta( $email->object->ID, 'billing_last_name', true );
				if ( empty( $last_name ) ) {
					$last_name = get_user_meta( $email->object->ID, 'last_name', true );
					if ( empty( $last_name ) ) {
						// Fall back to user display name.
						$last_name = $email->object->display_name;
					}
				}

				$full_name = get_user_meta( $email->object->ID, 'formatted_billing_full_name', true );
				if ( empty( $full_name ) ) {
					// Fall back to user display name.
					$full_name = $email->object->display_name;
				}
				$string = str_replace( '{customer_first_name}', $first_name, $string );
				$string = str_replace( '{customer_last_name}', $last_name, $string );
				$string = str_replace( '{customer_full_name}', $full_name, $string );
				$string = str_replace( '{customer_username}', $email->user_login, $string );
				$string = str_replace( '{customer_email}', $email->object->user_email, $string );

			} elseif ( is_a( $email->object, 'WC_Order' ) ) {
				$user_id = (int) get_post_meta( $email->object->get_id(), '_customer_user', true );
				if ( 0 === $user_id ) {
					$user_id = 'guest';
				}
				$string = str_replace( '{customer_first_name}', $email->object->get_billing_first_name(), $string );
				$string = str_replace( '{customer_last_name}', $email->object->get_billing_last_name(), $string );
				$string = str_replace( '{customer_full_name}', $email->object->get_formatted_billing_full_name(), $string );
				$string = str_replace( '{customer_company}', $email->object->get_billing_company(), $string );
				$string = str_replace( '{customer_email}', $email->object->get_billing_email(), $string );
				$string = str_replace( '{customer_username}', self::get_username_from_id( $user_id ), $string );
			}

			return $string;
		}

		/**
		 * Checks to see if we are opening our custom customizer preview
		 *
		 * @access public
		 * @return bool
		 */
		public static function is_own_preview_request() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return isset( $_REQUEST['mailtpl-woomail-preview'] ) && '1' === $_REQUEST['mailtpl-woomail-preview'];
		}

		/**
		 * Checks to see if we are opening our custom customizer controls
		 *
		 * @access public
		 * @return bool
		 */
		public static function is_own_customizer_request() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return isset( $_REQUEST['mailtpl-woomail-customize'] ) && '1' === $_REQUEST['mailtpl-woomail-customize'];
		}

		/**
		 * Gets the capability setting needed to edit in the email customizer
		 *
		 * @access public
		 * @return string
		 */
		public static function get_admin_capability() {
			// Get capability.
			if ( is_null( self::$admin_capability ) ) {
				self::$admin_capability = apply_filters( 'mailtpl_woomail_capability', 'manage_woocommerce' );
			}

			// Return capability.
			return self::$admin_capability;
		}

		/**
		 * Check if user is authorized to use the email customizer
		 *
		 * @access public
		 * @return bool
		 */
		public static function is_admin() {
			return current_user_can( self::get_admin_capability() );
		}

		/**
		 * Hook in email header with access to the email object
		 *
		 * @param string $email_heading email heading.
		 * @param object $email the email object.
		 * @access public
		 * @return void
		 */
		public function add_email_header( $email_heading, $email = '' ) {
			if ( $this->woo_email_disabled ) {
				return;
			}

			wc_get_template(
				'emails/email-header.php',
				array(
					'email_heading' => $email_heading,
					'email'         => $email,
				)
			);
		}

		/**
		 * Filter in custom email templates with priority to child themes
		 *
		 * @param string $template the email template file.
		 * @param string $template_name name of email template.
		 * @param string $template_path path to email template.
		 * @access public
		 * @return string
		 */
		public function filter_locate_template( $template, $template_name, $template_path ) {
			if ( $this->woo_email_disabled ) {
				return $template;
			}

			// Make sure we are working with an email template.
			if ( ! in_array( 'emails', explode( '/', $template_name ), true ) ) {
				return $template;
			}
			// clone template.
			$_template = $template;

			// Get the woocommerce template path if empty.
			if ( ! $template_path ) {
				global $woocommerce;
				$template_path = $woocommerce->template_url;
			}

			// Get our template path.
			$plugin_path = MAILTPL_WOOMAIL_PATH . 'templates/woo/';

			// Look within passed path within the theme - this is priority.
			$template = locate_template( array( $template_path . $template_name, $template_name ) );

			// If theme isn't trying to override get the template from this plugin, if it exists.
			if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
				$template = $plugin_path . $template_name;
			}

			// else if we still don't have a template use default.
			if ( ! $template ) {
				$template = $_template;
			}
			// Return template.
			return $template;
		}

		/**
		 * Filter in custom email templates with priority to child themes
		 *
		 * @param string $template the email template file.
		 * @param string $template_name name of email template.
		 * @param string $template_path path to email template.
		 * @access public
		 * @return string
		 */
		public function filter_locate_template_language( $template, $template_name, $template_path ) {
			// Make sure we are working with an email template.
			if ( ! in_array( 'emails', explode( '/', $template_name ), true ) ) {
				return $template;
			}

			// Return template.
			return $template;
		}

		/**
		 * Filter when email languages are set, adds in a switch if needed
		 *
		 * @access public
		 * @param bool $switch whether or not it should switch.
		 * @return bool
		 */
		public function switch_to_site_locale( $switch ) {
			if ( $switch ) {
				if ( function_exists( 'switch_to_locale' ) ) {
					add_filter( 'woocommerce_locate_template', array( $this, 'filter_locate_template_language' ), 5, 3 );
				}
			}
			return $switch;
		}

		/**
		 * Restore the locale to the default locale. Use after finished with setup_locale.
		 *
		 * @param boolean $switch whether or not it should switch.
		 * @return boolean
		 */
		public function restore_to_user_locale( $switch ) {
			if ( $switch ) {
				if ( function_exists( 'restore_previous_locale' ) ) {
					remove_filter( 'woocommerce_locate_template', array( $this, 'filter_locate_template_language' ), 5, 3 );
				}
			}
			return $switch;
		}
	}
}
