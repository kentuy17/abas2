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
		<div class="panel-heading" >
        	&nbsp;
			<span style="float:left; margin-left:0px; margin-top:-10px">
			<h4><strong><span style="background:#000099; color:#FFFFFF">INVENT</span><span style="background:#FF0000; color:#F4F4F4">ORY</span></strong></h4>
            </span>
            <span style="float:right; margin-right:20px; margin-top:-5px">
				<span style="float:right; margin-right:10px; margin-top:5px">
				             
                
                &nbsp;&nbsp;
				<a class="like" href="<?php echo HTTP_PATH ?>inventory/"  title="Back">
					<button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-export"></i>    Back  </button>
                </a>                     
				
                
            </span>
				
                
            </span>
		</div>
		<div class="panel-body">
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					
                    <div class="panel panel-primary"></div>
					<div class="panel panel-default" style="font-size:12px;background:#CCCCCC">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								Inventory Transactions
								</a>
							</h4>
						</div>
						
						<div class="panel-body" style="background:#FFF">
								
							
                            <ul class="nav nav-tabs">
                              <li class="active"><a data-toggle="tab" href="#receiving" >Receiving</a></li>
                              <li><a data-toggle="tab" href="#issuance">Issuance</a></li>
                              <li><a data-toggle="tab" href="#transfer">Transfer</a></li>
                              <li><a data-toggle="tab" href="#request">Requests</a></li>
                              <li><a data-toggle="tab" href="#po">Purchase Order</a></li>
                            </ul>
                            
                            <div class="tab-content">
                              
                              <div id="receiving" class="tab-pane fade in active">
                                <div style="margin-top:20px; margin-left:20px; display:none">
                                	<button type="button" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-import"></i> 
                                    Create Report</button>
                                </div>
                                <p>
                                	<table data-toggle="table" id="invent-table" class="table table-striped table-hover table-responsive" data-cache="false" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-page-size="5"  data-search="true" style="font-size:12px">
										<thead>
											<tr align="center">
												<th width="2%" class="col-sm-0" data-field="id" data-align="center" data-sortable="true" >#</th>
                                                <th width="3%" class="col-sm-1" data-align="center" data-sortable="true">Received Date</th>												
												<th width="20%" class="col-sm-2" >Delivery #</th>
                                                <th  width="48%" class="col-sm-3"  data-sortable="true">Received From</th>
												<th width="15%" class="col-sm-2"  data-sortable="true">PO Number</th>
                                                <th width="5%" class="col-sm-0" data-align="center"    data-sortable="true">Location</th>
                                                <th width="5%" class="col-sm-0" data-align="center"   data-sortable="true">Remarks</th>                                            
												<th width="4%"  data-halign="center" data-align="center">Manage</th>
											</tr>
										</thead>
											<tbody>
											<?php
												
												foreach($receiving as $receive){
												
												
													$supplier = $this->Inventory_model->getSuppliers($receive['supplier_id']);
												

											?>
												<tr>
													<td width="2%" align="center"><?php echo $receive['id']; ?></td>
                                                    <td align="center"><?php echo date('m-d-Y', strtotime($receive['delivery_date'])); ?></td>
													<td width="48%" align="left"><?php echo $receive['delivery_receipt_no']; ?></td>
													<td align="left"><?php echo $supplier[0]['name']; ?></td>
													<td align="left"><?php echo $receive['po_no']; ?></td>
													<td align="center"><?php echo $receive['location']; ?></td>
                                                    <td align="center"><?php echo $receive['remark']; ?></td>
                                                    
													<td><a class="like" href="<?php echo HTTP_PATH.'inventory/print_rr/'.$receive['id']; ?>" title="View" target="_blank"><i class="glyphicon glyphicon-list-alt"></i> View</a></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
                                </p>                                
                              </div>
                              
                              
                              <div id="issuance" class="tab-pane fade">
                                <h3>ISSUANCE</h3>
                                <p>
                                	<table data-toggle="table" id="invent-table" class="table table-striped table-hover table-responsive" data-cache="false" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-page-size="5"  data-search="true" style="font-size:12px">
										<thead>
											<tr align="center">
												<th width="2%" class="col-sm-0" data-field="id" data-align="center" data-sortable="true" >#</th>
                                                <th width="3%" class="col-sm-1" data-align="center" data-sortable="true">Issued Date</th>												
												<th width="20%" class="col-sm-2" >Issued To</th>
                                                <th  width="48%" class="col-sm-3"  data-sortable="true">Issued For</th>
												<th width="15%" class="col-sm-2"  data-sortable="true">Purpose</th>
                                                <th width="5%" class="col-sm-0" data-align="center"    data-sortable="true">Location</th>
                                                
												<th width="4%" data-field="operate"  data-halign="center" data-align="center">Manage</th>
											</tr>
										</thead>
											<tbody>
											<?php
												
												foreach($issuance as $issue){
												
												

													
													if($issue['issued_for'] == 101){
														$vessel_n = 'Avega Integrated';
													}else{
														$vessel_name = $this->Inventory_model->getVessels($issue['issued_for']);	
														$vessel_n = $vessel_name[0]['name'];
													}	

											?>
												<tr>
													<td width="2%" align="center"><?php echo $issue['id']; ?></td>
                                                    <td align="center"><?php echo date('m-d-Y', strtotime($issue['issue_date'])); ?></td>
													<td width="48%" align="left"><?php echo $issue['issued_to']; ?></td>
													<td align="left"><?php echo $vessel_n ?></td>
													<td align="left"><?php echo $issue['remark']; ?></td>
													<td align="center"><?php echo $issue['from_location']; ?></td>
                                                    
                                                    
													<td>
                                                     <a class="like" href="<?php echo HTTP_PATH.'inventory/print_is/'.$issue['id']; ?>" title="View" target="_blank"><i class="glyphicon glyphicon-list-alt"></i> View</a>
                                                    </td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
                                </p>
                              </div>
                              
                              <div id="transfer" class="tab-pane fade">
                                <h3>INVENTORY TRANSFER</h3>
                                <p>
                                	
                                    <table data-toggle="table" id="invent-table" class="table table-striped table-hover table-responsive" data-cache="false" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-page-size="5"  data-search="true" style="font-size:12px">
										<thead>
											<tr align="center">
												<th width="2%" class="col-sm-0" data-field="id" data-align="center" data-sortable="true" >#</th>
                                                <th width="3%" class="col-sm-1" data-align="center" data-sortable="true">Transfer Date</th>												
												<th width="20%" class="col-sm-2" >Transfered From</th>
                                                <th width="20%" class="col-sm-2" >Transfered To</th>
                                                
												<th width="15%" class="col-sm-2"  data-sortable="true">Purpose</th>
                                                
                                                
												<th width="4%"  data-halign="center" data-align="center">Manage</th>
											</tr>
										</thead>
											<tbody>
											<?php
												
												foreach($transfer as $trans){
												
												
													//$issued_for = $this->Inventory_model->getVessels($issue['issued_for']);
												

											?>
												<tr>
													<td width="2%" align="center"><?php echo $trans['id']; ?></td>
                                                    <td align="center"><?php echo date('m-d-Y', strtotime($trans['transfer_date'])); ?></td>
													<td width="48%" align="left"><?php echo $trans['from_location']; ?></td>
													<td align="left"><?php echo $trans['to_location']; ?></td>
													<td align="left"><?php echo $trans['remark']; ?></td>													
                                                    
                                                    
													<td>
                                                    <a class="like" href="<?php echo HTTP_PATH.'inventory/print_tr/'.$trans['id']; ?>" title="View" target="_blank"><i class="glyphicon glyphicon-list-alt"></i> View</a>
                                                    </td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
                                    
                                </p>
                              </div>
                              <div id="request" class="tab-pane fade">
                                <h4>INVENTORY REQUEST</h4>
                                <p>
                                	
                                    <table data-toggle="table" id="invent-table" class="table table-striped table-hover table-responsive" data-cache="false" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-page-size="5"  data-search="true" style="font-size:12px">
										<thead>
											<tr align="center">
												<th width="2%" class="col-sm-0" data-field="id" data-align="center" data-sortable="true" >#</th>
                                                <th width="3%" class="col-sm-1" data-align="center" data-sortable="true">Request Date</th>												
												<th width="20%" class="col-sm-2" >Request By</th>                                                
                                                <th width="20%" class="col-sm-2" >Status</th>
                                                
                                                
                                                
												<th width="4%"  data-halign="center" data-align="center">Manage</th>
											</tr>
										</thead>
											<tbody>
											<?php
												
												foreach($requests as $request){										
												
													//$issued_for = $this->Inventory_model->getVessels($issue['issued_for']);											?>
												<tr>
													<td width="2%" align="center"><?php echo $request['id']; ?></td>
                                                    <td align="center"><?php echo date('m-d-Y', strtotime($request['request_date'])); ?></td>
													<td width="48%" align="left"><?php echo $request['request_by']; ?></td>													
													<td align="left"><?php echo $request['remark']; ?></td>													
                                                    
                                                    
													<td>
                                                    	<a class="like" href="<?php echo HTTP_PATH.'inventory/print_req/'.$request['id']; ?>" title="View" target="_blank"><i class="glyphicon glyphicon-list-alt"></i> View</a>
                                                    </td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
                                
                                </p>
                              </div>
                              
                              
                              <div id="po" class="tab-pane fade">
                                <h4>PURCHASE ORDERS</h4>
                                <p>
                                	
                                     <table data-toggle="table" id="invent-table" class="table table-striped table-hover table-responsive" data-cache="false" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-page-size="7"  data-search="true" style="font-size:12px">
                                                <thead>
                                                    <tr align="center">
                                                    
                                                        <th width="3%" class="col-sm-2" data-align="center" data-sortable="true">PO Date</th>												
                                                        <th width="20%" class="col-sm-2" >PO Number</th>
                                                        <th  width="48%" class="col-sm-3"  data-sortable="true">Supplier</th>                                                    
                                                        <th width="5%" class="col-sm-0"     data-sortable="true">Location</th>
                                                        <th width="5%" class="col-sm-0"    data-sortable="true">Remarks</th>                                            
                                                        <th width="4%"  data-halign="center" data-align="center">Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                	<?php 
														foreach($pos as $po){ 
														
														$sup = $this->Inventory_model->getSuppliers($po['supplier_id']);
														
													?>
                                               		<tr>
                                                    
                                                        <td align="center"><?php echo date('F j, Y', strtotime($po['po_date'])); ?></td>
                                                        <td width="48%" align="left"><?php echo $po['po_no']; ?></td>
                                                        <td align="left"><?php echo $sup[0]['name']; ?></td>
                                                        
                                                        <td align="center"><?php echo $po['location']; ?></td>
                                                        <td align="left"><?php echo $po['remark']; ?></td>
                                                        
                                                        <td align="center"><a data-toggle="tab" href="#">View</a></td>
                                                    </tr>
                                                    <?php } ?>
                                            	</tbody>
                                        </table>
                                
                                </p>
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
</div>


<script>


	function operateFormatter(value, row, index) {
		id = row['id']; //alert(id);
		return [
            '<a class="like" href="<?php echo HTTP_PATH.'inventory/print_rr/'; ?>'+row['id']+'" title="View" target="_blank">',
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
