<?php
	$form_action	= HTTP_PATH."mastertables/tax_codes/insert";
	$title="Add New Tax Code";

	$e	=	array(
		"from_sal"=>"",
		"to_sal"=>"",
		"over"=>"",
		"amount"=>"",
		"stat"=>""
	);

	if (isset($existing)){
		$form_action= HTTP_PATH."mastertables/tax_codes/update/".$existing['id'];
		$title="Edit Tax Code";
		$e = $existing;
	}
?>
<div class="panel panel-primary">
	<div class= "panel-heading" style="min-height">
		<button type= "button" class ="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title"><?php echo $title; ?></h5>
	</div>
</div>
	<div class= "panel-body">
		<form action="<?php echo $form_action; ?>" role='form' method='POST' id='mastertables_tax_codes_form' enctype='multipart/form-data'>
			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>Salary (From)</label>
				<input type='number' id='from_sal' class='form-control' name='from_sal' placeholder='Salary (From)' value='<?php echo $e['from_sal']; ?>'/>
			</div>

			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>Salary (From)</label>
				<input type='number' id='to_sal' class='form-control' name='to_sal' placeholder='Salary (From)' value='<?php echo $e['to_sal']; ?>'/>
			</div>

			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>Over</label>
				<input type='number' id='over' class='form-control' name='over' placeholder='Over' value='<?php echo $e['over']; ?>'/>
			</div>

			<div class= 'form-group col-xs-12 col-sm-6'>
				<label>Amount</label>
				<input type='number' id='amount' class='form-control' name='amount' placeholder='Amount' value='<?php echo $e['amount']; ?>'/>
			</div>
				
			<div class='form-group col-xs-12 col-sm-6'>
				<label for= 'stat'>Stat</label>
				<input type='number' id='stat' class='form-control' placeholder= '1' name='stat' value='<?php echo $e['stat'] ;?>'/>
			</div>

			<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
			<div class='col-xs-12 col-sm-12 col-lg-12'>
				<div class="form-group col-xs-12 col-sm-12 pull-right">
				<input type='button' value='Submit' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
			</div>
		</form>
</div>
<script>
		function checkInput() {
			var msg="";
			var from_sal=document.forms.mastertables_tax_codes_form.from_sal.value;
			var to_sal=document.forms.mastertables_tax_codes_form.to_sal.value;
			var over=document.forms.mastertables_tax_codes_form.over.value;
			var amount=document.forms.mastertables_tax_codes_form.amount.value;
			var stat=document.forms.mastertables_tax_codes_form.stat.value;
			if (from_sal==null || from_sal=="")	{
				msg+="Salary (From) is required! <br/>";
			}
			if (to_sal==null || to_sal=="")	{
				msg+="Salary (To) is required! <br/>";
			}
			if (over==null || over=="")	{
				msg+="Over is required! <br/>";
			}
			if (amount==null || amount=="")	{
				msg+="Amount is required! <br/>";
			}
			if (stat==null || stat=="")	{
				msg+="Stat is required! <br/>";
			}
			if(msg!="")	{
				toastr["warning"] (msg,"ABAS Says");
				return false;
			}
			else {
				document.getElementById("mastertables_tax_codes_form").submit();
				return true;
			}
		}
</script>