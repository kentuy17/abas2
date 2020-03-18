<div class="panel panel-primary" >
	<div class="panel-heading" style="min-height">
	   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	 	Request for Payment Details	
	</div>
</div>
		<div class="panel-body">
			<div class="col-xs-12 col-sm-12 table-responsive">
			<table data-toggle="table" id="request_for_payment_details" class="table table-bordered table-striped table-hover" data-show-columns="true">
				<?php
					echo "<tr>";
						echo "<td><b>Transaction Code:</b></td>";
						echo "<td>".$request_for_payment[0]['id']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td><b>Control No:</b></td>";
						echo "<td>".$request_for_payment[0]['control_number']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td><b>Company:</b></td>";
						$company = $this->Abas->getCompany($request_for_payment[0]['company_id']);
						echo "<td>".$company->name."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td><b>Vessel/Office:</b></td>";
						$vessel = $this->Abas->getVessel($request_for_payment[0]['vessel_id']);
						echo "<td>".$vessel->name."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td><b>Requested Date:</b></td>";
						echo "<td>".date('F j, Y',strtotime($request_for_payment[0]['request_date']))."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td><b>Requested By:</b></td>";
						$requestor = $this->Abas->getUser($request_for_payment[0]['requested_by']);
						echo "<td>".$requestor['full_name']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td><b>Payee Type:</b></td>";
						echo "<td>".$request_for_payment[0]['payee_type']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td><b>Amount:</b></td>";
						echo "<td>".number_format($request_for_payment[0]['amount'],2,'.',',')."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td><b>Purpose:</b></td>";
						echo "<td>".$request_for_payment[0]['purpose']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td><b>Remark:</b></td>";
						echo "<td>".$request_for_payment[0]['remark']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td><b>Status:</b></td>";
						echo "<td>".$request_for_payment[0]['status']."</td>";
					echo "</tr>";
				?>
			</table>
		</div>
	</div>