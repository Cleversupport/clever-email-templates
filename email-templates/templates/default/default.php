<?php
/**
 * Default template
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;
$settings = Mailtpl::opts();
//exit( var_dump( $settings, wp_footer() ) );
$wrapper = '
background-color: ' . $settings['body_bg'] . ';
width: 100%;
-webkit-text-size-adjust:none !important;
margin: 0;
padding: ' . ( $settings['template_padding_top'] ? $settings['template_padding_top'] . 'px' : '70px' ) . ' 0 ' . ( $settings['template_padding_bottom'] ? $settings['template_padding_bottom'] . 'px' : '70px' ) . ' 0;';

if ( is_customize_preview() ) {
	$wrapper .= 'min-height: 100vh;';
}

$template_container                  = '
background-color: #fafafa;
width: 100%;
width: ' . ( 'boxed' === $settings['template'] ? $settings['body_size'] . 'px' : '100%' ) . ';
border-color: ' . $settings['template_border_color'] . ';
border-style: solid;
border-width: ' . $settings['template_border_top_width'] . 'px ' . $settings['template_border_right_width'] . 'px ' . $settings['template_border_bottom_width'] . 'px ' . $settings['template_border_left_width'] . 'px;
border-radius: ' . $settings['template_border_radius'] . 'px;
box-shadow: 0 ' . ( $settings['template_box_shadow'] ? 1 . 'px' : 0 ) . ' ' . ( $settings['template_box_shadow'] ? ( $settings['template_box_shadow'] * 4 ) . 'px' : 0 ) . ' ' . $settings['template_box_shadow'] . 'px rgba(0,0,0,0.1);
overflow: hidden;
';
$template_header_image_container     = '
background-color: ' . $settings['header_image_bg'] . ';
padding: ' . $settings['header_image_padding_top_bottom'] . 'px 0;
text-align: ' . $settings['header_image_alignment'] . ';
';

$template_header_logo_text           = '
font-size: ' . $settings['header_text_size'] . 'px;
font-weight: ' . $settings['header_font_weight'] . ';
font-style: ' . $settings['header_font_style'] . ';
font-family: ' . $settings['header_font_family'] . ';
line-height: 150%;
';
$template_header_logo_text_a         = '
color: ' . $settings['header_text_color'] . ';
';
$template_header_logo_text_container = '
text-align: ' . $settings['header_text_aligment'] . ';
background: ' . $settings['header_bg'] . ';
padding: ' . $settings['header_text_padding_top'] . 'px ' . $settings['header_text_padding_left_right'] . 'px ' . $settings['header_text_padding_bottom'] . 'px ' . $settings['header_text_padding_left_right'] . 'px;
';

$template_body_inner                 = '
color: ' . $settings['body_text_color'] . ';
font-size: ' . $settings['body_text_size'] . 'px;
padding: ' . $settings['body_padding_top'] . 'px ' . $settings['body_padding_left_right'] . 'px ' . $settings['body_padding_bottom'] . 'px ' . $settings['body_padding_left_right'] . 'px;
line-height: 150%;
';
$template_body_container             = '
background-color: ' . $settings['email_body_bg'] . ';
font-family: ' . $settings['body_font_family'] . ';
font-weight: ' . $settings['body_font_weight'] . ';
';
$template_footer_container           = '
background-color: ' . $settings['footer_bg'] . ';
padding: ' . $settings['footer_padding_top'] . 'px ' . $settings['footer_padding_left_right'] . 'px ' . $settings['footer_padding_bottom'] . 'px ' . $settings['footer_padding_left_right'] . 'px;
font-size: ' . $settings['footer_text_size'] . 'px;
text-align: ' . $settings['footer_aligment'] . ';
color: ' . $settings['footer_text_color'] . ';
font-weight: ' . $settings['footer_font_weight'] . ';
font-family: ' . $settings['footer_font_style'] . ';
';
$template_footer_credit              = '
padding: ' . $settings['footer_text_padding_top'] . 'px 0 ' . $settings['footer_text_padding_bottom'] . 'px 0;
line-height: 125%;
';

$fonts_url = mailtpl_fonts_generators( $settings );



// Including email header.
require_once MAILTPL_PLUGIN_DIR . 'templates/default/includes/email-header.php';

// Including email body.
require_once MAILTPL_PLUGIN_DIR . 'templates/default/includes/email-content.php';

// Including email footer.
require_once MAILTPL_PLUGIN_DIR . 'templates/default/includes/email-footer.php';

if ( is_customize_preview() ) {
	wp_footer();
}
