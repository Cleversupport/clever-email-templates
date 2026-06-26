<?php
/**
 * Customer note email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-note.php.
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

/**
 * Woocommerce email content.
 *
 * @param object $order         Order object.
 * @param bool   $sent_to_admin Sent to admin.
 * @param bool   $plain_text    Plain text.
 * @param object $email         Email object.
 */
do_action( 'mailtpl_woomailemail_details', $order, $sent_to_admin, $plain_text, $email );

?>

<blockquote><?php echo wp_kses_post( wpautop( wptexturize( $customer_note ) ) ); ?></blockquote>
<p><?php esc_attr_e( 'For your reference, your order details are shown below.', 'woocommerce' ); ?></p>

<?php

/**
 * Woocommerce email content.
 *
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Emails::order_schema_markup() Adds Schema.org markup.
 *
 * @param object $order         Order object.
 * @param bool   $sent_to_admin Sent to admin.
 * @param bool   $plain_text    Plain text.
 * @param object $email         Email object.
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Woocommerce email content.
 *
 * @hooked WC_Emails::order_meta() Shows order meta data.
 * @hooked WC_Emails::customer_details() Shows customer details.
 * @hooked WC_Emails::email_address() Shows email address.
 * @hooked WC_Emails::sent_to_admin() Shows email sent to admin.
 * @hooked WC_Emails::order_schema_markup() Adds Schema.org markup.
 * @hooked WC_Emails::email_display_meta() Shows order meta data.
 *
 * @param object $order         Order object.
 * @param bool   $sent_to_admin Sent to admin.
 * @param bool   $plain_text    Plain text.
 * @param object $email         Email object.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * Woocommerce email content.
 *
 * @hooked WC_Emails::customer_details() Shows customer details.
 * @hooked WC_Emails::email_address() Shows email address.
 * @hooked WC_Emails::sent_to_admin() Shows email sent to admin.
 * @hooked WC_Emails::order_schema_markup() Adds Schema.org markup.
 * @hooked WC_Emails::email_display_meta() Shows order meta data.
 *
 * @param object $order         Order object.
 * @param bool   $sent_to_admin Sent to admin.
 * @param bool   $plain_text    Plain text.
 * @param object $email         Email object.
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

if ( isset( $additional_content ) && ! empty( $additional_content ) ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/**
 * Woocommerce email footer.
 *
 * @hooked WC_Emails::email_footer() Output the email footer
 *
 * @param object $email Email object.
 */
do_action( 'woocommerce_email_footer', $email );
