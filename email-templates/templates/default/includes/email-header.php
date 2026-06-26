<?php
/**
 * Email header template
 *
 * @package Email Templates
 */

defined('ABSPATH') || exit;

?>

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
	<tr>
		<td align="center" valign="top">
			<?php if ('inside' !== $settings['header_logo_location']) { ?>
				<table border="0" cellspacing="0" cellpadding="0" width="100%" id="template_header_logo_image"
					style="<?php echo esc_attr($template_header_image_container); ?>">
					<tr>
						<td>
							<a href="#">
								<?php if ($settings['header_logo']) { ?>
									<img src="<?php echo esc_url($settings['header_logo']); ?>"
										style="width: <?php echo isset($settings['image_width_control']) ? esc_attr($settings['image_width_control']) : '130'; ?>px"
										alt="#">
								<?php } ?>
							</a>
						</td>
					</tr>
				</table>
			<?php } ?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container"
				style="<?php echo esc_attr($template_container); ?>">
				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header">
							<tr>
								<td>
									<?php if ('inside' === $settings['header_logo_location']) { ?>
										<table width="100%" id="template_header_logo_image"
											style="<?php echo esc_attr($template_header_image_container); ?>">
											<tr>
												<td>
													<a href="#">
														<?php if ($settings['header_logo']) { ?>
															<img src="<?php echo esc_url($settings['header_logo']); ?>"
																style="width: <?php echo isset($settings['image_width_control']) ? esc_attr($settings['image_width_control']) : '130'; ?>px;"
																alt="#">
														<?php } ?>
													</a>
												</td>
											</tr>
										</table>
									<?php } ?>
									<table border="0" cellspacing="0" cellpadding="0" width="100%"
										id="template_header_logo_text" class="cccc"
										style="<?php echo esc_attr($template_header_logo_text_container); ?>">
										<tr>
											<td>
												<h1 style="<?php echo esc_attr($template_header_logo_text); ?>">
													<a href="#"
														style="<?php echo esc_attr($template_header_logo_text_a); ?>">
														<?php echo do_shortcode($settings['header_logo_text']); ?>
													</a>
												</h1>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" valign="top">
						<table border="0" cellspacing="0" cellpadding="0" width="100%" id="template_body_container"
							style="<?php echo esc_attr($template_body_container); ?>">
							<tr>
								<td id="template_body_content" style="<?php echo esc_attr($template_body_inner); ?>">