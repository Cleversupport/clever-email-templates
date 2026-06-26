window.law_collector = [];

( function( $ ) {
    let api = wp.customize;

    api.bind( 'pane-contents-reflowed', function() {
        let sections = [], panels = [];

        api.section.each( function( section ) {

            if ( 'mailtpl-section' !== section.params.type || 'undefined' === typeof section.params.section ) {

                return;
            }

            sections.push( section );
        } );

        sections.sort( api.utils.prioritySort ).reverse();
        $.each( sections, function( i, section ) {
            let parent_container = $( '#sub-accordion-section-' + section.params.section );
            parent_container.children( '.section-meta' ).after( section.headContainer );
        } );

        api.panel.each( function( panel ) {

            if ( 'mailtpl-panel' !== panel.params.type || 'undefined' === typeof panel.params.panel ) {

                return;
            }

            panels.push( panel );
        } );

        panels.sort( api.utils.prioritySort ).reverse();
        $.each( panels, function( i, panel ) {
            window.law_collector =  panel;
            let parent_container = $( '#sub-accordion-panel-' + panel.params.panel );

            parent_container.children( '#accordion-section-section_mailtpl_footer' ).after( panel.headContainer );
        } );
    } );

    let _panelEmbed               = wp.customize.Panel.prototype.embed,
        _panelIsContexuallyActive = wp.customize.Panel.prototype.isContextuallyActive,
        _panelAttachEvents        = wp.customize.Panel.prototype.attachEvents;

    wp.customize.Panel = wp.customize.Panel.extend( {
        attachEvents () {
            if ( 'mailtpl-panel' !== this.params.type || 'undefined' === typeof this.params.panel ) {

                _panelAttachEvents.call( this );
                return;
            }

            _panelAttachEvents.call( this );
            let panel = this;
            panel.expanded.bind( function( expanded ) {

                let parent = api.panel( panel.params.panel );
                if ( expanded ) {

                    parent.contentContainer.addClass( 'current-panel-parent' );
                } else {

                    parent.contentContainer.removeClass( 'current-panel-parent' );
                }
            } );

            panel.container.find( '.customize-panel-back' ).off( 'click keydown' ).on( 'click keydown', function( event ) {

                if ( api.utils.isKeydownButNotEnterEvent( event ) ) {

                    return;
                }

                event.preventDefault();
                if ( panel.expanded() ) {

                    api.panel( panel.params.panel ).expand();
                }
            } );
        },
        embed () {

            if ( 'mailtpl-panel' !== this.params.type || 'undefined' === typeof this.params.panel ) {

                _panelEmbed.call( this );
                return;
            }

            _panelEmbed.call( this );
            let panel           = this,
                parentContainer = $( '#subsection-accordion-panel-' + this.params.panel );
            parentContainer.append( panel.headContainer );
        },
        isContextuallyActive () {

            if ( 'mailtpl-panel' !== this.params.type ) {

                return _panelIsContexuallyActive.call( this );
            }

            let panel   = this,
                children = this._children( 'panel', 'section' );
            api.panel.each( function( child ) {

                if ( ! child.params.panel ) {

                    return;
                }

                if ( child.params.panel !== panel.id ) {

                    return;
                }

                children.push( child );
            } );

            children.sort( api.utils.prioritySort );
            let activeCount = 0;
            _( children ).each( function( child ) {

                if ( child.active() && child.isContextuallyActive() ) {

                    activeCount += 1;
                }
            } );

            return ( activeCount !== 0 );
        },
    } );

    let _sectionEmbed               = wp.customize.Section.prototype.embed,
        _sectionIsContexuallyActive = wp.customize.Section.prototype.isContextuallyActive,
        _sectionAttachEvents        = wp.customize.Section.prototype.attachEvents;

    wp.customize.Section = wp.customize.Section.extend( {
        attachEvents () {
            if ( 'mailtpl-section' !== this.params.type || 'undefined' === typeof this.params.section ) {

                _sectionAttachEvents.call( this );
                return;
            }

            _sectionAttachEvents.call( this );
            let section = this;
            section.expanded.bind( function( expanded ) {

                let parent = api.section( section.params.section );
                if ( expanded ) {

                    parent.contentContainer.addClass( 'current-section-parent' );
                } else {

                    parent.contentContainer.removeClass( 'current-section-parent' );
                }

                section.container.find( '.customize-panel-back' ).off( 'click keydown' ).on( 'click keydown', function( event ) {
                    if ( api.utils.isKeydownButNotEnterEvent( event ) ) {

                        return;
                    }

                    event.preventDefault();
                    if ( section.expanded() ) {

                        api.section( section.params.section ).expand();
                    }
                } );
            } );
        },
        embed () {

            if ( 'mailtpl-section' !== this.params.type || 'undefined' !== typeof this.params.section ) {

                _sectionEmbed.call( this );
                return;
            }

            let section         = this,
                parentContainer = $( '#subsection-accordion-section-' + this.params.section );
            parentContainer.append( section.headContainer );
        },
        isContextuallyActive () {

            if ( 'mailtpl-section' !== this.params.type || 'undefined' === typeof this.params.section ) {

                return _sectionIsContexuallyActive.call( this );
            }

            let section = this, children = this._children( 'section', 'control' );
            api.section.each( function( child ) {

                if ( ! child.params.section ) {

                    return;
                }

                if ( ! child.params.section !== section.id ) {

                    return;
                }

                children.push( child );
            } );

            children.sort( api.utils.prioritySort );
            let activeCount = 0;
            _( children ).each( function( child ) {

                if ( 'undefined' !== typeof child.isContextuallyActive ) {

                    if ( child.active() && child.isContextuallyActive() ) {

                        activeCount += 1;
                    } else {

                        if ( child.active() ) {

                            activeCount += 1;
                        }
                    }
                }
            } );

            return ( activeCount !== 0 );
        }
    } );
    /**
     *
     * @param {string}parent_setting
     * @param {string}affected_control
     * @param {string}value
     */
    const mailtpl_display_hide = ( parent_setting, affected_control, value ) => {
        wp.customize( parent_setting, function( setting ) {
            wp.customize.control( affected_control, function( control ) {
                let visibility = function() {
                    if ( String( setting.get() ).trim().length ) {
                        control.container.slideDown( 100 );
                    } else {
                        control.container.slideUp( 100 );
                    }
                };

                visibility();
                setting.bind( visibility );
            } );
        } );
    };

    mailtpl_display_hide( 'mailtpl_opts[header_logo]', 'mailtpl_opts[image_width_control]', '127.0.0.1' );
} )( jQuery )