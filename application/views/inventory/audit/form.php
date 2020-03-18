<?php
$attributes = array('id'=>'inventory_audit_form','role'=>'form');
if(!isset($audit)){
	$title = "Add Inventory Count Sheet";
	$action = 'inventory/audit/insert';
	$company_id ='';
	$company_name ='';
	$audited_by = '';
	$audit_date ='';
	$location ='';
	$status ='';
	$disabled = '';
}else{
	$title = "Edit Inventory Count Sheet";
	$action = 'inventory/audit/update/'.$audit->id;
	$company_id =$audit->company_id;
	$company_name =$audit->company_name;
	$audited_by = $audit->audited_by;
	$audit_date = date('Y-m-d', strtotime($audit->audit_date));
	$status =$audit->status;
}

$company_options = "<option value=''>Select</option>";
if(!empty($companies)) {
	foreach($companies as $option) {
		if(isset($audit)){
			$company_options	.=	"<option ".($audit->company_id==$option->id ? "selected":"")." value='".$option->id."'>".$option->name."</option>";
		}else{
			if($option->name!="Avega Bros. Integrated Shipping Corp. (Staff)"){
				$company_options	.=	"<option value='".$option->id."'>".$option->name."</option>";
			}
		}
	}
	unset($option);
}

$category_options = "<option value=''>Select</option>";
if(!empty($categories)) {
	foreach($categories as $option) {
		if(isset($audit)){
			if($option->category!="Service"){
				$category_options	.=	"<option ".($audit->type_of_inventory==$option->id ? "selected":"")." value='".$option->id."'>".$option->category."</option>";
			}
		}else{
			if($option->category!="Service"){
				$category_options	.=	"<option value='".$option->id."'>".$option->category."</option>";
			}
		}
	}
	unset($option);
}

$location_options="<option value=''>Select</option>";
if(!empty($inventory_locations)) { 
	foreach($inventory_locations as $loc){
		if(isset($audit)){
			if($loc->location_name != 'Direct Delivery' ){
				$location_options	.=	"<option ".($audit->location==$loc->location_name ? "selected":"")." value='".$loc->location_name."'>".$loc->location_name."</option>";
			}
		}else{
		    if($loc->location_name != 'Direct Delivery' ){
		        $location_options	.='<option value="'.$loc->location_name.'">'.$loc->location_name.'</option>';
		    }
		}
	}
}

$docs = '<div class="row item-row-docs command-row-docs docs_divs">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<label>Document Name*</label>
					<input type="text" id="document_name[]" name="document_name[]" class="form-control" required>
				</div>
				<div class="col-md-5 col-sm-5 col-xs-12">
					<label>Last Used (Control Number)*</label>
					<input type="number" id="last_used[]" name="last_used[]" class="form-control" required>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<label>Date Last Used*</label>
					<input type="date" id="date_last_used[]" name="date_last_used[]" class="form-control" required>
				</div>
				<div class="col-md-5 col-sm-5 col-xs-12">
					<label>First Unused (Control Number)*</label>
					<input type="number"  id="first_unused[]" name="first_unused[]" class="form-control" required>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
				<hr>
				</div>
					<a class="btn-remove-row-docs btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>
	        </div>';



if(isset($audit)){
$docs_edit ='';
$disabled = "disabled";
	
	foreach($audit_cutoff_documents as $row){
		$docs_edit .= '<div class="row item-row-docs command-row-docs docs_divs">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<label>Document Name*</label>
							<input type="text" id="document_name[]" name="document_name[]" class="form-control" value="'.$row->document_name.'" required>
						</div>
						<div class="col-md-5 col-sm-5 col-xs-12">
							<label>Last Used (Control Number)*</label>
							<input type="number" id="last_used[]" name="last_used[]" class="form-control" value="'.$row->last_used.'" required>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<label>Date Last Used*</label>
							<input type="date" id="date_last_used[]" name="date_last_used[]" class="form-control" value="'.$row->date_last_used.'" required>
						</div>
						<div class="col-md-5 col-sm-5 col-xs-12">
							<label>First Unused (Control Number)*</label>
							<input type="number"  id="first_unused[]" name="first_unused[]" class="form-control" value="'.$row->first_unused.'" required>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12">
						<hr>
						</div>
							<a class="btn-remove-row-docs btn btn-danger btn-xs col-m-1" style="margin-top:25px"><span class="glyphicon glyphicon-remove"></span></a>
			        </div>';
	}

	
}

$docs_append= trim(preg_replace('/\s+/',' ', $docs));

?>

<div class='panel panel-primary'>
	<div class='panel-heading'>
		<div class='panel-title'>
			<text><?php echo $title;?></text>
			<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
		</div>
	</div>
</div>

<?php
	echo form_open_multipart(HTTP_PATH.$action,$attributes);
	echo $this->Mmm->createCSRF();
?>

<div class='panel-body panel'>
	<div class='panel-group' id='IAFormDivider' role='tablist' aria-multiselectable='true'>
		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='general'>	
					<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#IAGeneral' aria-expanded='true' aria-controls='IAGeneral'>
					General Information
					<span class='glyphicon glyphicon-chevron-down pull-right'></span>
					</a>
			</div>

			<div id='IAGeneral' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='IAGeneral'>
				<div class='panel-body'>
					<div class='col-md-9 col-sm-9 col-xs-9'>
						<label>Company*</label>
						<select id='company' name='company' class='form-control' <?php echo $disabled;?> required>
							<?php echo $company_options;?>
						</select>
					</div>
					<div class='col-md-3 col-sm-3 col-xs-3'>
						<label>Audit Date*</label>
						<input type="date" id="audit_date" name="audit_date" class='form-control' value="<?php echo $audit_date?>" required>
					</div>
					<div class='col-md-5 col-sm-5 col-xs-9'>
						<label>Type of Inventory*</label>
						<select id="type_of_inventory" name="type_of_inventory" class='form-control' <?php echo $disabled;?> required>
							<?php echo $category_options?>
						</select>
					</div>
					<div class='col-md-4 col-sm-4 col-xs-9'>
						<label>Auditor(s)*</label>
						<input type="text" id="audited_by" name="audited_by" class='form-control' value="<?php echo $audited_by?>" required>
					</div>
					<div class='col-md-3 col-sm-3 col-xs-3'>
						<label>Audit Location*</label>
						<select id="location" name="location" class='form-control' required <?php echo $disabled;?>>
							<?php echo $location_options?>
						</select>
					</div>
				</div>
			</div>
			
		</div>
	
		<div class='panel panel-info'>
			<div class='panel-heading' role='tab' id='itemlist'>	
				<a role='button' data-toggle='collapse' data-parent='#FormDivider' href='#IACutoff' aria-expanded='true' aria-controls='IACutoff'>
				Cut-off Documents
				<span class='glyphicon glyphicon-chevron-down pull-right'></span>
				</a>
			</div>
			<div id='IACutoff' class='panel-collapse collapse' role='tabpanel' aria-labelledby='IACutoff'>
				<div class='pull-right' style='float:left; margin-top:5px; margin-left:5px'>
					<a id='btn_add_row_docs' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>								
					<a id='btn_remove_row_docs' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
				</div>
				<div class="clearfix"><br/></div>
				<div class='panel-body item-row-container-docs' >
					<?php 
						if(isset($audit)){
							echo $docs_edit;
						}else{
							echo $docs;
						}
					?>
				</div>
			</div>
		</div>

	</div>
</div>

<div class='col-xs-12 col-sm-12 col-lg-12'>
	<span class='pull-right'>
		<input type='button' value='Save' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: validateForm()' />
		<input type='button' class='btn btn-danger btn-m' value='Discard' data-dismiss='modal'>
	</span>
</div>
<br><br><br>
</form>

<script type="text/javascript">

	$('#btn_remove_row_docs').click(function(){
		$('.item-row-docs:last').remove();
	});
	$(document).on('click', '.btn-remove-row-docs', function() {
		$(this).parent().remove();
	});
	$('#btn_add_row_docs').click(function(){
		$('.item-row-container-docs').append('<?php echo $docs_append; ?>');
	});

	$(document).on('click', "#remove_row", function(){
	     $(this).closest("tr").remove();
	}); 



	function validateForm(){

		var gen_selects = document.getElementById('IAGeneral').querySelectorAll("[required]");

    	var gen_flag=0;
        for(var i = 0; i < gen_selects.length; i++){         
            if (gen_selects[i].value==""){
            	gen_flag=1;
            } 
        }

        if(gen_flag==1){
        	toastr['error']("Please fill-out all required* fields in General Information Tab!", "ABAS says:");
			return false;
        }

    	/*var item_list = document.getElementById('IAItemlist').getElementsByTagName('input');

    	var item_flag=0;
    	for(var i = 0; i < item_list.length; i++){         
            if (item_list[i].value=="" && item_list[i].required==true){
            	item_flag=1;
            } 
        }

        if(item_flag==1){
        	toastr['error']("Please fill-out all required* fields in Inventories Tab!", "ABAS says:");
			return false;
        }

        var row_count = $('#item_list_table tr').length;

		if(row_count<=1){
        	toastr['error']("Please add at least one item.", "ABAS says:");
			return false;
        }*/


        var docs_inputs = document.getElementById('IACutoff').getElementsByTagName('input');

    	var docs_flag=0;
    	if(docs_inputs.length > 0){
	        for(var x = 0; x < docs_inputs.length; x++){
	        	if (docs_inputs[x].value==""){
	            	docs_flag=1;
	            	toastr['error']("Please fill-out all required* fields in Cut-off Documents Tab!", "ABAS says:");
					return false;
	            }
	        }	
	    }else{
	    	docs_flag=1;
	    	toastr['error']("Please add or at least one cut-off document.", "ABAS says:");
			return false;
	    }

        //if(row_count>0 && gen_flag==0 && item_flag==0 && docs_flag==0) {
        if(gen_flag==0 && docs_flag==0) {

        	$('body').addClass('is-loading'); 
			$('#modalDialog').modal('toggle'); 

			document.getElementById("inventory_audit_form").submit();
			return true;
		}

	}

</script>