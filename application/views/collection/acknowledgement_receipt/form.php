<?php

$title = "Add Acknowledgement Receipt";
$ar_id = "";
$control_number = "";
$company = "";
$received_from = "";
$received_on = "";
$TIN = "";
$type= "";
$remarks = "";

$companyoptions	=	"";
if(!empty($companies)) {
	foreach($companies as $company) {
		if(isset($acknowledgement)){
			$companyoptions .=	"<option ".($acknowledgement['company_id']==$company->id ? "selected":"")." value='".$company->id."'>".$company->name."</option>";
		}
		else{
			$companyoptions	.=	"<option value='".$company->id."'>".$company->name."</option>";
		}
	}
}


if(isset($acknowledgement)){

	$title = "View Acknowledgement Receipt";
	$ar_id = $acknowledgement['id'];
	$control_number = $acknowledgement['control_number'];
	$company = $this->Abas->getCompany($acknowledgement['company_id'])->name;
	$received_from = $acknowledgement['received_from'];
	$TIN = $acknowledgement['TIN'];
	$received_on = $acknowledgement['received_on'];
	$type = $acknowledgement['type'];
	$remarks =$acknowledgement['remarks'];

}

$row_cash = "<td><input type='hidden' id='sorting[]' name='sorting[]' class='form-control sorting'/>
	        	<select type='text' id='denomination[]' name='denomination[]' class='form-control denomination' required/>
	        	</select>
	        </td>
	        <td>
	        	<input type='number' id='quantity[]' name='quantity[]' placeholder='Qty.' class='form-control qty' required>
	        </td>
	        <td>
	        	<input type='number' id='amount[]' name='amount[]' placeholder='Amount' class='form-control amount' readonly>
	        </td>";
$appendable_row_cash	= trim(preg_replace('/\s+/',' ', $row_cash));

$tomorrow = date("Y-m-d", strtotime("+ 1 day"));

$row_check = "<td><input type='hidden' id='sorting[]' name='sorting[]' class='form-control sorting'/>
	        	<input type='text' id='bank_name[]' name='bank_name[]' placeholder='Bank Name' class='form-control' required>
	        </td>
	        <td>
	        	<input type='text' id='bank_branch[]' name='bank_branch[]' placeholder='Bank Branch' class='form-control' required>
	        </td>
	        <td>
	        	<input type='text' id='check_number[]' name='check_number[]' placeholder='Check Number' class='form-control' required>
	        </td>
	        <td>
	        	<input type='date' id='check_date[]' name='check_date[]' placeholder='Check Date' min=".$tomorrow." class='form-control' required>
	        </td>
	        <td>
	        	<input type='number' id='amount[]' name='amount[]' placeholder='Amount' class='form-control amount_check'>
	        </td>";
$appendable_row_check	= trim(preg_replace('/\s+/',' ', $row_check));

?>
<!DOCTYPE html>
<html>
<head>
	<title>Acknowledgement Receipt</title>
	<style type="text/css">
		.table td,.table th { min-width: 240px;}
	</style>
</head>
<body>

<div class="panel panel-primary">
	<div class='panel-heading'>
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
		<h2 class="panel-title"><?php echo $title;?></h2>
	</div>
</div>

	<?php
		$attributes = array('id'=>'ar_form','role'=>'form');
		echo form_open_multipart(HTTP_PATH.CONTROLLER.'/insert/acknowledgement_receipt',$attributes);
		echo $this->Mmm->createCSRF();
	?>

		<div class="panel-body">
			
			<div class='tile-stats col-xs-12 col-md-12'>

				<input type="hidden" id="payment_id" name="payment_id" value="<?php echo $ar_id;?>">
				
				<div class='col-md-8 col-xs-12'></div>

				<div class='col-xs-12 col-md-4'>
					<label for='received_on'>Date Recieved</label>
					<?php
						if(isset($acknowledgement)){

							echo "<input type='date' name='received_on' id='received_on' placeholder='Received On' class='form-control' style='text-align:center' value=". $acknowledgement['received_on'] . " readonly/>";
						}else{

							$received_on = new DateTime();
							$received_on = $received_on->format('Y-m-d\TH:i');

							echo "<input type='datetime-local' name='received_on' id='received_on' placeholder='Received On' class='form-control' style='text-align:center' value=".$received_on." readonly/>";
						}
					?>
				</div>

				

				<div class='col-md-3 col-xs-12'>
					<label for='control_number'>Control Number</label>
					<input type="text" name="control_number" id="control_number" class="form-control" value="<?php echo $control_number;?>" style="text-align:center" readonly>
				</div>

				<div class='col-md-9 col-xs-12'>
					<label for='company'>Company*</label>
					<select id='company' name='company' class='form-control' <?php if(isset($acknowledgement)){ echo "disabled";}?>>
					<option value=''>Select</option>
						<?php echo $companyoptions; ?>
					</select>
				</div>


				<div class='col-xs-12 col-md-8'>
					<label for='received_from'>Received from*</label>
					<input type='text' name='received_from' id='received_from' placeholder='Client' class='form-control' value="<?php echo $received_from;?>" <?php if(isset($acknowledgement)){ echo "disabled";}?>></input>
				</div>

				<div class='col-xs-12 col-md-4'>
					<label for='TIN'>TIN</label>
					<input type='text' name='TIN' id='TIN' class='form-control' value="<?php echo $TIN;?>" <?php if(isset($acknowledgement)){ echo "disabled";}?>></input>
				</div>
				
				<div class='col-xs-12 col-md-8'>
					<label>Type*</label>
					<br>
					 	<input type="radio" class="rd1" value='Cash' <?php if($type=="Cash"){ echo "checked";}?> <?php if(isset($acknowledgement)){ echo "disabled";}?> onclick="javascript:acknowledgement_receipt_type('Cash');">Cash &nbsp  &nbsp
					 	<input type="radio" class="rd2" value='Check' <?php if($type=="Check"){ echo "checked";}?> <?php if(isset($acknowledgement)){ echo "disabled";}?> onclick="javascript:acknowledgement_receipt_type('Check');">Post-dated Check &nbsp &nbsp
					 
						<input type="hidden" id="ar_type" name="ar_type" value="">
				</div>
				
				<div class='col-xs-12 col-md-12'>
					<label for='remarks'>Purpose of Payment/Remarks*</label>
					<textarea name='remarks' id='remarks' placeholder='eg. Payment for...' class='form-control' <?php if(isset($acknowledgement)){ echo "readonly";}?>><?php echo $remarks;?></textarea>
				</div>

			</div>
		</div>
	
		<div class='panel-body'>
			<div class='tile-stats col-xs-12 col-md-12'>
				<div role="tabpanel" data-example-id="togglable-tabs">

					 <ul id="tab_list" class="nav nav-tabs bar_tabs" role="tablist">
					 	
			            <li role="presentation" id="tab1" <?php if(isset($acknowledgement) && $type=="Cash"){ echo "class=''";}else{ echo "class='hidden'"; }?>><a href="#tab_cash" id="cash_tab" name="cash_tab" role="tab" data-toggle="tab" aria-expanded="true"><b>Cash Breakdown</b></a>
			            </li>

			            <li role="presentation" id="tab2" <?php if(isset($acknowledgement) && $type=="Check"){ echo "class=''";}else{ echo "class='hidden'"; }?>><a href="#tab_check" role="tab" id="check_tab" name="check_tab" data-toggle="tab" aria-expanded="false"><b>Check Breakdown</b></a>
			            </li>
			           
			         </ul>

			        <div id="tab_contents" class="tab-content">
			         	
						<div role="tabpanel" <?php if(isset($acknowledgement) && $type=="Cash"){ echo "class='tab-pane fade active in'";}else{echo "class='tab-pane fade'";} ?> id="tab_cash" aria-labelledby="tab_cash">

							<?php if(!isset($acknowledgement)){?>  
								<div style='float:left; margin-top:5px; margin-left:5px'>                    
									<a id='btn_add_row_cash' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>								
									<a id='btn_remove_row_cash' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
								</div>
							<?php } ?>

							<div class="clearfix"></div>
							<div class='panel-body item-row-container-cash' style="overflow-x:auto;">
								<table id="table_cash" data-toggle="table" class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<td>#</td>
											<td>Denomination*</td>
											<td>Quantity*</td>
											<td>Amount</td>
										</tr>
									</thead>
									<tbody>

										   <?php 

										  	if(isset($acknowledgement)){

										  		$breakdown =  $this->Collection_model->getARCashBreakdown($acknowledgement['id']);

										  		$ctr = 1;
										  		$total_cash=0;
										  		foreach($breakdown as $item){
										  			echo "<tr>";
										  			echo "<td>".$ctr."</td>";
										  			echo "<td>" . $item->denomination . "</td>";
										  			echo "<td>" . $item->quantity. "</td>";
										  			echo "<td>" . number_format($item->amount,2,".",",") . "</td>";
										  			echo "</tr>";
										  			$ctr++;
										  			$total_cash = $total_cash + $item->amount;
										  		}

										  		echo "<tr>";
										  		echo "<td colspan='3' align='right'>Total Cash Amount</td>";
										  		echo "<td>Php ". number_format($total_cash,2,".",",") ."</td>";
										  		echo "</tr>";

										  	}else{
										  		 echo "<tr id='row_cash0' class='tbl_row_cash'></tr>";
										  	}

										  ?>

									</tbody>
								</table>
							</div>
							<?php if(!isset($acknowledgement)){?>
								<div class='col-sm-5 col-m-4' style='float:right; margin-top:0px; margin-left:205px'>
									<label class=''>Total Cash Amount</label>
								</div>
								<div class='col-sm-5 col-m-4 pull-right'>
									<span class='fa fa-user form-control-feedback left'  aria-hidden='true'>Php</span>
									<input type='text' id='total_cash' name='total_cash' class='form-control' style='text-align:right;font-size:25px;' readonly/>
								</div>
							<br><br>
							<?php } ?>
						</div>

						<div role="tabpanel" <?php if(isset($acknowledgement) && $type=="Check"){ echo "class='tab-pane fade active in'";}else{echo "class='tab-pane fade'";} ?> id="tab_check" aria-labelledby="tab_check">
							
							<?php if(!isset($acknowledgement)){?> 
								<div style='float:left; margin-top:5px; margin-left:5px'>                          
									<a id='btn_add_row_check' class='btn btn-success btn-xs' href='#'><span class='glyphicon glyphicon-plus'></span></a>								
									<a id='btn_remove_row_check' class='btn btn-danger btn-xs' href='#'><span class='glyphicon glyphicon-minus'></span></a>
								</div>
							<?php } ?>

							<div class="clearfix"></div>
							<div class='panel-body item-row-container-cash' style="overflow-x:auto;">
								<table id="table_check" data-toggle="table" class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<td>#</td>
											<td>Bank Name*</td>
											<td>Bank Branch*</td>
											<td>Check No.*</td>
											<td>Check Date*</td>
											<td>Amount*</td>
										</tr>
									</thead>
									<tbody>
				
										   <?php 

										  	if(isset($acknowledgement)){

										  		$breakdown =  $this->Collection_model->getARCheckBreakdown($acknowledgement['id']);

											  		$ctr = 1;
											  		$total_check=0;
											  		foreach($breakdown as $item){
											  			echo "<tr>";
											  			echo "<td>".$ctr."</td>";
											  			echo "<td>" . $item->bank_name . "</td>";
											  			echo "<td>" . $item->bank_branch. "</td>";
											  			echo "<td>" . $item->check_number . "</td>";
											  			echo "<td>" . $item->check_date. "</td>";
											  			echo "<td>" . number_format($item->amount,2,".",",") . "</td>";	
											  			echo "</tr>";
											  			$ctr++;
											  			$total_check = $total_check + $item->amount;
											  		}	

											  		echo "<tr>";
											  		echo "<td colspan='5' align='right'>Total Check Amount</td>";
											  		echo "<td>Php ". number_format($total_check,2,".",",") ."</td>";
											  		echo "</tr>";

										  	}else{
										  		 echo "<tr id='row_check0' class='tbl_row_check'></tr>";
										  	}

										  ?>

									</tbody>
								</table>
							</div>
							
							<?php if(!isset($acknowledgement)){?>
							<div class='col-sm-5 col-m-4' style='float:right; margin-top:0px; margin-left:205px'>
								<label class=''>Total Check Amount</label>
							</div>
							<div class='col-sm-5 col-m-4 pull-right'>
								<span class='fa fa-user form-control-feedback left'  aria-hidden='true'>Php</span>
								<input type='text' id='total_check' name='total_check' class='form-control' style='text-align:right;font-size:25px;' readonly/>
							</div>
							<?php } ?>
							<br><br>
						</div>
				    </div>
				</div>
			</div>
					
					<div class='col-sm-12 col-md-12'>
						<span class="pull-right">
					<?php if(!isset($acknowledgement)){?>
							<input type="button" class="btn btn-success btn-m" onclick="javascript:checkform();" value="Submit"/>
							<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal" />
					<?php }?>
					<?php if(isset($acknowledgement)){?>
							<a href="<?php echo HTTP_PATH.CONTROLLER.'/prints/acknowledgement_receipt/'.$acknowledgement['id'];?>" class="btn btn-success" target='blank'>Print</a>
							<input type="button" class="btn btn-danger btn-m" value="Close" data-dismiss="modal" />
					<?php }?>	

						</span>
					</div>
				</div>
			
		</div>
		
	</form>

</body>
</html>


<script  type="text/javascript">

$('#company').change(function(){ 
	  $.ajax({

	     type:"POST",
	     url:"<?php echo HTTP_PATH.CONTROLLER;?>/set_control_number/acknowledgement_receipts/"+$(this).val(),

	     success:function(data){
	        var control_number = $.parseJSON(data);   
	       	document.getElementById('control_number').value = control_number;	       	
	     }
	  });

});


function acknowledgement_receipt_type(ar_type){

	 if(ar_type=="Cash"){
	 	$('#tab1').removeClass();
	 	$('#tab2').addClass('hidden');

	 	$('.rd1').attr('disabled','disabled');
	 	$('.rd2').attr('disabled','disabled');

	 	$('#ar_type').val(ar_type);
	 }
	 if(ar_type=="Check"){
	 	$('#tab2').removeClass();
	 	$('#tab1').addClass('hidden');

	 	$('.rd1').attr('disabled','disabled');
	 	$('.rd2').attr('disabled','disabled');

	 	$('#ar_type').val(ar_type);

	 }

}

var i_cash=0;
$("#btn_add_row_cash").click(function(){

	var $row_cash;
	$row_cash = "<?php echo $appendable_row_cash;?>";

	$('#row_cash'+i_cash).html("<td class='text-center'>"+ (i_cash+1) +"</td>" + $row_cash);
	$('#table_cash').append('<tr class="tbl_row_cash" id="row_cash'+(i_cash+1)+'"></tr>');
	i_cash++; 

	var denomination = new Array('Select','1000.00','500.00','200.00','100.00','50.00','20.00','10.00','5.00','1.00','0.50','0.25','0.10','0.05','0.01');

	//$('.denomination').find('option').remove().end();
	for(var i = 0; i < 15; i++){
   		var option = $('<option />');
	    option.attr('value',denomination[i]).text(denomination[i]);
	    $('.denomination').append(option);
    }

	var ctr = 0;
	$(".tbl_row_cash").each(function() {
		ctr = ctr + 1;
		$('.sorting', this).val(ctr);
	});

});
$("#btn_remove_row_cash").click(function(){

	 if(i_cash>1){
	 	$("#row_cash"+(i_cash-1)).html('');
	 	i_cash--;
	 }

	 calcInputs();
	
});

/////////////////////////////////////////////////////////////////////////////////////////////

var i_check=0;
$("#btn_add_row_check").click(function(){

	var $row_check;
	$row_check = "<?php echo $appendable_row_check;?>";

	$('#row_check'+i_check).html("<td class='text-center'>"+ (i_check+1) +"</td>" + $row_check);
	$('#table_check').append('<tr class="tbl_row_check" id="row_check'+(i_check+1)+'"></tr>');
	i_check++; 

	var ctr = 0;
	$(".tbl_row_check").each(function() {
		ctr = ctr + 1;
		$('.sorting', this).val(ctr);
	});

});
$("#btn_remove_row_check").click(function(){

	 if(i_check>1){
	 	$("#row_check"+(i_check-1)).html('');
	 	i_check--;
	 }

	 calcInputs2();
	
});

///////////////////////////////////////////////////////////////////////////////////////////////

 $(document).on('keyup', "#table_cash input", calcInputs);

  function calcInputs() {	 	
    $("tr").each(function() {
    	  var $denomination  = $('.denomination', this).val();
	      var $qty  = $('.qty', this).val();
	
	      var $amount = parseFloat((($denomination*1)*($qty*1))).toFixed(2);
	      $('.amount', this).val($amount);

	      var total_amount = 0;
  		  var inps = document.getElementsByName('amount[]');
		  for (var i = 0; i < inps.length; i++) {
			var inp=inps[i];
		     total_amount = parseFloat((total_amount*1) + (inp.value*1)).toFixed(2);
		  }

		  //var res = new Intl.NumberFormat().format(total_amount);
		  res = total_amount;
		  document.getElementById("total_cash").value = res;
		
    });

  }

///////////////////////////////////////////////////////////////////////////////////////////////

  $(document).on('keyup', "#table_check input", calcInputs2);

  function calcInputs2() {	 	
    $("tr").each(function() {
    	  
	      var total_amount = 0;
  		  var inps = document.getElementsByName('amount[]');
		  for (var i = 0; i < inps.length; i++) {
			var inp=inps[i];
		     total_amount = parseFloat((total_amount*1) + (inp.value*1)).toFixed(2);
		  }

		  //var res = new Intl.NumberFormat().format(total_amount);
		  res = total_amount;
		  document.getElementById("total_check").value = res;
		
    });

  }

 ///////////////////////////////////////////////////////////////////////////////////////////////


function depositCash(){
	var msg = "";

	var deposit_reference_number=document.getElementById("deposit_reference_number").value;
	if (deposit_reference_number==""){
		msg+="Deposit Reference No. is required! <br/>";
	}
	var deposit_date=document.getElementById("deposit_date").value;
	if (deposit_date==""){
		msg+="Deposit Date is required! <br/>";
	}
	var deposited_account=document.getElementById("deposited_account").value;
	if (deposited_account==""){
		msg+="Deposited Account is required! <br/>";
	}

	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}else{

		bootbox.confirm({
			title: "Deposit Payment",
			size: 'small',
		    message: 'Are you sure you want to mark this payment as "Deposited"?',
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
			        document.getElementById("deposit_cash_form").submit();
			        return true;
		    	}
		    }
		});

	}
}


function checkform() {
	var msg="";

	var control_number=document.getElementById("control_number").value;
	if (control_number==""){
		msg+="Company is required! <br/>";
	}
	
	var received_on=document.getElementById("received_on").value;
	if (received_on==""){
		msg+="Date Received On is required! <br/>";
	}
	var received_from=document.getElementById("received_from").value;
	if (received_from==""){
		msg+="Received from is required! <br/>";
	}
	var ar_type=document.getElementById("ar_type").value;
	if (ar_type==""){
		msg+="Type is required! <br/>";
	}
	var remarks=document.getElementById("remarks").value;
	if (remarks==""){
		msg+="Purpose of Payment is required! <br/>";
	}
	
	var ar_type=$("#ar_type_of_collection").val();
	var net_amount=$("#net_amount").val();

	if(ar_type=="Cash"){
		var total_cash=$("#total_cash").val();
		if (total_cash=="" || total_cash==0){
			msg+="Total Cash Amount is required! <br/>";
		}
		if(total_cash!=net_amount){
			msg+="Total Cash Amount is not equal with Total Amount Receivable! <br/>";
		}
	}
	if(ar_type=="Check"){
		var total_check=$("#total_check").val();
		if (total_check=="" || total_check==0){
			msg+="Total Check Amount is required! <br/>";
		}
		if(total_check!=net_amount){
			msg+="Total Check Amount is not equal with Total Amount Receivable! <br/>";
		}
	}
	

	$('#table_cash input').each(function() {
        if(!$(this).val()){
           msg+="Please complete all required fields in Cash Breakdown!<br/>";
           return false;
        }
    });

    $('#table_check input').each(function() {
        if(!$(this).val()){
           msg+="Please complete all required fields in Check Breakdown!<br/>";
           return false;
        }
    });
	
	if(msg!="") {
		toastr['error'](msg, "ABAS says:");
		return false;
	}
	else {

		bootbox.confirm({
			title: "Acknowledgement Receipt",
			size: 'small',
		    message: "Are you sure you want to submit this Acknowledgement Receipt?",
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
			        document.getElementById("ar_form").submit();
			        return true;
		    	}
		    }
		});

	}

}
</script>