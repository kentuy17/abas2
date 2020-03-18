<!DOCTYPE html>
<html>
<head>
	<title>Cargo Out-Turn Report</title>
</head>
	<style type="text/css">
		.table td,.table th { min-width: 150px;}
	</style>
<body>

<?php


if(!isset($edit)){

	$action_label	=	"Add Statement of Account";
	$action			=	HTTP_PATH."statements_of_account/insert/".$type;

	$created_on=null;
	$reference_number = null;
	$out_turn_id = null;
	$control_number = null;
	$description = null;
	$terms = null;

	$add_tax = 0;
	$wtax_15_percent = 0;
	$vat_12_percent = 0;
	$vat_5_percent = 0;
	$wtax_2_percent = 0;
	$wtax_1_percent = 0;
	//$checked_empty_sacks = 0;
	$checked_tail_end_handling = 0;

	$total_balance = 0;

}else{

	$action_label	=	"Edit Statement of Account";
	$action			=	HTTP_PATH."statements_of_account/update/".$edit['id'];

 	$created_on =  date('Y-m-d', strtotime($edit['created_on']));
	$reference_number = $edit['reference_number'];
	$out_turn_id = $edit['out_turn_summary_id'];
 	$control_number = $edit['control_number'];
 	$description = $edit['description'];
 	$terms =	$edit['terms'];

	$add_tax = $edit['add_tax'];
	$wtax_15_percent = $edit['wtax_15_percent'];
	$vat_12_percent = $edit['vat_12_percent'];
	$vat_5_percent = $edit['vat_5_percent'];
	$wtax_2_percent = $edit['wtax_2_percent'];
	$wtax_1_percent = $edit['wtax_1_percent'];
	//$checked_empty_sacks = $edit['details'][0]['empty_sacks'];
	$checked_tail_end_handling = $edit['details'][0]['tail_end_handling'];

	/*if(isset($edit['details'][0]['empty_sacks'])){
		$checked_empty_sacks = $edit['details'][0]['empty_sacks'];
	}else{
		$checked_empty_sacks =0;
	}*/
	
	if(isset($edit['details'][0]['tail_end_handling'])){
		$checked_tail_end_handling = $edit['details'][0]['tail_end_handling'];
	}else{
		$checked_tail_end_handling =0;
	}

	$services = $edit['services'];

	if($edit['type']=="General"){
		$type = "general";
	}else{
		$type = "out_turn";
	}

	$total_balance = $this->Billing_model->getSOAAmount($edit['type'],$edit['id']);
	$total_balance = number_format($total_balance['grandtotal'],2,'.',',');
}

$company_options=$client_options=$contract_options=$service_options=$transaction_options="";

$transactions = array("Sales","Credit Sales","Transfer","Rebagging/Resacking","Filler","Dispersal","BSP","BBO","BPO","Rice Allowance","Consumer","Inventory","DSWD SFP","Gov't Institution","LGU","Bagsakan",);

foreach($transactions as $transaction){
	$transaction_options .= "<option value='" . $transaction . "'>" . $transaction . "</option>";
}

if(!empty($companies)) {
	foreach($companies as $c) {
		if(isset($edit)){
			$company_options	.=	"<option ".($edit['company']->id==$c->id ? "selected":"")." value='".$c->id."'>".$c->name."</option>";
		}else{
			if($c->name!="Avega Bros. Integrated Shipping Corp. (Staff)"){
				$company_options	.=	"<option value='".$c->id."'>".$c->name."</option>";
			}
		}
	}
	unset($c);
}

if(!empty($contracts)) {
	foreach($contracts as $c) {
		if(isset($edit)){
			$contract_options	.=	"<option ".($edit['contract_id']==$c['id'] ? "selected":"")." value='".$c['id']."'>".$c['reference_no']."</option>";
		}
		else{
			$contract_options	.=	"<option value='".$c['id']."'>".$c['reference_no']."</option>";
		}
	}
	unset($c);
}
if(!empty($clients)) {
	foreach($clients as $c) {
		if(isset($edit)){
			$client_options	.=	"<option ".($edit['client']['id']==$c['id'] ? "selected":"")." value='".$c['id']."'>".$c['company']."</option>";
		}else{
			$client_options	.=	"<option value='".$c['id']."'>".$c['company']."</option>";
		}	
	}
	unset($c);
}
if(!empty($service_contract)) {
	foreach($service_contract as $c) {
		if(isset($edit)){
			$service_options	.=	"<option ".($edit['services']==$c->type ? "selected":"")." value='".$c->type."'>".$c->type."</option>";
		}else{
			$service_options	.=	"<option value='".$c->type."'>".$c->type."</option>";
		}	
	}
	unset($c);
}
	
if($type=="general") {

	$rowfields ="<div class='row item-row command-row'>
					<input type='hidden' min='1' id='sorting[]' name='sorting[]' class='form-control sorting' value='1' style='text-align:center' readonly/>
					<div class='col-sm-3 col-xs-12'>
						<label for='destination'>Warehouse/Port/Destination</label>
						<!--<select id='destination' name='destination' class='form-control md-input dest' onchange='javascript:contractRates(this);'>
							<option value=''>Select (Optional)</option>
						</select>-->
						<input type='text' id='destination[]' name='destination[]' class='form-control  destination' value='' placeholder='Auto-search'/>
						<input type='hidden' id='quantityx[]' name='quantityx[]' class='form-control  qtyx' value=''/>
						<input type='hidden' id='unitx[]' name='unitx[]' class='form-control  unitx' value=''/>
						<input type='hidden' id='ratex[]' name='ratex[]' class='form-control  ratex' value=''/>
					</div>
					<div class='col-sm-8 col-xs-12'>
						<label for='particular[]'>Particular*</label>
						<input type='text' id='particular[]' name='particular[]' class='form-control md-input parti' value='' placeholder='Specific detail of the service'/>
					</div>
					<div class='col-sm-2 col-xs-12'>
						<label for='quantity[]'>Quantity</label>
						<input type='number' max='999999999' id='quantity[]' name='quantity[]' class='form-control md-input qty'/>
					</div>
					<div class='col-sm-2 col-xs-12'>
						<label for='unit_of_measurement[]'>Unit Of Measurement</label>
						<input type='text'id='unit_of_measurement[]' name='unit_of_measurement[]' class='form-control md-input uom' placeholder='Auto-search'/>
					</div>
					<div class='col-sm-2 col-xs-12'>
						<label for='rate[]'>Rate</label>
						<input type='number' min='0.01' step='0.01' max='999999999' id='rate[]' name='rate[]' class='form-control md-input rate'/>
					</div>
					<div class='col-sm-2 col-xs-12'>
						<label for='payment[]'>Payment</label>
						<input type='number' min='0.01' step='0.01' max='999999999' id='payment[]' name='payment[]' class='form-control md-input val1'/>
					</div>
					<div class='col-sm-2 col-xs-12'>
						<label for='charges[]'>Charges</label>
						<input type='number' min='0.01' step='0.01' max='999999999' id='charges[]' name='charges[]' class='form-control md-input val2'/>
					</div>
					<div class='col-sm-2 col-xs-12'>
						<label for='balance[]'>Balance</label>
						<input type='number' min='0.01' step='0.01' max='999999999' id='balance[]' name='balance[]' class='form-control md-input val3' readonly/>
					</div>
					<a class='btn-remove-row btn btn-danger btn-xs col-m-1' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
			   </div><hr>";

	if(isset($edit)){

		$detailform	= "";

		foreach($edit['details'] as $detail){
			$detailform		.= "<div class='row item-row command-row'>
									<input type='hidden' min='1' id='sorting[]' name='sorting[]' class='form-control sorting' style='text-align:center' value='" . $detail['sorting'] . "' readonly/>
									<div class='col-sm-3 col-xs-12'>
										<label for='destination'>Warehouse/Port/Destination</label>
										<!--<select id='destination' name='destination' class='form-control md-input dest' onchange='javascript:contractRates(this);'>
											<option value=''>Select (Optional)</option>
										</select>-->
										<input type='text' id='destination[]' name='destination[]' class='form-control  destination' value='' placeholder='Auto-search'/>
										<input type='hidden' id='quantityx[]' name='quantityx[]' class='form-control  qtyx' value=''/>
										<input type='hidden' id='unitx[]' name='unitx[]' class='form-control  unitx' value=''/>
										<input type='hidden' id='ratex[]' name='ratex[]' class='form-control  ratex' value=''/>
									</div>
									<div class='col-sm-8 col-xs-12'>
										<label for='particular[]'>Particular*</label>
										<input type='text' id='particular[]' name='particular[]'  class='form-control parti' value='" . $detail['particular'] . "' placeholder='Specific details of the service'/>
									</div>
									<div class='col-sm-2 col-xs-12'>
									<label for='quantity[]'>Quantity</label>
									<input type='number' max='999999999' id='quantity[]' name='quantity[]'  class='form-control md-input qty' value='" . $detail['quantity'] . "'/>
									</div>
									<div class='col-sm-2 col-xs-12'>
										<label for='unit_of_measurement[]'>Unit Of Measurement</label>
										<input type='text'id='unit_of_measurement[]' name='unit_of_measurement[]' class='form-control md-input uom' value='".$detail['unit_of_measurement']."' placeholder='Auto-search'/>
									</div>
									<div class='col-sm-2 col-xs-12'>
										<label for='rate[]'>Rate</label>
										<input type='number' min='0.01' step='0.01' max='999999999' id='rate[]' name='rate[]' class='form-control md-input rate' value='" . $detail['rate'] . "'/>
									</div>
									<div class='col-sm-2 col-xs-12'>
										<label for='payment[]'>Payment</label>
										<input type='number' min='0.01' step='0.01' max='999999999' id='payment[]' name='payment[]' class='form-control md-input val1' value='" . $detail['payment'] . "'/>
									</div>
									<div class='col-sm-2 col-xs-12'>
										<label for='charges[]'>Charges</label>
										<input type='number' min='0.01' step='0.01' max='999999999' id='charges[]' name='charges[]' class='form-control md-input val2' value='" . $detail['charges'] . "'/>
									</div>
									<div class='col-sm-2 col-xs-12'>
										<label for='balance[]'>Balance</label>
										<input type='number' min='0.01' step='0.01' max='999999999' id='balance[]' name='balance[]' class='form-control md-input val3' value='" . $detail['balance'] . "'  readonly/>
									</div>
									<div class='col-sm-12 col-xs-12'>
										<hr>
										</div>
									<a class='btn-remove-row btn btn-danger btn-xs col-m-1' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
								</div>";
		}

		$appendable	= trim(preg_replace('/\s+/',' ', $rowfields));
	}
	elseif(!isset($edit)){

		$detailform		=	$rowfields;
		$appendable	= trim(preg_replace('/\s+/',' ', $rowfields));

	}

}elseif($type=="out_turn"){

	/*$rowfields	= "<div class='col-sm-8 col-xs-12'>
						<label for='OS'>Reference Out-Turn Summary*</label>
						<select id='out_turn_summary' name='out_turn_summary' class='form-control OS'>
							<option value=''>Select</option>
						</select>
						<input type='hidden' id='type_of_service' name='type_of_service'>
			   		</div>";*/
	
	$rowfields	= "<div class='col-sm-6 col-xs-12'>
						<label for='OS'>Reference Out-Turn Summary*</label>
						<input type='text' id='out_turn_summary' name='out_turn_summary' class='form-control ui-autocomplete-input' placeholder='Search using OS Transaction Code'>
						<input type='hidden' id='out_turn_id' name='out_turn_id'>
						<input type='hidden' id='type_of_service' name='type_of_service'>
			   		</div>";
		   		
	$rowfields	.= "<div class='col-sm-3 col-xs-6'>
						<center><label>Auto-compute rows?</label></center>
						<input type='checkbox' id='compute' name='compute' class='form-control'>
			   		</div>";

	$rowfields	.= "<div class='col-sm-3 col-xs-6'>
				   		<br>
						<input type='button' value='Load' name='btnLoad' class='btn btn-success btn-s' onclick='javascript: loadDeliveries()' data-toggle='tooltip' title='This will merge and append all records based from selected Out Turn Summary according to their similarity on Warehouse and/or Trucking Co. and/or Transaction.'/>
						<input type='button' value='Clear' name='btnClear' class='btn btn-danger btn-s' onclick='javascript: clearDeliveries()'/>
					</div>
				  	<div class='col-sm-12 col-xs-12'><hr></div>";

	$rowfields	.=	"<div class='row item-row'>
						<div class='col-sm-12 col-xs-12' id='tbl_div'>
								<table id='tbl_summary' name='tbl_summary' data-toggle='table' class='table table-bordered table-striped table-hover'>
								</table>
							</div>
						</div><br>";

	$rowfields	.= "<div class='col-sm-6 col-xs-12' id='on_board_vessel_div'>
						<label for='OS'>On-board on Vessel</label>
						<input type='text' id='on_board_vessel' name='on_board_vessel' class='form-control' placeholder='If applicable only'>
	                </div>
	                <div class='col-sm-6 col-xs-12' id='bol_number_div'>
	                	<label for='OS'>Bill of Lading No.</label>
						<input type='text' id='bol_number' name='bol_number' class='form-control' placeholder='If applicable only'>
	                </div>
	                <div class='col-sm-6 col-xs-12' id='consignee_div'>
	                	<label for='OS'>Consignee</label>
						<input type='text' id='consignee' name='consignee' placeholder='If applicable only' class='form-control'>
	                </div>
	                <div class='col-sm-6 col-xs-12' id='destination_div'>
	                	<label for='OS'>Destination</label>
						<input type='text' id='destination' name='destination' placeholder='If applicable only' class='form-control'>
	                </div>
	                <div class='col-sm-12 col-xs-12' id='cargo_div'>
	                	<label for='OS'>Commodity/Cargo Description</label>
						<input type='text' id='commodity_cargo' name='commodity_cargo' placeholder='If applicable only' class='form-control'>
	                </div>
			        <br><br><br><br><br><br><br><br><br><br><br><hr>";
	

	if(isset($edit)){

		$OS = $this->Operation_model->getOutTurnSummary($edit['details'][0]['out_turn_summary_id']);
		$OS_control_number = "OS No." . $OS->control_number. " | " . $OS->type_of_service . " (Transaction Code No.".$OS->id.")";

		$detailform	= "<div class='col-sm-6 col-xs-12'>
						<label for='OS'>Reference Out-Turn Summary*</label>
						<input type='text' id='out_turn_summary' name='out_turn_summary' class='form-control ui-autocomplete-input' placeholder='Search using OS Transaction Code'>
			   		</div>";

		$detailform	.=	"<input type='hidden' id='out_turn_id' name='out_turn_id' value='".$OS->id."'>
						 <input type='hidden' id='type_of_service' name='type_of_service' value='".$OS->type_of_service."'>
						<div class='row item-row'>";

		$detailform	.= "<div class='col-sm-3 col-xs-3'>
						<center><label>Auto-compute rows?</label></center>
						<input type='checkbox' id='compute' name='compute' class='form-control'>
			   		</div>";

		$detailform	.= "<div class='col-sm-3 col-xs-3'>
				   		<br>
							<input type='button' value='Load' name='btnLoad' class='btn btn-success btn-s' onclick='javascript: loadDeliveries()' data-toggle='tooltip' title='This will merge and append all records based from selected Out Turn Summary according to their similarity on Warehouse and/or Trucking Co. and/or Transaction.'/>
							<input type='button' value='Clear' name='btnClear' class='btn btn-danger btn-s' onclick='javascript: clearDeliveries()'/>
					    </div>
				  		 <div class='col-sm-12 col-xs-12'><hr></div>";

		$detailform	.= 	"<div class='col-sm-12 col-xs-12' id='tbl_div'>
							<table id='tbl_summary' name='tbl_summary' data-toggle='table' class='table table-bordered table-striped table-hover'>";

					if($OS->type_of_service=="Handling"){
							$detailform	.= "<thead><tr>";
							$detailform	.= "<th>Date</th>";
							$detailform	.= "<th>Warehouse</th>";
							$detailform	.= "<th>Quantity</th>";
							$detailform	.= "<th>Net Kilos</th>";
							$detailform	.= "<th>Transaction</th>";
							$detailform	.= "<th>No. of Moves</th>";
							$detailform	.= "<th>Rate*</th>";
							$detailform	.= "<th>AI No.</td>";
							$detailform	.= "<th>Amount</th>";
							$detailform	.= "<th></th>";
							$detailform	.= "</tr></thead>";
							$detailform	.= "<tbody>";
					}elseif($OS->type_of_service=="Trucking"){
							$detailform	.= "<thead><tr>";
							$detailform	.= "<th>Date</td>";
							$detailform	.= "<th>Warehouse</td>";
							$detailform	.= "<th>Trucking Co.</td>";
							$detailform	.= "<th>Quantity</td>";
							$detailform	.= "<th>Weight</td>";
							$detailform	.= "<th>Transaction</td>";
							$detailform	.= "<th>Rate*</td>";
							$detailform	.= "<th>Kilometers*</td>";
							$detailform	.= "<th>AI No.</td>";
							$detailform	.= "<th>Amount</td>";
							$detailform	.= "<th>Compute Empty Sacks?</th>";
							$detailform	.= "<th></th>";
							$detailform	.= "</tr></thead>";
							$detailform	.= "<tbody>";
					}

							foreach($edit['details'] as $detail){

								if($OS->type_of_service=="Handling"){
									$detailform	.= "<tr>";
									$detailform	.= "<td><input type='hidden' class='form-control' id='out_turn_summary_id[]' name='out_turn_summary_id[]' value='".$detail['out_turn_summary_id']."' readonly><input type='date' class='form-control' id='date_of_delivery[]' name='date_of_delivery[]' value='".$detail['date_of_delivery']."' readonly></td>";
									$detailform	.= "<td><input type='text' class='form-control' id='warehouse[]' name='warehouse[]' value='".$detail['warehouse']."' readonly></td>";
									$detailform	.= "<td><input type='number' class='form-control qty' id='quantity[]' name='quantity[]' value='".$detail['quantity']."' readonly></td>";
									$detailform	.= "<td><input type='number' class='form-control wt' id='total_weight[]' name='total_weight[]' value='".$detail['total_weight']."' readonly></td>";
									$detailform	.= "<td><input type='text' class='form-control' id='transaction[]' name='transaction[]' value='".$detail['transaction']."' readonly></td>";
									$detailform	.= "<td><input type='number' class='form-control moves' id='no_of_moves[]' name='no_of_moves[]' value='".$detail['number_of_moves']."' readonly></td>";
									$detailform	.= "<td><input type='number' class='form-control rate' id='rate[]' name='rate[]' value='".$detail['rate']."'></td>";
									$detailform	.= "<td><input type='text' class='form-control ai_number' id='ai_number[]' name='ai_number[]' value='".$detail['authority_to_issue_number']."'></td>";
									$detailform	.= "<td><input type='number' class='form-control amount' id='balance[]' name='balance[]' value='".$detail['amount']."' readonly></td>";
									$detailform	.= "<td><center><a id='remove_row' class='btn btn-danger btn-xs'>×</a></center></td>";
									$detailform	.= "</tr>";
								}
								elseif($OS->type_of_service=="Trucking"){
									$detailform	.= "<tr>";
									$detailform	.= "<td><input type='hidden' class='form-control' id='out_turn_summary_id[]' name='out_turn_summary_id[]' value='".$detail['out_turn_summary_id']."' readonly><input type='date' class='form-control' id='date_of_delivery[]' name='date_of_delivery[]' value='".$detail['date_of_delivery']."' readonly></td>";
									$detailform	.= "<td><input type='text' class='form-control' id='warehouse[]' name='warehouse[]' value='".$detail['warehouse']."' readonly></td>";
									$detailform	.= "<td><input type='text' class='form-control' id='trucking_company[]' name='trucking_company[]' value='".$detail['trucking_company']."' readonly></td>";
									$detailform	.= "<td><input type='number' class='form-control qty' id='quantity[]' name='quantity[]' value='".$detail['quantity']."' readonly></td>";
									$detailform	.= "<td><input type='number' class='form-control wt' id='total_weight[]' name='total_weight[]' value='".$detail['total_weight']."' readonly></td>";
									$detailform	.= "<td><input type='text' class='form-control' id='transaction[]' name='transaction[]' value='".$detail['transaction']."' readonly></td>";
									$detailform	.= "<td><input type='number' class='form-control rate' id='rate[]' name='rate[]' value='".$detail['rate']."'></td>";
									$detailform	.= "<td><input type='number' class='form-control moves' id='no_of_moves[]' name='no_of_moves[]' value='".$detail['number_of_moves']."'></td>";
									$detailform	.= "<td><input type='text' class='form-control ai_number' id='ai_number[]' name='ai_number[]' value='".$detail['authority_to_issue_number']."'></td>";
									$detailform	.= "<td><input type='number' class='form-control amount' id='balance[]' name='balance[]' value='".$detail['amount']."' readonly></td>";
									if($detail['empty_sacks']==1){
										$checked = 'selected';
									}else{
										$checked = '';
									}
									$detailform	.= "<td><select class='form-control' id='empty_sacks[]' name='empty_sacks[]'><option value='0' ".$checked.">No</option><option value='1' ".$checked.">Yes</option></select></td>";
									$detailform	.= "<td><center><a id='remove_row' class='btn btn-danger btn-xs'>×</a></center></td>";
									$detailform	.= "</tr>";
								}
							}
			
		$detailform	.= "</tbody>
						</table>
						</div>
						</div><br><br>";

		if($OS->type_of_service=="Trucking"){


			$detailform	.= "<div class='col-sm-6 col-xs-12' id='on_board_vessel_div'>
								<label for='OS'>On-board on Vessel</label>
								<input type='text' id='on_board_vessel' name='on_board_vessel' class='form-control' placeholder='If applicable only' value='".$edit['details'][0]['on_board_vessel']."'>
			                </div>
			                <div class='col-sm-6 col-xs-12' id='bol_number_div'>
			                	<label for='OS'>Bill of Lading No.</label>
								<input type='text' id='bol_number' name='bol_number' class='form-control' placeholder='If applicable only' value='".$edit['details'][0]['bill_of_lading_number']."'>
			                </div>
			                <div class='col-sm-6 col-xs-12' id='consignee_div'>
			                	<label for='OS'>Consignee</label>
								<input type='text' id='consignee' name='consignee' placeholder='If applicable only' value='".$edit['details'][0]['consignee']."' class='form-control'>
			                </div>
			                <div class='col-sm-6 col-xs-12' id='destination_div'>
			                	<label for='OS'>Destination</label>
								<input type='text' id='destination' name='destination' placeholder='If applicable only' value='".$edit['details'][0]['destination']."' class='form-control'>
			                </div>
			                <div class='col-sm-12 col-xs-12' id='cargo_div'>
			                	<label for='OS'>Commodity/Cargo Description</label>
								<input type='text' id='commodity_cargo' name='commodity_cargo' placeholder='If applicable only' value='".$edit['details'][0]['commodity_cargo']."' class='form-control'>
			                </div>
			                <br><br><br><br><br><br><br><br><br><br><br><hr>";
		}elseif($OS->type_of_service=="Handling"){

			$detailform	.= "<div class='col-sm-6 col-xs-12' id='on_board_vessel_div'>
								<label for='OS'>On-board on Vessel</label>
								<input type='text' id='on_board_vessel' name='on_board_vessel' class='form-control' placeholder='If applicable only' value='".$edit['details'][0]['on_board_vessel']."'>
			                </div>
			                <div class='col-sm-6 col-xs-12' id='bol_number_div'>
			                	<label for='OS'>Bill of Lading No.</label>
								<input type='text' id='bol_number' name='bol_number' class='form-control' placeholder='If applicable only' value='".$edit['details'][0]['bill_of_lading_number']."'>
			                </div>
			                <div class='col-sm-6 col-xs-12' id='consignee_div'>
			                	<label for='OS'>Consignee</label>
								<input type='text' id='consignee' name='consignee' placeholder='If applicable only' value='".$edit['details'][0]['consignee']."' class='form-control'>
			                </div>
			                <div class='col-sm-6 col-xs-12' id='destination_div'>
			                	<label for='OS'>Destination</label>
								<input type='text' id='destination' name='destination' placeholder='If applicable only' value='".$edit['details'][0]['destination']."' class='form-control'>
			                </div>
			                <div class='col-sm-12 col-xs-12' id='cargo_div'>
			                	<label for='OS'>Commodity/Cargo Description</label>
								<input type='text' id='commodity_cargo' name='commodity_cargo' placeholder='If applicable only' value='".$edit['details'][0]['commodity_cargo']."' class='form-control'>
			                </div>
			                <br><br><br><br><br><br><br><br><br><br><br><hr>";
		}	


		$appendable	= trim(preg_replace('/\s+/',' ', $rowfields));
	}
	elseif(!isset($edit)){

		$detailform		=	$rowfields;
		$appendable	= trim(preg_replace('/\s+/',' ', $rowfields));

	}
}
?>


<div class='panel panel-primary'>
	<div class='panel-heading'><h2 class="panel-title"><?php echo $action_label; ?><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2>
	</div>
</div>
	<div id='soaForm' class='panel-body'>
		<form action='<?php echo $action; ?>' role='form' method='POST' id='soa_form' enctype='multipart/form-data'>
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="panel-group" id="soaFormDivider" role="tablist" aria-multiselectable="true">
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="summary">
							
							<a role="button" data-toggle="collapse" data-parent="#soaFormDivider" href="#soaSummary" aria-expanded="true" aria-controls="soaSummary">
							Summary
							<span class="glyphicon glyphicon-chevron-down pull-right"></span>
							</a>
						
					</div>
					<div id="soaSummary" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="summary">
						<div class="panel-body">

							<div class='col-xs-12 col-md-3'>
								<label for='created_on'>Date*</label>
								<input type='date' id='created_on' name='created_on' class='form-control' style='text-align:center' value="<?php echo $created_on;?>"/>
							</div>
					
							<div class='col-xs-12 col-md-9'>
								<label for='company1'>Company*</label>
								<select name='company' id='company1' class='form-control' <?php if(isset($edit)){ echo "disabled";}?>>
									<option value=''>Select</option>
									<?php echo $company_options; ?>
								</select>
							</div>
							<div class='col-xs-12 col-md-3'>
								<label for='contract5'>Contract</label>
								<select name='contract' id='contract5' class='form-control' <?php if(isset($edit)){ echo "disabled";}?>>
									<option value=''>Select</option>
									<?php echo $contract_options; ?>
								</select>
							</div>
							<div class='col-xs-12 col-md-9'>
								<label for='client0'>Client*</label>
								<select name='client' id='client0' class='form-control' <?php if( isset($edit) &&$edit['contract_id']!=0){ echo "disabled";}?>>
									<option value=''>Select</option>
									<?php echo $client_options; ?>
								</select>
							</div>
							<div class='col-xs-12 col-md-3'>
								<label for='terms'>Terms*</label>
								<input type='number' id='terms' name='terms' class='form-control' placeholder='No. of days' value="<?php echo $terms;?>" />
							</div>
							<div class='col-xs-12 col-md-3'>
								<label for='reference_number3'>SOA Reference No.*</label>
								<input type='text' id='reference_number3' name='reference_number' class='form-control' value="<?php echo $reference_number;?>"/>
							</div>

							<div class='col-xs-12 col-md-6'>
							  	<label for='services'>Services*</label>
						      		<select id='services' name='services' class='form-control' <?php if(isset($edit) && $edit['contract_id']!=0){ echo "";}?>>
						      		<option value=''>Select</option>
						      		<?php echo $service_options; ?>
						      		</select>
						    </div>
						
							<?php if($type=="general"){ ?>
								<div class='col-xs-12 col-md-3'>
									<label for='outturn8'>Reference Out-turn Summary</label>
									<input type='text' name='out_turn_summary' id='out_turn_summary' class='form-control ui-autocomplete-input'  value='<?php echo $out_turn_id;?>'>
									<input type='hidden' id='out_turn_id' name='out_turn_id' value='<?php echo $out_turn_id;?>'>
								</div>
								<div class='col-xs-12 col-md-9'>
									<label for='description7'>Description*</label>
									<input type='text' id='description7' name='description' placeholder='This is to bill you for...' class='form-control' value="<?php echo $description;?>"/>
								</div>
							<?php }else{ ?>
								<div class='col-xs-12 col-md-12'>
									<label for='description7'>Description*</label>
									<input type='text' id='description7' name='description' placeholder='This is to bill you for...' class='form-control' value="<?php echo $description;?>"/>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="details">
						
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#soaFormDivider" href="#soaDetails" aria-expanded="false" aria-controls="soaDetails">
							Details
							<span class="glyphicon glyphicon-chevron-down pull-right"></span>
							</a>
							
						
					</div>
					<div id="soaDetails" class="panel-collapse collapse" role="tabpanel" aria-labelledby="details">

						<?php if($type=="general"){?>
							<div class="pull-right" style="float:left; margin-top:5px; margin-left:5px">
								<a id="btn_add_row" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>
								<a id="btn_remove_row" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
							</div>
							<div class="clearfix"><br/></div>
						<?php } ?>
		
						<div class="panel-body item-row-container">
							<?php echo $detailform; ?>
						</div>
						<div class="panel-body">
							
							
							
							<div class='col-sm-8 col-m-8 '>
								<label>Check only if applicable:</label>
								<br>
								<label>&nbsp  Add? &nbsp 
								<input type='checkbox' id='add_tax' name='add_tax' class='form-control' value='1' <?php if($add_tax==1){echo "checked";}?> data-toggle="tooltip" title="Check this if you are adding additional charges ontop of SOA amount and then check applicable percentage(s) on right otherwise, leave this uncheck."></label>
							 
							
								<label>&nbsp 12% &nbsp 
								<input type='checkbox' id='vat_12_percent' name='vat_12_percent' class='form-control' value='1' <?php if($vat_12_percent==1){echo "checked";}?>></label>
							
							
								<label>&nbsp 5% &nbsp &nbsp 
								<input type='checkbox' id='vat_5_percent' name='vat_5_percent' class='form-control' value='1' <?php if($vat_5_percent==1){echo "checked";}?>></label>
							
							
								<label>&nbsp 15% &nbsp 
								<input type='checkbox' id='wtax_15_percent' name='wtax_15_percent' class='form-control' value='1' <?php if($wtax_15_percent==1){echo "checked";}?>></label>
							
						
								<label>&nbsp 2% &nbsp &nbsp
								<input type='checkbox' id='wtax_2_percent' name='wtax_2_percent' class='form-control' value='1' <?php if($wtax_2_percent==1){echo "checked";}?>></label>
							
						
								<label>&nbsp 1% &nbsp &nbsp
								<input type='checkbox' id='wtax_1_percent' name='wtax_1_percent' class='form-control' value='1' <?php if($wtax_1_percent==1){echo "checked";}?>></label>
							
							 <!--<div class='col-sm-3 col-m-3 col-xs-12' id='empty_sacks_div' style='margin-top:-75px; margin-left:408px'>
		                			<label for='OS'>Compute Empty Sacks?
									<input type='checkbox' id='empty_sacks' name='empty_sacks' class='form-control' value='1' <?php //if($checked_empty_sacks==1){echo "checked";}?>></label>
		                	</div>-->
		                	
		                			<label for='OS'>Compute Tail-end Handling?
									<input type='checkbox' id='tail_end_handling' name='tail_end_handling' class='form-control' value='1' <?php if($checked_tail_end_handling==1){echo "checked";}?>></label>
								
		                	</div>
		                	<div class='col-sm-4 col-m-4 pull-right'>
		                		<br><label>Total Balance:</label>
								<input type='text' id='total_balance' name='total_balance' class='form-control' style='text-align:right;font-size:20px;' value='<?php echo $total_balance; ?>' readonly/>
							</div>
		                </div>
					</div>
					
				</div>

			</div>
			<div class='col-xs-12 col-xs-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-xs-12 col-lg-12'>
				<span class="pull-right">
				<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript:checkAutoForm();'/>
					<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
				</span>
			</div>
		</form>
	</div>

</body>
</html>

<script type="text/javascript">
//here
$( "#out_turn_summary" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>statements_of_account/auto_complete_out_turn_summary",
	minLength: 1,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		toastr.clear();
	},
	select: function( event, ui ) {
		$( "#out_turn_summary" ).val( ui.item.label );
		$( "#out_turn_id" ).val( ui.item.id );
		$( "#type_of_service" ).val( ui.item.service );
		return false;
	}
});


$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    
   	var type_of_service = $('#type_of_service').val();

    if(type_of_service=="Trucking" || type_of_service=="Handling"){
    	$('#on_board_vessel_div').show();
    	$('#bol_number_div').show();

    	if(type_of_service=="Handling"){
    		$('#empty_sacks_div').hide();
    		$('#tail_end_handling_div').show();
    	}else{
    		$('#empty_sacks_div').show();
    		$('#tail_end_handling_div').hide();
    	}
    	
    	$('#consignee_div').show();
    	$('#destination_div').show();
    	$('#cargo_div').show();
    }else{
    	$('#on_board_vessel_div').hide();
    	$('#bol_number_div').hide();
    	$('#empty_sacks_div').hide();
    	$('#tail_end_handling_div').hide();
    	$('#consignee_div').hide();
    	$('#destination_div').hide();
    	$('#cargo_div').hide();
    }

});

<?php if(isset($edit)){?>
	$(function () {
		var $table = $('#tbl_summary');
		$table.bootstrapTable();
	});
<?php } ?>

  var contract_rate = null;
  var contract_qty = null;
  var contract_unit = null;

	$("#btn_remove_row").click(function(){
			$('.item-row:last').remove();
		multInputs();
	});
	$(document).on('click', '.btn-remove-row', function() {
		$(this).parent().remove();

		var ctr = 0;
		$("div.command-row").each(function() {
			ctr = ctr + 1;
			$('.sorting', this).val(ctr);
		});

		multInputs();

	});
	$("#btn_add_row").click(function(){
	
		$('.item-row-container').append("<?php echo $appendable; ?>");

		var ctr = 0;
		$("div.command-row").each(function() {
			ctr = ctr + 1;
			$('.sorting', this).val(ctr);

			if(contract_qty){
				//$('.qty',this).val(contract_qty);
				//$('.uom',this).val(contract_unit);
				//$('.rate',this).val(contract_rate);
			}

		});

		var contract_id = $('#contract5').val();

		 $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH;?>statements_of_account/get_contract_rates/"+contract_id,
		     success:function(data){

		        var contract_rates = $.parseJSON(data);   


		        $('.dest',this).find('option').remove().end().append('<option value="">Select (Optional)</option>').val('');

		        for(var i = 0; i < contract_rates.length; i++){
		       		var dest = contract_rates[i];
		       		var option = $('<option />');
				    option.attr('value',dest.warehouse).text(dest.warehouse);
				    $('.dest').append(option);
		        }


		     }

		  });
		
	});



	function contractRates(){

		var contract  = $('#contract5').val();
		var destination = $(event.target).val();

			 $.ajax({
				     type:"POST",
				     url:"<?php echo HTTP_PATH;?>statements_of_account/get_contract_rates/"+contract+"/"+destination,
				     success:function(data){

				     	var contract_details = $.parseJSON(data);

				        var qty = parseFloat(contract_details[0].quantity*1);
				        var unit = contract_details[0].unit;
				        var rate = parseFloat(contract_details[0].rate*1);

				        console.log(contract);
				        console.log(destination);
				        console.log(qty);
				        console.log(unit);
				        console.log(rate);

					    $(event.target).next().next().val(qty);
					    $(event.target).next().next().next().val(unit);
					    $(event.target).next().next().next().next().val(rate);

				      // $('.qty',event.target).val(qty);
				     //  $('.uom',event.target).val(unit);
				     //  $('.rate',event.target).val(rate);

					}
			  	});
 
	}

	$(document).on('keyup', "div.item-row", autoCompleteWH);

	function autoCompleteWH(){

		var contract  = $('#contract5').val();

			$(".destination").autocomplete({
				source: "<?php echo HTTP_PATH;?>statements_of_account/auto_complete_warehouse/"+contract,
				minLength: 2,
				search: function(event, ui){
					toastr['info']('Loading, please wait...');
					toastr.clear();
				},
				response: function(event, ui){
					if (ui.content){
						toastr.clear();
					}
					else{
						toastr["warning"]("Item not found!", "ABAS Says");
						$(this).next().next().val(0);
						$(this).next().next().next().val('');
						$(this).next().next().next().next().val(0);
					}
					return true;
				},
				select: function( event, ui ) {
					$(this).val(ui.item.label);
					$(this).next().val(ui.item.qty);
					$(this).next().next().val(ui.item.unit);
					$(this).next().next().next().val(ui.item.rate);
					return false;
				}
			});
		  		
	}


	// calculates the amount of particulars
	//$(document).ready(function() {

	  $(document).on('change', "div.command-row input", transInput);

	  function transInput() {
	  	 $("div.command-row").each(function() {
	  	 	var destination = $('.destination', this).val();
	 		var x_qty = $('.qtyx', this).val();
	 		var x_unit = $('.unitx', this).val();
	 		var x_rate = $('.ratex', this).val();

	 		if(destination!=""){
				$('.qty', this).val(x_qty);
				$('.uom', this).val(x_unit);
				$('.rate', this).val(x_rate);
	 		}
	  	 });
	  }

	  $(document).on('keyup', "div.command-row input", multInputs);

	  function multInputs() {	 	

	  	var $previous_balance = 0;

	    $("div.command-row").each(function() {

		      var $qty  = $('.qty', this).val();
		      var $rate  = $('.rate', this).val();
		      var $moves  = $('.moves', this).val();
		      var $val1 = $('.val1', this).val();
		      var $val2 = $('.val2', this).val();

		      if($qty != 0 && $rate != 0){
				var $charges = parseFloat((($qty*1)*($rate*1)));
		      	$('.val2', this).val($charges);
		      }

		      if(typeof($moves)!='undefined'){
		      	 var $amount = parseFloat((($qty*1)*($rate*1))*($moves*1));
		      	 $('.amount', this).val($amount);
		      }else{
		      	var $amount = parseFloat((($qty*1)*($rate*1)));
		      	$('.amount', this).val($amount);
		      }
		 

		     if($qty != 0 && $rate != 0){
		     	var $balance = $charges - $val1;
		     }else{
		     	var $balance = $val2 - $val1;
		     }
		      
		      $balance  = $balance + $previous_balance;
		      $('.val3', this).val($balance);

		      $previous_balance = $balance;

		      var total_balance = parseFloat($balance).toFixed(2);

			  document.getElementById("total_balance").value = "PHP "+formatNumber(total_balance);


	    });

	    	
	  }

	  function formatNumber (num) {
		return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	  }

	  
	  $(document).on('keyup', "#tbl_summary input", multInputs2);

	  function multInputs2() {	 	
	    $("tr").each(function() {
	    	  var $wt  = $('.wt', this).val();
		      var $qty  = $('.qty', this).val();
		      var $rate  = $('.rate', this).val();
		      var $moves  = $('.moves', this).val();
		      var $type  = $('#type_of_service').val();
		   	  var $client = $('#client0 option:selected').text();
		 	  var $auto_compute = $('#compute').is(":checked");
		 	  
			      if(typeof($moves)!='undefined'){
			      	if($auto_compute==true){

			      		var mt = ($wt/1000);
				      	var mtNFA = ($wt/50);//for client NFA bags special computation as of Nov/Dec 2017

				      	if($type=="Trucking"){

				      	 	if($client.indexOf('NFA')>-1 || $client.indexOf('National')>-1){
				      	 		var $amount = parseFloat(mtNFA*($rate*1));
				      	 	}else{
				      	 		var $amount = parseFloat(mt*(($rate*1)*($moves*1)));
				      	 	}
				      	 }else if($type=="Handling"){
				      	 	
				      	 	//var $amount = parseFloat((($qty*1)*($rate*1))*($moves*1));

				      	 	if($client.indexOf('NFA')>-1 || $client.indexOf('National')>-1){
				      	 		var $amount = parseFloat(mtNFA*($rate*1)*($moves*1));
				      	 	}else{
				      	 		var $amount = parseFloat(mt*(($rate*1)*($moves*1)));
				      	 	}

				      	 }

			      	 	$('.amount', this).val($amount);
			      	 	$('.amount', this).prop('readonly',true);
			      	 }else{
			      	 	$('.amount', this).prop('readonly',false);
			      	 }
			      }

			 	  var total_balance = 0;
		  		  var inps = document.getElementsByName('balance[]');
				  for (var i = 0; i < inps.length; i++) {
					var inp=inps[i];
				     total_balance = parseFloat((total_balance*1) + (inp.value*1)).toFixed(2);
				  }

			  	document.getElementById("total_balance").value = formatNumber(total_balance);

			
	    });

	    	
	  }

	//});


		
	
	$('#contract5').change(function() 
	{   

      $('#on_board_vessel_div').hide();
	  $('#bol_number_div').hide();
	  $('#empty_sacks_div').hide();
	  $('#tail_end_handling_div').hide();
	  $('#consignee_div').hide();
      $('#destination_div').hide();
      $('#cargo_div').hide();
	  $('#on_board_vessel').val("");
      $('#bol_number').val("");
	  prev_deliveries = [];
	  deliveries = [];

      //Ajax to fill up the contract details
	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH;?>statements_of_account/get_contract_details/"+$(this).val(),
	     success:function(data){

	        var contract_details = $.parseJSON(data);   

	        $("#client0").val(contract_details.client_id);
	        $("#company1").val(contract_details.company_id);
	        //$("#reference_number3").val(contract_details.reference_no);
	        $("#services").val(contract_details.type);
	        $("#terms").val(contract_details.terms_of_payment);

	        contract_qty = contract_details.quantity;
	        contract_unit = contract_details.unit;
	        contract_rate = contract_details.rate;
	        
	     	<?php if($type=='general'){?>
			    //document.getElementById("quantity[]").value = contract_qty;
			    //document.getElementById("unit_of_measurement[]").value = contract_unit;
			    //document.getElementById("rate[]").value = contract_rate;
			 <?php } ?>

		
			 $("#out_turn_id").prop('disabled',false);
			 $('#tbl_summary').bootstrapTable('destroy');
			 $('#total_balance').val(0);

	     }

	  });

	   $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH;?>statements_of_account/get_contract_rates/"+$(this).val(),
	     success:function(data){

	        var contract_rates = $.parseJSON(data);   


	        $('.dest').find('option').remove().end().append('<option value="">Select (Optional)</option>').val('');

	        for(var i = 0; i < contract_rates.length; i++){
	       		var dest = contract_rates[i];
	       		var option = $('<option />');
			    option.attr('value',dest.warehouse).text(dest.warehouse);
			    $('.dest').append(option);
	        }


	     }

	  });

	

	   //Ajax to fill Out Turn Summary dropdown based on company selected
	  /*$.ajax({
	     type:"POST",
	     url:"<?php //echo HTTP_PATH;?>statements_of_account/get_out_turn_summary_by_contract/"+$(this).val(),
	     success:function(data){

	        var OS_control_numbers = $.parseJSON(data);    

	        	$('.OS').find('option').remove().end().append('<option value="">Select</option>').val('');

		        for(var i = 0; i < OS_control_numbers.length; i++){
		       		var OS = OS_control_numbers[i];
		       		var option = $('<option />');
				    option.attr('value',OS.OS_ID).text("OS No."+OS.OS_CN+" | "+OS.type_of_service +" (Transaction Code "+OS.OS_ID+")");
				    $('.OS').append(option);
		        }

	     }

	  });*/

	  $("#client0").prop('disabled',true);
      $("#company1").prop('disabled',true);
      //$("#reference_number3").prop('disabled',true);
      //$("#services").prop('disabled',true);

	});


	 $(document).on('keyup', "div.command-row input", autoComplete);

	  function autoComplete(){

			$(".uom").autocomplete({
				source: "<?php echo HTTP_PATH; ?>statements_of_account/auto_complete_unit_of_measurement",
				minLength: 2,
				search: function(event, ui){
					toastr['info']('Loading, please wait...');
					toastr.clear();
				},
				response: function(event, ui){
					if (ui.content){
						toastr.clear();
					}
					else{
						toastr["warning"]("Item not found!", "ABAS Says");
						$( this ).next().val("");
					}
					return true;
				},
				select: function( event, ui ) {
					$( this ).val( ui.item.label );
					return false;
				}
			});

	 }

    //////////////////////////////////////////

    /*$('.OS').click(function() 
	{   

	  $.ajax({
	     type:"POST",
	     url:"<?php //echo HTTP_PATH;?>statements_of_account/get_out_turn/"+$(this).val(),
	     success:function(data){

	        var summary = $.parseJSON(data);    

	       	$('#type_of_service').val(summary.type_of_service);

	     }

	  });

	});*/

	
var prev_deliveries =[];
var deliveries = [];

function loadDeliveries() {


	  $('#tbl_summary').bootstrapTable('destroy');
	  $('#total_balance').val(0);

	  var type_of_service = $('#type_of_service').val();
	  var out_turn_id 	  = $('#out_turn_id').val();


	  $.ajax({
	     type:"POST",
	     url:"<?php echo HTTP_PATH;?>statements_of_account/get_out_turn_deliveries/"+out_turn_id,
	     success:function(data){

	     	prev_deliveries = deliveries;
	        deliveries = prev_deliveries.concat($.parseJSON(data));   

	        var auto_compute = $('#compute').is(":checked");

	        if(type_of_service=="Handling"){

	          /*$('#on_board_vessel_div').hide();
       		  $('#bol_number_div').hide();
       		  $('#empty_sacks_div').hide();
       		  $('#consignee_div').hide();
    		  $('#destination_div').hide();
    		  $('#cargo_div').hide();*/

			    $('#on_board_vessel_div').show();
	   			$('#bol_number_div').show();
	   			$('#empty_sacks_div').hide();
	   			$('#tail_end_handling_div').show();
	   			$('#consignee_div').show();
			    $('#destination_div').show();
		        $('#cargo_div').show();

		      var tbl_col = [{
	            	  		field: 'operate',
			                title: 'Date',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<input type="hidden" id="out_turn_summary_id[index]" name="out_turn_summary_id[]" value="'+deliveries[index].out_turn_summary_id+'" class="form-control" style="text-align:center" readonly/><input type="date" id="date_of_delivery[index]" name="date_of_delivery[]" value="'+deliveries[index].delivery_date+'" class="form-control" readonly/>';
			                } 
			            },{
			            	field: 'operate',
			                title: 'Warehouse',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<input type="text" id="warehouse[index]" name="warehouse[]" value="'+deliveries[index].warehouse+'" class="form-control" readonly/>';
			                }
			            },{
			            	field: 'operate',
			                title: 'Quantity',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<input type="number" id="quantity[index]" name="quantity[]" value="'+deliveries[index].quantity+'" class="form-control qty" readonly/>';
			                }
			            },{
			            	field: 'operate',
			                title: 'Net Kilos',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<input type="number" id="total_weight[index]" name="total_weight[]" value="'+deliveries[index].total_gross_weight+'" class="form-control wt" readonly/>';
			                }
			            },{
			            	field: 'operate',
			                title: 'Transaction',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<input type="text" id="transaction[index]" name="transaction[]" value="'+deliveries[index].transaction+'" class="form-control" readonly/>';
			                }
			            },{
			                field: 'operate',
			                title: 'No. of Moves',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<input type="number" id="no_of_moves[index]" name="no_of_moves[]" value="'+deliveries[index].number_of_moves+'" class="form-control moves"/>';
			                }  
			            },{
			                field: 'operate',
			                title: 'Rate*',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<input type="number" id="rate[index]" name="rate[]" value="'+deliveries[index].wh_rate+'" class="form-control rate"/>';
			                }  
			            },{
			                field: 'operate',
			                title: 'AI No.',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<input type="text" id="ai_number[index]" name="ai_number[]" class="form-control" value=""/>';
			                }  
				        },{
			                field: 'operate',
			                title: 'Amount',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                	if(auto_compute){
			                		return '<input type="number" id="balance[index]" name="balance[]" class="form-control amount" readonly/>';
			                	}else{
			                		return '<input type="number" id="balance[index]" name="balance[]" class="form-control amount"/>';
			                	}
			                }  
			            },{
			                field: 'operate',
			                title: '',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<a id="remove_row" class="btn btn-danger btn-xs">×</a>';
			                }  
			            }];
		
		    }else if(type_of_service=="Trucking"){

		        $('#on_board_vessel_div').show();
       			$('#bol_number_div').show();
       			$('#empty_sacks_div').show();
       			$('#tail_end_handling_div').hide();
       			$('#consignee_div').show();
    		    $('#destination_div').show();
    	        $('#cargo_div').show();

		        var tbl_col = [{
		            	  		field: 'operate',
				                title: 'Date',
				                align: 'center',
				                valign: 'middle',
				                clickToSelect: false,
				                formatter : function(value,row,index) {
				                   return '<input type="hidden" id="out_turn_summary_id[index]" name="out_turn_summary_id[]" value="'+deliveries[index].out_turn_summary_id+'" class="form-control" style="text-align:center" readonly/><input type="date" id="date_of_delivery[index]" name="date_of_delivery[]" value="'+deliveries[index].delivery_date+'" class="form-control" readonly/>';
				                }
			                },{
				            	field: 'operate',
				                title: 'Warehouse',
				                align: 'center',
				                valign: 'middle',
				                clickToSelect: false,
				                formatter : function(value,row,index) {
				                   return '<input type="text" id="warehouse[index]" name="warehouse[]" value="'+deliveries[index].warehouse+'" class="form-control" readonly/>';
				                }
				            },{
			            	field: 'operate',
			                title: 'Trucking Co.',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<input type="text" id="trucking_company[index]" name="trucking_company[]" value="'+deliveries[index].trucking_company+'" class="form-control" readonly/>';
			                }
				            },{
				            	field: 'operate',
				                title: 'Quantity',
				                align: 'center',
				                valign: 'middle',
				                clickToSelect: false,
				                formatter : function(value,row,index) {
				                   return '<input type="number" id="quantity[index]" name="quantity[]" value="'+deliveries[index].quantity+'" class="form-control qty" readonly/>';
				                }
				            },{
				            	field: 'operate',
				                title: 'Weight',
				                align: 'center',
				                valign: 'middle',
				                clickToSelect: false,
				                formatter : function(value,row,index) {
				                   return '<input type="number" id="total_weight[index]" name="total_weight[]" value="'+deliveries[index].total_net_weight+'" class="form-control wt" readonly/>';
				                }
				            },{
				                field: 'operate',
				                title: 'Transaction',
				                align: 'center',
				                valign: 'middle',
				                clickToSelect: false,
				                formatter : function(value,row,index) {
				                   return '<input type="text" id="transaction[index]" name="transaction[]" value="'+deliveries[index].transaction+'" class="form-control" readonly/>';
				                }  
				            },{
				                field: 'operate',
				                title: 'Rate*',
				                align: 'center',
				                valign: 'middle',
				                clickToSelect: false,
				                formatter : function(value,row,index) {
				                   return '<input type="number" id="rate[index]" name="rate[]" class="form-control rate" value="'+deliveries[index].wh_rate+'"/>';
				                }  
				            },{
				                field: 'operate',
				                title: 'Kilometers*',
				                align: 'center',
				                valign: 'middle',
				                clickToSelect: false,
				                formatter : function(value,row,index) {
				                   return '<input type="number" id="no_of_moves[index]" name="no_of_moves[]" class="form-control moves" value=""/>';
				                }  
				            },{
				                field: 'operate',
				                title: 'AI No.',
				                align: 'center',
				                valign: 'middle',
				                clickToSelect: false,
				                formatter : function(value,row,index) {
				                   return '<input type="text" id="ai_number[index]" name="ai_number[]" class="form-control" value=""/>';
				                }  
				            },{
				                field: 'operate',
				                title: 'Amount',
				                align: 'center',
				                valign: 'middle',
				                clickToSelect: false,
				                formatter : function(value,row,index) {
				                	if(auto_compute){
				                   		return '<input type="number" id="balance[index]" name="balance[]" class="form-control amount" readonly/>';
				                   	}else{
				                   		return '<input type="number" id="balance[index]" name="balance[]" class="form-control amount"/>';
				                   	}
				                }
				            },{
				                field: 'operate',
				                title: 'Compute Empty Sacks?',
				                align: 'center',
				                valign: 'middle',
				                clickToSelect: false,
				                formatter : function(value,row,index) {
				                	return "<select class='form-control' id='empty_sacks[]' name='empty_sacks[]'><option value='0'>No</option><option value='1'>Yes</option></select>";
				                }  
				            },{
			                field: 'operate',
			                title: '',
			                align: 'center',
			                valign: 'middle',
			                clickToSelect: false,
			                formatter : function(value,row,index) {
			                   return '<a id="remove_row" class="btn btn-danger btn-xs">×</a>';
			                }  
			            	}];
			}


			
			

			 $('#tbl_summary').bootstrapTable({data:deliveries,columns:tbl_col});
			 $('#out_turn_summary').val('');	
			 //$('#out_turn_id').val('');
			 //$('#type_of_service').val('');
	     }

	  });

	  return true;
}

	$(document).on('click', "#remove_row", function(){
	     $(this).closest("tr").remove();
	     multInputs2();
	}); 

	 function clearDeliveries(){
	    $('#on_board_vessel_div').hide();
        $('#bol_number_div').hide();
        $('#empty_sacks_div').hide()
        $('#tail_end_handling_div').hide();
        $('#consignee_div').hide();
    	$('#destination_div').hide();
    	$('#cargo_div').hide();
        $('#on_board_vessel').val("");
        $('#bol_number').val("");
	 	$(".OS").prop('disabled',false);
	 	$('#tbl_summary').bootstrapTable('destroy');
	 	$('#tbl_summary').empty();
	 	$('#total_balance').val(0);
	 	prev_deliveries = [];
	 	deliveries = [];
	 	return true;
	 }

	  ////////////////////////////////////////

	function checkAutoForm(){
		$("#btnSubmit").visible=false;
		var msg="";

		var client0=document.forms.soa_form.client0.selectedIndex;
		if (client0==null || client0=="" || client0=="Client") {
			msg+="Client is required! <br/>";
		}
		var company1=document.forms.soa_form.company1.selectedIndex;
		if (company1==null || company1=="") {
			msg+="Company is required! <br/>";
		}
		var reference_number3=document.forms.soa_form.reference_number3.value;
		if (reference_number3==null || reference_number3=="") {
			msg+="SOA Reference No. is required! <br/>";
		}
		var created_on=document.forms.soa_form.created_on.value;
		if (created_on==null || created_on=="") {
			msg+="Date is required! <br/>";
		}
		var terms=document.forms.soa_form.terms.value;
		if (terms==null || terms=="") {
			msg+="Terms is required! <br/>";
		}
		var description7=document.forms.soa_form.description7.value;
		if (description7==null || description7=="") {
			msg+="Description is required! <br/>";
		}
		var services=document.forms.soa_form.services.value;
		if (services==null || services=="") {
			msg+="Services is required! <br/>";
		}
		var out_turn_id=document.forms.soa_form.out_turn_id.value;
		if(out_turn_id==null || out_turn_id==""){
			//if(services!='Trucking' && services!='Handling' && services!='Towing' && services!='Rental' && services!='Equipment Rental' && services!='Fabrication' && services!='Storage'){
			//	msg+="Out-turn Reference No. is required for this type of service! <br/>";
			//}
		}
		var total_balance=document.getElementById('total_balance').value;
		if (total_balance<=0) {
			msg+="Total Balance must not be equal or less than zero! <br/>";
		}
			
			var particulars = document.getElementsByName('particular[]');
			var charges = document.getElementsByName('charges[]');
			var balances = document.getElementsByName('balance[]');

			var warehouse = document.getElementsByName('warehouse[]');
			var trucking_company = document.getElementsByName('trucking_company[]');
			var rate = document.getElementsByName('rate[]');
			var moves = document.getElementsByName('moves[]');
			var vessel = document.getElementsByName('vessel[]');
			var transaction = document.getElementsByName('transaction[]');
			var new_import = document.getElementsByName('new_import[]');
	                 
	        var flag=0;           
	        for(var i = 0; i < particulars.length; i++){         
	            if (particulars[i].value==""){flag=1;} 
	        }
	        if(flag==1){msg+="All Particular fields are required! <br/>";}

		    var divs = document.getElementsByClassName('item-row');
		    var flag=0;
		    if (divs.length == 0){flag=1;}
		    if(flag==1){msg+="Please make sure to provide at least one SOA detail.<br/>";}

	        var flag=0;           
	        for(var i = 0; i < balances.length; i++){         
	            if (balances[i].value==0){flag=1;} 
	        }
	        if(flag==1){msg+="Please check the amount in balances. It must not be equal to zero!<br/>";}

	        <?php if($type=="out_turn"){?>

				var flag=0;
		        for(var i = 0; i < warehouse.length; i++){         
		            if (warehouse[i].value==""){flag=1;} 
		        }
		        if(flag==1){msg+="All Warehouse fields are required! <br/>";}

		       	var flag=0;
		        for(var i = 0; i < trucking_company.length; i++){         
		            if (trucking_company[i].value==""){flag=1;} 
		        }
		        if(flag==1){msg+="All Trucking Co. fields are required! <br/>";}

		       	var flag=0;
		        for(var i = 0; i < rate.length; i++){         
		            if (rate[i].value==0){flag=1;} 
		        }
		        if(flag==1){msg+="All Rate fields are required! <br/>";}

		        var flag=0;
		        for(var i = 0; i < moves.length; i++){         
		            if (moves[i].value==0){flag=1;} 
		        }
		        if(flag==1){msg+="All Moves fields are required! <br/>";}

		        var flag=0;
		        for(var i = 0; i < transaction.length; i++){         
		            if (transaction[i].value==0){flag=1;} 
		        }
		        if(flag==1){msg+="All Transaction fields are required! <br/>";}
		    
			    var rowCount = $('#tbl_summary tr').length;
			    var flag1=0;
			    if(rowCount==0){flag1=1;}
			    if(flag1==1){msg+="Please make sure to load the required Out-Turn Summary.<br/>";}

			<?php } ?>

		if(msg!="") {
			$("#btnSubmit").visible=true;
			toastr["error"](msg,"ABAS Says:");
			return false;
		}
		else{

		  $("#client0").prop('disabled',false);
		  $("#contract5").prop('disabled',false);
	      $("#company1").prop('disabled',false);
	      //$("#reference_number3").prop('disabled',false);
	      $("#services").prop('disabled',false);
	      $(".OS").prop('disabled',false);

		  $("#btnSubmit").visible=true;

		  $('body').addClass('is-loading'); 
		  $('#modalDialog').modal('toggle'); 

		  document.getElementById("soa_form").submit();
		  return true;

		}
	}
</script>