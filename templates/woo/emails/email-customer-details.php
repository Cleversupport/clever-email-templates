<?php
/**
 * Customer reset password email
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
 *
 * @package Email Templates
 */

defined( 'ABSPATH' ) || exit;

?>

<?php if ( ! empty( $fields ) ) : ?>
	<div class="email-spacing-wrap" style="margin-top: 40px;">
		<h2><?php esc_attr_e( 'Customer Details', 'woocommerce' ); ?></h2>
		<ul>
			<?php foreach ( $fields as $field ) : ?>
				<li>
					<strong><?php echo esc_html( $field['label'] ); ?>:</strong>
					<span class="text"><?php echo wp_kses_post( $field['value'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
