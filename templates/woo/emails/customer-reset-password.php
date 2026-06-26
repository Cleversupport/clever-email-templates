<?php
/**
 * Customer reset password email
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

$button_check = true; // Set to false to remove button.

do_action( 'woocommerce_email_header', $email_heading, $email );

do_action( 'mailtpl_woomailemail_text', $email );

$reset_link = add_query_arg(
	array(
		'key' => $reset_key,
		'id'  => $user_id,
	),
	wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) )
);

$button_border_width       = mailtpl_get_options( 'button_border_width', '' );
$button_border_style       = mailtpl_get_options( 'button_border_style', '' );
$button_border_color       = mailtpl_get_options( 'button_border_color', '' );
$button_text_color         = mailtpl_get_options( 'button_text_color', '' );
$button_font_size          = mailtpl_get_options( 'button_font_size', '' );
$button_background_color   = mailtpl_get_options( 'button_background_color', '' );
$button_border_radius      = mailtpl_get_options( 'button_border_radius', '' );
$button_font_family        = mailtpl_get_options( 'button_font_family', '' );
$button_font_weight        = mailtpl_get_options( 'button_font_weight', '' );
$button_padding_top_bottom = mailtpl_get_options( 'button_padding_top_bottom', '' );
$button_padding_left_right = mailtpl_get_options( 'button_padding_left_right', '' );
$border                    = sprintf( '%1$spx %2$s %3$s', $button_border_width, $button_border_style, $button_border_color );

if ( true === $button_check ) { ?>
	<p class="button-container" style="padding: <?php echo esc_attr( $button_padding_top_bottom ); ?>px <?php echo esc_attr( $button_padding_left_right ); ?>px;">
		<a
				href="<?php echo esc_url( $reset_link ); ?>"
				class="btn"
				style="
					color:         <?php echo sanitize_hex_color( $button_text_color ); ?>!important;
					font-size:     <?php echo esc_attr( $button_font_size ); ?>px;
					background:    <?php echo sanitize_hex_color( $button_background_color ); ?>;
					border-radius: <?php echo esc_attr( $button_border_radius ); ?>px;
					border:        <?php echo esc_attr( $border ); ?>;
					font-family:   <?php echo esc_attr( $button_font_family ); ?>;
					font-weight:   <?php echo esc_attr( $button_font_weight ); ?>;
					padding:       <?php echo esc_attr( $button_padding_top_bottom ); ?>px <?php echo esc_attr( $button_padding_left_right ); ?>px;
				"><?php esc_html_e( 'Reset your password', 'woocommerce' ); ?></a>
	</p>
	<?php
} else {
	?>
	<p>
		<a href="<?php echo esc_url( $reset_link ); ?>" class="link"><?php esc_html_e( 'Click here to reset your password', 'woocommerce' ); ?></a>
	</p>
	<?php
}
?>
<p></p>
<?php
if ( isset( $additional_content ) && ! empty( $additional_content ) ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
