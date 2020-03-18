<?php
	$company_id 			="";
	$item_id				="";
	$item_code				="";
	$asset_code				="";
	$description			="";
	$particular				="";
	$brand                  ="";
	$unit					="";
	$unit_price				="";
	$type                   ="";
	$picture                = LINK.'assets/uploads/inventory/item_images/default.jpg';
	$id						="";
	$qty					=0;
	$category				="";
	$categoryid				="";
	$sub_category			="";
	$sub_categoryid			="";
	$classification			="";
	$classification_name	="";
	$reorder				="";
	$qty					=0;
	$location				=$_SESSION['abas_login']['user_location'];
	$stock_location			="";	
	$account_type			="";
	$display				="";
	$title 					="Add Item";
	$action 				="insert";
	$packaging_details 	        = "";
	$packaging_details_edit		= "";
	$packaging_details_append     = "";
	$company_edit_id		= "";
	$location_edit_id		= "";
	$vessel_options = "<option value=''>Select</option>";
	if(isset($item)){
		foreach($vessels as $vessel) {
			$vessel_options	.=	"<option ".($item[0]['stock_location']==$vessel->id ? "selected":"")." value='".$vessel->id."'>".$vessel->name."</option>";
		}
	}else{
		foreach($vessels as $vessel) {
			$vessel_options	.=	"<option value='".$vessel->id."'>".$vessel->name."</option>";
		}
	}
	$company_options = "<option value=''>Select</option>";
	if(!isset($item)){
		foreach($companies as $company) {
			$company_options	.=	"<option value='".$company->id."'>".$company->name."</option>";
		}
	}
	$unit_options = "<option value=''>Select</option>";
	foreach($units as $unitx) {
		$unit_options	.=	"<option value='".$unitx['unit']."'>".$unitx['unit']."</option>";
	}

	$packaging_details	.=	"<div class='row uom-row command-row-uom'>
							<div class='col-sm-3 col-xs-12'>
								<label data-toggle='tooltip' data-placement='top' title='' data-original-title='Tooltip top'>1 Packaging*</label>
								<select class='form-control input-sm' name='packaging[]' id='packaging[]'>".$unit_options."
								</select>
							</div>
							<div class='col-sm-1 col-xs-12'>
								<label>&nbsp</label>
								<input type='text' value='=' style='font-size:20px;border:none'>
							</div>
							<div class='col-sm-3 col-xs-12'>
								<label>Equivalent Qty.*</label>
								<input type='number' class='form-control input-sm' name='conversion[]' id='conversion[]'>
							</div>
							<div class='col-sm-4 col-xs-12'>
								<label>Unit</label>
								<input class='form-control input-sm unit' name='default_unit[]' id='default_unit[]' readonly>
							</div>
							<div class='col-sm-12 col-xs-12'>
							<hr>
							</div>
							<a class='btn-remove-row btn btn-danger btn-xs' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
						</div>";

	$packaging_details_append= trim(preg_replace('/\s+/',' ', $packaging_details));

	if(isset($item)){
		$company_edit_id		=$company_idx;
		$location_edit_id		=$locationx;
		$action 				="update/".$item[0]['id'];
		$item_id				=$item[0]['id'];
		$item_code				=$item[0]['item_code'];
		$asset_code				=$item[0]['asset_code'];
		$description			=$item[0]['description'];
		$brand					=$item[0]['brand'];
		$particular				=$item[0]['particular'];
		$stock_location			=$item[0]['stock_location'];
		$unit					=$item[0]['unit'];
		$unit_price				=$item[0]['unit_price'];
		$id						=$item[0]['id'];
		$type                   =$item[0]['type'];
		$title 					="Edit Item";
		if($item[0]['picture']!=NULL){
			$picture				= LINK.'assets/uploads/inventory/item_images/'.$item[0]['picture'];
		}else{
			$picture				= LINK.'assets/uploads/inventory/item_images/default.jpg';
		}
		$account_type			=$item[0]['account_type'];
		$classification			="";
		$classification_name	="";
		$reorder				=$item[0]['reorder_level'];
		$location				=$_SESSION['abas_login']['user_location'];
		$stock_location			=$item[0]['stock_location'];
		$display				='';
		$sql4					="SELECT id, category FROM inventory_category WHERE id	=".$item[0]['category'];
		$r4						=$this->db->query($sql4);
		$chk4					=$r4->result_array();
		if(!empty($chk4)){
			$category				=$chk4[0]['category'];
			$categoryid				=$chk4[0]['id'];
		}else{
			$category				='Uncategorized';
			$categoryid				='';
		}
		if($item[0]['sub_category']!=0){
			$sql				="SELECT id, category FROM inventory_category WHERE id	=".$item[0]['sub_category'];
			$r					=$this->db->query($sql);
			$chk				=$r->result_array();
			$sub_category		=$chk[0]['category'];
			$sub_categoryid		=$chk[0]['id'];
		}

		if(isset($packaging)){
			foreach($packaging as $detail){
				$packaging_details_edit	.=	"<div class='row uom-row command-row-uom'>
												<div class='col-sm-3 col-xs-12'>
													<label data-toggle='tooltip' data-placement='top' title='' data-original-title='Tooltip top'>1 Packaging*</label>
													<select class='form-control input-sm' name='packaging[]' id='packaging[]'>
													<option value='".$detail->packaging."' selected>".$detail->packaging."</option>
													".$unit_options."
													</select>
												</div>
												<div class='col-sm-1 col-xs-12'>
													<label>&nbsp</label>
													<input type='text' value='=' style='font-size:20px;border:none'>
												</div>
												<div class='col-sm-3 col-xs-12'>
													<label>Equivalent Qty.*</label>
													<input type='number' class='form-control input-sm' name='conversion[]' id='conversion[]' value='".$detail->conversion."'>
												</div>
												<div class='col-sm-4 col-xs-12'>
													<label>Unit</label>
													<input class='form-control input-sm unit' name='default_unit[]' id='default_unit[]' readonly value='".$unit."'>
												</div>
												<div class='col-sm-12 col-xs-12'>
												<hr>
												</div>
												<a class='btn-remove-row btn btn-danger btn-xs' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
											</div>";
			}
		}

	}


?>


<form class="form-horizontal" role="form" id="itemForm" name="itemForm"	action="<?php echo HTTP_PATH.'inventory/items/'.$action; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF() ?>

		<div class="panel panel-primary">
			<div class="panel-heading" role="tab" id="headingOne">
				<strong><?php echo $title?></strong>
				 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
		</div>
			<div class="panel-body" role="tab" >
				<input class="form-control" type="hidden" name="company_edit_id" id="company_edit_id" value="<?php echo $company_edit_id;?>" readonly>
				<input class="form-control" type="hidden" name="location_edit_id" id="location_edit_id" value="<?php echo $location_edit_id;?>" readonly>
				<div class='col-sm-12 col-xs-12'>
					<label	for="payee">Picture/Diagram</label>
					<img class="center-block img-responsive" style="width:210px; height:150px;" src="<?php echo $picture; ?>" />
					<br>
					<?php if($_SESSION['abas_login']['role']=="Inventory" || $_SESSION['abas_login']['role']=="Purchasing"  || $_SESSION['abas_login']['role']=="Administrator"){ ?>
					<input class="center-block" type="file" name="picture" id="picture">
					<?php } ?>
				</div>
				
					<div class='col-sm-12 col-xs-12'>
						<hr>
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="##desc-tab">Description</a></li>
							<li><a data-toggle="tab" href="##price-tab">Stocks & Pricing</a></li>
							<?php if($_SESSION['abas_login']['role']=="Inventory" || $_SESSION['abas_login']['role']=="Purchasing"  || $_SESSION['abas_login']['role']=="Administrator"){ ?>
							<li><a data-toggle="tab" href="##pack-tab">Packaging</a></li>
							<?php } ?>
							<?php if($packaging_details_edit!=''){ ?>
								<li><a data-toggle="tab" href="##qty-tab">Conversions</a></li>
							<?php } ?>
						</ul>
					</div>
					<div class="tab-content">
						<div id="desc-tab" class="tab-pane fade in active">
							<div class='col-sm-12 col-xs-12'>
								<input class="form-control input-sm" type="hidden" name="item_id" id="item_id" value="<?php echo $item_id;	?>" />
								<label for="item_code">Item Code</label>
								<input class="form-control input-sm" type="text" name="item_code" id="item_code" value="<?php echo $item_code;	?>" style="text-align:center;font-size: 200%;"/>
							</div>
							<div class='col-sm-8 col-xs-12'>
								<label	for="item_name">Item Name*</label>
								<input class="form-control input-sm" type="text" name="description" id="description" value="<?php echo $description;?>" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>/>
							</div>
							<div class='col-sm-4 col-xs-12'>
								<label	for="brand">Brand</label>
								<input class="form-control input-sm" type="text" name="brand" id="brand" value="<?php echo $brand;?>" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>/>
							</div>
							<div class='col-sm-12 col-xs-12'>
								<label	for="particular">Particular* (Make/Model/Color/Size/Serial Part No.):</label>
								<input class="form-control input-sm" type="text" id="particular" name="particular"	value='<?php echo $particular; ?>' <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>/>
							</div>
							<div class='col-sm-6 col-xs-12'>
								<label for="category">Category*</label>
								<select class="form-control input-sm" name="category" id="category" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
									<option value="<?php echo $categoryid; ?>"><?php echo $category; ?></option>
									<?php foreach($categories as $category){ ?>
										<option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class='col-sm-6 col-xs-12'>
								<label>Type</label>
								<select class="form-control input-sm" name="type" id="type" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Accounting" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
									<option value=''>Select</option>
									<option value='Non-Capex' <?php if($type=='Non-Capex'){ echo 'selected';} ?>>Non-Capex (For consumables)</option>
									<option value='Capex' <?php if($type=='Capex'){ echo 'selected';} ?>>Capex (For Fixed Asset inclusion)</option>
								</select>
							</div>
							<div class='col-sm-6 col-xs-12'>
								<label>Smallest/Default Unit Of Measure*</label>
								<select class="form-control input-sm" name="unit" id="unit" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
									<option value="<?php echo $unit ?>"><?php echo $unit ?></option>
									<?php foreach($units as $unit) { ?>
										<option value="<?php echo $unit['unit']; ?>"><?php echo $unit['unit']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class='col-sm-6 col-xs-12'>
								<label>Default Unit Price*</label>
								<input class="form-control input-sm" type="number" min="0.01" step="0.01" max="5000000" name="unit_price" id="unit_price" value="<?php echo $unit_price;?>" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Accounting" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
							</div>
							<div class='col-sm-12 col-xs-12'>
								<label>Storage Location (Shelf/Rack No.):</label>
								<input type="text" class="form-control input-sm" name="stock_location" id="stock_location" value="<?php echo $stock_location;?>" <?php if($_SESSION['abas_login']['role']=="Inventory" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
							</div>
							<div class='col-sm-6 col-xs-12'>
								<label>Reorder Point (Quantity)</label>
								<input class="form-control input-sm" type="number" name="reorder" id="reorder" value="<?php echo $reorder; ?>" <?php if($_SESSION['abas_login']['role']=="Inventory" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
							</div>
							<div class='col-sm-6 col-xs-6'>
								<label>Status</label>
								<select class="form-control input-sm" name="stat" id="stat" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Accounting" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
									<option value='1' <?php (isset($item) && $item[0]['stat']==1)?'selected':''?>>Active</option>
									<option value='0' <?php (isset($item) && $item[0]['stat']==0)?'selected':''?>>Inactive</option>
								</select>
							</div>
							<?php if(!isset($item)){ ?>
								<div class='col-sm-6 col-xs-12'>
									<label> Initially For Company*</label>
									<select class="form-control input-sm" name="company" id="company" <?php if($_SESSION['abas_login']['role']=="Purchasing" || $_SESSION['abas_login']['role']=="Accounting" || $_SESSION['abas_login']['role']=="Administrator"){ echo '';}else{ echo 'readonly';} ?>>
										<?php echo $company_options;?>
									</select>
								</div>
							<?php }?>

							<!--Temporarily only, to allow correction of item quantity 
								Please remove later once inventory is now accurate
							-->
							<?php if(!isset($item)){ 
								if($this->Abas->checkPermissions("inventory|set_item_qty",false)): ?>
									<div class='col-sm-6 col-xs-12'>
										<label>Initial Quantity:</label>
										<input class="form-control input-sm" type="number" name="qty" id="qty" value="" >
									</div>
								<?php endif; ?>
							<?php }?>
						</div>
						<div id="pack-tab" class="panel-body tab-pane fade">
							<div class="clearfix"><br/></div>
							<div class="clearfix"><br/></div>
							<div class="pull-right">
								<a id="btn_add_row" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>
								<a id="btn_remove_row" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
							</div>
							<div class="clearfix"><br/></div>
							<div class="packing-row-container" id='uom_container'>
								<?php 
									if(isset($item)){
										echo $packaging_details_edit;
									}
								?>
							</div>
						</div>
						<div id="qty-tab" class="panel-body tab-pane fade">
							<div class='col-sm-12 col-xs-12'>
								<br>
							<?php 
								if(isset($item)){
									$itemx = $this->Inventory_model->getItemQuantityPerCompany($item[0]['id'],$company_edit_id,$location_edit_id);
									$balance = ($itemx[0]->total_quantity_received-$itemx[0]->total_quantity_issued);
										echo "<h4>Total Quantity on Stock: ".number_format($balance,2,'.',',')." ".$item[0]['unit']."</h4>";
										echo "<hr>";
									echo "<table class='table table-striped table-bordered'>";
										echo "<tr>";
											echo "<td style='width:20%'>Equivalent Quantity</td>";
											echo "<td>Packaging in</td>";
										echo "</tr>";
										foreach($packaging as $unitx){
											if($unitx->unit==$item[0]['unit']){
												echo "<tr>";
												echo "<td>".($balance/$unitx->conversion)."</td>";
												echo "<td>".$unitx->packaging."</td>";
												echo "</tr>";
											}
										}
									echo "</table>";
								}
							?>
							</div>
						</div>
						<div id="price-tab" class="panel-body tab-pane fade">
							<div class='col-sm-12 col-xs-12'  style='overflow-x:auto'>
								<br>
								<?php
									
									echo "<table class='table table-striped table-bordered'>";
									echo "<td>RR No.</td>";
									echo "<td>PO No.</td>";
									echo "<td>Date Purchased</td>";
									echo "<td>Unit Price</td>";
									echo "<td>Quantity (Received)</td>";
									echo "<td>Total Cost (Received)</td>";
									echo "<td>Quantity (Issued)</td>";
									echo "<td>Total Cost (Issued)</td>";
									echo "<td>Quantity (Remaining)</td>";
									echo "<td>Total Cost (Remaining)</td>";
									echo "<td>Manage</td>";

									if(isset($item)){
										$inventory = $this->Inventory_model->getItemForIssuance($item[0]['id'],$company_edit_id,$location_edit_id);
										$total_unit_price_received = 0;
										$total_unit_price_issued = 0;
										$total_unit_price_remaining= 0;
										$total_received = 0;
										$total_issued = 0;
										$total_remaining = 0;
										foreach($inventory as $item){
											if($item->quantity_issued<$item->quantity){
												echo "<tr>";
													$rr = $this->Inventory_model->getDeliveries($item->delivery_id);
													if(count($rr)>0){
														$rr_id = $item->delivery_id;
														$po_id = $rr[0]['po_no'];
														$po_date = date('j F Y',strtotime($rr[0]['tdate']));
													}else{
														$rr_id = "--";
														$po_id = "--";
														$po_date = "--";
													}
													echo "<td>".$rr_id."</td>";
													echo "<td>".$po_id."</td>";
													echo "<td>".$po_date."</td>";
													echo "<td>".number_format($item->unit_price,2,'.',',')."</td>";

													echo "<td>".number_format($item->quantity,2,'.',',')."</td>";
													echo "<td>".number_format(($item->quantity*$item->unit_price),2,'.',',')."</td>";

													echo "<td>".number_format($item->quantity_issued,2,'.',',')."</td>";
													echo "<td>".number_format(($item->quantity_issued*$item->unit_price),2,'.',',')."</td>";

													echo "<td>".number_format(($item->quantity - $item->quantity_issued),2,'.',',')."</td>";
													echo "<td>".number_format((($item->quantity - $item->quantity_issued)*$item->unit_price),2,'.',',')."</td>";
													
													echo "<td><a href='".HTTP_PATH."inventory/items/print_qr_code/".$item->id."' class='btn btn-primary btn-xs btn-block' target='_blank'>QR Code</a>";

													if($this->Abas->checkPermissions("inventory|company_quantity_transfer",false)){
														echo '<a class="btn btn-danger btn-xs btn-block btn-xs" href="'.HTTP_PATH.'inventory/company_quantity_transfer/'.$item->id.'" data-toggle="modal" data-target="#modalDialogNorm" > Transfer</a>';
													}

													echo "</td>";
													
												echo "</tr>";
												$total_unit_price_received = $total_unit_price_received + ($item->quantity*$item->unit_price);
												$total_unit_price_issued = $total_unit_price_issued + ($item->quantity_issued*$item->unit_price);
												$total_received = $total_received + $item->quantity;
												$total_issued = $total_issued + $item->quantity_issued;
												$total_remaining = $total_received  - $total_issued;
												$total_unit_price_remaining = $total_unit_price_remaining + (($item->quantity - $item->quantity_issued)*$item->unit_price);
											}
										}
										echo "<tr>
												<td colspan='4' style='text-align:right'>Total</td>
												
												<td>".number_format($total_received,2,'.',',')."</td>
												<td>".number_format($total_unit_price_received,2,'.',',')."</td>
												<td>".number_format($total_issued,2,'.',',')."</td>
												<td>".number_format($total_unit_price_issued,2,'.',',')."</td>
												<td>".number_format($total_remaining,2,'.',',')."</td>
												<td>".number_format($total_unit_price_remaining,2,'.',',')."</td>
											</tr>";
									}else{
										echo "<tr><td colspan='11'><center>No record found</center></td></tr>";
									}
									echo "</table>";
								?>
							</div>
						</div>
					</div>
			</div>
			<div class='modal-footer'>
				<span class='pull-right'>
					<?php if($this->Abas->checkPermissions("inventory|edit_item",false)): ?>
					<input class="btn btn-success" type="button" value="Save" onclick="javascript:submitMe()" id="submitbtn">
					<?php endif; ?>
					<input class="btn btn-danger" type="button" value="Cancel" data-dismiss="modal">
				</span>
			</div>
	</div>
			

<input type="hidden" id="fromModule" name="fromModule" value="<?php echo $_SERVER['HTTP_REFERER'] ?>" />
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
<input	type="hidden" name="qty2" id="qty2" value="<?php echo $qty; ?>" />
</form>
<script type="text/javascript">

	$("#btn_remove_row").click(function(){
		$('.uom-row:last').remove();
	});
	$(document).on('click', '.btn-remove-row', function() {
		$(this).parent().remove();
	});
	$("#btn_add_row").click(function(){
		var default_uom = $('#unit').val();
		$('.packing-row-container').append("<?php echo $packaging_details_append; ?>");
		$('.unit').val(default_uom);
	});

	var qrcode = new QRCode(document.getElementById("qrcode"), {
		width : 100,
		height : 100
	});

	function makeCode () {		
		var item_id = document.getElementById("item_id");
		var item_code = document.getElementById("item_code");
		var item_unit = document.getElementById("unit");
		var item_price = document.getElementById("unit_price");
		
		if(!item_code.value || item_code.value==0) {
			return;
		}

		if(!item_unit.value){
			return;
		}

		qrcode.makeCode(item_id.value+","+item_price.value);
	}

	makeCode();

	$("#item_code").
		on("blur", function () {
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			makeCode();
		}
	});

	$("#unit").
		on("blur", function () {
		makeCode();
		var default_uom = $(this).val();
		$('.unit').val(default_uom);
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			makeCode();
		}
	});

	$("#unit_price").
		on("blur", function () {
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			makeCode();
		}
	});

	function submitMe(){
		
		var d=document.getElementById('description').value;
		var p=document.getElementById('particular').value;
		var u=document.getElementById('unit').value;
		var c=document.getElementById('category').value;
		var msg="";
		if(d==''){
			msg += 'Please enter description.<br>';
		}
		if(p==''){
			msg += 'Please enter particular.<br>';
		}
		if(u==''){
			msg += 'Please select unit.<br>';
		}
		if(c==''){
			msg += 'Please select category.<br>';
		}
		
		if(msg!="") {
			toastr["error"](msg,"ABAS Says:");
			return false;
		}else{
			document.forms['itemForm'].submit();
		}

	}

</script>