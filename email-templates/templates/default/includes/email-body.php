<?php
/**
 * Email body template
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

?>

<!-- Template body container -->
<p>
	<?php
	esc_attr_e( 'Here you will see the email content that is normally sent in WordPress.', 'email-templates' );
	?>
</p>
<p>
	<?php
	esc_attr_e( 'The email template is responsive an fully customizable. I hope you enjoy it!', 'email-templates' );
	?>
</p>
<p>
	<?php
	echo sprintf(
	// Translators: %1$s for WordPress support url.
		wp_kses_post( __( 'We would like to ask you a little favour. If you are happy with the plugin and can take a minute please <a href="%1$s" target="_blank">leave a nice review</a> on WordPress. It will be a tremendous help for us!', 'email-templates' ) ),
		'https://wordpress.org/support/view/plugin-reviews/email-templates?filter=5'
	);
	?>
</p>

<h3><?php esc_attr_e( 'Placeholders', 'email-templates' ); ?></h3>

<ul>
	<li>%%BLOG_URL%%</li>
	<li>%%HOME_URL%%</li>
	<li>%%BLOG_NAME%%</li>
	<li>%%BLOG_DESCRIPTION%%</li>
	<li>%%ADMIN_EMAIL%%</li>
	<li>%%DATE%%</li>
	<li>%%TIME%%</li>
	<li>%%USER_EMAIL%% (not on sendgrid)</li>
</ul>
<!-- ./ Template body container -->
