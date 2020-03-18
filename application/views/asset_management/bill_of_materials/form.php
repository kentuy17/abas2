<?php

$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $option) {
		if(isset($BOM)){
			$company_options	.=	"<option ".($BOM['company_id']==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
		}else{
			if($option->name!="Avega Bros. Integrated Shipping Corp. (Staff)"){
				$company_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
			}
		}
	}
	unset($option);
}

$tasks = '<div class="row item-row-tasks command-row-tasks">
			<hr>
				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Task No.*</label>
					<input type="number" min="1" id="tasks_no[]" name="tasks_no[]" class="form-control task_no" value="1" style="text-align:center" required readonly>
				</div>
				<div class="col-md-5 col-sm-5 col-xs-12">
					<label>Scope of Work*</label>
					<input type="text" id="scope_of_work[]" name="scope_of_work[]" class="form-control" required>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Total Area (sqm.)*</label>
					<input type="number" min="0" id="total_area[]" name="total_area[]" class="form-control" required>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>ETC (No. of Days)*</label>
					<input type="number" min="1" id="estimated_time_to_complete[]" name="estimated_time_to_complete[]" class="form-control" required>
				</div>
					<a class="btn-remove-row-tasks btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>
	        </div>';

$tasks_append= trim(preg_replace('/\s+/',' ', $tasks));

$labor = '<div class="row item-row-labor command-row-labor"><hr>

				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Quantity*</label>
					<input type="number" min="1" id="labor_quantity[]" name="labor_quantity[]" class="form-control labor_quantity" required>
				</div>

				<div class="col-md-9 col-sm-9 col-xs-12">
					<label>Job Description*</label>
					<input type="text" id="labor_job_description[]" name="labor_job_description[]" class="form-control" required>
				</div>

				<a class="btn-remove-row-labor btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>

				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Task No(s).*</label>
					<input type="text" id="labor_task_no[]" name="labor_task_no[]" class="form-control" required>
				</div>
				
				<div class="col-md-3 col-sm-3 col-xs-12">
					<label>Days Needed*</label>
					<input type="number" min="1" id="labor_days_needed[]" name="labor_days_needed[]" class="form-control labor_days_needed" required>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<label>Rate per Day*</label>
					<input type="number" style="text-align:right" min="1" id="labor_rate_per_day[]" name="labor_rate_per_day[]" class="form-control labor_rate_per_day" required>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<label>Total Cost</label>
					<input type="number" style="text-align:right" id="labor_total_cost[]" name="labor_total_cost[]" class="form-control labor_total_cost" readonly>
				</div>
			</div>';

$labor_append= trim(preg_replace('/\s+/',' ', $labor));

$materials = '<div class="row item-row-materials command-row-materials">
	            <hr>

	            <div class="col-md-2 col-sm-2 col-xs-12">
					<label>Quantity Needed*</label>
					<input type="number" min="0" id="item_quantity[]" name="item_quantity[]" class="form-control item_quantity">
				</div>

				<div class="col-md-5 col-sm-5 col-xs-12">
					<label>Item Name & Description*</label>
					<input type="text" id="item_description[]" name="item_description[]" class="form-control item_code" required>
					<input type="hidden" id="item_id[]" name="item_id[]" class="form-control item_id" value="0" readonly/>
					<input type="hidden" id="item_unit[]" name="item_unit[]" class="form-control item_unit" value="-" readonly/>
					<input type="hidden" id="wh_qty[]" name="wh_qty[]" class="form-control wh_qty" value="0" readonly/>
					<input type="hidden" id="wh_uc[]" name="wh_uc[]" class="form-control wh_uc" value="0" readonly/>
				</div>
				
				
				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Size*</label>
					<input type="text" min="0" id="item_size[]" name="item_size[]" class="form-control item_size">
				</div>

				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Unit Measurement*</label>
					<input type="text" min="0" id="item_unit_measurement[]" name="item_unit_measurement[]" class="form-control item_unit_measurement">
				</div>
				
					<a class="btn-remove-row-materials btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>
				
				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Warehouse Qty</label>
					<input type="number" id="warehouse_quantity[]" name="warehouse_quantity[]" class="form-control warehouse_qty" value="0" readonly>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Warehouse Unit Cost</label>
					<input type="number" style="text-align:right" id="warehouse_unit_cost[]" name="warehouse_unit_cost[]" class="form-control warehouse_uc" value="0" readonly>
				</div>

				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Purchase Qty</label>
					<input type="number" min="0" id="purchase_quantity[]" name="purchase_quantity[]" class="form-control purchase_quantity" value="0" readonly>
				</div>

				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Purchase Unit Cost*</label>
					<input type="number" style="text-align:right" min="0" id="item_unit_cost[]" name="item_unit_cost[]" class="form-control item_unit_cost" value="0">
				</div>

				<div class="col-md-3 col-sm-3 col-xs-12">
					<label>Total Cost</label>
					<input type="number" style="text-align:right" min="0" id="item_total_cost[]" name="item_total_cost[]" class="form-control item_total_cost" readonly>
				</div>
				
			</div>';

$materials_append= trim(preg_replace('/\s+/',' ', $materials));

$tools = '<div class="row item-row-tools command-row-tools">
			<hr>

			<div class="col-md-2 col-sm-2 col-xs-12">
				<label>Quantity*</label>
				<input type="number" min="0" id="tool_quantity[]" name="tool_quantity[]" class="form-control tool_quantity" required>
			</div>

			<div class="col-md-7 col-sm-7 col-xs-12">
				<label>Particulars*</label>
				<input type="text" id="tool_name[]" name="tool_name[]" class="form-control tool_name" required>
			</div>
			
			<div class="col-md-2 col-sm-2 col-xs-12">
				<label>Est. Days Used*</label>
				<input type="number" min="1" id="tool_estimated_days_used[]" name="tool_estimated_days_used[]" class="form-control" required>
			</div>
			
				<a class="btn-remove-row-tools btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>
			
		  </div>';

$tools_append= trim(preg_replace('/\s+/',' ', $tools));

$disabled = "";
$tasks_edit = "";
$labor_edit = "";
$materials_edit = "";
$tools_edit = "";
$start_date_of_repair="";
$evaluation = "";
$remarks = "";
$total_cost =0;
$total_labor_cost =0;
$total_material_cost =0;

if(isset($BOM)){

	$evaluation = $this->Asset_Management_model->getEvaluationFormNumber($BOM['bom_type'],$BOM['company_id'],$BOM['evaluation_id']);

	$for = $BOM['bom_type'];
	$evaluation_form_no = "<option value='".$BOM['evaluation_id']."' selected>".$evaluation->maintenance_form . $evaluation->control_number. " (" . $evaluation->asset_name.")</option>";
	$start_date_of_repair = "value='".$BOM['start_date_of_repair']."'";
	$disabled = "disabled";
	$remarks = $BOM['remarks'];

	foreach($BOM_tasks as $row){
		$tasks_edit .= '<div class="row item-row-tasks command-row-tasks">
							<hr>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<label>Task No.*</label>
									<input type="number" min="1" id="tasks_no[]" name="tasks_no[]" class="form-control task_no" style="text-align:center" required value="'.$row->task_number.'" readonly>
								</div>
								<div class="col-md-5 col-sm-5 col-xs-12">
									<label>Scope of Work*</label>
									<input type="text" id="scope_of_work[]" name="scope_of_work[]" class="form-control" value="'.$row->scope_of_work.'" required>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<label>Total Area (sqm.)*</label>
									<input type="number" min="0" id="total_area[]" name="total_area[]" class="form-control" value="'.$row->total_area.'" required>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<label>ETC (No. of Days)*</label>
									<input type="number" min="1" id="estimated_time_to_complete[]" name="estimated_time_to_complete[]" class="form-control" value="'.$row->estimated_time_to_complete.'" required>
								</div>
									<a class="btn-remove-row-tasks btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>
					        </div>';
	}


	foreach($BOM_labor as $row){

		$total_cost = ($row->rate_per_day*$row->days_needed)*$row->quantity;
		$total_labor_cost = $total_labor_cost + $total_cost;

		$labor_edit .= '<div class="row item-row-labor command-row-labor"><hr>

					<div class="col-md-2 col-sm-2 col-xs-12">
						<label>Quantity*</label>
						<input type="number" min="1" id="labor_quantity[]" name="labor_quantity[]" class="form-control labor_quantity" value="'.$row->quantity.'" required>
					</div>

					<div class="col-md-9 col-sm-9 col-xs-12">
						<label>Job Description*</label>
						<input type="text" id="labor_job_description[]" name="labor_job_description[]" class="form-control" value="'.$row->job_description.'" required>
					</div>

					<a class="btn-remove-row-labor btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>

					<div class="col-md-2 col-sm-2 col-xs-12">
						<label>Task No(s).*</label>
						<input type="text" id="labor_task_no[]" name="labor_task_no[]" class="form-control" value="'.$row->task_numbers.'" required>
					</div>
					
					<div class="col-md-3 col-sm-3 col-xs-12">
						<label>Days Needed*</label>
						<input type="number" min="1" id="labor_days_needed[]" name="labor_days_needed[]" class="form-control labor_days_needed" value="'.$row->days_needed.'" required>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12">
						<label>Rate per Day*</label>
						<input type="number" style="text-align:right" min="1" id="labor_rate_per_day[]" name="labor_rate_per_day[]" class="form-control labor_rate_per_day" value="'.$row->rate_per_day.'" required>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12">
						<label>Total Cost</label>
						<input type="number" style="text-align:right" id="labor_total_cost[]" name="labor_total_cost[]" class="form-control labor_total_cost" value="'.number_format($total_cost,2,'.','').'" readonly>
					</div>
				</div>';
	}

	foreach($BOM_supplies as $row){

		$quantity_for_purchase = $row->quantity-$row->warehouse_quantity;
					
		if($quantity_for_purchase<=0){
			$total_cost = $row->quantity * $row->warehouse_unit_cost;
		}else{
			$calc_wh_cost = $row->warehouse_quantity * $row->warehouse_unit_cost;
			$calc_ps_cost = $row->unit_cost * $quantity_for_purchase;
			$total_cost =  $calc_wh_cost  + $calc_ps_cost;
		}

		$total_material_cost = $total_material_cost + $total_cost;

		$materials_edit .= '<div class="row item-row-materials command-row-materials">
		            <hr>

		            <div class="col-md-2 col-sm-2 col-xs-12">
						<label>Quantity Needed*</label>
						<input type="number" min="0" id="item_quantity[]" name="item_quantity[]" class="form-control item_quantity" value="'.$row->quantity.'">
					</div>

					<div class="col-md-5 col-sm-5 col-xs-12">
						<label>Item Name & Description*</label>
						<input type="text" id="item_description[]" name="item_description[]" class="form-control item_code" value="'.$row->item_description.'" required>
						<input type="hidden" id="item_id[]" name="item_id[]" class="form-control item_id" value="'.$row->item_id.'" readonly/>
						<input type="hidden" id="item_unit[]" name="item_unit[]" class="form-control item_unit" value="'.$row->item_unit_measurement.'"readonly/>
						<input type="hidden" id="wh_qty[]" name="wh_qty[]" class="form-control wh_qty" value="'.$row->warehouse_quantity.'" readonly/>
						<input type="hidden" id="wh_uc[]" name="wh_uc[]" class="form-control wh_uc" value="'.$row->warehouse_unit_cost.'" readonly/>
					</div>
					
					
					<div class="col-md-2 col-sm-2 col-xs-12">
						<label>Size*</label>
						<input type="text" min="0" id="item_size[]" name="item_size[]" class="form-control item_size" value="'.$row->item_size.'">
					</div>

					<div class="col-md-2 col-sm-2 col-xs-12">
						<label>Unit Measurement*</label>
						<input type="text" min="0" id="item_unit_measurement[]" name="item_unit_measurement[]" class="form-control item_unit_measurement" value="'.$row->item_unit_measurement.'">
					</div>
					
						<a class="btn-remove-row-materials btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>
					
					<div class="col-md-2 col-sm-2 col-xs-12">
						<label>Warehouse Qty</label>
						<input type="number" id="warehouse_quantity[]" name="warehouse_quantity[]" class="form-control warehouse_qty" value="'.$row->warehouse_quantity.'" readonly>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12">
						<label>Warehouse Unit Cost</label>
						<input type="number" style="text-align:right" id="warehouse_unit_cost[]" name="warehouse_unit_cost[]" class="form-control warehouse_uc" value="'.$row->warehouse_unit_cost.'" readonly>
					</div>

					<div class="col-md-2 col-sm-2 col-xs-12">
						<label>Purchase Qty</label>
						<input type="number" min="0" id="purchase_quantity[]" name="purchase_quantity[]" class="form-control purchase_quantity" value="'.$row->purchase_quantity.'" readonly>
					</div>

					<div class="col-md-2 col-sm-2 col-xs-12">
						<label>Purchase Unit Cost*</label>
						<input type="number" style="text-align:right" min="0" id="item_unit_cost[]" name="item_unit_cost[]" class="form-control item_unit_cost" value="'.$row->unit_cost.'">
					</div>

					<div class="col-md-3 col-sm-3 col-xs-12">
						<label>Total Cost</label>
						<input type="number" style="text-align:right" min="0" id="item_total_cost[]" name="item_total_cost[]" class="form-control item_total_cost" value="'.number_format($total_cost,2,'.','').'" readonly>
					</div>
					
				</div>';
	}

	foreach($BOM_tools as $row){

		$tools_edit .= '<div class="row item-row-tools command-row-tools">
				<hr>

				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Quantity*</label>
					<input type="number" min="0" id="tool_quantity[]" name="tool_quantity[]" class="form-control tool_quantity" value="'.$row->quantity.'" required>
				</div>

				<div class="col-md-7 col-sm-7 col-xs-12">
					<label>Particulars*</label>
					<input type="text" id="tool_name[]" name="tool_name[]" class="form-control tool_name" value="'.$row->tool_name.'" required>
				</div>
				
				<div class="col-md-2 col-sm-2 col-xs-12">
					<label>Est. Days Used*</label>
					<input type="number" min="1" id="tool_estimated_days_used[]" name="tool_estimated_days_used[]" class="form-control" value="'.$row->days_used.'" required>
				</div>
				
					<a class="btn-remove-row-tools btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>
				
			  </div>';
	}

}

?>


<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text>
					<?php 
						if(!isset($BOM)){
							echo "Add Bill Of Materials";
						}else{
							echo "Edit Bill Of Materials";
						}
					?>
				</text>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
</div>

	<?php
		// CI Form 
		$attributes = array('id'=>'bill_of_materials_form','role'=>'form');
		if(!isset($BOM)){
			$action = '/add/BOM';
		}else{
			$action = '/edit/BOM/'.$BOM['id'];
		}
		echo form_open_multipart(HTTP_PATH.CONTROLLER.$action,$attributes);
		echo $this->Mmm->createCSRF();
	?>
		
		<div class='panel-body panel'>
			

				<div class='panel-group' id='BOMFormDivider' role='tablist' aria-multiselectable='true'>

					<div class='panel panel-info'>
						<div class='panel-heading' role='tab' id='general'>	
								<a role='button' data-toggle='collapse' data-parent='#BOMFormDivider' href='#BOMGeneral' aria-expanded='true' aria-controls='BOMGeneral'>
								General Information
								<span class='glyphicon glyphicon-chevron-down pull-right'></span>
								</a>
						</div>

						<div id='BOMGeneral' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='BOMGeneral'>

							<div class='panel-body'>

								<!--<div class='col-md-3 col-sm-3 col-xs-12'>
									<label>Control Number</label>
									<input type='text' id='control_number' name='control_number' style='text-align:center' class='form-control' required readonly>
								</div>-->
								
								<div class='col-md-9 col-sm-9 col-xs-12'>
									<label>Company*</label>
									<select type='text' id='company' name='company' class='form-control' <?php echo $disabled;?> required>
										<?php echo $company_options;?>
									</select>
								</div>
								<div class='col-md-3 col-sm-3 col-xs-12'>
									<label>Type</label>
									<input type='text' id='maintenance_type' name='maintenance_type' style='text-align:center' class='form-control' value='<?php echo $for;?>' required readonly>
								</div>
								<div class='col-md-9 col-sm-9 col-xs-12'>
									<label>Evaluation Form No.*</label>
									<select type='text' id='evaluation_form_no' name='evaluation_form_no' class='form-control' <?php echo $disabled;?> required>
										<option value=''>Select</option>
										<?php echo $evaluation_form_no;?>
									</select>
								</div>
								<div class='col-md-3 col-sm-3 col-xs-12'>
									<label>Starting Date of Repair*</label>
									<input type='date' id='start_date_of_repair' name='start_date_of_repair' class='form-control' <?php echo $start_date_of_repair;?> required>
								</div>
								
								<div class='col-md-12 col-sm-12 col-xs-12'>
									<label>Asset For Repair:</label>
										<table id='for_repair_table' class='table table-striped'>
											<?php 

												
												if(!isset($BOM)){
													echo "<tbody>
															<tr>
																<td>...</td>
															</tr>
														</tbody>";
												}elseif(isset($BOM)){
													if(isset($vessel)){
														echo "<tbody>
															<tr>
																<th>Vessel Name</th>
																<th>Dry-Docking Date</th>
																<th>Dry-Docking Location</th>
																<th>LOA</th>
																<th>Breadth</th>
																<th>Depth</th>
																<th>GT</th>
															</tr>";
														echo "<tr>
																<td>".$vessel['vessel_name']."</td>
																<td>".$vessel['dry_docking_date']."</td>
																<td>".$vessel['dry_docking_location']."</td>
																<td>".$vessel['length_loa']."</td>
																<td>".$vessel['breadth']."</td>
																<td>".$vessel['depth']."</td>
																<td>".$vessel['gross_tonnage']."</td>
															</tr>
															</tbody>";
													}elseif(isset($truck)){
														echo "<tbody>
															<tr>
																<th>Truck Plate-No.</th>
																<th>Make</th>
																<th>Model</th>
																<th>Engine No.</th>
																<th>Chasis No.</th>
																<th>Type</th>
															</tr>";
														echo "<tr>
																<td>".$truck['plate_number']."</td>
																<td>".$truck['make']."</td>
																<td>".$truck['model']."</td>
																<td>".$truck['engine_number']."</td>
																<td>".$truck['chassis_number']."</td>
																<td>".$truck['type']."</td>
															</tr>
															</tbody>";
													}

												} 

											?>
										</table>			
								</div>

							</div>

						</div>
					</div>
				

					<div class='panel panel-info'>

						<div class='panel-heading' role='tab' id='tasks'>
								<a class='collapsed' role='button' data-toggle='collapse' data-parent='#BOMFormDivider' href='#BOMTasks' aria-expanded='false' aria-controls='BOMTasks'>
								Tasks Description
								<span class='glyphicon glyphicon-chevron-down pull-right'></span>
								</a>
				
						</div>

						<div id='BOMTasks' class='panel-collapse collapse' role='tabpanel' aria-labelledby='BOMTasks'>
							
							<div class='pull-right' style='float:left; margin-top:5px; margin-left:5px'>                          
								<a id='btn_add_row_tasks' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>								
								<a id='btn_remove_row_tasks' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
							</div>
							
							<div class='panel-body item-row-container-tasks'>
								<?php 
									if(isset($BOM_tasks)){
										echo $tasks_edit;
									}else{
										echo $tasks;
									}
								?>
							</div>

						</div>
					</div>

					<div class='panel panel-info'>

						<div class='panel-heading' role='tab' id='requirements'>
								<a class='collapsed' role='button' data-toggle='collapse' data-parent='#BOMFormDivider' href='#BOMRequirements' aria-expanded='false' aria-controls='BOMRequirements'>
								Requirements
								<span class='glyphicon glyphicon-chevron-down pull-right'></span>
								</a>
						</div>

						<div id='BOMRequirements' class='panel-collapse collapse' role='tabpanel' aria-labelledby='BOMRequirements'>
							
							<div class='panel-body'>
				                <div role="tabpanel" data-example-id="togglable-tabs">
				                  <ul id="tab_list" class="nav nav-tabs bar_tabs" role="tablist">
				                    <li role="presentation" class="active"><a href="#tab_labor" id="labor_tab" role="tab" data-toggle="tab" aria-expanded="true"><b>Labor</b></a>
				                    </li>
				                    <li role="presentation" class=""><a href="#tab_materials" role="tab" id="materials_tab" data-toggle="tab" aria-expanded="false"><b>Materials and Supplies</b></a>
				                    </li>
				                    <li role="presentation" class=""><a href="#tab_tools" role="tab" id="tools_tab" data-toggle="tab" aria-expanded="false"><b>Tools and Equipment</b></a>
				                    </li>
				                  </ul>

				                  <div id="tab_contents" class="tab-content">
				                    <div role="tabpanel" class="tab-pane fade active in" id="tab_labor" aria-labelledby="labor_tab">
				                      	<div class='pull-right' style="float:left; margin-top:5px; margin-left:5px">                          
												<a id="btn_add_row_labor" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>								
												<a id="btn_remove_row_labor" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>

										</div>

										<br>
										<div class="item-row-container-labor">
											<?php 
												if(isset($BOM_labor)){
													echo $labor_edit;
												}else{
													echo $labor;
												}
											?>
										</div>
										
										<hr>
										<div class='col-sm-3 col-m-3 pull-right'>
											<input type="text" id="labor_grand_total" name="labor_grand_total" class="form-control labor_grand_total" style="text-align:right; font-size:18px;" value='PHP<?php echo number_format($total_labor_cost,2,".",",");?>' readonly>
										</div>
										<label style="margin-top:8px;" class="pull-right">Total Labor Cost</label>
									
				                    </div>
				                    <div role="tabpanel" class="tab-pane fade" id="tab_materials" aria-labelledby="materials_tab">
				                    	<div class='pull-right' style="float:left; margin-top:5px; margin-left:5px">                          
												<a id="btn_add_row_materials" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>								
												<a id="btn_remove_row_materials" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
										</div>

											
										<br>
				                      	<div class="item-row-container-materials">
											<?php 
												if(isset($BOM_supplies)){
													echo $materials_edit;
												}else{
													echo $materials;
												}
											?>
										</div>

										<hr>
										<div class='col-sm-3 col-m-3 pull-right'>
											<input type="text" id="materials_grand_total" name="materials_grand_total" class="form-control materials_grand_total" style="text-align:right; font-size:18px;" value='PHP<?php echo number_format($total_material_cost,2,".",",");?>' readonly>
										</div>
										<label style="margin-top:8px;" class="pull-right">Total Material Cost</label>

									
				                    </div>
				                    <div role="tabpanel" class="tab-pane fade" id="tab_tools" aria-labelledby="tools_tab">
				                    	<div class='pull-right' style="float:left; margin-top:5px; margin-left:5px">                          
												<a id="btn_add_row_tools" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>								
												<a id="btn_remove_row_tools" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
										</div>
										<br>
				                      	<div class="item-row-container-tools">
											<?php 
												if(isset($BOM_tools)){
													echo $tools_edit;
												}else{
													echo $tools;
												}
											?>
										</div>
				                    </div>
				                  </div>
				                </div>
				           </div>
				        </div>
					</div>


					<div class='panel panel-info'>

						<div class='panel-heading' role='tab' id='remarks'>
								<a class='collapsed' role='button' data-toggle='collapse' data-parent='#BOMFormDivider' href='#BOMRemarks' aria-expanded='false' aria-controls='BOMRemarks'>
								Notes/Others/Remarks
								<span class='glyphicon glyphicon-chevron-down pull-right'></span>
								</a>

						</div>

						<div id='BOMRemarks' class='panel-collapse collapse' role='tabpanel' aria-labelledby='BOMRemarks'>
							
							<div class='panel-body'>
								<div class='col-md-12 col-sm-12 col-xs-12'>
									<textarea id='remarks' name='remarks' class='form-control' rows="5"><?php echo $remarks;?></textarea>
								</div>
							</div>

						</div>
					</div>
				</div>
					
		</div>

		<div class='col-xs-12 col-sm-12 col-lg-12'>
			<span class='pull-right'>
				<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: validateForm()' />
				<input type='button' class='btn btn-danger btn-m' value='Discard' data-dismiss='modal'>
			</span>
		</div>
		<br><br><br>
	</form>
	
				
</body>
</html>

<script type='text/javascript'>
	
	$('#btn_remove_row_tasks').click(function(){
		$('.item-row-tasks:last').remove();
	});
	$(document).on('click', '.btn-remove-row-tasks', function() {
		$(this).parent().remove();
		var ctr = 0;
		$("div.command-row-tasks").each(function() {
			ctr = ctr + 1;
			$('.task_no', this).val(ctr);
		});
	});
	$('#btn_add_row_tasks').click(function(){
		$('.item-row-container-tasks').append('<?php echo $tasks_append; ?>');
		var ctr = 0;
		$("div.command-row-tasks").each(function() {
			ctr = ctr + 1;
			$('.task_no', this).val(ctr);
		});
	});

	$('#btn_remove_row_labor').click(function(){
		$('.item-row-labor:last').remove();
	});
	$(document).on('click', '.btn-remove-row-labor', function() {
		$(this).parent().remove();
	});
	$('#btn_add_row_labor').click(function(){
		$('.item-row-container-labor').append('<?php echo $labor_append; ?>');
	});

	$('#btn_remove_row_materials').click(function(){
		$('.item-row-materials:last').remove();
	});
	$(document).on('click', '.btn-remove-row-materials', function() {
		$(this).parent().remove();
	});
	$('#btn_add_row_materials').click(function(){
		$('.item-row-container-materials').append('<?php echo $materials_append; ?>');
	});

	$('#btn_remove_row_tools').click(function(){
		$('.item-row-tools:last').remove();
	});
	$(document).on('click', '.btn-remove-row-tools', function() {
		$(this).parent().remove();
	});
	$('#btn_add_row_tools').click(function(){
		$('.item-row-container-tools').append('<?php echo $tools_append; ?>');
	});

$(document).ready(function() {


		
	  $(document).on('keyup', "div.command-row-labor input", multInputsLabor);

	  		function multInputsLabor(){

	  			$('.labor_rate_per_day,.labor_total_cost').on('change', function(){
				    $(this).val(parseFloat($(this).val()).toFixed(2));
				});
				
				$("div.command-row-labor").each(function() {
					var quantity = $('.labor_quantity', this).val();
					var days_needed = $('.labor_days_needed', this).val();
					var rate_per_day = $('.labor_rate_per_day', this).val();
					var rate_x_day = parseFloat((days_needed*1)*(rate_per_day*1));
					var total = parseFloat((rate_x_day*1)*(quantity*1)).toFixed(2);

					$('.labor_total_cost', this).val(total);

					var grand_total = 0;
	  		        var inps = document.getElementsByName('labor_total_cost[]');
					for (var i = 0; i < inps.length; i++) {
					var inp=inps[i];
					     grand_total = parseFloat((grand_total*1) + (inp.value*1)).toFixed(2);
					}

					document.getElementById("labor_grand_total").value = new Intl.NumberFormat('en-US',{style: 'currency', currency: 'PHP'}).format(grand_total);

				});

	  		}

	  $(document).on('keyup', "div.command-row-materials input", multInputsMaterials);

	  		function multInputsMaterials(){

	  			$('.item_unit_cost,.warehouse_uc').on('change', function(){
				    $(this).val(parseFloat($(this).val()).toFixed(2));
				});
				
				$("div.command-row-materials").each(function() {
					var quantity_needed = $('.item_quantity', this).val();
					var purchase_unit_cost = $('.item_unit_cost', this).val();
					var warehouse_quantity = $('.warehouse_qty', this).val();
					var warehouse_unit_cost = $('.warehouse_uc', this).val();
					var quantity_for_purchase = parseFloat((quantity_needed*1)-(warehouse_quantity*1));
					
					if(quantity_for_purchase<=0){
						var total = parseFloat((quantity_needed*1) * (warehouse_unit_cost*1)).toFixed(2);
						$('.purchase_quantity', this).val(0);
						$('.item_unit_cost', this).val(0);
					}else{
						var calc_wh_cost = parseFloat((warehouse_quantity*1) * (warehouse_unit_cost*1));
						var calc_ps_cost = parseFloat((purchase_unit_cost*1) * (quantity_for_purchase*1));
						var total =  parseFloat(calc_wh_cost + calc_ps_cost).toFixed(2);
						$('.purchase_quantity', this).val(quantity_for_purchase);	
					}

					$('.item_total_cost', this).val(total);

					var grand_total = 0;
	  		        var inps = document.getElementsByName('item_total_cost[]');
					for (var i = 0; i < inps.length; i++) {
						var inp=inps[i];
				        grand_total = parseFloat((grand_total*1) + (inp.value*1)).toFixed(2);
					}

					document.getElementById("materials_grand_total").value = new Intl.NumberFormat('en-US',{style: 'currency', currency: 'PHP'}).format(grand_total);
					
				});

	  		}

	  $(document).on('keyup', "div.command-row-materials input", autoComplete);
	  $(document).on('keyup', "div.command-row-tools input", autoComplete);

	  function autoComplete(){

			$(".item_code, .tool_name").autocomplete({
				source: "<?php echo HTTP_PATH.CONTROLLER; ?>/auto_complete_item_search",
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
						$( this ).next().val(0);
						$( this ).next().next().val("-");
						$( this ).next().next().next().val(0);
						$( this ).next().next().next().next().val(0);
					}
					return true;
				},
				select: function( event, ui ) {
					$( this ).val( ui.item.label );
					$( this ).next().val( ui.item.item_id );
					$( this ).next().next().val( ui.item.unit_measurement );
					$( this ).next().next().next().val( ui.item.quantity );
					$( this ).next().next().next().next().val( ui.item.unit_cost );
					return false;
				}
			});
		  		
	 }

	  $(document).on('change', "div.command-row-materials input", inventoryInfo);
	
		 function inventoryInfo_old(){
		 	$("div.command-row-materials").each(function() {
		 		var item = $('.item_code', this).val();

		 		var strRaw= item.substr(item.indexOf("["));
		 		var strSplit = strRaw.split(",");
		 		var qty = strSplit[0];
		 		var uc = strSplit[1];
		 		var id = strSplit[2];

		 		//$('.item_code', this).attr("readonly", "readonly");

				if(uc=="" || uc==null){
					uc=0;
				}
				if(id=="" || id==null){
					id=0;
					$('.item_id',this).val(Number(id));
				}else{
					$('.item_id',this).val(Number(id.substr(0,id.length-1)));
				}

		 		$('.warehouse_qty',this).val(Number(qty.substr(1)));
		 		$('.warehouse_uc',this).val(Number(uc));

		 	

		 	});
		 }

		 function inventoryInfo(){

		 	$("div.command-row-materials").each(function() {
		 		var id = $('.item_id', this).val();
		 		var unit = $('.item_unit', this).val();
		 		var qty = $('.wh_qty', this).val();
		 		var uc = $('.wh_uc', this).val();

		 		if(id==0){
		 			$('.warehouse_qty', this).val(0);
					$('.warehouse_uc', this).val(0);
		 		}
		 		else{
		 			$('.item_unit_measurement', this).val(unit);
		 			$('.warehouse_qty', this).val(qty);
					$('.warehouse_uc', this).val(uc);
		 		}

		 			/*$.ajax({
				     type:"POST",
				     url:"<?php //echo HTTP_PATH.CONTROLLER;?>/inventory_item_info/"+item,
					     success:function(data){

				        	var item_info = $.parseJSON(data);	

			        		$('.item_unit_measurement', this).val(item_info.unit);
				   			$('.warehouse_qty', this).val(Number(item_info.qty));
			  				$('.warehouse_uc', this).val(Number(item_info.unit_price));

					     } 	
					});*/

			});


		}
});


	//Ajax to fill submitted maintenance forms per vessel or truck by company
	//$('#maintenance_type').click(function() 
	$('#company').change(function() 
	{   
		  var company_id = document.getElementById('company').value;
		  var type = document.getElementById('maintenance_type').value;
		  if(company_id!=""){
			  $.ajax({
			     type:"POST",
			     url:"<?php echo HTTP_PATH.CONTROLLER;?>/maintenance_form_by_company/"+type+"/"+company_id,
			     success:function(data){
			        var evaluation_form_no = $.parseJSON(data);

			        $('#evaluation_form_no').find('option').remove().end().append('<option value="">Select</option>').val('');
					for(var i = 0; i < evaluation_form_no.length; i++){
			       		var mf_no = evaluation_form_no[i];
			       		var option = $('<option />');
					    option.attr('value', mf_no.id).text(mf_no.maintenance_form + " " + mf_no.control_number + " (" + mf_no.asset_name + ")");
					    $('#evaluation_form_no').append(option);
			        }
			     } 	
			  });
		  }

		  $('#for_repair_table tbody').remove();
		  var default_table = "<tbody><tr><td>...</td></tr></tbody>";
		  $('#for_repair_table').append(default_table);
	});	

	$('#company').change(function() 
	{ 
		 /* $.ajax({

		     type:"POST",
		     url:"<?php //echo HTTP_PATH.CONTROLLER;?>/control_number_by_company/am_bill_of_materials/"+$(this).val(),

		     success:function(data){
		        var control_number = $.parseJSON(data);   
		       	document.getElementById('control_number').value = control_number;	       	
		     }
		  });*/

		  document.getElementById('evaluation_form_no').value = "";

		  $('#for_repair_table tbody').remove();
		  var default_table = "<tbody><tr><td>...</td></tr></tbody>";
		  $('#for_repair_table').append(default_table);
	});


	$('#evaluation_form_no').change(function(){

			var type = document.getElementById('maintenance_type').value;

			$.ajax({
				type:"POST",
				url:"<?php echo HTTP_PATH.CONTROLLER;?>/maintenance_info/"+type+"/"+$(this).val(),
				success:function(data){

					var table_data = $.parseJSON(data);

						$('#for_repair_table tbody').remove();

						if(type=="Vessel"){
							var newRow = "<tbody><tr><th>Vessel Name</th><th>Dry-Docking Date</th><th>Dry-Docking Location</th><th>LOA</th><th>Breadth</th><th>Depth</th><th>GT</th></tr><tr><td>"+table_data.vessel_name+"</td><td>"+table_data.dry_docking_date+"</td><td>"+table_data.dry_docking_location+"</td><td>"+table_data.length_loa+"</td><td>"+table_data.breadth+"</td><td>"+table_data.depth+"</td><td>"+table_data.gross_tonnage+"</td></tr></tbody>";
						}else{
							var newRow = "<tbody><tr><th>Truck Plate-No.</th><th>Make</th><th>Model</th><th>Engine No.</th><th>Chassis No.</th><th>Type</th></tr><tr><td>"+table_data.plate_number+"</td><td>"+table_data.make+"</td><td>"+table_data.model+"</td><td>"+table_data.engine_number+"</td><td>"+table_data.chassis_number+"</td><td>"+table_data.type+"</td></tr></tbody>";
						}

						$('#for_repair_table').append(newRow);
				}
			});

	});

	$('#evaluation_form_noXXX').change(function(){

		var id = $(this).val();
		var type = $('#maintenance_type').val();

		$.ajax({
				type:"POST",
				url:"<?php echo HTTP_PATH.CONTROLLER;?>/check_bom/"+id+"/"+type,
				success:function(data){

					var bom = $.parseJSON(data);

					if(bom.chk_bom == true){
						toastr['error']("A BOM has already been created for this company asset. Please select another one.", "ABAS says:");
						document.getElementById('evaluation_form_no').value = "";

					}

				}
			});

	});

	function validateForm(){

		var gen_selects = document.getElementById('BOMGeneral').getElementsByTagName('select');
    	var gen_inputs = document.getElementById('BOMGeneral').getElementsByTagName('input');

    	var gen_flag=0;
        for(var i = 0; i < gen_selects.length; i++){         
            if (gen_selects[i].value==""){
            	gen_flag=1;
            } 
        }
        for(var x = 0; x < gen_inputs.length; x++){
        	if (gen_inputs[x].value==""){
            	gen_flag=1;
            }
        }

        if(gen_flag==1){
        	toastr['error']("Please fill-out all required* fields in General Information Tab!", "ABAS says:");
			return false;
        }

        var tasks_divs = document.getElementsByClassName('item-row-tasks')
    	var tasks_inputs = document.getElementById('BOMTasks').getElementsByTagName('input');

    	var tasks_flag=0;
    	if(tasks_divs.length > 0){
	        for(var x = 0; x < tasks_inputs.length; x++){
	        	if (tasks_inputs[x].value==""){
	            	tasks_flag=1;
	            }
	        }	
	    }
	    if(tasks_divs.length == 0){
	    	tasks_flag==1;
	    }
        if(tasks_flag==1){
        	toastr['error']("Please fill-out all required* fields in Tasks Description Tab!", "ABAS says:");
			return false;
        }

        var req_inputs = document.getElementById('tab_contents').getElementsByTagName('input');

    	var req_flag=0;
        for(var x = 0; x < req_inputs.length; x++){
        	if (req_inputs[x].value==""){
            	req_flag=1;
            }
        }
        if(req_flag==1){
        	toastr['error']("Please fill-out all required* fields in Requirements Tab! (Labor,Materials,Tools)", "ABAS says:");
			return false;
        }

        var uc_flag =0;
        var chk_unit_cost = document.getElementsByClassName('item_unit_cost');
        var pur_item_qty = document.getElementsByClassName('purchase_quantity');

        for(var x = 0; x < chk_unit_cost.length; x++){

        	if (chk_unit_cost[x].value==0){   
        		if(pur_item_qty[x].value>0){
        			uc_flag=1;	
        		}	
            }

        }
        if(uc_flag==1){
        	toastr["error"]("Please check the unit cost of materials. It must not be equal to zero.", "ABAS says:");
        }

        var qty_flag =0;
        var chk_quantity = $('.item_quantity, .tool_quantity');
        for(var x = 0; x < chk_quantity.length; x++){
        	if (chk_quantity[x].value==0){
            	qty_flag=1;
            }
        }
        if(qty_flag==1){
        	toastr["error"]("Please make sure that all quantity fields are not equal to zero.", "ABAS says:");
        }

  
	  	var duplicate_flag = 0;
	  	/*var values = [];

	  	$('.item_id').each(function () {
	  		//If value is empty then skip to next iteration
	  		if(!this.value) return true;
	    	//If the stored array has this value, break from the each method
	    	if(values.indexOf(this.value) !== -1) {
	      		 duplicate_flag = 1;
	       		 return false;
	     	}else if(values.indexOf(this.value)==0){
	     		 duplicate_flag = 0;
	     		 return false;
	     	}
	    	// store the value
	    	values.push(this.value);
	  	});   
		 

  		if(duplicate_flag==1){
  			toastr["error"]("One or more items are duplicated in the list of materials. Please verify and remove duplicates.", "ABAS says:");
  		}*/

        if(gen_flag==0 && tasks_flag==0 && req_flag==0 && duplicate_flag ==0 && uc_flag==0 && qty_flag==0) {

        	$('body').addClass('is-loading'); 
			$('#modalDialog').modal('toggle'); 

			document.getElementById("bill_of_materials_form").submit();
			return true;
		}

	}


</script>
