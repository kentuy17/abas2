<?php

$companyoptions	=	"";
if(!empty($companies)) {
	foreach($companies as $company) {
		if(isset($payment)){
			$companyoptions .=	"<option ".($payment['company_id']==$company->id ? "selected":"")." value='".$company->id."'>".$company->name."</option>";
		}
		else{
			if($company->name!="Avega Bros. Integrated Shipping Corp. (Staff)"){
				$companyoptions	.=	"<option value='".$company->id."'>".$company->name."</option>";
			}
		}
	}
}

?>
<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title">Add Daily Cash and Checks Received Report</h2>
	</div>
</div>

	<?php
		// CI Form 
		$attributes = array('id'=>'dccrr_form','role'=>'form');
		echo form_open_multipart(HTTP_PATH.CONTROLLER.'/insert/DCCRR',$attributes);
		echo $this->Mmm->createCSRF();
	?>

		<div class="panel-body">
			<div class='col-md-12 col-xs-6'>
				<label>Company*</label>
				<select name="company_id" id="company_id" class="form-control">
					<option value="">Select</option>
						<?php echo $companyoptions;?>
				</select>
				
			</div>
				
			<div class='col-md-12 col-xs-6'>
				<label>Date*</label>
				<input type="date" id="created_on" name="created_on" class="form-control" style="text-align:center" value="">
			</div>
			<div class='col-xs-12 col-xs-12 col-lg-12 clearfix'><br/></div>
			<div class='col-md-12 col-xs-6'>
				<span class="pull-right">
					<input type="button" value="Save" name="btnSubmit" class="btn btn-success btn-m" onclick="javascript:checkForm();">
					<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
				</span>
			</div>
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
	var created_on=document.getElementById("created_on").value;
	if (created_on==""){
		msg+="Date is required! <br/>";
	}
	
	
	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Add DCCRR",
			size: 'small',
		    message: "Are you sure you want to add DCCRR on this date?",
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

			        document.getElementById("dccrr_form").submit();
			        return true;
		    	}
		    }
		});

	}

}
</script>