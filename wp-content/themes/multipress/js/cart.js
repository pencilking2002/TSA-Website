

jQuery(document).ready(function( ){
	
	jQuery('.confirm_payment').click(function() {
		jQuery('#ajax-indicator').show();
		jQuery.post( ajaxurl ,
						{
							"action" : 'confirm_payment' 
						} ,
						function( data ){ 
						json = eval("(" + data + ")");
						if(json['error_msg'] && json['error_msg'] != ''){
							jQuery('.response_msg').html('<div class="warning">'+json['error_msg']+'</div>');
							jQuery('#ajax-indicator').hide();
						}else{
							/*save all data about transaction*/
							save_transaction(json);
						}
					});
	});
	
	jQuery('.addtocart_btn').click(function() { 
		postId = jQuery(this).data('id');  
		AddToCart(1,postId,'add_item');
	});
});

function save_transaction(response_data){
	jQuery('.confirm_payment').hide();
	jQuery('#shopping_cart_details').hide();
	
	jQuery.post( ajaxurl ,
		{
			"action" : 'save_transaction',
			"response_data" : response_data	
		} ,
		function( data ){ 
			
			json = eval("(" + data + ")");
			if(json['success_msg'] && json['success_msg'] != ''){
				jQuery('.response_msg').html(json['transaction_details']);
				//jQuery('.response_msg').html(json['success_msg']);
			}
			jQuery('#ajax-indicator').hide();
	});
}

function AddToCart(qty,post_id,cart_action){
	jQuery('#ajax-indicator').show();
	jQuery.post( ajaxurl ,
            {
                "action" : 'add_to_cart' ,
                //"item_id" : item_id,
                "qty" : qty,
                "post_id" : post_id,
                "cart_action" : cart_action	
            } ,
            function( data ){ 
            	json = eval("(" + data + ")");
        		if(json['error_msg'] && json['error_msg'] != ''){
        			alert(json['error_msg']);
        		}else{
					if(cart_action == 'update_cart'){
						get_shopping_cart_details();
					}else{
						/*redirect to shopping cart page*/
						jQuery.post( ajaxurl ,
								{
									"action" : 'redirect_shopping_cart' 
								} ,
								function( data ){ 
									
								jQuery('#ajax-indicator').hide();
								window.location = data;								
						});
						
						/*jQuery.post( ajaxurl ,
								{
									"action" : 'get_cart_total' 
								} ,
								function( data ){ 
									jQuery('#shopping-cart').html(data);
									jQuery('#ajax-indicator').hide();
									
						});*/
					}	
        		}
            		
            	
                
    });
}

/*this fnction is triggered when user changes the qty value from the input, on ht e shopping cart page*/
function update_shopping_cart( obj, post_id){
	jQuery('#ajax-indicator').show();
	if(obj.val() == ''){
		
		obj.val('1');
	}
	
	AddToCart(obj.val(),post_id,'update_cart');
	//we want to run get_shopping_cart_details() function after AddToCart()     
	window.setTimeout("get_shopping_cart_details();", 1); 
}

function get_shopping_cart_details(){
	jQuery('#ajax-indicator').show();
	jQuery.post( ajaxurl ,
            {
                "action" : 'get_cart_details_updated' 
            } ,
            function( data ){ 
				json = eval("(" + data + ")");
        		if(json['payment_amount'] == 0){
					jQuery('#paypal_btn').hide();
				}
            	
				jQuery('#shopping_cart_details').html(json['content']);
				jQuery('#ajax-indicator').hide();
                
    });
	
}

function remove_cart_item(item_id,confirm_msg){
	if(confirm(confirm_msg)) {
		jQuery('#ajax-indicator').show();
		jQuery.post( ajaxurl ,
	            {
	                "action" : 'remove_cart_item' ,
	                "item_id" : item_id,
					"is_ajax" : true		
	            } ,
	            function( ){ 
	            	get_shopping_cart_details();
	                
	    });
	}	
}