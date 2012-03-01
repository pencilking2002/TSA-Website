<?php
	if(isset($_GET['tr_d']) && is_numeric($_GET['tr_d'])){
		$transactions_details = _core::method('_meta','get',$_GET['tr_d'],'items_sold');
		//var_dump($transactions_details);
		if(sizeof($transactions_details) && is_array($transactions_details)){
?>
			<table class="table t_subscript" id="checkout">
				<thead>
					<tr>
						<th class=""><?php _e('Ticket ID',_DEV_); ?></th>
						<th class="" style="width: 15%;"><?php _e('Buyer_name',_DEV_); ?></th>
						<th class="last" style="width: 10%;"><?php _e('Date',_DEV_); ?></th>
					</tr>
				</thead>
				<tbody>
<?php		
			foreach($transactions_details as $transaction){
?>
				<tr class="elements">	
					<td>
						<span class="license"><?php echo $transaction['ticket_id']; ?> </span>
					</td>
					<td>
						<span ><?php echo $transaction['buyer_name']; ?> </span>
					</td>
					<td class="last">
						<span ><?php echo $transaction['date']; ?> </span>
					</td>
				</tr>
				
<?php				
			}
?>
				</tbody>
			</table>	
<?php			
		}
	}
?>