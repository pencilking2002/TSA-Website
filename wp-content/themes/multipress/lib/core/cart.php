<?php 
	class _cart {
		
		function get_btn($post_id, $cart_action='add_item'){
			/*$cart_action may be update_cart or add_item*/
			
			$post_info = get_post( $post_id );
			$registration = _core::method('_meta','get',$post_id,'register');
			
		
		//$w = _core::method("_settings","get","settings", "payment", "paypal", "cart_page");


		
			
			//$result = '<input type="button" value="Add to cart" class="addtocart_btn" data-id='.$post_id.'>';
			$result = '<p class="button add-to-cart addtocart_btn" data-id='.$post_id.'><a href="javascript:void(0)" ><span>'.__("add to cart",_DEV_).'</span></a></p>';
			return $result;
			//AddToCart(qty,price,post_id,cart_action)
		
		}
		
		/*The addtocart method makes sure that the session variable is initialzed and then 
		 * stores the received productid and quantiy to next available index. And note that 
		 * there is no need to increment the $max variable*/
		function addtocart(){    
			$q = 0;
			
			if( isset($_POST['qty']) && isset($_POST['post_id'])){
				$registration = _core::method('_meta','get',$_POST['post_id'],'register');
				if(is_array($registration) && sizeof($registration) && $registration['enable'] == 'yes' && $registration['use'] == 'yes' && $registration['value'] != '' ){
					$post_info = get_post( $_POST['post_id'] );
					$q = $_POST['qty'];
					$pid = $post_info->post_title;
					//$price = $_POST['price'];
					$price = $registration['value'];
					$post_id = $_POST['post_id'];
				}
				
			}
			/*$pid is the product id (may be name of the ticket for example),
			 * $q is he qty added,
			 * $post_id is the post (event) for which the tickets are sold */
			
			$responce = array('error_msg'=>'');
			
			if($pid == '' or (int)$q<1){
				$responce['error_msg'] =  	__("Please specify quantity",_DEV_);
				echo json_encode($responce);
				exit;
			} 
		 	
			/*check if this item is already in cart session, and add summ up the qty added now with the qty fron session*/
			$session_qty = 0;
			if(is_array($_SESSION['cart'])){
				if(self::product_exists($pid) >= 0){
					if(isset($_POST['cart_action']) && $_POST['cart_action'] == 'update_cart'){
						$session_qty = $q;
					}else{
						$session_qty = $_SESSION['cart'][self::product_exists($pid)]['qty'] + $q;
					}	
					
				}
				
			}
			
			/*if qty available is less than requesten qty + qty from session, we don't allow to add the item to the cart*/
			if(self::get_available_qty($pid, $post_id) < $session_qty){
				$responce['error_msg'] =  	__("Unfortunately we do not have enough tickets, please try adding smaller quantity",_DEV_);
				echo json_encode($responce);
				
				exit;
			}
			
			if(is_array($_SESSION['cart'])){
				
				/*If product exist we will increment the qty*/
				if(self::product_exists($pid) >= 0){
					if(isset($_POST['cart_action']) && $_POST['cart_action'] == 'update_cart'){
						
						$_SESSION['cart'][self::product_exists($pid)]['qty'] = $q;
					}else{
						$_SESSION['cart'][self::product_exists($pid)]['qty'] += $q;
					}	
					echo json_encode($_SESSION['cart']);
					exit;
				}
				
				$max=count($_SESSION['cart']);
				$_SESSION['cart'][$max]['productid']=$pid;
				$_SESSION['cart'][$max]['qty']=$q;
				$_SESSION['cart'][$max]['price']=$price;
				$_SESSION['cart'][$max]['post_id']=$post_id;	
			}
			else{
				$_SESSION['cart']=array();
				$_SESSION['cart'][0]['productid']=$pid;
				$_SESSION['cart'][0]['qty']=$q;
				$_SESSION['cart'][0]['price']=$price;
				$_SESSION['cart'][0]['post_id']=$post_id;
			} 
			
			echo json_encode($_SESSION['cart']);
			
			exit;
		}
		
		function redirect_shopping_cart(){
			
			echo get_permalink(_core::method("_settings","get","settings", "payment", "paypal", "cart_page"));
			exit;
		}
		
		/*The function goes through all the elements of shopping cart and checks if a products exists in the shopping cart*/
		function product_exists($pid){
			$pid=$pid;
			$max=count($_SESSION['cart']);
			$flag=-1;
			
			for($i = 0;$i < $max; $i++){
				
				if($pid==$_SESSION['cart'][$i]['productid']){
					$flag = $i; /*return the index of the product in the cart*/
					break;
				}
			}
			return $flag;
		}
		
		
		/*The remove_product function first finds the product and then removes the corresponding index from 
		 * the session array. The last statement { $_SESSION['cart']=array_values($_SESSION['cart']) } resets the array indexes.*/
		function remove_product(){
			$pid = $_POST['item_id'];
			$pid=intval($pid);
			$max=count($_SESSION['cart']);
			for($i=0;$i<$max;$i++){
				if($pid==$_SESSION['cart'][$i]['post_id']){
					unset($_SESSION['cart'][$i]);
					break;
				}
			}
			$_SESSION['cart']=array_values($_SESSION['cart']);

			return $_SESSION['cart'];
			
			if(isset($_POST['is_ajax'])){
			/* FOR ajax request */
				exit();
			}
		}
		
		function get_available_qty($pid, $post_id){
			$available_qty = 0;
			//$tickets = meta::get_meta( $post_id , 'tickets' );
			$registration = _core::method('_meta','get',$post_id,'register');
			
			$available_qty = $registration['quantity'];
			
			if((int)$available_qty > 0){
				$available_qty = $available_qty - (int)_meta::get( $post_id , 'register','quantity_sold' );;
			}else{
				$available_qty = 999999999;
			}	
			/*foreach ($tickets as $ticket) {
				if($ticket['ticket_title'] == $pid){
					$available_qty = $registration['quantity'];
				}
			}*/
			
			return $available_qty;
		}
		
		function update_qty($pid, $post_id , $changed_qty, $action = 'add'){ /*$action can be add OR remove*/
			$tickets = meta::get_meta( $post_id , 'tickets' );
			
			$tickets_new = $tickets;
			$index = 0;
			foreach ($tickets as $ticket) {
				if($ticket['ticket_title'] == $pid){
					$available_qty = $ticket['ticket_qty_available'];
					
					if($action == 'add'){
						$available_qty_updated = $available_qty + $changed_qty;
					}else{
						$available_qty_updated = $available_qty - $changed_qty;
					}
					$tickets_new[$index]['ticket_qty_available'] = $available_qty_updated;
					
				}
				$index ++;
			}
			
			//delete_post_meta($post_id, 'tickets' );
			meta::set_meta( $post_id , 'tickets' , $tickets_new );
		}
		
		function get_shopping_cart_totals(){
			
			if(is_array($_SESSION['cart']) && sizeof($_SESSION['cart'])){
				$qty = 0;
				$sum = 0;
				
				foreach ($_SESSION['cart'] as $ticket) {
					$qty += $ticket['qty'];
					$sum += $ticket['qty']*$ticket['price'];
				}
				$checkout_page = _core::method("_settings","get","settings", "payment", "paypal", "cart_page");
				$checkout_page_link = '';
				if( (int)$checkout_page > 0 ){
					$checkout_page_link = '<a href="'.get_permalink( (int)$checkout_page ).'">'.__('Open shopping cart',_DEV_).'</a>';
				}
				//[ 'settings' ][ 'payment' ][ 'paypal' ][ 'cart_page' ]
				$result = '<div>'.sprintf(__('%d (%d items)',_DEV_),$sum, $qty).'</div>';
				if($checkout_page_link != ''){
					$result .= '<div class="shoping_cart_link">'.$checkout_page_link.'</div>';
				}
			}else{
				$result = '<div>'.__("Shopping cart is empty",_DEV_).'</div>';
			}
			
				return $result;
			
		}
		
		function show_cart_total(){
			echo self::get_shopping_cart_totals();
			exit;
		}
		
		function get_shopping_cart_details(){
			$cart_details = __('Your cart is currently empty',_DEV_);
			$_SESSION["Payment_Amount"] = 	0;
			if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
				$currency = _core::method('_settings','get','settings','payment','paypal','currency' );
				
				$cart_details = '<table class="table t_subscript" cellpadding="0" cellspacing="0" id="checkout">';
				$cart_details .='	<tbody>';
				$cart_details .='	<tr>';
				$cart_details .='		<th class="" style="width: 7%;">'.__('Remove',_DEV_).'</th>';
				$cart_details .='		<th class="" >'.__('Product',_DEV_).'</th>';
				$cart_details .='		<th class="" style="width: 10%;">'.__('Price',_DEV_).'</th>';
				$cart_details .='		<th class="" style="width: 10%;">'.__('Quantity',_DEV_).'</th>';
				$cart_details .='		<th class="last" style="width: 10%;">'.__('Total price',_DEV_).'</th>';
				$cart_details .='	</tr>';
				$subtotal = 0;
				foreach ($_SESSION['cart'] as $cart_item) {
					$cart_details .='<tr class="elements">';
					$confirm_msg = __('Are you sure ?',_DEV_);
					$cart_details .='	<td><a href="javascript:void(0)" class="delete" onclick="remove_cart_item(\''.$cart_item['post_id'].'\',\''.$confirm_msg.'\')"><span class="remove">'.__("Remove item",_DEV_).'</span></a></td>';
					$cart_details .='	<td><span class="license">'.$cart_item['productid'].'</span></td>';
					$cart_details .='	<td>'.self::get_currency_symbol($currency).' '.$cart_item['price'].'</td>';
					$cart_details .='	<td><input type="text" id="qty_'.$cart_item['productid'].'_'.$cart_item['post_id'].'" onkeyup="update_shopping_cart(jQuery(this),'.$cart_item['post_id'].');" class="digit" value="'.$cart_item['qty'].'"></td>';
					$cart_details .='	<td class="last">'.self::get_currency_symbol($currency).' '.$cart_item['qty']*$cart_item['price'].'</td>';
					
					$cart_details .='</tr>';
					$subtotal += $cart_item['qty']*$cart_item['price'];
				}
				$cart_details .='	<tr class="subtotal">';
				$cart_details .='		<td colspan=4>'.__('Grand total',_DEV_).'</td>';
				$cart_details .='		<td id="grandTotal"> '.self::get_currency_symbol($currency).' '.$subtotal.'</td>';
				$cart_details .='	</tr>';
				$cart_details .='	</tbody>';
				$cart_details .='</table>';
				
				$_SESSION["Payment_Amount"] = 	$subtotal;
				
			}
			
			return $cart_details;
		}
		
		function get_cart_details_updated(){
			//echo self::get_shopping_cart_details();
			$responce = array();
			$responce['content'] = self::get_shopping_cart_details();
			$responce['payment_amount'] = $_SESSION["Payment_Amount"];
			
			echo json_encode($responce);
			exit();
		}
		
		function expresscheckout(){
			require_once 'paypal/expresscheckout.php';
		}
		
		function confirm_payment(){
			require_once 'paypal/order_confirm.php';
			exit();
		}
		
		function send_notif_email($customer_name,$transaction_Id,$attachment_tickets){
			//$headers = 'From: My Name <myname@mydomain.com>' . "\r\n";
			global $current_user;
			get_currentuserinfo(); 
			$headers = '';
			$mail_to = $current_user->user_email;
			$subject = _core::method('_settings','get','settings','payment','paypal','email_subject' );
			$message = str_replace('%customer_name%',$customer_name,_core::method('_settings','get','settings','payment','paypal','email_content' ));
			wp_mail($mail_to, $subject, $message, $headers, $attachment_tickets);
			
			foreach($attachment_tickets as $ticket){
				if(file_exists($ticket)){
					/*remove files from hard drive*/
					unlink($ticket); 
				}
			}
		}
		
		function get_transaction_details($items_purchased,$transaction_Id){
			if(sizeof($items_purchased)){
				
							
				$currency = _core::method('_settings','get','settings','payment','paypal','currency' );
				$cart_details = __('<h3>Transaction details</h3>',_DEV_);
				$cart_details .= '<table class="table t_subscript" cellpadding="0" cellspacing="0" id="checkout">';
				$cart_details .='	<tbody>';
				$cart_details .='	<tr>';
				$cart_details .='		<th class="" >'.__('Product',_DEV_).'</th>';
				$cart_details .='		<th class="" style="width: 10%;">'.__('Price',_DEV_).'</th>';
				$cart_details .='		<th class="" style="width: 10%;">'.__('Quantity',_DEV_).'</th>';
				$cart_details .='		<th class="" style="width: 60px;">'.__('Total price',_DEV_).'</th>';
				$cart_details .='		<th class="" style="width: 90px;">'.__('Tools',_DEV_).'</th>';
				$cart_details .='	</tr>';
				$subtotal = 0;
				//_deb::e($items_purchased );
				foreach ($items_purchased as $index => $cart_items) {
					if($index == $transaction_Id){
						foreach($cart_items as $cart_item){
							global $current_user;
							get_currentuserinfo();
							
							//_deb::e($cart_item );
							$registration = _core::method('_meta','get',$cart_item['post_id'],'register');
							$pdf_link = '';
							if(is_array($registration) && sizeof($registration) && $registration['enable'] == 'yes' && $registration['use'] == 'yes' && $registration['value'] != '' && $registration['quantity'] >0 ){
								/*show PDF generation link*/
								
								//$pdf_link = '<a href="'.add_query_arg( "doc", $cart_item["post_id"], get_permalink(_core::method("_settings","get","settings", "payment", "paypal", "return_url")) ).'" > '.__("Download ticket",_DEV_).' </a>';
								foreach($cart_item['ticket_id'] as $ticket_id){
									/*we need to send 3 parameters:
									user_id, transaction_id and ticket_id */
									$ticket_link = add_query_arg( "uid", $current_user->ID, get_permalink(_core::method("_settings","get","settings", "payment", "paypal", "return_url")) );
									$ticket_link = add_query_arg( "tid", $ticket_id, $ticket_link );
									
									$pdf_link .= '<a href="'.add_query_arg( "tr_id", $cart_item["transaction_Id"], $ticket_link ).'" > '.__("Download ticket",_DEV_).' </a><br/>';	
								
								}
							}
								
								
						
							$cart_details .='<tr class="elements">';
							$cart_details .='	<td><span class="license">'. get_the_title($cart_item["post_id"]).'</span></td>';
							$cart_details .='	<td>'.self::get_currency_symbol($currency).' '.$cart_item['price'].'</td>';
							$cart_details .='	<td>'.$cart_item['qty'].'</td>';
							$cart_details .='	<td>'.self::get_currency_symbol($currency).' '.$cart_item['qty']*$cart_item['price'].'</td>';
							$cart_details .='	<td>'.$pdf_link.'</td>';
							
							$cart_details .='</tr>';
							$subtotal += $cart_item['qty']*$cart_item['price'];
						}	
					}
				}
				$cart_details .='	<tr class="subtotal">';
				$cart_details .='		<td colspan=4>'.__('Grand total',_DEV_).'</td>';
				$cart_details .='		<td id="grandTotal"> '.self::get_currency_symbol($currency).' '.$subtotal.'</td>';
				$cart_details .='	</tr>';
				$cart_details .='	</tbody>';
				$cart_details .='</table>';
				
				return $cart_details;	
			}else{
				return '';
			}
			
		}
		
		
		function save_transaction(){
			//var_dump($_POST['response_data']);
			if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
				$currency = _core::method('_settings','get','settings','payment','paypal','currency' );
				
				global $current_user, $wp_roles;
				get_currentuserinfo();
				$items_purchased = get_user_meta($current_user->ID, 'purchased_items', true);
				if(!is_array($items_purchased)){
					$items_purchased = array();
				}	
				
				$transaction_details = array();
				
				$attachment_tickets = array();
				foreach ($_SESSION['cart'] as $cart_item) {
				
					/*for each post attach qty sold meta,*/
					$register_meta = _meta::get( $cart_item['post_id'] , 'register' );
					$updated_qty_sold = $cart_item['qty'] + (int)_meta::get( $cart_item['post_id'] , 'register','quantity-sold' );
					$register_meta['quantity-sold'] = $updated_qty_sold;
					_core::method('_meta','set', $cart_item['post_id'] , 'register' , $register_meta );
					
					$nr_items_sold = (int)_meta::get( $cart_item['post_id'] , 'nr_items_sold' );
					_core::method('_meta','set', $cart_item['post_id'] , 'nr_items_sold' , ($nr_items_sold + $cart_item['qty']) ); /*update nr_items sold meta*/
					
					
					/*--------------*/
					
					$ticket_id = array();
					$transaction_Id = $_POST['response_data']['transactionId'];
					$registration = _core::method('_meta','get',$cart_item['post_id'],'register');
					
					$items_sold = _meta::get( $cart_item['post_id'] , 'items_sold' );
					
					if(!is_array($items_sold)){
						$items_sold = array();
					}					
					if(is_array($registration) && sizeof($registration) && $registration['enable'] == 'yes' && $registration['use'] == 'yes' && $registration['value'] != '' && $registration['quantity'] >0 ){
						/*if paid registration is enabled */
						for($i = 0;$i < $cart_item['qty']; $i++){
							//this one is only for tickets, because we save data ablout each ticket	
							$tid = mt_rand(0,2147483640).'_'.$transaction_Id; //we need ID for each ticket, this is only for tickets
							$ticket_id[] = $tid;
							/*for tickets we need to save info about each sold unit, to be able to display statistics*/
							
							$items_sold[] = array(	'ticket_id'=>$tid, 
													'buyer_name'=>$_SESSION['customer_name'],
													'price'=> $cart_item['price'],
													'date'=>$_POST['response_data']['orderTime']	);
						}
					}
					_core::method('_meta','set', $cart_item['post_id'] , 'items_sold' , $items_sold ); /*update items sold meta*/
					
					$post_id = $cart_item['post_id'];
					$price = $cart_item['price'];
					$qty = $cart_item['qty'];
					$buyer_name = $_SESSION['customer_name'];
					$purchase_date = $_POST['response_data']['orderTime'];
					$currencyCode = $currency;
					
					
					/*for each unit sold create ticket ID - random and assign the values above in an array.
						then set this array as user meta 
						
						ticket_id - random number
						{
							transaction_id,
							post_id,
							price,
							purchase_date,
							customer name (get from $_SESSION['customer_name'] )
					}*/
					
					

					$item_info[] = array('ticket_id' => $ticket_id,
										'post_id' => $post_id,
										'price' => $price,
										'qty' => $qty,
										'buyer_name' => $buyer_name,
										'purchase_date' => $purchase_date,
										'transaction_Id' => $transaction_Id,
										'currencyCode' => $currencyCode);	
										
					$item = array(	'ticket_id' => $ticket_id,
									'post_id' => $post_id,
									'price' => $price,
									'qty' => $qty,
									'buyer_name' => $buyer_name,
									'purchase_date' => $purchase_date,
									'transaction_Id' => $transaction_Id,
									'currencyCode' => $currencyCode);					
										
					foreach($ticket_id as $ticket){
						/*save each ticket in uploads*/
						_core::method('_invoice','generateTicket',$item,$ticket,false );
						$attachment_tickets[] = WP_CONTENT_DIR . '/uploads/'.$ticket.".pdf";
					}					
				}
				//$transaction_details[$transaction_Id] = $item_info;
				$items_purchased[$transaction_Id] = $item_info;
				update_user_meta( $current_user->ID, 'purchased_items', $items_purchased);
				
				$responce['transaction_details'] = self::get_transaction_details($items_purchased,$transaction_Id);
				self::send_notif_email($_SESSION['customer_name'],$transaction_Id,$attachment_tickets);
				$_SESSION['cart'] = array(); //empty cart
				$responce['success_msg'] = __('Thank you',_DEV_);
				
				
				
				echo json_encode( $responce );
				
				/* the data is saved in this format:
				Array
				(
					[34K56726E4169270X] => Array
						(
							[0] => Array
								(
									[ticket_id] => Array
										(
											[0] => 1405779052_34K56726E4169270X
										)
									[post_id] => 39
									[price] => 25
									[qty] => 1
									[buyer_name] => Test User 
									[purchase_date] => 2011-12-09T14:56:22Z
									[transaction_Id] => 34K56726E4169270X
									[currencyCode] => USD
								)
							[1] => Array
								(
									[ticket_id] => Array
										(
											[0] => 1646910765_34K56726E4169270X
											[1] => 1238762418_34K56726E4169270X
										)
									[post_id] => 36
									[price] => 12
									[qty] => 2
									[buyer_name] => Test User 
									[purchase_date] => 2011-12-09T14:56:22Z
									[transaction_Id] => 34K56726E4169270X
									[currencyCode] => USD
								)
						)
					[43W00366B8661011Y] => Array
						(
							[0] => Array
								(
									[ticket_id] => Array
										(
											[0] => 406249235_43W00366B8661011Y
										)
									[post_id] => 39
									[price] => 25
									[qty] => 1
									[buyer_name] => Test User 
									[purchase_date] => 2011-12-09T14:58:30Z
									[transaction_Id] => 43W00366B8661011Y
									[currencyCode] => USD
								)
						)
				)*/
				
				
			}	
			
			
			exit();
		}
		
		function get_currency_symbol($currency){
			switch ($currency) {
				case 'USD':
					$result = '$';
					break;
				case 'EUR':
					$result = '&euro;';
					break;
				default:
					$result = $currency;
					break;
			}
			
			return $result;
		}
	}
?>