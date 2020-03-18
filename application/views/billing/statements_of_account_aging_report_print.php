<?php

$content = "<style type='text/css'>
				 h1 { font-size:250%;text-align:center; }
				 h2 { font-size:150%;text-align:center; }	
				 h3 { font-size:100%;text-align:center; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:130%;}
				 th { font-weight:bold;font-size:150%;text-align:center}
			</style>";

$content .="<br><h1>Statement of Account Aging Report</h1><br>
			<h2>As of ".date('F j, Y')."</h2><br><hr><br><br>";

if($date_from!=""){
	$content .="<h2 style=\"text-align:left;\">Date Range: ".date("M-d-Y",strtotime($date_from))." to ".date("M-d-Y",strtotime($date_to))."</h2><br>";	
}

if($company_id!=""){
	$company	=	$this->Abas->getCompany($company_id);
	$content .="<h2 style=\"text-align:left;\">Company: ".$company->name."</h2><br>";
}

if($client_id!=""){
	$client		=	$this->Abas->getClient($client_id);
	$content .="<h2 style=\"text-align:left;\">Client: ".$client['company']."</h2><br>";
}

$content .= "<table border=\"1\" cellpadding=\"3\">
			<thead>
				<tr>
					<th style=\"width:30px;\">#</th>
					<th style=\"width:40px;\">SOA No.</th>
					<th style=\"width:60px;\">Ref. No.</th>
					<th style=\"width:100px;\">Company</th>
					<th style=\"width:100px;\">Client</th>
					<th>Received by Client</th>
					<th>Due Date</th>
					<th>Aging</th>
					<th>SOA Amount</th>
					<th>Current</th>
					<th>1 to 30 Days</th>
					<th>31 to 60 Days</th>
					<th>61 to 120 Days</th>
					<th>Over 120 Days</th>
					<th>Total</th>
				</tr>
			</thead>
				<tbody>";

				if(isset($rows)){
					$ctr=1;
					$total_soa_amount=0;
					$total_current=0;
					$total_1_30=0;
					$total_31_60=0;
					$total_61_120=0;
					$total_over_120=0;
					$total_aging_amount=0;
					foreach($rows as $row){
						$content .= "<tr>";
							$content .= "<td style=\"text-align:center;width:30px\">".$ctr."</td>";
							$content .= "<td style=\"text-align:center;width:40px\">".$row['control_number']."</td>";
							$content .= "<td style=\"text-align:center;width:60px\">".$row['reference_number']."</td>";
							$content .= "<td style=\"width:100px;\">".$row['company_name']."</td>";
							$content .= "<td style=\"width:100px;\">".$row['client_name']."</td>";
							$content .= "<td>".$row['date']."</td>";
							$content .= "<td>".$row['due']."</td>";
							$content .= "<td style=\"text-align:center;\">".$row['aging']."</td>";
							$content .= "<td style=\"text-align:right;\">".$row['total_amount']."</td>";
							$content .= "<td style=\"text-align:right;\">".$row['current']."</td>";
							$content .= "<td style=\"text-align:right;\">".$row['one_to_thirty_days']."</td>";
							$content .= "<td style=\"text-align:right;\">".$row['thirty_one_to_sixty_days']."</td>";
							$content .= "<td style=\"text-align:right;\">".$row['sixty_one_to_one_hundred_twenty_days']."</td>";
							$content .= "<td style=\"text-align:right;\">".$row['over_one_hundred_twenty_days']."</td>";
							$content .= "<td style=\"text-align:right;\">".$row['total_aging_amount']."</td>";
						$content .= "</tr>";
						$ctr++;
						$total_soa_amount=$total_soa_amount+str_replace( ',', '',$row['total_amount']);
						$total_current=$total_current+str_replace( ',', '',$row['current']);
						$total_1_30=$total_1_30+str_replace( ',', '',$row['one_to_thirty_days']);
						$total_31_60=$total_31_60+str_replace( ',', '',$row['thirty_one_to_sixty_days']);
						$total_61_120=$total_61_120+str_replace( ',', '',$row['sixty_one_to_one_hundred_twenty_days']);
						$total_over_120=$total_over_120+str_replace( ',', '',$row['over_one_hundred_twenty_days']);
						$total_aging_amount=$total_aging_amount+str_replace( ',', '',$row['total_aging_amount']);
					}
						$content .= "<tr>";
							$content .= "<td colspan=\"8\" style=\"text-align:right;font-weight:bold;\">PHP</td>";
							$content .= "<td style=\"text-align:right;font-weight:bold;\">".number_format($total_soa_amount,2,'.',',')."</td>";
							$content .= "<td style=\"text-align:right;font-weight:bold;\">".number_format($total_current,2,'.',',')."</td>";
							$content .= "<td style=\"text-align:right;font-weight:bold;\">".number_format($total_1_30,2,'.',',')."</td>";
							$content .= "<td style=\"text-align:right;font-weight:bold;\">".number_format($total_31_60,2,'.',',')."</td>";
							$content .= "<td style=\"text-align:right;font-weight:bold;\">".number_format($total_61_120,2,'.',',')."</td>";
							$content .= "<td style=\"text-align:right;font-weight:bold;\">".number_format($total_over_120,2,'.',',')."</td>";
							$content .= "<td style=\"text-align:right;font-weight:bold;\">".number_format($total_aging_amount,2,'.',',')."</td>";
						$content .= "</tr>";
				}else{
					$content .= "<tr>";
						$content .= "<td colspan=\"16\"><center>No Records Found.</center></td>";
					$content .= "</tr>";
				}
					
$content .=	"</tbody>
			</table>";


	$data['orientation']		=	"L";
	$data['pagetype']			=	"legal";
	$data['title']				=	"Statement of Account Aging Report as of ". date('F j, Y');
	$data['content'] = $content;
	$this->load->view('pdf-container.php',$data);

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php //echo $content;?>
</body>
</html>>

