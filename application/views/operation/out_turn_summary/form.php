<?php

 $trucking_header = "<th class='text-center' style='width:10px'>Trip #</th>
                    <th class='text-center'>Date*</th>
                    <th class='text-center'>Truck Plate No.*</th>
                    <th class='text-center'>Name of Truck Driver*</th>
                    <th class='text-center'>Trucking Co.*</th>
                    <th class='text-center'>Warehouse/Consignee*</th>
                    <th class='text-center'>Item Description*</th>
                    <th class='text-center'>Quantity*</th>
                    <th class='text-center'>Gross Wt.*</th>
                    <th class='text-center'>Tare Wt.*</th>
                    <th class='text-center'>Net Wt.*</th>
                    <th class='text-center'>Transaction*</th>
                    <th class='text-center'>DR No.*</th>
                    <th class='text-center'>WT No.*</th>
                    <th class='text-center'>WIF No.*</th>
                    <th class='text-center'>WRF No.*</th>
                    <th class='text-center'>WB No.*</th>
                    <th class='text-center'>ATL No.*</th>
                    <th class='text-center'>CR No.*</th>
                    <th class='text-center'>Others Docs</th>";

 $handling_header = "<th class='text-center' style='width:10px'>#</th>
                    <th class='text-center'>Date*</th>
                    <th class='text-center'>Warehouse*</th>
                    <th class='text-center'>Quantity*</th>
                    <th class='text-center'>Weight.*</th>
                    <th class='text-center'>No. of Moves*</th>
                    <th class='text-center'>Variety*</th>
                    <th class='text-center'>Transaction*</th>
                    <th class='text-center'>WIF No.*</th>
                    <th class='text-center'>WRF No.*</th>
                    <th class='text-center'>Other Docs</th>";

$trucking_row = "
					<td>
						<input type='hidden' id='sorting[]' name='sorting[]' class='form-control sorting'/>
			        	<input type='date' id='delivery_date[]' name='delivery_date[]' placeholder='date' class='form-control ddate' required/>
			        </td>
			        <td>
			        	<input type='text' id='truck_plate_number[]' name='truck_plate_number[]' placeholder='Plate No.' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='truck_driver[]' name='truck_driver[]' placeholder='Driver' class='form-control' required/>
			        </td>
			        <td>
			        	<input type='text' id='trucking_company[]' name='trucking_company[]' placeholder='Trucking Co.' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='warehouse[]' name='warehouse[]' placeholder='Warehouse' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='variety_item[]' name='variety_item[]' placeholder='Item Desc.' class='form-control' required/>
			        </td>
			         <td>
			        	<input type='number' id='quantity[]' name='quantity[]' placeholder='Quantity' class='form-control' required>
			        </td>
			        <td>
			        	<input type='number' id='gross_weight[]' name='gross_weight[]' placeholder='Gross Wt.' class='form-control gross_weight' required>
			        </td>
			        <td>
			        	<input type='number' id='tare_weight[]' name='tare_weight[]' placeholder='Tare Wt.' class='form-control tare_weight' required>
			        </td>
			        <td>
			        	<input type='number' id='net_weight[]' name='net_weight[]' placeholder='Net Wt.' class='form-control net_weight' readonly>
			        </td>
			        <td>
			        	<input type='text' id='transaction[]' name='transaction[]' placeholder='Transaction' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='dr_number[]' name='dr_number[]' placeholder='DR No.' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='wt_number[]' name='wt_number[]' placeholder='WT No.' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='wif_number[]' name='wif_number[]' placeholder='WIF No.' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='wrf_number[]' name='wrf_number[]' placeholder='WRF No.' class='form-control' required>
			        </td>
			         <td>
			        	<input type='text' id='wb_number[]' name='wb_number[]' placeholder='WB No.' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='atl_number[]' name='atl_number[]' placeholder='ATL No.' class='form-control' required>
			        </td>
			         <td>
			        	<input type='text' id='cr_number[]' name='cr_number[]' placeholder='CR No.' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='others[]' name='others[]' placeholder='Other Docs' class='form-control otherss'>
			        </td>";

$handling_row = "
					<td>
						<input type='hidden' id='sorting[]' name='sorting[]' class='form-control sorting'/>
			        	<input type='date' id='delivery_date[]' name='delivery_date[]' placeholder='date' class='form-control ddate' required/>
			        </td>
			        <td>
			        	<input type='text' id='warehouse[]' name='warehouse[]' placeholder='Warehouse/Consignee' class='form-control' required>
			        </td>
			        <td>
			        	<input type='number' id='quantity[]' name='quantity[]' placeholder='Quantity' class='form-control' required>
			        </td>
			        <td>
			        	<input type='number' id='gross_weight[]' name='gross_weight[]' placeholder='Weight' class='form-control gross_weight' required>
			        </td>
			        <td>
			        	<input type='number' id='number_of_moves[]' name='number_of_moves[]' placeholder='No. of Moves' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='variety_item[]' name='variety_item[]' placeholder='Variety' class='form-control' required/>
			        </td>
			        <td>
			        	<input type='text' id='transaction[]' name='transaction[]' placeholder='Transaction' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='wif_number[]' name='wif_number[]' placeholder='WIF No.' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='wrf_number[]' name='wrf_number[]' placeholder='WRF No.' class='form-control' required>
			        </td>
			        <td>
			        	<input type='text' id='others[]' name='others[]' placeholder='Other Docs' class='form-control otherss'>
			        </td>";

$appendable_trucking_header	= trim(preg_replace('/\s+/',' ', $trucking_header));
$appendable_handling_header	= trim(preg_replace('/\s+/',' ', $handling_header));
$appendable_trucking_row	= trim(preg_replace('/\s+/',' ', $trucking_row));
$appendable_handling_row	= trim(preg_replace('/\s+/',' ', $handling_row));

$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $option) {
		if(!isset($OS)){
			$company_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
		}
		elseif(isset($OS)){
			$company_options	.=	"<option ".($OS->company_id==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
		}
	}
	unset($option);
}

$company = "";
$service = "";
$reference_no = "";
$service_order_type = "";
$service_order_options = "<option value=''>Select</option>";
$contract_options = "<option value=''>Select</option>";
$out_turn_summary_options = "<option value=''>Select</option>";
$date_needed = "";
$client = "";
$status = "";
$bill_of_lading = "";
$qty_bol = "";
$weight_bol = "";
$cargo_dec = "";
$local = "";
$vessel_id="";
$mother_vessel = "";
$voyage_no = "";
$port_origin = "";
$port_destination = "";
$shipper = "";
$consignee = "";
$surveyor = "";
$arrastre = "";
$loading_arrival = "";
$loading_start = "";
$loading_ended = "";
$loading_departure = "";
$loading_quantity_volume = "";
$unloading_arrival = "";
$unloading_start = "";
$unloading_ended = "";
$unloading_departure = "";
$unloading_quantity_volume = "";
$shipper_bags = "";
$shipper_weight = "";
$consignee_bags = "";
$consignee_weight="";
$variance_bags = "";
$variance_weight="";
$percentage_bags = "";
$percentage_weight="";
$good = "";
$damaged="";
$total="";
$remarks="";
$lighterage_receipt_no = "";
$soa_id = "";
$trip_ticket_no = "";
$statement_of_facts_no = "";
$barge_patron = "";
$loading_batch_weight = "";
$unloading_batch_weight = "";

$type_of_service_OS="";
$client_OS="";

$SO_radio = "checked='checked'";
$OS_radio = "";

$a1="";
$a2="";
$a3="";
$a4="";
$a5="";
$a6="";
$a7="";
$a8="";
$a9="";
$a10="";
$a11="";
$a12="";
$a13="";
$a14="";
$a15="";
$a16="";
$a17="";
$a18="";
$a19="";
$a20="";
$a21="";
$others="";

if(!isset($OS)){

	$title = "Add Out-Turn Summary";


}elseif(isset($OS)){

	$title = "Edit Out-Turn Summary";

	$company_id = $OS->company_id;
	$service_order = "";
	$service_order_type = $OS->type_of_service;
	$remarks = $OS->remarks;
	$reference_no = $contract['reference_no'];
	$client = $contract['client']['company'];

	if(isset($SO)){
		$date_needed = $SO->date_needed;
		$status = $SO->status;
		$service_order_options = "<option value='".$SO->id."' selected>SO No. ".$SO->control_number." | ".$SO->type." (Transaction Code ".$SO->id.")</option>";
		$SO_radio = "checked='checked'";
		$OS_radio = "";
	}else{
		$SO_radio = "";
		$OS_radio = "checked='checked'";
	}

	$contract_options = "<option value=''>Select</option>";
	if(!empty($contracts)) {
		foreach($contracts as $option) {
			$contract_options	.=	"<option ".($OS->service_contract_id==$option['id'] ? "selected":"")." value='".$option['id']."'>".$option['reference_no']."</option>";
		}
		unset($option);
	}

	if($OS->service_contract_id!=0){
		$contract = $this->Abas->getContract($OS->service_contract_id);
		$type_of_service_OS=$contract['type'];
		$client_OS=$contract['client']['company'];
	}

	if(isset($OS)){
		$out_turn_summary_options = "<option value='".$OS->id."' selected>OS No. ".$OS->control_number." | ". $OS->type_of_service." (".$OS->company_name." - Transaction Code ".$OS->id.")</option>";
	}

	if($service_order_type=='Trucking'){
		$local = "N/A";
		$port_origin = isset($SO_Details)?$SO_Details->from_location:"";
		$port_destination = isset($SO_Details)?$SO_Details->to_location:"";
		$cargo_dec = isset($SO_Details)?$SO_Details->cargo_description:"";
	}elseif($service_order_type=='Handling'){
		$local = "N/A";
		$cargo_dec = isset($SO_Details)?$SO_Details->cargo_description:"";
		$port_origin = "N/A";
		$port_destination = "N/A";
	}elseif($service_order_type=='Shipping'){
		$bill_of_lading = $OS_Details->bill_of_lading_number;
		$qty_bol = $OS_Details->quantity_per_bill_of_lading;
		$weight_bol = $OS_Details->weight_per_bill_of_lading;
		$cargo_dec = $SO_Details->cargo_description;
		$local = $OS_Details->vessel_name;
		$vessel_id = $OS_Details->vessel_id;
		$mother_vessel = $OS_Details->mother_vessel;
		$voyage_no = $OS_Details->voyage_number;
		$port_origin = $OS_Details->port_of_origin;
		$port_destination = $OS_Details->port_of_destination;
		$shipper = $OS_Details->shipper;
		$consignee = $OS_Details->consignee;
		$surveyor = $OS_Details->surveyor;
		$arrastre = $OS_Details->arrastre;
		$loading_arrival = $OS_Details->loading_arrival;
		$loading_start = new DateTime($OS_Details->loading_start);
		$loading_start = $loading_start->format('Y-m-d\TH:i');
		$loading_ended = new DateTime($OS_Details->loading_ended);
		$loading_ended = $loading_ended->format('Y-m-d\TH:i');
		$loading_departure = $OS_Details->loading_departure;
		$loading_quantity_volume = $OS_Details->loading_quantity_volume;
		$unloading_arrival = $OS_Details->unloading_arrival;
		$unloading_start = new DateTime($OS_Details->unloading_start);
		$unloading_start = $unloading_start->format('Y-m-d\TH:i');
		$unloading_ended = new DateTime($OS_Details->unloading_ended);
		$unloading_ended = $unloading_ended->format('Y-m-d\TH:i');
		$unloading_departure = $OS_Details->unloading_departure;
		$unloading_quantity_volume = $OS_Details->unloading_quantity_volume;

		foreach($OS_Attachments as $attachment){
			switch($attachment['document_name']){
				case "Out-Turn Report - Shipper":
					$a1=TRUE;
				break;
				case "Out-Turn Report - Consignee":
					$a2=TRUE;
				break;
				case "Out-Turn Report - Surveyor":
					$a3=TRUE;
				break;
				case "Bill of Lading":
					$a4=TRUE;
				break;
				case "Notice of Readiness - Loading":
					$a5=TRUE;
				break;
				case "Notice of Completion - Loading":
					$a6=TRUE;
				break;
				case "Notice of Readiness - Unloading":
					$a7=TRUE;
				break;
				case "Notice of Completion - Unloading":
					$a8=TRUE;
				break;
				case "Statement of Facts - Loading":
					$a9=TRUE;
				break;
				case "Statement of Facts - Unloading":
					$a10=TRUE;
				break;
				case "Mates Receipt":
					$a11=TRUE;
				break;
				case "Mate's Receipt":
					$a11=TRUE;
				break;
				case "Trip Ticket(s)":
					$a12=TRUE;
				break;
				case "Certificate of Cargo - Delivery Receipt (CCR)":
					$a13=TRUE;
				break;
				case "Complete Discharge Report and Unloading Certificate":
					$a14=TRUE;
				break;
				case "Letter of Protest(s)":
					$a15=TRUE;
				break;
				case "Certificate of Cargo Hold Inspection":
					$a17=TRUE;
				break;
				case "Copy of Fixture Note or Contract":
					$a18=TRUE;
				break;
				case "Incident Report":
					$a19=TRUE;
				break;
				case "Sealing Plan":
					$a20=TRUE;
				break;
				case "Stowage Plan":
					$a21=TRUE;
				break;
			}
			if(preg_match('/Others:/',$attachment['document_name'])){
				$a16=TRUE;
				$others=substr($attachment['document_name'],7);
			}
		}

		$shipper_bags = $OS_Output->shipper_number_of_bags;
		$shipper_weight = $OS_Output->shipper_weight;
		$consignee_bags = $OS_Output->consignee_number_of_bags;
		$consignee_weight= $OS_Output->consignee_weight;
		$variance_bags = $OS_Output->variance_number_of_bags;
		$variance_weight= $OS_Output->variance_weight;
		$percentage_bags = $OS_Output->percentage_number_of_bags . "%";
		$percentage_weight= $OS_Output->percentage_weight . "%";
		$good = $OS_Output->good_number_of_bags;
		$damaged= $OS_Output->damaged_number_of_bags;
		$total= $OS_Output->total_number_of_bags;

	}elseif($service_order_type=="Lighterage" || $service_order_type=="Time Charter" || $service_order_type=="Towing"){
		$cargo_dec = $SO_Details->cargo_description;
		$local = $OS_Details->vessel_name;
		$vessel_id = $OS_Details->vessel_id;
		$mother_vessel = $OS_Details->mother_vessel;
		$voyage_no = $OS_Details->voyage_number;
		$port_origin = $OS_Details->port_of_origin;
		$port_destination = $OS_Details->port_of_destination;
		$shipper = $OS_Details->shipper;
		$consignee = $OS_Details->consignee;
		$loading_arrival = $OS_Details->loading_arrival;
		$loading_start = new DateTime($OS_Details->loading_start);
		$loading_start = $loading_start->format('Y-m-d\TH:i');
		$loading_ended = new DateTime($OS_Details->loading_ended);
		$loading_ended = $loading_ended->format('Y-m-d\TH:i');
		$loading_departure = $OS_Details->loading_departure;
		$loading_quantity_volume = $OS_Details->loading_quantity_volume;
		$unloading_arrival = $OS_Details->unloading_arrival;
		$unloading_start = new DateTime($OS_Details->unloading_start);
		$unloading_start = $unloading_start->format('Y-m-d\TH:i');
		$unloading_ended = new DateTime($OS_Details->unloading_ended);
		$unloading_ended = $unloading_ended->format('Y-m-d\TH:i');
		$unloading_departure = $OS_Details->unloading_departure;
		$unloading_quantity_volume = $OS_Details->unloading_quantity_volume;
		$lighterage_receipt_no = $OS_Details->lighterage_receipt_number;
		$trip_ticket_no = $OS_Details->trip_ticket_number;
		$statement_of_facts_no = $OS_Details->statement_of_facts_number;
		$barge_patron = $OS_Details->barge_patron;
		$loading_batch_weight = $OS_Details->loading_batch_weight;
		$unloading_batch_weight = $OS_Details->unloading_batch_weight;
		if($service_order_type=="Time Charter"){
			$soa_id = $OS->soa_id;
		}
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Out-turn Summary</title>
</head>
	<style type="text/css">
		.table td,.table th { min-width: 150px;}
	</style>
<body>

<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text><?php echo $title?></text>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
</div>

<?php
	// CI Form
	$attributes = array('id'=>'out_turn_summary_form','role'=>'form');
	if(!isset($OS)){
		echo form_open_multipart(HTTP_PATH.'operation/out_turn_summary/insert',$attributes);
	}else{
		echo form_open_multipart(HTTP_PATH.'operation/out_turn_summary/update/'.$OS->id,$attributes);
	}
	echo $this->Mmm->createCSRF();
?>

<div class='panel-body panel'>
	<div class='panel-group' id='OSFormDivider' role='tablist' aria-multiselectable='true'>

		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='general'>
				<a role='button' data-toggle='collapse' data-parent='#OSFormDivider' href='#OSGeneral' aria-expanded='true' aria-controls='OSGeneral'>
				General Information
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>

			<div id='OSGeneral' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='OSGeneral'>
				<div class='panel-body'>

					<div class='col-md-7 col-sm-7 col-xs-12'>
						<label>Company*</label>
						 <select type='text' id='company' name='company' class='form-control' required <?php if(isset($OS)){echo 'readonly';}?>>
								<?php echo $company_options;?>
						</select>
					</div>


                     <div class='col-md-5 col-sm-5 col-xs-12'>
                        <label>Basis*</label>
							<br>
                            <input type="radio" id='SO_radio' name='SO_radio' value='SO' onclick="javascript:basis('SO');" <?php echo $SO_radio;?>> From Service Order &nbsp&nbsp
                            <input type="radio" id='OS_radio' name='OS_radio' value='OS' onclick="javascript:basis('OS');" <?php echo $OS_radio;?>> Copy Out-Turn Summary
                    </div>

					<div class='col-md-12 col-sm-12 col-xs-12' id='service_order_div'>
						<label>Service Order No.*</label>
						<select id='service_order' name='service_order' class='form-control' <?php if(isset($OS)){echo 'readonly';}?>>
							<?php echo $service_order_options;?>
						</select>
					</div>


					<div class='col-md-12 col-sm-12 col-xs-12' id='out_turn_summary_div'>
						<label>Out-Turn Summary No.*</label>
						<select id='out_turn_summary' name='out_turn_summary' class='form-control' <?php if(isset($OS)){echo 'readonly';}?>>
							<?php echo $out_turn_summary_options;?>
						</select>
					</div>

					<div id='service_order_details_div'>
						<div class='col-md-12 col-sm-12 col-xs-12'>
							<hr>
						</div>
						<div class='col-md-3 col-sm-3 col-xs-12'>
							<label>Contract Ref. No.</label>
							<input type='text' id='reference_no' name='reference_no' class='form-control' value='<?php echo $reference_no?>' readonly>
						</div>

						<div class='col-md-3 col-sm-3 col-xs-12'>
							<label>Type of Service</label>
							<input type='text' id='service_order_type' name='service_order_type' class='form-control' value='<?php echo $service_order_type?>' readonly>
						</div>

						<div class='col-md-3 col-sm-3 col-xs-12'>
							<label>Date Served</label>
							<input type='text' id='date_needed' name='date_needed' class='form-control' value='<?php echo $date_needed?>' readonly>
						</div>

						<div class='col-md-3 col-sm-3 col-xs-12'>
							<label>Status</label>
							<input type='text' id='status' name='status' class='form-control' value='<?php echo $status?>' readonly>
						</div>

						<div class='col-md-6 col-sm-6 col-xs-12' id='client_div'>
							<label>Client</label>
							<input type='text' id='client' name='client' class='form-control' value='<?php echo $client?>' readonly>
						</div>

						<div class='col-md-6 col-sm-6 col-xs-12' id='local_div'>
							<label>Vessel</label>
							<input type='text' id='local' name='local' class='form-control' value='<?php echo $local?>' readonly>
							<input type='hidden' id='vessel_id' name='vessel_id' class='form-control' value='<?php echo $vessel_id?>' readonly>
						</div>

						<div class='col-md-6 col-sm-6 col-xs-12' id='port_origin_div'>
							<label>Port/Point of Origin</label>
							<input type='text' id='port_origin' name='port_origin' value='<?php echo $port_origin?>' class='form-control' readonly>
						</div>

						<div class='col-md-6 col-sm-6 col-xs-12' id='port_destination_div'>
							<label>Port/Point of Discharge</label>
							<input type='text' id='port_destination' name='port_destination' value='<?php echo $port_destination?>' class='form-control' readonly>
						</div>

						<div class='col-md-12 col-sm-12 col-xs-12' id='cargo_description_div'>
							<label>Cargo Description</label>
							<input type='text' id='cargo_description' name='cargo_description' class='form-control' value='<?php echo $cargo_dec?>' readonly>
						</div>
					</div>

					<div id='out_turn_summary_details_div'>
						<div class='col-md-12 col-sm-12 col-xs-12'>
							<hr>
						</div>
						<div class='col-md-3 col-sm-3 col-xs-12'>
							<label>Link on Contract Ref. No.*</label>
							<select id='reference_no_OS' name='reference_no_OS' class='form-control'>
								<?php echo $contract_options;?>
							</select>
						</div>
						<div class='col-md-3 col-sm-3 col-xs-12'>
							<label>Type of Service</label>
							<input type='text' id='type_of_service_OS' name='type_of_service_OS' class='form-control' value='<?php echo $type_of_service_OS?>' readonly>
						</div>
						<div class='col-md-6 col-sm-6 col-xs-12' id='client_div'>
							<label>Client</label>
							<input type='text' id='client_OS' name='client_OS' class='form-control' value='<?php echo $client_OS?>' readonly>
						</div>
					</div>

				</div>
			</div>

		</div>

		<div class='panel panel-info' id='panel_details'>
			<div class='panel-heading' role='tab' id='details'>
				<a role='button' data-toggle='collapse' data-parent='#OSFormDivider' href='#OSDetails' aria-expanded='true' aria-controls='OSDetails'>
				Details
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>

			<div id='OSDetails' class='panel-collapse collapse' role='tabpanel' aria-labelledby='OSDetails'>
				<div class='panel-body'>

					<div class='col-md-3 col-sm-3 col-xs-12' id='soa_div'>
						<label>SOA Transaction Code*</label>
						<input type='text' id='soa_id' name='soa_id' class='form-control' value='<?php echo $soa_id?>' required <?php if(isset($OS)){ echo 'readonly';} ?>>
					</div>

					<div class='col-md-3 col-sm-3 col-xs-12' id='lighterage_receipt_no_div'>
						<label>Lighter Receipt No.*</label>
						<input type='text' id='lighterage_receipt_no' name='lighterage_receipt_no' class='form-control' value='<?php echo $lighterage_receipt_no?>' required>
					</div>

					<div class='col-md-3 col-sm-3 col-xs-12' id='trip_ticket_no_div'>
						<label>Trip Ticket No.*</label>
						<input type='text' id='trip_ticket_no' name='trip_ticket_no' class='form-control' value='<?php echo $trip_ticket_no?>' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='contract_reference_div'>
						<label>Contract Ref. No.</label>
						<input type='text' id='contract_reference_no' name='contract_reference_no' class='form-control' readonly>
					</div>

					<div class='col-md-3 col-sm-3 col-xs-12' id='statement_of_facts_no_div'>
						<label id='reference_label'>Statement of Facts Ref.*</label>
						<input type='text' id='statement_of_facts_no' name='statement_of_facts_no' class='form-control' value='<?php echo $statement_of_facts_no?>' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='bol_number_div'>
						<label>Bill of Lading No.*</label>
						<input type='number' id='bol_number' name='bol_number' class='form-control' value='<?php echo $bill_of_lading?>' required>
					</div>

					<div class='col-md-4 col-sm-2 col-xs-12' id='qty_bol_div'>
						<label>Quantity/Volume per BOL*</label>
						<input type='number' id='qty_bol' name='qty_bol' class='form-control' value='<?php echo $qty_bol?>' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='weight_bol_div'>
						<label>Total Cargo Weight per BOL*</label>
						<input type='number' id='weight_bol' name='weight_bol' class='form-control' value='<?php echo $weight_bol?>' required>
					</div>

					<div class='col-md-12 col-sm-12 col-xs-12' id='barge_patron_div'>
						<label id='patron_label'>Barge Patron*</label>
						<input type='text' id='barge_patron' name='barge_patron' value='<?php echo $barge_patron?>' class='form-control' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='voyage_number_div'>
						<label>Voyage No.*</label>
						<input type='number' id='voyage_number' name='voyage_number' value='<?php echo $voyage_no?>' class='form-control' required>
					</div>

					<div class='col-md-8 col-sm-8 col-xs-12' id='mother_vessel_div'>
						<label id='mother_label'>Mother Vessel</label>
						<input type='text' id='mother_vessel' name='mother_vessel' value='<?php echo $mother_vessel?>' class='form-control'>
					</div>

					<div class='col-md-6 col-sm-6 col-xs-12' id='shipper_div'>
						<label id='From'>Shipper*</label>
						<input type='text' id='shipper' name='shipper' value='<?php echo $shipper?>' class='form-control' required>
					</div>

					<div class='col-md-6 col-sm-6 col-xs-12' id='consignee_div'>
						<label id='To'>Consignee*</label>
						<input type='text' id='consignee' name='consignee' value='<?php echo $consignee?>' class='form-control' required>
					</div>

					<div class='col-md-6 col-sm-6 col-xs-12' id='surveyor_div'>
						<label>Surveyor*</label>
						<input type='text' id='surveyor' name='surveyor' value='<?php echo $surveyor?>' class='form-control' required>
					</div>


					<div class='col-md-6 col-sm-6 col-xs-12' id='arrastre_div'>
						<label>Arrastre*</label>
						<input type='text' id='arrastre' name='arrastre' value='<?php echo $arrastre?>' class='form-control' required>
					</div>

					<div class='col-md-12 col-sm-12 col-xs-12'>
						<hr>
						<label id='loading_label'>Loading:</label>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='loading_arrival_date_div'>
						<label>Date of Arrival*</label>
						<input type='date' id='loading_arrival_date' name='loading_arrival_date' value='<?php echo $loading_arrival?>' class='form-control' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='loading_start_datetime_div'>
						<label>Date and Time Started*</label>
						<input type='datetime-local' id='loading_start_datetime' name='loading_start_datetime' value='<?php echo $loading_start?>' class='form-control' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='loading_ended_datetime_div'>
						<label>Date and Time Ended*</label>
						<input type='datetime-local' id='loading_ended_datetime' name='loading_ended_datetime' value='<?php echo $loading_ended ?>' class='form-control' onchange='javascript:checkDates(1);' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='loading_departure_date_div'>
						<label>Date of Departure*</label>
						<input type='date' id='loading_departure_date' name='loading_departure_date' value='<?php echo $loading_departure?>' class='form-control' required>
					</div>

					<div class='col-md-8 col-sm-8 col-xs-12' id='departure_loc'>
						<label>Location</label>
						<input type='text' id='departure_location' name='departure_location' class='form-control'>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='loading_quantity_volume_div'>
						<label>Quantity/Volume*</label>
						<input type='number' id='loading_quantity_volume' name='loading_quantity_volume' value='<?php echo $loading_quantity_volume?>' class='form-control' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='loading_batch_weight_div'>
						<label>Batch Weight*</label>
						<input type='number' id='loading_batch_weight' name='loading_batch_weight' value='<?php echo $loading_batch_weight?>' class='form-control' required>
					</div>

					<div class='col-md-12 col-sm-12 col-xs-12'>
						<hr>
						<label id='unloading_label'>Unloading:</label>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='unloading_arrival_date_div'>
						<label>Date of Arrival*</label>
						<input type='date' id='unloading_arrival_date' name='unloading_arrival_date' value='<?php echo $unloading_arrival?>' class='form-control' required>
					</div>			

					<div class='col-md-4 col-sm-4 col-xs-12' id='unloading_start_datetime_div'>
						<label>Date and Time Started*</label>
						<input type='datetime-local' id='unloading_start_datetime' name='unloading_start_datetime' onchange='javascript:checkDates(2);' value='<?php echo $unloading_start?>' class='form-control' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='unloading_ended_datetime_div'>
						<label>Date and Time Ended*</label>
						<input type='datetime-local' id='unloading_ended_datetime' name='unloading_ended_datetime'  onchange='javascript:checkDates(3);' value='<?php echo $unloading_ended?>' class='form-control' required>
					</div>

					<div class='col-md-8 col-sm-8 col-xs-12' id='arrival_loc'>
						<label>Location</label>
						<input type='text' id='arrival_location' name='arrival_location' class='form-control'>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='unloading_departure_date_div'>
						<label>Date of Departure*</label>
						<input type='date' id='unloading_departure_date' name='unloading_departure_date' value='<?php echo $unloading_departure?>' class='form-control' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='unloading_quantity_volume_div'>
						<label>Quantity/Volume*</label>
						<input type='number' id='unloading_quantity_volume' name='unloading_quantity_volume' value='<?php echo $unloading_quantity_volume?>' class='form-control' required>
					</div>

					<div class='col-md-4 col-sm-4 col-xs-12' id='unloading_batch_weight_div'>
						<label>Batch Weight*</label>
						<input type='number' id='unloading_batch_weight' name='unloading_batch_weight' value='<?php echo $unloading_batch_weight?>' class='form-control' required>
					</div>

				</div>
			</div>
		</div>

		<div class='panel panel-info' id='panel_deliveries'>
			<div class='panel-heading' role='tab' id='breakdown'>
				<a role='button' data-toggle='collapse' data-parent='#OSFormDivider' href='#OSBreakdown' aria-expanded='true' aria-controls='OSBreakdown'>
				Breakdown of Deliveries
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>

			<div id='OSBreakdown' class='panel-collapse collapse' role='tabpanel' aria-labelledby='OSBreakdown'>

				<div class='panel-body'>

					<div class='col-sm-12 col-xs-8'>
						<table border='0'>
								<tr>
									<td><label>Template:</label></td>
									<td style='width:200px'>&nbsp&nbsp
									<button>
									    <a href=<?php echo HTTP_PATH."../assets/downloads/operations/out_turn_summary/template.ods"?> download>Download</a>
									</button>
									</td>
									<td style='width:180px'><label>Upload using spreadsheet file:</label></td>
									<td style='width:380px'><input type='file' id='uploaded_file' name='uploaded_file' accept='.xls,.xlsx,.ods'></td>
								</tr>
						</table>
					</div>

					<div class='col-sm-12 col-xs-12'  id='breakdown_table'>
						<hr>
						<label>Or by manual encoding:</label>
			            <table id="table_breakdown" data-toggle="table" class="table table-bordered table-striped table-hover">
			                <thead>
			                <?php
			                	if(!isset($OS)){
			                    	echo "<tr id='cargo_header'></tr>";
			                    }else{
			                    	echo "<tr id='cargo_header'>";
					                    	if($OS->type_of_service=='Trucking'){
					            				echo  $trucking_header;
					            			}
					            			elseif($OS->type_of_service=='Handling'){
					            				echo $handling_header;
					            			}
			                    	echo "</tr>";
			                    }
			                ?>
			                </thead>
			                <tbody>
			                    <?php
				                    if(!isset($OS_Deliveries)){
				                    	echo "<tr id='cargo_row0' class='tbl_row'></tr>";
									}else{
										$ctr=0;
										foreach($OS_Deliveries as $delivery){


											echo "<tr id='cargo_row".$ctr."' class='tbl_row'>";
											echo "<td>".($ctr+1)."</td>";

											if($OS->type_of_service=='Trucking'){
					            				echo "
												<td>
													<input type='hidden' id='sorting[]' name='sorting[]' value='".$delivery['sorting']."' class='form-control sorting'/>
										        	<input type='date' id='delivery_date[]' name='delivery_date[]' placeholder='date' value='".$delivery['delivery_date']."' class='form-control ddate' required/>
										        </td>
										        <td>
										        	<input type='text' id='truck_plate_number[]' name='truck_plate_number[]' placeholder='Plate No.' value='".$delivery['truck_plate_number']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='truck_driver[]' name='truck_driver[]' placeholder='Driver' value='".$delivery['truck_driver']."' class='form-control' required/>
										        </td>
										        <td>
										        	<input type='text' id='trucking_company[]' name='trucking_company[]' placeholder='Trucking Co.' value='".$delivery['trucking_company']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='warehouse[]' name='warehouse[]' placeholder='Warehouse/Consignee' value='".$delivery['warehouse']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='variety_item[]' name='variety_item[]' placeholder='Item Desc.' value='".$delivery['variety_item']."' class='form-control' required/>
										        </td>
										         <td>
										        	<input type='number' id='quantity[]' name='quantity[]' placeholder='Quantity' value='".$delivery['quantity']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='number' id='gross_weight[]' name='gross_weight[]' placeholder='Gross Wt.' value='".$delivery['gross_weight']."' class='form-control gross_weight' required>
										        </td>
										        <td>
										        	<input type='number' id='tare_weight[]' name='tare_weight[]' placeholder='Tare Wt.' value='".$delivery['tare_weight']."' class='form-control tare_weight' required>
										        </td>
										        <td>
										        	<input type='number' id='net_weight[]' name='net_weight[]' placeholder='Net Wt.' value='".$delivery['net_weight']."' class='form-control net_weight' readonly>
										        </td>
										        <td>
										        	<input type='text' id='transaction[]' name='transaction[]' placeholder='Transaction' value='".$delivery['transaction']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='dr_number[]' name='dr_number[]' placeholder='DR No.' value='".$delivery['delivery_receipt_number']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='wt_number[]' name='wt_number[]' placeholder='WT No.' value='".$delivery['weighing_ticket_number']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='wif_number[]' name='wif_number[]' placeholder='WIF No.' value='".$delivery['warehouse_issuance_form_number']."' class='form-control' required>
										        </td>
										         <td>
										        	<input type='text' id='wrf_number[]' name='wrf_number[]' placeholder='WRF No.' value='".$delivery['warehouse_receipt_form_number']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='wb_number[]' name='wb_number[]' placeholder='WB No.' value='".$delivery['way_bill_number']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='atl_number[]' name='atl_number[]' placeholder='ATL No.' value='".$delivery['authority_to_load_number']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='cr_number[]' name='cr_number[]' placeholder='CR No.' value='".$delivery['cargo_receipt_number']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='others[]' name='others[]' placeholder='Others' value='".$delivery['others']."' class='form-control otherss'>
										        </td>";

					            			}
					            			elseif($OS->type_of_service=='Handling'){
					            				echo "
												<td>
													<input type='hidden' id='sorting[]' name='sorting[]' value='".$delivery['sorting']."' class='form-control sorting'/>
										        	<input type='date' id='delivery_date[]' name='delivery_date[]' placeholder='date' value='".$delivery['delivery_date']."' class='form-control ddate' required/>
										        </td>
										        <td>
										        	<input type='text' id='warehouse[]' name='warehouse[]' placeholder='Warehouse' value='".$delivery['warehouse']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='number' id='quantity[]' name='quantity[]' placeholder='Quantity' value='".$delivery['quantity']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='number' id='gross_weight[]' name='gross_weight[]' placeholder='Weight' value='".$delivery['gross_weight']."' class='form-control gross_weight' required>
										        </td>
										        <td>
										        	<input type='number' id='number_of_moves[]' name='number_of_moves[]' placeholder='No. of Moves' value='".$delivery['number_of_moves']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='variety_item[]' name='variety_item[]' placeholder='Variety' value='".$delivery['variety_item']."' class='form-control' required/>
										        </td>
										        <td>
										        	<input type='text' id='transaction[]' name='transaction[]' placeholder='Transaction' value='".$delivery['transaction']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='wif_number[]' name='wif_number[]' placeholder='WIF No.' value='".$delivery['warehouse_issuance_form_number']."' class='form-control' required>
										        </td>
										         <td>
										        	<input type='text' id='wrf_number[]' name='wrf_number[]' placeholder='WRF No.' value='".$delivery['warehouse_receipt_form_number']."' class='form-control' required>
										        </td>
										        <td>
										        	<input type='text' id='others[]' name='others[]' placeholder='Others' value='".$delivery['others']."' class='form-control otherss'>
										        </td>";
					            			}
					            			$ctr++;
											echo "</tr>";
										}

									}
			                    ?>
			                </tbody>
			            </table>

			      		<div style='float:left; margin-top:5px; margin-left:5px' class='pull-right'>
							<a id='add_row' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>
							<a id='delete_row' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
						</div>

					</div>
				</div>
			</div>

		</div>

		<div class='panel panel-info' id='panel_attachments'>
			<div class='panel-heading' role='tab' id='attachments'>
				<a role='button' data-toggle='collapse' data-parent='#OSFormDivider' href='#OSAttachments' aria-expanded='true' aria-controls='OSAttachments'>
				Attachments
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>

			<div id='OSAttachments' class='panel-collapse collapse' role='tabpanel' aria-labelledby='OSAttachments'>
				<div class='panel-body'>
					<div class='col-sm-12 col-xs-12'>
						<label>Please check only the documents that you have received:</label>
						<br>
					</div>
					<div class='col-sm-6 col-xs-12'>
						<input type='checkbox' id='out_turn_report_shipper' name='attachments[]' value='Out-Turn Report - Shipper' <?php if($a1){echo "checked";}?>> Out-Turn Report - Shipper
						<br>
						<input type='checkbox' id='out_turn_report_consignee' name='attachments[]' value='Out-Turn Report - Consignee' <?php if($a2){echo "checked";}?>> Out-Turn Report - Consignee
						<br>
						<input type='checkbox' id='out_turn_report_surveyor' name='attachments[]' value='Out-Turn Report - Surveyor' <?php if($a3){echo "checked";}?>> Out-Turn Report - Surveyor
						<br>
						<input type='checkbox' id='bill_of_lading' name='attachments[]' value='Bill of Lading' <?php if($a4){echo "checked";}?>> Bill of Lading
						<br>
						<input type='checkbox' id='notice_of_readiness_loading' name='attachments[]' value='Notice of Readiness - Loading' <?php if($a5){echo "checked";}?>> Notice of Readiness - Loading
						<br>
						<input type='checkbox' id='notice_of_completion_loading' name='attachments[]' value='Notice of Completion - Loading' <?php if($a6){echo "checked";}?>> Notice of Completion - Loading
						<br>
						<input type='checkbox' id='notice_of_readiness_unloading' name='attachments[]' value='Notice of Readiness - Unloading' <?php if($a7){echo "checked";}?>> Notice of Readiness - Unloading
						<br>
						<input type='checkbox' id='notice_of_completion_unloading' name='attachments[]' value='Notice of Completion - Unloading' <?php if($a8){echo "checked";}?>> Notice of Completion - Unloading
						<br>
						<input type='checkbox' id='statement_of_facts_loading' name='attachments[]' value='Statement of Facts - Loading' <?php if($a9){echo "checked";}?>> Statement of Facts - Loading
						<br>
						<input type='checkbox' id='statement_of_facts_unloading' name='attachments[]' value='Statement of Facts - Unloading' <?php if($a10){echo "checked";}?>> Statement of Facts - Unloading
					</div>
					<div class='col-sm-6 col-xs-12'>
						<input type='checkbox' id='mates_receipt' name='attachments[]' value="Mates Receipt" <?php if($a11){echo "checked";}?>> Mate's Receipt
						<br>
						<input type='checkbox' id='trip_tickets' name='attachments[]' value='Trip Ticket(s)' <?php if($a12){echo "checked";}?>> Trip Ticket(s)
						<br>
						<input type='checkbox' id='certificate_of_cargo' name='attachments[]' value='Certificate of Cargo - Delivery Receipt (CCR)' <?php if($a13){echo "checked";}?>> Certificate of Cargo - Delivery Receipt (CCR)
						<br>
						<input type='checkbox' id='complete_discharge_report' name='attachments[]' value='Complete Discharge Report and Unloading Certificate' <?php if($a14){echo "checked";}?>> Complete Discharge Report and Unloading Certificate
						<br>
						<input type='checkbox' id='letter_of_protests' name='attachments[]' value='Letter of Protest(s)' <?php if($a15){echo "checked";}?>> Letter of Protest(s)
						<br>
						<input type='checkbox' id='certificate_of_cargo_hold' name='attachments[]' value='Certificate of Cargo Hold Inspection' <?php if($a17){echo "checked";}?>> Certificate of Cargo Hold Inspection
						<br>
						<input type='checkbox' id='copy_of_fixture' name='attachments[]' value='Copy of Fixture Note or Contract' <?php if($a18){echo "checked";}?>> Copy of Fixture Note or Contract
						<br>
						<input type='checkbox' id='incident_report' name='attachments[]' value='Incident Report' <?php if($a19){echo "checked";}?>> Incident Report
						<br>
						<input type='checkbox' id='sealing_plan' name='attachments[]' value='Sealing Plan' <?php if($a20){echo "checked";}?>> Sealing Plan
						<br>
						<input type='checkbox' id='stowage_plan' name='attachments[]' value='Stowage Plan' <?php if($a21){echo "checked";}?>> Stowage Plan
						<br>
						<input type='checkbox' id='others' name='attachments[]' value='<?php echo 'Others:'.$others?>' <?php if($a16){echo "checked";}?>> Others, please specify:
						<br>
						<input type='text' id='others_txt' name='others_txt' class='form-control others_txt' value='<?php echo $others?>' <?php if(!$a16){echo "disabled";}?>>
					</div>
				</div>
			</div>

		</div>

		<div class='panel panel-info' id='panel_final'>
			<div class='panel-heading' role='tab' id='output_variance'>
				<a role='button' data-toggle='collapse' data-parent='#OSFormDivider' href='#OSOutput' aria-expanded='true' aria-controls='OSOutput'>
				Final Output and Variance
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>

			<div id='OSOutput' class='panel-collapse collapse' role='tabpanel' aria-labelledby='OSOutput'>
				<div class='panel-body'>

				<div id='table_output_div' class='col-md-12 col-sm-12 col-xs-12'>
					<table id='table_output' class="table table-bordered table-hover table_output">
						<thead>
							<th>
								Cargo Declared Per:
							</th>
							<th>
								Quantity*
							</th>
							<th>
								Weight*
							</th>
						</thead>
						<tbody>
							<tr>
								<td>
									<center>Shipper</center>
								</td>
								<td>
									<input type='number' id='shipper_number_of_bags' name='shipper_number_of_bags' class='form-control no_of_bags_shipper' value='<?php echo $shipper_bags?>' required>
								</td>
								<td>
									<input type='number' id='shipper_weight' name='shipper_weight' class='form-control weight_shipper' value='<?php echo $shipper_weight?>' required>
								</td>
							</tr>
							<tr>
								<td>
									<center>Consignee</center>
								</td>
								<td>
									<input type='number' id='consignee_number_of_bags' name='consignee_number_of_bags' class='form-control no_of_bags_consignee' value='<?php echo $consignee_bags?>' required>
								</td>
								<td>
									<input type='number' id='consignee_weight' name='consignee_weight' class='form-control weight_consignee' value='<?php echo $consignee_weight?>' required>
								</td>
							</tr>
							<tr>
								<td>
									<center>Variance</center>
								</td>
								<td>
									<input type='number' id='variance_number_of_bags' name='variance_number_of_bags' class='form-control no_of_bags_variance' value='<?php echo $variance_bags?>' readonly>
								</td>
								<td>
									<input type='number' id='variance_weight' name='variance_weight' class='form-control weight_variance' value='<?php echo $variance_weight?>' readonly>
								</td>
							</tr>
							<tr>
								<td>
									<center>Percentage</center>
								</td>
								<td>
									<input type='text' id='percentage_number_of_bags' name='percentage_number_of_bags' class='form-control no_of_bags_percentage' value='<?php echo $percentage_bags?>' readonly>
								</td>
								<td>
									<input type='text' id='percentage_weight' name='percentage_weight' class='form-control weight_percentage' value='<?php echo $percentage_weight?>' readonly>
								</td>
							</tr>
							<tr>
								<td colspan='2'><b>Accounted For:</b></td>
							</tr>
							<tr>
								<td>
									<center>Good</center>
								</td>
								<td>
									<input type='number' id='good_number_of_bags' name='good_number_of_bags' class='form-control good' value='<?php echo $good?>' required>
								</td>

							</tr>
							<tr>
								<td>
									<center>Damaged/Loss</center>
								</td>
								<td>
									<input type='number' id='damaged_number_of_bags' name='damaged_number_of_bags' class='form-control damaged' value='<?php echo $damaged?>' required>
								</td>

							</tr>
							<tr>
								<td>
									<center>Total</center>
								</td>
								<td>
									<input type='number' id='total_number_of_bags' name='total_number_of_bags' class='form-control total' value='<?php echo $total?>' readonly>
								</td>

							</tr>
						</tbody>

					</table>
				</div>

				</div>
			</div>

		</div>

		<div class='panel panel-info' id='panel_remarks'>
			<div class='panel-heading' role='tab' id='remarks_tab'>
				<a role='button' data-toggle='collapse' data-parent='#OSFormDivider' href='#OSRemarks' aria-expanded='true' aria-controls='OSRemarks'>
				Remarks
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>

			<div id='OSRemarks' class='panel-collapse collapse' role='tabpanel' aria-labelledby='OSRemarks'>
				<div class='panel-body'>
					<div class='col-md-12 col-sm-12 col-xs-12'>
						<textarea id='remarks' name='remarks' class='form-control' rows='5'><?php echo $remarks?></textarea>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

	<div class='col-xs-12 col-sm-12 col-lg-12'>
		<span class='pull-right'>
			<input type='button' value='Save' name='btnSubmit' id='btnSubmit' class='btn btn-success btn-m' onclick='javascript: validateForm()' />
			<input type='button' class='btn btn-danger btn-m' value='Discard' data-dismiss='modal'>
		</span>
	</div>
	<br><br><br>
</form>

</body>
</html>

<script type="text/javascript">

var row_type;//for type of services

//sets table_breakdown as bootstrap table
$(function () {
	var $table = $('#table_breakdown');
	$table.bootstrapTable();

});

$( "#soa_id" ).autocomplete({
	source: "<?php echo HTTP_PATH; ?>collection/auto_complete_statement_of_account",
	minLength: 1,
	search: function(event, ui) {
		toastr['info']('Loading, please wait...');
	},
	response: function(event, ui) {
		toastr.clear();
	},
	select: function( event, ui ) {
		$("#soa").val( ui.item.label );
		$( "#soa_id" ).val( ui.item.id );
		return false;
	}
});

function basis(type){

	 if(type=="SO"){
	 	$('#SO_radio').prop('checked', true);
	 	$('#OS_radio').prop('checked', false);
	 	$('#out_turn_summary_div').hide();
		$('#service_order_div').show();
		$('#service_order_details_div').show();
		$('#out_turn_summary_details_div').hide();
	 }
	 else if(type=="OS"){
	 	$('#SO_radio').prop('checked', false);
	 	$('#OS_radio').prop('checked', true);
	 	$('#out_turn_summary_div').show();
		$('#service_order_div').hide();
		$('#service_order_details_div').hide();
		$('#out_turn_summary_details_div').show();
	 }
}

$(document).ready(function()
{
	$('#out_turn_summary_div').hide();
	$('#out_turn_summary_details_div').hide();
	$('#service_order_div').show();

	$("#bol_number_div").hide();
	$("#qty_bol_div").hide();
	$("#weight_bol_div").hide();
	$("#shipper_div").hide();
	$("#consignee_div").hide();
	$("#surveyor_div").hide();
	$("#arrastre_div").hide();
	$("#voyage_number_div").hide();
	$("#mother_vessel_div").hide();
	$("#loading_arrival_date_div").hide();
	$("#loading_start_datetime_div").hide();
	$("#loading_ended_datetime_div").hide();
	$("#loading_departure_date_div").hide();
	$("#loading_quantity_volume_div").hide();
	$("#unloading_arrival_date_div").hide();
	$("#unloading_start_datetime_div").hide();
	$("#unloading_ended_datetime_div").hide();
	$("#unloading_departure_date_div").hide();
	$("#unloading_quantity_volume_div").hide();
	$('#lighterage_receipt_no_div').hide();
	$('#trip_ticket_no_div').hide();
	$('#statement_of_facts_no_div').hide();
	$('#barge_patron_div').hide();
	$('#loading_batch_weight_div').hide();
	$('#unloading_batch_weight_div').hide();

	$("#panel_details").hide();
	$("#panel_attachments").hide();
	$("#panel_deliveries").hide();
	$("#panel_final").hide();
	$("#table_output_div").hide();

	<?php if(isset($OS)){

		if($OS->service_order_id!=0){
			echo "$('#out_turn_summary_div').hide();";
			echo "$('#SO_radio').prop('disabled','disabled');";
			echo "$('#OS_radio').prop('disabled','disabled');";
			echo "$('#out_turn_summary_details_div').hide();";
			echo "$('#service_order_div').show();";
			echo "$('#service_order_details_div').show();";
		}else{
			echo "$('#out_turn_summary_div').hide();";
			echo "$('#SO_radio').prop('disabled','disabled');";
			echo "$('#OS_radio').prop('disabled','disabled');";
			echo "$('#out_turn_summary_details_div').show();";
			echo "$('#service_order_div').hide();";
			echo "$('#service_order_details_div').hide();";
			echo "$('#panel_deliveries').show();";
		}

		echo "show_service_details('".$OS->type_of_service."');";

	}?>



});

$('#SO_radio').change(function(){
	<?php if(!isset($OS)){?>
		$('#out_turn_summary').val('');
		$('#reference_no').val("");
		$('#service_order_type').val("");
		$('#date_needed').val("");
		$('#status').val("");
		$('#cargo_description').val("");
		$('#local').val("");
		$('#port_origin').val("");
		$('#port_destination').val("");
		$('#client').val("");

		$("#panel_details").hide();
		$("#panel_attachments").hide();
		$("#panel_deliveries").hide();
		$("#panel_final").hide();
		$("#table_output_div").hide();
	<?php } ?>
});

$('#OS_radio').change(function(){
	<?php if(!isset($OS)){?>
		$('#service_order').val('');
		$('#reference_no').val("");
		$('#service_order_type').val("");
		$('#date_needed').val("");
		$('#status').val("");
		$('#cargo_description').val("");
		$('#local').val("");
		$('#port_origin').val("");
		$('#port_destination').val("");
		$('#client').val("");

		$("#panel_details").hide();
		$("#panel_attachments").hide();
		$("#panel_deliveries").hide();
		$("#panel_final").hide();
		$("#table_output_div").hide();
	<?php } ?>
});

//Ajax action after selecting company
$('#company').change(function(){

	$('#reference_no').val("");
	$('#service_order_type').val("");
	$('#date_needed').val("");
	$('#status').val("");
	$('#cargo_description').val("");
	$('#local').val("");
	$('#port_origin').val("");
	$('#port_destination').val("");
	$('#client').val("");

   //Ajax to set the Service Orders for selected company
  $.ajax({
     type:"POST",
     url:"<?php echo HTTP_PATH;?>/operation/get_service_orders_by_company/"+$(this).val(),
     success:function(data){

        var service_orders = $.parseJSON(data);
    	$('#service_order').find('option').remove().end().append('<option value="">Select</option>').val('');

        for(var i = 0; i < service_orders.length; i++){
       		var service_order = service_orders[i];
       		var option = $('<option />');
		    option.attr('value', service_order.id).text("SO No. "+service_order.control_number+" | "+service_order.type+" (Transaction Code "+service_order.id+")");
		    $('#service_order').append(option);
        }

     }

  });

   //Ajax to get all Out-Turn Summaries when company is selected
  $.ajax({
     type:"POST",
     url:"<?php echo HTTP_PATH;?>/operation/get_out_turn_summaries",
     success:function(data){

        var out_turn_summaries = $.parseJSON(data);
    	$('#out_turn_summary').find('option').remove().end().append('<option value="">Select</option>').val('');

        for(var i = 0; i < out_turn_summaries.length; i++){
       		var out_turn_summary = out_turn_summaries[i];
       		var option = $('<option />');

   			 option.attr('value', out_turn_summary.id).text("OS No. "+out_turn_summary.control_number+" | "+out_turn_summary.type_of_service+" ("+out_turn_summary.company_name+" - Transaction Code "+out_turn_summary.id+")");
	    	$('#out_turn_summary').append(option);

        }

     }

  });

  //Ajax to set the Contracts for selected company
  $.ajax({
     type:"POST",
     url:"<?php echo HTTP_PATH;?>/operation/get_contracts_by_company/"+$(this).val(),
     success:function(data){

        var contracts = $.parseJSON(data);
    	$('#reference_no_OS').find('option').remove().end().append('<option value="">Select</option>').val('');

        for(var i = 0; i < contracts.length; i++){
       		var contract = contracts[i];
       		var option = $('<option />');
		    option.attr('value', contract.id).text(contract.reference_no);
		    $('#reference_no_OS').append(option);
        }

     }

  });


});



$('#out_turn_summary').change(function(){

  $.ajax({
     type:"POST",
     url:"<?php echo HTTP_PATH;?>/operation/get_out_turn_summary/"+$(this).val(),
     success:function(data){

        var out_turn = $.parseJSON(data);
    	$('#service_order_type').val(out_turn.type_of_service);

     }

  });


});

$('#reference_no_OS').change(function(){

  $.ajax({
     type:"POST",
     url:"<?php echo HTTP_PATH;?>/operation/get_contract_details/"+$(this).val(),
     success:function(data){

        var contract = $.parseJSON(data);
    	$('#type_of_service_OS').val(contract.type);
    	$('#client_OS').val(contract.client.company);
     }

  });


});

//Ajax action after service order
$('#service_order').change(function(){

  $.ajax({
     type:"POST",
     url:"<?php echo HTTP_PATH;?>/operation/get_service_order/"+$(this).val(),
     success:function(data){

        var service_order = $.parseJSON(data);

        $('#reference_no').val(service_order.contract.reference_no);
    	$('#service_order_type').val(service_order.type);
    	$('#date_needed').val(service_order.date_needed);
    	$('#status').val(service_order.status);
    	$('#client').val(service_order.contract.client.company);
    	$('#cargo_description').val(service_order.details.cargo_description);

    	if(service_order.type=="Shipping"){
    		$('#local').val(service_order.details.vessel);
    		$('#vessel_id').val(service_order.details.vessel_id);
    		$('#port_origin').val(service_order.details.from_location);
    		$('#port_destination').val(service_order.details.to_location);
    	}else if(service_order.type=="Trucking"){
    		$('#local').val("N/A");
    		$('#port_origin').val(service_order.details.from_location);
    		if(service_order.details.drop_off_point_1!="" || service_order.details.drop_off_point_1!=null){
    			var loc = service_order.details.drop_off_point_1 + " | " + service_order.details.drop_off_point_2 + " | "+ service_order.details.drop_off_point_3 + " | " + service_order.details.drop_off_point_4;

    			if(loc=="null | null | null | null"){
    				loc = service_order.details.to_location;
    			}

    			$('#port_destination').val(loc);

    		}else{
    			$('#port_destination').val(service_order.details.to_location);
    		}
    	}else if(service_order.type=="Handling"){
    		$('#local').val("N/A");
    		$('#port_origin').val("N/A");
    		$('#port_destination').val("N/A");
    	}else if(service_order.type=="Lighterage"){
    		$('#local').val(service_order.details.vessel);
    		$('#vessel_id').val(service_order.details.vessel_id);
    		$('#port_origin').val(service_order.details.vessel_location);
    		$('#port_destination').val(service_order.details.discharge_location);
    	}else if(service_order.type=="Time Charter"){
    		$('#local').val(service_order.details.vessel);
    		$('#vessel_id').val(service_order.details.vessel_id);
    		$('#port_origin').val(service_order.details.start_location);
    		$('#port_destination').val(service_order.details.end_location);
    	}else if(service_order.type=="Towing"){
    		$('#local').val(service_order.details.vessel);
    		$('#vessel_id').val(service_order.details.vessel_id);
    		$('#port_origin').val(service_order.details.from_location);
    		$('#port_destination').val(service_order.details.to_location);
    	}


    	show_service_details(service_order.type);
    	row_type = service_order.type;
     }

  });

  	$('#company').prop('readOnly',true);
    $('#service_order').prop('readOnly',true);

});


//adds row in breakdown table
var i=0;
var cnt_tbl_row = 0;
$("#add_row").click(function(){

	if(typeof(row_type) != "undefined"){

		var x;
		var y;

		if(row_type=="Trucking"){
			y = "<?php echo $appendable_trucking_header;?>";
			x = "<?php echo $appendable_trucking_row;?>";
		}
		if(row_type=="Handling"){
			y = "<?php echo $appendable_handling_header;?>";
			x = "<?php echo $appendable_handling_row;?>";
		}

		<?php
			if(!isset($OS)){?>
				$("#table_breakdown").find("thead").empty().append(y);
				$('#cargo_row'+i).html("<td class='text-center'>"+ (i+1) +"</td>" + x);
				$('#table_breakdown').append('<tr class="tbl_row" id="cargo_row'+(i+1)+'"></tr>');
				i++;
		<?php }?>

		<?php
			if(isset($OS)){?>

				cnt_tbl_row = $(".tbl_row").length;
				i = cnt_tbl_row + 1;

				$('#cargo_row'+i).html("<td class='text-center'>"+ (i-1) +"</td>" + x);
				$('#table_breakdown').append('<tr class="tbl_row" id="cargo_row'+ (i+1) +'"></tr>');

		<?php }?>

		var ctr = 0;
		$(".tbl_row").each(function() {
			ctr = ctr + 1;
			$('.sorting', this).val(ctr);
		});

	}

});

$("#table_breakdown").keypress(function(e) {
    if(e.which == 13) {
        if(typeof(row_type) != "undefined"){

			var x;
			var y;

			if(row_type=="Trucking"){
				y = "<?php echo $appendable_trucking_header;?>";
				x = "<?php echo $appendable_trucking_row;?>";
			}
			if(row_type=="Handling"){
				y = "<?php echo $appendable_handling_header;?>";
				x = "<?php echo $appendable_handling_row;?>";
			}

			<?php
				if(!isset($OS)){?>
					$("#table_breakdown").find("thead").empty().append(y);
					$('#cargo_row'+i).html("<td class='text-center'>"+ (i+1) +"</td>" + x);
					$('#table_breakdown').append('<tr class="tbl_row" id="cargo_row'+(i+1)+'"></tr>');
					i++;

			<?php }?>

			<?php
				if(isset($OS)){?>

					cnt_tbl_row = $(".tbl_row").length;
					i = cnt_tbl_row + 1;

					$('#cargo_row'+i).html("<td class='text-center'>"+ (i-1) +"</td>" + x);
					$('#table_breakdown').append('<tr class="tbl_row" id="cargo_row'+ (i+1) +'"></tr>');


			<?php }?>

			var ctr = 0;
			$(".tbl_row").each(function() {
				ctr = ctr + 1;
				$('.sorting', this).val(ctr);
			});

			//$( document.activeElement ).next().focus().select();

		}

		$('table tr #cargo_row'+(i+1)+'').find('input:first').focus().select();


		   //var next = $(this).closest('td').next().find('input[type="date"]');
		  // next.focus().select();


    }
});


//deletes row in breakdown table
$("#delete_row").click(function(){
	<?php
	if(!isset($OS)){?>
		 if(i>1){
		 	$("#cargo_row"+(i-1)).html('');
		 	i--;
		 }
	<?php }?>

	<?php
	if(isset($OS)){?>
		 if(cnt_tbl_row>=1){
		 	$("#cargo_row"+(i)).html('');
		 	i--;
		 }
	<?php }?>

});

//enable/disable others in attachments tab
$('#others').change(function(){
    if ($('#others').is(':checked') == true){
    	$('#others').val('');
        $('#others_txt').prop('disabled', false);
    }
    else{
        $('#others_txt').prop('disabled', true).val('');
    }
});
//sets the document name of other attachment if selected
$('#others_txt').change(function(){
	var txt = $('.others_txt').val();
	$('#others').val('Others:'+txt);
});

//calculates Net weight per breakdown
$(document).on('keyup', ".tbl_row input", calcNetWt);

  function calcNetWt() {
		$(".tbl_row").each(function() {
		var $gross_wt  = $('.gross_weight', this).val();
	    var $tare_wt  = $('.tare_weight', this).val();
		var $net_wt = parseFloat((($gross_wt*1)-($tare_wt*1)));
		$('.net_weight', this).val($net_wt);
	});
  };


//calculates values on Final Output
$(document).on('keyup', ".table_output input", calcOutput);

  function calcOutput() {
		$(".table_output").each(function() {
		var $no_of_bags_shipper  = $('.no_of_bags_shipper', this).val();
	    var $no_of_bags_consignee  = $('.no_of_bags_consignee', this).val();
		var $no_of_bags_variance = parseFloat((($no_of_bags_shipper*1)-($no_of_bags_consignee*1)));
		var $no_of_bags_percentage = parseFloat((($no_of_bags_variance*1)/($no_of_bags_shipper*1)))*100;
		$('.no_of_bags_variance', this).val($no_of_bags_variance);
		$('.no_of_bags_percentage', this).val($no_of_bags_percentage.toFixed(2)+"%");

		var $weight_shipper  = $('.weight_shipper', this).val();
	    var $weight_consignee  = $('.weight_consignee', this).val();
		var $weight_variance = parseFloat((($weight_shipper*1)-($weight_consignee*1)));
		var $weight_percentage = parseFloat((($weight_variance*1)/($weight_shipper*1)))*100;
		$('.weight_variance', this).val($weight_variance);
		$('.weight_percentage', this).val($weight_percentage.toFixed(2)+"%");

		var $good  = $('.good', this).val();
	    var $damaged  = $('.damaged', this).val();
		var $total = parseFloat((($good*1)-($damaged*1)));
		$('.total', this).val($total);

	});
  };



//checks un/loading start/end dates
function checkDates(step){
	var loading_start_datetime = $('#loading_start_datetime').val();
	var loading_ended_datetime = $('#loading_ended_datetime').val();
	var unloading_start_datetime = $('#unloading_start_datetime').val();
	var unloading_ended_datetime = $('#unloading_ended_datetime').val();

	if(step==1){
		if(typeof(loading_start_datetime)!='undefined' && typeof(loading_ended_datetime)!='undefined'){
			if(loading_ended_datetime < loading_start_datetime){
				toastr['error']("Please make sure that loading end date is not earlier that loading start date!", "ABAS says:");
				$('#loading_ended_datetime').val('');;
			}
		}
	}

	if(step==2){
		if(typeof(loading_ended_datetime)!='undefined' && typeof(unloading_start_datetime)!='undefined'){
			if(unloading_start_datetime < loading_ended_datetime){
				toastr['error']("Please make sure that unloading start date is not earlier that loading end date!", "ABAS says:");
				$('#unloading_start_datetime').val('');;
			}
		}
	}

	if(step==3){
		if(typeof(unloading_start_datetime)!='undefined' && typeof(unloading_ended_datetime)!='undefined'){
			if(unloading_ended_datetime < unloading_start_datetime){
				toastr['error']("Please make sure that unloading end date is not earlier that unloading start date!", "ABAS says:");
				$('#unloading_ended_datetime').val('');;
			}
		}
	}

}


function show_service_details(service_type){
	//var service_type = $("#service_order_type").val();

	if(service_type==""){
		$("#bol_number_div").hide();
		$("#qty_bol_div").hide();
		$("#weight_bol_div").hide();
		$("#shipper_div").hide();
		$("#consignee_div").hide();
		$("#surveyor_div").hide();
		$("#arrastre_div").hide();
		$("#voyage_number_div").hide();
		$("#mother_vessel_div").hide();
		$("#loading_arrival_date_div").hide();
		$("#loading_start_datetime_div").hide();
		$("#loading_ended_datetime_div").hide();
		$("#loading_departure_date_div").hide();
		$("#loading_quantity_volume_div").hide();
		$("#unloading_arrival_date_div").hide();
		$("#unloading_start_datetime_div").hide();
		$("#unloading_ended_datetime_div").hide();
		$("#unloading_departure_date_div").hide();
		$("#unloading_quantity_volume_div").hide();
		$('#soa_div').hide();
		$('#lighterage_receipt_no_div').hide();
		$('#trip_ticket_no_div').hide();
		$('#statement_of_facts_no_div').hide();
		$('#barge_patron_div').hide();
		$('#loading_batch_weight_div').hide();
		$('#unloading_batch_weight_div').hide();

		row_type = service_type;

		$('#reference_label').text('Statement of Facts Ref.*');
		$('#patron_label').text('Barge Patron*');
		$('#mother_label').text('Mother Vessel');
		$('#From').text('Shipper*');
		$('#loading_label').text('Loading:');
		$('#unloading_label').text('Unloading:');
		$('#contract_reference_div').hide();
		$('#departure_loc').hide();
		$('#arrival_loc').hide();

		$("#panel_details").hide();
		$("#panel_attachments").hide();
		$("#panel_deliveries").hide();
		$("#panel_final").hide();
		$("#table_output_div").hide();

	}else if(service_type=="Shipping"){
		$("#bol_number_div").show();
		$("#qty_bol_div").show();
		$("#weight_bol_div").show();
		$("#shipper_div").show();
		$("#consignee_div").show();
		$("#surveyor_div").show();
		$("#arrastre_div").show();
		$("#voyage_number_div").show();
		$("#mother_vessel_div").show();
		$("#loading_arrival_date_div").show();
		$("#loading_start_datetime_div").show();
		$("#loading_ended_datetime_div").show();
		$("#loading_departure_date_div").show();
		$("#loading_quantity_volume_div").show();
		$("#unloading_arrival_date_div").show();
		$("#unloading_start_datetime_div").show();
		$("#unloading_ended_datetime_div").show();
		$("#unloading_departure_date_div").show();
		$("#unloading_quantity_volume_div").show();
		$('#soa_div').hide();
		$('#lighterage_receipt_no_div').hide();
		$('#trip_ticket_no_div').hide();
		$('#statement_of_facts_no_div').hide();
		$('#barge_patron_div').hide();
		$('#loading_batch_weight_div').hide();
		$('#unloading_batch_weight_div').hide();

		$("#bol_number_div").addClass('im_visible');
		$("#qty_bol_div").addClass('im_visible');
		$("#weight_bol_div").addClass('im_visible');
		$("#shipper_div").addClass('im_visible');
		$("#consignee_div").addClass('im_visible');
		$("#surveyor_div").addClass('im_visible');
		$("#arrastre_div").addClass('im_visible');
		$("#voyage_number_div").addClass('im_visible');
		$("#loading_arrival_date_div").addClass('im_visible');
		$("#loading_start_datetime_div").addClass('im_visible');
		$("#loading_ended_datetime_div").addClass('im_visible');
		$("#loading_departure_date_div").addClass('im_visible');
		$("#loading_quantity_volume_div").addClass('im_visible');
		$("#unloading_arrival_date_div").addClass('im_visible');
		$("#unloading_start_datetime_div").addClass('im_visible');
		$("#unloading_ended_datetime_div").addClass('im_visible');
		$("#unloading_departure_date_div").addClass('im_visible');
		$("#unloading_quantity_volume_div").addClass('im_visible');

		row_type = service_type;

		$('#reference_label').text('Statement of Facts Ref.*');
		$('#patron_label').text('Barge Patron*');
		$('#mother_label').text('Mother Vessel');
		$('#From').text('Shipper*');
		$('#loading_label').text('Loading:');
		$('#unloading_label').text('Unloading:');
		$('#contract_reference_div').hide();
		$('#departure_loc').hide();
		$('#arrival_loc').hide();

		$("#panel_details").show();
		$("#panel_attachments").show();
		$("#panel_deliveries").hide();
		$("#panel_final").show();
		$("#table_output_div").show();

	}else if(service_type=="Trucking"){
		$("#bol_number_div").show();
		$("#qty_bol_div").show();
		$("#weight_bol_div").show();
		$("#shipper_div").show();
		$("#consignee_div").hide();
		$("#surveyor_div").hide();
		$("#arrastre_div").hide();
		$("#voyage_number_div").hide();
		$("#mother_vessel_div").hide();
		$("#loading_arrival_date_div").hide();
		$("#loading_start_datetime_div").hide();
		$("#loading_ended_datetime_div").hide();
		$("#loading_departure_date_div").hide();
		$("#loading_quantity_volume_div").hide();
		$("#unloading_arrival_date_div").hide();
		$("#unloading_start_datetime_div").hide();
		$("#unloading_ended_datetime_div").hide();
		$("#unloading_departure_date_div").hide();
		$("#unloading_quantity_volume_div").hide();
		$('#soa_div').hide();
		$('#lighterage_receipt_no_div').hide();
		$('#trip_ticket_no_div').hide();
		$('#statement_of_facts_no_div').hide();
		$('#barge_patron_div').hide();
		$('#loading_batch_weight_div').hide();
		$('#unloading_batch_weight_div').hide();

		row_type = service_type;

		$('#reference_label').text('Statement of Facts Ref.*');
		$('#patron_label').text('Barge Patron*');
		$('#mother_label').text('Mother Vessel');
		$('#From').text('Shipper*');
		$('#loading_label').text('Loading:');
		$('#unloading_label').text('Unloading:');
		$('#contract_reference_div').hide();
		$('#departure_loc').hide();
		$('#arrival_loc').hide();

		$("#panel_details").hide();
		$("#panel_attachments").hide();
		$("#panel_deliveries").show();
		$("#panel_final").hide();
		$("#table_output_div").hide();

	}else if(service_type=="Handling"){
		$("#bol_number_div").hide();
		$("#qty_bol_div").hide();
		$("#weight_bol_div").hide();
		$("#shipper_div").hide();
		$("#consignee_div").hide();
		$("#surveyor_div").hide();
		$("#arrastre_div").hide();
		$("#voyage_number_div").hide();
		$("#mother_vessel_div").hide();
		$("#loading_arrival_date_div").hide();
		$("#loading_start_datetime_div").hide();
		$("#loading_ended_datetime_div").hide();
		$("#loading_departure_date_div").hide();
		$("#loading_quantity_volume_div").hide();
		$("#unloading_arrival_date_div").hide();
		$("#unloading_start_datetime_div").hide();
		$("#unloading_ended_datetime_div").hide();
		$("#unloading_departure_date_div").hide();
		$("#unloading_quantity_volume_div").hide();
		$('#soa_div').hide();
		$('#lighterage_receipt_no_div').hide();
		$('#trip_ticket_no_div').hide();
		$('#statement_of_facts_no_div').hide();
		$('#barge_patron_div').hide();
		$('#loading_batch_weight_div').hide();
		$('#unloading_batch_weight_div').hide();

		row_type = service_type;

		$('#reference_label').text('Statement of Facts Ref.*');
		$('#patron_label').text('Barge Patron*');
		$('#mother_label').text('Mother Vessel');
		$('#From').text('Shipper*');
		$('#loading_label').text('Loading:');
		$('#unloading_label').text('Unloading:');
		$('#contract_reference_div').hide();
		$('#departure_loc').hide();
		$('#arrival_loc').hide();

		$("#panel_details").hide();
		$("#panel_attachments").hide();
		$("#panel_deliveries").show();
		$("#panel_final").hide();
		$("#table_output_div").hide();

	}else if (service_type=="Lighterage" || service_type=="Time Charter"){
		$("#bol_number_div").hide();
		$("#qty_bol_div").hide();
		$("#weight_bol_div").hide();
		$("#shipper_div").show();
		$("#consignee_div").show();
		$("#surveyor_div").hide();
		$("#arrastre_div").hide();
		$("#voyage_number_div").show();
		$("#mother_vessel_div").show();
		$("#loading_arrival_date_div").show();
		$("#loading_start_datetime_div").show();
		$("#loading_ended_datetime_div").show();
		$("#loading_departure_date_div").show();
		$("#loading_quantity_volume_div").show();
		$("#unloading_arrival_date_div").show();
		$("#unloading_start_datetime_div").show();
		$("#unloading_ended_datetime_div").show();
		$("#unloading_departure_date_div").show();
		$("#unloading_quantity_volume_div").show();
		if(service_type=="Time Charter"){
			$('#soa_div').show();
		}
		$('#lighterage_receipt_no_div').show();
		$('#trip_ticket_no_div').show();
		$('#statement_of_facts_no_div').show();
		$('#barge_patron_div').show();
		$("#patron_label").text("Master/Patron");
		$('#loading_batch_weight_div').show();
		$('#unloading_batch_weight_div').show();


		$("#shipper_div").addClass('im_visible');
		$("#consignee_div").addClass('im_visible');
		$("#voyage_number_div").addClass('im_visible');
		$("#loading_arrival_date_div").addClass('im_visible');
		$("#loading_start_datetime_div").addClass('im_visible');
		$("#loading_ended_datetime_div").addClass('im_visible');
		$("#loading_departure_date_div").addClass('im_visible');
		$("#loading_quantity_volume_div").addClass('im_visible');
		$("#unloading_arrival_date_div").addClass('im_visible');
		$("#unloading_start_datetime_div").addClass('im_visible');
		$("#unloading_ended_datetime_div").addClass('im_visible');
		$("#unloading_departure_date_div").addClass('im_visible');
		$("#unloading_quantity_volume_div").addClass('im_visible');
		if(service_type=="Time Charter"){
			$('#soa_div').addClass('im_visible');
		}
		$('#lighterage_receipt_no_div').addClass('im_visible');
		$('#trip_ticket_no_div').addClass('im_visible');
		$('#statement_of_facts_no_div').addClass('im_visible');
		$('#barge_patron_div').addClass('im_visible');
		$('#loading_batch_weight_div').addClass('im_visible');
		$('#unloading_batch_weight_div').addClass('im_visible');

		row_type = service_type;

		$('#reference_label').text('Statement of Facts Ref.*');
		$('#patron_label').text('Barge Patron*');
		$('#mother_label').text('Mother Vessel');
		$('#From').text('Shipper*');
		$('#loading_label').text('Loading:');
		$('#unloading_label').text('Unloading:');
		$('#contract_reference_div').hide();
		$('#departure_loc').hide();
		$('#arrival_loc').hide();

		$("#panel_details").show();
		$("#panel_attachments").hide();
		$("#panel_deliveries").hide();
		$("#panel_final").hide();
		$("#table_output_div").hide();
	}
	else if (service_type=="Towing"){

		var reference_no  = $("#reference_no").val();
		$("#contract_reference_no").val(reference_no);

		var customer  = $("#client").val();
		$("#shipper").val(customer);

		var departure_location  = $("#port_origin").val();
		$("#departure_location").val(departure_location);

		var arrival_location  = $("#port_destination").val();
		$("#arrival_location").val(arrival_location);

		$("#bol_number_div").hide();
		$("#qty_bol_div").hide();
		$("#weight_bol_div").hide();
		$("#shipper_div").show();
		$("#consignee_div").show();
		$("#surveyor_div").hide();
		$("#arrastre_div").hide();
		$("#voyage_number_div").show();
		$("#mother_vessel_div").show();
		$("#loading_arrival_date_div").hide();
		$("#loading_start_datetime_div").show();
		$("#loading_ended_datetime_div").hide();
		$("#loading_departure_date_div").hide();
		$("#loading_quantity_volume_div").hide();
		$("#unloading_arrival_date_div").hide();
		$("#unloading_start_datetime_div").hide();
		$("#unloading_ended_datetime_div").show();
		$("#unloading_departure_date_div").hide();
		$("#unloading_quantity_volume_div").hide();
		$('#soa_div').hide();
		$('#lighterage_receipt_no_div').hide();
		$('#trip_ticket_no_div').show();
		$('#statement_of_facts_no_div').hide();
		$('#barge_patron_div').show();
		$('#loading_batch_weight_div').hide();
		$('#unloading_batch_weight_div').hide();

		$("#shipper_div").addClass('im_visible');
		$("#consignee_div").addClass('im_visible');
		$("#voyage_number_div").addClass('im_visible');

		$("#loading_departure_date_div").addClass('im_visible');
		$("#unloading_arrival_date_div").addClass('im_visible');
		
		$('#trip_ticket_no_div').addClass('im_visible');
		$('#barge_patron_div').addClass('im_visible');
		$('#loading_batch_weight_div').addClass('im_visible');
		$('#unloading_batch_weight_div').addClass('im_visible');

		$('#patron_label').text('Master/Patron*');
		$('#mother_label').text('Craft Towed');
		$('#From').text('Account/Customer*');
		$('#loading_label').text('Departure Terminal:');
		$('#unloading_label').text('Arrival Terminal:');
		$('#contract_reference_div').show();
		$('#departure_loc').show();
		$('#arrival_loc').show();

		row_type = service_type;

		$("#panel_details").show();
		$("#panel_attachments").hide();
		$("#panel_deliveries").hide();
		$("#panel_final").hide();
		$("#table_output_div").hide();
	}

}

$('#uploaded_file').change(function(){
    $obj = this.files[0] ;
    if (typeof($obj) != 'undefined'){
    	$('#table_breakdown').bootstrapTable('removeAll');
    	$('#breakdown_table').hide().prop('disabled', true);
    }else{
    	$('#breakdown_table').show().prop('disabled', false);
    }
});


function validateForm(){

	var service_type = $("#service_order_type").val();
	var SO_isChecked = $('SO_radio').val()?true:false;

    if($("#company").val()==""){
    	toastr['error']("Please fill-out all required* fields in General Information Tab!", "ABAS says:");
		return false;
    }

    if($("#reference_no_OS").is(":visible") && $("#reference_no_OS").val()==""){
    	toastr['error']("Please fill-out all required* fields in General Information Tab!", "ABAS says:");
		return false;
	}

    if(service_type=="Shipping"){

	    var detail_selects = $("#OSDetails").find('.im_visible select').filter('[required]');
	    var detail_inputs = $("#OSDetails").find('.im_visible input').filter('[required]');

	    if(SO_isChecked==true){

			var detail_flag=0;
		    for(var i = 0; i < detail_selects.length; i++){
		        if (detail_selects[i].value==""){
		        	detail_flag=1;
		         }
		    }
		    for(var x = 0; x < detail_inputs.length; x++){
		    	if (detail_inputs[x].value==""){
		        	detail_flag=1;
		        }
		    }

		    if(detail_flag==1){
		    	toastr['error']("Please fill-out all required* fields in Details Tab!", "ABAS says:");
				return false;
		    }

		    //var final_inputs = $(".table_output input").not(':hidden').filter('[required]');
		    var final_inputs = $(".table_output input").filter('[required]');
			var final_flag=1;
		    for(var i = 0; i < final_inputs.length; i++){
		        if (final_inputs[i].value==""){
		        	final_flag=1;
		        }else{
		        	final_flag=0;
		        }
		    }

		    if(final_flag==1){
		    	toastr['error']("Please fill-out all required* fields in Final Output and Variance Tab!", "ABAS says:");
				return false;
		    }

		}else{
			detail_flag=0;
			final_flag=0;
		}

	    if(detail_flag==0 && final_flag==0) {

	    	$('body').addClass('is-loading');
			$('#modalDialog').modal('toggle');

			$('#out_turn_summary_form').submit();
			return true;
		}

	 }else if(service_type=="Trucking" || service_type=="Handling"){

	    var breakdown_inputs = $("#OSBreakdown input").filter('[required]');
	    var breakdown_count = $(".tbl_row");

	    var upload = $('input[type=file]').val();

	    if(SO_isChecked==true){
	    	if(upload==""){

			    var breakdown_flag=0;
			    for(var i = 0; i < breakdown_inputs.length; i++){
			        if (breakdown_inputs[i].value==""){
			        	breakdown_flag=1;
			        }
			    }
			    if(breakdown_flag==1){
			    	toastr['error']("Please fill-out all required* fields in Breakdown of Deliveries!", "ABAS says:");
					return false;
			    }

		   		var breakdown_count_flag=0;

			    <?php if(!isset($OS)){?>
			 		if(breakdown_count.length == 1){
			    		breakdown_count_flag=1;
			 		}
			 	<?php } ?>

			 	<?php if(isset($OS)){?>
			 		if(breakdown_count.length < 1){
			    		breakdown_count_flag=1;
			 		}
			 	<?php } ?>

			    if(breakdown_count_flag==1){
			    	toastr['error']("Please add Breakdown of Deliveries!", "ABAS says:");
					return false;
			    }

		    }else{
		    	breakdown_flag=0;
		    	breakdown_count_flag=0;
		    }

		}else{
	    	breakdown_flag=0;
	    	breakdown_count_flag=0;
	    }

	    if(breakdown_flag==0 && breakdown_count_flag==0) {

	       $('body').addClass('is-loading');
		   $('#modalDialog').modal('toggle');

     	  $('#out_turn_summary_form').submit();
		  return true;
		}

	}else if(service_type=="Lighterage" || service_type=="Time Charter" || service_type=="Towing"){

	    var detail_selects = $("#OSDetails").find('.im_visible select').filter('[required]');
	    var detail_inputs = $("#OSDetails").find('.im_visible input').filter('[required]');

		var detail_flag=0;

		if(SO_isChecked==true){
		    for(var i = 0; i < detail_selects.length; i++){
		        if (detail_selects[i].value==""){
		        	detail_flag=1;
		         }
		    }
		    for(var x = 0; x < detail_inputs.length; x++){
		    	if (detail_inputs[x].value==""){
		        	detail_flag=1;
		        }
		    }

		    if(detail_flag==1){
		    	toastr['error']("Please fill-out all required* fields in Details Tab!", "ABAS says:");
				return false;
		    }
		}else{
			detail_flag=0;
		}

	    if(detail_flag==0) {

	    	$('body').addClass('is-loading');
			$('#modalDialog').modal('toggle');

			$('#out_turn_summary_form').submit();
			return true;
		}

	 }

}


</script>