<?php
	_panel::$fields[ 'settings' ][ 'payment' ][ 'check_transactions' ][ 'my_payments' ] = array(
        'type' => 'st--select' , 
        'label' => __( 'Select My Payments page' , _DEV_ ) ,
		'hint' => __('Create a blank page then select it from the list to generate My Payments page, a page where the user will be able to see his purchases',_DEV_) , 
		'values' => get__pages( array( '-' => __( 'Select page' , _DEV_  ) ) ) 
	);
	
	_panel::$fields[ 'settings' ][ 'payment' ][ 'check_transactions' ][ 'sold_items' ] = array(
        'type' => 'st--select' , 
        'label' => __( 'Select Sold Items page' , _DEV_ ) ,
		'hint' => __('Select a blank page from the list for Sold Items page, a page form where the admin will be able to see the sold items',_DEV_) , 
		'values' => get__pages( array( '-' => __( 'Select page' , _DEV_  ) ) ) 
	);
?>