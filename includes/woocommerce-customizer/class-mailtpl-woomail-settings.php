<?php
/**
 * Customizer Mail Settings
 *
 * @package Mailtpl WooCommerce Email Composer
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Mailtpl_Woomail_Settings' ) ) {
	/**
	 * Customizer Settings
	 */
	class Mailtpl_Woomail_Settings {
		/**
		 * The panels for customizer.
		 *
		 * @var null
		 */
		private static $panels = null;

		/**
		 * The sections for customizer.
		 *
		 * @var null
		 */
		private static $sections = null;

		/**
		 * The settings for customizer.
		 *
		 * @var null
		 */
		private static $settings = null;

		/**
		 * The woo settings copy for customizer.
		 *
		 * @var null
		 */
		private static $woo_copy_settings = null;

		/**
		 * The woo settings for customizer.
		 *
		 * @var null
		 */
		private static $woo_settings = null;

		/**
		 * The default values for customizer.
		 *
		 * @var null
		 */
		private static $default_values = null;

		/**
		 * The order ids.
		 *
		 * @var null
		 */
		private static $order_ids = null;

		/**
		 * The emails types.
		 *
		 * @var null
		 */
		private static $email_types = null;

		/**
		 * The available text edit email types.
		 *
		 * @var null
		 */
		private static $customized_email_types = null;

		/**
		 * The available font options.
		 *
		 * @var array
		 */
		public static $font_family_mapping = array(
			'helvetica'   => '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif',
			'arial'       => 'Arial, Helvetica, sans-serif',
			'arial_black' => '"Arial Black", Gadget, sans-serif',
			'courier'     => '"Courier New", Courier, monospace',
			'impact'      => 'Impact, Charcoal, sans-serif',
			'lucida'      => '"Lucida Sans Unicode", "Lucida Grande", sans-serif',
			'palatino'    => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
			'georgia'     => 'Georgia, serif',
		);

		/**
		 * Get our prebuilt tempaltes.
		 *
		 * @var array
		 */
		public static $prebuilt_templates_mapping = array(
			'kt_full'   => 'assets/images/kt_full_template.jpg',
			'kt_skinny' => 'assets/images/kt_skinny_template.jpg',
			'kt_flat'   => 'assets/images/kt_flat_template.jpg',
		);


		/**
		 * Get default values
		 *
		 * @access public
		 * @return array
		 */
		public static function get_default_values() {
			// Define default values.
			if ( is_null( self::$default_values ) ) {
				$default_values       = array(
					'preview_order_id'                     => 'mockup',
					'email_type'                           => 'new_order',
					'email_templates'                      => 'default',
					'body_background_color'                => '#fdfdfd',
					'border_radius'                        => '3',
					'border_width'                         => '1',
					'border_color'                         => '#dedede',
					'responsive_mode'                      => false,
					'shadow'                               => '1',
					'content_width'                        => '600',
					'email_padding'                        => '70',
					'background_color'                     => '#ffffff',
					'header_image_maxwidth'                => '300',
					'header_image_align'                   => 'center',
					'header_image_background_color'        => 'transparent',
					'header_image_padding_top_bottom'      => '0',
					'header_image_placement'               => 'outside',
					'woocommerce_waitlist_mailout_body'    => __( 'Hi There,', 'email-templates' ),
					'woocommerce_waitlist_mailout_heading' => __( '{product_title} is now back in stock at {site_title}', 'email-templates' ),
					'woocommerce_waitlist_mailout_subject' => __( 'A product you are waiting for is back in stock', 'email-templates' ),
					'new_renewal_order_heading'            => __( 'New customer order', 'email-templates' ),
					'new_renewal_order_subject'            => __( '[{site_title}] New customer order ({order_number}) - {order_date}', 'email-templates' ),
					'new_renewal_order_body'               => __( 'You have received a subscription renewal order from {customer_full_name}. Their order is as follows:', 'email-templates' ),
					'customer_processing_renewal_order_heading' => __( 'Thank you for your order', 'email-templates' ),
					'customer_processing_renewal_order_subject' => __( 'Your {site_title} order receipt from {order_date}', 'email-templates' ),
					'customer_processing_renewal_order_body' => __( 'Your subscription renewal order has been received and is now being processed. Your order details are shown below for your reference:', 'email-templates' ),
					'customer_completed_renewal_order_heading' => __( 'Your order is complete', 'email-templates' ),
					'customer_completed_renewal_order_subject' => __( 'Your {site_title} order from {order_date} is complete', 'email-templates' ),
					'customer_completed_renewal_order_body' => __( 'Hi there. Your subscription renewal order with {site_title} has been completed. Your order details are shown below for your reference:', 'email-templates' ),
					'customer_completed_switch_order_heading' => __( 'Your order is complete', 'email-templates' ),
					'customer_completed_switch_order_subject' => __( 'Your {site_title} order from {order_date} is complete', 'email-templates' ),
					'customer_completed_switch_order_body' => __( 'Hi there. You have successfully changed your subscription items on {site_title}. Your new order and subscription details are shown below for your reference:', 'email-templates' ),
					'customer_renewal_invoice_heading'     => __( 'Invoice for order {order_number}', 'email-templates' ),
					'customer_renewal_invoice_subject'     => __( 'Invoice for order {order_number}', 'email-templates' ),
					'customer_renewal_invoice_body'        => __( 'An invoice has been created for you to renew your subscription with {site_title}. To pay for this invoice please use the following link: {invoice_pay_link}', 'email-templates' ),
					'customer_renewal_invoice_btn_switch'  => false,
					'customer_renewal_invoice_body_failed' => __( 'The automatic payment to renew your subscription with {site_title} has failed. To reactivate the subscription, please login and pay for the renewal from your account page: {invoice_pay_link}', 'email-templates' ),
					'cancelled_subscription_heading'       => __( 'Subscription Cancelled', 'email-templates' ),
					'cancelled_subscription_subject'       => __( '[{site_title}] Subscription Cancelled', 'email-templates' ),
					'cancelled_subscription_body'          => __( 'A subscription belonging to {customer_full_name} has been cancelled. Their subscription\'s details are as follows:', 'email-templates' ),
					'customer_payment_retry_heading'       => __( 'Automatic payment failed for order {order_number}', 'email-templates' ),
					'customer_payment_retry_subject'       => __( 'Automatic payment failed for {order_number}, we will retry {retry_time}', 'email-templates' ),
					'customer_payment_retry_body'          => '',
					'customer_payment_retry_override'      => false,
					'customer_payment_retry_btn_switch'    => false,
					'admin_payment_retry_heading'          => __( 'Automatic renewal payment failed', 'email-templates' ),
					'admin_payment_retry_subject'          => __( '[{site_title}] Automatic payment failed for {order_number}, retry scheduled to run {retry_time}', 'email-templates' ),
					'admin_payment_retry_body'             => '',
					'admin_payment_retry_override'         => false,
					'new_order_heading'                    => __( 'New customer order', 'email-templates' ),
					'cancelled_order_heading'              => __( 'Cancelled order', 'email-templates' ),
					'customer_processing_order_heading'    => __( 'Thank you for your order', 'email-templates' ),
					'new_order_additional_content'         => __( 'Congratulations on the sale!', 'email-templates' ),
					'customer_processing_order_additional_content' => __( 'Thanks for using {site_address}!', 'email-templates' ),
					'customer_completed_order_additional_content' => __( 'Thanks for shopping with us.', 'email-templates' ),
					'customer_refunded_order_additional_content' => __( 'We hope to see you again soon.', 'email-templates' ),
					'customer_on_hold_order_additional_content' => __( 'We look forward to fulfilling your order soon.', 'email-templates' ),
					'customer_new_account_additional_content' => __( 'We look forward to seeing you soon.', 'email-templates' ),
					'customer_reset_password_additional_content' => __( 'Thanks for reading.', 'email-templates' ),
					'customer_completed_order_heading'     => __( 'Your order is complete', 'email-templates' ),
					'customer_refunded_order_heading_full' => __( 'Order {order_number} details', 'email-templates' ),
					'customer_refunded_order_heading_partial' => __( 'Your order has been partially refunded', 'email-templates' ),
					'customer_on_hold_order_heading'       => __( 'Thank you for your order', 'email-templates' ),
					'customer_invoice_heading'             => __( 'Invoice for order {order_number}', 'email-templates' ),
					'customer_invoice_heading_paid'        => __( 'Your order details', 'email-templates' ),
					'failed_order_heading'                 => __( 'Failed order', 'email-templates' ),
					'customer_new_account_heading'         => __( 'Welcome to {site_title}', 'email-templates' ),
					'customer_note_heading'                => __( 'A note has been added to your order', 'email-templates' ),
					'customer_reset_password_heading'      => __( 'Password reset instructions', 'email-templates' ),
					'customer_reset_password_btn_switch'   => false,
					'new_order_subject'                    => __( '[{site_title}] New customer order ({order_number}) - {order_date}', 'email-templates' ),
					'cancelled_order_subject'              => __( '[{site_title}] Cancelled order ({order_number})', 'email-templates' ),
					'customer_processing_order_subject'    => __( 'Your {site_title} order receipt from {order_date}', 'email-templates' ),
					'customer_completed_order_subject'     => __( 'Your {site_title} order from {order_date} is complete', 'email-templates' ),
					'customer_refunded_order_subject_full' => __( 'Your {site_title} order from {order_date} has been refunded', 'email-templates' ),
					'customer_refunded_order_subject_partial' => __( 'Your {site_title} order from {order_date} has been partially refunded', 'email-templates' ),
					'customer_on_hold_order_subject'       => __( 'Your {site_title} order receipt from {order_date}', 'email-templates' ),
					'customer_invoice_subject'             => __( 'Invoice for order {order_number}', 'email-templates' ),
					'customer_invoice_subject_paid'        => __( 'Your {site_title} order from {order_date}', 'email-templates' ),
					'failed_order_subject'                 => __( '[{site_title}] Failed order ({order_number})', 'email-templates' ),
					'customer_new_account_subject'         => __( 'Your account on {site_title}', 'email-templates' ),
					'customer_note_subject'                => __( 'Note added to your {site_title} order from {order_date}', 'email-templates' ),
					'customer_reset_password_subject'      => __( 'Password reset for {site_title}', 'email-templates' ),
					'new_order_body'                       => __( 'You have received an order from {customer_full_name}. The order is as follows:', 'email-templates' ),
					'cancelled_order_body'                 => __( 'The order {order_number} from {customer_full_name} has been cancelled. The order was as follows:', 'email-templates' ),
					'customer_processing_order_body'       => __( 'Your order has been received and is now being processed. Your order details are shown below for your reference:', 'email-templates' ),
					'customer_completed_order_body'        => __( 'Hi there. Your recent order on {site_title} has been completed. Your order details are shown below for your reference:', 'email-templates' ),
					'customer_refunded_order_switch'       => true,
					'customer_refunded_order_body_full'    => __( 'Your order on {site_title} has been refunded. There are more details below for your reference:', 'email-templates' ),
					'customer_refunded_order_body_partial' => __( 'Your order on {site_title} has been partially refunded. There are more details below for your reference:', 'email-templates' ),
					'customer_on_hold_order_body'          => __( 'Your order is on-hold until we confirm payment has been received. Your order details are shown below for your reference:', 'email-templates' ),
					'customer_invoice_switch'              => true,
					'customer_invoice_btn_switch'          => false,
					'customer_invoice_body'                => __( 'An order has been created for you on {site_title}. {invoice_pay_link}', 'email-templates' ),
					'customer_invoice_body_paid'           => '',
					'failed_order_body'                    => __( 'Payment for order {order_number} from {customer_full_name} has failed. The order was as follows:', 'email-templates' ),
					'customer_new_account_btn_switch'      => false,
					'customer_new_account_account_section' => true,
					'customer_new_account_body'            => __( 'Thanks for creating an account on {site_title}. Your username is {customer_username}', 'email-templates' ),
					'customer_note_body'                   => __( 'Hello, a note has just been added to your order:', 'email-templates' ),
					'customer_reset_password_body'         => __(
						'Someone requested that the password be reset for the following account:

Username: {customer_username}

If this was a mistake, just ignore this email and nothing will happen.

To reset your password, visit the following address:',
						'email-templates'
					),
					'WC_Memberships_User_Membership_Ended_Email_heading' => __( 'Renew your {membership_plan}', 'email-templates' ),
					'WC_Memberships_User_Membership_Ended_Email_subject' => __( 'Your {site_title} membership has expired', 'email-templates' ),
					'WC_Memberships_User_Membership_Activated_Email_heading' => __( 'You can now access {membership_plan}', 'email-templates' ),
					'WC_Memberships_User_Membership_Activated_Email_subject' => __( 'Your {site_title} membership is now active!', 'email-templates' ),
					'WC_Memberships_User_Membership_Ending_Soon_Email_heading' => __( 'An update about your {membership_plan}', 'email-templates' ),
					'WC_Memberships_User_Membership_Ending_Soon_Email_subject' => __( 'Your {site_title} membership ends soon!', 'email-templates' ),
					'WC_Memberships_User_Membership_Note_Email_heading' => __( 'A note has been added about your membership', 'email-templates' ),
					'WC_Memberships_User_Membership_Note_Email_subject' => __( 'Note added to your {site_title} membership', 'email-templates' ),
					'WC_Memberships_User_Membership_Renewal_Reminder_Email_heading' => __( 'You can renew your {membership_plan}', 'email-templates' ),
					'WC_Memberships_User_Membership_Renewal_Reminder_Email_subject' => __( 'Renew your {site_title} membership!', 'email-templates' ),
					'customer_delivered_order_heading'     => __( 'Thanks for shopping with us', 'email-templates' ),
					'customer_delivered_order_subject'     => __( 'Your {site_title} order is now delivered', 'email-templates' ),
					'customer_delivered_order_body'        => __(
						'Hi {customer_full_name}
						Your {site_title} order has been marked delivered on our side.',
						'email-templates'
					),
					'header_background_color'              => get_option( 'woocommerce_email_base_color' ),
					'header_text_align'                    => 'left',
					'header_padding_top_bottom'            => '36',
					'header_padding_left_right'            => '48',
					'heading_font_size'                    => '30',
					'heading_line_height'                  => '40',
					'heading_font_family'                  => 'helvetica',
					'heading_font_style'                   => 'normal',
					'heading_color'                        => '#ffffff',
					'heading_font_weight'                  => '300',
					'subtitle_placement'                   => 'below',
					'subtitle_font_size'                   => '18',
					'subtitle_line_height'                 => '24',
					'subtitle_font_family'                 => 'helvetica',
					'subtitle_font_style'                  => 'normal',
					'subtitle_color'                       => '#ffffff',
					'subtitle_font_weight'                 => '300',
					'content_padding'                      => '48',
					'content_padding_bottom'               => '0',
					'text_color'                           => '#737373',
					'font_family'                          => 'helvetica',
					'font_size'                            => '14',
					'line_height'                          => '24',
					'font_weight'                          => '400',
					'link_color'                           => get_option( 'woocommerce_email_base_color' ),
					'h2_font_size'                         => '18',
					'h2_line_height'                       => '26',
					'h2_font_family'                       => 'helvetica',
					'h3_font_style'                        => 'normal',
					'h2_color'                             => get_option( 'woocommerce_email_base_color' ),
					'h2_font_weight'                       => '700',
					'h2_margin_bottom'                     => '18',
					'h2_padding_top'                       => '0',
					'h2_margin_top'                        => '0',
					'h2_padding_bottom'                    => '0',
					'h2_text_transform'                    => 'none',
					'h2_separator_color'                   => get_option( 'woocommerce_email_base_color' ),
					'h2_separator_height'                  => '1',
					'h2_separator_style'                   => 'solid',
					'h3_font_size'                         => '16',
					'h3_line_height'                       => '20',
					'h3_font_family'                       => 'helvetica',
					'h3_font_style'                        => 'normal',
					'h3_color'                             => '#787878',
					'h3_font_weight'                       => '500',
					'btn_border_width'                     => '0',
					'btn_border_radius'                    => '4',
					'btn_border_color'                     => '#dedede',
					'btn_font_family'                      => 'helvetica',
					'btn_color'                            => '#ffffff',
					'btn_font_weight'                      => '600',
					'btn_left_right_padding'               => '8',
					'btn_top_bottom_padding'               => '10',
					'btn_size'                             => '16',
					'order_items_style'                    => 'normal',
					'order_items_image'                    => 'normal',
					'order_items_image_size'               => '100x50',
					'items_table_border_width'             => '1',
					'items_table_background_color'         => '#ffffff',
					'items_table_background_odd_color'     =>'#ffffff',
					'address_box_background_color'     => '#ffffff',
					'items_table_padding'                  => '12',
					'order_heading_style'                  => 'normal',
					'notes_outside_table'                  => false,
					'addresses_padding'                    => '12',
					'addresses_border_width'               => '1',
					'addresses_border_color'               => '#e5e5e5',
					'addresses_border_style'               => 'solid',
					'addresses_background_color'           => '',
					'addresses_text_color'                 => '#8f8f8f',
					'addresses_text_align'                 => 'left',
					'footer_background_placement'          => 'inside',
					'footer_background_color'              => '',
					'footer_top_padding'                   => '0',
					'footer_bottom_padding'                => '48',
					'footer_left_right_padding'            => '48',
					'footer_social_enable'                 => true,
					'footer_social_title_color'            => '#000000',
					'footer_social_title_font_family'      => 'helvetica',
					'footer_social_title_font_size'        => '18',
					'footer_social_title_font_weight'      => '400',
					'footer_social_top_padding'            => '0',
					'footer_social_bottom_padding'         => '0',
					'footer_social_border_width'           => '0',
					'footer_social_border_color'           => '#dddddd',
					'footer_social_border_style'           => 'solid',
					'footer_text_align'                    => 'center',
					'footer_font_size'                     => '12',
					'footer_font_family'                   => 'helvetica',
					'footer_color'                         => '#555555',
					'footer_font_weight'                   => '400',
					'footer_credit_bottom_padding'         => '0',
					'footer_credit_top_padding'            => '0',
					'items_table_border_color'             => '#e4e4e4',
					'items_table_border_style'             => 'solid',
					'footer_content_text'                  => get_option( 'woocommerce_email_footer_text', '' ),
					'email_recipient'                      => get_option( 'admin_email' ),
					'customer_ekomi_heading'               => _x( 'Please rate your Order', 'ekomi', 'email-templates' ),
					'customer_new_account_activation_heading' => __( 'Account activation {site_title}', 'email-templates' ),
					'customer_paid_for_order_heading'      => __( 'Payment received', 'email-templates' ),
					'customer_revocation_heading'          => __( 'Your revocation', 'email-templates' ),
					'customer_sepa_direct_debit_mandate'   => __( 'SEPA Direct Debit Mandate', 'email-templates' ),
					'customer_trusted_shops'               => _x( 'Please rate your Order', 'trusted-shops', 'email-templates' ),
					'woocommerce_waitlist_mailout_hide_content' => false,
					'header_image_link'                    => true,
					'email_schema'                         => false,
				);
				self::$default_values = apply_filters( 'mailtpl_woomail_email_settings_default_values', $default_values );
			}

			// Return default values.
			return self::$default_values;
		}

		/**
		 * Get default values
		 *
		 * @access public
		 * @param string $key the setting key.
		 * @return string
		 */
		public static function get_default_value( $key ) {
			// Get default values.
			$default_values = self::get_default_values();

			// Check if such key exists and return default value.
			return isset( $default_values[ $key ] ) ? $default_values[ $key ] : '';
		}

		/**
		 * Get border styles
		 *
		 * @access public
		 * @return array
		 */
		public static function get_border_styles() {
			return array(
				'none'   => __( 'none', 'email-templates' ),
				'hidden' => __( 'hidden', 'email-templates' ),
				'dotted' => __( 'dotted', 'email-templates' ),
				'dashed' => __( 'dashed', 'email-templates' ),
				'solid'  => __( 'solid', 'email-templates' ),
				'double' => __( 'double', 'email-templates' ),
				'groove' => __( 'groove', 'email-templates' ),
				'ridge'  => __( 'ridge', 'email-templates' ),
				'inset'  => __( 'inset', 'email-templates' ),
				'outset' => __( 'outset', 'email-templates' ),
			);
		}

		/**
		 * Get text align options
		 *
		 * @access public
		 * @return array
		 */
		public static function get_text_aligns() {
			return array(
				'left'    => __( 'Left', 'email-templates' ),
				'center'  => __( 'Center', 'email-templates' ),
				'right'   => __( 'Right', 'email-templates' ),
				'justify' => __( 'Justify', 'email-templates' ),
			);
		}
		/**
		 * Get image align options
		 *
		 * @access public
		 * @return array
		 */
		public static function get_image_aligns() {
			return array(
				'left'   => __( 'Left', 'email-templates' ),
				'center' => __( 'Center', 'email-templates' ),
				'right'  => __( 'Right', 'email-templates' ),
			);
		}
		/**
		 * Get Order Ids
		 *
		 * @access public
		 * @return array
		 */
		public static function get_order_ids() {
			if ( is_null( self::$order_ids ) ) {
				$order_array           = array();
				$order_array['mockup'] = __( 'Mockup Order', 'email-templates' );
				$orders                = new WP_Query(
					array(
						'post_type'      => 'shop_order',
						'post_status'    => array_keys( wc_get_order_statuses() ),
						'posts_per_page' => 20,
					)
				);
				if ( $orders->posts ) {
					foreach ( $orders->posts as $order ) {
						// Get order object.
						$order_object                           = new WC_Order( $order->ID );
						$order_array[ $order_object->get_id() ] = $order_object->get_id() . ' - ' . $order_object->get_billing_first_name() . ' ' . $order_object->get_billing_last_name();
					}
				}
				self::$order_ids = $order_array;
			}
			return self::$order_ids;
		}
		/**
		 * Get font families
		 *
		 * @access public
		 * @return array
		 */
		public static function get_font_families() {
			return apply_filters( 'mailtpl_woomail_email_font_families', self::$font_family_mapping );
		}

		/**
		 * Get Email Types
		 *
		 * @access public
		 * @return array
		 */
		public static function get_email_types() {
			if ( is_null( self::$email_types ) ) {
				$types = array(
					'new_order'                 => __( 'New Order', 'email-templates' ),
					'cancelled_order'           => __( 'Cancelled Order', 'email-templates' ),
					'customer_processing_order' => __( 'Customer Processing Order', 'email-templates' ),
					'customer_completed_order'  => __( 'Customer Completed Order', 'email-templates' ),
					'customer_refunded_order'   => __( 'Customer Refunded Order', 'email-templates' ),
					'customer_on_hold_order'    => __( 'Customer On Hold Order', 'email-templates' ),
					'customer_invoice'          => __( 'Customer Invoice', 'email-templates' ),
					'failed_order'              => __( 'Failed Order', 'email-templates' ),
					'customer_new_account'      => __( 'Customer New Account', 'email-templates' ),
					'customer_note'             => __( 'Customer Note', 'email-templates' ),
					'customer_reset_password'   => __( 'Customer Reset Password', 'email-templates' ),
				);
				if ( class_exists( 'WC_Subscriptions' ) ) {
					$types = array_merge(
						$types,
						array(
							'new_renewal_order'        => __( 'New Renewal Order', 'email-templates' ),
							'customer_processing_renewal_order' => __( 'Customer Processing Renewal Order', 'email-templates' ),
							'customer_completed_renewal_order' => __( 'Customer Completed Renewal Order', 'email-templates' ),
							'customer_completed_switch_order' => __( 'Customer Completed Switch Order', 'email-templates' ),
							'customer_renewal_invoice' => __( 'Customer Renewal Invoice', 'email-templates' ),
							'cancelled_subscription'   => __( 'Cancelled Subscription', 'email-templates' ),
							'customer_payment_retry'   => __( 'Customer Payment Retry', 'email-templates' ),
							'admin_payment_retry'      => __( 'Payment Retry', 'email-templates' ),
						)
					);
				}
				if ( class_exists( 'WC_Memberships' ) ) {
					$types = array_merge(
						$types,
						array(
							'WC_Memberships_User_Membership_Note_Email'             => __( 'User Membership Note', 'email-templates' ),
							'WC_Memberships_User_Membership_Ending_Soon_Email'      => __( 'User Membership Ending Soon', 'email-templates' ),
							'WC_Memberships_User_Membership_Ended_Email'            => __( 'User Membership Ended', 'email-templates' ),
							'WC_Memberships_User_Membership_Renewal_Reminder_Email' => __( 'User Membership Renewal Reminder', 'email-templates' ),
							'WC_Memberships_User_Membership_Activated_Email'        => __( 'User Membership Activated', 'email-templates' ),
						)
					);
				}
				if ( class_exists( 'WCMp' ) ) {
					$types = array_merge(
						$types,
						array(
							'vendor_new_account'          => __( 'New Vendor Account', 'email-templates' ),
							'admin_new_vendor'            => __( 'Admin New Vendor Account', 'email-templates' ),
							'approved_vendor_new_account' => __( 'Approved Vendor Account', 'email-templates' ),
							'rejected_vendor_new_account' => __( 'Rejected Vendor Account', 'email-templates' ),
							'vendor_new_order'            => __( 'Vendor New order', 'email-templates' ),
							'notify_shipped'              => __( 'Notify as Shipped.', 'email-templates' ),
							'admin_new_vendor_product'    => __( 'New Vendor Product', 'email-templates' ),
							'admin_added_new_product_to_vendor' => __( 'New Vendor Product By Admin', 'email-templates' ),
							'vendor_commissions_transaction' => __( 'Transactions (for Vendor)', 'email-templates' ),
							'vendor_direct_bank'          => __( 'Commission Paid (for Vendor) by BAC', 'email-templates' ),
							'admin_widthdrawal_request'   => __( 'Withdrawal request to Admin from Vendor by BAC', 'email-templates' ),
							'vendor_orders_stats_report'  => __( 'Vendor orders stats report', 'email-templates' ),
							'vendor_contact_widget_email' => __( 'Vendor Contact Email', 'email-templates' ),
						)
					);
				}
				if ( class_exists( 'WooCommerce_Germanized' ) ) {
					$types = array_merge(
						$types,
						array(
							'customer_ekomi'          => __( 'eKomi Review Reminder', 'email-templates' ),
							'customer_new_account_activation' => __( 'New account activation', 'email-templates' ),
							'customer_paid_for_order' => __( 'Paid for order', 'email-templates' ),
							'customer_revocation'     => __( 'Revocation', 'email-templates' ),
							'customer_trusted_shops'  => __( 'Trusted Shops Review Reminder', 'email-templates' ),
						)
					);
				}
				if ( class_exists( 'WooCommerce_Waitlist_Plugin' ) ) {
					$types = array_merge(
						$types,
						array(
							'woocommerce_waitlist_mailout' => __( 'Waitlist Mailout', 'email-templates' ),
						)
					);
				}
				if ( class_exists( 'WC_Stripe' ) ) {
					$types = array_merge(
						$types,
						array(
							'failed_renewal_authentication' => __( 'Failed Subscription Renewal SCA Authentication', 'email-templates' ),
							'failed_preorder_sca_authentication' => __( 'Pre-order Payment Action Needed', 'email-templates' ),
						)
					);
				}
				if ( class_exists( 'WC_Stripe' ) && class_exists( 'WC_Subscriptions' ) ) {
					$types = array_merge(
						$types,
						array(
							'failed_authentication_requested' => __( 'Payment Authentication Requested Email', 'email-templates' ),
						)
					);
				}

				self::$email_types = apply_filters( 'mailtpl_woomail_email_types', $types );
			}

			return self::$email_types;
		}

		/**
		 * Get Email Types
		 *
		 * @access public
		 * @return array
		 */
		public static function get_customized_email_types() {
			if ( is_null( self::$customized_email_types ) ) {
				$types = array(
					'new_order'                 => __( 'New Order', 'email-templates' ),
					'cancelled_order'           => __( 'Cancelled Order', 'email-templates' ),
					'customer_processing_order' => __( 'Customer Processing Order', 'email-templates' ),
					'customer_completed_order'  => __( 'Customer Completed Order', 'email-templates' ),
					'customer_refunded_order'   => __( 'Customer Refunded Order', 'email-templates' ),
					'customer_on_hold_order'    => __( 'Customer On Hold Order', 'email-templates' ),
					'customer_invoice'          => __( 'Customer Invoice', 'email-templates' ),
					'failed_order'              => __( 'Failed Order', 'email-templates' ),
					'customer_new_account'      => __( 'Customer New Account', 'email-templates' ),
					'customer_note'             => __( 'Customer Note', 'email-templates' ),
					'customer_reset_password'   => __( 'Customer Reset Password', 'email-templates' ),
				);
				if ( class_exists( 'WC_Subscriptions' ) ) {
					$types = array_merge(
						$types,
						array(
							'new_renewal_order'        => __( 'New Renewal Order', 'email-templates' ),
							'customer_processing_renewal_order' => __( 'Customer Processing Renewal Order', 'email-templates' ),
							'customer_completed_renewal_order' => __( 'Customer Completed Renewal Order', 'email-templates' ),
							'customer_completed_switch_order' => __( 'Customer Completed Switch Order', 'email-templates' ),
							'customer_renewal_invoice' => __( 'Customer Renewal Invoice', 'email-templates' ),
							'cancelled_subscription'   => __( 'Cancelled Subscription', 'email-templates' ),
							'customer_payment_retry'   => __( 'Customer Payment Retry', 'email-templates' ),
							'admin_payment_retry'      => __( 'Payment Retry', 'email-templates' ),
						)
					);
				}
				if ( class_exists( 'WooCommerce_Waitlist_Plugin' ) ) {
					$types = array_merge(
						$types,
						array(
							'woocommerce_waitlist_mailout' => __( 'Waitlist Mailout', 'email-templates' ),
						)
					);
				}

				self::$customized_email_types = apply_filters( 'mailtpl_woomail_customized_email_types', $types );
			}

			return self::$customized_email_types;
		}

		/**
		 * Get Email Templates
		 *
		 * @access public
		 * @return array
		 */
		public static function get_email_templates() {
			return apply_filters( 'mailtpl_woomail_prebuilt_email_templates_settings', self::$prebuilt_templates_mapping );
		}
	}
}
