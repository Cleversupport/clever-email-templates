/**
 * Email templates Customizer preview scripts
 *
 * @package Email Templates
 */

( function( $, api ) {
    /**
     * Customizer.
     *
     * @param {string}setting
     * @param {function(): function()}callback
     */
    const mailtpl_customizer = ( setting, callback ) => {
        return wp.customize( setting, callback );
    };

    /**
     * Customizer value binder.
     *
     * @param {string}setting
     * @param {function(): function()}callback
     */
    const mailtpl_value_bind = ( setting, callback ) => {
        return mailtpl_customizer( setting, function( value ) {
            value.bind( callback );
        } );
    };

    /**
     * Customizer settings name with prefix.
     *
     * @param {string}setting
     * @param {function(): function()}callback
     * @returns {*}
     */
    const mailtpl_settings_customizer = ( setting, callback ) => {
        return mailtpl_value_bind( `mailtpl_opts[${setting}]`, callback );
    };

    /**
     * Font weight generator
     *
     * @param {string}font_style
     * @param {string}font_weight
     *
     * @return {string}
     */
    function mailtpl_font_weight_generator( font_style, font_weight ) {
        let font_weight_min;
        if ( font_style === 'normal' ) {
            font_weight_min = '';
        } else {
            font_weight_min = '1,';
        }

        return `wght@${font_weight_min}${font_weight}`;
    }

    /**
     * Font style links generator.
     *
     * @param {string}font_name
     * @param {string}font_weight
     * @param {string}font_style
     *
     * @return string|false
     */
    function mailtpl_generate_fonts_style_links( font_name = 'default', font_weight = false, font_style = 'normal' ) {
        let font_url = '';
        if ( 'default' === font_name ) {
            return false;
        }

        if ( 'normal' !== font_style ) {
            font_url = `:ital`;
        }
        if ( font_weight ) {
            font_weight = mailtpl_font_weight_generator( font_style, font_weight );
            if ( font_url.length ) {
                font_url += ',';
            } else {
                font_url = ':'
            }

            font_url += font_weight;
        }

        font_url = `family=${font_name}${font_url}`;

        return `https://fonts.googleapis.com/css2?${font_url}&display=swap`;
    }

    /**
     * WPCustomize get setting
     *
     * @param {string}setting_name
     * @return {*}
     */
    const mailtpl_customizer_setting = ( setting_name ) => (
        wp.customize( setting_name )()
    );
    /**
     * Get setting
     *
     * @param {string}setting_name
     * @return {*}
     */
    const mailtpl_get_setting = ( setting_name ) => (
        mailtpl_customizer_setting( `mailtpl_opts[${setting_name}]` )
    );

    /**
     * Mailtpl enqueue scripts.
     *
     * @param {string}font_url
     * @param {string}element_id
     */
    function mailtpl_enqueue_styles( font_url, element_id, ) {
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
     * Font style css generator
     *
     * @param {string}font_family
     * @param {string}font_weight
     * @param {string}font_style
     * @param {string}element_id
     * @param {string}target
     */
    const mailtpl_font_style_css_generator = ( font_family, font_weight, font_style, element_id, target ) => {
        let font_url = mailtpl_generate_fonts_style_links(
            font_family,
            font_weight,
            font_style
        );

        mailtpl_enqueue_styles( font_url, element_id );

        $( target ).css( {
            'font-weight': font_weight,
            'font-style': font_style,
            'font-family': font_family,
        } );
    };

    /**
     * ===========================================
     * Customizer settings start
     * ===========================================
     */

    /**
     * Template section customization
     */
    mailtpl_settings_customizer( 'body_size', function( to ) {
        $( '#template_container, #template_footer' ).css( 'width', `${to}px` );
    } );

    mailtpl_settings_customizer( 'body_bg', function( to ) {
        $( '#body, body, #wrapper' ).css( 'background-color', to );
    } );

    mailtpl_settings_customizer( 'template_border_color', function( to ) {
        $( '#template_container' ).css( {
            'border-color': to,
            'border-style': 'solid',
        } );
    } );

    mailtpl_settings_customizer( 'template_border_top_width', function( to ) {
        $( '#template_container' ).css( 'border-top-width', `${to}px` );
    } );

    mailtpl_settings_customizer( 'template_border_bottom_width', function( to ) {
        $( '#template_container' ).css( 'border-bottom-width', `${to}px` );
    } );

    mailtpl_settings_customizer( 'template_border_left_width', function( to ) {
        $( '#template_container' ).css( 'border-left-width', `${to}px` );
    } );

    mailtpl_settings_customizer( 'template_border_right_width', function( to ) {
        $( '#template_container' ).css( 'border-right-width', `${to}px` );
    } );

    mailtpl_settings_customizer( 'template_border_radius', function( to ) {
        $( '#template_container' ).css( 'border-radius', `${to}px` );
    } );

    mailtpl_settings_customizer( 'template_padding_top', function( to ) {
        $( '#body, #wrapper' ).css( 'padding-top', `${to}px` );
    } );

    mailtpl_settings_customizer( 'template_padding_bottom', function( to ) {
        $( '#body, #wrapper' ).css( 'padding-bottom', `${to}px` );
    } );

    mailtpl_settings_customizer( 'template_box_shadow', function( to ) {
        let enable_box_shadow = ( to ) ? `1px` : 0,
            box_shadow_width  = ( to ) ? ( to*4 ) + 'px' : 0,
            box_shadow_color  = `rgba(0,0,0,0.5)`,
            box_shadow        = `0 ${enable_box_shadow} ${box_shadow_width} ${to}px ${box_shadow_color}`;
        $( '#template_container' ).css( 'box-shadow', box_shadow );
    } );

    /**
     * Header section customizer
     */
    /**
     * Header Image customization
     */
    mailtpl_settings_customizer( 'header_logo', function( to ) {
        $( '#template_header_logo_image a img, #template_header_image img' ).attr( 'src', to )
    } );

    mailtpl_settings_customizer( 'header_image_alignment', function( to ) {
        $( '#template_header_logo_image tr td, #template_header_image_table td' ).css( 'text-align', to );
    } );

    mailtpl_settings_customizer( 'image_width_control', function( to ) {
        $( '#template_header_logo_image a img, #template_header_image img' ).css( 'width', `${to}px` );
    } );

    mailtpl_settings_customizer( 'header_image_bg', function( to ) {
        $( '#template_header_logo_image, #template_header_image_container' ).css( 'background-color', to );
    } );

    mailtpl_settings_customizer( 'header_image_padding_top_bottom', function( to ) {
        $( '#template_header_logo_image tr td, #template_header_image_table tr td' ).css( 'padding', `${to}px 0 ${to}px 0` );
    } );

    /**
     * Header text customization
     */
    mailtpl_settings_customizer( 'header_logo_text', function( to ) {
        $( '#template_header_logo_text h1 a' ).html( to );
    } );

    mailtpl_settings_customizer( 'header_text_size', function( to ) {
        $( '#template_header_logo_text h1 a, #header_wrapper h1' ).css( 'font-size', `${to}px` );
    } );

    mailtpl_settings_customizer( 'header_text_aligment', function( to ) {
        $( '#template_header_logo_text tr td, #header_wrapper h1, #header_wrapper' ).css( 'text-align', to );
    } );

    mailtpl_settings_customizer( 'header_text_padding_top', function( to ) {
        $( '#template_header_logo_text tr td, #header_wrapper' ).css( 'padding-top', `${to}px` );
    } );

    mailtpl_settings_customizer( 'header_text_padding_bottom', function( to ) {
        $( '#template_header_logo_text tr td, #header_wrapper' ).css( 'padding-bottom', `${to}px` );
    } );

    mailtpl_settings_customizer( 'header_text_padding_left_right', function( to ) {
        $( '#template_header_logo_text tr td, #header_wrapper' ).css( {
            'padding-left'  : `${to}px`,
            'padding-right' : `${to}px`,
        } );
    } );

    mailtpl_settings_customizer( 'header_bg', function( to ) {
        $( '#template_header_logo_text, #header_wrapper' ).css( 'background-color', to );
    } );

    mailtpl_settings_customizer( 'header_text_color', function( to ) {
        $( '#template_header_logo_text h1 a, #header_wrapper h1' ).css( 'color', to );
    } );

    mailtpl_settings_customizer( 'header_font_style', function( to ) {
        mailtpl_font_style_css_generator(
            mailtpl_get_setting( 'header_font_family' ),
            mailtpl_get_setting( 'header_font_weight' ),
            to,
            'maltpl--header-fonts',
            '#template_header_logo_text h1 *, #template_header_logo_text h1 a *, #header_wrapper h1 *',
        );
    } );

    mailtpl_settings_customizer( 'header_font_weight', function( to ) {
        mailtpl_font_style_css_generator(
            mailtpl_get_setting( 'header_font_family' ),
            to,
            mailtpl_get_setting( 'header_font_style' ),
            'maltpl--header-fonts',
            '#template_header_logo_text h1 *, #template_header_logo_text h1 a *, #header_wrapper h1 *'
        );
    } );

    mailtpl_settings_customizer( 'header_font_family', function( to ) {
        mailtpl_font_style_css_generator(
            to,
            mailtpl_get_setting( 'header_font_weight' ),
            mailtpl_get_setting( 'header_font_style' ),
            'maltpl--header-fonts',
            '#template_header_logo_text h1 *, #template_header_logo_text h1 a *, #header_wrapper *, h1 *'
        );
    } );

    /**
     * Body section Customization
     */
    mailtpl_settings_customizer( 'email_body_bg', function( to ) {
        $( '#template_body_container, #body_content' ).css( 'background-color', to );
    } );

    mailtpl_settings_customizer( 'body_text_size', function( to ) {
        $( '#template_body_content, #body_content_inner' ).css( 'font-size', `${to}px` );
    } );

    mailtpl_settings_customizer( 'body_text_color', function( to ) {
        $( '#template_body_content, #template_body_content ul, #body_content_inner, #body_content_inner table, #body_content_inner td, #body_content_inner th' ).css( 'color', to );
    } );

    mailtpl_settings_customizer( 'body_href_color', function( to ) {
        $( '#template_body_content a, #body_content_inner a' ).css( 'color', to );
    } );

    mailtpl_settings_customizer( 'body_padding_top', function( to ) {
        $( '#template_body_content, #body_content' ).css( 'padding-top', `${to}px` );
    } );

    mailtpl_settings_customizer( 'body_padding_bottom', function( to ) {
        $( '#template_body_content, #body_content' ).css( 'padding-bottom', `${to}px` );
    } );

    mailtpl_settings_customizer( 'body_padding_left_right', function( to ) {
        $( '#template_body_content, #body_content' ).css( {
            'padding-left'  :`${to}px`,
            'padding-right' :`${to}px`,
        } );
    } );

    mailtpl_settings_customizer( 'body_font_weight', function( to ) {
        mailtpl_font_style_css_generator(
            mailtpl_get_setting( 'body_font_family' ),
            to,
            'normal',
            'mailtpl--body-font-style',
            '#template_body_container *, #body_content_inner *, #body_content_inner *'
        );
    } );

    mailtpl_settings_customizer( 'body_font_family', function( to ) {
        mailtpl_font_style_css_generator(
            to,
            mailtpl_get_setting( 'body_font_weight' ),
            'normal',
            'mailtpl--body-font-style',
            '#template_body_container *, #body_content_inner *, body_content_inner *'
        );
    } );

    /**
     * Footer Section Customization.
     */
    mailtpl_settings_customizer( 'footer_padding_top', function( to ) {
        $( '#template_footer_container' ).css( 'padding-top', `${to}px` );
    } );

    mailtpl_settings_customizer( 'footer_padding_bottom', function( to ) {
        $( '#template_footer_container' ).css( 'padding-bottom', `${to}px` );
    } );

    mailtpl_settings_customizer( 'footer_padding_left_right', function( to ) {
        $( '#template_footer_container' ).css( {
            'padding-left'  : `${to}px`,
            'padding-right' : `${to}px`,
        } );
    } );

    mailtpl_settings_customizer( 'footer_aligment', function( to ) {
        $( '#template_footer_container, #credit' ).css( 'text-align', to );
    } );

    mailtpl_settings_customizer( 'footer_text_color', function( to ) {
        $( '#template_footer_container, #template_footer_container a, #credit, #credit a' ).css( 'color', to );
    } );

    mailtpl_settings_customizer( 'footer_text_size', function( to ) {
        $( '#template_footer_container, #credit' ).css( 'font-size', `${to}px` );
    } );

    mailtpl_settings_customizer( 'footer_bg', function( to ) {
        $( '#template_footer_container' ).css( 'background-color', to );
    } );

    mailtpl_settings_customizer( 'footer_text_padding_top', function( to ) {
        $( '#credit' ).css( 'padding-top', `${to}px` );
    } );

    mailtpl_settings_customizer( 'footer_text_padding_bottom', function( to ) {
        $( '#credit' ).css( 'padding-bottom', `${to}px` );
    } );

    mailtpl_settings_customizer( 'footer_text', function( to ) {
        $( '#credit' ).html( to );
    } );

    mailtpl_settings_customizer( 'footer_font_weight', function( to ) {
        mailtpl_font_style_css_generator(
            mailtpl_get_setting( 'footer_font_style' ),
            to,
            'normal',
            'mailtpl--footer-font-family',
            '#template_footer_container *, #credit *'
        );
    } );

    mailtpl_settings_customizer( 'footer_font_style', function( to ) {
        mailtpl_font_style_css_generator(
            to,
            mailtpl_get_setting( 'footer_font_weight' ),
            'normal',
            'mailtpl--footer-font-family',
            '#template_footer_container *, #credit *'
        );
    } );
} )( jQuery, wp.customize );