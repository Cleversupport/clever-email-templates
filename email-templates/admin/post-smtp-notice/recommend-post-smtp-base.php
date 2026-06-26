<?php

namespace RecommendPostSMTP\Base;

if( ! class_exists( __NAMESPACE__ . '\\Recommend_Post_SMTP_Base' ) ):

class Recommend_Post_SMTP_Base {

    private $slug = '';
    private $format = 'png';

    private $plugins = array(
        'post-smtp/postman-smtp.php',
        'wp-mail-smtp/wp_mail_smtp.php',
        'wp-mail-smtp-pro/wp_mail_smtp_pro.php',
        'easy-wp-smtp/easy-wp-smtp.php',
        'fluent-smtp/fluent-smtp.php',
        'gosmtp/gosmtp.php',
        'smtp-mailer/smtp-mailer.php',
        'suremails/suremails.php',
        'mailin/mailin.php',
        'site-mailer/site-mailer.php',
        'wp-smtp/wp-smtp.php'
    );

    private $is_installed = false;

    public function __construct( $slug, $show_admin_notice = true, $parent_menu = false, $format = 'png' ) {
        $this->slug = $slug;
        $this->format = $format;
         // Only show notice if global option not set
         $global_notice_hidden = get_option( 'post_smtp_global_recommendation_notice_hidden', false );
         if ( $global_notice_hidden ) {
             $show_admin_notice = false;
         }
        add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
        
        // Register AJAX actions early
        add_action( 'wp_ajax_post_smtp_request', array( $this, 'request_post_smtp_ajax' ) );
        add_action( 'wp_ajax_nopriv_post_smtp_request', array( $this, 'request_post_smtp_ajax' ) );

        if( $show_admin_notice || $parent_menu ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            add_action( 'admin_head', array( $this, 'admin_head' ) );

            if( ! function_exists( 'is_plugin_active' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }

            foreach( $this->plugins as $plugin ) {
                if( is_plugin_active( $plugin ) ) {
                    break;
                }
                else {
                    if( $parent_menu ) {
                        if ( 'login-designer' === $this->slug ) {
                            add_action( 'admin_menu', function() use ( $parent_menu ) {
                                add_theme_page( 'SMTP', 'SMTP <span class="awaiting-mod"><span class="pending-count">Free</span></span>', 'manage_options', "{$this->slug}-recommend-post-smtp", array( $this, 'recommend_post_smtp_submenu' ), 99 );
                            }, 9999 );
                        } else {
                            add_action( 'admin_menu', function() use ( $parent_menu ) {
                            add_submenu_page( 
                                $parent_menu, 
                                'SMTP', 
                                'SMTP <span class="awaiting-mod"><span class="pending-count">Free</span></span>', 
                                'manage_options', 
                                "{$this->slug}-recommend-post-smtp", 
                                array( $this, 'recommend_post_smtp_submenu' ),
                                99
                            );
                            }, 9999 );
                        }
                    }
                    
                    if( file_exists( WP_PLUGIN_DIR . "/{$this->plugins[0]}" ) ) {
                        $this->is_installed = true;
                    }
                    break;
                }
            }
        }
    }

    
    /**
     * Hide the admin notice | Action Callback
     * 
     * @return void
     */
    public function hide_post_smtp_recommendation_notice() {
        if( ! current_user_can( 'manage_options' ) || ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'hide-post-smtp-recommendation-notice' ) ) {
            wp_die( __( 'Security Check.', 'post-smtp' ) );
        }

        if( isset( $_GET['action'] ) && $_GET['action'] === 'hide-post-smtp-recommendation-notice' ) {
            update_option( 'post-smtp-recommendation-notice-hidden', true );
            
            wp_redirect( wp_get_referer() );
        }
    }

    /**
     * Display the submenu page for the plugin | Action Callback 
     * 
     * @return void
     */
    public function recommend_post_smtp_submenu() {
        $button = array(
            'text'      => 'Install and Activate Post SMTP Now!',
            'action'    => 'install-plugin_post-smtp',
        );

        if( $this->is_installed ) {
            $button['text'] = 'Activate Post SMTP Now!';
            $button['action'] = 'activate-plugin_post-smtp';
        }

        ?>
        <div class="recommend-post-smtp-container">
            <div class="recommend-post-smtp-header">
                <div class="recommend-post-smtp-logos">
                    <img src="<?php echo esc_url( "https://plugins.svn.wordpress.org/{$this->slug}/assets/icon-128x128.{$this->format}" ); ?>" alt="<?php echo "{$this->slug} Logo" ?>" width="75px" />
                    <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '/assets/images/attachment.png' ); ?>" alt="Attachment" width="28px" />
                    <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'assets/images/post-smtp-logo.gif' ); ?>" alt="Post SMTP Logo" width="75px" />
                </div>
                <h1>Boost Wordpress Email Deliverability with Post SMTP</h1>
                <p>
                    Post SMTP is #1 SMTP plugin trusted by over 400,000 WordPress sites. Experience flawless email deliverability, detailed email logs, instant failure notifications and much more.
                </p>
                <a href="" data-action="<?php echo esc_attr( $button['action'] ); ?>" class="post-smtp-notice-install recommend-post-smtp-secondary">
                    <?php echo esc_html( $button['text'] ); ?> <span class="dashicons dashicons-arrow-right-alt"></span>
                </a>
            </div>
            <div class="recommend-post-smtp-section">
                <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'assets/images/post-smtp-banner.jpg' ); ?>" alt="Post SMTP Banner" width="100%" />
                <div class="recommend-post-smtp-container">
                    <h2>Post SMTP - Your WordPress Email Solution</h2>
                    <p>Ensure guaranteed email deliverability with seamless integration to top SMTP services. Manage your emails confidently and ensure they always reach the inbox. <a href="https://postmansmtp.com/" target="_blank">Learn more about Post SMTP</a></p>
                    <hr />
                </div>
                <div class="recommend-post-smtp-container">
                    <h2>Exclusive API  support for all popular ESPs</h2>
                    <div class="recommend-post-smtp-providers">
                        <div>
                            <h3><span class="dashicons dashicons-yes-alt"></span> Gmail SMTP</h3>
                            <h3><span class="dashicons dashicons-yes-alt"></span> Microsoft 365 (Office 365)</h3>
                            <h3><span class="dashicons dashicons-yes-alt"></span> Mailgun</h3>
                        </div>
                        <div>
                            <h3><span class="dashicons dashicons-yes-alt"></span> SendGrid</h3>
                            <h3><span class="dashicons dashicons-yes-alt"></span> Brevo (fomerly SendInBlue)</h3>
                            <h3><span class="dashicons dashicons-yes-alt"></span> Amazon SES</h3>
                        </div>
                        <div>
                            <h3><span class="dashicons dashicons-yes-alt"></span> Twilio (SMS Notifications)</h3>
                            <h3><span class="dashicons dashicons-yes-alt"></span> Zoho Mail</h3>
                            <h3><span class="dashicons dashicons-yes-alt"></span> +Any SMTP Provider</h3>
                        </div>
                    </div>
                    <div class="recommend-post-smtp-footer">
                        <a href="" data-action="<?php echo esc_attr( $button['action'] ); ?>" class="post-smtp-notice-install recommend-post-smtp-primary">
                            <?php echo esc_html( $button['text'] ); ?> <span class="dashicons dashicons-arrow-right-alt"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Add custom styles to the admin head | Action Callback
     * 
     * @return void
     */
    public function admin_head() {
        $image_path = plugin_dir_url( __FILE__ ) . 'assets/images';
        ?>
        <style type="text/css">
            <?php
            if( ! empty( $_GET['page'] ) && $_GET['page'] === "{$this->slug}-recommend-post-smtp" ) {
                echo '.notice.is-dismissible { display: none; }';
            }
            ?>
            .post-smtp-notice-install {
                text-decoration: none; 
                font-weight: bold;
            }
            .recommend-post-smtp-notice {
                background-color: #FFFFFF;
                padding: 20px;
                border-left: 5px solid #00a0d2;
            }
            .post-smtp-notice-wrapper {
                display: flex;
                align-items: center;
                gap: 20px;
            }
            .post-smtp-notice-content {
                flex: 1;
            }
            .post-smtp-notice-content p {
                color: #000000;
                font-size: 14px;
                margin: 0;
                line-height: 1.5;
            }
            .post-smtp-notice-right {
                display: flex;
                align-items: center;
                gap: 15px;
            }
            .post-smtp-notice-icon {
                flex-shrink: 0;
            }
            .post-smtp-notice-actions {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
            }
            .post-smtp-notice-install {
                margin: 0 !important;
            }
            .notice-link {
                text-align: center;
                font-size: 12px;
            }
            .post-smtp-dont-show-again {
                color: #666 !important;
                text-decoration: underline;
                font-size: 12px;
            }
            .post-smtp-dont-show-again:hover {
                color: #333 !important;
                text-decoration: underline;
            }
            .recommend-post-smtp-container {
                width: 90%;
                margin: 75px auto;
            }
            .recommend-post-smtp-header {
                background-color: #FFFFFF;
                border-radius: 18px;
                text-align: center;
                padding: 70px 75px;
            }
            .recommend-post-smtp-logos {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 15px;
            }
            .recommend-post-smtp-logos img {
                display: block;
            }
            .recommend-post-smtp-logos img:nth-child(1),
            .recommend-post-smtp-logos img:nth-child(3) {
                border: 1px solid #B3C1D5;
                border-radius: 10px;
                padding: 10px;
            }
            .recommend-post-smtp-logos img:nth-child(2) {
                align-self: center;
            }
            .recommend-post-smtp-header h1 {
                color: #214A72;
                margin-top: 45px;
            }
            .recommend-post-smtp-header p {
                color: #949494;
                font-size: 16px;
                margin-bottom: 55px;
            }
            .recommend-post-smtp-secondary {
                color: #046BD2 !important;
                border: 1px solid #046BD2;
                border-radius: 10px;
                padding: 16px 25px;
                font-size: 18px;
            }
            .recommend-post-smtp-primary {
                background-color: #046BD2;
                color: #FFFFFF !important;
                border-radius: 10px;
                padding: 16px 25px;
                font-size: 18px;
            }
            .recommend-post-smtp-primary:hover {
                background-color: #FFFFFF;
                color: #046BD2 !important;
                border: 1px solid #046BD2;
                transition: all 0.2s ease-in-out;
            }
            .recommend-post-smtp-secondary:hover {
                background-color: #046BD2;
                color: #FFFFFF !important;
                transition: all 0.2s ease-in-out;
            }
            .recommend-post-smtp-section {
                border-radius: 18px;
                background: #FFFFFF;
                padding: 25px;
                margin-top: 30px;
                background-image: url( '<?php echo "{$image_path}/bottom-right.png" ?>' ), url( '<?php echo "{$image_path}/bottom-left.png" ?>' );
                background-position: bottom right, bottom left;
                background-repeat: no-repeat, no-repeat;
            }
            .recommend-post-smtp-section .recommend-post-smtp-container {
                width: 97%;
                margin: 30px auto;
            }
            .recommend-post-smtp-section h2 {
                color: #484848;
                font-weight: 700;
                font-size: 25px;
            }
            .recommend-post-smtp-section p {
                color: #484848;
                font-size: 19px;
            }
            .recommend-post-smtp-section hr {
                border-color: #D8D9DA;
                margin: 35px 0;
            }
            .recommend-post-smtp-providers {
                display: flex;
                justify-content: space-between;
            }
            .recommend-post-smtp-providers h3 {
                color: #565353;
                font-weight: 500;
            }
            .recommend-post-smtp-providers .dashicons {
                color: #8FC895;
                margin-right: 5px;
            }
            .recommend-post-smtp-footer {
                text-align: center;
                margin-top: 60px;
            }
        </style>
        <?php
    }

    /**
     * Registers AJAX actions | Action Callback
     * 
     * @return void
     */
    public function rest_api_init() {
        // AJAX handlers are registered in constructor
    }
    
    /**
     * AJAX callback for Post SMTP request
     * 
     * @return void
     */
    public function request_post_smtp_ajax() {
        // Debug: Log the request
        error_log( 'Post SMTP AJAX request received' );
        error_log( 'POST data: ' . print_r( $_POST, true ) );
        
        // Check if nonce exists
        if( ! isset( $_POST['nonce'] ) ) {
            error_log( 'No nonce provided' );
            wp_send_json_error( array( 'message' => 'No nonce provided' ) );
        }
        
        // Check nonce for security
        if( ! wp_verify_nonce( $_POST['nonce'], 'post_smtp_request_nonce' ) ) {
            error_log( 'Nonce verification failed' );
            wp_send_json_error( array( 'message' => 'Security check failed' ) );
        }
        
        // Check user permissions
        if( ! current_user_can( 'manage_options' ) ) {
            error_log( 'User does not have manage_options capability' );
            wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
        }
        
        // Check if status is provided
        if( ! isset( $_POST['status'] ) ) {
            error_log( 'No status provided' );
            wp_send_json_error( array( 'message' => 'No status provided' ) );
        }
        
        $site_url = get_bloginfo( 'url' );
        $status = sanitize_text_field( $_POST['status'] );
        $plugin_slug = $this->slug;
        $secret_key = 'WP_*#KXs2)34KM@_-*^%?>"}0!@~\@4C2*0A^%(%MVBS';

        error_log( 'Sending request to SMTP server with status: ' . $status );

        $response = wp_remote_post( "https://connect.postmansmtp.com/wp-json/update/v1/update?site_url={$site_url}&status={$status}&plugin_slug={$plugin_slug}", array(
            'method'      => 'POST',
            'headers'     => array(
                'Content-Type'  => 'application/json',
                'Secret-Key'    => $secret_key
            )
        ) );

        if( is_wp_error( $response ) ) {
            error_log( 'SMTP server request failed: ' . $response->get_error_message() );
            wp_send_json_error( array(
                'message' => 'Failed to send request: ' . $response->get_error_message()
            ) );
        } else {
            error_log( 'SMTP server request successful' );
            wp_send_json_success( array(
                'message' => __( 'Request sent successfully', 'post-smtp' )
            ) );
        }
    }

    /**
     * Add the admin footer script to the notice | Action Callback
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_script( 'wp-updates' );
        wp_enqueue_script( 'recommend-post-smtp-script', plugin_dir_url( __FILE__ ) . 'assets/js/admin-script.js', array( 'wp-updates', 'jquery' ), '1.0.0', true );
        wp_localize_script( 'recommend-post-smtp-script', 'recommendPostSMTP', array(
            'redirectURL'   => admin_url( "admin-post.php?action=hide-post-smtp-recommendation-notice&nonce=" . wp_create_nonce( 'hide-post-smtp-recommendation-notice' ) ),
            'postSMTPURL'   => admin_url( "admin.php?page=postman" ),
            'ajaxURL'       => admin_url( 'admin-ajax.php' ),
            'ajaxNonce'     => wp_create_nonce( 'post_smtp_request_nonce' )
        ) );
    }
}
endif;