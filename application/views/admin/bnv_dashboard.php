<?php
 //echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png';
 	
	$r_tab_active = '';
	$r_in_active = '';
	$c_tab_active = '';
	$c_in_active = '';
	$v_tab_active = '';
	$v_in_active = '';
	$ca_tab_active = '';
	$ca_in_active = '';
	$p_tab_active = '';
	$p_in_active = '';
	$j_tab_active = '';
	$j_in_active = '';
	
	if($tab == 'canvass'){
		$c_tab_active = 'class="active"';
		$c_in_active = 'in active';
	}elseif($tab == 'voucher'){
		$v_tab_active = 'class="active"';
		$v_in_active = 'in active';
	}elseif($tab == 'purchase_order'){
		$p_tab_active = 'class="active"';
		$p_in_active = 'in active';
	}elseif($tab == 'job_order'){
		$j_tab_active = 'class="active"';
		$j_in_active = 'in active';
	}elseif($tab == 'request'){
		$r_tab_active = 'class="active"';
		$r_in_active = 'in active';
	}elseif($tab == 'cash_advance'){
		$ca_tab_active = 'class="active"';
		$ca_in_active = 'in active';
	}
	
	
	//REQUEST COUNT
	$req_ctr = 0;			
	foreach($requests as $r){
	
		if(strtoupper($r['status']) == strtoupper('For request approval')){
			$req_ctr = $req_ctr + 1;
		}
		
	}
	
	
	//PO COUNTER PER APPROVAL LEVEL
	$po_ctr = 0;			
	foreach($purchase_orders as $p){										
		
		if($p['amount']<=50000) { 
		  if($this->Abas->checkPermissions("purchasing|approve_low_amount_po",false)) $po_ctr = $po_ctr + 1; 
		} 
		elseif($p['amount']>50000 && $p['amount']<=150000) { 
		  if($this->Abas->checkPermissions("purchasing|approve_medium_amount_po",false)) $po_ctr = $po_ctr + 1; 
		} 
		elseif($p['amount']>150000) { 
		  if($this->Abas->checkPermissions("purchasing|approve_high_amount_po",false)) $po_ctr = $po_ctr + 1; 
		} 
		
	}

	//JO COUNTER PER APPROVAL LEVEL
	$jo_ctr = 0;			
	foreach($job_orders as $j){										
		
		if($j['amount']<=50000) { 
		  if($this->Abas->checkPermissions("purchasing|approve_low_amount_jo",false)) $jo_ctr = $jo_ctr + 1; 
		} 
		elseif($j['amount']>50000 && $j['amount']<=150000) { 
		  if($this->Abas->checkPermissions("purchasing|approve_medium_amount_jo",false)) $jo_ctr = $jo_ctr + 1; 
		} 
		elseif($j['amount']>150000) { 
		  if($this->Abas->checkPermissions("purchasing|approve_high_amount_jo",false)) $jo_ctr = $jo_ctr + 1; 
		} 
		
	}	

?>

    
      
    <!-- top tiles 
         
            
			<?php if($this->Abas->checkPermissions("manager|requests",false)): ?>
				<?php if(count($requests) > 0 ){?>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" style="display:none">
                  <span class="blink_me">
                  <span class="count_top"><i class="fa fa-clock-o"></i> <?php //echo count($requests) ?> Requests For Approval</span>
                  </span>
                  <div class="count"></div>   
                </div>
                <?php } ?>
            <?php endif; ?>                
                
             <?php if($this->Abas->checkPermissions("manager|canvass",false)): ?>
				<?php if($canvass){?>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                  <span class="blink_me">
                  <span class="count_top"><i class="fa fa-clock-o"></i> <?php echo count($canvass) ?> Canvass for Approval</span>
                  </span>
                  <div class="count"></div>
                </div>
                <?php } ?>
            <?php endif; ?>    
            <!---
            <?php if($this->Abas->checkPermissions("manager|vouchers",false)): ?>
				<?php if(count($vouchers) > 0 ){?>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" >
                  <span class="blink_me">
                  <span class="count_top"><i class="fa fa-clock-o"></i>  <?php echo count($vouchers) ?> Vouchers for Approval</span>
                  </span>
                  <div class="count"></div>
                </div>
                <?php }  ?>
			<?php endif; ?>  
            
            <?php if($this->Abas->checkPermissions("manager|cash_requests",false)): ?>
				<?php if(count($cash_advance) > 0 ){?>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                  <span class="blink_me">
                  <span class="count_top"><i class="fa fa-clock-o"></i>  <?php echo count($cash_advance) ?> Cash Advance for Approval</span>
                  </span>
                  <div class="count"></div>    
                </div>
                <?php }  ?>
			<?php endif; ?>  
            --->
         
          <!-- /top tiles -->
			
            
            <!-- Tab Content here --->
                    <ul class="nav nav-tabs bg-success table-responsive">
                        
						<?php if($this->Abas->checkPermissions("manager|requests",false)): ?>
                        <li <?php echo $r_tab_active; ?>><a data-toggle="tab" href="##request">Requests <span class="badge"><?php echo $req_ctr; //count($requests) ?></span></a></li>
                        <?php endif; ?>
                        
                        <?php if($this->Abas->checkPermissions("manager|canvass",false)): ?>
                        <li <?php echo $c_tab_active; ?>><a data-toggle="tab" href="##canvass">Canvass <span class="badge"><?php 
                            echo ($canvass) ? count($canvass) : 0; ?></span></a></li>
                        <?php endif; ?>
                        
                        <?php if($this->Abas->checkPermissions("manager|purchase_orders",false)): ?>
                        <li <?php echo $p_tab_active; ?>><a data-toggle="tab" href="##purchase_orders">Purchase Orders <span class="badge">
							<?php    echo $po_ctr; ?></span></a></li>
                        <?php endif; ?>

                          <?php if($this->Abas->checkPermissions("manager|job_orders",false)): ?>
                        <li <?php echo $j_tab_active; ?>><a data-toggle="tab" href="##job_orders">Job Orders <span class="badge">
							<?php    echo $jo_ctr; ?></span></a></li>
                        <?php endif; ?>
                        
                        <?php if($this->Abas->checkPermissions("manager|check_vouchers",false)): ?>
                        <li style="display:block" <?php echo $v_tab_active; ?>><a data-toggle="tab" href="##vouch">Check Vouchers  
                        <span class="badge" ><?php //echo count($vouchers) ?></span></a> </li>
                        <?php endif; ?>
                        
                        <?php if($this->Abas->checkPermissions("manager|request_for_payments",false)): ?>
                        <li <?php echo $ca_tab_active; ?> style="display:block">
                        	<a data-toggle="tab" href="##cash_advance">Request For Payments  
                            <span class="badge" ><?php //echo count($cash_advance) ?></span></a> </li>
                        <?php endif; ?>
                    </ul>
					<div class="tab-content">
                        <div id="request" class="tab-pane fade <?php echo $r_in_active ?> table-responsive">
							<br>
                           <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                <thead>
                                    <th >Request #</th>
                                    <th >Date Requested</th>                                    
                                    <th >Requisitioner</th>
                                    <th >Purpose</th>
                                    <th >Priority</th>
                                    <th >Status</th>
                                    <th >Manage</th>
                                </thead>
                                <tbody>
                                    <?php
									
									
									if(count($requests)){
										
										foreach($requests as $r){
										
											if(strtoupper($r['status']) == strtoupper('For request approval')){
                                    ?>
                                        <tr>
                                          <td width="5%" align="center"><?php echo $r['id'] ?></td>
                                          <td width="15%"><?php echo date('F j, Y', strtotime($r['tdate'])) ?></td>                                    
                                          <td width="25%"><?php echo $r['requisitioner'] ?></td>
                                          <td width="30%"><?php echo $r['purpose'] ?></td>
                                          <td width="10%"><?php echo $r['priority'] ?></td>
                                          <td width="10%"><?php echo $r['status'] ?></td>
                                          <td width="10%"  align="center">
    
    
    
                                            <a class="like" href="<?php echo HTTP_PATH."admin/request_approval_form/".$r['id']; ?>" data-toggle="modal" data-target="#modalDialogNorm" title="View Details">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-search"></i> View</button>
                                            </a>

                                        </tr>
                                        <?php 
											}
										}
										
									}else{
										echo '<tr><td  colspan="6">No request found</td></tr>';
									}
										?>

                                    </tbody>
                                </table>
                            </div>
                            
                            <div id="canvass" class="tab-pane fade <?php echo $c_in_active ?> table-responsive">
									<br>
                            		<table id="datatable-responsive1" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                <thead>
                                    <th >Canvassed Items</th>

                                </thead>
                                <tbody>
                                    <?php

									
                                    if($canvass){
									    
										foreach($canvass as $canv){
											
																			
											//get vessel name
											$vessel = $this->Abas->getVessel($canv[0]['vessel_id']);
											
											//$det = $this->Purchasing_model->getRequestDetail($canv['id']);
											
											$bg_priority = '#009933';
											if($canv[0]['priority'] == 'High'){ $bg_priority = '#FF0000'; }
											//var_dump($count);
											
											$sql_append = " AND status = 'For canvass approval'";
											$item = $this->Admin_model->getRequestDetailDistinctItems($canv[0]['id'], $sql_append);
											
											
											$itemCanvass = $this->Admin_model->getSupplierCanvass($item[0]['item_id'],$canv[0]['id']);
											
											
											
										if($canv > 0){
                                    ?>
                                            <tr>
                                              <td colspan="5">
        
                                                    <div style="font-size:16px"><?php echo count($itemCanvass); ?> canvass waiting for your approval, requested by <?php echo $canv[0]['requisitioner'] ?> for <?php echo $vessel->name; ?></div>
                                                    <div style="font-size:12px">Purpose:  <?php echo $canv[0]['purpose'] ?></div>
                                                    <div style="font-size:12px; color:<?php echo $bg_priority; ?>">Priority: <i class="fa fa-exclamation-circle"></i>  <?php echo $canv[0]['priority'] ?></div>
        
                                                    <a class="like" href="<?php echo HTTP_PATH."admin/canvass_approval_form/".$canv[0]['id']; ?>" data-toggle="modal" data-target="#modalDialogNorm" title="View Details">
                                                    <br>
                                                    <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-search"></i> View Canvass</button>
                                                </a>
        
                                              </td>
                                            </tr>

                                        <?php }
										
										}

								}else{
									echo '<tr><td  colspan="6">No canvass found</td></tr>';
								} ?>

                                    </tbody>
                                </table>


                            </div>
							
                            
                            <!--- PURCHASE ORDER FOR APPROVAL -->
						
                        	<div id="purchase_orders" class="tab-pane fade <?php echo $p_in_active ?> table-responsive">
							<br>
                           <table id="datatable-responsive2" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                <thead>                                    
                                    <th >Date</th>                                    
                                    <th >PO#</th>
                                    <th >Company</th>
                                    <th >Requested For</th>
                                    <th >Supplier</th>
                                    <th >Served By</th>
                                    <th >Requisition Approved By</th>
                                    <th >Remark</th>
                                    <th >Manage</th>
                                </thead>
                                <tbody>
                                    <?php
									
									
									if(count($purchase_orders)){
										
										foreach($purchase_orders as $po){
										
											//if(strtoupper($r['status']) == strtoupper('For request approval')){
											$supplier = $this->Abas->getSupplier($po['supplier_id']);
											$company = $this->Abas->getCompany($po['company_id']);
											$serve_by = $this->Abas->getUser($po['added_by']);
											$requisition = $this->Purchasing_model->getRequest($po['request_id']);
											//$this->Mmm->debug($po);
											
											
											
											$can_approve  =  false; 
											if($po['amount']<=50000) { 
											  if($this->Abas->checkPermissions("purchasing|approve_low_amount_po",false)) $can_approve    =  true; 
											} 
											elseif($po['amount']>50000 && $po['amount']<=150000) { 
											  if($this->Abas->checkPermissions("purchasing|approve_medium_amount_po",false)) $can_approve  =  true; 
											} 
											elseif($po['amount']>150000) { 
											  if($this->Abas->checkPermissions("purchasing|approve_high_amount_po",false)) $can_approve  =  true; 
											} 
											
											
											
										if($can_approve  == true){	
                                    ?>
                                        <tr>
                                          <td width="10%" align="center"><?php echo date('F j, Y', strtotime($po['tdate'])) ?></td>
                                          <td width="5%" align="center"><?php echo $po['control_number'] ?></td>                                    
                                          <td width="20%"><?php echo $company->name ?></td>
                                          <td width="10%"><?php echo $requisition['vessel_name'] ?></td>
                                          <td width="20%"><?php echo $supplier['name'] ?></td>
                                          <td width="7%"><?php echo $serve_by['user_location'] ?></td>
                                          <td width="40%"><?php echo ($requisition['approved_by_name']=="")?$requisition['details'][0]['request_approved_by']['full_name']:$requisition['approved_by_name'] ?></td>
                                          <td width="30%"><?php echo $po['remark'] ?></td>

                                          <td width="10%"  align="center">
    
    
    
                                            <a class="like" href="<?php echo HTTP_PATH."admin/po_approval_form/".$po['id']; ?>" data-toggle="modal" data-target="#modalDialog" title="View Details">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-search"></i> View</button>
                                            </a>

                                        </tr>
                                        <?php 
											}
										}
										
									}else{
										echo '<tr><td  colspan="6">No request found</td></tr>';
									}
										?>

                                    </tbody>
                                </table>
                            </div>
                            <!--- END PURCHASE ORDER FOR APPROVAL -->


                            <!--- JOB ORDER FOR APPROVAL -->
						
                        	<div id="job_orders" class="tab-pane fade <?php echo $j_in_active ?> table-responsive">
							<br>
                           <table id="datatable-responsive2" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                <thead>                                    
                                    <th >Date</th>                                    
                                    <th >JO#</th>
                                    <th >Company</th>
                                    <th >Requested For</th>
                                    <th >Supplier</th>
                                    <th >Served By</th>
                                    <th >Requisition Approved By</th>
                                    <th >Remark</th>
                                    <th >Manage</th>
                                </thead>
                                <tbody>
                                    <?php
									
									
									if(count($job_orders)){
										
										foreach($job_orders as $jo){
										
											$supplier = $this->Abas->getSupplier($jo['supplier_id']);
											$company = $this->Abas->getCompany($jo['company_id']);
											$serve_by = $this->Abas->getUser($jo['added_by']);
											$requisition = $this->Purchasing_model->getRequest($jo['request_id']);

											$can_approve  =  false; 
											if($jo['amount']<=50000) { 
											  if($this->Abas->checkPermissions("purchasing|approve_low_amount_jo",false)) $can_approve    =  true; 
											} 
											elseif($jo['amount']>50000 && $jo['amount']<=150000) { 
											  if($this->Abas->checkPermissions("purchasing|approve_medium_amount_jo",false)) $can_approve  =  true; 
											} 
											elseif($jo['amount']>150000) { 
											  if($this->Abas->checkPermissions("purchasing|approve_high_amount_jo",false)) $can_approve  =  true; 
											} 
											
										if($can_approve  == true){	
                                    ?>
                                        <tr>
                                          <td width="10%" align="center"><?php echo date('F j, Y', strtotime($jo['tdate'])) ?></td>
                                          <td width="5%" align="center"><?php echo $jo['control_number'] ?></td>                                    
                                          <td width="20%"><?php echo $company->name ?></td>
                                          <td width="10%"><?php echo $requisition['vessel_name'] ?></td>
                                          <td width="20%"><?php echo $supplier['name'] ?></td>
                                          <td width="7%"><?php echo $serve_by['user_location'] ?></td>
                                          <td width="40%"><?php echo ($requisition['approved_by_name']=="")?$requisition['details'][0]['request_approved_by']['full_name']:$requisition['approved_by_name'] ?></td>
                                          <td width="30%"><?php echo $jo['remark'] ?></td>
                                          <td width="10%"  align="center">

                                            <a class="like" href="<?php echo HTTP_PATH."admin/jo_approval_form/".$jo['id']; ?>" data-toggle="modal" data-target="#modalDialog" title="View Details">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-search"></i> View</button>
                                            </a>

                                        </tr>
                                        <?php 
											}
										}
										
									}else{
										echo '<tr><td  colspan="7">No request found</td></tr>';
									}
										?>

                                    </tbody>
                                </table>
                            </div>
                            <!--- END JOB ORDER FOR APPROVAL -->
                            
                       </div>     


<script>
                 
$(document).ready(function() {
   // $('#datatable-responsive').DataTable();
	//$('#datatable-responsive1').DataTable();
	//$('#datatable-responsive2').DataTable();
});

</script>
