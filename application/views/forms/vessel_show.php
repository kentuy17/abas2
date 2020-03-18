<?php
	$id = '';
	$company = '';
	$photo_path = '';
	$name = '';
	$ex_name = '';
	$price_sold = '';
	$price_paid = '';
	$length_loa = '';
	$length_lr = '';
	$length_lbp	 = '';							
	$breadth = '';
	$depth	 = '';								
	$draft = '';
	$year_built = '';
	$builder = '';
	$place_built = '';
	$jap_dwt = '';
	$bale_capacity = '';
	$grain_capacity = '';
	$hatch_size = '';
	$hatch_type = '';
	$phil_dwt = '';
	$gross_tonnage = '';
	$net_tonnage = '';
	$main_engine = '';
	$main_engine_rating = '';
	$main_engine_actual_rating = '';
	$model_serial_no = '';
	$estimated_fuel_consumption = '';
	$bow_thrusters = '';
	$propeller = '';
	$call_sign = '';
	$imo_no = '';
	$monthly_amortization_no_of_months = '';
	$hm_agreed_value = '';
	$maiden_voyage = '';
	//var_dump($maiden_voyage);exit;
	$sound_value = '';
	$market_value = '';
	$status = '';
	$vessel_account_num = '';
	$bank_account_name = '';
	$date_acquired = '';
   
   if(isset($vessel)){
   		//var_dump($item[0]['id']);exit;
   		$id = $vessel[0]['id'];
		$company = $vessel[0]['company'];
		$photo_path = $vessel[0]['photo_path'];
		//var_dump($photo_path);exit;
		$name = $vessel[0]['name'];
		$ex_name = $vessel[0]['ex_name'];
		$price_sold = $vessel[0]['price_sold'];
		$price_paid = $vessel[0]['price_paid'];
		$length_loa = $vessel[0]['length_loa'];
		$length_lr = $vessel[0]['length_lr'];
		$length_lbp	 = $vessel[0]['length_lbp'];							
		$breadth = $vessel[0]['breadth'];
		$depth	 = $vessel[0]['depth'];								
		$draft = $vessel[0]['draft'];
		$year_built = $vessel[0]['year_built'];
		$builder = $vessel[0]['builder'];
		$place_built = $vessel[0]['place_built'];
		$jap_dwt = $vessel[0]['jap_dwt'];
		$bale_capacity = $vessel[0]['bale_capacity'];
		$grain_capacity = $vessel[0]['grain_capacity'];
		$hatch_size = $vessel[0]['hatch_size'];
		$hatch_type = $vessel[0]['hatch_type'];
		$phil_dwt = $vessel[0]['phil_dwt'];
		$gross_tonnage = $vessel[0]['gross_tonnage'];
		$net_tonnage = $vessel[0]['net_tonnage'];
		$main_engine = $vessel[0]['main_engine'];
		$main_engine_rating = $vessel[0]['main_engine_rating'];
		$main_engine_actual_rating = $vessel[0]['main_engine_actual_rating'];
		$model_serial_no = $vessel[0]['model_serial_no'];
		$estimated_fuel_consumption = $vessel[0]['estimated_fuel_consumption'];
		$bow_thrusters = $vessel[0]['bow_thrusters'];
		$propeller = $vessel[0]['propeller'];
		$call_sign = $vessel[0]['call_sign'];
		$imo_no = $vessel[0]['imo_no'];
		$monthly_amortization_no_of_months = $vessel[0]['monthly_amortization_no_of_months'];
		$hm_agreed_value = $vessel[0]['hm_agreed_value'];
		//$maiden_voyage = date("Y-m-d",strtotime($vessel[0]['date_acquired']));
		$sound_value = $vessel[0]['sound_value'];
		$market_value = $vessel[0]['market_value'];
		$status = $vessel[0]['status'];
		$vessel_account_num = $vessel[0]['vessel_account_num'];
		$bank_account_name = $vessel[0]['bank_account_name'];
		$date_acquired = date("Y-m-d",strtotime($vessel[0]['date_acquired']));
		$maiden_voyage = date("Y-m-d",strtotime($vessel[0]['maiden_voyage']));
		//var_dump($maiden_voyage);exit;
		
		//var_dump($date_acquired);exit;
	  }
   ?>

<style>
   .modal-dialog{width: 400px !important;height: 300px;}
   .form-control.input-sm{height:26px !important;}
   .modal-content{right: 190px !important;}
</style>
<form class="form-horizontal" role="form" action="<?php echo HTTP_PATH.'forms/add_vessel'; ?>" method="post" style="height: 400px;">
<?php echo $this->Mmm->createCSRF() ?>
   <input type="hidden" name="stat" value="1">
   <input type="hidden" name="id" value="<?php echo $id;  ?>">
   <!--- waybill activity --->
   <div style="width:250px; float:left; margin-left:0px;">
      <div class="container">
         <div class="panel panel-primary" style="font-size:12px; width:804px; height: 865px">
            <div class="panel-heading" role="tab" id="headingOne">
               <strong>Vessel Information&nbsp;</strong>
            </div>
            <div class="panel-body" role="tab">
			<table class="table table-striped table-bordered">
			
			<tr>
				<td><b>Company</b></td>
				<td><?php echo $company; ?></td>
				<td><b>Photo Path</b></td>
				<td><?php echo $photo_path; ?></td>
			</tr>
			<tr>
				<td><b>Name</b></td>
				<td><?php echo $name; ?></td>
				<td><b>Ex Name</b></td>
				<td><?php echo $ex_name; ?></td>
			</tr>
			<tr>
				<td><b>Price Sold</b></td>
				<td><?php echo $price_sold; ?></td>
				<td><b>Price Paid</b></td>
				<td><?php echo $price_paid; ?></td>
			</tr>
			<tr>
				<td><b>Length loa</b></td>
				<td><?php echo $length_loa; ?></td>
				<td><b>Length lr</b></td>
				<td><?php echo $length_lr; ?></td>
			</tr>
			<tr>
				<td><b>Length lbp</b></td>
				<td><?php echo $length_lbp; ?></td>
				<td><b>Breadth</b></td>
				<td><?php echo $breadth; ?></td>
			</tr>
			<tr>
				<td><b>Depth</b></td>
				<td><?php echo $depth; ?></td>
				<td><b>Draft</b></td>
				<td><?php echo $draft; ?></td>
			</tr>
			<tr>
				<td><b>Year Build</b></td>
				<td><?php echo $year_built; ?></td>
				<td><b>Builder</b></td>
				<td><?php echo $builder; ?></td>
			</tr>
			<tr>
				<td><b>Place Built</b></td>
				<td><?php echo $place_built; ?></td>
				<td><b>Jap DWT</b></td>
				<td><?php echo $jap_dwt; ?></td>
			</tr>
			<tr>
				<td><b>Bale Capacity</b></td>
				<td><?php echo $bale_capacity; ?></td>
				<td><b>Grain Capacity</b></td>
				<td><?php echo $grain_capacity; ?></td>
			</tr>
			<tr>
				<td><b>Hatch Size</b></td>
				<td><?php echo $hatch_size; ?></td>
				<td><b>Hatch Type</b></td>
				<td><?php echo $hatch_type; ?></td>
			</tr>
			<tr>
				<td><b>Phil DWT</b></td>
				<td><?php echo $phil_dwt; ?></td>
				<td><b>Gross Tonnage</b></td>
				<td><?php echo $gross_tonnage; ?></td>
			</tr>
			<tr>
				<td><b>Net Tonnage</b></td>
				<td><?php echo $net_tonnage; ?></td>
				<td><b>Main Engine</b></td>
				<td><?php echo $main_engine; ?></td>
			</tr>
			<tr>
				<td><b>Main Engine Rating</b></td>
				<td><?php echo $main_engine_rating; ?></td>
				<td><b>Main Engine Actual Rating</b></td>
				<td><?php echo $main_engine_actual_rating; ?></td>
			</tr>
			<tr>
				<td><b>Model Serial No</b></td>
				<td><?php echo $model_serial_no; ?></td>
				<td><b>Estimated Fuel Consumption</b></td>
				<td><?php echo $estimated_fuel_consumption; ?></td>
			</tr>
			<tr>
				<td><b>Bow Thrusters</b></td>
				<td><?php echo $bow_thrusters; ?></td>
				<td><b>Propeller</b></td>
				<td><?php echo $propeller; ?></td>
			</tr>
			<tr>
				<td><b>Call Sign</b></td>
				<td><?php echo $call_sign; ?></td>
				<td><b>IMO No</b></td>
				<td><?php echo $imo_no; ?></td>
			</tr>
			<tr>
				<td><b>Monthly Amortization No of Months</b></td>
				<td><?php echo $monthly_amortization_no_of_months; ?></td>
				<td><b>Hm Agreed Value</b></td>
				<td><?php echo $hm_agreed_value; ?></td>
			</tr>
			<tr>
				<td><b>Maiden Voyage</b></td>
				<td><?php echo date('F j, Y', strtotime($maiden_voyage)); ?></td>
				<td><b>Sound Value</b></td>
				<td><?php echo $sound_value; ?></td>
			</tr>
			<tr>
				<td><b>Market Value</b></td>
				<td><?php echo $market_value; ?></td>
				<td><b>Status</b></td>
				<td><?php echo $status; ?></td>
			</tr>
			<tr>
				<td><b>Vessel Account No</b></td>
				<td><?php echo $vessel_account_num; ?></td>
				<td><b>Bank Account Name</b></td>
				<td><?php echo $bank_account_name; ?></td>
			</tr>
			<tr>
				<td><b>Date Acquired</b></td>
				<td><?php  echo date('F j, Y', strtotime($date_acquired)); ?></td>
				<td></td>
				<td></td>
			</tr>
			

</table>			
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default btn-xs" data-dismiss="modal" onclick="myFunction()">Close</button>
              
            </div>
         </div>
      </div>
   </div>
</form>