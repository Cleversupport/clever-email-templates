<?php
/**
 * Footer settings section
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

do_action( 'mailtpl_sections_footer_before_content', $wp_customize );

// Footer placement.
$wp_customize->add_setting(
	'mailtpl_opts[footer_placement]',
	array(
		'type'              => 'option',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'inside',
	)
);
$wp_customize->add_control(
	'mailtpl_opts[footer_placement]',
	array(
		'type'        => 'select',
		'label'       => esc_attr__( 'Footer Placement', 'email-templates' ),
		'description' => esc_attr__( 'Footer placement', 'email-templates' ),
		'section'     => 'section_mailtpl_footer',
		'choices'     => array(
			'outside' => esc_attr__( 'Outside Body', 'email-templates' ),
			'inside'  => esc_attr__( 'Inside Body', 'email-templates' ),
		),
	)
);

// Footer padding top.
$wp_customize->add_setting(
	'mailtpl_opts[footer_padding_top]',
	array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'default'           => 15,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[footer_padding_top]',
		array(
			'label'       => esc_attr__( 'Padding Top', 'email-templates' ),
			'description' => esc_html__( 'Adjust your footer Top padding', 'email-templates' ),
			'section'     => 'section_mailtpl_footer',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// Footer padding bottom.
$wp_customize->add_setting(
	'mailtpl_opts[footer_padding_bottom]',
	array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'default'           => 15,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[footer_padding_bottom]',
		array(
			'label'       => esc_attr__( 'Padding Bottom', 'email-templates' ),
			'description' => esc_html__( 'Adjust your footer Bottom padding', 'email-templates' ),
			'section'     => 'section_mailtpl_footer',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// Footer padding Left/Right.
$wp_customize->add_setting(
	'mailtpl_opts[footer_padding_left_right]',
	array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'default'           => 15,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[footer_padding_left_right]',
		array(
			'label'       => esc_attr__( 'Padding Left/Right', 'email-templates' ),
			'description' => esc_html__( 'Adjust your footer Left/Right padding', 'email-templates' ),
			'section'     => 'section_mailtpl_footer',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// footer alignment.
$wp_customize->add_setting(
	'mailtpl_opts[footer_aligment]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['footer_aligment'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => array( $this, 'sanitize_alignment' ),
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'mailtpl_footer_aligment',
		array(
			'label'       => __( 'Alignment', 'email-templates' ),
			'type'        => 'select',
			'default'     => 'center',
			'choices'     => array(
				'left'   => 'Left',
				'center' => 'Center',
				'right'  => 'Right',
			),
			'section'     => 'section_mailtpl_footer',
			'settings'    => 'mailtpl_opts[footer_aligment]',
			'description' => __( 'Choose alignment for footer', 'email-templates' ),
		)
	)
);

// text size.
$wp_customize->add_setting(
	'mailtpl_opts[footer_text_size]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['footer_text_size'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => array( $this, 'sanitize_text' ),
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_footer_text_size',
		array(
			'label'       => __( 'Text Size', 'email-templates' ),
			'type'        => 'mailtpl-range-control',
			'section'     => 'section_mailtpl_footer',
			'settings'    => 'mailtpl_opts[footer_text_size]',
			'description' => __( 'Slide to change text size', 'email-templates' ),
			'input_attrs' => array(
				'min' => 1,
				'max' => 100,
			),
		)
	)
);

// Footer font style.
$wp_customize->add_setting(
	'mailtpl_opts[footer_font_style]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'arial',
	),
);
$wp_customize->add_control(
	'mailtpl_opts[footer_font_style]',
	array(
		'type'        => 'select',
		'label'       => esc_attr__( 'Font Family', 'email-templates' ),
		'description' => esc_html__( 'Change your footer font family', 'email-templates' ),
		'section'     => 'section_mailtpl_footer',
		'choices'     => mailtpl_get_all_fonts(),
	),
);

// Footer font weight.
$wp_customize->add_setting(
	'mailtpl_opts[footer_font_weight]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 100,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[footer_font_weight]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Font Weight', 'email-templates' ),
			'description' => esc_html__( 'Change your footer font weight', 'email-templates' ),
			'section'     => 'section_mailtpl_footer',
			'input_attrs' => array(
				'min'  => 100,
				'max'  => 900,
				'step' => 100,
			),
		),
	)
);

// text color.
$wp_customize->add_setting(
	'mailtpl_opts[footer_text_color]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['footer_text_color'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_footer_text_color',
		array(
			'label'       => __( 'Text Color', 'email-templates' ),
			'section'     => 'section_mailtpl_footer',
			'settings'    => 'mailtpl_opts[footer_text_color]',
			'description' => __( 'Choose footer text color', 'email-templates' ),
		)
	)
);

// Footer text padding top.
$wp_customize->add_setting(
	'mailtpl_opts[footer_text_padding_top]',
	array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'default'           => 0,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[footer_text_padding_top]',
		array(
			'label'       => esc_attr__( 'Footer Text Padding Top', 'email-templates' ),
			'description' => esc_html__( 'Adjust your footer text Top padding', 'email-templates' ),
			'section'     => 'section_mailtpl_footer',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// Footer text padding bottom.
$wp_customize->add_setting(
	'mailtpl_opts[footer_text_padding_bottom]',
	array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'type'              => 'option',
		'default'           => 0,
	),
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[footer_text_padding_bottom]',
		array(
			'label'       => esc_attr__( 'Footer Text Padding Bottom', 'email-templates' ),
			'description' => esc_html__( 'Adjust your footer text Bottom padding', 'email-templates' ),
			'section'     => 'section_mailtpl_footer',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

$wp_customize->add_setting(
	'mailtpl_opts[footer_text]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['footer_text'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => array( $this, 'sanitize_text' ),
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'mailtpl_footer',
		array(
			'label'       => __( 'Footer Text', 'email-templates' ),
			'type'        => 'textarea',
			'section'     => 'section_mailtpl_footer',
			'settings'    => 'mailtpl_opts[footer_text]',
			'description' => __( 'Change the email footer here', 'email-templates' ),
		)
	)
);

// background color.
$wp_customize->add_setting(
	'mailtpl_opts[footer_bg]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['footer_bg'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_footer_bg',
		array(
			'label'       => __( 'Background Color', 'email-templates' ),
			'section'     => 'section_mailtpl_footer',
			'settings'    => 'mailtpl_opts[footer_bg]',
			'description' => __( 'Choose footer background color', 'email-templates' ),
		)
	)
);

// Powered by.
$wp_customize->add_setting(
	'mailtpl_opts[footer_powered_by]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['footer_powered_by'],
		'transport'            => 'refresh',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => '',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'mailtpl_footer_powered_by',
		array(
			'label'       => __( 'Powered By', 'email-templates' ),
			'section'     => 'section_mailtpl_footer',
			'settings'    => 'mailtpl_opts[footer_powered_by]',
			'type'        => 'select',
			'choices'     => array(
				'off' => 'Off',
				'on'  => 'On',
			),
			'description' => __( 'Display a tiny link to the plugin page', 'email-templates' ),
		)
	)
);
do_action( 'mailtpl_sections_footer_after_content', $wp_customize );
