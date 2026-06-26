<?php
/**
 * Class Mailtpl Customizer.
 *
 * @package Email Templates
 */

/**
 * All customizer aspects will go in here
 *
 * @link       https://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Mailtpl
 * @subpackage Mailtpl/includes
 * @author     wpexperts
 */
class Mailtpl_Customizer {

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

    private $defaults;

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
		$this->defaults    = Mailtpl::defaults();

		add_action( 'customize_controls_print_styles', array( $this, 'customize_control_print_style' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_control_enqueue_script' ) );
	}

	/**
	 * Customize control styles
	 */
	public function customize_control_print_style() {
		wp_enqueue_style( 'mailtpl-customize-panel-sections-css', MAILTPL_PLUGIN_URL.'assets/css/sections/custom-sections.css', array(), MAILTPL_VERSION, 'all' );
	}

	/**
	 * Customize control scripts
	 */
	public function customize_control_enqueue_script() {
		wp_enqueue_script( 'mailtpl-customize-panel-script', MAILTPL_PLUGIN_URL . 'assets/js/sections/custom-sections.js', array( 'customize-controls' ), MAILTPL_VERSION, true );
	}

	/**
	 * Add all panels to customizer
	 *
	 * @param WP_Customize_Manager $wp_customize Customize manager.
	 */
	public function register_customize_sections( $wp_customize ) {
		$this->required_panels( $wp_customize );
		$this->required_sections( $wp_customize );
		$this->required_controls( $wp_customize );

		$wp_customize->add_panel(
			'mailtpl',
			array(
				'title'       => __( 'Wordpress Email Templates', 'email-templates' ),
				'description' => __( 'Within the Email Templates customizer you can change how your WordPress Emails looks. It\'s fully compatible with WooCommerce and Easy Digital Downloads html emails', 'email-templates' ),
			)
		);

		do_action( 'mailtpl_sections_before', $wp_customize );
		// Add sections.
		$wp_customize->add_section(
			'section-mailtpl-email-type',
			array(
				'title' => esc_attr__( 'Change Template', 'email-templates' ),
				'panel' => 'mailtpl',
			)
		);
		$wp_customize->add_section(
			'section_mailtpl_settings',
			array(
				'title' => __( 'Settings', 'email-templates' ),
				'panel' => 'mailtpl',
			)
		);
		$wp_customize->add_section(
			'section_mailtpl_template',
			array(
				'title' => __( 'Template', 'email-templates' ),
				'panel' => 'mailtpl',
			)
		);
		$wp_customize->add_section(
			'section_mailtpl_header',
			array(
				'title' => __( 'Email Header', 'email-templates' ),
				'panel' => 'mailtpl',
			)
		);
		$wp_customize->add_section(
			'section_mailtpl_body',
			array(
				'title' => __( 'Email Body', 'email-templates' ),
				'panel' => 'mailtpl',
			)
		);
		$wp_customize->add_section(
			'section_mailtpl_footer',
			array(
				'title' => __( 'Footer', 'email-templates' ),
				'panel' => 'mailtpl',
			)
		);
		$wp_customize->add_section(
			'section_mailtpl_test',
			array(
				'title' => __( 'Send Test Email', 'email-templates' ),
				'panel' => 'mailtpl',
			)
		);
	
		// Populate sections.
		$this->section_mailtpl_email_type( $wp_customize );
		$this->settings_section( $wp_customize );
		$this->template_section( $wp_customize );
		$this->header_section( $wp_customize );
		$this->body_section( $wp_customize );
		$this->footer_section( $wp_customize );
		$this->test_section( $wp_customize );

		do_action( 'mailtpl_sections_after', $wp_customize );

		if ( mailtpl_is_woocommerce_active() ) {
			do_action( 'mailtpl_before_woocommerce_settings', $wp_customize );
			$this->woocommerce_settings( $wp_customize );
			do_action( 'mailtpl_after_woocommerce_settings', $wp_customize );
		}

		mailtpl_show_postman_smtp_marketing( $wp_customize, true, 'mailtpl_postman_smtp_marketing' );
	}

	/**
	 * Including and registering customizer panels.
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function required_panels( $wp_customize ) {
		include_once MAILTPL_PLUGIN_DIR . 'includes/customize-panels/class-mailtpl-panels.php';
		$wp_customize->register_panel_type( 'Mailtpl_Panels' );
	}

	/**
	 * Including and registering customizer sections.
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function required_sections( $wp_customize ) {
		include_once MAILTPL_PLUGIN_DIR . 'includes/customize-sections/class-mailtpl-sections.php';
		include_once MAILTPL_PLUGIN_DIR . 'includes/customize-sections/class-mailtpl-branding-section.php';
		$wp_customize->register_section_type( 'Mailtpl_Sections' );
		$wp_customize->register_section_type( 'Mailtpl_Branding_Section' );
	}

	/**
	 * Including and registering customizer controls.
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function required_controls( $wp_customize ) {
		include_once MAILTPL_PLUGIN_DIR . 'includes/customize-controls/class-mailtpl-send-mail-customize-control.php';
		include_once MAILTPL_PLUGIN_DIR . 'includes/customize-controls/class-mailtpl-range-control.php';
		include_once MAILTPL_PLUGIN_DIR . 'includes/customize-controls/class-mailtpl-select-template-button-control.php';
		include_once MAILTPL_PLUGIN_DIR . 'includes/customize-controls/class-mailtpl-toggle-switch-control.php';
		include_once MAILTPL_PLUGIN_DIR . 'includes/customize-controls/class-mailtpl-branding-control.php';
		/**
		 * // include_once MAILTPL_PLUGIN_DIR . '/includes/customize-controls/class-mailtpl-font-size-customize-control.php';
		 */

		$wp_customize->register_control_type( 'Mailtpl_Range_Control' );
		$wp_customize->register_control_type( 'Mailtpl_Send_Mail_Customize_Control' );
		$wp_customize->register_control_type( 'Mailtpl_Select_Template_Button_Control' );
		$wp_customize->register_control_type( 'Mailtpl_Toggle_Switch_Control' );
		$wp_customize->register_control_type( 'Mailtpl_Branding_Control' );
		/**
		 * // $wp_customize->register_control_type( 'Mailtpl_Font_Size_Customize_Control' );.
		 */
	}

	/**
	 * Remover other sections
	 *
	 * @param mixed $active Array of active sections.
	 * @param array $section Array of sections.
	 *
	 * @return bool
	 */
	public function remove_other_sections( $active, $section ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['mailtpl_display'] ) ) {
			$sections = apply_filters(
				'mailtpl_customizer_sections',
				array(
					'section_mailtpl_footer',
					'section_mailtpl_template',
					'section_mailtpl_header',
					'section_mailtpl_body',
					'section_mailtpl_test',
					'mailtpl_postman_smtp_marketing',
					'section_mailtpl_settings',
					'mailtpl-woocommerce-order-items',
					'mailtpl-woocommerce-address',
					'mailtpl-woocommerce-button',
					'section-mailtpl-email-type',
					'mailtpl-woocommerce-heading-subtitle',
				)
			);
			if ( in_array( $section->id, $sections, true ) ) {
				return true;
			}
			return false;
		}
		return true;
	}

	/**
	 * Remover other panels
	 *
	 * @param array $active Array of active panels.
	 * @param array $panel Array of panels.
	 *
	 * @return bool
	 */
	public function remove_other_panels( $active, $panel ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['mailtpl_display'] ) ) {
			if ( 'mailtpl' === $panel->id || 'mailtpl-woocommerce-section' === $panel->id ) {
				return true;
			}
			return false;
		}
		return true;
	}

	/**
	 * Here we capture the page and show template accordingly
	 *
	 * @param string $template Array of templates.
	 *
	 * @return string
	 */
	public function capture_customizer_page( $template ) {
		// If WooCommerce is not active, force is_woo_mail=false
		if ( ! class_exists( 'WooCommerce' ) ) {
			$_GET['is_woo_mail'] = 'false';
		}
	
		if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'open-email-template' ) ) {
			if ( is_customize_preview() && isset( $_GET['mailtpl_display'] ) && ( 'true' === $_GET['mailtpl_display'] || true === $_GET['mailtpl_display'] ) ) {
				if ( isset( $_GET['is_woo_mail'] ) && 'false' === sanitize_text_field( wp_unslash( $_GET['is_woo_mail'] ) ) ) {
					return apply_filters( 'mailtpl_customizer_template', MAILTPL_PLUGIN_DIR . 'templates/default/default.php' );
				} else {
					return MAILTPL_PLUGIN_DIR . 'preview.php';
				}
			}
		}
		return $template;
	}
	

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'mailtpl-js', MAILTPL_PLUGIN_URL . '/admin/js/mailtpl-admin.js', array( 'customize-controls', 'jquery' ), $this->version, false );
		wp_localize_script(
			'mailtpl-js',
			'mailtpl_scripts_nonce',
			array(
				'_wpnonce' => wp_create_nonce( 'mailtpl-send-test-mail' ),
			)
		);
	}

	/**
	 * Enqueue scripts for preview area
	 *
	 * @since 1.0.0
	 */
	public function enqueue_template_scripts() {
		wp_enqueue_script( 'mailtpl-front-js', MAILTPL_PLUGIN_URL . 'assets/js/customizer/wp-customizer.js', array( 'jquery', 'customize-preview' ), $this->version, true );
		wp_enqueue_script( 'mailtpl-woocommerce-front-js', MAILTPL_PLUGIN_URL . 'assets/js/customizer/wc-customizer.js', array( 'jquery', 'customize-preview' ), $this->version, true );
		wp_enqueue_style( 'mailtpl-css', MAILTPL_PLUGIN_URL . '/admin/css/mailtpl-admin.css', '', $this->version, false );
		$sample_image = MAILTPL_PLUGIN_URL. 'assets/images/sample/sample-image.jpg';
		$localized_data = array(
			'sample_image' => isset($sample_image) ? $sample_image : '', 
		);
		wp_localize_script( 'mailtpl-woocommerce-front-js', 'localizedImage', $localized_data );
	
	}

	/**
	 * Template Section
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize manager.
	 */
	private function settings_section( $wp_customize ) {
		include_once MAILTPL_PLUGIN_DIR . 'includes/settings/settings-section.php';
	}

	/**
	 * Change template Section.
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function section_mailtpl_email_type( $wp_customize ) {
		$section = str_replace( '_', '-', __FUNCTION__ );

		$wp_customize->add_setting(
			'mailtpl_opts[open_template]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Select_Template_Button_Control(
				$wp_customize,
				'mailtpl_opts[open_template]',
				array(
					'label'       => esc_attr__( 'Open Template', 'email-templates' ),
					'description' => esc_html__( 'Click button to open the selected template.', 'email-templates' ),
					'type'        => 'mailtpl-select-template-button',
					'section'     => $section,
				)
			)
		);
	}

	/**
	 * Template Section
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function template_section( $wp_customize ) {
		include_once MAILTPL_PLUGIN_DIR . 'includes/settings/template-section.php';
	}

	/**
	 * Header section
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function header_section( $wp_customize ) {
		include_once MAILTPL_PLUGIN_DIR . 'includes/settings/header-section.php';
	}

	/**
	 * Body section
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function body_section( $wp_customize ) {
		include_once MAILTPL_PLUGIN_DIR . 'includes/settings/body-section.php';
	}

	/**
	 * Footer section
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize manager.
	 */
	private function footer_section( $wp_customize ) {
		include_once MAILTPL_PLUGIN_DIR . 'includes/settings/footer-section.php';
	}

	/**
	 * Send test email section
	 *
	 * @param Wp_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function test_section( $wp_customize ) {

		do_action( 'mailtpl_sections_test_before_content', $wp_customize );

		// image logo.
		$wp_customize->add_setting(
			'mailtpl_opts[send_mail]',
			array(
				'type'                 => 'option',
				'default'              => '',
				'transport'            => 'postMessage',
				'capability'           => 'edit_theme_options',
				'sanitize_callback'    => '',
				'sanitize_js_callback' => '',
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Send_Mail_Customize_Control(
				$wp_customize,
				'mailtpl_test',
				array(
					'label'       => __( 'Send Test Email', 'email-templates' ),
					'type'        => 'mailtpl-send-mail',
					'section'     => 'section_mailtpl_test',
					'settings'    => 'mailtpl_opts[send_mail]',
					'description' => __( 'Save the template and then click the button to send a test email to admin email ', 'email-templates' ) . get_bloginfo( 'admin_email' ),
				)
			)
		);

		// mailtpl_show_postman_smtp_marketing( $wp_customize, false, 'section_mailtpl_test' );

		do_action( 'mailtpl_sections_test_after_content', $wp_customize );
	}

	/**
	 * We let them use some safe html
	 *
	 * @param string $input to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_text( $input ) {
		return wp_kses_post( force_balance_tags( $input ) );
	}

	/**
	 * Sanitize aligment selects
	 *
	 * @param string $input to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_alignment( $input ) {
		$valid = array(
			'left',
			'right',
			'center',
		);

		if ( in_array( $input, $valid, true ) ) {
			return $input;
		} else {
			return '';
		}
	}

	/**
	 * Sanitize template select
	 *
	 * @param string $input to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_templates( $input ) {
		$valid = apply_filters(
			'mailtpl_template_choices',
			array(
				'boxed'     => 'Simple Theme',
				'fullwidth' => 'Fullwidth',
			)
		);

		if ( array_key_exists( $input, $valid ) ) {
			return $input;
		} else {
			return '';
		}
	}

	/**
	 * WooCommerce Settings sections.
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function woocommerce_settings( $wp_customize ) {
		if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'open-email-template' ) ) {
			if ( isset( $_GET['is_woo_mail'] ) && 'true' === sanitize_text_field( wp_unslash( $_GET['is_woo_mail'] ) ) ) {
				$wp_customize->add_panel(
					new Mailtpl_Panels(
						$wp_customize,
						'mailtpl-woocommerce-section',
						array(
							'title'    => esc_attr__( 'WooCommerce', 'email-templates' ),
							'priority' => 5,
							'panel'    => 'mailtpl',
							'type'     => 'mailtpl-panel',
						)
					)
				);
			}
		}
		$wp_customize->add_section(
			'mailtpl-woocommerce-heading-subtitle',
			array(
				'title' => esc_attr__( 'Subtitle Styles', 'email-templates' ),
				'panel' => 'mailtpl-woocommerce-section',
			)
		);
		$wp_customize->add_section(
			'mailtpl-woocommerce-order-items',
			array(
				'title' => esc_attr__( 'Order Items Styles', 'email-templates' ),
				'panel' => 'mailtpl-woocommerce-section',
			)
		);
		$wp_customize->add_section(
			'mailtpl-woocommerce-address',
			array(
				'title' => esc_attr__( 'Address Style', 'email-templates' ),
				'panel' => 'mailtpl-woocommerce-section',
			)
		);
		$wp_customize->add_section(
			'mailtpl-woocommerce-button',
			array(
				'title' => esc_attr__( 'Button Style', 'email-templates' ),
				'panel' => 'mailtpl-woocommerce-section',
			)
		);

		$this->mailtpl_woocommerce_heading_subtitle( $wp_customize );
		$this->mailtpl_woocommerce_order_items( $wp_customize );
		$this->mailtpl_woocommerce_address( $wp_customize );
		$this->mailtpl_woocommerce_button( $wp_customize );
	}

	/**
	 * Email Templates WooCommerce Button
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function mailtpl_woocommerce_heading_subtitle( $wp_customize ) {
		$section = str_replace( '_', '-', __FUNCTION__ );
		$woocommerce_email_types_with_product_item_table = array(
			'new_order'                 => array(
				'title' => __( 'New Order Subtitle', 'email-templates' ),
				'desc'  => __( 'This is the subtitle for the new order email', 'email-templates' ),
			),
			'cancelled_order'           => array(
				'title' => __( 'Cancelled order subtitle', 'email-templates' ),
				'desc'  => __( 'This is the subtitle for the cancelled order email', 'email-templates' ),
			),
			'customer_processing_order' => array(
				'title' => __( 'Processing order subtitle', 'email-templates' ),
				'desc'  => __( 'This is the subtitle for the processing order email', 'email-templates' ),
			),
			'customer_completed_order'  => array(
				'title' => __( 'Completed order subtitle', 'email-templates' ),
				'desc'  => __( 'This is the subtitle for the completed order email', 'email-templates' ),
			),
			'customer_refunded_order'   => array(
				'title' => __( 'Refunded order subtitle', 'email-templates' ),
				'desc'  => __( 'This is the subtitle for the refunded order email', 'email-templates' ),
			),
			'customer_on_hold_order'    => array(
				'title' => __( 'On hold order subtitle', 'email-templates' ),
				'desc'  => __( 'This is the subtitle for the on hold order email', 'email-templates' ),
			),
			'customer_invoice'          => array(
				'title' => __( 'Invoice subtitle', 'email-templates' ),
				'desc'  => __( 'This is the subtitle for the invoice email', 'email-templates' ),
			),
			'customer_note'             => array(
				'title' => __( 'Customer note subtitle', 'email-templates' ),
				'desc'  => __( 'This is the subtitle for the customer note email', 'email-templates' ),
			),
		);

		foreach ( $woocommerce_email_types_with_product_item_table as $email_type => $email_args ) :
			$wp_customize->add_setting(
				'mailtpl_opts[heading_' . esc_attr( $email_type ) . '_subtitle]',
				array(
					'transport'         => 'postMessage',
					'type'              => 'option',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
		endforeach;

		$wp_customize->add_setting(
			'mailtpl_opts[subtitle_placement]',
			array(
				'transport'         => 'refresh',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_text_field',
                'default'           => 'below',
			)
		);
		$wp_customize->add_setting(
			'mailtpl_opts[subtitle_font_size]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'absint',
                'default'           => 20,
			)
		);
		$wp_customize->add_setting(
			'mailtpl_opts[subtitle_font_weight]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'absint',
                'default'           => 900
			)
		);
		$wp_customize->add_setting(
			'mailtpl_opts[subtitle_font_family]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_text_field',
                'default'           => 'arial'
			)
		);
		$wp_customize->add_setting(
			'mailtpl_opts[subtitle_font_style]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_text_field',
                'default'           => 'normal'
			)
		);
		$wp_customize->add_setting(
			'mailtpl_opts[subtitle_text_color]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_hex_color',
                'default'           => '#ffffff'
			)
		);

		if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'open-email-template' ) ) :
			if ( isset( $_GET['email_type'] ) && isset( $woocommerce_email_types_with_product_item_table[ sanitize_text_field( wp_unslash( $_GET['email_type'] ) ) ] ) ) :
				$email_type = sanitize_text_field( wp_unslash( $_GET['email_type'] ) );
				$wp_customize->add_control(
					'mailtpl_opts[heading_' . $email_type . '_subtitle]',
					array(
						'label'       => $woocommerce_email_types_with_product_item_table[ $email_type ]['title'],
						'description' => $woocommerce_email_types_with_product_item_table[ $email_type ]['desc'],
						'section'     => $section,
						'type'        => 'text',
					)
				);

				$wp_customize->add_control(
					'mailtpl_opts[subtitle_placement]',
					array(
						'section'     => $section,
						'label'       => __( 'Subtitle Placement', 'email-templates' ),
						'description' => __( 'Choose where to place the subtitle', 'email-templates' ),
						'type'        => 'select',
						'choices'     => array(
							'above' => __( 'Above the title', 'email-templates' ),
							'below' => __( 'Below the title', 'email-templates' ),
						),
					)
				);
				$wp_customize->add_control(
					new Mailtpl_Range_Control(
						$wp_customize,
						'mailtpl_opts[subtitle_font_size]',
						array(
							'type'        => 'mailtpl-range-control',
							'section'     => $section,
							'label'       => __( 'Subtitle Font Size', 'email-templates' ),
							'description' => __( 'Choose the font size for the subtitle', 'email-templates' ),
							'input_attrs' => array(
								'min'  => 10,
								'max'  => 75,
								'step' => 1,
							),
						)
					)
				);
				$wp_customize->add_control(
					new Mailtpl_Range_Control(
						$wp_customize,
						'mailtpl_opts[subtitle_font_weight]',
						array(
							'type'        => 'mailtpl-range-control',
							'section'     => $section,
							'label'       => __( 'Subtitle Font Weight', 'email-templates' ),
							'description' => __( 'Choose the font weight for the subtitle', 'email-templates' ),
							'input_attrs' => array(
								'min'  => 100,
								'max'  => 900,
								'step' => 100,
							),
						)
					)
				);
				$wp_customize->add_control(
					'mailtpl_opts[subtitle_font_family]',
					array(
						'type'        => 'select',
						'section'     => $section,
						'label'       => __( 'Subtitle Font Family', 'email-templates' ),
						'description' => __( 'Choose the font family for the subtitle', 'email-templates' ),
						'choices'     => mailtpl_get_all_fonts(),
					)
				);
				$wp_customize->add_control(
					'mailtpl_opts[subtitle_font_style]',
					array(
						'type'        => 'select',
						'section'     => $section,
						'label'       => __( 'Subtitle Font Style', 'email-templates' ),
						'description' => __( 'Choose the font style for the subtitle', 'email-templates' ),
						'choices'     => array(
							'normal' => __( 'Normal', 'email-templates' ),
							'italic' => __( 'Italic', 'email-templates' ),
						),
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Color_Control(
						$wp_customize,
						'mailtpl_opts[subtitle_text_color]',
						array(
							'label'       => __( 'Subtitle Text Color', 'email-templates' ),
							'description' => __( 'Choose the text color for the subtitle', 'email-templates' ),
							'section'     => $section,
						)
					)
				);
			endif;

		endif;
	}

	/**
	 * Email Templates WooCommerce Order items
	 *
	 * @param WP_Customize_manager $wp_customize WP Customize manager.
	 */
	private function mailtpl_woocommerce_order_items( $wp_customize ) {
		$section = str_replace( '_', '-', __FUNCTION__ );

		$wp_customize->add_setting(
			'mailtpl_opts[order_items_style]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => mailtpl_get_options( 'order_items_style', 'normal' )
			)
		);
		$wp_customize->add_control(
			'mailtpl_opts[order_items_style]',
			array(
				'label'       => esc_attr__( 'Order Table Styles', 'email-templates' ),
				'description' => esc_attr__( 'Select order table styles', 'email-templates' ),
				'section'     => $section,
				'type'        => 'select',
				'choices'     => array(
					'normal' => esc_attr__( 'Normal', 'email-templates' ),
					'light'  => esc_attr__( 'Light', 'email-templates' ),
				),
			)
		);

		$wp_customize->add_setting(
			'mailtpl_opts[order_image_image]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => mailtpl_get_options( 'order_image_image', 'normal' )
			)
		);
		$wp_customize->add_control(
			'mailtpl_opts[order_image_image]',
			array(
				'label'       => esc_attr__( 'Product Image Option', 'email-templates' ),
				'description' => esc_html__( 'Order items image options', 'email-templates' ),
				'section'     => $section,
				'type'        => 'select',
				'choices'     => array(
					'normal' => esc_attr__( 'Do not show', 'email-templates' ),
					'show'   => esc_attr__( 'Show', 'email-templates' ),
				),
			)
		);

		$wp_customize->add_setting(
			'mailtpl_opts[order_items_image_size]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => mailtpl_get_options( 'order_items_image_size', '40x40' )
			)
		);
		$wp_customize->add_control(
			'mailtpl_opts[order_items_image_size]',
			array(
				'label'       => esc_attr__( 'Product Image Size', 'email-templates' ),
				'description' => esc_html__( 'Order items image size', 'email-templates' ),
				'type'        => 'select',
				'section'     => $section,
				'choices'     => array(
					'40x40'                 => esc_attr__( '40x40', 'email-templates' ),
					'50x50'                 => esc_attr__( '50x50', 'email-templates' ),
					'100x50'                => esc_attr__( '100x50', 'email-templates' ),
					'100x100'               => esc_attr__( '100x100', 'email-templates' ),
					'150x150'               => esc_attr__( '150x150', 'email-templates' ),
					'woocommerce_thumbnail' => esc_attr__( 'WooCommerce Thumbnail', 'email-templates' ),
				),
			)
		);

		$wp_customize->add_setting(
			'mailtpl_opts[items_table_background_color]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#ffffff'
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mailtpl_opts[items_table_background_color]',
				array(
					'label'       => esc_attr__( 'Order Table Background Color', 'email-templates' ),
					'description' => esc_html__( 'Change order table background color', 'email-templates' ),
					'section'     => $section,
				)
			),
		);

		$wp_customize->add_setting(
			'mailtpl_opts[items_table_background_odd_color]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#ffffff'
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mailtpl_opts[items_table_background_odd_color]',
				array(
					'label'       => esc_attr__( 'Order Table Background Odd Row Color', 'email-templates' ),
					'description' => esc_html__( 'Change order Table Background Odd Row Color', 'email-templates' ),
					'section'     => $section,
				)
			),
		);

		$wp_customize->add_setting(
			'mailtpl_opts[items_table_padding_top_bottom]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'              => mailtpl_get_options( 'items_table_padding_top_bottom', 0 ),
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[items_table_padding_top_bottom]',
				array(
					'label'       => esc_attr__( 'Padding Top/Bottom', 'email-templates' ),
					'description' => esc_html__( 'Adjust your top bottom padding', 'email-templates' ),
					'type'        => 'mailtpl-range-control',
					'section'     => $section,
					'input_attrs' => array(
						'min' => 0,
						'max' => 50,
					),
				)
			)
		);

		$wp_customize->add_setting(
			'mailtpl_opts[items_table_padding_left_right]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'              => mailtpl_get_options( 'items_table_padding_left_right', 0 ),
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[items_table_padding_left_right]',
				array(
					'label'       => esc_attr__( 'Padding Left/Right', 'email-templates' ),
					'description' => esc_html__( 'Adjust your left right padding', 'email-templates' ),
					'type'        => 'mailtpl-range-control',
					'section'     => $section,
					'input_attrs' => array(
						'min' => 0,
						'max' => 50,
					),
				)
			)
		);

		$wp_customize->add_setting(
			'mailtpl_opts[items_table_border_width]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'              => mailtpl_get_options( 'items_table_border_width', 0 ),

				
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[items_table_border_width]',
				array(
					'label'       => esc_attr__( 'Border Width', 'email-templates' ),
					'description' => esc_html__( 'Adjust your border width', 'email-templates' ),
					'type'        => 'mailtpl-range-control',
					'section'     => $section,
					'input_attrs' => array(
						'min' => 0,
						'max' => 10,
					),
				)
			)
		);

		$wp_customize->add_setting(
			'mailtpl_opts[items_table_border_color]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#e0e0e0'
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mailtpl_opts[items_table_border_color]',
				array(
					'label'       => esc_attr__( 'Order Table Border Color', 'email-templates' ),
					'description' => esc_html__( 'Change order table border color', 'email-templates' ),
					'section'     => $section,
				)
			),
		);

		// uzair
		$wp_customize->add_setting(
			'mailtpl_opts[items_table_border_style]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => mailtpl_get_options( 'items_table_border_style', 'solid' )
			)
		);
		$wp_customize->add_control(
			'mailtpl_opts[items_table_border_style]',
			array(
				'label'       => esc_attr__( 'Border Style', 'email-templates' ),
				'description' => esc_attr__( 'Change order items border style', 'email-templates' ),
				'section'     => $section,
				'type'        => 'select',
				'choices'     => array(
					'solid'  => esc_attr__( 'Solid', 'email-templates' ),
					'double' => esc_attr__( 'Double', 'email-templates' ),
					'groove' => esc_attr__( 'Groove', 'email-templates' ),
					'dotted' => esc_attr__( 'Dotted', 'email-templates' ),
					'dashed' => esc_attr__( 'Dashed', 'email-templates' ),
					'ridge'  => esc_attr__( 'Ridge', 'email-templates' ),
				),
			)
		);

		$wp_customize->add_setting(
			'mailtpl_opts[order_heading_style]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => mailtpl_get_options( 'order_heading_style', 'split' )
			)
		);
		$wp_customize->add_control(
			'mailtpl_opts[order_heading_style]',
			array(
				'label'       => esc_attr__( 'Order Table Heading Style', 'email-templates' ),
				'description' => esc_attr__( 'Change order table heading style', 'email-templates' ),
				'section'     => $section,
				'type'        => 'select',
				'choices'     => array(
					'split'  => esc_attr__( 'Split', 'email-templates' ),
					'normal' => esc_attr__( 'Normal', 'email-templates' ),
				),
			)
		);

		$wp_customize->add_setting(
			'mailtpl_opts[notes_outside_table]',
			array(
				'transport'         => 'postMessage',
				'type'              => 'option',
				'sanitize_callback' => 'sanitize_text_field',
				'default' => false
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Toggle_Switch_Control(
				$wp_customize,
				'mailtpl_opts[notes_outside_table]',
				array(
					'label'       => esc_attr__( 'Enable Order Notes To Be Moved Below', 'email-templates' ),
					'description' => '',
					'section'     => $section,
					'type'        => 'mailtpl-toggle-switch-control',
				)
			)
		);
	}

	/**
	 * Email Templates WooCommerce Address.
	 *
	 * @param WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function mailtpl_woocommerce_address( $wp_customize ) {
		$section = str_replace( '_', '-', __FUNCTION__ );

		// Background Color.
		$wp_customize->add_setting(
			'mailtpl_opts[address_box_background_color]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				 'default'           => '#ffffff'
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mailtpl_opts[address_box_background_color]',
				array(
					'type'    => 'color',
					'label'   => esc_attr__( 'Address Box Background Color', 'email-templates' ),
					'section' => $section,
				)
			)
		);

		// Box padding.
		$wp_customize->add_setting(
			'mailtpl_opts[address_box_padding]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'              => mailtpl_get_options( 'address_box_padding', 0 ),
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[address_box_padding]',
				array(
					'type'        => 'mailtpl-range-control',
					'label'       => esc_attr__( 'Address Box Padding', 'email-templates' ),
					'section'     => $section,
					'input_attrs' => array(
						'min' => 0,
						'max' => 100,
					),
				)
			)
		);

		// Box border width
		$wp_customize->add_setting(
			'mailtpl_opts[address_box_border_width]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'              => mailtpl_get_options( 'address_box_border_width', 0 ),
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[address_box_border_width]',
				array(
					'type'        => 'mailtpl-range-control',
					'label'       => esc_attr__( 'Address Box Border Width', 'email-templates' ),
					'section'     => $section,
					'input_attrs' => array(
						'min' => 0,
						'max' => 10,
					),
				)
			)
		);

		// Box border color
		$wp_customize->add_setting(
			'mailtpl_opts[address_box_border_color]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#ffffff' 
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mailtpl_opts[address_box_border_color]',
				array(
					'type'    => 'color',
					'label'   => esc_attr__( 'Address Box Border Color', 'email-templates' ),
					'section' => $section,
				)
			)
		);

		// Box border style
		$wp_customize->add_setting(
			'mailtpl_opts[address_box_border_style]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'mailtpl_opts[address_box_border_style]',
			array(
				'label'   => esc_attr__( 'Address Box Border Style', 'email-templates' ),
				'type'    => 'select',
				'section' => $section,
				'choices' => array(
					'solid'  => esc_attr__( 'Solid', 'email-templates' ),
					'ridge'  => esc_attr__( 'Ridge', 'email-templates' ),
					'double' => esc_attr__( 'Double', 'email-templates' ),
					'groove' => esc_attr__( 'Groove', 'email-templates' ),
					'dotted' => esc_attr__( 'Dotted', 'email-templates' ),
					'dashed' => esc_attr__( 'Dashed', 'email-templates' ),
				),
			)
		);

		// Box text color
		$wp_customize->add_setting(
			'mailtpl_opts[address_box_text_color]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#131313'
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mailtpl_opts[address_box_text_color]',
				array(
					'type'    => 'color',
					'label'   => esc_attr__( 'Address Box Text Color', 'email-templates' ),
					'section' => $section,
				),
			)
		);

		// Box text Color
		$wp_customize->add_setting(
			'mailtpl_opts[address_box_text_align]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => 'left'
			)
		);
		$wp_customize->add_control(
			'mailtpl_opts[address_box_text_align]',
			array(
				'type'    => 'select',
				'label'   => esc_attr__( 'Address Box Text Align', 'email-templates' ),
				'section' => $section,
				'choices' => array(
					'left'   => esc_attr__( 'Left', 'email-templates' ),
					'center' => esc_attr__( 'Center', 'email-templates' ),
					'right'  => esc_attr__( 'Right', 'email-templates' ),
				),
			)
		);
	}

	/**
	 * Email Templates WooCommerce Buttons
	 *
	 * @param  WP_Customize_Manager $wp_customize WP Customize Manager.
	 */
	private function mailtpl_woocommerce_button( $wp_customize ) {
		$section = str_replace( '_', '-', __FUNCTION__ );

		// $wp_customize->add_setting(
		// 	'mailtpl_opts[enable_buttons]',
		// 	array(
		// 		'type'      => 'option',
		// 		'transport' => 'refresh',
		// 	)
		// );
		// $wp_customize->add_control(
		// 	new Mailtpl_Toggle_Switch_Control(
		// 		$wp_customize,
		// 		'mailtpl_opts[enable_buttons]',
		// 		array(
		// 			'type'    => 'mailtpl-toggle-switch-control',
		// 			'label'   => esc_attr__( 'Enable Buttons', 'email-templates' ),
		// 			'section' => $section,
					
		// 		)
		// 	)
		// );

		// Button text color.
		$wp_customize->add_setting(
			'mailtpl_opts[button_text_color]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#131313'
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mailtpl_opts[button_text_color]',
				array(
					'type'    => 'color',
					'label'   => esc_attr__( 'Button Text Color', 'email-templates' ),
					'section' => $section,
				)
			)
		);

		// Button font size.
		$wp_customize->add_setting(
			'mailtpl_opts[button_font_size]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'              => mailtpl_get_options( 'button_font_size', 18 ),

			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[button_font_size]',
				array(
					'type'        => 'mailtpl-range-control',
					'label'       => esc_attr__( 'Button Font Size', 'email-templates' ),
					'section'     => $section,
					'input_attrs' => array(
						'min'  => 8,
						'max'  => 30,
						'step' => 1,
					),
				)
			)
		);

		// Button font family.
		$wp_customize->add_setting(
			'mailtpl_opts[button_font_family]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'mailtpl_opts[button_font_family]',
			array(
				'type'    => 'select',
				'label'   => esc_attr__( 'Button Font Family', 'email-templates' ),
				'section' => $section,
				'choices' => mailtpl_get_all_fonts(),
			)
		);

		// Button font weight.
		$wp_customize->add_setting(
			'mailtpl_opts[button_font_weight]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'           => mailtpl_get_options( 'button_font_weight', 100 )

			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[button_font_weight]',
				array(
					'type'        => 'mailtpl-range-control',
					'label'       => esc_attr__( 'Button Font Weight', 'email-templates' ),
					'section'     => $section,
					'input_attrs' => array(
						'min'  => 100,
						'max'  => 900,
						'step' => 100,
					),
				)
			)
		);

		// Button background color.
		$wp_customize->add_setting(
			'mailtpl_opts[button_background_color]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#ffffff'
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mailtpl_opts[button_background_color]',
				array(
					'type'    => 'color',
					'label'   => esc_attr__( 'Button Background Color', 'email-templates' ),
					'section' => $section,
				)
			)
		);

		// Button padding top and bottom.
		$wp_customize->add_setting(
			'mailtpl_opts[button_padding_top_bottom]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'           => mailtpl_get_options( 'button_padding_top_bottom', 5 )
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[button_padding_top_bottom]',
				array(
					'type'        => 'mailtpl-range-control',
					'label'       => esc_attr__( 'Button Padding Top and Bottom', 'email-templates' ),
					'section'     => $section,
					'input_attrs' => array(
						'min'  => 5,
						'max'  => 50,
						'step' => 1,
					),
				)
			)
		);

		// Button padding left and right.
		$wp_customize->add_setting(
			'mailtpl_opts[button_padding_left_right]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'           => mailtpl_get_options( 'button_padding_left_right', 5 )
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[button_padding_left_right]',
				array(
					'type'        => 'mailtpl-range-control',
					'label'       => esc_attr__( 'Button Padding Left and Right', 'email-templates' ),
					'section'     => $section,
					'input_attrs' => array(
						'min'  => 5,
						'max'  => 50,
						'step' => 1,
					),
				)
			)
		);

		// Button border radius.
		$wp_customize->add_setting(
			'mailtpl_opts[button_border_radius]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'           => mailtpl_get_options( 'button_border_radius', 0 )
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[button_border_radius]',
				array(
					'type'        => 'mailtpl-range-control',
					'label'       => esc_attr__( 'Button Border Radius', 'email-templates' ),
					'section'     => $section,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				)
			)
		);

		// Button border width.
		$wp_customize->add_setting(
			'mailtpl_opts[button_border_width]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'default'           => mailtpl_get_options( 'button_border_width', 0 )
			)
		);
		$wp_customize->add_control(
			new Mailtpl_Range_Control(
				$wp_customize,
				'mailtpl_opts[button_border_width]',
				array(
					'type'        => 'mailtpl-range-control',
					'label'       => esc_attr__( 'Button Border Width', 'email-templates' ),
					'section'     => $section,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),
				)
			)
		);

		// Button border color.
		$wp_customize->add_setting(
			'mailtpl_opts[button_border_color]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#ffffff'
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mailtpl_opts[button_border_color]',
				array(
					'type'    => 'color',
					'label'   => esc_attr__( 'Button Border Color', 'email-templates' ),
					'section' => $section,
				)
			)
		);

		// Button border style.
		$wp_customize->add_setting(
			'mailtpl_opts[button_border_style]',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'mailtpl_opts[button_border_style]',
			array(
				'type'    => 'select',
				'label'   => esc_attr__( 'Button Border Style', 'email-templates' ),
				'section' => $section,
				'choices' => array(
					'solid'  => esc_attr__( 'Solid', 'email-templates' ),
					'dashed' => esc_attr__( 'Dashed', 'email-templates' ),
					'dotted' => esc_attr__( 'Dotted', 'email-templates' ),
					'double' => esc_attr__( 'Double', 'email-templates' ),
					'groove' => esc_attr__( 'Groove', 'email-templates' ),
					'ridge'  => esc_attr__( 'Ridge', 'email-templates' ),
					'inset'  => esc_attr__( 'Inset', 'email-templates' ),
					'outset' => esc_attr__( 'Outset', 'email-templates' ),
				),
			)
		);
	}
}
