<?php
 //echo LINK.'assets/images/Avega Bros Integrated Shipping Corp.png';
 	
	//var_dump($aging_list);

?>

    
                           

                   
                  
                       
							<br>
                           <table id="datatable-responsive" style="margin-top:10px" class="table table-striped table-bordered dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                
                                <thead>
                                    <tr>
                                        <th colspan="10" style="font-size:14px">AGING MATERIAL REQUESTS</th>
                                        
                                    </tr>
                                    <tr>
                                        <th >Transaction Code #</th>
                                        <th >Date Requested</th>                                    
                                        <th >Aging (days)</th>
                                        <!---<th style="display:none" >Requisitioner</th>--->
                                        <th >Vessel/Office</th>
                                        <th >Purpose</th>
                                        <th >Priority</th>
                                        <th >Status</th>
                                        <th >Serve by</th>                                    
                                    	<th >*</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                    <?php
									
									
									if(count($aging_list)){
									
										//for 1-5 days
										
									?>	
										
									<?php
										
										foreach($aging_list as $r){
										
											
											$v = $this->Abas->getVessel($r['vessel_id']);
											$s = $this->Abas->getUser($r['added_by']);	
												
											$status = $r['priority']; 	
											
											
											
												switch ($status){
													
													case "High":														
														
														$age = $r['aging'] + 6;
														
														break;
													
													case "Medium":
														$age = $r['aging'] + 4;
														break;
														
													case "Low":
														$age = $r['aging'];
														break;
																										
													
												}
												
																				
											
											
											if($age > 0 ){	
										?>
											 
											  
                                                <tr>
											  <td width="5%" align="center"><?php echo $r['request_id'] ?></td>
											  <td width="10%"><?php echo date('F j, Y', strtotime($r['tdate'])) ?></td>                                    
											  <td width="5%"><?php echo $age ?></td>
                                             <!--- <td width="15%" style="display:none"><?php echo $r['requisitioner'] ?></td>--->
											  <td width="15%"  align="left"><?php echo $v->name ?></td>
											  <td width="20%"><?php echo $r['purpose'] ?></td>
											  <td width="5%"><?php echo $r['priority'] ?></td>
											  <td width="10%"><?php echo $r['status'] ?></td>
                                              <td width="7%"><?php echo $s['user_location'] ?></td>
											  <td width="5%"  align="center">
		
		
		
												<a class="like" href="<?php echo HTTP_PATH."admin/request_detail_view/".$r['rid']; ?>" data-toggle="modal" data-target="#modalDialog" title="View Details" >
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
                            

                           

                           
						

                       

                        <!-- Enf of Tab Content here --->





                 
<script>
                 
$(document).ready(function() {
    //$('#datatable-responsive').DataTable();
	 $('#datatable-responsive').DataTable( {
        "order": [[ 7, "desc" ]]
    } );
} );

</script>







              
        <!-- /page content -->

      
