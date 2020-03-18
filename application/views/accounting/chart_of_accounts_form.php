<?php
$edit_coa_structure	=	$this->Abas->checkPermissions("accounting|edit_chart_of_accounts_structure",false);
$a = array(
	"code" => "",
	"name" => "", 
	"description" => "", 
	"general_ledger_code" => "", 
	"financial_statement_code" => "",
	'classification' => "",
	'type' => '',
	'sub_type' => ''
);
$type_array = array('Assets','Liabilities','Equity','Revenue','Cost of Sale','Operating Expenses','Other Expense','Other Income','Other Accounts');
$sub_type_array = array('Assets','Liabilities','Equities','Revenues','Income','Cost of Sales','General Expense','');
$selected	=	0;
$form_action=	HTTP_PATH."accounting/chart_of_accounts/insert";
if(isset($account)) {
	$form_action	=	HTTP_PATH."accounting/chart_of_accounts/update/".$account['id'];
	//$this->Mmm->debug($account);
	$a	=	$account;
}
$title="Add New Account";
$classification = $this->Abas->getItems('ac_accounts_classification');
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h4 class="panel-title">
			<?php echo $title; ?>
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h4>
	</div>
	<div class="panel-body">
		<form action="<?php echo $form_action; ?>" role='form' method='POST' id='chart_of_accounts_form' enctype='multipart/form-data'>
			<?php echo $this->Mmm->createCSRF(); ?>
			<div class='col-xs-12 col-sm-6'>
				<label for='financial_statement_code'>Financial Statement Code</label>
				<input type='text' id='financial_statement_code' name='financial_statement_code' placeholder='Financial Statement Code' class='form-control' value='<?php echo $a['financial_statement_code']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label for='general_ledger_code'>General Ledger Code</label>
				<input type='text' id='general_ledger_code' name='general_ledger_code' placeholder='General Ledger Code' class='form-control' value='<?php echo $a['general_ledger_code']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-12'>
				<label for='name'>Account Name</label>
				<input type='text' id='name' name='name'  placeholder='Account Name' class='form-control' value='<?php echo $a['name']; ?>' />
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label>Account Classification</label>
				<select class="form-control" name="classification">
					<option></option>
					<?php foreach ($classification as $row) { ?>
						<option value="<?=$row->id?>" <?=($a['classification'] == $row->id ? "selected" : "")?>>
							<?=$row->name?>
						</option>
					<?php } ?>
				</select>
			</div>
			<div class='col-xs-12 col-sm-6'>
				<label>Account Type</label>
				<select class="form-control" name="type">
					<option></option>
					<?php foreach ($type_array as $row) { ?>
						<option value="<?=$row?>" <?=($a['type'] == "$row" ? "selected" : "")?>><?=$row?></option>
					<?php } ?>
				</select>
			</div>

			<!--div class='col-xs-6 col-sm-6'>
				<select id="direct_del">
					<option>1</option>
					<option>2</option>
					<option>3</option>
				</select>
			</div>
			<div class='col-xs-6 col-sm-6'>
				<select id="issued_for">
					<option>a</option>
					<option>b</option>
					<option>c</option>
				</select>
			</div-->
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<label for='description'>Description</label>
				<textarea name="description" id="description" class="form-control"><?php echo $a['description']; ?></textarea>
			</div>
			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<input type='button' value='Submit' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkCOAform()' />
				</div>
		</form>
	</div>
</div>
<script>
	function checkCOAform() {
		var msg="";
		//var patt1=/^[0-9]+$/i;
		var patt1=/^\d+(\.\d+)*$/i;
		var name=document.forms.chart_of_accounts_form.name.value;
		if (name==null || name=="" || name=="Account Name") {
			msg+="Account name is required! <br/>";
		}
		var financial_statement_code=document.forms.chart_of_accounts_form.financial_statement_code.value;
		if (financial_statement_code==null || financial_statement_code=="" || financial_statement_code=="Financial Statement Code") {
			msg+="Financial statement code is required! <br/>";
		}
		else {

			/*$.ajax({
	             type:"POST",
	             url:"<?php echo HTTP_PATH;?>accounting/checkFinancialStatementCodeIfExist/"+financial_statement_code,
	             success:function(data){
	                    var fs = $.parseJSON(data);
		               	if(fs=='has'){
		               		msgx = ""+financial_statement_code+" already exists. Please choose another FS code.<br/>";
				        	toastr['warning'](msgx, "ABAS says:");
		               	}
	             },
			     error: function (request, status, error) {
			        alert(request.responseText);
			     }
	         });*/

			if(financial_statement_code.length!=4) {
				msg+="Financial statement code is required to be 4 characters long! <br/>";
			}
			else {
				if (!patt1.test(financial_statement_code)) {
					msg+="Only numbers are allowed in Financial statement code! <br/>";
				}
			}
		}

		var general_ledger_code=document.forms.chart_of_accounts_form.general_ledger_code.value;
		if (general_ledger_code==null || general_ledger_code=="" || general_ledger_code=="General Ledger Code") {
			msg+="General ledger code is required! <br/>";
		}
		else {
			if(general_ledger_code.length!=4) {
				msg+="General ledger code is required to be 4 characters long! <br/>";
			}
			else {
				if (!patt1.test(general_ledger_code)) {
					msg+="Only numbers are allowed in general ledger code! <br/>";
				}
			}
		}
             	
		if(msg!="") {
			console.log(msg);
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("chart_of_accounts_form").submit();
			return true;
		}
	}
</script>

<script type="text/javascript">
	$('#direct_del').change(function() {
		var company = $('#selected_company').val();
		$.ajax({
	         type:"POST",
	         url:"<?php echo HTTP_PATH;?>/inventory/get_vessels_by_company/"+company,
	         success:function(data){

	            var vessel_names = data;//$.parseJSON(data);

	            $("#issued_for").find('option').remove().end().append('<option value="">Select</option>').val('');
	            for(i in vessel_names){
	                var vessel = vessel_names[i];
	                var option = $('<option />');
	                option.attr('value', vessel.id).text(vessel.name);
	                $("#issued_for").append(option);
	            }
	        }
	    });
	});
</script>