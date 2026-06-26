// ( function( $, api ) {
//     api.controlConstructor['mailtpl-toggle-switch-control'] = api.Control.extend( {
//         ready() {
//             let control = this;
//             this.container.on( 'click', 'input[type=checkbox].mailtpl-toggle', function( e ) {
//                 let selected = false;
//                 if ( $( this ).is( ':checked' ) ) {
//                     selected = true;
//                 }
//                 control.setting.set( selected );
//             } );
//         }
//     } );
// } )( jQuery, wp.customize );



( function( $, api ) {
    api.controlConstructor['mailtpl-toggle-switch-control'] = api.Control.extend( {
        ready() {
         

            // Function to check if the `::before` content exists
            function hasBeforeContent(element) {
                const beforeContent = window.getComputedStyle(element, '::before').content;
                return beforeContent && beforeContent !== 'none' && beforeContent !== '""';
            }

            // Apply the check on document ready
            $('.customToggle').each(function() {
                if (hasBeforeContent(this)) {
                    $(this).val('1');
                    $(this).attr('checked','checked');
                } else {
                    $(this).val('');
                }
            });

            $('.customToggle').on('change', function() {
                $(this).val(this.checked ? '1' : '0');
            });
            
            // Listen for checkbox changes
            // this.container.on('click', 'input[type=checkbox].customToggle', function(e) {
            //     let selected = $(this).is(':checked');
            //     control.setting.set(selected);
            // });
        }
    });
} )( jQuery, wp.customize );
