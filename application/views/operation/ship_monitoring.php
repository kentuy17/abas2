<?php
$table	=	"<tr><td colspan=5>No Vessels Found!</td></tr>";
if(isset($vessels)) {
	if(!empty($vessels)) {
		$table	=	"";
		foreach($vessels as $v) {
			$timestamp		=	"<p class=''>(".date("H:i l\, j F Y",strtotime($v['activity_date'])).")</p>";
			$weather		=	"<p class=''>".$v['weather']."</p>";
			$link			=	"<p class='text-muted'>".$v['name']."</p><span class='text-muted'>".$v['issued_to']." ".$v['mobile_number']."</span>";
			// $fuel_on_board	=	($v['activity_fob'])?"FOB: ".$v['activity_fob']."L":"FOB: No Report Received";
			$fuel_on_board	=	"FOB: No Report Received";
			$fuel_on_board	=	"";
			$location		=	($v['current_location'])?"<span class=''>".$v['current_location'].$timestamp.$weather."</span><a class='btn btn-default btn-xs exclude-pageload' href='https://www.google.com/maps?q=".$v['coordinates']."' target='_new'>Map View</a>":"No Report Received";

			$tablerow	=	"<tr>";
			$tablerow	.=	"<td>".$link."</td>"; // name, FOB, avg fuel consumption
			$tablerow	.=	"<td>".$fuel_on_board."</td>"; // name, FOB, avg fuel consumption
			$tablerow	.=	"<td>".$location."</td>"; // location & time/date of report
			$tablerow	.=	"<td>".$v['activity']."</td>"; // activity
			$tablerow	.=	"<td>".$v['activity_remark']."</td>"; // remarks
			$tablerow	.=	"<td><a class='btn btn-info btn-sm' href='".HTTP_PATH."vessel/vessel_profile/view/".$v['id']."' data-toggle='modal' data-target='#modalDialog'>View</a></td>"; // remarks
			$tablerow	.=	"</tr>";

			$table	.=	$tablerow;
		}
	}
}

?>
<div class="under-navbar">
	<div class="pull-right">
		<a class="btn btn-default btn-sm" href="<?php echo HTTP_PATH.'sms/report_sim'; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="Simulate SMS Report">
			Report Simulator
		</a>
        <a class="btn btn-default btn-sm" href="<?php echo HTTP_PATH.'operation/fuel_report_form'; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="Fuel Report">
			Vessel Fuel Report
		</a>
	   <a class="btn btn-default btn-sm" href="<?php echo HTTP_PATH.'operation/contract_monitoring'; ?>" class=""  title="Operation Monitoring">
			View Operation Status
		</a>
	</div>
</div>
<div class="container-fluid">
	<div class="panel panel-default">
		<div class="panel-heading">
			Vessel Monitoring
		</div>
		<div class="panel-body">
			<div class="box-body table-responsive no-padding">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th width="15%">Vessel</th>
							<th width="10%">Fuel On Board</th>
							<th width="30%">Current Location / Status</th>
							<th width="30%">Activity</th>
							<th width="15%">Message</th>
						</tr>
					</thead>
					<?php echo $table; ?>
				</table>
			</div>
		</div>
	</div>
</div>
