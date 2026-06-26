<?php
/**
 * Header section settings
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

do_action( 'mailtpl_sections_header_before_content', $wp_customize );

// Image logo.
$wp_customize->add_setting(
	'mailtpl_opts[header_logo]',
	array(
		'type'                 => 'option',
		'default'              => mailtpl_get_options( 'header_logo', '' ),
		'transport'            => 'refresh',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => '',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize,
		'mailtpl_header',
		array(
			'label'       => __( 'Logo Image', 'email-templates' ),
			'type'        => 'image',
			'section'     => 'section_mailtpl_header',
			'settings'    => 'mailtpl_opts[header_logo]',
			'description' => __( 'Add an image to use in header. Leave empty to use text instead', 'email-templates' ),
		)
	)
);

// Image location.
$wp_customize->add_setting(
	'mailtpl_opts[header_logo_location]',
	array(
		'type'                 => 'option',
		'default'              => mailtpl_get_options( 'header_logo_location', 'inside' ),
		'transport'            => 'refresh',
		'sanitize_callback'    => '',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	'mailtpl_opts[header_logo_location]',
	array(
		'type'        => 'select',
		'label'       => esc_attr__( 'Header Image Location', 'email-templates' ),
		'description' => esc_html__( 'Header image location want to display inside body or out side body.', 'email-templates' ),
		'section'     => 'section_mailtpl_header',
		'choices'     => array(
			'outside' => esc_attr__( 'Outside Body Container', 'email-templates' ),
			'inside'  => esc_attr__( 'Inside Body Container', 'email-templates' ),
		),
	)
);

// Image alignment.
$wp_customize->add_setting(
	'mailtpl_opts[header_image_alignment]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => mailtpl_get_options( 'header_image_alignment', 'center' ),
	)
);
$wp_customize->add_control(
	'mailtpl_opts[header_image_alignment]',
	array(
		'label'       => esc_attr__( 'Image Alignment', 'email-templates' ),
		'description' => esc_attr__( 'Adjust image alignment', 'email-templates' ),
		'type'        => 'select',
		'section'     => 'section_mailtpl_header',
		'choices'     => array(
			'center' => esc_attr__( 'Center', 'email-templates' ),
			'right'  => esc_attr__( 'Right', 'email-templates' ),
			'left'   => esc_attr__( 'Left', 'email-templates' ),
		),
	),
);

// Image width control.
$wp_customize->add_setting(
	'mailtpl_opts[image_width_control]',
	array(
		'type'                 => 'option',
		'default'              => mailtpl_get_options( 'image_width_control', 15 ),
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => '',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[image_width_control]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Image Max Width', 'email-templates' ),
			'description' => esc_html__( 'Adjust your Logo Width', 'email-templates' ),
			'section'     => 'section_mailtpl_header',
			'input_attrs' => array(
				'min' => 10,
				'max' => 1200,
			),
		)
	),
);

// Image background color.
$wp_customize->add_setting(
	'mailtpl_opts[header_image_bg]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'default'           => mailtpl_get_options( 'header_image_bg', '#454545' )
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_opts[header_image_bg]',
		array(
			'label'       => __( 'Image Container Background Color', 'email-templates' ),
			'section'     => 'section_mailtpl_header',
			'description' => __( 'Choose header image background color', 'email-templates' ),
		)
	)
);

// Image padding top/bottom.
$wp_customize->add_setting(
	'mailtpl_opts[header_image_padding_top_bottom]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'header_image_padding_top_bottom', 15 )
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[header_image_padding_top_bottom]',
		array(
			'label'       => esc_attr__( 'Image Container Padding Top/Bottom', 'email-templates' ),
			'description' => esc_html__( 'Adjust header image padding Top/Bottom', 'email-templates' ),
			'section'     => 'section_mailtpl_header',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// Header text.
$wp_customize->add_setting(
	'mailtpl_opts[header_logo_text]',
	array(
		'type'                 => 'option',
		'default'              => get_bloginfo( 'name' ),
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => array( $this, 'sanitize_text' ),
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'mailtpl_header_logo_text',
		array(
			'label'       => __( 'Logo Text', 'email-templates' ),
			'type'        => 'textarea',
			'section'     => 'section_mailtpl_header',
			'settings'    => 'mailtpl_opts[header_logo_text]',
			'description' => __( 'Add text to your mail header. Used for alt text when Image it\'s used', 'email-templates' ),
		)
	)
);

// header font size.
$wp_customize->add_setting(
	'mailtpl_opts[header_text_size]',
	array(
		'type'                 => 'option',
		'default'              => mailtpl_get_options( 'header_text_size', $this->defaults['header_text_size'] ),
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => array( $this, 'sanitize_text' ),
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_header_text_size',
		array(
			'label'       => __( 'Text Size', 'email-templates' ),
			'type'        => 'mailtpl-range-control',
			'section'     => 'section_mailtpl_header',
			'settings'    => 'mailtpl_opts[header_text_size]',
			'description' => __( 'Slide to change text size', 'email-templates' ),
			'input_attrs' => array(
				'min' => 1,
				'max' => 100,
			),
		)
	)
);

// header alignment.
$wp_customize->add_setting(
	'mailtpl_opts[header_text_aligment]',
	array(
		'type'                 => 'option',
		'default'              => mailtpl_get_options( 'header_text_aligment', $this->defaults['header_aligment'] ),
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => array( $this, 'sanitize_alignment' ),
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'mailtpl_text_aligment',
		array(
			'label'       => __( 'Alignment', 'email-templates' ),
			'type'        => 'select',
			'default'     => 'center',
			'choices'     => array(
				'left'   => 'Left',
				'center' => 'Center',
				'right'  => 'Right',
			),
			'section'     => 'section_mailtpl_header',
			'settings'    => 'mailtpl_opts[header_text_aligment]',
			'description' => __( 'Choose alignment for header', 'email-templates' ),
		)
	)
);

// header text color.
$wp_customize->add_setting(
	'mailtpl_opts[header_text_color]',
	array(
		'type'                 => 'option',
		'default'              => mailtpl_get_options( 'header_text_color', $this->defaults['header_text_color'] ),
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_header_text_color',
		array(
			'label'       => __( 'Text Color', 'email-templates' ),
			'section'     => 'section_mailtpl_header',
			'settings'    => 'mailtpl_opts[header_text_color]',
			'description' => __( 'Choose header text color', 'email-templates' ),
		)
	)
);

// background color.
$wp_customize->add_setting(
	'mailtpl_opts[header_bg]',
	array(
		'type'                 => 'option',
		'default'              => mailtpl_get_options( 'header_bg', $this->defaults['header_bg'] ),
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'header_bg',
		array(
			'label'       => __( 'Background Color', 'email-templates' ),
			'section'     => 'section_mailtpl_header',
			'settings'    => 'mailtpl_opts[header_bg]',
			'description' => __( 'Choose header background color', 'email-templates' ),
		)
	)
);

// header padding top.
$wp_customize->add_setting(
	'mailtpl_opts[header_text_padding_top]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'header_text_padding_top', 0 ),
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[header_text_padding_top]',
		array(
			'label'       => esc_attr__( 'Padding Top', 'email-templates' ),
			'description' => esc_html__( 'Adjust padding Top', 'email-templates' ),
			'section'     => 'section_mailtpl_header',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// header padding bottom.
$wp_customize->add_setting(
	'mailtpl_opts[header_text_padding_bottom]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'header_text_padding_bottom', 0 ),
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[header_text_padding_bottom]',
		array(
			'label'       => esc_attr__( 'Padding Bottom', 'email-templates' ),
			'description' => esc_html__( 'Adjust padding Bottom', 'email-templates' ),
			'section'     => 'section_mailtpl_header',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// padding left right.
$wp_customize->add_setting(
	'mailtpl_opts[header_text_padding_left_right]',
	array(
		'transport'         => 'postMessage',
		'type'              => 'option',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'header_text_padding_left_right', 15 )
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[header_text_padding_left_right]',
		array(
			'label'       => esc_attr__( 'Padding Left/Right', 'email-templates' ),
			'description' => esc_html__( 'Adjust padding Left/Right', 'email-templates' ),
			'section'     => 'section_mailtpl_header',
			'input_attrs' => array(
				'min' => 0,
				'max' => 150,
			),
		)
	)
);

// font style.
$wp_customize->add_setting(
	'mailtpl_opts[header_font_style]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'normal',
	)
);
$wp_customize->add_control(
	'mailtpl_opts[header_font_style]',
	array(
		'type'        => 'select',
		'choices'     => array(
			'normal' => esc_attr__( 'Normal', 'email-templates' ),
			'italic' => esc_attr__( 'Italic', 'email-templates' ),
		),
		'label'       => esc_attr__( 'Select Header Font Style', 'email-templates' ),
		'description' => esc_attr__( 'Customize your text by changing the font style', 'email-templates' ),
		'section'     => 'section_mailtpl_header',
	)
);

// font weight.
$wp_customize->add_setting(
	'mailtpl_opts[header_font_weight]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 100,
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[header_font_weight]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Select Header Font Weight', 'email-templates' ),
			'description' => esc_attr__( 'Customize your text by changing the font weight', 'email-templates' ),
			'section'     => 'section_mailtpl_header',
			'input_attrs' => array(
				'min'  => 100,
				'max'  => 900,
				'step' => 100,
			),
		)
	)
);

// header font family.
$wp_customize->add_setting(
	'mailtpl_opts[header_font_family]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'arial',
	)
);
$wp_customize->add_control(
	'mailtpl_opts[header_font_family]',
	array(
		'type'        => 'select',
		'choices'     => mailtpl_get_all_fonts(),
		'label'       => esc_attr__( 'Select Header Font', 'email-templates' ),
		'description' => esc_attr__( 'Customize your text by changing the font', 'email-templates' ),
		'section'     => 'section_mailtpl_header',
	)
);

do_action( 'mailtpl_sections_header_after_content', $wp_customize );
