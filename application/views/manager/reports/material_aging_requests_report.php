<h2>Material Aging Requests</h2>
<br>
<div style="overflow-x: auto">
	<table class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
	    
	    <thead>
	        <tr>
	            <th>Transaction Code No.</th>
	            <th>Date Requested</th>                                    
	            <th>Aging (days)</th>
	            <th>Vessel/Office</th>
	            <th>Purpose</th>
	            <th>Priority</th>
	            <th>Status</th>
	            <th>Serve by</th>                                    
	        	<th>Details</th>
	        </tr>
	    </thead>
	    <tbody>
	    
	        <?php
			
			if(count($aging_list)){
			
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
						  <td width="15%"  align="left"><?php echo $v->name ?></td>
						  <td width="20%"><?php echo $r['purpose'] ?></td>
						  <td width="5%"><?php echo $r['priority'] ?></td>
						  <td width="10%"><?php echo ucwords($r['status']) ?></td>
		                  <td width="7%"><?php echo $s['user_location'] ?></td>
						  <td width="5%"  align="center">
							<a  href="<?php echo HTTP_PATH."manager/material_aging_requests_report/".$r['rid']; ?>" data-toggle="modal" data-target="#modalDialog" title="View Details" >
							<button type="button" class="btn btn-info btn-xs"></i>View</button>
							</a>
						  </td>
					</tr>
	                    
					<?php 
					}
				}
				
			}else{
				echo '<tr><td colspan="6">No request found</td></tr>';
			}
		?>
	    </tbody>
	</table>
</div>