<?php 
   //echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png';
   $id = '';
   $wsr_no = '';
   $wsr_date = '';
   $waybill_no = '';
   $wsi_no = '';
   $reference_no = '';
   $from_location = '';
   $from_location_name = '';
   
   $to_location = '';
   $to_location_name = '';
   
   $region = '';
   $region_name = '';
   
   $transaction_type = '';
   $transaction_type_name = '';
   
   $truck_plate_no = '';
   $bags = '';
   $gross_weight = 0;
   $wsi_gross_weight = 0;
   $net_weight = 0;
   
   $variety = '';
   $age = '';
   $stock_condition = '';
   
   
   if(isset($wsr)){
   	
   	$id = $wsr->id;
   $wsr_no = $wsr->wsr_no;
   $wsr_date = $wsr->wsr_date;
   $waybill_no = $wsr->waybill_no;
   $wsi_no = $wsr->wsi_no;
   $reference_no = $wsr->reference_no;
   
   $from_location = $wsr->from_location;
   $f = $this->Operation_model->getTruckingLocation($wsr->from_location);
   $from_location_name = $f->name;
   
   $to_location = $wsr->to_location;
   $l = $this->Operation_model->getTruckingLocation($wsr->to_location);
   $to_location_name = $l->name;
   
   
   $region = $wsr->region;
   $r =  $this->Abas->getRegion($wsr->region);
   
   $region_name = $r[0]->name;
   
   $transaction_type = $wsr->transaction_type;
   $t =  $this->Operation_model->getTransactionType($wsr->transaction_type);
   $transaction_type_name = $t->transaction_name;
   
   
   $truck_plate_no = $wsr->truck_plate_no;
   $bags = $wsr->bags;
   $gross_weight = $wsr->gross_weight;
   $wsi_gross_weight = $wsr->wsi_gross_weight;
   $net_weight = $wsr->net_weight;
   
   $variety = $wsr->variety;
   $age = $wsr->age;
   $stock_condition = $wsr->stock_condition;
   
   
   }
   
   //for tab
   $r_tab_active = '';
   $r_in_active = '';
   $c_tab_active = '';
   $c_in_active = '';
   $v_tab_active = '';
   $v_in_active = '';
   
   if($tab == 'voucher_deliveries'){
   	$c_tab_active = 'class="active"';
   	$c_in_active = 'in active';
   }elseif($tab == 'services'){
   	$v_tab_active = 'class="active"';
   	$v_in_active = 'in active';
   }elseif($tab == 'cash_advance'){
   	$r_tab_active = 'class="active"';
   	$r_in_active = 'in active';
   }else{
   	$r_tab_active = 'class="active"';
   	$r_in_active = 'in active';
   }
   
   ?>

    

    
 
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                     <div class="x_panel">
                        <div class="x_title">
                           <h2>For Funding Approval</h2>
                           <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                              </li>
                              <li><a class="close-link"><i class="fa fa-close"></i></a>
                              </li>
                           </ul>
                           <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                           <ul class="nav nav-tabs bg-success table-responsive">
                              <li <?php echo $r_tab_active; ?>><a data-toggle="tab" href="##purchasing">Purchasing <span class="badge"><?php echo count($for_funding) ?></span></a></li>
                              <li <?php echo $c_tab_active; ?>><a data-toggle="tab" href="##services">Service Providers <span class="badge"><?php echo count($services) ?></span></a></li>
                              <li <?php echo $v_tab_active; ?>><a data-toggle="tab" href="##cash_advance">Cash Requests  <span class="badge" ><?php echo count($cash_advance) ?></span></a> </li>
                           </ul>
                           <div  class="tab-content">
                              <div id="purchasing" class="tab-pane fade <?php echo $r_in_active ?> table-responsive">
                                 <br>
                                 <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Voucher Date</th>
                                          <th>Voucher Number</th>
                                          <th>Payee</th>
                                          <th>Check Number</th>
                                          <th>Amount</th>
                                          <th>Allocated to</th>
                                          <th>Manage</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php 
                                          if($for_funding == TRUE){	
                                          	foreach($for_funding as $v){
                                          	
                                          		$supplier = $this->Abas->getSupplier($v['payee']);
                                          		$bank = $this->Finance_model->getBank($v['bank_id']);
												
												if(count($bank)){
													$bank_name = $bank[0]['name'];
													$bank_account_no = $bank[0]['account_no'];
												}else{
													$bank_name = '';
													$bank_account_no = '';
												}
                                          		
                                          ?>
                                       <tr>
                                          <td><?php echo date('F j, Y', strtotime($v['voucher_date'])) ?></td>
                                          <td><?php echo $v['voucher_number'] ?></td>
                                          <td><?php echo $supplier['name'] ?></td>
                                          <td><?php echo $v['check_num'] ?></td>
                                          <td><?php echo number_format($v['amount'],'2') ?></td>
                                          <td><a href="##"><?php echo $bank_name.' ('.$bank_account_no.')' ?></a></td>
                                          <td align="center">
                                             <a class="like" href="<?php echo HTTP_PATH ?>finance/purchasing_funding/<?php echo $v['id'] ?>" data-toggle="modal" data-target="#modalDialog" title="Approve Funding">
                                             <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></button>
                                             </a>
                                          </td>
                                       </tr>
                                       <?php 
                                          }
                                          }else{
                                          	
                                          	echo '<tr><td  colspan="6">No request found</td></tr>';
                                          }
                                          ?>
                                    </tbody>
                                 </table>
                              </div>
                              <div id="services" class="tab-pane fade <?php echo $c_in_active ?> table-responsive">
                                 Services
                              </div>
                              <div id="cash_advance" class="tab-pane fade <?php echo $v_in_active ?> table-responsive">
                                 <hr />
                                 <?php  if( !empty($cash_advance) ) { ?>
                                 <table id="datatable-responsive1" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                          <th>Date Requested</th>
                                          <th>Requested by</th>
                                          <th>Amount</th>
                                          <th>Purpose</th>
                                          <th>Department</th>
                                          <th>Type of Request</th>
                                          
                                          <th>Status</th>
                                          <th>Manage</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php 
									   
									   
                                          foreach ($cash_advance as $cash){	
											
											
											$c = $this->Finance_model->getCashAdvanceByVoucherId($cash['id']);
											
											$requested_by = $this->Abas->getEmployee($c[0]['requested_by']);  
											$department = $this->Abas->getDepartments($c[0]['department']);  
											
										  
                                       ?>
                                       <tr>
                                          <td><?php echo date('F j, Y', strtotime($c[0]['date_requested'])) ?></td>
                                          <td><?php echo $requested_by['full_name']; ?></td>
                                          <td align="right"><?php echo number_format($c[0]['amount'],2); ?></td>
                                          <td><?php echo $c[0]['purpose']; ?></td>
                                          <td><?php echo $department[0]->name; ?></td>
                                          <td><?php echo $c[0]['type']; ?></td>
                                          
                                          <td><?php echo $cash['status'] ?></td>
                                          <td align="center">
                                            
                                             <button type="button" class="btn btn-primary btn-xs" onClick="
                                             			if(confirm('You are about to approve this request, click ok to continue.')){
                                             				
        
                                                            window.location.href='<?php echo HTTP_PATH ?>finance/cash_forApproval/<?php echo $cash['id'] ?>';
                                             			}
                                             "
                                             
                                             >Approve</button>
                                            
                                          </td>
                                       </tr>
                                       <?php 
											
											}
										}else{
												
												echo '<tr><td  colspan="6">No request found</td></tr>';
										}
										?>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <!-- end of tab--->   
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- /page content -->
         <!-- footer content -->
      
      
    <script>
         $(document).ready(function() {
           
		   $('#datatable-responsive').DataTable();
		   $('#datatable-responsive1').DataTable();
		   
         });
      </script>