<?php
	$company="";
	$client = "";
	$contract="";

	$companies=$this->db->query("SELECT * FROM companies");
	$companies=$companies->result_array();
	$companyoptions="";
	if(!empty($companies)){
		foreach($companies as $c){
			$companyoptions.="<option ".($company==$c['id']?"SELECTED":"")." value='".$c['id']."'>".$c['name']."</option>";
		}
	}

	$clients=$this->db->query("SELECT * FROM clients");
	$clients=$clients->result_array();
	$clientsoptions="";
	if(!empty($clients)){
		foreach($clients as $c){
			$clientsoptions.="<option ".($client==$c['id']?"SELECTED":"")." value='".$c['id']."'>".$c['company']."</option>";
		}
	}

		// if (!empty($company)){
			// if(!empty($client)){
				$contracts=$this->db->query("SELECT * FROM service_contracts");

				$contracts=$contracts->result_array();

				$contractsoptions="";
				if(!empty($contracts)){
					foreach($contracts as $c){
						$contractsoptions.="<option ".($contract ==$c['id']?"SELECTED":"")." value='".$c['id']."'>".$c['reference_no']."</option>";
					}
				}
			// }
		// }

	$reference=$this->db->query("SELECT * FROM service_contracts WHERE id='".$contract."'");
	$reference=$reference->result_array();
	if (!empty($reference)){
		$reference_no=$reference['reference_no'];
	}
	else{
		$reference_no="";
	}
$itemform = "
<div class='row item-row'><div class='form-group col-xs-8'><input type='text' class='col-xs-12' name='particular[]' id='particular[]' placeholder='Particular'/></div><div class='form-group col-xs-4'><input type='text' class='col-xs-12' name='amount[]' id='amount[]' placeholder='Amount' onkeyup= 'total()'/></div></div>
"
;

?>
<div class="panel panel-primary">
	<div class="panel panel-heading" style="min-height">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class "modal title">Add Billing</h5>
	</div>
	<div class="panel-body">
		<form action="<?php echo HTTP_PATH.'finance/billing/add'; ?>" method="POST">
			<div class="col-md-4">
				<div class="form-group col-xs-12">
					<select class="col-xs-12" name="company" id="company">
						<option>Select Company</option>
						<?php echo $companyoptions; ?>
					</select>
				</div>
				<div class="form-group col-md-12">
					<select class="col-xs-12" name="contract" id="contract">
						<option>Select Contract</option>
						<?php echo $contractsoptions; ?>
					</select>
				</div>
			</div>
			<div class="col-md-8">
				<div class="form-group panel panel-default col-md-12">

					<legend><h5>Enter Billing Details
						<div class="col-xs-1 pull-right">
						<a id="btn_add_row" class="btn btn-success btn-xs" href="#"><span class="glyphicon glyphicon-plus"></span></a>
						</div>
						<div class="col-xs-1 pull-right">
						<a id="btn_remove_row" class="btn btn-danger btn-xs" href="#"><span class="glyphicon glyphicon-minus"></span></a>
						</div>
					</h5></legend>
					<div class="clearfix"><br/></div>
					<div class="panel-body item-row-container">
					<?php echo $itemform; ?>
					</div>

					<div class="form-group col-xs-4 pull-right">
						<input type="text" name="total" id="total" placeholder="Total"/>
					</div>
				</div>
				<div class="pull-right">
					<input type="button" class="btn btn-sm btn-info" name="cancel" id="cancel" value="Cancel" data-dismiss="modal"/>
					<input type="submit" class="btn btn-sm btn-info" name="add" id="add" value="Create SOA"/>
					<!--<a href="<?php echo HTTP_PATH.'finance/billing/soa'; ?>" role="button" class="btn btn-sm btn-info" name="soa" id="soa">Create SOA</a> -->
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	$("#btn_remove_row").click(function(){
		$('.item-row:last').remove();
		});
	$("#btn_add_row").click(function(){
		$('.item-row-container').append("<div class='row item-row'><div class='form-group col-xs-8'><input type='text' class='col-xs-12' name='particular[]' id='particular[]' placeholder='Particular'/></div><div class='form-group col-xs-4'><input type='text' class='col-xs-12' name='amount[]' id='amount[]' placeholder='Amount' onkeyup= 'total()'/></div></div>");
		});


		// var result = 0;
		// var amount1 = parseInt(document.getElementById('amount1').value);
		// var amount2 = parseInt(document.getElementById('amount2').value);
		// var amount3 = parseInt(document.getElementById('amount3').value);
		// var amount4 = parseInt(document.getElementById('amount4').value);

		// if(amount1 == "")
			// amount = 0;
		// if(amount2 == "")
			// amount2 = 0;
		// if (amount3 == "")
			// amount3 = 0;
		// if (amount4 == "")
			// amount4 = 0;

		// var result = (amount1 + amount2 + amount3 + amount4);
		// if (!isNaN(result)){
			// document.getElementById('total').value = result;
			// }
		// }

	//function selected(){
		// var company= document.getElementById("company").value;
		// var client= document.getElementById("client").value;

		// var dataString {
			// "client": client,
			// "company": company,
			// };
		// $.ajax({
			// type: "POST",
			// url: "<?php echo HTTP_PATH; ?>finance/billing/ajax",
			// data: dataString,
			// });
	// }
	</script>