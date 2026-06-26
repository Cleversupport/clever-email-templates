( async function() {
    const buttons = document.querySelectorAll( '.post-smtp-notice-install' );

    buttons.forEach( function( button ) {
        const action = button.getAttribute( 'data-action' );

        button.addEventListener( 'click', async function( e ) {
            e.preventDefault();
            button.setAttribute( 'disabled', 'disabled' );
            
            if( action === 'install-plugin_post-smtp' ) {
                button.innerHTML = 'Installing...';

                try {
                    // Send AJAX call for installation
                    await sendPostSMTPRequest( 'installed' );
                    
                    const response = await wp.updates.installPlugin( {
                        slug: 'post-smtp'
                    } );
                    
                    // Plugin Installed, Lets activate immediately!
                    if( response.activateUrl !== undefined && response.install === 'plugin' ) {
                        await activatePostSMTP( button );
                    }
                } catch ( error ) {
                    console.error( error );
                    button.innerHTML = 'Error!';
                }
            }
            if( action === 'activate-plugin_post-smtp' ) {
                // Send AJAX call for activation
                await sendPostSMTPRequest( 'activated' );
                await activatePostSMTP( button );
            }
        } );
    } );
} )();

// AJAX call function to send request to SMTP server
const sendPostSMTPRequest = async function( status ) {
    try {
        
        const formData = new URLSearchParams({
            action: 'post_smtp_request',
            status: status,
            nonce: recommendPostSMTP.ajaxNonce
        });
        
        const response = await fetch( recommendPostSMTP.ajaxURL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        });
        
        if( !response.ok ) {
            const errorText = await response.text();
            console.error( 'Response error:', errorText );
            throw new Error( `HTTP ${response.status}: ${errorText}` );
        }
        
        const data = await response.json();
        
        if( data.success ) {
            return true;
        } else {
            return false;
        }
    } catch ( error ) {
        console.error( 'Error sending Post SMTP AJAX request:', error );
    }
}

const activatePostSMTP = async function( button ) {
    button.innerHTML = 'Activating...';

    const activateResponse = await wp.updates.activatePlugin( {
        slug: 'post-smtp',
        name: 'Post SMTP',
        plugin: 'post-smtp/postman-smtp.php'
    } );

    if( activateResponse ) {
        // Send final AJAX call for activation
        await sendPostSMTPRequest( 'activated' );
        
        button.innerHTML = 'Activated!';
        // Redirect after successful activation
        setTimeout(() => {
            window.location.href = recommendPostSMTP.postSMTPURL;
        }, 1000);
    }
    else {
        button.innerHTML = 'Error!';
    }
} 

// Notice dismiss functionality removed since admin notice is disabled