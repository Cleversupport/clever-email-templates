<?php
/**
 * Template Section Settings
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

do_action( 'mailtpl_ections_template_before_content', $wp_customize );
$wp_customize->add_setting(
	'mailtpl_opts[template]',
	array(
		'type'                 => 'option',
		'default'              => mailtpl_get_options( 'template', $this->defaults['template'] ),
		'transport'            => 'refresh',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => array( $this, 'sanitize_templates' ),
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'mailtpl_template',
		array(
			'label'       => __( 'Template Display Type', 'email-templates' ),
			'type'        => 'select',
			'section'     => 'section_mailtpl_template',
			'settings'    => 'mailtpl_opts[template]',
			'choices'     => apply_filters(
				'mailtpl_template_choices',
				array(
					'boxed'     => 'Boxed',
					'fullwidth' => 'Fullwidth',
				)
			),
			'description' => '',
		)
	)
);

// body size.
$wp_customize->add_setting(
	'mailtpl_opts[body_size]',
	array(
		'type'                 => 'option',
		'default'              => mailtpl_get_options( 'body_size', $this->defaults['body_size'] ),
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => array( $this, 'sanitize_text' ),
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_body_size',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => __( 'Email body size', 'email-templates' ),
			'section'     => 'section_mailtpl_template',
			'settings'    => 'mailtpl_opts[body_size]',
			'description' => __( 'Choose boxed size', 'email-templates' ),
			'input_attrs' => array(
				'min' => 320,
				'max' => 1280,
			),
		)
	)
);

// body bg.
$wp_customize->add_setting(
	'mailtpl_opts[body_bg]',
	array(
		'type'                 => 'option',
		'default'              => mailtpl_get_options( 'body_bg', $this->defaults['body_bg'] ),
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_hex_color',
		'sanitize_js_callback' => '',
		'default'           => '#bfbfbf'
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_body_bg',
		array(
			'label'       => __( 'Background Color', 'email-templates' ),
			'section'     => 'section_mailtpl_template',
			'settings'    => 'mailtpl_opts[body_bg]',
			'description' => __( 'Choose email background color', 'email-templates' ),
		)
	)
);


// border starts.
$wp_customize->add_setting(
	'mailtpl_opts[template_border_top_width]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'template_border_top_width', 0 ),
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[template_border_top_width]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Border Top Width', 'email-templates' ),
			'description' => esc_attr__( 'Adjust border top width.', 'email-templates' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 20,
			),
			'section'     => 'section_mailtpl_template',
		)
	)
);

$wp_customize->add_setting(
	'mailtpl_opts[template_border_bottom_width]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'template_border_bottom_width', 0 ),
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[template_border_bottom_width]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Border Bottom Width', 'email-templates' ),
			'description' => esc_attr__( 'Adjust border bottom width.', 'email-templates' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 20,
			),
			'section'     => 'section_mailtpl_template',
		)
	)
);

$wp_customize->add_setting(
	'mailtpl_opts[template_border_left_width]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'template_border_left_width', 0 )
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[template_border_left_width]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Border Left Width', 'email-templates' ),
			'description' => esc_attr__( 'Adjust border left width.', 'email-templates' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 20,
			),
			'section'     => 'section_mailtpl_template',
		)
	)
);

$wp_customize->add_setting(
	'mailtpl_opts[template_border_right_width]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'template_border_right_width', 0 )
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[template_border_right_width]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Border Right Width', 'email-templates' ),
			'description' => esc_attr__( 'Adjust border right width.', 'email-templates' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 20,
			),
			'section'     => 'section_mailtpl_template',
		)
	)
);


$wp_customize->add_setting(
	'mailtpl_opts[template_border_color]',
	array(
		'default'           => mailtpl_get_options( 'template_border_color', '' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);
$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'mailtpl_opts[template_border_color]',
		array(
			'type'        => 'color',
			'label'       => esc_attr__( 'Border Color', 'email-templates' ),
			'description' => esc_attr__( 'Select color for border.', 'email-templates' ),
			'section'     => 'section_mailtpl_template',
			'default'     => '#eaeaea',
		)
	)
);

// border end.

$wp_customize->add_setting(
	'mailtpl_opts[template_border_radius]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'template_border_radius', 6 )
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[template_border_radius]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Border Radius', 'email-templates' ),
			'description' => esc_attr__( 'Add border radius to your template.', 'email-templates' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 100,
			),
			'section'     => 'section_mailtpl_template',
		)
	)
);

$wp_customize->add_setting(
	'mailtpl_opts[template_padding_top]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'template_padding_top', 70 )
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[template_padding_top]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Padding Top', 'email-templates' ),
			'description' => esc_attr__( 'Adjust your top padding.', 'email-templates' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 250,
			),
			'section'     => 'section_mailtpl_template',
		)
	)
);

$wp_customize->add_setting(
	'mailtpl_opts[template_padding_bottom]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'template_padding_bottom', 70 )
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[template_padding_bottom]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Padding Bottom', 'email-templates' ),
			'description' => esc_attr__( 'Adjust your bottom padding.', 'email-templates' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 250,
			),
			'section'     => 'section_mailtpl_template',
		)
	)
);

$wp_customize->add_setting(
	'mailtpl_opts[template_box_shadow]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'default'           => mailtpl_get_options( 'template_box_shadow', 1 ),
	)
);
$wp_customize->add_control(
	new Mailtpl_Range_Control(
		$wp_customize,
		'mailtpl_opts[template_box_shadow]',
		array(
			'type'        => 'mailtpl-range-control',
			'label'       => esc_attr__( 'Box Shadow', 'email-templates' ),
			'description' => esc_attr__( 'Adjust your Box shadow.', 'email-templates' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 20,
			),
			'section'     => 'section_mailtpl_template',
		)
	)
);

// custom css.
$wp_customize->add_setting(
	'mailtpl_opts[custom_css]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['custom_css'],
		'transport'            => 'refresh',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'wp_filter_nohtml_kses',
		'sanitize_js_callback' => 'wp_filter_nohtml_kses',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'mailtpl_custom_css',
		array(
			'label'       => __( 'Custom CSS', 'email-templates' ),
			'type'        => 'textarea',
			'section'     => 'section_mailtpl_template',
			'settings'    => 'mailtpl_opts[custom_css]',
			'description' => __( 'Add custom css. Be aware that this may not work on all email clients.', 'email-templates' ),
		)
	)
);

do_action( 'mailtpl_sections_template_after_content', $wp_customize );
