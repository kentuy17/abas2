<?php
	$heading	=	"Add Materials/Services Request";
	$r			=	array("requisitioner"=>"","vessel_id"=>"","truck_id"=>"","department_id"=>"","remark"=>"","stat"=>"","status"=>"","priority"=>"","added_by"=>"","purpose"=>"","approved_by"=>"");
	$action		=	HTTP_PATH."purchasing/requisition/insert";
	$useroptions	=	"";
	$requisitioner = "";
	$company = "";
	$company_id="";
	$reference_no = "";
	$priority = "";
	if(isset($request)) {
		$heading=	"Edit Materials/Services Request";
		$action	=	HTTP_PATH."purchasing/requisition/update/".$request['id'];
		$r=$request;
		//$this->Mmm->debug($r);
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
	$truckoptions	=	"";
	if(!empty($trucks)) {
		foreach($trucks as $t) {
			$truckoptions	.=	"<option ".($r['truck_id']==$t['id'] ? "selected":"")." value='".$t['id']."'>".$t['plate_number']."</option>";
		}
	}
	$detailform	=	"
		<div class='row item-row'>
			<div class='col-xs-12 col-md-11 col-lg-11'>
				<label for='item[]'>Item/Service*</label>
				<input type='text' id='itemname[]' name='itemname[]'  placeholder='Item/Service' class='itemname form-control' value='' required/>
				<input type='hidden' id='itemvalue[]' name='itemvalue[]' class='itemvalue form-control' value='' />
				<input type='hidden' id='itemunit[]' name='itemunit[]' class='itemunit form-control' value='' />
			</div>
			<div class='col-xs-12 col-md-3 col-lg-3'>
				<label for='quantity[]'>Quantity*</label>
				<input type='number' id='quantity[]' name='quantity[]'  placeholder='Quantity' class='form-control quantity' value='' required/>
			</div>
			<div class='col-xs-12 col-md-3 col-lg-3'>
				<label for='packaging[]'>Unit/Packaging*</label>
				<select name='packaging[]' id='packaging[]' class='packaging form-control' required>
					<option value=''>Select</option>
				</select>
			</div>
			<div class='col-xs-12 col-md-5 col-lg-5'>
				<label for='assign_to[]'>Assign To Purchaser*</label>
				<select name='assign_to[]' id='assign_to[]' class='form-control'>
					<option value=''>Select</option>
					".$useroptions."
				</select>
			</div>
			<div class='col-xs-12 col-md-11 col-lg-11'>
				<label for='item_remark[]'>Remarks</label>
				<input type='text' id='item_remark[]' name='item_remark[]'  placeholder='Remarks' class='form-control' value='' />
			</div>
			<div class='col-sm-12'>
			<hr>
			</div>
				<a class='btn-remove-row btn btn-danger btn-xs' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
		</div>";

$detailform_append= trim(preg_replace('/\s+/',' ', $detailform));

	if(isset($request_details)) {
		$detailform	=	"";
		$requisitioner = $request['requisitioner'];
		$vessel = $this->Abas->getVessel($request['vessel_id']);
		$company = $this->Abas->getCompany($vessel->company)->name;
		$company_id = $vessel->company;
		$reference_no = $request['reference_number'];
		$priority = $request['priority'];
		foreach($request_details as $rd) {
			if($rd['item_id']!=''){
				$edituseroptions	=	"";
				if(!empty($users)) {
					foreach($users as $u) {
						$edituseroptions	.=	"<option ".($rd['assigned_to']==$u['id'] ? "selected":"")." value='".$u['id']."'>".$u['last_name'].", ".$u['first_name']."</option>";
					}
				}
				if(strtolower($rd['status'])!=strtolower("For Request Approval")) { $disabled="readonly"; }
				else { $disabled=""; }
				$item		=	$this->Inventory_model->getItem($rd['item_id']);
				$packaging_option = "";
				$packagings = $this->Inventory_model->getPackagingByItem($rd['item_id']);
				if(count($packagings)>0){
					foreach($packagings as $packaging){
						$packaging_option	.=	"<option ".($packaging->packaging==$rd['packaging'] ? "selected":"")." value='".$packaging->packaging."'>".$packaging->packaging."</option>";
					}
					if($rd['unit']=='' || $rd['packaging']==''){
						$packaging_option	.=	"<option value='".$item[0]['unit']."' selected>".$item[0]['unit']."</option>";
					}else{
						$packaging_option	.=	"<option value='".$rd['unit']."'>".$rd['unit']."</option>";
					}
					$itemunit = $item[0]['unit'];
				}else{
					if($rd['unit']=='' || $rd['packaging']==''){
						$packaging_option	.=	"<option value='".$item[0]['unit']."' selected>".$item[0]['unit']."</option>";
					}else{
						$packaging_option	.=	"<option value='".$rd['unit']."' selected>".$rd['unit']."</option>";
					}
					$itemunit = $item[0]['unit'];
				}
				$item		=	$item[0];
				$detailform	.=	"
				<div class='row ".($disabled=="" ? "item-row":"")."'>
					<div class='col-xs-12 col-md-11 col-lg-11'>
						<label for='item[]'>Item/Service*</label>
						<input type='text' id='itemname[]' name='itemname[]' ".$disabled."  placeholder='Item' class='itemname form-control' value='".$item['item_code']." | ".$item['description']." | ".$item['unit']."' />
						<input type='hidden' id='itemvalue[]' name='itemvalue[]' ".$disabled."class='itemvalue form-control' value='".$item['id']."' />
						<input type='hidden' id='itemunit[]' name='itemunit[]' ".$disabled." class='itemunit form-control' value='".$itemunit."' />
					</div>
					<div class='col-xs-12 col-md-4 col-lg-4'>
						<label for='quantity[]'>Quantity*</label>
						<input type='text' id='quantity[]' name='quantity[]' ".$disabled." placeholder='Quantity' class='form-control quantity' value='".$rd['quantity']."' />
					</div>
					<div class='col-xs-12 col-md-4 col-lg-4'>
						<label for='packaging[]'>Unit/Packaging*</label>
						<select name='packaging[]' id='packaging[]' class='packaging form-control' ".$disabled." required>
							<option value=''>Select</option>
							".$packaging_option."
						</select>
					</div>
					<div class='col-xs-12 col-md-4 col-lg-4'>
					<label for='assign_to[]'>Assign To Purchaser*</label>
						<select name='assign_to[]' id='assign_to[]' class='form-control'>
							<option value=''>Select</option>
							".$edituseroptions."
						</select>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-12'>
						<label for='item_remark[]'>Remarks</label>
						<input type='text' id='item_remark[]' name='item_remark[]' ".$disabled." placeholder='Remarks' class='form-control' value='".$rd['remark']."' />
					</div>
					".($disabled=="" ? "
						<div class='col-sm-12'>
						<hr>
						</div>
						<a class='btn-remove-row btn btn-danger btn-xs' style='margin-top:25px'><span class='glyphicon glyphicon-remove'></span></a>
						":"")."

				</div>";
			}
		}
	}
?>
<div class='panel panel-primary'>
	<div class='panel-heading'><h2 class="panel-title"><?php echo $heading; ?><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2></div></div>
	<div class='panel-body'>
		<form action='<?php echo $action; ?>' role='form' method='POST' id='request_form' enctype='multipart/form-data'>
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="panel-group" id="requisitionFormDivider" role="tablist" aria-multiselectable="true">
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="summary">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#requisitionFormDivider" href="#requestSummary" aria-expanded="true" aria-controls="requestSummary">
							Summary
							<span class="glyphicon glyphicon-chevron-down pull-right"></span>
							</a>
						</h4>
					</div>
					<div id="requestSummary" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="summary">
						<div class="panel-body">
							<div class='col-xs-12 col-md-3 col-lg-3'>
								<label for='reference_no'>Project Ref No. (If applicable)</label>
								<input type='text' id='reference_no' name='reference_no' class='form-control' value='<?php echo $reference_no;?>'/>
							</div>
							<div class='col-xs-12 col-md-9 col-lg-9'>
								<label for='company_name'>Company</label>
								<input type='text' id='company_name' name='company_name' class='form-control' value='<?php echo $company;?>' readonly/>
								<input type='hidden' id='company_id' name='company_id' class='form-control' value='<?php echo $company_id;?>' readonly/>
							</div>
							<div class='col-xs-12 col-md-7 col-lg-7'>
								<label for='requisitioner0'>Requisitioner's Name*</label>
								<input type='text' id='requisitioner0' name='requisitioner'  placeholder='Requisitioner' class='form-control' value='<?php echo $requisitioner?>' required/>
							</div>
							<div class='col-xs-12 col-md-5 col-lg-5'>
								<label for='vessel1'>Vessel/Office*</label>
								<select name='vessel' id='vessel1' class='form-control' required>
									<option value=''>Select</option>
									<?php echo $vesseloptions; ?>
								</select>
							</div>
							<div class='col-xs-12 col-md-7 col-lg-7'>
								<label for='department2'>Department*</label>
								<select name='department' id='department2' class='form-control' required>
									<option value=''>Select</option>
									<?php echo $departmentoptions; ?>
								</select>
							</div>
							<div class='col-xs-12 col-md-5 col-lg-5'>
								<label for='truck'>Truck</label>
								<select name='truck' id='truck' class='form-control' disabled>
									<option value=''>Select</option>
									<?php echo $truckoptions; ?>
								</select>
							</div>
							<div class='col-xs-12 col-md-3 col-lg-3'>
								<label for='date_needed'>Date Needed*</label>
								<input type='date' id='date_needed' name='date_needed' class='form-control' value='<?php echo date('Y-m-d',strtotime($request['tdate']))?>' required>
							</div>
							<div class='col-xs-12 col-md-4 col-lg-4'>
								<label for='priority3'>Priority</label>
								<!--<select id='priority3' name='priority' class='form-control' required>
									<option value=''>Select</option>
									<option <?php //echo ($r['priority']=="High"?"selected":""); ?> value='High'>High</option>
									<option <?php //echo ($r['priority']=="Medium" || $r['priority']=="" ?"selected":""); ?> value='Medium'>Medium</option>
									<option <?php //echo ($r['priority']=="Low"?"selected":""); ?> value='Low'>Low</option>
								</select>-->
								<input type='text' id='priority3' name='priority' class='form-control' value='<?php echo $priority?>' readonly>
							</div>
							<div class='col-xs-12 col-md-5 col-lg-5'>
								<label for='approved_by'>Approver*</label>
								<select name='approved_by' id='approved_by' class='form-control' required>
									<option value=''>Any Manager</option>
									<?php echo $approveroptions; ?>
								</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-12'>
								<label for='remark4'>Purpose*</label>
								<textarea id='remark4' name='remark' class='form-control' required><?php echo $r['remark']; ?></textarea>
							</div>
							
						</div>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="details">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#requisitionFormDivider" href="#requestItems" aria-expanded="false" aria-controls="requestItems">
							Materials and Services
							<span class="glyphicon glyphicon-chevron-down pull-right"></span>
							</a>
						</h4>
					</div>
					<div id="requestItems" class="panel-collapse collapse" role="tabpanel" aria-labelledby="details">
						<div class="pull-right" style="float:left; margin-top:5px; margin-left:5px">
							<a id="btn_add_row" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>
							<a id="btn_remove_row" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
						</div>
						<div class="clearfix"><br/></div>
						<div class="panel-body item-row-container" id='item_container'>
							<?php echo $detailform;?>
						</div>
					</div>
				</div>

			</div>

				
	</div>
	<div class="modal-footer">
		<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript:checkautoform();'/>
		<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
	</div>
</form>
<script>
	$("#btn_remove_row").click(function(){
		$('.item-row:last').remove();
	});
	$(document).on('click', '.btn-remove-row', function() {
		$(this).parent().remove();
	});
	$('#vessel1').change(function() 
	{   
	  var vessel_id = document.getElementById('vessel1').value;
	  if(vessel_id!=""){
	  	$('#reference_no').val('');
		  $.ajax({
		     type:"POST",
		     url:"<?php echo HTTP_PATH;?>/purchasing/get_company_name/"+vessel_id,
		     success:function(data){
		        var c = $.parseJSON(data);
		        $('#company_name').val(c.company_name);
		        $('#company_id').val(c.company_id);
		     } 	
		  });
	  }
	});	
	$( ".itemname" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>purchasing/autocomplete_request",
			minLength: 2,
			search: function(event, ui) {
				toastr['info']('Loading, please wait...');
			},
			response: function(event, ui) {
				if (ui.content.length === 0) {
					toastr.clear();
					toastr["warning"]("Item not found!", "ABAS Says");
				}
				else {
					toastr.clear();
				}
			},
			select: function( event, ui ) {
				$(this).prop("disabled", true);
				$(this).val( ui.item.label );
				$(this).next().val( ui.item.value );
				$(this).next().next().val( ui.item.unit );
				$(".quantity").focus();

				$.ajax({
					 type:"POST",
					 url:"<?php echo HTTP_PATH;?>purchasing/get_item_packaging/"+ui.item.value,
					 success:function(data){
					 
					 	 var packaging = data; 

					    	$('.packaging',this).find('option').remove().end().append('<option value="">Select</option>').val('');

					        for(var i = 0; i < packaging.length; i++){
					       		var item = packaging[i];
					       		var option = $('<option />');
							    option.attr('value',item.packaging).text(item.packaging);
							    $('.packaging').append(option);
					        }
					     
					       
					}
				});

				var option2 = $('<option />');
				 option2.attr('value',ui.item.unit ).text(ui.item.unit);
	 			 $('.packaging').append(option2);

				return false;
			}
		});
	$("#btn_add_row").click(function(){
		$( ".itemname" ).autocomplete( "destroy" );
		$('.item-row-container').append("<?php echo $detailform_append;?>");
		$( ".itemname" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>purchasing/autocomplete_request",
			minLength: 2,
			search: function(event, ui) {
				toastr['info']('Loading, please wait...');
			},
			response: function(event, ui) {
				if (ui.content.length === 0) {
					toastr.clear();
					toastr["warning"]("Item not found!", "ABAS Says");
				}
				else {
					toastr.clear();
				}
			},
			select: function( event, ui ) {
				$(this).prop("disabled", true);
				$(this).val( ui.item.label );
				$(this).next().val( ui.item.value );
				$(this).next().next().val( ui.item.unit );
				$(".quantity",this).focus();
				
				$.ajax({
					 type:"POST",
					 url:"<?php echo HTTP_PATH;?>purchasing/get_item_packaging/"+ui.item.value,
					 success:function(data){
					 
					 	 var packaging = data; 

					    	$('.packaging',this).find('option').remove().end().append('<option value="">Select</option>').val('');

					        for(var i = 0; i < packaging.length; i++){
					       		var item = packaging[i];
					       		var option = $('<option />');
							    option.attr('value',item.packaging).text(item.packaging);
							    $('.packaging').append(option);
					        }

					}
				});

				 var option2 = $('<option />');
				 option2.attr('value',ui.item.unit ).text(ui.item.unit);
	 			 $('.packaging').append(option2);

				return false;
			}
		});
	});

	function checkautoform() {
		$(this).prop("disabled", true);
		var msg="";
		var requisitioner0=document.forms.request_form.requisitioner0.value;
		if (requisitioner0==null || requisitioner0=="" || requisitioner0=="Requisitioner") {
			msg+="Requisitioner is required! <br/>";
		}
		var vessel1=document.forms.request_form.vessel1.value;
		if (vessel1==null || vessel1=="" || vessel1=="Vessel/Office") {
			msg+="Vessel/Office is required! <br/>";
		}
		var department2=document.forms.request_form.department2.value;
		if (department2==null || department2=="" || department2=="Department") {
			msg+="Department is required! <br/>";
		}
		var priority3=document.forms.request_form.priority.value;
		if (priority3==null || priority3=="" || priority3=="Priority") {
			msg+="Priority is required! <br/>";
		}
		var purpose=document.forms.request_form.remark4.value;
		if (purpose==null || purpose=="") {
			msg+="Purpose is required! <br/>";
		}
	    var item_divs = document.getElementsByClassName('item-row'); 
    	var item_inputs = $('#item_container').find('input').filter('[required]:visible');
    	var item_flag=0;
    	var no_item=0;
    	if(item_divs.length > 0){
	        for(var x = 0; x < item_inputs.length; x++){
	        	if (item_inputs[x].value==""){
	            	item_flag=1;
	            }
	        }
	       
	    }
	    var item_divs = document.getElementsByClassName('item-row'); 
    	var item_inputs = $('#item_container').find('select').filter('[required]:visible');
    	var item_flag=0;
    	var no_item=0;
    	if(item_divs.length > 0){
	        for(var x = 0; x < item_inputs.length; x++){
	        	if (item_inputs[x].value==""){
	            	item_flag=1;
	            }
	        }
	       
	    }

	    if(item_flag==1){
        	msg+="Please fill-out all required* fields in Materials Tab!<br/>";
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

	$( "#reference_no" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>purchasing/project_reference_autocomplete_list",
		minLength: 1,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				toastr.clear();
				toastr['warning']	=	"No Project Reference No. found!";
			}
			else {
				toastr.clear();
			}
		},
		select: function( event, ui ) {
			$( "#reference_no" ).val( ui.item.label );
			$( "#company_name" ).val('');
			$( "#company_name" ).val( ui.item.company );
			$( "#company_id" ).val( ui.item.company_id );
			$( "#vessel1" ).val('');
			$( "#vessel1" ).val( ui.item.asset );
			if(ui.item.asset==99994){
				$("#truck").val('');
				$("#truck").prop('disabled', false);
			}else{
				$("#truck").val('');
				$("#truck").prop('disabled', true);
			}
			return false;
		}
	});

	$("#vessel1" ).click(function(){
		var vessel = $(this).val();
		if(vessel==99994){
			$("#truck").val('');
			$("#truck").prop('disabled', false);
		}else{
			$("#truck").val('');
			$("#truck").prop('disabled', true);
		}
	});

	$("#date_needed" ).change(function(){
		var date_needed = $(this).val();

		var startDate = Date.now();
		var endDate   = date_needed;
		var timeDiff  = (new Date(startDate)) - (new Date(endDate));
		var days      = parseFloat(Math.abs(timeDiff / (1000 * 60 * 60 * 24))).toFixed(2);

		if(days<=1){
			$("#priority3").val('Emergency');
		}else if(days<=3){
			$("#priority3").val('High');
		}else if(days<=7){
			$("#priority3").val('Medium');
		}else if(days<=10){
			$("#priority3").val('Low');
		}else{
			$("#priority3").val('Low');
		}

	});
	
</script>
