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
	
?>

        <!-- page content -->
    
           <!-- top tiles -->
         <!-- <div class="row tile_count" style="height:50px; color:#FF6600">
            <div class="col-xs-6 tile_stats_count">
              <span class="count_top"> 
              	
				<a class="like" href="<?php //echo HTTP_PATH ?>forms/port_form" data-toggle="modal" data-target="#modalDialog" title="New Item" data-keyboard="false" data-backdrop="static">
								<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i> Add New Port</button>
							</a>
				<a class="like" href="<?php //echo HTTP_PATH ?>forms/warehouse_form" data-toggle="modal" data-target="#modalDialog" title="New Item" data-keyboard="false" data-backdrop="static">
								<button type="button" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-plus"></i> Add New Warehouse</button>
							</a>	
              </span>
              <div class="count"></div>
              
            </div>
            
          </div>-->		
          <!-- /top tiles -->

          <div class="">
            
              
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="xx_panel">
                
                 <h2>Contracts</h2>

                  <div class="xx_content">

	              	<a href="<?php echo HTTP_PATH.'operation/new_contract_form';?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Add</a>

	              	<a href="<?php echo HTTP_PATH.'operation/'?>" class="btn btn-dark">Clear Filter</a> 

                    <br>
                    	<table data-toggle="table" id="invent-table" class="table table-striped table-hover table-responsive" data-cache="false" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-page-size="5"  data-search="true" style="font-size:12px">
										<thead>
											<tr align="center">
												<th width="3%"  data-field="id" data-align="center" data-sortable="true" >ID</th>
                                                <th width="12%"  data-align="center" data-sortable="true">Contract Date</th>
                                                <th width="12%"  data-align="center" data-sortable="true">Reference #</th>
												<th  width="23%"  data-sortable="true">Contractor</th>
                                                <th  width="24%"   data-sortable="true">Client</th>
												<th width="15%"  >Type</th>
												
                                                <th width="10%"  data-align="center"    data-sortable="true">Status</th>                                            
												<th width="4%"    data-halign="center" data-align="center">Manage</th>
											</tr>
										</thead>
											<tbody>
											<?php
												//var_dump($expenses);
												//exit;
											
											if($contracts){
												
												//$ctr = count($contracts);
												
												foreach($contracts as $contract){
																								
												$company_name = $this->Abas->getCompany($contract['company_id']);
												$client_name = $this->Abas->getClient($contract['client_id']);
												$client='';
												//var_dump($client_name['company']);
												if($client_name){ $client = $client_name['company']; }
												
											?>
												<tr>
													<td  align="center"><?php  echo $contract['id']; ?></td>                                                    
                                                    <td align="left"><?php echo date('F j, Y',strtotime($contract['date_effective'])); ?></td>
													<td  align="left"><?php echo $contract['reference_no']; ?></td>
                                                    <td  align="left"><?php echo $company_name->name; ?></td>
													<td align="left"><?php echo $client; ?></td>
													<td align="left"><?php echo $contract['type']; ?></td>
													
                                                    <td align="center"><?php echo $contract['status']; ?></td>                                                    
													<td align="center">
                                                    	<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.'operation/service_order/view'; ?>/'+ row['id'] +'">View</a>
                                                    </td>
												</tr>
											<?php }											
											}else{
													echo "<tr><td  colspan='8'>No contracts found. </td></tr>";
											}
											 ?>
										</tbody>
									</table>
                    
                  	</div>
                </div>
             
            
            
            
                
            
            
            
          
          
        
        <!-- /page content -->

       
	
    
 