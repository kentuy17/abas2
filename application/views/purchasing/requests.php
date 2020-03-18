<?php
	$display_complete_request	=	false;
	$hide_buttons				=	false;
	if(!empty($sorted_by_status)) {
		foreach($sorted_by_status as $status=>$sorted_by_priority) {
			if(!empty($sorted_by_priority)) {
				foreach($sorted_by_priority as $priority=>$requests) {
					if(!empty($requests)) {
						$start_time	=	microtime(true);
						if($display_complete_request==false) {
							$display[$status][$priority]	=	"<h3>".ucwords(strtolower($priority))." Priority</h3>";
							$display[$status][$priority]	.=	"<table class='table table-bordered table-striped table-hover'>";
							$display[$status][$priority]	.=	"<tr>";
							$display[$status][$priority]	.=	"<th data-visible='false' data-align='center' data-sortable='false'>Request Number</th>";
							$display[$status][$priority]	.=	"<th data-visible='false' data-align='center' data-sortable='false'>Vessel</th>";
							$display[$status][$priority]	.=	"<th data-visible='false' data-align='center' data-sortable='false'>Department</th>";
							// $display[$status][$priority]	.=	"<th data-visible='false' data-align='center' data-sortable='false'>Items</th>";
							$display[$status][$priority]	.=	"<th data-visible='false' data-align='center' data-sortable='false'>Requested On</th>";
							$display[$status][$priority]	.=	"<th data-visible='false' data-align='center' data-sortable='false'>Approver</th>";
							$display[$status][$priority]	.=	"<th data-visible='false' data-align='center' data-sortable='false'>Prepared By</th>";
							$display[$status][$priority]	.=	"<th data-visible='false' data-align='center' data-sortable='false'>Transaction Code</th>";
							$display[$status][$priority]	.=	"<th data-visible='false' data-align='center' data-sortable='false'>Manage</th>";
							$display[$status][$priority]	.=	"</tr>";
						}
						foreach($requests as $request) {
							if(!empty($request)) {
								if($display_complete_request==true) {
									$display_current	=	$this->Purchasing_model->renderRequest($request['id'], str_replace(" ", "", ucwords(strtolower($status)))."Collapsible", $hide_buttons);
									if(isset($display[$status][$priority])) {
										$display[$status][$priority]	.=	$display_current;
									}
									else {
										$display[$status][$priority]	=	$display_current;
									}
								}
								if($display_complete_request==false) {
									if($priority=="Emergency") $aging_start		=	date("Y-m-d H:i:s", strtotime($request['tdate']."+24 hours"));
									if($priority=="High") $aging_start		=	date("Y-m-d H:i:s", strtotime($request['tdate']."+3 hours"));
									if($priority=="Medium") $aging_start	=	date("Y-m-d H:i:s", strtotime($request['tdate']."+7 days"));
									if($priority=="Low") $aging_start		=	date("Y-m-d H:i:s", strtotime($request['tdate']."+10 days"));
									$aging							=	ceil((strtotime($aging_start)-strtotime(date("Y-m-d H:i:s")))/(60*60*24));
									if($aging<0) $aging				=	" (".abs($aging)." days late)";
									else $aging						=	"";
									$added_by						=	$this->Abas->getUser($request['added_by']);
									$approved_by					=	$this->Abas->getUser($request['approved_by']);
									$request['added_on']			=	date("j F Y",strtotime($request['tdate'])).$aging;
									$view_button					=	"<a href='".HTTP_PATH."purchasing/requisition/view/".$request['id']."' class='btn btn-xs btn-primary'>View</a>";
									$display[$status][$priority]	.=	"<tr>";
									$display[$status][$priority]	.=	"<td>".$request['control_number']."</td>";
									$display[$status][$priority]	.=	"<td>".$request['vessel_name']."</td>";
									$display[$status][$priority]	.=	"<td>".$request['department_name']."</td>";
									// $display[$status][$priority]	.=	"<td>".count($request['details'])."</td>";
									$display[$status][$priority]	.=	"<td>".$request['added_on']."</td>";
									$display[$status][$priority]	.=	"<td>".$approved_by['full_name']."</td>";
									$display[$status][$priority]	.=	"<td>".$added_by['full_name']."</td>";
									$display[$status][$priority]	.=	"<td>".$request['id']."</td>";
									$display[$status][$priority]	.=	"<td>".$view_button."</td>";
									$display[$status][$priority]	.=	"</tr>";
								}
							}
						}
						if($display_complete_request==false) $display[$status][$priority]	.=	"</table>";
						$end_time	=	microtime(true);
						$duration	=	number_format((($end_time-$start_time)*60), 2);
						//echo "<pre>"."System display duration for requests ".$status." - ".$priority.": ".$duration." seconds (".count($requests)." requests for this category)</pre>";
					}
				}
			}
		}
	}
?>
<div class="col-xs-12 col-m-12">
	<div class="pull-right">
		<br>
		<label for="search">Requisition Transaction Code:</label>
		<table>
			<tr>
			<td><input type="text" name="search" id="search" placeholder="Transaction Code" class="form-control" /></td>
			<td>&nbsp&nbsp<button class="btn btn-m btn-dark" id="findRequest" type="button">Search</button></td>
		</tr>
		</table>
	</div>
</div>
<div class="panel-group" role="tablist" aria-multiselectable="true">
	<div class="panel panel-default">
		<div class="panel-body">
			<ul class="nav nav-tabs">
				<li class="active nav-danger"><a data-toggle="tab" href="#forRequestApproval">For Request Approval</a></li>
				<li class="nav-warning"><a data-toggle="tab" href="#forCanvassing">For Canvassing</a></li>
				<li class="nav-info"><a data-toggle="tab" href="#forCanvassApproval">For Canvass Approval</a></li>
				<li class="nav-success"><a data-toggle="tab" href="#forPurchase">For Purchase</a></li>
			</ul>
			<div class="tab-content">
				<div id="forRequestApproval" class="tab-pane fade in active" style="overflow-x: scroll;">
					<div class="panel-group" id="ForRequestApprovalCollapsible" role="tablist" aria-multiselectable="true">
						<?php echo !empty($display['For Request Approval']['Emergency'])?$display['For Request Approval']['Emergency']:""; ?>
						<?php echo !empty($display['For Request Approval']['High'])?$display['For Request Approval']['High']:""; ?>
						<?php echo !empty($display['For Request Approval']['Medium'])?$display['For Request Approval']['Medium']:""; ?>
						<?php echo !empty($display['For Request Approval']['Low'])?$display['For Request Approval']['Low']:""; ?>
					</div>
				</div>
				<div id="forCanvassing" class="tab-pane fade" style="overflow-x: scroll;">
					<div class="panel-group" id="ForCanvassingCollapsible" role="tablist" aria-multiselectable="true">
						<?php echo !empty($display['For Canvassing']['Emergency'])?$display['For Canvassing']['Emergency']:""; ?>
						<?php echo !empty($display['For Canvassing']['High'])?$display['For Canvassing']['High']:""; ?>
						<?php echo !empty($display['For Canvassing']['Medium'])?$display['For Canvassing']['Medium']:""; ?>
						<?php echo !empty($display['For Canvassing']['Low'])?$display['For Canvassing']['Low']:""; ?>
					</div>
				</div>
				<div id="forCanvassApproval" class="tab-pane fade" style="overflow-x: scroll;">
					<div class="panel-group" id="ForCanvassApprovalCollapsible" role="tablist" aria-multiselectable="true">
						<?php echo !empty($display['For Canvass Approval']['Emergency'])?$display['For Canvass Approval']['Emergency']:""; ?>
						<?php echo !empty($display['For Canvass Approval']['High'])?$display['For Canvass Approval']['High']:""; ?>
						<?php echo !empty($display['For Canvass Approval']['Medium'])?$display['For Canvass Approval']['Medium']:""; ?>
						<?php echo !empty($display['For Canvass Approval']['Low'])?$display['For Canvass Approval']['Low']:""; ?>
					</div>
				</div>
				<div id="forPurchase" class="tab-pane fade" style="overflow-x: scroll;">
					<div class="panel-group" id="ForPurchaseCollapsible" role="tablist" aria-multiselectable="true">
						<?php echo !empty($display['For Purchase']['Emergency'])?$display['For Purchase']['Emergency']:""; ?>
						<?php echo !empty($display['For Purchase']['High'])?$display['For Purchase']['High']:""; ?>
						<?php echo !empty($display['For Purchase']['Medium'])?$display['For Purchase']['Medium']:""; ?>
						<?php echo !empty($display['For Purchase']['Low'])?$display['For Purchase']['Low']:""; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="editUnitPriceTitle" class="hide">Update Unit Price</div>
<div id="editUnitPriceForm" class="hide">
	<form id="edit_unit_price_form" name="edit_unit_price_form" action="<?php echo HTTP_PATH."purchasing/requisition_item/edit_price/"; ?>" method="POST" enctype='multipart/form-data'>
		<?php echo $this->Mmm->createCSRF(); ?>
		Total: <input type='text' class='input-sm form-control' name="unit_price" id="edit_unit_price_input" />
		<input type='button' class='btn-small' value='Save' />
	</form>
</div>
<script>
$('#findRequest').click(function() {
	var requestid=$("#search").val();
	if(Number.isNaN(requestid)==true) {
		toastr['warning']("Invalid Transaction Code", "ABAS Says");
	}
	else {
		window.location="<?php echo HTTP_PATH."purchasing/requisition/view/"; ?>"+requestid;
	}
});
$('.edit-unit-price').popover({
	html : true,
	title: function() {
		return $("#editUnitPriceTitle").html();
	},
	content: function() {
		// update action of #edit_unit_price_form to reflect ID of request detail
		return $("#editUnitPriceForm").html();
	}
});
function validateEmail(email) {
	var re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}
function validateRadio (radios)	{
	for (var i = 0; i < radios.length; i++)	{
		if (radios[i].checked) {return true;}
	}
	return false;
}
function checkrequestitemform() {
	var msg="";
	//var patt1=/^[0-9]+$/i;
	var patt1=/^\d+(\.\d+)*$/i;
	var company0=document.forms.new_item_request.company0.value;
	if (company0==null || company0=="" || company0=="Company") {
		msg+="Company is required! <br/>";
	}
	var type3=document.forms.new_item_request.type.selectedIndex;
	if (type3==null || type3=="" || type3=="Type") {
		msg+="Type is required! <br/>";
	}
	var amount7=document.forms.new_item_request.amount7.value;
	if (amount7==null || amount7=="" || amount7=="Total Amount") {
		msg+="Total Amount is required! <br/>";
	}
	else if (!patt1.test(amount7)) {
		msg+="Only numbers are allowed in Total Amount! <br/>";
	}

	if(msg!="") {
		toastr['warning'](msg,"ABAS Says");
		return false;
	}
	else {
		document.getElementById("new_item_request").submit(); return true;
	}
}
function toggleHide(e) {
	$(".canvassdetails"+e).toggleClass("hide");
}
function confirmSaveCanvass() {
	toastr.clear();
	toastr['warning']('<a class="btn btn-success btn-sm" onclick="javascript: document.getElementById(\'canvass_form\').submit();">Save Canvass</a>', "Are you sure?");
}
function createPO(requestid) {
	toastr.clear();
	toastr['warning']('This will create a PO from all items that are marked as "for purchase" <a class="btn btn-success btn-sm" data-target="#modalDialog" data-toggle="modal" href="<?php echo HTTP_PATH; ?>purchasing/requisition/create_po/'+requestid+'">Continue</a>', "Are you sure?");
}
function cancelRequest(requestid) {
	toastr.clear();
	toastr['warning']('This will cancel all items in this request. <a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/requisition/cancel/'+requestid+'">Continue</a>', "Are you sure?");
}
function confirmApproveRequestItem(itemid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/requisition_item/'+itemid+'/approve_request">Approve Item</a>', "Are you sure?");
}
function confirmApproveCanvassItem(itemid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/requisition_item/'+itemid+'/approve_canvass">Approve Item</a>', "Are you sure?");
}
function confirmCancelRequestItem(itemid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-danger btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/requisition_item/'+itemid+'/cancel">Cancel Item</a>', "Are you sure?");
}
function confirmCancelCanvassItem(itemid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-danger btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/canvass_details/'+itemid+'/cancel">Cancel Item</a>', "Are you sure?");
}
function confirmCancelCanvassItemDraft(itemid, draftid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-danger btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/canvass_details/'+itemid+'/cancel_draft/'+draftid+'">Cancel Item</a>', "Are you sure?");
}
function confirmApproveRequest(requestid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-success btn-sm" onclick="javascript:document.getElementById(\'request_approve_'+requestid+'\').submit();">Approve Selected</a>', "Are you sure?");
}
</script>