<?php
/**
 * Show user-defined addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

$text_align               = is_rtl() ? 'right' : 'left';
$address                  = $order->get_formatted_billing_address();
$shipping                 = $order->get_formatted_shipping_address();
$responsive_check         = 'fullwidth' === mailtpl_get_options( 'template', '' );
$padding                  = mailtpl_get_options( 'address_box_padding', '' );
$background               = mailtpl_get_options( 'address_box_background_color', '#ffffff' );
$address_box_text_color   = mailtpl_get_options( 'address_box_text_color', '' );
$address_box_border_width = mailtpl_get_options( 'address_box_border_width', '' );
$address_box_border_style = mailtpl_get_options( 'address_box_border_style', '' );
$address_box_border_color = mailtpl_get_options( 'address_box_border_color', '' );
$border                   = sprintf( '%1$spx %2$s %3$s', $address_box_border_width, $address_box_border_style, $address_box_border_color );
$text_align               = mailtpl_get_options( 'address_box_text_align', $text_align );


if ( $responsive_check ) {
	// this is not a css this is html. I am trying to create element with emmet syntax.
	?>
	<table id="addresses" cellspacing="0" cellpadding="0" style="width:100%;vertical-align:top;margin-bottom:40px;padding:0" border="0">
		<tr>
			<td class="address-container" style="text-align:<?php echo esc_attr( $text_align ); ?>;padding: <?php echo esc_attr( $padding ); ?>px;border:<?php echo esc_attr( $border ); ?>;background: <?php echo sanitize_hex_color( $background ); ?>;color: <?php echo sanitize_hex_color( $address_box_text_color ); ?>;" valign="top">
				<h2 style="text-align: <?php echo esc_attr( $text_align ); ?>;color: <?php echo sanitize_hex_color( $address_box_text_color ); ?>;"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2>

				<address class="address">
					<table cellspacing="0" cellpadding="0" style="width:100%;padding:0;" border="0">
						<tr>
							<td class="address-td" valign="top">
								<?php echo wp_kses_post( $address ? $address : esc_html__( 'N/A', 'woocommerce' ) ); ?>
								<?php
								if ( ! class_exists( 'APG_Combo_NIF' ) ) {
									if ( $order->get_billing_phone() ) {
										?>
										<br>
										<?php
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										echo wc_make_phone_clickable( $order->get_billing_phone() );
									}
									if ( $order->get_billing_email() ) {
										?>
										<a href="mailto:<?php echo esc_attr( $order->get_billing_email() ); ?>"><?php echo esc_attr( $order->get_billing_email() ); ?></a>
										<?php
									}
								}
								?>
							</td>
						</tr>
					</table>
				</address>
			</td>
		</tr>
		<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping ) : ?>
			<tr>
				<td class="shipping-address-container" style="text-align: <?php echo esc_attr( $text_align ); ?>;border: <?php echo esc_attr( $border ); ?>;padding: <?php echo esc_attr( $padding ); ?>px;background: <?php echo sanitize_hex_color( $background ); ?>;color: <?php echo sanitize_hex_color( $address_box_text_color ); ?>;" valign="top">
					<h2 style="text-align: <?php echo esc_attr( $text_align ); ?>;color: <?php echo sanitize_hex_color( $address_box_text_color ); ?>;"><?php esc_attr_e( 'Shipping address', 'woocommerce' ); ?></h2>

					<address class="address">
						<table cellspacing="0" cellpadding="0" style="width:100%;padding:0;" border="0">
							<tr>
								<td class="address-td" valign="top">
									<?php echo wp_kses_post( $shipping ); ?>
								</td>
							</tr>
						</table>
					</address>
				</td>
			</tr>
		<?php endif; ?>
	</table>
	<?php
} else {
	?>
	<table id="addresses" cellspacing="0" cellpadding="0" border="0" style="width:100%;vertical-align: top;margin-bottom: 40px;padding: 0;">
		<tr>
			<td class="address-container" style="padding: <?php echo esc_attr( $padding ); ?>px;border: <?php echo esc_attr( $border ); ?>;background: <?php echo sanitize_hex_color( $background ); ?>;text-align: <?php echo esc_attr( $text_align ); ?>;color: <?php echo sanitize_hex_color( $address_box_text_color ); ?>;" valign="top" width="50%">
				<h2 style="text-align: <?php echo esc_attr( $text_align ); ?>;color: <?php echo sanitize_hex_color( $address_box_text_color ); ?>;"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2>

				<address class="address">
					<?php echo wp_kses_post( $address ? $address : esc_html__( 'N/A', 'woocommerce' ) ); ?>
					<?php
					if ( ! class_exists( 'APG_Combo_NIF' ) ) {
						if ( $order->get_billing_phone() ) {
							?>
							<br>
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo wc_make_phone_clickable( $order->get_billing_phone() );
						}
						if ( $order->get_billing_email() ) {
							?>
							<a href="mailto:<?php echo esc_attr( $order->get_billing_email() ); ?>"><?php echo esc_attr( $order->get_billing_email() ); ?></a>
							<?php
						}
					}
					?>
				</address>
			</td>
			<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping ) : ?>
				<td class="shipping-address-container" style="padding: <?php echo esc_attr( $padding ); ?>px;border: <?php echo esc_attr( $border ); ?>;background: <?php echo sanitize_hex_color( $background ); ?>;text-align: <?php echo esc_attr( $text_align ); ?>;color: <?php echo sanitize_hex_color( $address_box_text_color ); ?>;" valign="top" width="50%">
					<h2 style="text-align: <?php echo esc_attr( $text_align ); ?>;color: <?php echo sanitize_hex_color( $address_box_text_color ); ?>;"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>

					<address class="address">
						<?php echo wp_kses_post( $shipping ); ?>
					</address>
				</td>
			<?php endif; ?>
		</tr>
	</table>
	<?php
}
