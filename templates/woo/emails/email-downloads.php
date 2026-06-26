<?php
/**
 * Customer reset password email
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

$text_align       = is_rtl() ? 'right' : 'left';
$responsive_check = 'fullwidth' === mailtpl_get_options( 'template', '' );

if ( true === $responsive_check ) {
	unset( $columns['download-expires'] );
}
?>

<div style="clear:both;height:1px"></div>
<h2 class="woocommerce-order-downloads__title"><?php esc_attr_e( 'Downloads', 'woocommerce' ); ?></h2>

<table class="email-spacing-wrap td" cellspacing="0" cellpadding="0" style="width:100%;margin-bottom:40px" border="1">
	<thead>
	<tr>
		<?php foreach ( $columns as $column_id => $column_name ) : ?>
			<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>"><?php echo esc_html( $column_name ); ?></th>
		<?php endforeach; ?>
	</tr>
	</thead>

	<?php foreach ( $downloads as $download ) : ?>
		<tr>
			<?php foreach ( $columns as $column_id => $column ) : ?>
				<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>">
					<?php if ( has_action( 'woocommerce_email_downloads_column_' . $column_id ) ) : ?>
						<?php do_action( 'woocommerce_email_downloads_column_' . $column_id, $download ); ?>
					<?php else : ?>
						<?php
						switch ( $column_id ) {
							case 'download-product':
								?>
								<a href="<?php echo esc_url( get_permalink( $download['product_id'] ) ); ?>"><?php echo wp_kses_post( $download['product_name'] ); ?></a>
								<?php
								break;
							case 'download-file':
								?>
								<a href="<?php echo esc_url( $download['download_url'] ); ?>" class="woocommerce-MyAccount-downloads-file button alt"><?php echo esc_html( $download['download_name'] ); ?></a>
								<?php
								if ( true === $responsive_check ) {
									if ( ! empty( $download['access_expires'] ) ) {
										?>
										<p style="margin-bottom:0;">
											<small>
												<?php esc_html_e( 'Expires:', 'woocommerce' ); ?>
												<time datetime="<?php echo esc_attr( gmdate( 'Y-m-d', strtotime( $download['access_expires'] ) ) ); ?>" title="<?php echo esc_attr( strtotime( $download['access_expires'] ) ); ?>">
													<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) ); ?>
												</time>
											</small>
										</p>
										<?php
									} else {
										echo '<p style="margin-bottom:0;">
											<small>' . esc_attr__( 'Expires: Never', 'woocommerce' ) . '</small>
										</p>';
									}
								}
								break;
							case 'download-expires':
								if ( ! empty( $download['access_expires'] ) ) {
									?>
									<time datetime="<?php echo esc_attr( gmdate( 'Y-m-d', strtotime( $download['access_expires'] ) ) ); ?>" title="<?php echo esc_attr( strtotime( $download['access_expires'] ) ); ?>">
										<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) ); ?>
									</time>
									<?php
								} else {
									echo esc_attr__( 'Never', 'woocommerce' );
								}
								break;
						}
						?>
					<?php endif; ?>
				</td>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
</table>
