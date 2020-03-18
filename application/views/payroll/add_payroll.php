<?php
$employeeoptions=	"";
if(isset($employees)) {
	if(is_array($employees) && !empty($employees)) {
		foreach($employees as $e) {
			$employeeoptions	.=	"<option value='".$e['id']."'>".ucwords($e['full_name'])."</option>";
		}
	}
}
$payrolloptions=	"";
if(isset($payroll_periods)) {
	$ctr=0;
	$len = count($payroll_periods);
	$selected = "";
	if(is_array($payroll_periods) && !empty($payroll_periods)) {
		foreach($payroll_periods as $pp) {
			$value	=	($pp['payroll_coverage'] == "1st-half" ? 0 : 1)."-".date("m-Y",strtotime($pp['payroll_date']))."-".$pp['company_id'];
			$company_name	=	"";
			if(!empty($pp['company_id'])) {
				$company	=	$this->Abas->getCompany($pp['company_id']);
				if($company!=false) {
					$company_name	=	isset($company->name) ? $company->name : $a['company_id'];
				}
			}
			if($ctr == $len - 1){
				$selected = "selected";
			}
			$payrolloptions	.=	"<option value='".$value."' ".$selected.">".$pp['payroll_coverage']." ".date("F Y",strtotime($pp['payroll_date']))." - ".$company_name."</option>";
			$ctr++;
		}
	}
}

?>

	<div class='panel panel-primary'>
		<div class='panel-heading'>
			<div class='panel-title'>
				<text>Add Employee to Payroll</text>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
			</div>
		</div>
	</div>

	<div class="panel-body">
		<form action="<?php echo HTTP_PATH ?>payroll_history/add/" role="form" method="POST" id="add_employee_to_payroll" onsubmit="javascript: checkform()" enctype="multipart/form-data">
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class="col-lg-12 col-sm-12">
				<label for="employee_id0">Employee Name:*</label>
				<p>
					<input type="text" id="employee_label" class="form-control ui-autocomplete-input" name="employee_label"/>
					<input type="text" id="employee_id" class="hide" name="employee_id"/>
				</p>
			</div>
			<div class="col-lg-12 col-sm-12">
				<label for="payroll_id1">Payroll:*</label>
				<p>
					<select id="payroll_id1" class="form-control" name="payroll_id">
						<option value="">Select</option>
						<?php echo $payrolloptions; ?>
					</select>
				</p>
			</div>
			<div class="col-sm-lg-12">
				<span class='pull-right'>
					<br>
					<input type='button' value='Submit' name='btnSubmit' class='btn btn-success btn-m' onclick='javascript: checkform()' />
					<input type='button' class='btn btn-danger btn-m' value='Discard' data-dismiss='modal'>
				</span>
			</div>
		</form>

	</div>


<script>
	
	function checkform() {
		var msg="";
		var patt1=/^[0-9]+$/i;
		var employee_id0=document.forms.add_employee_to_payroll.employee_id.value;
		if (employee_id0==null || employee_id0=="" || employee_id0=="Employee") {
			msg+="Employee Name is required! <br/>";
		}
		var payroll_id1=document.forms.add_employee_to_payroll.payroll_id.selectedIndex;
		if (payroll_id1==null || payroll_id1=="" || payroll_id1=="Payroll") {
			msg+="Payroll is required! <br/>";
		}

		if(msg!="") {
			$( "body" ).append('<div id="validation">&nbsp;</div>');
			toastr['error'](msg,"ABAS Says");
			return false;
		} else {
			document.getElementById("add_employee_to_payroll").submit();
			return true;
		}
	}
	$( "#employee_label" ).autocomplete({
		source: "<?php echo HTTP_PATH; ?>hr/employee_autocomplete_list",
		minLength: 2,
		search: function(event, ui) {
			toastr['info']('Loading, please wait...');
		},
		response: function(event, ui) {
			toastr.clear();
		},
		select: function( event, ui ) {
			$( "#employee_label" ).val( ui.item.label );
			$( "#employee_id" ).val( ui.item.value );
			return false;
		}
	});
</script>