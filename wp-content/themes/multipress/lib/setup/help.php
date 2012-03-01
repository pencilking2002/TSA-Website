<?php
    $help['123'] = array( 
        'title' => __( 'First help title' , _DEV_ ),
        'desc' => __( 'First help short description' , _DEV_ ),
        'class' => 'test-help-box',
        'media' => array(
            'type' => 'image',
            'src' => get_template_directory_uri() . '/lib/classes/images/help/123.php'
        )
    );
    
    $help['124'] = array( 
        'title' => __( 'First help title' , _DEV_ ),
        'desc' => __( 'First help short description' , _DEV_ ),
        'class' => 'test-help-box2',
        'media' => array(
            'type' => 'image',
            'src' => get_template_directory_uri() . '/lib/classes/images/help/123.jpg'
        )
    );
	$help['paypal_return_url'] = array( 
        'title' => __( 'Return URL' , _DEV_ ),
        'desc' => __( 'The landing page on the merchant\'s site<br> where the shopper returns to after the <br>Payment review on PayPal. This is <br>usually the Order review page.' , _DEV_ ),
        'class' => 'test-help-box',
        'media' => array(
            'type' => '',
            
        )
    );
	
	$help['paypal_cancel_url'] = array( 
        'title' => __( 'Return URL' , _DEV_ ),
        'desc' => __( 'The landing page on the merchant\'s site<br> where the shopper is taken to when they<br> cancel the PayPal payment review.	' , _DEV_ ),
        'class' => 'test-help-box',
        'media' => array(
            'type' => '',
            
        )
    );
	
?>