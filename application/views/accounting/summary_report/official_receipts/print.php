<?php 

		$content = "<style type='text/css'>
						 h1 { font-size:270%;text-align:center; }
						 h2 { font-size:210%;text-align:center; }	
						 h3 { font-size:150%;text-align:left; }
						 h5 { border-bottom: double 3px; }
						 td {font-size:130%;text-align:center}
						 th { font-weight:bold;font-size:150%;text-align:center}
					</style>";

		$table_data = "<style type='text/css'>
						 h1 { font-size:200%;text-align:center; }
						 h2 { font-size:150%;text-align:center; }	
						 h3 { font-size:100%;text-align:center; }
						 h5 { border-bottom: double 3px; }
						 td {font-size:130%;}
						 th { font-weight:bold;font-size:150%;text-align:center}
					</style>";

		$content .= "<br><h1>Official Receipts Summary Report</h1><br>";

		if($company!="" || $company!=NULL){
			$company_name = $this->Abas->getCompany($company)->name;
			$content .= "<h2>".$company_name."</h2><br>";
		}else{
			$content .= "<h2>Avega Group of Companies</h2><br>";
		}

		if($date_from!="" || $date_from!=NULL){
			$content .= "<h3>Date Range: ".date('M. d, Y',strtotime($date_from))." to ".date('M. d, Y',strtotime($date_to))."</h3><hr><br><br>";
		}


		$content .=	"<div><table border=\"1\" cellpadding=\"3px\">
					 <thead>
						<tr>
							<th style=\"width:40px\">#</th>
							<th style=\"width:40px\">OR No.</th>
							<th style=\"width:180px\">Company</th>
							<th style=\"width:160px\">Payor</th>
							<th style=\"width:200px\">Particulars</th>
							<th>Mode of Collection</th>
							<th>Issued On</th>
							<th style=\"width:130px\">Issued By</th>
							<th>Amount</th>
						</tr>
					</thead>";

		
		$content .=	"<tbody>";

						$total_amount = 0;
						$ctr=1;
						if(isset($receipts)){
							foreach($receipts as $row){
								$content .= "<tr>";
									$content .= "<td style=\"width:40px\">".$ctr."</td>";
									$content .= "<td style=\"width:40px\">".$row->or_num."</td>";
									$content .= "<td style=\"width:180px\">".$row->company."</td>";
									$content .= "<td style=\"width:160px\">".$row->payor."</td>";
									$content .= "<td style=\"width:200px;text-align:left\">".$row->particulars."</td>";
									$content .= "<td>".$row->mode_of_collection."</td>";
									$content .= "<td>".date('Y-M-d',strtotime($row->issued_date))."</td>";
									$content .= "<td style=\"width:130px\">".$row->issued_by."</td>";
									$content .= "<td style=\"text-align:right\">".number_format($row->net_amount,2,".",",")." </td>";
								$content .= "</tr>";
								$ctr++;
								$total_amount = $total_amount + $row->net_amount;
							}
						}
						$content .= "<tr><td colspan=\"8\" style=\"text-align:right\">Total Amount:</td>";
						$content .= "<td style=\"text-align:right\"><b>".number_format($total_amount,2,".",",")."</b></td></tr>";
					
		$content .=	"</tbody>
		             </table></div>";			

			

		$data['orientation']		=	"L";
		$data['pagetype']			=	"legal";
		$data['title']				=	"Official Receipts Summary Report";
		$data['content']			=	$content ;
	
		$this->load->view('pdf-container.php',$data);

?>