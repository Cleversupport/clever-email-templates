<?php
/**
 * Email Footer
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( isset( $email ) && is_object( $email ) && isset( $email->id ) ) {
	$key = $email->id;
} else {
	$key = '';
}

$email_subtitle            = mailtpl_get_options( 'heading_' . esc_attr( $key ) . '_subtitle', '' );
$subtitle_placement        = mailtpl_get_options( 'subtitle_placement', 'below' );
$template                  = mailtpl_get_options( 'template', 'fullwidth' );
$responsive_check          = 'fullwidth' === $template;
$content_width             = mailtpl_get_options( 'body_size', '680' );
$order_style               = mailtpl_get_options( 'order_items_style', 'normal' );
$h2_style                  = mailtpl_get_options( 'normal', '' );
$header_image_max_width    = mailtpl_get_options( 'image_width_control', '300' );
$header_placement          = mailtpl_get_options( 'header_logo_location', 'inside' );
$image_align               = mailtpl_get_options( 'header_image_alignment', 'center' );
$image_container_bg_color  = mailtpl_get_options( 'header_image_bg', '#454545' );
$image_container_padding   = mailtpl_get_options( 'header_image_padding_top_bottom', '15' );
$header_image_link         = true;
$responsive_mode           = $responsive_check ? 'fluid' : 'normal';
$img                       = mailtpl_get_options( 'header_logo', '' );
$header_text_align         = mailtpl_get_options( 'header_text_aligment', 'center' );
$header_title_font_size    = mailtpl_get_options( 'header_text_size', '30' );

$heading_background_color  = mailtpl_get_options( 'header_bg', '#454545' );
$heading_color             = mailtpl_get_options( 'header_text_color', '#f1f1f1' );
$header_padding_top        = mailtpl_get_options( 'header_text_padding_top', '15' );
$header_padding_bottom     = mailtpl_get_options( 'header_text_padding_bottom', '15' );
$header_padding_left_right = mailtpl_get_options( 'header_text_padding_left_right', '15' );
$body_content_font_size    = mailtpl_get_options( 'body_text_size', '14' );
$body_content_color        = mailtpl_get_options( 'body_text_color', '#888888' );
$body_links_color          = mailtpl_get_options( 'body_href_color', '#4ca6cf' );
$body_background_color     = mailtpl_get_options( 'email_body_bg', '#fafafa' );
$body_top_padding          = mailtpl_get_options( 'body_padding_top', '0' );
$body_bottom_padding       = mailtpl_get_options( 'body_padding_bottom', '0' );
$body_left_right_padding   = mailtpl_get_options( 'body_padding_left_right', '0' );
$subtitle_font_size        = mailtpl_get_options( 'subtitle_font_size', '18' );
$subtitle_font_weight      = mailtpl_get_options( 'subtitle_font_weight', '100' );
$subtitle_font_family      = mailtpl_get_options( 'subtitle_font_family', 'Arial' );
$subtitle_font_style       = mailtpl_get_options( 'subtitle_font_style', 'normal' );
$subtitle_font_color       = mailtpl_get_options( 'subtitle_text_color', '#ffffff' );
$email_body_size           = mailtpl_get_options( 'body_size', '680' );
$template_bg_color         = mailtpl_get_options( 'body_bg', '#fafafa' );
$body_border_color         = mailtpl_get_options( 'template_border_color', '#000000' );
$border_top                = mailtpl_get_options( 'template_border_top_width', '0' );
$border_bottom             = mailtpl_get_options( 'template_border_bottom_width', '0' );
$border_left               = mailtpl_get_options( 'template_border_left_width', '0' );
$border_right              = mailtpl_get_options( 'template_border_right_width', '0' );
$border_radius             = mailtpl_get_options( 'template_border_radius', '5' );
$template_padding_top      = mailtpl_get_options( 'template_padding_top', '0' );
$template_padding_bottom   = mailtpl_get_options( 'template_padding_bottom', '0' );
$template_box_shadow       = mailtpl_get_options( 'template_box_shadow', 5 );
$note_check       = mailtpl_get_options( 'notes_outside_table', 'false' );

if ( $responsive_check ) {
	$content_width = '100%';
}
$mailtpl_settings = get_option( 'mailtpl_opts', array() );
$fonts_url        = mailtpl_fonts_generators( $mailtpl_settings );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>">
	<title><?php echo wp_kses_post( get_bloginfo( 'name', 'display' ) ); ?></title>
	<style type="text/css">
		#body_content_inner a {
			color: <?php echo sanitize_hex_color( $body_links_color ); ?> !important;
		}
		#body_content_inner table.td tr {
			background-color: <?php echo sanitize_hex_color( mailtpl_get_options( 'items_table_background_color', '' ) ); ?>;

		}

        #body_content_inner table.td tr td, #body_content_inner table.td tr th {
            padding: <?php echo esc_attr(mailtpl_get_options('items_table_padding_top_bottom', '5')); ?>px <?php echo esc_attr(mailtpl_get_options('items_table_padding_left_right', '5')); ?>px;
		}

		#body_content_inner table.td tr.order_item td, #body_content_inner table.td tr td, #body_content_inner table.td tr th.td {
			<?php if ($order_style === 'light') : ?>
				border-left:unset!important;
				border-right:unset!important;
				border: <?php echo esc_attr(mailtpl_get_options('items_table_border_width', 1)); ?>px <?php echo esc_attr(mailtpl_get_options('items_table_border_style', 'solid')); ?> <?php echo sanitize_hex_color(mailtpl_get_options('items_table_border_color', '#000000')); ?>;
				<?php else : ?>
			border: <?php echo esc_attr(mailtpl_get_options('items_table_border_width', 1)); ?>px <?php echo esc_attr(mailtpl_get_options('items_table_border_style', 'solid')); ?> <?php echo sanitize_hex_color(mailtpl_get_options('items_table_border_color', '#000000')); ?>;
			<?php endif; ?>	

		}


		#body_content_inner table.td tr:nth-child(odd) {
			background-color: <?php echo sanitize_hex_color( mailtpl_get_options( 'items_table_background_odd_color', '' ) ); ?>;
		}

		#body_content_inner * {
			font-family: <?php echo esc_attr( mailtpl_get_options( 'body_font_family', '' ) ); ?> !important;
			font-weight: <?php echo esc_attr( mailtpl_get_options( 'body_font_weight', '' ) ); ?> !important;
		}
	</style>
	<?php
	if ( $fonts_url ) {
		// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_style( 'mailtpl-woo-fonts', esc_url( $fonts_url ), array(), null, 'all' );
	}
	?>
</head>
<body
    style="
        background-color: <?php echo sanitize_hex_color( $template_bg_color ); ?>;
        padding-top: <?php echo esc_attr( $template_padding_top ); ?>px;
        padding-bottom: <?php echo esc_attr( $template_padding_bottom ); ?>px;
    "
    <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0"
    marginwidth="0"
    topmargin="0"
    marginheight="0"
    offset="0"
    class="mailtpl-woo-wrap order-items-<?php echo esc_attr( $order_style ); ?> k-responsive-<?php echo esc_attr( $responsive_mode ); ?> title-style-<?php echo esc_attr( $h2_style ); ?> email-id-<?php echo esc_attr( $key ); ?>">
<div id="wrapper" style="
        background-color: <?php echo sanitize_hex_color( $template_bg_color ); ?>;
" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
	<table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%" style="width: max-width: <?php echo esc_attr( $email_body_size ); ?>px;">
		<tr>
			<td align="center" valign="top">
				<?php if ( 'inside' !== $header_placement ) : ?>
					<table id="template_header_image_container">
						<tr id="template_header_image">
							<td align="center" valign="middle">
								<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header_image_table" style="background: <?php echo esc_attr( $image_container_bg_color ); ?>;">
									<tr>
										<td align="center" valign="middle" style="text-align:<?php echo esc_attr( $image_align ); ?>;padding: <?php echo esc_attr( $image_container_padding ); ?>px 0;">
                                            <p style="margin-top: 0;">
                                                <?php if ( $header_image_link ) : ?>
                                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" style="display:block;text-decoration:none;">
                                                       <?php endif; ?>
											            <?php if ( $img ) : ?>
														    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" width="<?php echo esc_attr( $header_image_max_width ); ?>px" style="width: <?php echo esc_attr( $header_image_max_width ); ?>px" />
											            <?php endif; ?>
														<?php if ( $header_image_link ) : ?>
													</a>
                                                <?php endif; ?>
                                            </p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				<?php endif; ?>
				<table border="0" cellpadding="0" cellspacing="0" width="<?php echo esc_attr( $content_width ); ?>" id="template_container" style="
                    border-top: <?php echo esc_attr( $border_top ); ?>px;
                    border-bottom: <?php echo esc_attr( $border_bottom ); ?>px;
                    border-left: <?php echo esc_attr( $border_left ); ?>px;
                    border-right:<?php echo esc_attr( $border_right ); ?>px;
                    border-color: <?php echo sanitize_hex_color( $body_border_color ); ?>;
                    border-style: solid;
                    border-radius: <?php echo esc_attr( $border_radius ); ?>px;
                    box-shadow: <?php echo mailtpl_box_shadow( $template_box_shadow ); ?>;
                ">
					<tr>
						<td>
							<!-- uzair -->
							<?php if ( 'inside' === $header_placement ) : ?>
								<table id="template_header_image_container" style="background: <?php echo esc_attr( $image_container_bg_color ); ?>">
									<tr id="template_header_image">
										<td align="center" valign="middle">
											<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header_image_table">
												<tr>
													<td align="center" valign="middle" style="text-align:<?php echo esc_attr( $image_align ); ?>;padding: <?php echo esc_attr( $image_container_padding ); ?>px 0;">
                                                        <p>
                                                            <?php if ( $header_image_link ) : ?>
                                                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" style="text-decoration:none;display:block;">
																	<?php endif; ?>
														            <?php if ( $img ) : ?>
																	    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" width="<?php echo esc_attr( $header_image_max_width ); ?>px" style="width: <?php echo esc_attr( $header_image_max_width ); ?>px"/>
														            <?php endif; ?>
																	<?php if ( $header_image_link ) : ?>
																</a>
                                                            <?php endif; ?>
                                                        </p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							<?php endif; ?>
							<!-- Header section start -->
							<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="background-color: <?php echo sanitize_hex_color( $heading_background_color ); ?>">
								<tr>
									<td id="header_wrapper" style="
										text-align: <?php echo esc_attr( $header_text_align ); ?>;
										padding: <?php echo esc_attr( $header_padding_top ); ?>px <?php echo esc_attr( $header_padding_left_right ); ?>px <?php echo esc_attr( $header_padding_bottom ); ?>px <?php echo esc_attr( $header_padding_left_right ); ?>px;
									">
										<?php if ( 'above' === $subtitle_placement ) : ?>
											<div class="subtitle" style="
												font-family: <?php echo esc_attr( $subtitle_font_family ); ?>;
												font-weight: <?php echo esc_attr( $subtitle_font_weight ); ?>;
												font-style: <?php echo esc_attr( $subtitle_font_style ); ?>;
												font-size: <?php echo esc_attr( $subtitle_font_size ); ?>px;
												color: <?php echo sanitize_hex_color( $subtitle_font_color ); ?>;
											">
												<?php echo wp_kses_post( $email_subtitle ); ?>
												
											</div>
										<?php endif; ?>
										<h1 style="
											font-family: <?php echo esc_attr( mailtpl_get_options( 'header_font_family', '' ) ); ?>;
											text-align: <?php echo esc_attr( $header_text_align ); ?>;
											font-size: <?php echo esc_attr( $header_title_font_size ); ?>px;
											color: <?php echo sanitize_hex_color( $heading_color ); ?>;
											font-weight: <?php echo esc_attr( mailtpl_get_options( 'header_font_weight', '' ) ); ?>;
										">
											<?php echo wp_kses_post( $email_heading ); ?>
										</h1>
										<?php if ( 'below' === $subtitle_placement ) : ?>
											<div class="subtitle" style="
												font-family: <?php echo esc_attr( $subtitle_font_family ); ?>;
												font-weight: <?php echo esc_attr( $subtitle_font_weight ); ?>;
												font-style: <?php echo esc_attr( $subtitle_font_style ); ?>;
												font-size: <?php echo esc_attr( $subtitle_font_size ); ?>px;
												color: <?php echo sanitize_hex_color( $subtitle_font_color ); ?>;
											">
												<?php echo wp_kses_post( $email_subtitle ); ?>
											</div>
										<?php endif; ?>
									</td>
								</tr>
							</table>
							<!-- Header section end -->
						</td>
					</tr>
					<tr>
						<td align="center" valign="top">
							<!-- Body Section start -->
							<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body">
								<tr>
									<td valign="top" id="body_content" style="
										background: <?php echo sanitize_hex_color( $body_background_color ); ?>;
										padding: <?php echo esc_attr( $body_top_padding ); ?>px <?php echo esc_attr( $body_left_right_padding ); ?>px <?php echo esc_attr( $body_bottom_padding ); ?>px <?php echo esc_attr( $body_left_right_padding ); ?>px;
									">
										<!-- Content Section start -->
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td valign="top">
													<div id="body_content_inner" class="<?php echo esc_attr($note_check) ? 'note_check' : ''; ?>" style="
															font-size: <?php echo esc_attr( $body_content_font_size ); ?>px;
															color: <?php echo sanitize_hex_color( $body_content_color ); ?>;
															font-family: <?php echo esc_attr( mailtpl_get_options( 'body_font_family', '' ) ); ?>;
															font-weight: <?php echo esc_attr( mailtpl_get_options( 'body_font_weight', '' ) ); ?>;
															">
														<!-- Body Section start -->
