<?php
/**
 * Admin cancelled order email (plain text)
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/admin-cancelled-order.php.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email );

do_action( 'mailtpl_woomailemail_details', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

if ( isset( $additional_content ) && $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
