<?php


 $r_tab_active = '';
   $r_in_active = '';
   $c_tab_active = '';
   $c_in_active = '';
   $v_tab_active = '';
   $v_in_active = '';
   $a_tab_active = '';
   $a_in_active = '';
   $p_tab_active = '';
   $p_in_active = '';

   if($tab == 'bank_accounts'){
   	$c_tab_active = 'class="active"';
   	$c_in_active = 'in active';
   }elseif($tab == 'cash_advance'){
   	$v_tab_active = 'class="active"';
   	$v_in_active = 'in active';
   }elseif($tab == 'supplier_accounts'){
   	$r_tab_active = 'class="active"';
   	$r_in_active = 'in active';
   }elseif($tab == 'service_provider'){
   	$a_tab_active = 'class="active"';
   	$a_in_active = 'in active';
	}elseif($tab == 'voucher'){
   	$p_tab_active = 'class="active"';
   	$p_in_active = 'in active';
   }else{
   	$v_tab_active = 'class="active"';
   	$v_in_active = 'in active';
   }


//set location
   if(isset($_SESSION['abas_login']['user_location'])){
			$location 		= $_SESSION['abas_login']['user_location'];
	}else{
		//log user out
		header('Location:' . HTTP_PATH . 'home/logout');
		die();
	}	
?>


                <div class="x_content">

                          
                           <ul class="nav nav-tabs bg-success table-responsive">
                               <li <?php echo $v_tab_active; ?>><a data-toggle="tab" href="##cash_advance">Cash Advances <span class="badge"><?php echo count($cash_advance) ?></span></a></li>
                              <!--
                              <li <?php echo $p_tab_active; ?>><a data-toggle="tab" href="##vouchers">Voucher Releasing <span class="badge"><?php echo count($vouchers) ?></span></a></li>
                              
                              <li <?php echo $r_tab_active; ?>><a data-toggle="tab" href="##supplier_accounts">Supplier Accounts  <span class="badge" ><?php echo count($supplier_accounts) ?></span></a> </li>
                              <li <?php echo $a_tab_active; ?>><a data-toggle="tab" href="##service_provider">Service Provider  <span class="badge" ><?php echo count($service_provider) ?></span></a> </li>
                              -->
                           </ul>
                           
                           <div  class="tab-content">
                           
                              <div id="cash_advance" class="tab-pane fade <?php echo $v_in_active ?> table-responsive">
                                 <br>
                                 <a class="btn btn-primary btn-xs" href="<?php echo HTTP_PATH ?>finance/cash_form" data-toggle="modal" data-target="#modalDialog">
                                 <i class="glyphicon glyphicon-plus"></i> New Cash Request
                                 </a>

                                 <a class="btn btn-primary btn-xs" href="<?php echo HTTP_PATH ?>finance/add_fund_form" data-toggle="modal" data-target="#modalDialog">
                                 <i class="glyphicon glyphicon-plus"></i> Add Funding
                                 </a>
                                 
                                 
                                 <a class="btn btn-primary btn-xs" href="<?php echo HTTP_PATH ?>finance/liquidation_report_form" data-toggle="modal" data-target="#modalDialogLarge" >
                                 <i class="glyphicon glyphicon-plus"></i> Replenishment Report
                                 </a>
                                 
                                 <?php 
								 
								 	$petty = $this->Finance_model->getPettyCashFund($location); 
									$revolving = $this->Finance_model->getRevolvingFund($location); 
									$operational = $this->Finance_model->getOperationalFund($location); 
									?>
                                 <span style="float:right; font-size:14px">
                                 	<strong>Remaining Funds:</strong><br>
                                    <span style="color:#009900">Petty Cash:&nbsp;&nbsp;	<?php  echo number_format($petty,2) ?>&nbsp;&nbsp;&nbsp;</span>			
                                    <span style="color:#0033CC">Revolving Fund:&nbsp;&nbsp;<?php  echo number_format($revolving,2) ?>&nbsp;&nbsp;&nbsp;	</span>
                                    <span style="color:#CC6600">Operations Fund:&nbsp;&nbsp;<?php  echo number_format($operational,2) ?>&nbsp;&nbsp;&nbsp;	</span>
                                 </span>
                                 <hr />
                                <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Date Requested</th>
                                          <th>Requested by</th>
                                          <th>Purpose</th>
                                          <th>Amount</th>

                                          <th>Department</th>
                                          <th>Type of Request</th>

                                          <th>Status</th>
                                          <th>Manage</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
										if( !empty($cash_advance) ) {
                                          foreach ($cash_advance as $cash){

										  	$requested_by = $this->Abas->getEmployee($cash['requested_by']);
											$department  = $this->Abas->getDepartment($cash['department']);

											//var_dump($department->name);										
											$date_released = (empty($cash['date_released'])) ? $date_released = '' : $date_released = date('F j, Y', strtotime($cash['date_released'])); 
											
											$department = (empty($department->name)) ? $department = '' : $department = $department->name; 

                                          ?>
                                       <tr>
                                          <td><?php echo date('F j, Y', strtotime($cash['date_requested'])) ?></td>
                                          <td><?php echo $requested_by['full_name']; ?></td>
                                          <td><?php echo $cash['purpose']; ?></td>
                                          <td align="right"><?php echo number_format($cash['amount'],2); ?></td>

                                          <td><?php echo $department ?></td>
                                          <td><?php echo $cash['type']; ?></td>

                                          <td>
										  	<?php echo $cash['status'] ?>
                                          </td>
                                          <td align="center">
                                             <?php if($cash['status'] == 'For releasing'){  ?>
                                             	<a class="like" href="<?php echo HTTP_PATH ?>finance/cash_release_form/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Release">
                                             	<button type="button" class="btn btn-primary btn-xs"><i class="fa fa-share"></i></button>
                                             	</a>
                                             <?php } ?>

                                             <?php if($cash['status'] == 'For funding approval'){  ?>

                                                <a class="like" href="<?php echo HTTP_PATH ?>finance/cash_form/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" data-remote="<?php echo HTTP_PATH ?>finance/cash_form/<?php echo $cash['id'] ?>" title="Approve">
                                             	<button type="button" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                                             	</a>

                                             <?php } ?>

                                             <?php if($cash['status'] == 'Released'){  ?>
                                             		<a class="like" href="<?php echo HTTP_PATH ?>finance/liquidation_form/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" data-load-url="<?php echo HTTP_PATH ?>finance/liquidation_form/<?php echo $cash['id'] ?>">
                                             <button type="button" class="btn btn-info btn-xs">Liquidate</button>
                                             </a>
                                             <?php } ?>

                                             <?php if($cash['status'] == 'Liquidated'){  ?>
                                             	<a class="like" href="<?php echo HTTP_PATH ?>finance/print_liquidation/<?php echo $cash['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Print">
                                             	<button type="button" class="btn btn-primary btn-xs"><i class="fa fa-print"></i></button>
                                             	</a>
                                             <?php } ?>

                                          </td>
                                       </tr>
										<?php

											}
										}else{

												echo '<tr><td  colspan="6">No data found</td></tr>';
										}
										?>
                                    </tbody>
                                 </table>
                              </div>

                              <!--- Voucher for release -->
                              <div id="vouchers" class="tab-pane fade <?php echo $p_in_active ?> table-responsive">
                                 <br>
                               <table id="datatable-responsive1" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>
                                         <th>Voucher Date</th>
                                          <th>Voucher No.</th>
                                          <th>SI No.</th>
                                          <th>PO Number</th>
                                          <th>Pay To</th>
                                          <th>Amount</th>
                                          <th>Status</th>
                                          <th>Manage</th>
                                        </tr>
                                      </thead>


                                       <tbody>

                                        <?php

										if($vouchers == TRUE){

											foreach($vouchers as $v){

												$payto_name = $this->Abas->getSupplier($v['pay_to']);
												$po_detail = $this->Inventory_model->getDeliveryInfoByVoucherId($v['id']);

												$receipt = '';
												$po_no = '';

												if($po_detail){
													$receipt = $po_detail[0]['receipt_num'];
													$po_no = $po_detail[0]['po_no'];

												}
												//check if there is 2 aprrovers on this voucher
												//$no_approver = $this->Admin_model->checkVoucherApprover($v['id']);

										?>
                                        <tr>
                                          <td><?php echo date('F j, Y',strtotime($v['voucher_date'])) ?></td>
                                          <td><?php echo $v['voucher_number'] ?></td>
                                          <td><?php echo $receipt ?></td>
                                          <td><?php echo $po_no ?></td>
                                          <td><?php echo $payto_name['name'] ?></td>
                                          <td><?php echo number_format($v['amount'],2) ?></td>
                                          <td><?php echo $v['status']?></td>
                                          <td align="center">
                                            <?php

											if($v['status'] == 'For releasing'){
											?>
												                    <a class="like" href="<?php echo HTTP_PATH.'accounting/voucher_release_form/'.$v['id']; ?>" data-toggle="modal" data-target="#modalDialog" title="View">
                                                <button type="button" class="btn btn-primary btn-xs">
                                                <i class="glyphicon glyphicon-search"></i> View</button>
                                            	</a>

											<?php }else{ ?>
                                            	<!--
                                                <a class="like" href="<?php echo HTTP_PATH.'accounting/print_voucher/'.$v['id']; ?>" data-toggle="modal" data-target="#modalDialogSemiWide" title="View">
                                                <button type="button" class="btn btn-success btn-xs">
                                                <i class="glyphicon glyphicon-print"></i> Print</button>
                                            	</a>
                                                -->
                                            <?php } ?>

                                          </td>
                                        </tr>
										<?php
											}

										}else{
											echo '<tr><td  colspan="6">No canvass found</td></tr>';
										} ?>


                                      </tbody>
                                    </table>
                              </div>




                              

                              <div id="supplier_accounts" class="tab-pane fade <?php echo $r_in_active ?> table-responsive" style="display:none">
                                 <hr />
                                <table id="datatable-responsive3" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Address</th>
                                          <th>Contact Person</th>
                                          <th>Telephone Number</th>
                                          <!---
                                          <th>Fax Number</th>
                                          <th>Email</th>
                                          --->
                                          <th>Bank Name</th>
                                          <th>Bank Account Number</th>
                                          <th>TIN</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                          if( !empty($supplier_accounts) ) {
                                              foreach ($supplier_accounts as $supplier){
                                          //var_dump($supplierList);exit;
                                          ?>
                                       <tr>
                                          <td><?php  echo $supplier['name'] ?></td>
                                          <td><?php  echo $supplier['address'] ?></td>
                                          <td><?php  echo $supplier['contact_person'] ?></td>
                                          <td><?php  echo $supplier['telephone_no'] ?></td>
                                          <!---
                                          <td><?php//  echo $supplier['fax_no'] ?></td>
                                          <td><?php//  echo $supplier['email'] ?></td>
                                          --->
                                          <td><?php  echo $supplier['bank_name'] ?></td>
                                          <td><?php  echo $supplier['bank_account_no'] ?></td>
                                          <td><?php  echo $supplier['tin'] ?></td>
                                       </tr>
									   <?php

											}
										}else{

												echo '<tr><td  colspan="6">No data found</td></tr>';
										}
										?>
                                    </tbody>
                                 </table>
                              </div>
                              <div id="service_provider" class="tab-pane fade <?php echo $a_in_active ?> table-responsive" style="display:none">
                                 <hr />
                                 <table id="datatable-responsive4" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Company Name</th>
                                          <th>Contact Person</th>
                                          <th>Contact</th>
                                          <th>Address</th>
                                          <th>Bank Name</th>
                                          <th>Bank Account Number</th>
                                          <th>TIN</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                          if( !empty($service_provider) ) {
                                                                      foreach ($service_provider as $service)

                                                                      {
                                          		//var_dump($portList);exit;
                                          	//echo (empty($bank)? "<h2 style='margin-top:30px;'><center>You have no Reservations!</center></h2>": ""); //result not empty
                                                                      ?>
                                       <tr>
                                          <td><?php  echo $service['company_name'] ?></td>
                                          <td><?php  echo $service['contact_person'] ?></td>
                                          <td><?php  echo $service['contact'] ?></td>
                                          <td><?php  echo $service['address'] ?></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                       </tr>
									   <?php

											}
										}else{

												echo '<tr><td  colspan="6">No data found</td></tr>';
										}
										?>
                                    </tbody>
                                 </table>
                              </div>
                          
                           <!-- end of tab--->
                        </div>
                </div>
              </div>







            </div>
          </div>
        </div>
        <!-- /page content -->

    


<script>


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


<script type="text/javascript">
  
  $(document).ready(function() {
     

    $('#modalDialog').on('hide.bs.modal', function() {
      $(this).removeData();
    });
    
	$('#modalDialog').on('show.bs.modal', function () {
	  // do something…
	  $('#past_liquidation').hide();
	}) 

  });    


</script>