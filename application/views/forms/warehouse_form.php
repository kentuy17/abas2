<form class="form-horizontal" role="form" action="<?php echo HTTP_PATH.'forms/add_warehouse'; ?>" method="post" style="height: 338px;">
<?php echo $this->Mmm->createCSRF() ?>
<input type="hidden" name="stat" value="1">
        <input type="hidden" name="id" value="">
								<!--- waybill activity --->
								<div style="width:250px; float:left; margin-left:0px;">
									<div class="container">
										<div class="panel panel-primary" style="font-size:12px; width:399px; height:338px">
											<div class="panel-heading" role="tab" id="headingOne">
												<strong>Port Information&nbsp;</strong>
											</div>
											<div class="panel-body" role="tab">
												<div style="width:200px; margin-left:80px; float:left; margin-top: 16px;">
													<div class="form-group">
														<label>Name:</label>
														<div>
															<input class="form-control input-sm" name="name" class="form-control abas_Formcontrol" value="" required>
														</div>
													</div>
													<div class="form-group">
														<label>Region:</label>
														<div>															
                                                            <input class="form-control input-sm ui-autocomplete-input" type="text" name="region" class="form-control abas_Formcontrol" value="" required>
														</div>
													</div>
													<div class="form-group">
														<label>Port:</label>
														<div>
															<input class="form-control input-sm ui-autocomplete-input" type="text" name="port" class="form-control" value="" required>
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