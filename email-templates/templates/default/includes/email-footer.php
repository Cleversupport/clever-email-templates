<?php
/**
 * Email footer template
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<?php if ( 'inside' === $settings['footer_placement'] ) : ?>
										<table cellpadding="10" cellspacing="0" border="0" width="100%" id="template_footer_container" style="<?php echo esc_attr( $template_footer_container ); ?>">
											<tr>
												<td colspan="2" valign="middle" style="">
													<div id="credit" style="<?php esc_attr( $template_footer_credit ); ?>">
														<?php echo do_shortcode( $settings['footer_text'] ); ?>
													</div>

													<?php if ( 'on' === $settings['footer_powered_by'] ) : ?>
														<p id="powered">
															<?php esc_attr_e( 'Powered By', 'email-templates' ); ?>
															<!-- todo: change the link to https://wpexperts.io{WPExperts Plugin} -->
															<a href="https://wp.timersys.com/email-templates/?utm_source=emails_template_plugin&utm_medium=powered_link&utm_campaign=Email%20Templates"><?php esc_attr_e( 'Email Templates Plugin', 'email-templates' ); ?></a>
														</p>
													<?php endif; ?>
												</td>
											</tr>
										</table>
									<?php endif; ?>
								</td>
							</tr>
						</table>
						<?php if ( 'inside' !== $settings['footer_placement'] ) : ?>
							<table cellpadding="10" cellspacing="0" border="0" width="100%" id="template_footer_container" style="<?php echo esc_attr( $template_footer_container ); ?>">
								<tr>
									<td colspan="2" valign="middle" style="">
										<div id="credit" style="<?php echo esc_attr( $template_footer_credit ); ?>">
											<?php echo do_shortcode( $settings['footer_text'] ); ?>
										</div>

										<?php if ( 'on' === $settings['footer_powered_by'] ) : ?>
											<p id="powered">
												<?php esc_attr_e( 'Powered By', 'email-templates' ); ?>
												<!-- todo: change the link to https://wpexperts.io{WPExperts Plugin} -->
												<a href="https://wp.timersys.com/email-templates/?utm_source=emails_template_plugin&utm_medium=powered_link&utm_campaign=Email%20Templates"><?php esc_attr_e( 'Email Templates Plugin', 'email-templates' ); ?></a>
											</p>
										<?php endif; ?>
									</td>
								</tr>
							</table>
						<?php endif ?>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
