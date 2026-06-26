<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

$text_align       = is_rtl() ? 'right' : 'left';
$responsive_check = 'fullwidth' === mailtpl_get_options( 'template', '' );
$zebra_check      = mailtpl_get_options( 'items_table_background_odd_color', '' );
$note_check       = mailtpl_get_options( 'notes_outside_table', 'false' );

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email );

?>
<div style="clear:both;height:1px;"></div>
<?php
$order_heading_style = 'normal';
$order_heading_option = mailtpl_get_options('order_heading_style');

if ( isset($order_heading_option) && ! empty($order_heading_option) ) {
    $order_heading_style = $order_heading_option;
}
?>
<div class="mailtlp_table_style">
	<?php	
	if ( 'split' === $order_heading_style ) {
	
	?>
	
	<table class="order-info-split-table" cellspacing="0" cellpadding="0" width="100%" border="0">
		<tr>
			<td align="left" valign="middle">
				<h3 style="text-align: left;">
					<?php
					if ( $sent_to_admin ) {
						$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
						$after  = '</a>';
					} else {
						$before = '';
						$after  = '';
					}
					/* translators: %s: Order ID. */
					echo wp_kses_post( $before . sprintf( __( 'Order #%s', 'woocommerce' ), $order->get_order_number() ) . $after );
					?>
				</h3>
			</td>
			<td align="right" valign="middle">
			<h3 style="text-align: right;">
				<?php
				echo wp_kses_post(
					sprintf(
						'<span class="order-date-label">%s:</span> <time datetime="%s">%s</time>',
						esc_html__( 'Order Date', 'woocommerce' ), // Escapes the 'Order Date' text
						$order->get_date_created()->format( 'c' ), // For `datetime` attribute (ISO format)
						esc_html( wc_format_datetime( $order->get_date_created() ) ) // Correctly formatted display date
					)
				);
				?>
			</h3>

			</td>
		</tr>
	</table>
	<?php
} 
else{
	if ( $sent_to_admin ) {
		$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
		$after  = '</a>';
	} else {
		$before = '';
		$after  = '';
	}
	echo wp_kses_post(
		sprintf(
			/* translators: %s: Order ID. */
			$before . __( 'Order #%1$s', 'woocommerce' ) . $after . '<span class="order_time"> (<time datetime="%2$s">%3$s</time>)</span>',
			$order->get_order_number(),
			$order->get_date_created()->format( 'c' ),
			wc_format_datetime( $order->get_date_created() )
		)
	);
}




?>
	</div>


<?php

if ( $responsive_check ) {
	?>
	<div class="email-spacing-wrap" style="margin-bottom: 40px;">
		<table class="td" cellspacing="0" cellpadding="0" width="100%" border="1">
			<thead>
			<tr>
				<th class="td" scope="col" style="text-align: <?php echo esc_attr( $text_align ); ?>;">
					<?php esc_attr_e( 'Product', 'woocommerce' ); ?>
				</th>
				<th class="td" scope="col" style="text-align: <?php echo esc_attr( $text_align ); ?>;">
					<?php esc_attr_e( 'Price', 'woocommerce' ); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php
			echo wp_kses_post(
				wc_get_email_order_items(
					$order,
					array(
						'show_sku'      => $sent_to_admin,
						'show_image'    => false,
						'image_size'    => array( 32, 32 ),
						'plain_text'    => $plain_text,
						'sent_to_admin' => $sent_to_admin,
					)
				)
			);
			?>
			<?php if ( empty( $zebra_check ) ) { ?>
				</tbody>
				<tfoot>
			<?php } ?>
			<?php
			$item_totals = $order->get_order_item_totals();
			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $total ) {
					++$i;
					?>
					<tr>
						<th class="td tlabel-<?php echo esc_attr( preg_replace( '/[^a-z]/', '', strtolower( $total['label'] ) ) ); ?>" scope="row" colspan="1" style="text-align: <?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( $total['label'] ); ?></th>
						<td class="td tvalue-<?php echo esc_attr( preg_replace( '/[^a-z]/', '', strtolower( $total['label'] ) ) ); ?>" style="text-align: <?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}

			
			
			?>
			<?php if ( empty( $zebra_check ) ) : ?>
				</tfoot>
			<?php else : ?>
				
				</tbody>
			<?php endif ?>

			<?php if ( false == $note_check && $order->get_customer_note() ) : ?>
				
				<tr>
					<th class="td" scope="row" colspan="1" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_attr_e( 'Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
			<?php endif; ?>

		</table>

		<?php if ( $note_check && $order->get_customer_note() ) : ?>
		<div class="email-spacing-wrap" style="margin-top: 40px; margin-bottom: unset;">
			<h2>
				<?php esc_attr_e( 'Order Note:', 'woocommerce' ); ?>
			</h2>
			<p class="note-content">
				<?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?>
			</p>
		</div>
	<?php endif; ?>
	</div>
	<?php
	if ( current_user_can( 'administrator' ) && is_customize_preview() ) {
	?>
	<div class="email-spacing-wrap note-check-below" style="margin-bottom: 40px;">
			<h2>
				<?php esc_attr_e( 'Order Note:', 'woocommerce' ); ?>
			</h2>
			<p class="note-content">
				<?php echo esc_attr('Please call before delivery.', 'email-templates'); ?>
			</p>
		</div>
		<?php
	}
	?>
<?php } else { ?>
	<div class="email-spacing-wrap preview-order-div" style="margin-bottom:40px">
		<table class="td" cellspacing="0" cellpadding="0" style="width:100%" border="1">
			<thead>
			<tr>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
					<?php esc_attr_e( 'Product', 'woocommerce' ); ?>
				</th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
					<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>
				</th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
					<?php esc_attr_e( 'Price', 'woocommerce' ); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php
			echo wp_kses_post(
				wc_get_email_order_items(
					$order,
					array(
						'show_sku'      => $sent_to_admin,
						'show_image'    => false,
						'image_size'    => array( 32, 32 ),
						'plain_text'    => $plain_text,
						'sent_to_admin' => $sent_to_admin,
					)
				)
			);
			?>
			<?php if ( empty( $zebra_check ) ) : ?>
				</tbody>
				<tfoot>
			<?php endif; ?>
			<?php
			$item_totals = $order->get_order_item_totals();
			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $total ) {
					++$i;
					?>
					<tr>
						<th class="td tlabel-<?php echo esc_attr( preg_replace( '/[^a-z]/', '', strtolower( $total['label'] ) ) ); ?>" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( $total['label'] ); ?></th>
						<td class="td tvalue-<?php echo esc_attr( preg_replace( '/[^a-z]/', '', strtolower( $total['label'] ) ) ); ?>" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
			
			
			?>
			

			<?php if ( false == $note_check && $order->get_customer_note() ) : ?>
				
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_attr_e( 'Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ( empty( $zebra_check ) ) : ?>
				</tfoot>
			<?php else : ?>
				</tbody>
			<?php endif; ?>
		</table>
	</div>
	
	<?php
	if ( current_user_can( 'administrator' ) && is_customize_preview() ) {
	?>
	<div class="email-spacing-wrap note-check-below" style="margin-bottom: 40px;">
			<h2>
				<?php esc_attr_e( 'Order Note:', 'woocommerce' ); ?>
			</h2>
			<p class="note-content">
				<?php echo esc_attr('Please call before delivery.', 'email-templates'); ?>
			</p>
		</div>
		<?php
	}
	?>


	<?php if ( $note_check && $order->get_customer_note() ) : ?>
		<div class="email-spacing-wrap" style="margin-bottom: 40px;">
			<h2>
				<?php esc_attr_e( 'Order Note:', 'woocommerce' ); ?>
			</h2>
			<p class="note-content">
				<?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?>
			</p>
		</div>
	<?php endif; ?>
	<?php
}
do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email );

