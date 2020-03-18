<?php
	$client="";
	$clients=$this->db->query("SELECT * FROM clients");
	$clients=$clients->result_array();
	$clients_options = "";
	if(!empty($clients)){
		foreach($clients as $c){
			$clients_options .="<option ".($client==$c['id']?"SELECTED":"")." value='".$c['id']."'>".$c['company']."</option>";
		}
	}
?>
<form action= "<?php echo HTTP_PATH; ?>'finance/billing/add'" method="POST" id='billing_form'>
	<div class="form-group col-sm-6">
		<label for="client">Client</label>
		<select class="form-control" name="client" id="client">
			<option></option>
			<?php echo $clients_options; ?>
		</select>
	</div>
	<div class="form-group col-sm-6">
		<label for="date">Date</label>
		<input type="text" class="form-control" name="date" id="date"/>
	</div>
	<div class="form-group col-sm-6">
		<label for="reference_no">Reference No</label>
		<input type="text" class="form-control" name="reference_no" id="reference_no"/>
	</div>
	<div class="form-group col-sm-6">
		<label for="account">Account as of</label>
		<input type="text" class="form-control" name="account" id="account"/>
	</div>
	<div class="form-group col-sm-6">
		<label for="particulars">Particulars</label>
		<textarea class="form-control" name="particulars" id="particulars"></textarea>
	</div>
	<div class="form-group col-sm-3">
		<label for="amount">Amount</label>
		<input type="text" class="form-control" name="amount" id="amount"/>
	</div>
	<div class="form-group col-sm-3">
		<input type="button" class="btn btn-primary btn-block" value="add" name="add" id="add" onclick='javascript: checkparticulars()'/>
	</div>
</form>
<div class="table-responsive" class="table table-stripped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Particulars</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody class="table-content">
	</tbody>
</div>
<table border= '1'>
	<tr>
		<td data-align="center">Particulars</td>
		<td data-align="center">Amount</td>
	</tr>
</table>
<script>
	$("#date").datepicker({ changeMonth: true, changeYear: true, yearRange:"-100:+0", dateFormat: "yy-mm-dd"});

	function checkparticulars(){
		var particulars = document.forms.billing_form.particulars.value;
		var amount = document.forms.billing_form.amount.value;
		var dataString={
			"particulars":particulars,
			"amount":amount,
		}
		$.ajax({
			type: "POST",
			url: "<?php echo HTTP_PATH; ?>finance/billing/table/ajax",
			data: dataString,
			cache: false,
			success: function(html){
				$(".table-content").append(html);
				if(html.test("/script/")){
					toastr['error']("","Particulars Not added!");
				}
				else{
					toastr['success']("","Particulars Added!");
					document.getElementById('particulars').value='';
					document.getElementById('amount').value'';
				}
			},
			error: function(html){
				toastr['error']("","Particulars Not Added!");
			}
		});
	}
</script>
