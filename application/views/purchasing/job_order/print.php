<?php
	$table	=	"";
	$totalcost	=	0;
	$jo_supplier			=	$this->Abas->getSupplier($jo->supplier_id);
	$jo_company				=	(array)$this->Abas->getCompany($jo->company_id);
	$request				=	$this->Purchasing_model->getRequest($jo->request_id);
	foreach($jo_details as $ctr=>$row) {
		$item	=	$this->Inventory_model->getItem($row->item_id);
		$item	=	$item[0];
		$sql = "SELECT * FROM inventory_request_details WHERE request_id=".$request['id']." AND item_id=".$row->item_id." AND unit_price is null";
		$query = $this->db->query($sql);
		$service_description = $query->row(); 
		$table	.=	'<tr style="text-align:center;">';
			$table	.= '<td width="10%">'.$row->quantity.'</td>';
			$table	.= '<td width="10%">'.$item['unit'].'</td>';
			$table	.= '<td width="50%" align="left">'.$item['item_name'].', '.$item['brand'].$item['particular']." - ".$service_description->remark.'</td>';
			$table	.= '<td width="15%" style="text-align:right;">P'.number_format($row->unit_price,2).'</td>';
			$table	.= '<td width="15%" style="text-align:right;">P'.number_format($row->unit_price*$row->quantity,2).'</td>';
		$table	.=	'</tr>';
		$totalcost	=	$totalcost+($row->unit_price*$row->quantity);
	}
	$added_by				=	$this->Abas->getUser($jo->added_by);
	if(empty($added_by['full_name'])) {	$added_by['full_name']	=	"N/A <h1 style='color:#FF0000;'>This PO is NOT VALID</h1>"; }
	if(empty($added_by['signature'])) {
		$added_by['signature']	=	'';
	}
	if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$added_by['signature'])) {
		$added_by['signature']	=	'';
	}
	else {
		if($added_by['signature']!="") {
			$added_by['signature']	=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/'.$added_by['signature'].'" width="200px" align="absmiddle" />';
		}
	}
	$approved_by					=	$this->Abas->getUser($jo->approved_by['id']);
	if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$approved_by['signature'])) {
		$approved_by['signature']	=	"";
	}
	else {
		if($approved_by['signature']!="") {
			$approved_by['signature']	=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/'.$approved_by['signature'].'" width="200px" align="absmiddle" />';
		}
	}
	if(!empty($request['details'])) {
		foreach($request['details'] as $request_detail) {
			$noted_by					=	$this->Abas->getUser($request_detail['request_approved_by']);
			if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$noted_by['signature'])) {
				$noted_by['signature']	=	"";
			}
			else {
				if($noted_by['signature']!="") {
					$noted_by['signature']	=	'<img src="'.PDF_LINK.'assets/images/digitalsignatures/'.$noted_by['signature'].'" width="200px" align="absmiddle" />';
				}
			}
			// force no value
			$noted_by					=	array("full_name"=>"", "signature"=>"");
		}
	}
	$user	=	$this->Abas->getUser($_SESSION['abas_login']['userid']);
	$gross_purchases			=	0;
	$vat						=	0;
	$etax						=	0;
	$vatable_purchases			=	$totalcost;
	$grand_total				=	$totalcost;
	if(strtolower($user['user_location'])!="makati") {
		$noted_by['full_name']	=	"Que, Johnson";
		$noted_by['signature']	=	"";
	}
	if($jo_supplier['issues_reciepts']==1) {
		$gross_purchases		=	$totalcost-$jo->discount;
		if(strtolower($jo_supplier['vat_computation'])=='vatable') {
			$vat				=	($totalcost-($totalcost/1.12));
			$vatable_purchases	=	$totalcost-$vat;
		}
		$etax					=	($vatable_purchases*($jo->extended_tax/100));
		$etax_percentage		=	0;
		if($jo->extended_tax>0) {
			$etax_percentage	=	$jo->extended_tax;
			$grand_total		=	$totalcost-$jo->extended_tax;
		}
		else {
			$grand_total		=	$gross_purchases-$etax-$jo->discount;
		}
	}
	$grand_total		=	$gross_purchases-$etax-$jo->discount;
	$table	.=	'<tr style="text-align:right;">';
	$table	.=	'<td colspan="4">Gross Purchases</td>';
	$table	.=	'<td>P'.number_format($gross_purchases,2).'</td>';
	$table	.=	'</tr>';
	$table	.=	'<tr style="text-align:right;">';
	$table	.=	'<td colspan="4">VATable Purchases</td>';
	$table	.=	'<td>P'.number_format($vatable_purchases,2).'</td>';
	$table	.=	'</tr>';
	$table	.=	'<tr style="text-align:right;">';
	$table	.=	'<td colspan="4">12% VAT</td>';
	$table	.=	'<td>P'.number_format($vat,2).'</td>';
	$table	.=	'</tr>';
	$table	.=	'<tr style="text-align:right;">';
	$table	.=	'<td colspan="4">Withholding Tax - Expanded ('.$etax_percentage.'%)</td>';
	$table	.=	'<td>(P'.number_format($etax,2).')</td>';
	$table	.=	'</tr>';
	$table	.=	'<tr style="text-align:right;">';
	$table	.=	'<td colspan="4">Amount Payable</td>';
	$table	.=	'<td>P'.number_format($grand_total,2).'</td>';
	$table	.=	'</tr>';
	$content=	'
	<style>
		div {
			font-size:12px;
		}
		table {
			margin:10px;
			font-size:10px;
		}
		.table-label {
			background-color:#000000;
			color:#FFFFFF;
			text-align:center;
		}
		#signature{
			float:left;
			margin-left:250px;
			margin-top:-250px
		}
		#signature_title{
			float:left;
			margin-left:250px;
			margin-top:-250px
		}
	</style>
	<div style="text-align:center;">
		<p style="font-size:10px">
		<div style="font-size:20px; font-weight:600"><strong>'.$jo_company['name'].'</strong></div>
		'.$jo_company['address'].'<br>
			Tel. Number: '.$jo_company['telephone_no'].' Fax Number: '.$jo_company['fax_no'].'<br>
			TIN: '.$jo_company['company_tin'].'
		</p>
		<div style="font-size:18px; font-weight:600">Job Order</div>
	</div>
	<div>
		<table width="100%">
			<tr>
				<td style="text-align:right;">Vendor: </td>
				<td>'.$jo_supplier['name'].'</td>
				<td style="text-align:right;">Date: </td>
				<td>'.date("j F Y", strtotime($jo->tdate)).'</td>
			</tr>
			<tr>
				<td style="text-align:right;">Attention: </td>
				<td>'.$jo_supplier['contact_person'].'</td>
				<td style="text-align:right;">JO Number: </td>
				<td>'.$jo->control_number.'</td>
			</tr>
			<tr>
				<td style="text-align:right;">Fax #: </td>
				<td>'.$jo_supplier['fax_no'].'</td>
				<td style="text-align:right;">Request #: </td>
				<td>'.$request['control_number'].'</td>
			</tr>
			<tr>
				<td style="text-align:right;">Tel. #: </td>
				<td>'.$jo_supplier['telephone_no'].'</td>
				<td style="text-align:right;">VAT Type:</td>
				<td>'.$jo_supplier['vat_computation'].'</td>
			</tr>
			<tr>
				<td style="text-align:right;">Address: </td>
				<td>'.$jo_supplier['address'].'</td>
				<td style="text-align:right;">TIN: </td>
				<td>'.$jo_supplier['tin'].'</td>
			</tr>
		</table>
	</div>
	<div style="clear:both;"><br/></div>
	<table border="1" cellpadding="5">
		<thead>
			<tr>
				<th class="table-label" width="10%">Quantity</th>
				<th class="table-label" width="10%">Unit</th>
				<th class="table-label" width="50%">Description</th>
				<th class="table-label" width="15%">Unit Price</th>
				<th class="table-label" width="15%">Total Price</th>
			</tr>
		</thead>
		<tbody>
			'.$table.'
		</tbody>
	</table>
	<br><br>
	<div style="font-size:12px">Request Transaction code: '.$request['id'].'</div>
	<div style="font-size:12px">Job Order Transaction code: '.$jo->id.'</div>
	<div style="font-size:12px">Terms: '.($jo_supplier['payment_terms']==0?"":$jo_supplier['payment_terms']).'</div>
	<div style="font-size:12px">For: '.$request['vessel_name'].'</div>
	<div style="font-size:12px">Remark: '.$jo->remark.'</div>
	<br><br>
	<table id="signature_title">
		<tr>
			<td>Prepared by:</td>
			<td>Noted by:</td>
			<td>Approved by:</td>
		</tr>
	</table>
	<table id="signature">
		<tr>
			<td>'.$added_by['signature'].'</td>
			<td>'.$noted_by['signature'].'</td>
			<td>'.$approved_by['signature'].'</td>
		</tr>
		<tr style="text-align:center;">
			<td>'.$added_by['full_name'].'</td>
			<td>'.$noted_by['full_name'].'</td>
			<td>'.$approved_by['full_name'].'</td>
		</tr>
	</table>
	';
$data['orientation']		=	"P";
$data['pagetype']			=	"letter";
$data['title']				=	"Job Order #" . $jo->control_number;
$data['content']			=	$content;
$this->load->view('pdf-container.php',$data);
?>
