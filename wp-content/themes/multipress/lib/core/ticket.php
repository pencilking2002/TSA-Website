<?php

if( isset($_GET['tr_id']) ){
	/* for tickets */
	if( isset($_GET['uid']) && isset($_GET['tid']) ){
		$items_purchased = get_user_meta($_GET['uid'], 'purchased_items', true);
		//var_dump($items_purchased);
		foreach($items_purchased as $index => $items){ //var_dump($item);
			if($index == $_GET['tr_id']){
				foreach($items as $item){ 
					foreach($item['ticket_id'] as $ticket){ 
						if($ticket == $_GET['tid']){
							_core::method('_invoice','generateTicket',$item,$ticket,true );
						}	
					}	
				}	
			}	
		}	
	}	
}else{
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(40,10,'Error! ');
	$pdf->Output();	
}


?>