<?php
/**
 * Email content template
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

if ( is_customize_preview() ) {
	include_once apply_filters( 'mailtpl_customizer_template_message', MAILTPL_PLUGIN_DIR . 'templates/default/includes/email-body.php' );
} else {
	?>
	%%MAILCONTENT%%
	<?php
}
