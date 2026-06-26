<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

/**
 * Woocommerce email header.
 *
 * @hooked WC_Emails::email_header() Output the email header
 *
 * @param string $email_heading Email heading.
 * @param object $email         Email object.
 */
do_action( 'woocommerce_email_header', $email_heading, $email );

$button_check            = true; // @todo - add button to email template.
$account_section         = true; // @todo - add account section to email template.
$button_border_width     = mailtpl_get_options( 'button_border_width', '' );
$button_border_style     = mailtpl_get_options( 'button_border_style', '' );
$button_border_color     = mailtpl_get_options( 'button_border_color', '' );
$padding_top_bottom      = mailtpl_get_options( 'button_padding_top_bottom', '' );
$padding_left_right      = mailtpl_get_options( 'button_padding_left_right', '' );
$button_text_color       = mailtpl_get_options( 'button_text_color', '' );
$button_font_size        = mailtpl_get_options( 'button_font_size', '' );
$button_background_color = mailtpl_get_options( 'button_background_color', '' );
$button_border_radius    = mailtpl_get_options( 'button_border_radius', '' );
$button_font_family      = mailtpl_get_options( 'button_font_family', '' );
$button_font_weight      = mailtpl_get_options( 'button_font_weight', '' );
$border                  = sprintf( '%1$spx %2$s %3$s', $button_border_width, $button_border_style, $button_border_color );

do_action( 'mailtpl_woomailemail_text', $email );

if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) :
	if ( $set_password_url ) {
		?>
		<p>
			<a href="<?php echo esc_url( $set_password_url ); ?>"><?php esc_attr_e( 'Click here to set your new password.', 'woocommerce' ); ?></a>
		</p>
		<?php
	} else {
		?>
		<p>
			<?php
			printf(
				wp_kses(
					// translators: %s: auto generated password.
					__( 'Your password has been automatically generated: %s', 'woocommerce' ),
					array(
						'strong' => array(),
					),
				),
				'<strong>' . esc_html( $user_pass ) . '</strong>'
			);
			?>
		</p>
		<?php
	}
endif;

if ( true === $account_section ) {
	if ( true === $button_check ) {
		?>
		<p>
			<?php esc_attr_e( 'You can access your account area to view orders, change your password, and more via the link below:', 'woocommerce' ); ?>
		</p>
		<p class="button-container" style="padding: <?php echo esc_attr( $padding_top_bottom ); ?>px <?php echo esc_attr( $padding_left_right ); ?>px;">
			<a
					href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
					class="btn"
					style="
						color:         <?php echo sanitize_hex_color( $button_text_color ); ?>!important;
						font-size:     <?php echo esc_attr( $button_font_size ); ?>px;
						background:    <?php echo sanitize_hex_color( $button_background_color ); ?>;
						border-radius: <?php echo esc_attr( $button_border_radius ); ?>px;
						border:        <?php echo esc_attr( $border ); ?>;
						font-family:   <?php echo esc_attr( $button_font_family ); ?>;
						font-weight:   <?php echo esc_attr( $button_font_weight ); ?>;
						padding: <?php echo esc_attr( $padding_top_bottom ); ?>px <?php echo esc_attr( $padding_left_right ); ?>px;
					"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
		</p>
		<?php
	} else {
		?>
		<?php
		printf(
			wp_kses(
				// translators: %s: My Account URL.
				__( 'You can access your account area to view orders, change your password, and more at: %s', 'woocommerce' ),
				array(
					'a' => array(
						'href' => array(),
					),
				)
			),
			wp_kses(
				make_clickable(
					esc_url( wc_get_page_permalink( 'myaccount' ) )
				),
				array( 'a' => array( 'href' => array() ) )
			)
		);
		?>
		<?php
	}
}

if ( isset( $additional_content ) && ! empty( $additional_content ) ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
