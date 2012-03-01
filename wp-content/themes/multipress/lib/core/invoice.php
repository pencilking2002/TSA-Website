<?php
	class _invoice{
		function generateTicket($item, $ticket_id, $output = true){
			require_once('fpdf/fpdf.php');


			//$pdf = new FPDF();
			$pdf = new PDF();

			$pdf->AddPage();
							
			$pdf->SetTextColor(0,0,0);

			$pdf->SetFont('Courier','',23);
			$pdf->Cell(150,3,get_the_title($item["post_id"]), 0, 0);
			//$pdf->Cell(150,3,'4th Anual Seattle Conference', 0, 0);
			
			
			$pdf->SetTextColor(255,255,255); 
			//$pdf->SetFont('Courier','B',18);
			$pdf->SetFont('Courier','',15);
			$pdf->MultiCell(40,3,get_the_title($item["post_id"]) );
			//$pdf->MultiCell(40,6,'4th Anual Seattle Conference' );
			
			$y = $pdf->GetY();
			
			$programm = _core::method('_program','getPrgrammDate',$item["post_id"]); 
			$pdf->SetTextColor(98,98,98); 
			if(is_array($programm) && isset($programm['start_end_date'])){
				
				$pdf->SetFont('Courier','',17);
				/*Add date here*/
				//$pdf->Cell(150,15,__($programm['start_end_date'],_DEV_), 0, 1);
				$pdf->Text(11,25,__($programm['start_end_date'],_DEV_));
			}			
			
			$pdf->Ln(30 - $y);
			
										
			$pdf->Cell(150,10,$item['buyer_name'],0,0);
			
			$pdf->SetTextColor(255,255,255); 
			$pdf->Cell(40,10,$item['buyer_name'],0,1);
			$pdf->SetTextColor(0,0,0); 
			
			$pdf->SetTextColor(98,98,98); 							
			$pdf->Cell(150,10,$ticket_id,0,0);
			
			$pdf->SetFontSize(8);
			$pdf->SetTextColor(255,255,255); 
			$pdf->Cell(40,10,$ticket_id,0,1);
			$pdf->SetTextColor(98,98,98); 
			
			$pdf->Ln(5);
			
			$pdf->SetFont('Courier','',13);
			
			$pdf->Cell(50,10,__('PRICE',_DEV_),0,0);
			$pdf->Cell(50,10,__('DATE',_DEV_),0,0);
			$pdf->Cell(50,10,__('TIME',_DEV_),0,0);
			
			$pdf->SetTextColor(255,255,255); 
			$pdf->Cell(25,10,'DATE',0,0);
			$pdf->Cell(15,10,'TIME',0,1);
			
			$pdf->SetTextColor(0,0,0); 
			$pdf->SetFontSize(19);
			$pdf->Cell(50,10,_cart::get_currency_symbol($item['currencyCode']).' '. $item['price'],0,0);
			if(is_array($programm) && isset($programm['start_date'])){
				$start_date = strtotime($programm['start_date']);
					
				$pdf->Cell(50,10,date( 'm/d/Y' , $start_date ),0,0);
			}
			if(is_array($programm) && isset($programm['start_hour'])){
				$pdf->Cell(50,10,$programm['start_hour'],0,0);
			}	
			
			$pdf->SetFontSize(10);
			$pdf->SetTextColor(255,255,255); 
			if(is_array($programm) && isset($programm['start_date'])){
				$start_date = strtotime($programm['start_date']);
				$pdf->Cell(25,10,date( 'm/d/Y' , $start_date ),0,0);
			}
			if(is_array($programm) && isset($programm['start_hour'])){
				$pdf->Cell(25,10,$programm['start_hour'],0,0);
			}
			
			if($output){
				$pdf->Output();	
			}else{
				$pdfcontent = $pdf->Output(WP_CONTENT_DIR . '/uploads/'.$ticket_id.".pdf", "F");
			}	
		}
	}
	
?>