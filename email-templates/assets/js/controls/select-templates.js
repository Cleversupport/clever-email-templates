( function( $, api ) {
    let mailtpl_generate_url = ( email_type, preview_order ) => {
        let is_woo_mail = email_type !== 'wordpress_standard_email';
        let url = mailtpl_select_template_button.home_url + `&email_type=${email_type}&preview_order=${preview_order}&_wpnonce=${mailtpl_select_template_button._wpnonce}&is_woo_mail=${is_woo_mail}`;
        url = encodeURIComponent( url );
        return mailtpl_select_template_button.customize_url + `&email_type=${email_type}&preview_order=${preview_order}&url=${url}&_wpnonce=${mailtpl_select_template_button._wpnonce}&is_woo_mail=${is_woo_mail}`;
    };

    let hide_show_preview_order_field = ( hide = true ) => {
        let element = $( '.mailtpl-template-preview-order' ).parent();
        if ( hide ) {
            element.hide();
        } else {
            element.show();
        }
    };

    let display_preview_order_on_email_types = [
        'wordpress_standard_email',
        'customer_reset_password',
        'customer_new_account',
    ];

    api.controlConstructor['mailtpl-select-template-button'] = api.Control.extend( {
        ready: function() {
            if ( display_preview_order_on_email_types.includes( jQuery( '.mailtpl-template-email-type' ).val() ) ) {
                hide_show_preview_order_field( true );
            } else {
                hide_show_preview_order_field( false );
            }

            this.container.on( 'change', '.mailtpl-template-email-type', function( e ) {
                if ( display_preview_order_on_email_types.includes( $( this ).val() ) ) {
                    hide_show_preview_order_field( true );
                } else {
                    hide_show_preview_order_field( false );
                }
            } );

            this.container.on( 'click', '.mailtpl-open-template', function( e ) {
                e.preventDefault();
                let email_type    = $( '.mailtpl-template-email-type' ).val(),
                    preview_order = $( '.mailtpl-template-preview-order' ).val();

                window.location.href = mailtpl_generate_url( email_type, preview_order );
            } );
        }
    } );
} )( jQuery, wp.customize );