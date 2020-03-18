<?php
// $this->Mmm->debug($request);
// $this->Mmm->debug($request_details);
$priorityclass	=	"default";
if($priorityclass=="default") {
	if($request['priority']=="High") { $priorityclass="danger"; }
	if($request['priority']=="Medium") { $priorityclass="info"; }
}
$supplier		=	array();
$vessel			=	$this->Abas->getVessel($request['vessel_id']);
$company		=	$this->Abas->getCompany($vessel->company);
if(!empty($request_details)) {
	foreach($request_details as $ctr=>$pi) {
		if(strtolower($pi['status'])!="cancelled") {
			$canvasssql			=	"SELECT * FROM inventory_request_details WHERE request_id=".$request['id']." AND item_id=".$pi['item_id']." AND supplier_id<>0 AND status LIKE 'for purchase'";
			$approved_canvass	=	$this->db->query($canvasssql);
			if($approved_canvass) {
				if($canvass=$approved_canvass->result_array()) {
					$already_added	=	array();
					foreach($canvass as $c) {
						$suppliers[$c['supplier_id']][]	=	$c; // sorts PO items by supplier
					}
				}
			}
		}
	}
}
unset($request_details);
$display['supplier']		=	"No items found!";
$complete_supplier_details	=	true;
if(!empty($suppliers)) {
	// $this->Mmm->debug($suppliers);
	$display['supplier']	=	'';
	foreach($suppliers as $sctr=>$items) {
		$supplierdata	=	$this->Abas->getSupplier($sctr);
		$this->Mmm->debug($supplierdata);
		if($supplierdata['vat_computation']=="" || !is_numeric($supplierdata['tin']) || $supplierdata['tin']=="0") {
			if($supplierdata['vat_computation']!="vatable" || $supplierdata['vat_computation']!="non-vatable" || $supplierdata['vat_computation']!="vat-exempt" || $supplierdata['vat_computation']!="zero-rated") { // check if vat computation is within set standards
				$complete_supplier_details=false;
			}
		}
		$this->Mmm->debug($supplierdata);
		$display['supplier']	.=	'
			<div class="clearfix">&nbsp;</div>
			<div class="col-xs-12">
				<div class="table-responsive">
					<table class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th colspan="5">
									<input type="checkbox" checked name="supplier['.$supplierdata['id'].']" id="supplier['.$supplierdata['id'].']" value="'.$supplierdata['id'].'" />
									<label for="supplier['.$supplierdata['id'].']">Supplier: '.$supplierdata['name'].'</label>
								</th>
							</tr>
							<tr>
								<th>Item</th>
								<th>Quantity</th>
								<th>Remark</th>
								<th>Unit Price</th>
								<th>Total Price</th>
							</tr>
						</thead>
						<tbody>';
		$display['items']	=	'No items found!';
		if(!empty($items)) {
			$display['items']='';
			$gross_purchases	=	0;
			foreach($items as $cctr=>$canvass) {
				$gross_purchases	=	$gross_purchases+($canvass['unit_price']*$canvass['quantity']);
				$item				=	$this->Inventory_model->getItem($canvass['item_id']);
				$item				=	$item[0];

				$sql = "SELECT * FROM inventory_request_details WHERE request_id=".$request['id']." AND item_id=".$canvass['item_id']." AND unit_price is null";
				$query = $this->db->query($sql);
				$service_description = $query->row(); 

				$display['items']	.=	"<tr>";
				$display['items']	.=	"<td>".$item['item_name'].",".$item['brand'].$item['particular']."</td>";
				$display['items']	.=	"<td>".$canvass['quantity']."</td>";
				$display['items']	.=	"<td>".$service_description->remark."</td>";
				$display['items']	.=	"<td>".number_format($canvass['unit_price'],2)."</td>";
				$display['items']	.=	"<td>".number_format(($canvass['unit_price']*$canvass['quantity']),2)."</td>";
				$display['items']	.=	"</tr>";
			}
			$vat						=	0;
			$etax						=	0;
			if($supplierdata['issues_reciepts']==1) {
				if($gross_purchases>0) {
					if(strtolower($supplierdata['vat_computation'])=='exclusive') {
						$vat				=	(($gross_purchases)*(0.12));
					}
					elseif(strtolower($supplierdata['vat_computation'])=='inclusive') {
						$vat				=	($gross_purchases-($gross_purchases/1.12));
					}
					$vatable_purchases	=	(strtolower($supplierdata['vat_computation'])=='inclusive')?$gross_purchases-$vat:$gross_purchases+$vat;
				}
				else {
					$this->Abas->sysMsg("warnmsg", "Invalid amount! The price of your request totals to P".$gross_purchases);
					die("<h1>Invalid amount! The price of your request totals to P".$gross_purchases."</h1>");
				}
			}

			$company_top_20000 = $this->Abas->isCompanyTop20000($company->id);

			$display['items'].='
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4"><label for="gross_purchases'.$supplierdata['id'].'">Gross Purchases</label></td>
								<td>'.number_format($gross_purchases,2).'</td>
							</tr>
							<tr>
								<td colspan="4"><label for="discount'.$supplierdata['id'].'"></label></td>
								<td>
									<input type="text" class="form-control input-xs hidden" name="discount['.$supplierdata['id'].']" id="discount'.$supplierdata['id'].'" value="0">
								</td>
							</tr>
							<tr>
								<td colspan="4"><label for="vat'.$supplierdata['id'].'">Value Added Tax ('.ucwords($supplierdata['vat_computation']).')</label></td>
								<td>
									<input type="text" disabled="disabled" class="form-control input-xs" name="vat['.$supplierdata['id'].']" id="vat'.$supplierdata['id'].'" value="'.number_format($vat,2).'">
								</td>
							</tr>
							<tr>
								<td colspan="4"><label for="etax'.$supplierdata['id'].'">Withholding Tax - Expanded</label></td>
								<td>
									<select class="form-control input-xs" name="etax['.$supplierdata['id'].']" id="etax'.$supplierdata['id'].'">
										<option value="">Choose One</option>
										<option value="0">None</option>
										'.(($company_top_20000==TRUE)?'<option value="1">1%</option>':'').'
										<option value="2">2%</option>
										<option value="5">5%</option>
										<option value="10">10%</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="4"><label for="payment_terms'.$supplierdata['id'].'">Payment Terms</label></td>
								<td>
									<select class="form-control input-xs" name="payment_terms['.$supplierdata['id'].']" id="payment_terms'.$supplierdata['id'].'">
										<option value="">Choose One</option>
										<option value="Cash on Delivery">Cash on Delivery</option>
										<option value=" Advanced Partial Payment">Advanced Partial Payment</option>
										<option value="Termed Payment">Termed Payment</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<label for="purchase_type'.$supplierdata['id'].'">Purchase Type (Required)</label>
								</td>
								<td>
									<select id="purchase_type['.$supplierdata['id'].']" name="purchase_type['.$supplierdata['id'].']" class="form-control purchase_type">
										<option value="">Choose One</option>
										<option value="PO">For Purchase Order</option>
										<option value="JO">For Job Order</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<label for="attach_file'.$supplierdata['id'].'">Attachment (Optional)</label>
								</td>
								<td>
								<input type="file" id="attach_file['.$supplierdata['id'].']" name="attach_file['.$supplierdata['id'].']" class="">
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>';
			$display['supplier']	.=	$display['items'];
		}
	}
}
?>
<div class="panel panel-<?php echo $priorityclass; ?>">
		<div class="panel-heading" role="tab" id="poFormHeadingRequest<?php echo $request['id']; ?>">
			<h4 class="panel-title">
				<a role="button" data-toggle="collapse" href="#poFormBodyRequest<?php echo $request['id']; ?>" aria-expanded="true" aria-controls="bodyRequest<?php echo $request['id']; ?>">
					Purchase Order for <?php echo $company->name." (".$vessel->name.")"; ?>
				</a>
			</h4>
		</div>
		<div id="poFormBodyRequest<?php echo $request['id']; ?></div>" class="panel-collapse" aria-labelledby="poFormHeadingRequest<?php echo $request['id']; ?>">
		<form action="<?php echo HTTP_PATH; ?>purchasing/purchase_order/create/<?php echo $request['id']; ?>" role="form" method="POST" id="purchase_form<?php echo $request['id']; ?>" enctype="multipart/form-data">
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="panel-body">
				<div class="col-xs-12">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label for="location<?php echo $request['id']; ?>">Location</label>
						<input type="text" class="form-control" name="location" id="location<?php echo $request['id']; ?>" value="" placeholder="Location" />
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label for="estimated_delivery_date<?php echo $request['id']; ?>">Estimated Delivery Date</label>
						<input type="text" class="form-control datepicker" name="estimated_delivery_date" id="estimated_delivery_date<?php echo $request['id']; ?>" value="" placeholder="Estimated Delivery Date" />
						<script>$(".datepicker").datepicker();</script>
					</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<label for="remark<?php echo $request['id']; ?>">Remark</label>
						<textarea class="form-control" name="remark" id="remark<?php echo $request['id']; ?>"><?php echo $request['remark']; ?></textarea>
					</div>
				</div>
				<?php echo $display['supplier']; ?>
				<div class="clearfix">&nbsp;</div>
				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
					<input class="btn btn-success btn-block" type="button"  value="Finalize Multiple Orders" onclick="javascript:checkpo(<?php echo $request['id']; ?>)" id="submitbtn">
				</div>
			</div>
		</form>
	</div>
</div>
<script>
<?php if(!$complete_supplier_details): ?>
toastr['warning']("Supplier details are incomplete! Please complete supplier details before proceeding.","ABAS Says");
<?php endif; ?>
function checkpo(request_id) {
	var msg="";
	var patt1=/^[0-9]+$/i;
	var location=document.getElementById("location"+request_id).value;
	if (location==null || location=="") {
		msg+="Location is required! <br/>";
	}
	var $nonempty = $('#etax<?php echo $supplierdata['id']; ?>').filter(function() { return this.selectedIndex != ''; });
	if ($nonempty.length == 0) {
		msg+="Withholding tax for a supplier was not selected properly!<br/>";
	}
	<?php if(!$complete_supplier_details): ?>
	msg	+=	"Supplier details are incomplete!";
	<?php endif; ?>

	var flag = 0;
	var chk_purchase_type = $('.purchase_type');
    for(var x = 0; x < chk_purchase_type.length; x++){
    	if (chk_purchase_type[x].value==""){
        	flag=1;
        }
    }
    if(flag==1){
		msg	+="Purchase Type was not selected properly!.";
	}
	if(msg!="") {
		toastr["warning"](msg, "You have missing input!");
		return false;
	}
	else {
		toastr["info"]('This action cannot be reversed. <br/><a onclick="javascript: submitpo('+request_id+')" class="btn btn-success btn-sm">Finalize Orders</a>', "Are you sure?");
		return true;
	}
}
function submitpo(request_id) {
	$('body').addClass('is-loading');
	$('#modalDialog').modal('toggle');
	document.getElementById("purchase_form"+request_id).submit();
}
</script>