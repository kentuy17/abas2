
<?php
$id = "";
$payee = "";
$payee_name = "";
$voucher_no = "";
$voucher_date = "";
$particular = "";
$amount = "";
$reference_no = "";
$voucher_no = "";
$vessel_id = "";
$vessel_name = "";
$include_on = "";
$classification = "";
$classification_name = "";
if(isset($viewExpense)){
	//var_dump($viewExpense[0]['id']);
	$id = $viewExpense[0]['id'];
	$voucher_no = $viewExpense[0]['check_voucher_no'];
	$voucher_date = $viewExpense[0]['check_voucher_date'];
	$particular = $viewExpense[0]['particulars'];;
	$amount = $viewExpense[0]['amount_in_php'];;
	$reference_no = $viewExpense[0]['reference_no'];
	$vessel_id = $viewExpense[0]['vessel_id'];
	if($vessel_id!=''){
		$v = $this->Abas->getVessel($vessel_id);
		$vessel_name = $v->name;
	}
	$payee = $viewExpense[0]['account_id'];
	if($payee!=''){
		$p = $this->Accounting_model->getSuppliers($payee);
		if(count($p) > 0){
			$payee_name = $p[0]['name'];
		}
	}
	$classification = $viewExpense[0]['expense_classification_id'];;
	if($classification!=''){
		$c = $this->Accounting_model->getExpenseClassification($classification);
		if(count($c) > 0){
			$classification_name = $c[0]['name'];
		}
	}
	$include_on = $viewExpense[0]['include_on'];
}
$link = HTTP_PATH.'Accounting/expense_report_form/';
?>

<style>#content{ margin-top:-20px; }</style>
<div class="panel-group" id="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<span style="float:left; margin-left:20px; margin-top:-10px">
			<h4><strong><span style="background:#000099; color:#FFFFFF">ACCOUN</span><span style="background:#FF0000; color:#F4F4F4">TiNG</span></strong></h4>
            </span>
            <span style="float:right; margin-right:20px; margin-top:-5px">
				<a class="like" href="<?php echo $link; ?>" data-toggle="modal" data-target="#modalDialog" title="Report">
					<button type="button" class="btn btn-danger btn-xs">View Report</button>
                </a>
				<?php if($this->Abas->checkPermissions("encoding|encoding", false)): ?>
                <a class="like" href="<?php echo HTTP_PATH ?>home/encode/suppliers" data-toggle="modal" data-target="#modalDialog" title="Report">
					<button type="button" class="btn btn-info btn-xs">Add Supplier</button>
                </a>
				<?php endif; ?>
            </span>
		</div>
		<div class="panel-body">
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-primary"></div>
					<div class="panel panel-default" style="font-size:12px">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								Expenses Entry
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body" style="background:#B0C6DF">
								<div style="width:680px; margin-right:30px; float:right; display:table;">
									<!--<table data-toggle="table" id="expenseTable" class="table table-striped table-hover" data-url="<?php echo HTTP_PATH; ?>accounting/view_all_expenses" data-cache="false" data-pagination="true"  data-page-list="[5]" data-page-size="5" data-search="true" style="width:680px; font-size:12px">-->
									<table data-toggle="table" id="hr-table" class="table table-striped table-hover" data-url="<?php echo HTTP_PATH."accounting/view_all_expenses"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-page-size="5"  data-search="true" style="font-size:12px">
										<thead>
											<tr>
												<th data-field="id" width="3%" data-align="center" data-sortable="true">ID</th>
												<th data-field="check_voucher_date" width="10%" data-align="center" data-sortable="true">Voucher Date</th>
												<th data-field="check_voucher_no" width="10%" data-align="left">Voucher No</th>
												<th data-field="particulars" width="50%" data-align="left" data-sortable="true">Particular</th>
												<th data-field="amount_in_php" width="10%" data-align="left" data-sortable="true">Amount</th>
												<th data-field="include_on" width="17%" data-align="center" data-sortable="true">Type</th>
												<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center">Manage</th>
											</tr>
										</thead>
										<!--<tbody>
											<?php
												//var_dump($expenses);
												//exit;
												//foreach($expenses as $expense){
											?>
												<tr>
													<td align="center"><?php echo $expense['id']; ?></td>
													<td align="center"><?php echo date("M j\, Y ",strtotime($expense['check_voucher_date'])); ?></td>
													<td align="center"><?php echo $expense['check_voucher_no']; ?></td>
													<td><?php echo $expense['particulars']; ?></td>
													<td align="right"><?php echo number_format($expense['amount_in_php'],2); ?></td>
													<td><?php echo $expense['include_on']; ?></td>
													<td>&nbsp;</td>
												</tr>
											<?php //} ?>
										</tbody>-->
									</table>
								</div>
								<form class="form-horizontal" role="form" id="expenseForm" name="expenseForm"  action="<?php echo HTTP_PATH.'file://///10.0.0.4/htdocs/application/views/accounting/Accounting/addExpense'; ?>" method="post" enctype='multipart/form-data'>
								<!--- waybill activity --->
								<div style="width:250px; float:left; margin-left:0px; margin-top:55px">
									<div class="container">
										<div class="panel panel-default" style="font-size:12px; width:550px; height:445px">
											<div class="panel-heading" role="tab" id="headingOne">
												<strong>&nbsp;</strong>
											</div>
											<div class="panel-body" role="tab" >
												<div style="width:200px; margin-left:20px; float:left">
													<div class="form-group">
														<label for="voucher_no">Voucher Number:</label>
														<div>
															<input class="form-control input-sm" type="text" name="voucher_no" id="voucher_no" value="<?php echo $voucher_no  ?>" />
														</div>
													</div>
													<div class="form-group">
														<label  for="payee">Payee:</label>
														<div>
															<select class="form-control input-sm" name="payee" id="payee">
																<option value="<?php echo $payee ?>"><?php echo $payee_name ?></option>
																<?php foreach($suppliers as $supplier){ ?>
																	<option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label  for="particular">Particular:</label>
														<div>
															<textarea id="particular" name="particular" rows="5" cols="37"><?php echo $particular; ?></textarea>
														</div>
													</div>
													<div class="form-group">
														<label  for="amount">Amount:</label>
														<div>
															<input class="form-control input-sm" type="text" name="amount" id="amount" value="<?php echo str_replace(",","",$amount); ?>" />
														</div>
													</div>
												</div>
												<div style="width:200px; margin-left:70px; float:left">
													<div class="form-group">
														<label for="voucher_date">Voucher Date:</label>
														<div>
															<input class="form-control input-sm" type="date" name="voucher_date" id="voucher_date" value="<?php echo $voucher_date;   ?>" />
															<script>$("#voucher_date").datepicker({dateFormat: "yy-mm-dd"/*changeYear: true,yearRange: "-100:+10"*/});</script>
														</div>
													</div>
													<div class="form-group">
														<label  for="reference_no">Reference Number:</label>
														<div>
															<input class="form-control input-sm" type="text" name="reference_no" id="reference_no" value="<?php echo $reference_no;  ?>" />
														</div>
													</div>
													<div class="form-group">
														<label for="vessel">Select Vessel (Optional):</label>
														<div>
															<select class="form-control input-sm" name="vessel" id="vessel">
																<option value="">Choose One</option>
																<?php foreach($vessels as $vessel){?>
																	<option <?php echo ($vessel->id == $vessel_id)?"SELECTED":""; ?> value="<?php echo $vessel->id ?>"><?php echo $vessel->name; ?></option>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="include_on">Include On:</label>
														<div>
															<select class="form-control input-sm" name="include_on" id="include_on">
																<option value="<?php echo $include_on; ?>"><?php echo $include_on; ?></option>
																<option  value="Cost of Vessel">Cost of Vessel</option>
																<option  value="Operation">Operation</option>
																<option  value="Repairs and Maintenance">Repairs and Maintenance</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="classification">Classification:</label>
														<div>
															<select class="form-control input-sm" name="classification" id="classification">
																<option value="<?php echo $classification; ?>"><?php echo $classification_name; ?></option>
																<?php foreach($classifications as $classification){ ?>
																	<option value="<?php echo $classification['id'] ?>"><?php echo $classification['name']; ?></option>
																<?php } ?>
															</select>
														</div>
													</div>
												</div>

												<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
												<span style="float:right; margin-right:10px; margin-top:0px">
													<input class="btn btn-success btn-sm" type="button"  value="Save" onclick="javascript:submitExpense()" id="submitbtn" style="width:100px; margin-left:30px; margin-top:10px">
													<input class="btn btn-default btn-sm"  value="Cancel" onclick="javascript:newEntry()" style="width:100px; margin-left:10px; margin-top:10px">
												</span>

											</div>
										</div>
									</div>
								</form>
								</div>
							</div>
						</div>

					</div>

				</div>




			<div class="panel-footer success text-right" style="color:#000099"><strong>AVEGA<span style="color:#FF0000">iT</span>.2015</strong></div>
		</div>
	</div>
</div>



<script>


	function operateFormatter(value, row, index) {
		id = row['id']; //alert(id);
		return [
            '<a class="like" href="<?php echo HTTP_PATH.'accounting/viewExpense/'; ?>'+row['id']+'" title="View">',
                '<i class="glyphicon glyphicon-list-alt"></i> View',
            '</a>',
        ].join('');
    }
	window.operateEvents = {
        'click .like': function (e, value, row, index) {
            p = row["sid"];
			var wid = 940;
			var leg = 680;
			var left = (screen.width/2)-(wid/2);
            var top = (screen.height/2)-(leg/2);
        },
        'click .edit': function (e, value, row, index) {
			p = row["sid"];
        }
    };
	/*
	function operateFormatter(value, row, index) {
		id = row['id']; //alert(id);
		return [
            '<a class="like" href="<?php echo HTTP_PATH.'accounting/viewExpense/'; ?>'+row['id']+'"  title="View">',
                '<i class="glyphicon glyphicon-list-alt"></i> View',
            '</a>'

        ].join('');
    }
	window.operateEvents = {
        'click .like': function (e, value, row, index) {
            p = row["sid"];
			var wid = 940;
			var leg = 680;
			var left = (screen.width/2)-(wid/2);
            var top = (screen.height/2)-(leg/2);
            // window.open('studProfile.cfm?pid='+p,'popuppage','width='+wid+',toolbar=0,resizable=1,location=no,scrollbars=no,height='+leg+',top='+top+',left='+left);
        },
        'click .edit': function (e, value, row, index) {
			p = row["sid"];
			// addForm(p);
        }
    };
	$(function () {
        var $table = $('#hr-table');
        $table.bootstrapTable();
    });
	*/



	$('input').on('click', function(){
	  var valeur = 0;
	  $('input:checked').each(function(){
		   if ( $(this).attr('value') > valeur )
		   {
			   valeur =  $(this).attr('value');
		   }
	  });
	  $('.progress-bar').css('width', valeur+'%').attr('aria-valuenow', valeur);
	});

	function newEntry(){
		window.location.assign("<?php echo HTTP_PATH.'Accounting' ?>")
		document.forms['expenseForm'].reset();
	}

	function submitExpense(){

		var id = document.getElementById('id').value;
		var v = document.getElementById('voucher_no').value;
		var d = document.getElementById('voucher_date').value;
		var p = document.getElementById('payee').value;
		var par = document.getElementById('particular').value;
		var a = document.getElementById('amount').value;
		var i = document.getElementById('include_on').value;
		var c = document.getElementById('classification').value;

		//if(id !== ''){

			/*
			alert('Editing is not allowed');
			return false;
		}else{*/

			if(v == ''){
				alert('Please enter Voucher Number');
				document.getElementById('voucher_no').focus();
				return false;
			}else if(d == ''){
				alert('Please enter Voucher Date');
				document.getElementById('voucher_date').focus();
				return false;
			}else if(p == ''){
				alert('Please select Payee');
				document.getElementById('payee').focus();
				return false;
			}else if(par == ''){
				alert('Please enter particular');
				document.getElementById('particular').focus();
				return false;
			}else if(a == ''){
				alert('Please enter amount');
				document.getElementById('amount').focus();
				return false;
			}else if(i == ''){
				alert('Please select where to include this expense.');
				document.getElementById('include_on').focus();
				return false;
			}else if(c == ''){
				alert('Please select classification.');
				document.getElementById('calssification').focus();
				return false;
			}else{
				document.forms['expenseForm'].submit();
			}

		//}


	}

	function createReport(){

		document.forms['expenseReport'].submit();

	}

</script>
