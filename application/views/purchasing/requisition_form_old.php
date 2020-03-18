<?php
	$heading	=	"New Request";
	$r			=	array("requisitioner"=>"","vessel_id"=>"","department_id"=>"","remark"=>"","stat"=>"","status"=>"","priority"=>"","added_by"=>"","purpose"=>"","approved_by"=>"");
	$action		=	HTTP_PATH."purchasing/requisition/insert";
	$useroptions	=	"";
	if(isset($request)) {
		$heading=	"Edit Request";
		$action	=	HTTP_PATH."purchasing/requisition/update/".$request['id'];
		$r=$request;
		$this->Mmm->debug($r);
	}
	if(!empty($users)) {
		foreach($users as $u) {
			$useroptions	.=	"<option value='".$u['id']."'>".$u['last_name'].", ".$u['first_name']."</option>";
		}
	}
	$approveroptions	=	"";
	if(!empty($approvers)) {
		foreach($approvers as $u) {
			$approveroptions	.=	"<option ".($r['approved_by']==$u['id'] ? "selected":"")." value='".$u['id']."'>".$u['last_name'].", ".$u['first_name']."</option>";
		}
	}
	$vesseloptions	=	"";
	if(!empty($vessels)) {
		foreach($vessels as $v) {
			$vesseloptions	.=	"<option ".($r['vessel_id']==$v->id ? "selected":"")." value='".$v->id."'>".$v->name."</option>";
		}
	}
	$departmentoptions	=	"";
	if(!empty($departments)) {
		foreach($departments as $d) {
			$departmentoptions	.=	"<option ".($r['department_id']==$d->id ? "selected":"")." value='".$d->id."'>".$d->name."</option>";
		}
	}
	$detailform	=	"
		<div class='row item-row'>
			<div class='col-sm-3'>
				<label for='item[]'>Item</label>
				<input type='text' id='itemname[]' name='itemname[]'  placeholder='Item' class='itemname form-control' value='' />
				<input type='text' id='itemvalue[]' name='itemvalue[]' placeholder='Item' class='itemvalue hide form-control' value='' />
			</div>
			<div class='col-sm-2'>
				<label for='quantity[]'>Quantity</label>
				<input type='text' id='quantity[]' name='quantity[]'  placeholder='Quantity' class='form-control quantity' value='' />
			</div>
			<div class='col-sm-3'>
				<label for='item_remark[]'>Remark</label>
				<input type='text' id='item_remark[]' name='item_remark[]'  placeholder='Remark' class='form-control' value='' />
			</div>
			<div class='col-sm-3'>
				<label for='assign_to[]'>Assign To</label>
				<select name='assign_to[]' id='assign_to[]' class='form-control'>
					<option value='0'>Choose One</option>
					".$useroptions."
				</select>
			</div>

				<a class='btn-remove-row btn btn-danger btn-xs' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>

		</div>
	";
	if(isset($request_details)) {
		$detailform	=	"";
		foreach($request_details as $rd) {
			$edituseroptions	=	"";
			if(!empty($users)) {
				foreach($users as $u) {
					$edituseroptions	.=	"<option ".($rd['assigned_to']==$u['id'] ? "selected":"")." value='".$u['id']."'>".$u['last_name'].", ".$u['first_name']."</option>";
				}
			}
			if(strtolower($rd['status'])!=strtolower("For Request Approval")) { $disabled="disabled"; }
			else { $disabled=""; }
			$item		=	$this->Inventory_model->getItem($rd['item_id']);
			$item		=	$item[0];
			$detailform	.=	"
			<div class='row ".($disabled=="" ? "item-row":"")."'>
				<div class='col-sm-3'>
					<label for='item[]'>Item</label>
					<input type='text' id='itemname[]' name='itemname[]' ".$disabled."  placeholder='Item' class='itemname form-control' value='".$item['description']."' />
					<input type='text' id='itemvalue[]' name='itemvalue[]' ".$disabled." placeholder='Item' class='itemvalue hide form-control' value='".$item['id']."' />
				</div>
				<div class='col-sm-1'>
					<label for='quantity[]'>Quantity</label>
					<input type='text' id='quantity[]' name='quantity[]' ".$disabled." placeholder='Quantity' class='form-control quantity' value='".$rd['quantity']."' />
				</div>
				<div class='col-sm-3'>
					<label for='item_remark[]'>Remark</label>
					<input type='text' id='item_remark[]' name='item_remark[]' ".$disabled." placeholder='Remark' class='form-control' value='".$rd['remark']."' />
				</div>
				<div class='col-sm-3'>
					<label for='assign_to[]'>Assign To</label>
					<select name='assign_to[]' id='assign_to[]' class='form-control' ".$disabled." ".($disabled!="" ? "title='Editable in canvassing'":"").">
						<option value='0'>Choose One</option>
						".$edituseroptions."
					</select>
				</div>

				".($disabled=="" ? "

					<a class='btn-remove-row btn btn-danger btn-xs'><span class='glyphicon glyphicon-minus'></span></a>
					<a class='btn-remove-row btn btn-danger btn-xs' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>

					":"")."

			</div>
			";
		}
	}
?>
<div class='panel panel-success'>
	<div class='panel-heading'><?php echo $heading; ?></div>
	<div class='panel-body'>
		<form action='<?php echo $action; ?>' role='form' method='POST' id='request_form' enctype='multipart/form-data'>
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="panel-group" id="requisitionFormDivider" role="tablist" aria-multiselectable="true">
				<div class="panel panel-primary">
					<div class="panel-heading" role="tab" id="summary">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#requisitionFormDivider" href="#requestSummary" aria-expanded="true" aria-controls="requestSummary">
							Summary
							</a>
						</h4>
					</div>
					<div id="requestSummary" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="summary">
						<div class="panel-body">
							<div class='col-sm-12 col-md-7'>
								<label for='requisitioner0'>Requisitioner</label>
								<input type='text' id='requisitioner0' name='requisitioner'  placeholder='Requisitioner' class='form-control' value='<?php echo $r['requisitioner']; ?>' />
							</div>
							<div class='col-sm-6 col-md-5'>
								<label for='vessel1'>Vessel/Office</label>
								<select name='vessel' id='vessel1' class='form-control'>
									<option value=''>Choose One</option>
									<?php echo $vesseloptions; ?>
								</select>
							</div>
							<div class='col-sm-6 col-md-4'>
								<label for='department2'>Department</label>
								<select name='department' id='department2' class='form-control'>
									<option value=''>Choose One</option>
									<?php echo $departmentoptions; ?>
								</select>
							</div>
							<div class='col-sm-12 col-md-4'>
								<label for='priority3'>Priority</label>
								<select id='priority3' name='priority' class='form-control'>
									<option value=''>Choose One</option>
									<option <?php echo ($r['priority']=="High"?"selected":""); ?> value='High'>High Priority</option>
									<option <?php echo ($r['priority']=="Medium" || $r['priority']=="" ?"selected":""); ?> value='Medium'>Medium Priority</option>
									<option <?php echo ($r['priority']=="Low"?"selected":""); ?> value='Low'>Low Priority</option>
								</select>
							</div>
							<div class='col-sm-6 col-md-4'>
								<label for='approved_by'>Approving Body</label>
								<select name='approved_by' id='approved_by' class='form-control'>
									<option value=''>Any Manager</option>
									<?php echo $approveroptions; ?>
								</select>
							</div>
							<div class='col-sm-12 col-md-12'>
								<label for='remark4'>Remark/Purpose</label>
								<textarea id='remark4' name='remark' class='form-control'><?php echo $r['remark']; ?></textarea>
							</div>
							<!--div class='col-sm-12 col-md-12'>
								<label for='purpose5'>Purpose</label>
								<textarea id='purpose5' name='purpose' class='form-control'><?php echo $r['purpose']; ?></textarea>
							</div-->
						</div>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="details">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#requisitionFormDivider" href="#requestDetails" aria-expanded="false" aria-controls="requestDetails">
							Items
							</a>
						</h4>
					</div>
					<div id="requestDetails" class="panel-collapse collapse" role="tabpanel" aria-labelledby="details">
						<div class="pull-left">
							<a id="btn_add_row" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>
							<a id="btn_remove_row" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
							<a data-placement="bottom" class='hide add_item btn btn-info btn-xs'>Add Item</a>
						</div>
						<div class="clearfix"><br/></div>
						<div class="panel-body item-row-container">
							<?php echo $detailform; ?>
						</div>
					</div>
				</div>
			</div>

			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<input type='button' value='Submit' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkautoform()' />
			</div>
		</form>
	</div>
</div>
<div id="add_item_head" class="hide">
	Add Item
</div>
<div id="add_item_content" class="hide">
	<form action="<?php echo HTTP_PATH; ?>" role='form' method='POST' id='item_entry' onsubmit='javascript: checkitemform()' enctype='multipart/form-data'>
		<div class='col-sm-12 col-md-6'>
			<label for='additem_itemcode'>Item Code</label>
			<input type='text' id='additem_itemcode' name='additem_itemcode'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-12 col-md-6'>
			<label for='additem_description'>Description</label>
			<input type='text' id='additem_description' name='additem_description'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-12 col-md-6'>
			<label for='additem_particular'>Particular</label>
			<input type='text' id='additem_particular' name='additem_particular'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-12 col-md-6'>
			<label for=''>Unit</label>
			<input type='text' id='additem_unit' name='additem_unit'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-12 col-md-6'>
			<label for=''>Unit Price</label>
			<input type='text' id='additem_unitprice' name='additem_unitprice'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-12 col-md-6'>
			<label for='additem_qty'>Quantity</label>
			<input type='text' id='additem_quantity' name='additem_quantity'  placeholder='' class='form-control' value='' />
		</div>
		<div class='col-sm-12 col-md-6'>
			<label for='additem_location'>Location</label>
			<input type='text' id='additem_location' name='additem_location'  placeholder='' class='form-control' value='' />
		</div>

		<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
		<div class='col-xs-12 col-sm-12 col-lg-12'>
			<input type='button' value='Submit' name='btnSubmit' id='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkitemform()' />
		</div>
		<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
	</form>
</div>
<script>
	$("#btn_remove_row").click(function(){
		$('.item-row:last').remove();
	});
	$(document).on('click', '.btn-remove-row', function() {
		$(this).parent().remove();
	});
	$("#btn_add_row").click(function(){
		$( ".itemname" ).autocomplete( "destroy" );
		$('.item-row-container').append("<div class='row item-row'><div class='col-sm-3'><label for='item[]'>Item</label><input type='text' id='itemname[]' name='itemname[]'  placeholder='Item' class='itemname form-control' value='' /><input type='text' id='itemvalue[]' name='itemvalue[]' placeholder='Item' class='hide form-control' value='' /></div><div class='col-sm-2'><label for='quantity[]'>Quantity</label><input type='text' id='quantity[]' name='quantity[]'  placeholder='Quantity' class='form-control quantity' value='' /></div><div class='col-sm-3'><label for='item_remark[]'>Remark</label><input type='text' id='item_remark[]' name='item_remark[]'  placeholder='Remark' class='form-control' value='' /></div><div class='col-sm-3'><label for='assign_to[]'>Assign to</label><select name='assign_to[]' id='assign_to[]' class='form-control'><option value='0'>Choose One</option><?php echo $useroptions; ?></select></div><a class='btn-remove-row btn btn-danger btn-xs'  style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a></div>");
		$( ".itemname" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>purchasing/autocomplete_request",
			minLength: 2,
			search: function(event, ui) {
				toastr['info']('Loading, please wait...');
			},
			response: function(event, ui) {
				if (ui.content.length === 0) {
					toastr.clear();
					toastr["warning"]("Item not found! Add an item with the button in the item panel so it will show up when you search it.", "ABAS Says");
				}
				else {
					toastr.clear();
				}
			},
			select: function( event, ui ) {
				$(this).prop("disabled", true);
				$( this ).val( ui.item.label );
				$( this ).next().val( ui.item.value );
				$(".quantity").focus();

				return false;
			}
		});

		// $('.form-requisition-quantity').on('input', function() {
		// saveCursorPosition(this);
		// });
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
	function checkautoform() {
		$(this).prop("disabled", true);
		var msg="";
		//var patt1=/^[0-9]+$/i;
		var patt1=/^\d+(\.\d+)*$/i;
		var requisitioner0=document.forms.request_form.requisitioner0.value;
		if (requisitioner0==null || requisitioner0=="" || requisitioner0=="Requisitioner") {
			msg+="Requisitioner in summary is required! <br/>";
		}
		var vessel1=document.forms.request_form.vessel1.value;
		if (vessel1==null || vessel1=="" || vessel1=="Vessel/Office") {
			msg+="Vessel/Office in summary is required! <br/>";
		}
		var department2=document.forms.request_form.department2.value;
		if (department2==null || department2=="" || department2=="Department") {
			msg+="Department in summary is required! <br/>";
		}
		var priority3=document.forms.request_form.priority.selectedIndex;
		if (priority3==null || priority3=="" || priority3=="Priority") {
			msg+="Priority in summary is required! <br/>";
		}
		// var remark4=document.forms.request_form.remark4.value;
		// if (remark4==null || remark4=="" || remark4=="Remark") {
			// msg+="Remark is required! <br/>";
		// }
		// var purpose5=document.forms.request_form.purpose5.value;
		// if (purpose5==null || purpose5=="" || purpose5=="Purpose") {
			// msg+="Purpose is required! <br/>";
		// }

		// check items if they were selected from dropdown
		var $nonempty = $('.itemvalue').filter(function() { return this.value != ''; });
		if ($nonempty.length == 0) {
			msg+="An item was not selected properly! Please select it in the dropdown after typing.<br/>";
		}

		if(msg!="") {
			$(this).prop("disabled", false);
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			$(this).prop("disabled", false);
			$('body').addClass('is-loading');
			$('#modalDialog').modal('toggle');
			document.getElementById("request_form").submit();
			return true;
		}
	}
	$( ".itemname" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>purchasing/autocomplete_request",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr["warning"]("Item not found! Click <a class='add_item'>here</a> to add.", "ABAS Says");
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$(this).prop("disabled", true);
			$( this ).val( ui.item.label );
			$( this ).next().val( ui.item.value );
			return false;
		}
	});
	$('.add_item').popover({
		html : true,
		container : "body",
		title: function() {
			return $("#add_item_head").html();
		},
		content: function() {
			return $("#add_item_content").html();
		}
	});
	function checkitemform() {
		var msg="";
		//var patt1=/^[0-9]+$/i;
		var patt1=/^\d+(\.\d+)*$/i;
		var ai_itemcode=document.forms.item_entry.additem_itemcode.value;
		if (ai_itemcode==null || ai_itemcode=="" || ai_itemcode=="Item Code") {
			msg+="Item Code is required! <br/>";
		}
		var ai_desc=document.forms.item_entry.additem_description.value;
		if (ai_desc==null || ai_desc=="" || ai_desc=="Description") {
			msg+="Description is required! <br/>";
		}
		var ai_particular=document.forms.item_entry.additem_particular.value;
		if (ai_particular==null || ai_particular=="" || ai_particular=="") {
			msg+="Particular is required! <br/>";
		}
		var ai_unit=document.forms.item_entry.additem_unit.value;
		if (ai_unit==null || ai_unit=="" || ai_unit=="") {
			msg+="Unit is required! <br/>";
		}
		var ai_unitprice=document.forms.item_entry.additem_unitprice.value;
		if (ai_unitprice==null || ai_unitprice=="" || ai_unitprice=="") {
			msg+="Unit Price is required! <br/>";
		}
		var ai_quantity=document.forms.item_entry.additem_quantity.value;
		if (ai_quantity==null || ai_quantity=="" || ai_quantity=="") {
			msg+="Quantity is required! <br/>";
		}
		var ai_location=document.forms.item_entry.additem_location.value;
		if (ai_location==null || ai_location=="" || ai_location=="") {
			msg+="Location is required! <br/>";
		}

		if(msg!="") {
			toastr['warning'](msg,"ABAS Says");
			return false;
		}
		else {
			var dataString = {
				"item_code":ai_itemcode,
				"description":ai_description,
				"particular":ai_particular,
				"unit":ai_unit,
				"unit_price":ai_unit_price,
				"qty":ai_quantity,
				"location":ai_location,
			};
			toastr['info']("","Please wait...");
			$.ajax({
				type: "POST",
				url: "<?php echo HTTP_PATH; ?>home/encode/inventory_items/insert",
				data: dataString,
				cache: false,
				success: function(html) {
					toastr['success']("","Item Added!");
				},
				error: function(html) {
					toastr['error']("","Item Not Added!");
				}
			});
			$("#add_charterer").popover('hide');
		}
	}
</script>
