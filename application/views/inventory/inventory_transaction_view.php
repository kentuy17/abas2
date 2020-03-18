<?php


$title='';
if(isset($_SESSION['abas_login']['user_location'])){

	//set inventory location
	$user_location = $_SESSION['abas_login']['user_location'];

	switch ($user_location) {
		case 'Makati':
			$title = "You are using Makati Inventory System";

			break;
		case 'NRA':
			$title = "You are using NRA Inventory System";

			break;
		case 'Tayud':
			$title = "You are using Tayud Inventory System";

			break;
		case 'Tacloban':
			$title = "You are using Tacloban Inventory System";

			break;
	}

}else{
	header("location:".HTTP_PATH."home");die();//user cannot use inventory without user location
}

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
		<div class="panel-heading" >
			<span style="float:left; margin-left:0px; margin-top:-10px">
			<h4><strong><span style="background:#000099; color:#FFFFFF">INVENT</span><span style="background:#FF0000; color:#F4F4F4">ORY</span></strong></h4>
            </span>
            <span style="float:right; margin-right:20px; margin-top:-5px">
				<a class="like" href="<?php echo HTTP_PATH ?>inventory/item_form" data-toggle="modal" data-target="#modalDialog" title="New Item">
					<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add Item</button>
                </a>
                <a class="like" href="<?php echo HTTP_PATH ?>inventory/receiving_form" data-toggle="modal" data-target="#modalDialog" title="Receiving">
					<button type="button" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-import"></i> Receiving</button>
                </a>
                <a class="like" href="<?php echo HTTP_PATH ?>inventory/issuance_form" data-toggle="modal" data-target="#modalDialog" title="Issuance">
					<button type="button" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-export"></i> Issuance</button>
                </a>
				
                <a class="like" href="<?php echo HTTP_PATH ?>inventory/transfer_form" data-toggle="modal" data-target="#modalDialog" title="Transfer">
					<button type="button" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-transfer"></i> Transfer</button>
                </a>

                <a class="like" href="<?php echo HTTP_PATH ?>inventory/inventory_request"  title="For Request">
					<button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-list"></i> For Request</button>
                </a>


                <a class="like" href="<?php echo HTTP_PATH ?>inventory/inventory_transaction"  title="Transactions">
					<button type="button" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-search"></i> View Transactions</button>
                </a>
                 <a class="like" href="<?php echo $link; ?>" data-toggle="modal" data-target="#modalDialog" title="Report">
					<button type="button" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-list-alt"></i> Create Report</button>
                </a>


            </span>
		</div>
		<div class="panel-body">
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                    <div class="panel panel-primary"></div>
					<div class="panel panel-default" style="font-size:12px;background:#CCCCCC">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								<?php echo $title; ?>
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body" style="background:#FFF">

								<div style="width:200px; float:left; margin-left:0px; margin-top:-15px">
                        <div class="container">

                            <table data-toggle="table" id="invent-table" class="table table-striped table-hover table-responsive" data-cache="false" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-page-size="5"  data-search="true" style="font-size:12px">
										<thead>
											<tr align="center">
												<th width="2%" class="col-sm-0" data-field="id" data-align="center" data-sortable="true" >ID</th>
                                                <th width="3%" class="col-sm-1" data-align="center" data-sortable="true">Item Code</th>
												<th  width="48%" class="col-sm-3"  data-sortable="true">Description</th>
												<th width="20%" class="col-sm-2" >Particular</th>
												<th width="15%" class="col-sm-2"  data-sortable="true">Category</th>
                                                <th width="5%" class="col-sm-0" data-align="center"    data-sortable="true">Qty in Tayud</th>
                                                <th width="5%" class="col-sm-0" data-align="center"   data-sortable="true">Qty in NRA</th>
                                                <th width="5%" class="col-sm-0" data-align="center"    data-sortable="true">Qty in MKT</th>
												<th width="5%" class="col-sm-0" data-align="center"  data-sortable="true">Total Qty</th>
                                                <th width="5%" class="col-sm-1" data-align="center"  data-sortable="true">Unit</th>
												<th width="4%" data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center">Manage</th>
											</tr>
										</thead>
											<tbody>
											<?php
												//var_dump($expenses);
												//exit;
												foreach($items as $item){

												//get category
												$sq = "SELECT * FROM `inventory_category` WHERE id =".$item['category'];
												$r = $this->db->query($sq);
												$cat = $r->result_array();

												//get category
												$sq1 = "SELECT * FROM `inventory_location` WHERE item_id =".$item['id'];

												$r1 = $this->db->query($sq1);
												$qt1 = $r1->result_array();


												if(count($qt1) > 0){
													$tayud_qty = $qt1[0]['tayud_qty'];
													$nra_qty = $qt1[0]['nra_qty'];
													$mkt_qty = $qt1[0]['mkt_qty'];
													$total_qty = $tayud_qty + $nra_qty + $mkt_qty;
												}else{
													$tayud_qty = 0;
													$nra_qty = 0;
													$mkt_qty = 0;
													$total_qty = 0;
												}

												//echo $tayud_q1ty.'<br>';
												//var_dump($qt1);

											?>
												<tr>
													<td width="2%" align="center"><?php echo $item['id']; ?></td>
                                                    <td align="center"><?php echo $item['item_code']; ?></td>
													<td width="48%" align="left"><?php echo $item['description']; ?></td>
													<td align="left"><?php echo $item['particular']; ?></td>
													<td align="left"><?php echo $cat[0]['category']; ?></td>
													<td align="center"><?php echo $tayud_qty; ?></td>
                                                    <td align="center"><?php echo $nra_qty; ?></td>
                                                    <td align="center"><?php echo $mkt_qty; ?></td>
                                                    <td align="center"><?php echo $total_qty; ?></td>
                                                    <td align="center"><?php echo $item['unit']; ?></td>
													<td>&nbsp;</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>


                        </div>
                    </div>
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
            '<a class="like" href="<?php echo HTTP_PATH.'inventory/item_form/'; ?>'+row['id']+'" title="View" data-toggle="modal" data-target="#modalDialog">',
                '<i class="glyphicon glyphicon-pencil"></i>',
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
		window.location.assign("<?php echo HTTP_PATH.'Inventory' ?>")
		document.forms['itemForm'].reset();
	}

	function submitMe(){

		var id = document.getElementById('id').value;
		var i = document.getElementById('item_code').value;
		var d = document.getElementById('description').value;
		var p = document.getElementById('particular').value;
		var u = document.getElementById('unit').value;
		var uc = document.getElementById('unit_cost').value;
		var q = document.getElementById('qty').value;


		//if(id !== ''){

			/*
			alert('Editing is not allowed');
			return false;
		}else{*/

			if(i == ''){
				alert('Please enter Item Code');
				document.getElementById('item_code').focus();
				return false;
			}else if(d == ''){
				alert('Please enter Description');
				document.getElementById('description').focus();
				return false;
			}else if(u == ''){
				alert('Please select unit');
				document.getElementById('unit').focus();
				return false;
			}else if(q == ''){
				alert('Please enter quantity on hand');
				document.getElementById('qty').focus();
				return false;
			}else{
				document.forms['itemForm'].submit();
			}

		//}


	}

	function createReport(){

		document.forms['expenseReport'].submit();

	}

</script>
