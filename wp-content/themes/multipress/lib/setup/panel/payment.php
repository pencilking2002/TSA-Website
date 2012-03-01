<?php
	/*PayPal Settings*/
    $general_hint = __( "This implementation works in conjunction with <a href='https://www.paypal.com/us/mrb/pal=KMEJ5UCMUQVAW'>PayPal&reg; Website Payments Standard</a>, for businesses.<br> You do NOT need a PayPal&reg; Pro account. 
    				You just need to <a href='http://pages.ebay.com/help/buy/questions/upgrade-paypal-account.html'>upgrade</a> your Personal PayPal&reg; account to a<br> Business status, which is free. A PayPal&reg; account can be upgraded from a 
    				Personal account to a Business<br> account, simply by going to the `Profile` button under the `My Account` tab, selecting the `Personal Business</br> Information` 
    				button, and then clicking the `Upgrade Your Account` button.<br/><br/>

					<strong>*PayPal&reg; API Credentials*</strong> Once you have a PayPal&reg; Business account, you'll need access to your <a href='https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO'>
					PayPal&reg;<br> API Credentials</a>. Log into your PayPal&reg; account, and navigate to Profile -> Request API Credentials. You'll choose<br> ( PayPal&reg; API ), and then choose ( Create Your Own ). Once you've got your API Credentials, 
					come back and<br> paste them into the fields below. <br/><br/>
					
					<b>NOTE! </b> In order to be able to use PayPal you must have CURL enabled.
					" , _DEV_ );
					
	
	
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'account_details_title' ] = array(
        'type' => 'ni--title',
        'title' => __( 'PayPal&reg; Account Details ( required, if using PayPal&reg; )' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'account_details_title' ] = array(
        'type' => 'cd--about',
        'content' => '<div class="standard-generic-field "><p>' . $general_hint . '</p></div>'
    );
		
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'paypal_email' ] = array(
        'type' => 'st--text',
        'label' => __( 'Your PayPal&reg; EMail Address' , _DEV_ ),
		'hint' => __( 'Enter the email address you have associated with your PayPal&reg; Business account.' , _DEV_ )
    );
	
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'paypal_api_username' ] = array(
        'type' => 'st--text',
        'label' => __( 'Your PayPal&reg; API Username' , _DEV_ ),
		'hint' => __( 'In your PayPal&reg; account, go to: Profile -> Request API Credentials.' , _DEV_ )
    );
	
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'paypal_api_password' ] = array(
        'type' => 'st--text',
        'label' => __( 'Your PayPal&reg; API Password' , _DEV_ ),
		'hint' => __( 'In your PayPal&reg; account, go to: Profile -> Request API Credentials.' , _DEV_ )
    );
	
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'paypal_api_signature' ] = array(
        'type' => 'st--text',
        'label' => __( 'Your PayPal&reg; API Signature' , _DEV_ ),
		'hint' => __( 'In your PayPal&reg; account, go to: Profile -> Request API Credentials.' , _DEV_ )
	);
	
	//currency select goes here ..........
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'currency' ] = array(
        'type' => 'st--select' , 
        'label' => __( 'Select currency' , _DEV_ ) ,
		'hint' => __('Select currency for you items price',_DEV_) , 
		'values' => _tools::currencies() //get__pages( array( '-' => __( 'Select currency' , _DEV_  ) ) ) 
	);
	
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'return_url' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select Return Page' , _DEV_ ),
		'hint' => __('Select a blank page from the list ',_DEV_),
		'help' => $help[ 'paypal_return_url' ],
		'values' => get__pages( array( '-' => __( 'Select page' , _DEV_  ) ) ) 
    );
	
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'cancel_url' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select Cancel Page' , _DEV_ ),
		'hint' => __('Select a blank page from the list ',_DEV_),
		'help' => $help[ 'paypal_cancel_url' ],
		'values' => get__pages( array( '-' => __( 'Select page' , _DEV_  ) ) ) 
    );
	
	
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'cart_page' ] = array(
        'type' => 'st--select' , 
        'label' => __( 'Select shopping cart page' , _DEV_ ) ,
		'hint' => __('Create a blank page then select it from the list to generate the Shopping cart page',_DEV_) , 
		'values' => get__pages( array( '-' => __( 'Select page' , _DEV_  ) ) ) 
	);
	
	_panel::$fields[ 'settings' ]['payment']['paypal']['paypal_sandbox']  = array( 
		'type' => 'st--logic-radio' , 
		'label' => __( 'Enable Developer/Sandbox Testing?' , _DEV_ ) ,
		'hint' => __( 'Only enable this if you have provided Sandbox credentials above. <br/> This puts the API, IPN, PDT and Form/Button Generators all into Sandbox mode.' , _DEV_)
	);
	
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'email_subject' ] = array(
        'type' => 'st--text',
        'label' => __( 'Email subject' , _DEV_ ),
		'hint' => __( 'The subject of the email the will be sent to customer after the succesfull transaction.' , _DEV_ )
	);
	
	_panel::$fields[ 'settings' ][ 'payment' ][ 'paypal' ][ 'email_content' ] = array(
        'type' => 'st--textarea',
        'label' => __( 'Email content' , _DEV_ ),
		'hint' => __( 'The content of the email the will be sent to customer after the succesfull transaction. You can use %customer_name%, and it will be replaced with the name of the buyer.', _DEV_ )
	);
	
?>