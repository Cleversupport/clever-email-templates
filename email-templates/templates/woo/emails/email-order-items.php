<?php
/**
 * Email Order Items
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-items.php.
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

$responsive_check = 'fullwidth' === mailtpl_get_options( 'template', '' );
$image_size       = mailtpl_get_options( 'order_items_image_size', '' );
$margin_side      = is_rtl() ? 'right' : 'left';
$text_align       = is_rtl() ? 'right' : 'left';


// convert string e.g 100x50 in to array.

$width = $height = '';

if ( $image_size !== 'woocommerce_thumbnail' && strpos( $image_size, 'x' ) !== false ) {
    list( $width, $height ) = explode( 'x', $image_size );
    $width  = (int) trim( $width );
    $height = (int) trim( $height );
}


if ( $responsive_check ) {
	foreach ( $items as $item_id => $item ) {
		$product       = $item->get_product();
		$sku           = '';
		$purchase_note = '';
		$image         = '';
		$image_url = $item->get_meta( '_product_image' );
		if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
			continue;
		}

		$order_notes =  $item->get_meta( '_order_notes' );
		$order_note_position =  $item->get_meta( '_order_notes_position' );

		if ( is_object( $product ) ) {
			$sku           = $product->get_sku();
			$purchase_note = $product->get_purchase_note();
			$image = $width && $height ? $product->get_image( array( $width, $height ) ) : $product->get_image( $image_size );
		}
		?>
		<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item' . ( $show_image ? ' show-image' : '' ) , $item, $order ) ); ?>">
			<td class="td" style="text-align: <?php echo esc_attr( $text_align ); ?>;vertical-align: middle; word-wrap:break-word;">
				<?php

				if ( ! empty( $image_url ) ) {
					echo '<img src="' . esc_url( $image_url ) . '" class="dummy-product-image" width="'.esc_attr($width).'" height="'.esc_attr($height).'"  alt="' . esc_attr( $item->get_name() ) . '" />';
				}

				if ( $show_image ) {
					echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
				}

				echo '<p style="margin-bottom: 0;"><strong>' . wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) ) . '</strong></p>';
				
				if ( $show_sku && $sku ) {
					echo '(#' . esc_attr( $sku ) . ')';
				}
				do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

				$qty          = $item->get_quantity();
				$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

				if ( $refunded_qty ) {
					$qty_display = '<del>' . esc_attr( $qty ) . '</del> <ins> ' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
				} else {
					$qty_display = $qty;
				}

				echo '<p class="inside-quantity" style="margin-bottom:0;">'
					. esc_html__( 'Quantity', 'woocommerce' )
					. ' '
					. wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $qty_display, $item ) )
					. '</p>';

				wc_display_item_meta(
					$item,
					array(
						'label_before' => '<strong class="wc-item-meta-label" style="margin-' . esc_attr( $margin_side ) . ': .25em;clear:both;">',
					)
				);

				do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );
				?>
			</td>
			<td class="td" style="text-align: <?php echo esc_attr( $text_align ); ?>; vertical-align: middle;">
				<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
			</td>
		</tr>
		<?php
		if ( $show_purchase_note && $purchase_note ) {
			?>
			<tr>
				<td colspan="2" style="text-align: <?php echo esc_attr( $text_align ); ?>; vertical-align: middle;">
					<?php echo wp_kses_post( wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ) ); ?>
				</td>
			</tr>
			<?php
		}

		if ( ! empty( $order_notes ) ) {
			?>
		<tr class="note-check">
						<th class="td" scope="row" colspan="1" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_attr_e( 'Order Note1:', 'woocommerce' ); ?></th>
						<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo esc_attr($order_notes); ?></td>
				</tr>	
			<?php
		}

	}
} else {
	foreach ( $items as $item_id => $item ) {
		$product       = $item->get_product();
		$sku           = '';
		$purchase_note = '';
		$image         = '';
		if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
			continue;
		}
		$image_url = $item->get_meta( '_product_image' );
		$order_notes =  $item->get_meta( '_order_notes' );
		$order_note_position =  $item->get_meta( '_order_notes_position' );
		if ( is_object( $product ) ) {
			$sku           = $product->get_sku();
			$purchase_note = $product->get_purchase_note();
			// Set image size for WooCommerce
			$image = $width && $height ? $product->get_image( array( $width, $height ) ) : $product->get_image( $image_size );
		}
		

		?>
		
		<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item' . ( $show_image ? ' show-image' : '' ) , $item, $order ) ); ?>">
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;vertical-align:middle;word-wrap:break-word;">
				<?php
           
				if ( ! empty( $image_url ) ) {
					echo '<img src="' . esc_url( $image_url ) . '" class="dummy-product-image" width="'.esc_attr($width).'" height="'.esc_attr($height).'"  alt="' . esc_attr( $item->get_name() ) . '" />';
				}


				if ( $show_image ) {
					echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
				}
				
				echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

				if ( $show_sku && $sku ) {
					echo '(#' . esc_attr( $sku ) . ')';
				}

				do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

				wc_display_item_meta(
					$item,
					array(
						'label_before' => '<strong class="wc-item-meta-label" style="margin-' . esc_attr( $margin_side ) . ': .25em;clear:both;">',
					)
				);

				do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );
				
				?>
			</td>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;vertical-align:middle;">
				<?php
				$qty          = $item->get_quantity();
				$refunded_qty = $order->get_qty_refunded_for_item( $item_id );
				
				if ( $refunded_qty ) {
					$qty_display = '<del>' . esc_attr( $qty ) . '</del> <ins> ' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
				} else {
					$qty_display = esc_html( $qty );
				}
				echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $qty_display, $item ) );
				?>
			</td>
			<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;vertical-align:middle;">
				<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
			</td>
			<?php
		
		
		?>
		</tr>
		
		

		<?php

		if ( $show_purchase_note && $purchase_note ) {
			?>
			<tr>
				<td colspan="2" style="text-align: <?php echo esc_attr( $text_align ); ?>; vertical-align: middle;">
					<?php echo wp_kses_post( wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ) ); ?>
				</td>
			</tr>
			<?php


		}
	}

	if ( ! empty( $order_notes ) ) {
		?>
	<tr class="note-check">
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_attr_e( 'Order Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo esc_attr($order_notes); ?></td>
			</tr>	
		<?php
	}

	
	if ( $order_note_position ) {
		
	?>


	<?php	
		}	
}
