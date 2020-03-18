<?php
	/*
		This for should handle creation of vouchers coming from
		1. Purchase
		2. Request For Payment
	*/
	$grand_total		=	0;
	$withholding_tax	=	0;
	$value_added_tax	=	0;
	$bankoptions		=	"";
	$voucher_number		=	"";
	$detailtable		= 	"";
	$payto				=	"";
	
	
	
	/////////////////////////////////////////////////////////////
	//this should be placed in controller or model
	//to be used globally
	$company = $this->Abas->getCompany($company_id);
	
	//for testing
	
	if(is_numeric($company_id)){
	//$voucher_number_serial_sql	=	"SELECT MAX(voucher_number) as old_serial FROM ac_vouchers WHERE 1=1 AND company_id=".$company->id;
		$voucher_number_serial_sql	=	"SELECT count(voucher_number) as voucher_count FROM ac_vouchers WHERE 1=1 AND company_id=".$company_id;
	
	}else{
		$this->Abas->sysMsg("sucmsg", "Problem occured while generating voucher serial number!");
		die();
	}
	
	if($supplier['issues_reciepts']) {
		$voucher_number_serial_sql	.=	" AND bir_visible=1";
		$voucher_prefix		=	"";
	}
	else {
		$voucher_prefix				=	"W";
		$voucher_number_serial_sql	.=	" AND bir_visible=0";
	}
	
	$voucher_number					=	$this->db->query($voucher_number_serial_sql);	
	
	if($voucher_number) {		
		
		if($voucher_number=(array)$voucher_number->row()) {
			
			$x = 	(int)$voucher_number['voucher_count'] + 1;
			
			$voucher_number			=	$voucher_prefix.str_repeat('0', 8).$x;	
			
			//recheck if existing
			$sq = "SELECT * FROM ac_vouchers WHERE company_id=".$company_id." AND voucher_number = '".$voucher_number."'";
			
			$chk = $this->db->query($sq);
			
			if($chk->num_rows() > 0){
				//add count to voucher number
				$x = 	$x + 1;
				$voucher_number			=	$voucher_prefix.str_repeat('0', 8).$x;	
			}
			
		}
	}
	////////////////////////////////////////////////////////////////
	
	if($ap_voucher[0]['rfp_no'] != 0){
		$type 				= 'non-po';
		$payto				=	$ap_voucher[0]['payee'];
		$labelTitle			= 	'RFP Number:';
		$ref_no				= 	$ap_voucher[0]['rfp_no'];
		//$grand_total		=	$request_payment[0]['amount'];
		/*
		$detailtable		=	"<tr>";
			$detailtable	.=	"<td colspan='4'>".$request_payment[0]['particular']."</td>";
			$detailtable	.=	"<td align='right' colspan='1'>P".number_format($request_payment[0]['amount'],2)."&nbsp;</td>";
		$detailtable	.=	"</tr>";
		*/
	}
	else{
		/*FOR PURCHASE*/
		$type 				= 'po';
		$labelTitle			=	'PO Number:';
		$ref_no				=	$ap_voucher[0]['po_no'];
		$payto				=	$ap_voucher[0]['payee'];
		$detailtable		=	"<tr><td colspan='99'>No information found!</td></tr>";
		if(!empty($delivery_detail)) {
			/*
			if($supplier['issues_reciepts']) {
				$voucher_number_serial_sql	.=	" AND bir_visible=1";
				$voucher_prefix		=	"";
			}
			else {
				$voucher_prefix				=	"W";
				$voucher_number_serial_sql	.=	" AND bir_visible=0";
			}
			$voucher_number					=	$this->db->query($voucher_number_serial_sql);
			if($voucher_number) {
				if($voucher_number=(array)$voucher_number->row()) {
					$voucher_number			=	$voucher_prefix.str_pad($voucher_number['old_serial'] + 1, 8,'0', STR_PAD_LEFT);
				}
			}*/
			$detailtable	=	"";
			foreach($delivery_detail as $d){
				$item			=	$this->Inventory_model->getItem($d['item_id']);
				$item			=	$item[0];
				$line_total		=	$d['quantity'] * $d['unit_price'];
				$grand_total	=	$grand_total + $line_total;
				$detailtable	.=	"<tr>";
					$detailtable	.=	"<td>".$item['item_code']."</td>";
					$detailtable	.=	"<td>".$item['description']."</td>";
					$detailtable	.=	"<td>".$d['quantity']." ".$d['unit']."</td>";
					$detailtable	.=	"<td>P".$d['unit_price']."</td>";
					$detailtable	.=	"<td>P".number_format($line_total,2)."</td>";
				$detailtable	.=	"</tr>";
			}
			if($supplier['issues_reciepts']) {
				$withholding_tax=	0;
				if($supplier['vat_registered']) {
					$value_added_tax	=	$grand_total * .12;
					if($supplier['vat_computation']=="Inclusive") {
						$grand_total	=	$grand_total - $value_added_tax;
					}
				}
			}
			$total_after_tax	=	$grand_total+$value_added_tax+$withholding_tax;
		}
	}
	/*END PURCHASE*/
	if(!empty($banks)) {
		foreach($banks as $b){
			$bankoptions	.=	"<option value='".$b['id']."'>".$b['name']." (".$b['code'].") </option>";
		}
	}
	// get latest voucher number
	$bir_hidden		=	$this->db->query("SELECT MAX(voucher_number) AS last_voucher_number FROM ac_vouchers WHERE bir_visible=0");
	$bir_visible	=	$this->db->query("SELECT MAX(voucher_number) AS last_voucher_number FROM ac_vouchers WHERE bir_visible=1");
	if($bir_hidden)		$bir_hidden		=	(array)$bir_hidden->row();
	if($bir_visible)	$bir_visible	=	(array)$bir_visible->row();
	$existing_voucher_numbers["bir_visible"]	=	!empty($bir_visible['last_voucher_number'])? $bir_visible['last_voucher_number']:"None";
	$existing_voucher_numbers["bir_hidden"]		=	!empty($bir_hidden['last_voucher_number'])? $bir_hidden['last_voucher_number']:"None";
?>

<form class="form-horizontal" role="form" name="create_voucher" id="create_voucher" action="<?php echo HTTP_PATH.'accounting/add_voucher/'.$ap_voucher[0]['id']; ?>" method="post">
	<input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
	<div class="panel panel-danger">
		<div class="panel-heading">
			<span class="close" aria-hidden="true" data-dismiss="modal">&times;</span>
			<h4 class="modal-title" id="myModalLabel"><strong>Check Voucher</strong></h4>
		</div>
		<div class="modal-body">
			<div class="col-md-4 well">
				<?php echo $this->Mmm->createCSRF() ?>
				<div class="col-xs-12 col-sm-6">
					<label data-toggle="tooltip" data-placement="right" title="Visible: <?php echo $existing_voucher_numbers['bir_visible']; ?> ---------- Hidden: <?php echo $existing_voucher_numbers['bir_hidden']; ?>" for="voucher_no">Voucher Number: </label>
					<input class="form-control input-sm" type="text" placeholder="Voucher Number" name="voucher_no" id="voucher_no" value="<?php echo $voucher_number; ?>">
				</div>
				<div class="col-xs-12 col-sm-6">
					<label>Voucher Type:</label>
					<select class="form-control input-sm" name="voucher_type" id="voucher_type">
						<option value="">Choose One</option>
						<option value="Check Voucher">Check</option>
						<option value="Cash Voucher">Cash</option>
						<option value="Disbursement Voucher">Disbursement</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-12">
					<label for="bank">Bank: </label>
					<select class="form-control input-sm" name="bank" id="bank">
						<option value="">Choose One</option>
						<?php echo $bankoptions; ?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-12">
					<label for="check_no">Check Number: </label>
					<input class="form-control input-sm" type="text" placeholder="Check Number" name="check_no" id="check_no">
				</div>
				<div class="col-xs-12 col-sm-6" style="display:none">
					<label for="wtax">Witholding Tax: </label>
					<input class="form-control input-sm numeric-only" type="text" placeholder="Withholding Tax" name="wtax" id="wtax" value="<?php echo $withholding_tax; ?>">
				</div>
				<div class="col-xs-12 col-sm-6" style="display:none">
					<label for="vat">Value Added Tax: </label>
					<input class="form-control input-sm numeric-only" type="text" placeholder="Value Added Tax" name="vat" id="vat" value="<?php echo $value_added_tax; ?>">
				</div>
				<div class="col-xs-12">
					<label for="remark">Particular: </label>
					<textarea name="remark" class="form-control input-sm" id="remark"></textarea>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearfix"><br/></div>
				<div class="col-xs-6">
					<input type="button" class="btn btn-default btn-block" data-dismiss="modal" value="Cancel" />
				</div>
				<div class="col-xs-6">
                	
					<input type='button' value='Create' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkInput()' />
				</div>
			</div>
			<div class="col-md-8">
				<p>Company: <?php echo $company->name; ?></p>
				<p>Date: <?php echo date('F j, Y')  ?></p>
				<p>Pay To: <?php echo $supplier['name'] ?></p>
				<p><?php echo $labelTitle.'  '. $ref_no ?></p>
				<?php
				if($type == 'po'){ ?>
                	<p>Invoice Number: <?php echo $ap_voucher[0]['invoice_no'] ?></p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th colspan='4'>Explanation of Payment</th>
                                    <th>Amount</th>
                                </tr>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $detailtable; ?>
                                <tr>
                                    <td colspan='4'>Total:</td>
                                    <td>P<?php echo number_format($grand_total,2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan='6'>Amount in Words: <?php echo $this->Mmm->chequeTextFormat($grand_total);?></td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php }elseif ($type=="non-po"){ ?>
                	<div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th colspan='5'>Explanation of Payment</th>

                                </tr>
                                <tr>

                                    <th colspan="4">Description</th>
                                    <th colspan="1">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $detailtable; ?>
                                <tr>
                                    <td colspan='4'>Total:</td>
                                    <td align="right" colspan="1">P<?php echo number_format($grand_total,2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan='6'>Amount in Words: <?php echo $this->Mmm->chequeTextFormat($grand_total);?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</form>
<script>
	$('[data-toggle="tooltip"]').tooltip();
	$(".numeric-only").keydown(function (e) {
		console.log(e);
		if (
			$.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || // Allow: backspace, delete, tab, escape, enter and .
			(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || // Allow: Ctrl+A, Command+A
			(e.keyCode >= 35 && e.keyCode <= 40) // Allow: home, end, left, right, down, up
		) {
			return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function checkInput() {
		var msg="";
		//var patt1=/^[0-9]+$/i;
		var patt1=/^\d+(\.\d+)*$/i;
		var voucher_number=document.forms.create_voucher.voucher_no.value;
		if (voucher_number==null || voucher_number=="" || voucher_number=="Voucher Number") {
			msg+="Voucher number is required! <br/>";
		}
		var voucher_type=document.forms.create_voucher.voucher_type.value;
		if (voucher_type==null || voucher_type=="" || voucher_type=="Voucher Type") {
			msg+="Voucher type is required! <br/>";
		}
		var bank=document.forms.create_voucher.bank.value;
		if (bank==null || bank=="" || bank=="Bank") {
			msg+="Bank is required! <br/>";
		}
		var check_number=document.forms.create_voucher.check_no.value;
		if (check_number==null || check_number=="" || check_number=="Check Number") {
			msg+="Check number is required! <br/>";
		}
		var wtax=document.forms.create_voucher.wtax.value;
		if (wtax==null || wtax=="" || wtax=="Withholding Tax") {
			msg+="Withholding tax is required! <br/>";
		}
		var vat=document.forms.create_voucher.vat.value;
		if (vat==null || vat=="" || vat=="Value Added Tax") {
			msg+="Value added tax is required! <br/>";
		}
		if(msg!="") {
			toastr["warning"](msg,"ABAS Says");
			return false;
		}
		else {
			document.getElementById("create_voucher").submit();
			return true;
		}
	}
</script>
