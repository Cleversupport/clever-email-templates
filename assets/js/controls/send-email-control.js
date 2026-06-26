( function( $, api ) {
    api.controlConstructor['mailtpl-send-mail'] = api.Control.extend( {
        ready() {
            let control = this;
            this.container.on( 'click', 'button[data-mailtpl-type="send-email"]', function( e ) {
                e.preventDefault();
                $( this ).attr( 'disabled', 'disabled' );
                $( this ).next( '#mailtpl-spinner' ).fadeIn();
                $.post( ajaxurl, {
                    action: 'mailtpl_send_email',
                    _wpnonce: mailtpl_sendemail_object._wpnonce,
                    email_type: mailtpl_sendemail_object.email_type,
                    preview_order: mailtpl_sendemail_object.preview_order,
                }, ( response ) => {
                    console.log( response );
                    if ( undefined !== typeof response.data.email_sanded && response.data.email_sanded ) {
                        $( this ).next( '#mailtpl-spinner' ).fadeOut();
                        $( this ).siblings( '#mailtpl-success' ).fadeIn().delay(5000).fadeOut();  // Show success message
                        $( this ).removeAttr( 'disabled' );
                    }
                } );
            } );
        },
    } );
} )( jQuery, wp.customize );