<?php
   $id = "";
   $cname = "";
   $cperson = "";
   $contact = "";
   $address = "";
   $region = "";
   
   if(isset($service)){
   		//var_dump($item[0]['id']);exit;
   		$id = $service[0]['id'];
		//var_dump($id);exit;
   		$cname = $service[0]['company_name'];
   		$cperson = $service[0]['contact_person'];
   		$contact = $service[0]['contact'];
   		$address = $service[0]['address'];
   		$region = $service[0]['region'];
   }
   ?>
<form class="form-horizontal" role="form" action="<?php echo HTTP_PATH.'forms/add_service_provider'; ?>" method="post" style="height: 465px;">
<?php echo $this->Mmm->createCSRF() ?>
<input type="hidden" name="stat" value="1">
         <input type="hidden" name="id" value="<?php echo $id;  ?>">
								<!--- waybill activity --->
								<div style="width:250px; float:left; margin-left:0px;">
									<div class="container">
										<div class="panel panel-primary" style="font-size:12px; width:399px; height:465px">
											<div class="panel-heading" role="tab" id="headingOne">
												<strong>Service Provider Information&nbsp;</strong>
											</div>
											<div class="panel-body" role="tab">
												<div style="width:200px; margin-left:80px; float:left; margin-top: 16px;">
													<div class="form-group">
														<label>Company Name:</label> <i>(required)</i>
														<div>
															<input class="form-control input-sm" name="cname" class="form-control abas_Formcontrol" value="<?php echo $cname;  ?>" required>
														</div>
													</div>
													<div class="form-group">
														<label>Contact Person:</label>
														<div>															
                                                            <input class="form-control input-sm ui-autocomplete-input" type="text" name="cperson" class="form-control abas_Formcontrol" value="<?php echo $cperson;  ?>">
														</div>
													</div>
													<div class="form-group">
														<label>Contact:</label>
														<div>															
                                                            <input class="form-control input-sm ui-autocomplete-input" type="text" name="contact" class="form-control abas_Formcontrol" value="<?php echo $contact;  ?>">
														</div>
													</div>
													<div class="form-group">
														<label>Address:</label>
														<div><input type="text" name="address" class="form-control input-sm ui-autocomplete-input" value="<?php echo $address;  ?>"/>
														</div>
													</div>	
													<div class="form-group">
														<label>Region:</label>
														<div>
															<input class="form-control input-sm ui-autocomplete-input" type="text" name="region" class="form-control" value="<?php echo $region;  ?>">
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