<?php 


 	
	
	
	
?>
  
                 
                  <div class="x_content">
                   
                    <ul class="nav nav-tabs bg-success table-responsive">
                        <li><a data-toggle="tab" href="##purchasing">For AP Clearing <span class="badge"><?php echo count($voucher_deliveries) ?></span></a></li>
                       
                       	<ul class="nav navbar-right panel_toolbox">
                     		<a class="like" href="<?php echo HTTP_PATH ?>accounting/ap_voucher_search" data-toggle="modal" data-target="#modalDialog" title="Search Voucher">                                                
                     			<button>Search AP Vouchers</button>
                    		 </a>	
                    	</ul>
                    </ul>
                    
                    <div  class="tab-content">
                        
                        <div id="purchasing" class="tab-pane active table-responsive">
                     	<br>
                    	
                    	<table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                      <thead>
                                        <tr>                                          
                                          <th>Date</th>                                          
                                          <th>PO Transaction Code</th>                                          
                                          <th>Payee</th>
                                          <th>Amount</th>                                          
                                          <th>Location</th> 
                                          <th>Status</th>
                                          <th>Manage</th>
                                        </tr>
                                      </thead>


                                      <tbody>
                                        
                                        <?php 
											
											///var_dump($voucher_deliveries);
										if($voucher_deliveries == TRUE){	
											foreach($voucher_deliveries as $v){
											
												$supplier = $this->Abas->getSupplier($v['supplier_id']);
												//$account_name = $this->Accounting_model->getAccount($v['coa_id']);
												$vstat= 'Awaiting document/s';
												$vnumber= '';
												

										?>
                                        <tr>
                                          
                                          <td><?php echo date('F j, Y', strtotime($v['tdate'])) ?></td>
                                          
                                          <td><?php echo $v['po_no'] ?></td>
                                          
                                          <td><?php echo $supplier['name'] ?></td>
                                          <td><?php echo number_format($v['amount'],'2') ?></td>                                         
                                          <td><?php echo $v['location'] ?></td>
                                          <td><?php echo $vstat; ?></td>
                                          <td align="center">
                                            
                                            <a class="like" href="<?php echo HTTP_PATH ?>accounting/ap_clearing_form/<?php echo $v['jid'] ?>/po" data-toggle="modal" data-target="#modalDialog" title="Clear Payable">
                                                <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                                            </a>
                                         
                                            
                                            
                                            
                                          </td>
                                        </tr>
										<?php 
											
											}
										}else{
												
												echo '<tr><td  colspan="7">No record for clearing found</td></tr>';
										}
										?>


                                      </tbody>
                                    </table>
                            </div>      
                     		
                           
                            
                           
                     
                     </div>                     
                     <!-- end of tab--->       
                                 
                  
        
	 <script>  
	  $(document).ready(function() {
		 $('#datatable-responsive').DataTable();
		
	});
   
	  </script>

