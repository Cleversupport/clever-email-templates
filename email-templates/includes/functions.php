<?php
/**
 * Email templates core functions
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'mailtpl_email_templates' ) ) {
	/**
	 * Email templates
	 *
	 * @return Mailtpl_Woomail_Composer
	 */
	function mailtpl_email_templates() {
		return Mailtpl_Woomail_Composer::get_instance();
	}
}

if ( ! function_exists( 'mailtpl_get_all_fonts' ) ) {
	/**
	 * Email templates getting all fonts.
	 *
	 * @return array
	 */
	function mailtpl_get_all_fonts() {
		$fonts = array(
			'arial'               => esc_html__( 'Default', 'login-designer' ),
			'Abril Fatface'       => 'Abril Fatface',
			'Georgia'             => 'Georgia',
			'Helvetica'           => 'Helvetica',
			'Lato'                => 'Lato',
			'Lora'                => 'Lora',
			'Karla'               => 'Karla',
			'Josefin Sans'        => 'Josefin Sans',
			'Montserrat'          => 'Montserrat',
			'Open Sans'           => 'Open Sans',
			'Oswald'              => 'Oswald',
			'Overpass'            => 'Overpass',
			'Poppins'             => 'Poppins',
			'PT Sans'             => 'PT Sans',
			'Roboto'              => 'Roboto',
			'Fira Sans Condensed' => 'Fira Sans',
			'Times New Roman'     => 'Times New Roman',
			'Nunito'              => 'Nunito',
			'Merriweather'        => 'Merriweather',
			'Rubik'               => 'Rubik',
			'Playfair Display'    => 'Playfair Display',
			'Spectral'            => 'Spectral',
		);

		return apply_filters( 'mailtpl_getting_all_fonts', $fonts );
	}
}

if ( ! function_exists( 'mailtpl_recusrive_sanitize_array' ) ) {
	/**
	 * Email templates sanitize text or array or object field
	 *
	 * @param mixed $content Content for sanitization.
	 *
	 * @return mixed
	 */
	function mailtpl_recusrive_sanitize_array( $content ) {
		if ( is_string( $content ) ) {
			$content = sanitize_text_field( $content );
		}

		if ( is_array( $content ) ) {
			foreach ( $content as $key => $value ) {
				$content[ $key ] = mailtpl_recusrive_sanitize_array( $value );
			}
		}

		if ( is_object( $content ) ) {
			foreach ( $content as $key => $value ) {
				$content->$key = mailtpl_recusrive_sanitize_array( $value );
			}
		}

		return $content;
	}
}

if ( ! function_exists( 'mailtpl_font_family_generator' ) ) {
	/**
	 * Email templates font family generator.
	 *
	 * @param string $font_family Font family.
	 * @param string $font_style Font style.
	 * @param string $font_weight Font weight.
	 *
	 * @return string
	 */
	function mailtpl_font_family_generator( $font_family, $font_style, $font_weight ) {
		$font_url = '';
		if ( 'arial' === $font_family ) {
			return '';
		}

		$min_font_weight = '';
		if ( 'normal' !== $font_style ) {
			$font_url        = ':ital';
			$min_font_weight = '1,';
		}

		if ( strlen( $font_url ) ) {
			$font_url .= ',';
		} else {
			$font_url = ':';
		}

		$font_url .= 'wght@' . $min_font_weight . $font_weight;

		return 'family=' . $font_family . $font_url;
	}
}

if ( ! function_exists( 'mailtpl_is_woocommerce_active' ) ) {
	/**
	 * Email templates is woocommerce active.
	 *
	 * @return boolean
	 */
	function mailtpl_is_woocommerce_active() {
		return Mailtpl_Plugin_Check::active_check( 'woocommerce/woocommerce.php' );
	}
}

if ( ! function_exists( 'mailtpl_get_woocommerce_templates' ) ) {
	/**
	 * Email templates get woocommerce templates
	 *
	 * @return array
	 */
	function mailtpl_get_woocommerce_templates() {
		$templates = array(
			'new_order'                 => esc_attr__( 'New Order', 'email-templates' ),
			'cancelled_order'           => esc_attr__( 'Cancelled Order', 'email-templates' ),
			'customer_processing_order' => esc_attr__( 'Processing Order', 'email-templates' ),
			'customer_completed_order'  => esc_attr__( 'Completed Order', 'email-templates' ),
			'customer_refunded_order'   => esc_attr__( 'Refunded Order', 'email-templates' ),
			'customer_on_hold_order'    => esc_attr__( 'On Hold Order', 'email-templates' ),
			'customer_invoice'          => esc_attr__( 'Invoice', 'email-templates' ),
			'failed_order'              => esc_attr__( 'Failed Order', 'email-templates' ),
			'customer_new_account'      => esc_attr__( 'New Account', 'email-templates' ),
			'customer_note'             => esc_attr__( 'Customer Note', 'email-templates' ),
			'customer_reset_password'   => esc_attr__( 'Reset Password', 'email-templates' ),
		);

		return apply_filters( 'mailtpl_get_woocommerce_templates', $templates );
	}
}

if ( ! function_exists( 'mailtpl_get_email_templates' ) ) {
	/**
	 * Email templates get all templates.
	 *
	 * @return array
	 */
	function mailtpl_get_email_templates() {
		$email_templates                             = array();
		$email_templates['wordpress_standard_email'] = esc_attr__( 'WordPress Standard Email', 'email-templates' );
		if ( mailtpl_is_woocommerce_active() ) {
			$email_templates = array_merge( $email_templates, mailtpl_get_woocommerce_templates() );
		}

		return $email_templates;
	}
}

if ( ! function_exists( 'mailtpl_dedicated_for_woocommerce_active' ) ) {
	/**
	 * Email template WooCommerce is required.
	 *
	 * @param string $for Test flag.
	 *
	 * @return bool
	 */
	function mailtpl_dedicated_for_woocommerce_active( $for ) {
		$options = get_option( 'mailtpl_woomail' );
		if ( 'is_woocommerce_and_settings' === $for ) {
			return apply_filters( 'mailtpl_woomail_is_dedicated_for_woocommerce_active', mailtpl_is_woocommerce_active() && $options, is_admin() );
		}
		if ( 'is_settings' === $for ) {
			if ( ! $options ) {
				return apply_filters( 'mailtpl_woomail_is_dedicated_for_woocommerce_active', false, is_admin() );
			}
			return apply_filters( 'mailtpl_woomail_is_dedicated_for_woocommerce_active', isset( $options['use_template_dedicated_for_woocommerce'] ) ? ( $options['use_template_dedicated_for_woocommerce'] ) : true, is_admin() );
		}

		return apply_filters( 'mailtpl_woomail_is_dedicated_for_woocommerce_active', false, is_admin() );
	}
}

if ( ! function_exists( 'mailtpl_fonts_generators' ) ) {
	/**
	 * Email templates fonts generators.
	 *
	 * @param array $settings Settings.
	 *
	 * @return string|void
	 */
	function mailtpl_fonts_generators( $settings ) {
		$google_fonts_array              = array();
		$google_fonts_array['header']    = array();
		$google_fonts_array['body']      = array();
		$google_fonts_array['footer']    = array();
		$google_fonts_array['button']    = array();
		$google_fonts_array['body'][1]   = 'normal';
		$google_fonts_array['footer'][1] = 'normal';
		$google_fonts_array['button'][1] = 'normal';
		if ( isset( $settings['header_font_family'] ) ) {
			$google_fonts_array['header'][0] = $settings['header_font_family'];
		} else {
			$google_fonts_array['header'][0] = 'arial';
		}
		if ( isset( $settings['header_font_style'] ) ) {
			$google_fonts_array['header'][1] = $settings['header_font_style'];
		} else {
			$google_fonts_array['header'][1] = 'normal';
		}
		if ( isset( $settings['header_font_weight'] ) ) {
			$google_fonts_array['header'][2] = $settings['header_font_weight'];
		} else {
			$google_fonts_array['header'][2] = 1;
		}
		if ( isset( $settings['body_font_family'] ) ) {
			$google_fonts_array['body'][0] = $settings['body_font_family'];
		} else {
			$google_fonts_array['body'][0] = 'arial';
		}
		if ( isset( $settings['body_font_weight'] ) ) {
			$google_fonts_array['body'][2] = $settings['body_font_weight'];
		} else {
			$google_fonts_array['body'][2] = 1;
		}
		if ( isset( $settings['footer_font_style'] ) ) {
			$google_fonts_array['footer'][0] = $settings['footer_font_style'];
		} else {
			$google_fonts_array['footer'][0] = 'arial';
		}
		if ( isset( $settings['footer_font_weight'] ) ) {
			$google_fonts_array['footer'][2] = $settings['footer_font_weight'];
		} else {
			$google_fonts_array['footer'][2] = 1;
		}
		if ( isset( $settings['button_font_family'] ) ) {
			$google_fonts_array['button'][0] = $settings['button_font_family'];
		} else {
			$google_fonts_array['button'][0] = 'arial';
		}
		if ( isset( $settings['button_font_weight'] ) ) {
			$google_fonts_array['button'][2] = $settings['button_font_weight'];
		} else {
			$google_fonts_array['button'][2] = 1;
		}

		return Mailtpl::create_fonts_link( $google_fonts_array );
	}
}

if ( ! function_exists( 'mailtpl_get_default_options' ) ) {
	/**
	 * Get default options.
	 *
	 * @return array
	 */
	function mailtpl_get_default_options() {
		return array(
			'footer_powered_by'               => 'off',
			'body_href_color'                 => '#0000ff',
			'header_bg'                       => '#454545',
			'header_aligment'                 => 'center',
			'custom_css'                      => '',
			'from_email'                      => get_bloginfo( 'admin_email' ),
			'from_name'                       => get_bloginfo( 'name' ),
			'footer_bg'                       => '#eee',
			'footer_text_size'                => '12',
			'footer_aligment'                 => 'center',
			'footer_text_color'               => '#777',
			'footer_font_weight'              => '100',
			'footer_font_style'               => 'Arial',
			'footer_text'                     => '&copy;'.date('Y').' ' .get_bloginfo('name'),
			'footer_padding_top'              => '15',
			'footer_padding_left_right'       => '15',
			'footer_padding_bottom'           => '15',
			'footer_text_padding_top'         => '0',
			'footer_text_padding_bottom'      => '0',
			'footer_placement'                => 'inside',
			'header_image_bg'                 => '#454545',
			'header_image_padding_top_bottom' => '0',
			'header_image_alignment'          => 'center',
			'header_text_aligment'            => 'center',
			'header_bg'          => '#454545',
			'header_text_padding_top'         => '15',
			'header_text_padding_left_right'  => '15',
			'header_text_padding_bottom'      => '15',
			'header_font_family'              => 'Arial',
			'header_font_style'               => 'normal',
			'header_font_weight'              => '100',
			'header_text_size'                => '30',
			'header_text_color'               => '#ffffff',
			'header_logo_text'                => get_bloginfo('name'),
			'body_font_weight'                => '100',
			'body_font_family'                => 'Arial',
			'email_body_bg'                   => '#fafafa',
			'body_padding_bottom'             => '20',
			'body_padding_left_right'         => '20',
			'body_padding_top'                => '20',
			'body_text_size'                  => '14',
			'body_text_color'                 => '#888888',
			'body_bg'                         => '#e3e3e3',
			'template_padding_top'            => '70',
			'template_padding_bottom'         => '70',
			'template'                        => 'boxed',
			'body_size'                       => '800',
			'template_border_color'           => '#e3e3e3',
			'template_border_top_width'       => '0',
			'template_border_right_width'     => '0',
			'template_border_bottom_width'    => '0',
			'template_border_left_width'      => '0',
			'template_border_radius'          => '5',
			'template_box_shadow'             => '4',
			'header_logo_location'            => 'inside',
		);
	}
}

if ( ! function_exists( 'mailtpl_get_options' ) ) {
	/**
	 * Get option.
	 *
	 * @param string      $name Option name.
	 * @param mixed|false $default_value Default value.
	 *
	 * @return mixed
	 */
	function mailtpl_get_options( $name, $default_value = false ) {
		$mailtpl_settings          = get_option( 'mailtpl_opts', array() );
		$mailtpl_previous_settings = get_option( 'mailtpl_woomail', array() );
		$default_options           = mailtpl_get_default_options();
		if ( isset( $mailtpl_settings[ $name ] ) ) {
			return $mailtpl_settings[ $name ];
		}
		if ( isset( $mailtpl_previous_settings[ $name ] ) ) {
			return $mailtpl_previous_settings[ $name ];
		}

		if ( isset( $default_options[ $name ] ) ) {
			return $default_options[ $name ];
		}
		return $default_value;
	}
}



if ( ! function_exists( 'mailtpl_show_postman_smtp_marketing' ) ) {
	/**
	 * Show postman smtp marketing.
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	function mailtpl_show_postman_smtp_marketing( $wp_customize, $create_section = false, $random_string = 'mailtpl_postman_smtp_marketing' ) {
		if ( $create_section ) {
			$wp_customize->add_section(
				new Mailtpl_Branding_Section(
					$wp_customize,
					'mailtpl_postman_smtp_marketing',
					array(
						'title'    => __( 'Postman SMTP', 'mailtpl' ),
						'panel'    => 'mailtpl',
						'type'     => 'mailtpl-branding-section',
						'tag'      => __( 'New', 'email-templates' ),
						
					)
				)
			);
		}

		$wp_customize->add_setting(
			'mailtpl_postman_smtp_marketing_' . $random_string,
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Mailtpl_Branding_Control(
				$wp_customize,
				'mailtpl_postman_smtp_marketing_' . $random_string,
				array(
					'section'     => $random_string,
					'label'       => __( 'POST SMTP Mailer – Email log, Delivery Failure Notifications, Chrome and Slack Support', 'mailtpl' ),
					'description' => __( 'Post SMTP is a next-generation WP Mail SMTP plugin that assists and improves the email deliverability process of your WordPress website.
Easy-to-use and reliable – 300,000+ customers trust Post SMTP Mailer to send their daily WordPress emails to millions of users worldwide.
Post SMTP is not another WP Mail SMTP clone like WP Bank or Easy SMTP. It helps provide authentication that makes sure your emails get delivered and don’t end up in the spam filter or worse, the undelivered email queue.', 'mailtpl' ),
					'media'       => array(
						'youtube' => array(
							'width'           => 310,
							'height'          => 290,
							'src'             => 'https://www.youtube.com/embed/UDmwPG-RmDc',
							'title'           => __( 'Postman SMTP', 'mailtpl' ),
							'frameborder'     => 0,
							'allow'           => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
							'allowfullscreen' => true,
						),
					),
					'buttons'     => array(
						'learn_more' => array(
							'title' => __( 'Learn More', 'mailtpl' ),
							'link'  => 'https://postmansmtp.com/free-smtp',
						),
					),
				)
			)
		);
	}
}

if ( ! function_exists( 'mailtpl_box_shadow' ) ) {
	function mailtpl_box_shadow( $shadow ) {
		$enable_shadow    = ( $shadow ) ? '1px' : 0 ;
		$box_shadow_width = ( $shadow ) ? ( $shadow * 4 ) . 'px' : 0;
		$box_shadow_color = 'rgba( 0, 0, 0, 0.5 )';

		return "0 {$enable_shadow} {$box_shadow_width} {$shadow}px {$box_shadow_color}";
	}
}
