/**
 *
 */
( function( $ ) {

// sample image for products
    sample_img = localizedImage.sample_image;

    /**
     * Check is customizer preview
     *
     * @returns {boolean}
     */
    function mailtpl_is_customizer_window() {
        return ! ( window.parent === window );
    }
    /**
     * Email templates settings binder.
     *
     * @param {string} setting setting
     * @param {function(): function()} callback callback
     *
     * @returns {*}
     */
    const mailtpl_wp_customize_setting_binder = ( setting, callback ) => (
        wp.customize( setting, callback )
    )

    /**
     * Email templates value binder
     *
     * @param {string} setting
     * @param {function(): function()} callback
     *
     * @returns {*}
     */
    const mailtpl_wp_customize_value_binder = ( setting, callback ) => (
        mailtpl_wp_customize_setting_binder( setting, function( value ) {
            value.bind( callback );
        } )
    );

    /**
     * Email templates wp customize control
     *
     * @param {string}setting
     * @param {function(): function()}callback
     *
     * @returns {*}
     */
    const mailtpl_customize_manager = ( setting, callback ) => {
        mailtpl_wp_customize_value_binder( `mailtpl_opts[${setting}]`, callback )
    };

    /**
     * Email templates get settings
     *
     * @param {string}setting
     *
     * @returns {*}
     */
    const mailtpl_customize_get_settings = ( setting ) => (
        wp.customize( setting )()
    );

    /**
     * Email templates get settings
     *
     * @param {string}setting
     *
     * @returns {*}
     */
    const mailtpl_get_setting = ( setting ) => (
        mailtpl_customize_get_settings( `mailtpl_opts[${setting}]` )
    );

    /**
     * Email templates order items element
     *
     * @param style {object:{}} - css style
     *
     * @returns {this|jQuery}
     */
    const mailtpl_order_items_element = (style = {}) => {
        return $('#body_content_inner table.td tr td, #body_content_inner table.td tr th').css(style);

    };

    // const mailtpl_order_items_table_style = (style = {}) => {
    //     return $('.mailtlp_table_style').css(style);

    // };
    
    /**
     * Font weight generator
     *
     * @param {string} font_style
     * @param {string} font_weight
     *
     * @return {string}
     */
    const mailtpl_font_weight_generator = ( font_style, font_weight ) => {
        let font_weight_min;
        if ( 'normal' === font_style ) {
            font_weight_min = '';
        } else {
            font_weight_min = `1,`;
        }

        return `wght@${font_weight_min}${font_weight}`;
    }

    /**
     * Font style links generator
     *
     * @param {string} font_name
     * @param {string} font_weight
     * @param {string} font_style
     *
     * @return {string|false}
     */
    const mailtpl_generate_fonts_style_links = ( font_name, font_weight, font_style ) => {
        let font_url = '';
        if ( 'default' === font_name ) {
            return false;
        }

        if ( 'normal' !== font_style ) {
            font_url = ':ital';
        }

        if ( font_weight ) {
            font_weight = mailtpl_font_weight_generator( font_style, font_weight );
            if ( font_url.length ) {
                font_url += `,`;
            } else {
                font_url = ':'
            }
            font_url += font_weight;
        }
        font_url = `family=${font_name}${font_url}`;
        return `https://fonts.googleapis.com/css2?${font_url}&display=swap`;
    }

    /**
     * Email templates font style generator
     *
     * @param {string} font_url
     * @param {string} element_id
     */
    const mailtpl_enqueue_styles = ( font_url, element_id ) => {
        let google_apis_cdn = '<link class="mailtpl-google-fonts-required-google_api-cdn" href="https://fonts.googleapis.com" rel="preconnect" />',
            gstatic_cdn     = '<link class="mailtpl-google-fonts-required-gstatic-cdn" href="https://fonts.gstatic.com" rel="preconnect" crossorigin />';

        let mailtpl_head = $( 'head' );
        if ( ! $( '.mailtpl-google-fonts-required-google_api-cdn' ).length && ! $( '.mailtpl-google-fonts-required-gstatic-cdn' ).length ) {
            mailtpl_head.append( google_apis_cdn );
            mailtpl_head.append( gstatic_cdn );
        }

        let element = `<link rel="stylesheet" id="${element_id}" href="${font_url}" />`;
        if ( $( `#${element_id}` ).length ) {
            $( `#${element_id}` ).replaceWith( element )
        }
        mailtpl_head.append( element );

      
    }
    /**
     * Email templates font style generator
     *
     * @param {string} font_family
     * @param {string} font_weight
     * @param {string} font_style
     * @param {string} element_id
     * @param {object:$|string} target
     */
    const mailtpl_font_style_css_generator = ( font_family, font_weight, font_style, element_id, target ) => {
        let font_url = mailtpl_generate_fonts_style_links(
            font_family,
            font_weight,
            font_style
        );

        mailtpl_enqueue_styles( font_url, element_id );

        $( target ).css( {
            'font-family': font_family,
            'font-weight': font_weight,
            'font-style': font_style,
        } );
    }



    /**
     * Email templates order items element style settings.
     */
    mailtpl_customize_manager('items_table_background_color', function (background_color) {
        $('#body_content_inner table.td tr').css('background-color', background_color);
    });
    
    mailtpl_customize_manager('items_table_background_odd_color', function (odd_background_color) {
        $('#body_content_inner table.td tr:nth-child(odd)').css('background-color', odd_background_color);
    });
    
    mailtpl_customize_manager('items_table_padding_top_bottom', function (padding_top_bottom) {
        paddingStyles = {
            'padding-top': `${padding_top_bottom}px`,
            'padding-bottom': `${padding_top_bottom}px`,
        };
        mailtpl_order_items_element(paddingStyles);
    });
    
    mailtpl_customize_manager('items_table_padding_left_right', function (padding_left_right) {
        paddingStyles = {
            'padding-left': `${padding_left_right}px`,
            'padding-right': `${padding_left_right}px`,
        };
        mailtpl_order_items_element(paddingStyles);
    });
    
    
    mailtpl_customize_manager('items_table_border_width', function (border_width) {

        mailtpl_order_items_element({
            'border-width': `${border_width}px`,
        });
    });
    
    mailtpl_customize_manager('items_table_border_color', function (border_color) {
      
        mailtpl_order_items_element({
            'border-color': border_color,
        });
    });
    
    mailtpl_customize_manager('items_table_border_style', function (border_style) {
        mailtpl_order_items_element({
            'border-style': border_style,
        });
    });

    mailtpl_customize_manager('order_items_style', function (table_style) {
        if (table_style === 'light') {
            mailtpl_order_items_element({
                'border-left': 'unset',
                'border-right': 'unset',
            });
        } else if (table_style === 'normal') {
        const borderStyle = mailtpl_get_setting('items_table_border_style') || 'solid';
        const borderWidth = mailtpl_get_setting('items_table_border_width') || '1';
        const borderColor = mailtpl_get_setting('items_table_border_color') || '#000';

        mailtpl_order_items_element({
            'border-left': `${borderWidth}px ${borderStyle} ${borderColor}`,
            'border-right': `${borderWidth}px ${borderStyle} ${borderColor}`,
        });
        }
    });

    mailtpl_customize_manager('order_heading_style', function (order_style) {
        const tableElement = $('.mailtlp_table_style');

        // Remove any previously added style classes
        tableElement.removeClass('split normal');
    
        if (order_style === 'split') {
            // Add the split class
            tableElement.addClass('split');
        } else if (order_style === 'normal') {
            // Add the normal class
            tableElement.addClass('normal');
        }
    });

  // Handle product image visibility and size dynamically
    mailtpl_customize_manager('order_image_image', function (product_image) {
        const imageElement = $('div#body_content_inner .order_item');
        if (product_image === 'show') {
            imageElement.addClass('show-image');
        } 
        else if (product_image === 'normal') {
            imageElement.removeClass('show-image');
        }
    });

// // Handle product image sizes
    mailtpl_customize_manager('order_items_image_size', function (image_size) {
        const sizes = {
            '40x40': { width: 40, height: 40 },
            '50x50': { width: 50, height: 50 },
            '100x50': { width: 100, height: 50 },
            '100x100': { width: 100, height: 100 },
            '150x150': { width: 150, height: 150 },
            'woocommerce_thumbnail': { width: 150, height: 150 } // Assuming WooCommerce default size
        };

        const selectedSize = sizes[image_size];

        // Apply size to all dummy images
        if (selectedSize) {
            $('div#body_content_inner .order_item td:first-child img.dummy-product-image').each(function () {
                $(this).css({
                    width: `${selectedSize.width}px`,
                    height: `${selectedSize.height}px`
                });
            });
        }
    });

 
    // email order notes settings
    mailtpl_customize_manager('notes_outside_table', function (order_notes) {
        if(order_notes === true){
            $('.email-spacing-wrap.note-check-below').css('display', 'block');
            $('tr.note-check').css('display', 'none');
          
        }else if(order_notes === false){
            $('.email-spacing-wrap.note-check-below').css('display', 'none');
            $('tr.note-check').css('display', 'table-row');
        }
            
      
    });


    /**
     * Email templates address element style settings.
     */
    mailtpl_customize_manager( 'address_box_background_color', function( background_color ) {
        $( '#addresses tr td' ).css( 'background-color', background_color );
    } );

    mailtpl_customize_manager( 'address_box_padding', function( padding ) {
        $( '#addresses tr td' ).css( 'padding', `${padding}px` );
    } );

    mailtpl_customize_manager( 'address_box_border_width', function( border_width ) {
        $( '#addresses tr td' ).css( 'border-width', `${border_width}px` );
    } );

    mailtpl_customize_manager( 'address_box_border_color', function( border_color ) {
        $( '#addresses tr td' ).css( 'border-color', border_color );
    } );

    mailtpl_customize_manager( 'address_box_border_style', function( border_style ) {
        $( '#addresses tr td' ).css( 'border-style', border_style );
    } );

    mailtpl_customize_manager( 'address_box_text_color', function( text_color ) {
        $( '#addresses tr td' ).css( 'color', text_color );
    } );

    mailtpl_customize_manager( 'address_box_text_align', function( text_align ) {
        $( '#addresses tr td, #addresses tr td h2' ).css( 'text-align', text_align );
    } );

    /**
     * Email templates button styles settings.
     */
    mailtpl_customize_manager( 'button_text_color', function( text_color ) {
        $( '#body_content_inner .button-container .btn' ).css( 'color', text_color );
    } );

    mailtpl_customize_manager( 'button_font_size', function( font_size ) {
        $( '#body_content_inner .button-container .btn' ).css( 'font-size', `${font_size}px` );
    } );

    mailtpl_customize_manager( 'button_background_color', function( background_color ) {
        $( '#body_content_inner .button-container .btn' ).css( 'background-color', background_color );
    } );

    mailtpl_customize_manager( 'button_padding_top_bottom', function( padding_top_bottom ) {
        $( '#body_content_inner .button-container, #body_content_inner .button-container .btn' ).css( {
            'padding-top'    : `${padding_top_bottom}px`,
            'padding-bottom' : `${padding_top_bottom}px`,
        } );
    } );

    mailtpl_customize_manager( 'button_padding_left_right', function( padding_left_right ) {
        $( '#body_content_inner .button-container, #body_content_inner .button-container .btn' ).css( {
            'padding-left'  : `${padding_left_right}px`,
            'padding-right' : `${padding_left_right}px`,
        } );
    } );

    mailtpl_customize_manager( 'button_border_radius', function( border_width ) {
        $( '#body_content_inner .button-container .btn' ).css( 'border-radius', `${border_width}px` );
    } );

    mailtpl_customize_manager( 'button_border_width', function( border_width ) {
        $( '#body_content_inner .button-container .btn' ).css( 'border-width', `${border_width}px` );
    } );

    mailtpl_customize_manager( 'button_border_color', function( border_color ) {
        $( '#body_content_inner .button-container .btn' ).css( 'border-color', border_color );
    } );

    mailtpl_customize_manager( 'button_border_style', function( border_style ) {
        $( '#body_content_inner .button-container .btn' ).css( 'border-style', border_style );
    } );

    mailtpl_customize_manager( 'button_font_family', function( font_family ) {
        mailtpl_font_style_css_generator( font_family, mailtpl_get_setting( 'button_font_weight' ), 'normal', 'button-font-family-control', $( '#body_content_inner .button-container .btn' ) );
    } );

    mailtpl_customize_manager( 'button_font_weight', function( font_weight ) {
        mailtpl_font_style_css_generator( mailtpl_get_setting( 'button_font_family' ), font_weight, 'normal', 'button-font-family-control', $( '#body_content_inner .button-container .btn' ) );
    } );

    /**
     * Email templates subtitle styles settings.
     */
    $.each(
        [ 'new_order', 'cancelled_order', 'customer_processing_order', 'customer_completed_order', 'customer_refunded_order', 'customer_on_hold_order', 'customer_invoice', 'customer_note', ],
        function( i, email_type ) {
            mailtpl_customize_manager( `heading_${email_type}_subtitle`, function( subtitle ) {
                $( '#header_wrapper .subtitle' ).html( subtitle );
            } );
        }
    )

    mailtpl_customize_manager( 'subtitle_font_size', function( font_size ) {
        $( '#header_wrapper .subtitle' ).css( 'font-size', `${font_size}px` );
    } );

    mailtpl_customize_manager( 'subtitle_text_color', function( text_color ) {
        $( '#header_wrapper .subtitle' ).css( 'color', text_color );
    } );

    mailtpl_customize_manager( 'subtitle_font_weight', function( font_family ) {
        mailtpl_font_style_css_generator( mailtpl_get_setting( 'subtitle_font_family' ), font_family, mailtpl_get_setting( 'subtitle_font_style' ), 'subtitle-font-family-control', $( '#header_wrapper .subtitle' ) );
    } );

    mailtpl_customize_manager( 'subtitle_font_family', function( font_family ) {
        mailtpl_font_style_css_generator( font_family, mailtpl_get_setting( 'subtitle_font_weight' ), mailtpl_get_setting( 'subtitle_font_style' ), 'subtitle-font-family-control', $( '#header_wrapper .subtitle' ) );
    } );

    mailtpl_customize_manager( 'subtitle_font_style', function( font_style ) {
        mailtpl_font_style_css_generator( mailtpl_get_setting( 'subtitle_font_family' ), mailtpl_get_setting( 'subtitle_font_weight' ), font_style, 'subtitle-font-family-control', $( '#header_wrapper .subtitle' ) );
    } );

} )( jQuery );