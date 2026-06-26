<?php
/**
 * Settings Section
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

do_action( 'mailtpl_sections_settings_before_content', $wp_customize );

$wp_customize->add_setting(
	'mailtpl_opts[from_name]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['from_name'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_text_field',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'mailtpl_from_name',
		array(
			'label'       => __( 'From name', 'email-templates' ),
			'type'        => 'text',
			'section'     => 'section_mailtpl_settings',
			'settings'    => 'mailtpl_opts[from_name]',
			'description' => __( 'Default: ', 'email-templates' ) . get_bloginfo( 'name' ),
		)
	)
);

$wp_customize->add_setting(
	'mailtpl_opts[from_email]',
	array(
		'type'                 => 'option',
		'default'              => $this->defaults['from_email'],
		'transport'            => 'postMessage',
		'capability'           => 'edit_theme_options',
		'sanitize_callback'    => 'sanitize_text_field',
		'sanitize_js_callback' => '',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'mailtpl_from_email',
		array(
			'label'       => __( 'From Email', 'email-templates' ),
			'type'        => 'text',
			'section'     => 'section_mailtpl_settings',
			'settings'    => 'mailtpl_opts[from_email]',
			'description' => __( 'Default: ', 'email-templates' ) . get_bloginfo( 'admin_email' ),
		)
	)
);

do_action( 'mailtpl_sections_settings_after_content', $wp_customize );
