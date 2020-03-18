<h3 class="text-center">Canvass History Report</h3>
<h2 class="text-center"><?php echo isset($vessel)?$vessel->name:"All Vessels/Offices"; ?></h2>
<p>From <?php echo date("j F Y",strtotime($dstart)); ?> to <?php echo date("j F Y",strtotime($dfinish)); ?></p>
<p><?php echo isset($canvasser)?"Canvassed By: ".$canvasser['full_name']:""; ?></p>
<table border="1" width="100%" class="table table-bordered table-striped table-hover">
	<thead>
		<th>Requisition TSCode No.</th>
		<th>Requisition Control No.</th>
		<th>Company</th>
		<th>Vessel/Office</th>
		<th>Department</th>
		<th>Canvassed On</th>
		<th>Canvassed By</th>
		<th>Approved On</th>
		<th>Approved By</th>
		<th>Status</th>
		<th>Manage</th>
	</thead>
	<tbody>
		<?php
		//$this->Mmm->debug($CR);
			foreach($CR as $canvass_item){
				if($canvass_item->request_id!=NULL){
					$request = $this->Purchasing_model->getRequest($canvass_item->request_id);
					$requisition_no	= $request['control_number'];
					$company_name	= $request['company']->name;
					$vessel_name	= $request['vessel_name'];
					$department_name = $request['department_name'];
				}
				if($canvass_item->canvass_approved_on!=NULL) {
					$approved_on	=	date("j F Y h:i A", strtotime($canvass_item->canvass_approved_on));
				}
				if($canvass_item->canvass_approved_by!=NULL) {
					$approved_by	=	$this->Abas->getUser($canvass_item->canvass_approved_by)['full_name'];
				}
				if($canvass_item->added_on!=NULL) {
					$canvassed_on	=	date("j F Y h:i A", strtotime($canvass_item->added_on));
				}
				if($canvass_item->added_by!=NULL) {
					$canvassed_by	=	$this->Abas->getUser($canvass_item->added_by)['full_name'];
				}
				
				$status	=	"Canvass Approved";
				
				
				echo "<tr>";
					echo "<td>".$request['id']."</td>";
					echo "<td>".$requisition_no."</td>";
					echo "<td>".$company_name."</td>";
					echo "<td>".$vessel_name."</td>";
					echo "<td>".$department_name."</td>";
					echo "<td>".$canvassed_on."</td>";
					echo "<td>".$canvassed_by."</td>";
					echo "<td>".$approved_on."</td>";
					echo "<td>".$approved_by."</td>";
					echo "<td>".$status."</td>";
					echo "<td><a class='btn btn-primary btn-xs btn-block' href='".HTTP_PATH."purchasing/canvass/print/".$canvass_item->request_id."' target='_blank'>Canvass Report</a><a class='btn btn-info btn-xs btn-block' href='".HTTP_PATH."purchasing/requisition/view/".$canvass_item->request_id."'>View Request</a></td>";
				echo "</tr>";
				
			}
		?>
	</tbody>
</table>