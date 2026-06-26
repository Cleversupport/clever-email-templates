<?php
/**
 * Body settings section
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

do_action( 'mailtpl_sections_body_before_content', $wp_customize );

// Body Text.
function get_customized_email_types() {
	$types = array(
		'new_order'                 => __( 'New Order', 'email-templates' ),
		'cancelled_order'           => __( 'Cancelled Order', 'email-templates' ),
		'customer_processing_order' => __( 'Customer Processing Order', 'email-templates' ),
		'customer_completed_order'  => __( 'Customer Completed Order', 'email-templates' ),
		'customer_refunded_order'   => __( 'Customer Refunded Order', 'email-templates' ),
		'customer_on_hold_order'    => __( 'Customer On Hold Order', 'email-templates' ),
		'customer_invoice'          => __( 'Customer Invoice', 'email-templates' ),
		'failed_order'              => __( 'Failed Order', 'email-templates' ),
		'customer_new_account'      => __( 'Customer New Account', 'email-templates' ),
		'customer_note'             => __( 'Customer Note', 'email-templates' ),
		'customer_reset_password'   => __( 'Customer Reset Password', 'email-templates' ),
	);

	return $types;
}

// Check if WooCommerce is active before attempting to use WooCommerce-specific classes
if (class_exists('WooCommerce') && class_exists('Mailtpl_Woomail_Settings')) {
    foreach (get_customized_email_types() as $key => $value) {
        // Skip generic body control for customer_refunded_order as it has separate full and partial controls
        if ($key === 'customer_refunded_order') {
            continue;
        }
        $wp_customize->add_setting(
            new WP_Customize_Setting(
                $wp_customize,
                'mailtpl_woomail[' . $key . '_body]',
                array(
                    'type'          => 'option',
                    'transport'     => 'refresh',
                    'default'       => Mailtpl_Woomail_Settings::get_default_value($key . '_body'),
                )
            )
        );

        if (isset($_GET['email_type']) && !empty($_GET['email_type']) && ($_GET['email_type'] == $key)) {
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'mailtpl_woomail[' . $key . '_body]',
                    array(
                        'label'             => __('Body Text', 'email-templates'),
                        'description'       => __('Write a custom body text', 'email-templates'),
                        'settings'          => 'mailtpl_woomail[' . $key . '_body]',
                        'priority'          => 10,
                        'section'           => 'section_mailtpl_body',
                        'type'              => 'textarea',
                    )
                )
            );
        }
    }

    // Special handling for customer_refunded_order with separate full and partial body controls
    $key = 'customer_refunded_order';
    $wp_customize->add_setting(
        new WP_Customize_Setting(
            $wp_customize,
            'mailtpl_woomail[' . $key . '_body_full]',
            array(
                'type'          => 'option',
                'transport'     => 'refresh',
                'default'       => Mailtpl_Woomail_Settings::get_default_value($key . '_body_full'),
            )
        )
    );
    $wp_customize->add_setting(
        new WP_Customize_Setting(
            $wp_customize,
            'mailtpl_woomail[' . $key . '_body_partial]',
            array(
                'type'          => 'option',
                'transport'     => 'refresh',
                'default'       => Mailtpl_Woomail_Settings::get_default_value($key . '_body_partial'),
            )
        )
    );

    if (isset($_GET['email_type']) && !empty($_GET['email_type']) && ($_GET['email_type'] == $key)) {
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'mailtpl_woomail[' . $key . '_body_full]',
                array(
                    'label'             => __('Full Refund Body Text', 'email-templates'),
                    'description'       => __('Write a custom body text for full refunds', 'email-templates'),
                    'settings'          => 'mailtpl_woomail[' . $key . '_body_full]',
                    'priority'          => 10,
                    'section'           => 'section_mailtpl_body',
                    'type'              => 'textarea',
                )
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'mailtpl_woomail[' . $key . '_body_partial]',
                array(
                    'label'             => __('Partial Refund Body Text', 'email-templates'),
                    'description'       => __('Write a custom body text for partial refunds', 'email-templates'),
                    'settings'          => 'mailtpl_woomail[' . $key . '_body_partial]',
                    'priority'          => 11,
                    'section'           => 'section_mailtpl_body',
                    'type'              => 'textarea',
                )
            )
        );
    }
}

// background color.
$wp_customize->add_setting(
	'mailtpl_opts[email_body_bg]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['email_body_bg'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_email_body_bg',
		array(
			'label'       => __( 'Background Color', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'settings'    => 'mailtpl_opts[email_body_bg]',
			'description' => __( 'Choose email body background color', 'email-templates' ),
		)
	)
);
// text size.
$wp_customize->add_setting(
	'mailtpl_opts[body_text_size]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['body_text_size'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => array( $this, 'sanitize_text' ),
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_body_text_size',
		array(
			'label'       => __( 'Text Size', 'email-templates' ),
			'type'        => 'mailtpl-range-control',
			'section'     => 'section_mailtpl_body',
			'settings'    => 'mailtpl_opts[body_text_size]',
			'description' => __( 'Slide to change text size', 'email-templates' ),
			'input_attrs' => array(
				'min' => 1,
				'max' => 100,
			),
		)
	)
);

// text color.
$wp_customize->add_setting(
	'mailtpl_opts[body_text_color]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['body_text_color'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_body_text_color',
		array(
			'label'       => __( 'Text Color', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'settings'    => 'mailtpl_opts[body_text_color]',
			'description' => __( 'Choose body text color', 'email-templates' ),
		)
	)
);

// Links color.
$wp_customize->add_setting(
	'mailtpl_opts[body_href_color]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['body_href_color'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_body_href_color',
		array(
			'label'       => __( 'Links Color', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'settings'    => 'mailtpl_opts[body_href_color]',
			'description' => __( 'Choose links color', 'email-templates' ),
		)
	)
);

// Padding top.
$wp_customize->add_setting(
	'mailtpl_opts[body_padding_top]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'default'           => 20,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[body_padding_top]',
		array(
			'label'       => esc_attr__( 'Padding Top', 'email-templates' ),
			'description' => esc_html__( 'Adjust your top padding', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// Padding bottom.
$wp_customize->add_setting(
	'mailtpl_opts[body_padding_bottom]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'default'           => 20,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[body_padding_bottom]',
		array(
			'label'       => esc_attr__( 'Padding Bottom', 'email-templates' ),
			'description' => esc_html__( 'Adjust your bottom padding', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// padding Left/Right.
$wp_customize->add_setting(
	'mailtpl_opts[body_padding_left_right]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'default'           => 15,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[body_padding_left_right]',
		array(
			'label'       => esc_attr__( 'Padding Left/Right', 'email-templates' ),
			'description' => esc_html__( 'Adjust your Left/Right padding', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// Body font weight.
$wp_customize->add_setting(
	'mailtpl_opts[body_font_weight]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 100,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[body_font_weight]',
		array(
			'label'       => esc_attr__( 'Font Weight', 'email-templates' ),
			'description' => esc_html__( 'Select font weight', 'email-templates' ),
			'section'     => 'section_mailtpl_body',
			'type'        => 'mailtpl-range-control',
			'input_attrs' => array(
				'min'  => 100,
				'max'  => 900,
				'step' => 100,
			),
		)
	)
);

// Font style.
$wp_customize->add_setting(
	'mailtpl_opts[body_font_family]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'arial',
	),
);
$wp_customize->add_control(
	'mailtpl_opts[body_font_family]',
	array(
		'label'       => esc_attr__( 'Font Family', 'email-templates' ),
		'description' => esc_html__( 'Select font family for your body text', 'email-templates' ),
		'section'     => 'section_mailtpl_body',
		'type'        => 'select',
		'choices'     => mailtpl_get_all_fonts(),
	)
);

do_action( 'mailtpl_sections_body_after_content', $wp_customize );
