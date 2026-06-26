<?php
/**
 * Admin failed order email (plain text)
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/admin-failed-order.php.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

$footer_placement = mailtpl_get_options( 'footer_placement', 'inside' );
$responsive_check = 'fullwidth' === mailtpl_get_options( 'template', 'fullwidth' );
$content_width    = mailtpl_get_options( 'body_size', '680' );
if ( $responsive_check ) {
	$content_width = '360';
}

?>

																<!-- Body Section end -->
															</div>
														</td>
													</tr>
												</table>
												<!-- Content Section end -->
											</td>
										</tr>
									</table>
									<!-- Body Section end -->
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<?php
									if ( 'inside' === $footer_placement ) {
										do_action( 'mailtpl_woomailemail_footer' );
										?>
										<table class="gmail-app-fix" width="100%" border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td>
													<table cellspacing="0" cellpadding="0" width="<?php echo esc_attr( $content_width ); ?>" align="center">
														<tr>
															<td cellpadding="0" cellspacing="0" border="0" style="line-height:1px;min-width:<?php echo esc_attr( floor( $content_width / 3 ) ); ?>px;"></td>
															<td cellpadding="0" cellspacing="0" border="0" style="line-height:1px;min-width:<?php echo esc_attr( floor( $content_width / 3 ) ); ?>px;"></td>
															<td cellpadding="0" cellspacing="0" border="0" style="line-height:1px;min-width:<?php echo esc_attr( floor( $content_width / 3 ) ); ?>px;"></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
										<?php
									}
									?>
								</td>
							</tr>
						</table>
						<?php
						if ( 'outside' === $footer_placement ) {
							do_action( 'mailtpl_woomailemail_footer' );
							?>
							<table class="gmail-app-fix" width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="<?php echo esc_attr( $content_width ); ?>">
											<tr>
												<td cellpadding="0" cellspacing="0" border="0" height="1"; style="line-height: 1px; min-width: <?php echo esc_attr( floor( $content_width / 3 ) ); ?>px;">
												</td>
												<td cellpadding="0" cellspacing="0" border="0" height="1"; style="line-height: 1px; min-width: <?php echo esc_attr( floor( $content_width / 3 ) ); ?>px;">
												</td>
												<td cellpadding="0" cellspacing="0" border="0" height="1"; style="line-height: 1px; min-width: <?php echo esc_attr( floor( $content_width / 3 ) ); ?>px;">
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<?php
						}
						?>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
