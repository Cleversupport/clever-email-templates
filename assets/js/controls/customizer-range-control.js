// ( function( $, api ) {
//     api.controlConstructor['mailtpl-range-control'] = api.Control.extend( {
//         ready: function() {
//             let control = this;
//             this.container.on( 'input', 'input[data-mailtpl-type="range"]', function( e ) {
//                 $( this ).prev( '.range-slider-value' ).find( 'span' ).html( $( this ).val() );
//                 control.setting.set( $( this ).val() );
//             } );
//         },
//     } );
// } )( jQuery, wp.customize );



( function( $, api ) {
    api.controlConstructor['mailtpl-range-control'] = api.Control.extend( {
        ready: function() {
            let control = this;

            // Range input
            let rangeInput = this.container.find( 'input[data-mailtpl-type="range"]' );

            // Number input
            let numberInput = this.container.find( 'input[data-mailtpl-type="number"]' );

            // Display value
            let displayValue = this.container.find( '.range-slider-value span' );

            // Sync range input with number input
            rangeInput.on( 'input', function() {
                let value = $( this ).val();
                numberInput.val( value ); // Update number input
                displayValue.text( value ); // Update displayed value
                control.setting.set( value ); // Update setting
            } );

            // Sync number input with range input
            numberInput.on( 'input', function() {
                let value = parseInt( $( this ).val(), 10 );

                // Validate the input value within allowed range
                if ( isNaN( value ) ) {
                    value = rangeInput.attr( 'min' ); // Default to min if value is invalid
                } else if ( value < rangeInput.attr( 'min' ) ) {
                    value = rangeInput.attr( 'min' ); // Clamp to min
                } else if ( value > rangeInput.attr( 'max' ) ) {
                    value = rangeInput.attr( 'max' ); // Clamp to max
                }

                $( this ).val( value ); // Correct the number input if out of bounds
                rangeInput.val( value ); // Update range slider
                displayValue.text( value ); // Update displayed value
                control.setting.set( value ); // Update setting
            } );
        },
    } );
} )( jQuery, wp.customize );


