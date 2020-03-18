<?php
	$id = "";
    $name = ""; 
	$make = "";
	$date_acquired = "";
	$make = "";
	$model_year = "";
	$aquisition_cost = "";
	$serial_no = "";
	$chasis_no = "";

   
   if(isset($crane)){
	  // var_dump($crane);exit;
   		//var_dump($item[0]['id']);exit;
   		$id = $crane[0]['id'];
		$name = $crane[0]['name'];
		$make = $crane[0]['make'];
		$date_acquired = date("Y-m-d",strtotime($crane[0]['date_acquired']));
		$make = $crane[0]['make'];
		$model_year = $crane[0]['model_year'];
		$aquisition_cost = $crane[0]['aquisition_cost'];
		$serial_no = $crane[0]['serial_no'];	
		$chasis_no = $crane[0]['chasis_no'];	
   }
?>

<form class="form-horizontal" role="form" action="<?php echo HTTP_PATH.'forms/add_crane'; ?>" method="post" style="height: 465px;">
<?php echo $this->Mmm->createCSRF() ?>
<input type="hidden" name="stat" value="1">
         <input type="hidden" name="id" value="<?php echo $id;  ?>">
								<!--- waybill activity --->
								<div style="width:250px; float:left; margin-left:0px;">
									<div class="container">
										<div class="panel panel-primary" style="font-size:12px; width:399px; height:559px">
											<div class="panel-heading" role="tab" id="headingOne">
												<strong>Crane Information&nbsp;</strong>
											</div>
											<div class="panel-body" role="tab">
												<div style="width:200px; margin-left:80px; float:left; margin-top: 16px;">
													<div class="form-group">
														<label>Name:</label><i>(required)</i>
														<div>
															<input class="form-control input-sm" name="name" class="form-control abas_Formcontrol" value="<?php echo $name;  ?>" required>
														</div>
													</div>
														<label>Date Acquired:</label>
														<div>
															<input type="text" id="date_acquired" class="date-picker form-control input-sm" name="date_acquired" value="<?php echo $date_acquired;  ?>">
																<script>$("#date_acquired").datepicker({changeYear: true,yearRange: "-100:+10"});
																</script>
														</div>
													<div class="form-group">
														<label>Make:</label>
														<div>
															<input class="form-control input-sm" name="make" class="form-control abas_Formcontrol" value="<?php echo $make;  ?>">
														</div>
													</div>
													<div class="form-group">
														<label>Model Year:</label>
														<div>
															<input class="form-control input-sm" name="model_year" class="form-control abas_Formcontrol" value="<?php echo $model_year;  ?>">
														</div>
													</div>
													<div class="form-group">
														<label>Acquisition Cost:</label>
														<div>
															<input class="form-control input-sm" name="aquisition_cost" class="form-control abas_Formcontrol" value="<?php echo $aquisition_cost;  ?>">
														</div>
													</div>
													<div class="form-group">
														<label>Serial No:</label>
														<div>
															<input class="form-control input-sm" name="serial_no" class="form-control abas_Formcontrol" value="<?php echo $serial_no;  ?>">
														</div>
													</div>
													<div class="form-group">
														<label>Chasis No:</label>
														<div>
															<input class="form-control input-sm" name="chasis_no" class="form-control abas_Formcontrol" value="<?php echo $chasis_no;  ?>">
														</div>
													</div>
													<div class="modal-footer">
												  <button type="button" class="btn btn-default" data-dismiss="modal" onclick="myFunction()">Close</button>
												  <button type="submit" class="btn btn-primary">Save</button>
											</div>
														
												</div>
												
											</div>

										</div>
									</div>
					</div>
</form>

<style>
.modal-dialog{width: 400px !important;height: 250px;}
</style>