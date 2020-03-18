<?php
   $id = "";
   $unit = "";   
   if(isset($inventory_unit)){
   		//var_dump($item[0]['id']);exit;
   		$id = $inventory_unit[0]['id'];
		//var_dump($id);exit;
   		$unit = $inventory_unit[0]['unit'];
   }
   ?>
   
   <style>
   .modal-dialog {
   width: 371px !important;}
   </style>
<form class="form-horizontal" role="form" action="<?php echo HTTP_PATH.'forms/add_inventory_unit'; ?>" method="post" style="">
<?php echo $this->Mmm->createCSRF() ?>
<input type="hidden" name="stat" value="1">
         <input type="hidden" name="id" value="<?php echo $id;  ?>">
								<!--- waybill activity --->
								<div style="width:250px; float:left; margin-left:0px;">
									<div class="container">
										<div class="panel panel-primary" style="font-size:12px; width:366px; height:217px">
											<div class="panel-heading" role="tab" id="headingOne">
												<strong>Inventory Unit Information&nbsp;</strong>
											</div>
											<div class="panel-body" role="tab">
												<div style="width:200px; margin-left:80px; float:left; margin-top: 16px;">
													<div class="form-group">
														<label>Inventory Unit:</label> <i>(required)</i>
														<div>
															<input class="form-control input-sm" name="unit" class="form-control abas_Formcontrol" value="<?php echo $unit;  ?>" required>
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