<?php 
	$id = '';
	$make = '';
	$model = '';
//	$contact_person = '';
	$plate_number = '';
	$engine_number = '';
	$chassis_number = '';
	$type = '';
	$color = '';
	$date_acquired = '';
	$registration_month	 = '';							
	$aquisition_cost = '';
	
	if(isset($truck)){
		
		//var_dump($truck);exit;
   		//var_dump($item[0]['id']);exit;
   		$id = $truck[0]['id'];
   		$make = $truck[0]['make'];
		//var_dump($make);exit;
   		//$contact_person = $truck[0]['contact_person'];
   		$model = $truck[0]['model'];
   		$plate_number = $truck[0]['plate_number'];
   		$engine_number = $truck[0]['id'];
   		$chassis_number = $truck[0]['chassis_number'];
   		$type = $truck[0]['type'];
   		$color = $truck[0]['color'];
   		$date_acquired = date("Y-m-d",strtotime($truck[0]['date_acquired']));
   		$registration_month = $truck[0]['registration_month'];
   		$aquisition_cost = $truck[0]['aquisition_cost'];
		//var_dump();exit;
		
	}
	
?>
	
<form class="form-horizontal" role="form" action="<?php echo HTTP_PATH.'forms/add_truck'; ?>" method="post" style="height: 401px;">
<?php echo $this->Mmm->createCSRF() ?>
<input type="hidden" name="stat" value="1">
        <input type="hidden" name="id" value="<?php echo $id;?>">
								<!--- waybill activity --->
								<div style="width:250px; float:left; margin-left:0px;">
									<div class="container">
										<div class="panel panel-primary" style="font-size:12px; width:399px; height:505px">
											<div class="panel-heading" role="tab" id="headingOne">
												<strong>Truck Information&nbsp;</strong>
											</div>
											<div class="panel-body" role="tab">
												<div style="width:142px; margin-left:28px; float:left">
													<div class="form-group">
														<label>Make:</label> <i>(required)</i>
														<div>
															<input class="form-control input-sm" name="make" value="<?php echo $make;?>" required>
														</div>
													</div>											
													<div class="form-group">
														<label>Model:</label> <i>(required)</i>
														<div>											
                                                            <input class="form-control input-sm" type="text" name="model" value="<?php echo $model;?>" required>
														</div>
													</div>
													<div class="form-group">
														<label>Plate Number:</label>
														<div>											
                                                            <input class="form-control input-sm" type="text" name="plate_number"  value="<?php echo $plate_number;?>">
														</div>
													</div>
													<div class="form-group">
														<label>Engine Number:</label>
														<div>
															<input class="form-control input-sm" type="text" name="engine_number" class="form-control" value="<?php echo $engine_number;?>">
														</div>
													</div>
													<div class="form-group">
														<label>Chassis Number:</label>
														<div>
															<input class="form-control input-sm" type="text" name="chassis_number" class="form-control" value="<?php echo $chassis_number;?>">
														</div>
													</div>
												</div>
												<div style="width:142px; margin-left:20px; float:left">	
													<div class="form-group">
														<label>Type:</label>
														<div>
															<input class="form-control input-sm" type="text" name="type" class="form-control" value="<?php echo $type;?>">
														</div>
													</div>
													<div class="form-group">
														<label>Color:</label>
														<div>
															<input class="form-control input-sm" type="text" name="color" class="form-control" value="<?php echo $color;?>">
														</div>
													</div>
													<div class="form-group">
														<label>Date Acquired:</label>
														<div>
															<input type="text" id="date_acquired" class="date-picker form-control input-sm" name="date_acquired" value="<?php echo $date_acquired;  ?>">
															<script>$("#date_acquired").datepicker({changeYear: true,yearRange: "-100:+10"});</script>
														</div>
													</div>
													<div class="form-group">
														<label>Registration Month:</label>
														<div>
															<input class="form-control input-sm" type="text" name="registration_month" class="form-control" value="<?php echo $registration_month;?>">
														</div>
													</div>
													<div class="form-group">
														<label>Acquisition Cost:</label>
														<div>
															<input class="form-control input-sm" type="text" name="aquisition_cost" class="form-control" value="<?php echo $aquisition_cost;?>">
														</div>
													</div>
											</div>
											
										</div>
										<div class="modal-footer">
												  <button type="button" class="btn btn-default" data-dismiss="modal" onclick="myFunction()">Close</button>
												  <button type="submit" class="btn btn-primary">Save</button>
											</div>
									</div>
					      </div>
					</div>
</form>

<style>
.modal-dialog{width: 400px !important;height: 250px;}
</style>

<script>
function myFunction() {
         location.reload();
         }
</script>