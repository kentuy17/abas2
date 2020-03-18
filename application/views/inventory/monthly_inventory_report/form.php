<?php
$companyoptions	=	"";
if(!empty($companies)) {
	foreach($companies as $company) {
			$companyoptions .=	"<option value='".$company->id."'>".$company->name."</option>";
	}
}
?>
<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">Add Monthly Inventory Report</h2>
	</div>
</div>
	<form action="<?php echo HTTP_PATH .'inventory/monthly_inventory_report/insert'?>" method="POST" id="monthly_inventory_form">
		<div class="panel-body">
			<div class='col-md-12 col-s-12 col-xs-12'>
				<label>Company*</label>
				<select name="company_id" id="company_id" class="form-control">
					<option value="">Select</option>
						<?php echo $companyoptions;?>
				</select>
			</div>
		</div>
		<div class='modal-footer'>
			<input type="button" value="Save" name="btnSubmit" class="btn btn-success btn-m" onclick="javascript:checkForm();">
			<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
		</div>
	</form>

</body>
</html>

<script  type="text/javascript">
function checkForm() {
	var msg="";

	var company=document.getElementById("company_id").value;
	if (company==""){
		msg+="Company is required! <br/>";
	}
	
	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Add Monthly Inventory Report",
			size: 'small',
		    message: "Are you sure you want to add this Month's Inventory Report?",
		    buttons: {
		        confirm: {
		            label: 'Yes',
		            className: 'btn-success'
		        },
		        cancel: {
		            label: 'No',
		            className: 'btn-danger'
		        }
		    },
		    callback: function (result) {
		    	if(result){

		    		$('body').addClass('is-loading'); 
					$('#modalDialogNorm').modal('toggle'); 

			        document.getElementById("monthly_inventory_form").submit();
			        return true;
		    	}
		    }
		});

	}

}
</script>