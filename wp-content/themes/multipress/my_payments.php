<?php
	global $current_user;
	get_currentuserinfo();
	
	$items_purchased = get_user_meta( $current_user->ID , 'purchased_items' , true );
    
	if( sizeof( $items_purchased ) ){
?>
		<table class="table t_subscript" id="checkout">
			<thead>
				<tr>
					<th class="" style="width: 7%;"><?php _e('Item',_DEV_); ?></th>
					<th class=""><?php _e('Product',_DEV_); ?></th>
					<th class="" style="width: 10%;"><?php _e('Price',_DEV_); ?></th>
					<th class="" style="width: 10%;"><?php _e('Quantity',_DEV_); ?></th>
					<th class="last" style="width: 10%;"><?php _e('Action',_DEV_); ?></th>
				</tr>
			</thead>
			<tbody>
<?php
			$item_nmbr = 1;
			$currency = _core::method('_settings','get','settings','payment','paypal','currency' );
			foreach($items_purchased as $transaction){
				foreach($transaction as $item){
?>
					<tr class="elements">
						<td>
							<span><?php echo $item['transaction_Id']; ?></span>
						</td>	
						<td>
							<span class="license"><?php echo get_the_title($item['post_id']); ?> </span>
						</td>
						<td >
							<?php echo _cart::get_currency_symbol($currency).' '.$item['price'] ?>
						</td>
						<td>
							<span><?php echo $item['qty']; ?></span>
						</td>
						<td class="last" id="total_item_1">
							<?php 
								/*for now only tickets for events are sold, thus we always show download link, 
									if other kind of items will be sold then you may want to change that!!!
								*/
								$pdf_link = '';
								foreach($item['ticket_id'] as $ticket_id){
									/*we need to send 3 parameters:
									user_id, transaction_id and ticket_id */
									$ticket_link = add_query_arg( "uid", $current_user->ID, get_permalink(_core::method("_settings","get","settings", "payment", "paypal", "return_url")) );
									$ticket_link = add_query_arg( "tid", $ticket_id, $ticket_link );
									
									$pdf_link .= '<a href="'.add_query_arg( "tr_id", $item["transaction_Id"], $ticket_link ).'" > '.__("Download",_DEV_).' </a><br/>';
								}
								echo $pdf_link;
							?>
							
						</td>	
					</tr>
<?php			
					$item_nmbr++;
				}
			}
?>			
			</tbody>
		</table>
<?php	
	}
?>