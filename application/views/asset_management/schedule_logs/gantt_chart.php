<!DOCTYPE html>
<html>
<head>
	<title><?php if(ENVIRONMENT=="development") { echo "[DEV] "; } elseif(ENVIRONMENT=="testing") { echo "[STG] "; } ?>AVega Business Automation System</title>
	<link rel="icon" href="<?php echo LINK."assets/images/av.ico"; ?>">
	<link rel="stylesheet" href="<?php echo LINK."assets/normalize.css"; ?>">
	<!--<link rel="stylesheet" href="<?php echo LINK."assets/bootstrap/css/bootstrap.min.css"; ?>">
	<link rel="stylesheet" href="<?php echo LINK."assets/gentelella-master/build/css/custom.min.css"; ?>">-->
	<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
	<link rel="stylesheet" href="<?php echo LINK."assets/style.css"; ?>" />

	<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
	<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>

	<script src="<?php echo LINK.'assets/jquery/jQuery.print.js' ?>"></script>
	<script src="<?php echo LINK.'assets/jquery/jquery.printPage.js' ?>"></script>

	<script type="text/javascript" src="<?php echo LINK.'assets/ganttChart/lib/date.js';?>"></script>
	<script type="text/javascript" src="<?php echo LINK.'assets/ganttChart/jquery.ganttView.js';?>"></script>

	<script src="<?php echo LINK.'assets/bootstrap/js/bootstrap.min.js'; ?>"></script>
	<script src="<?php echo LINK.'assets/gentelella-master/vendors/bootbox/bootbox.min.js'; ?>"></script>

	
	<style type="text/css">

		.bt { font-weight:bold; text-align:left; font-size:160%;}
		.btx { font-weight:bold; text-align:left; font-size:140%}
		td{ text-align:center; }
		.tdx { text-align:center;}
		.tdy { text-align:right; font-weight:bold;}

		table {
		    font-family: arial, sans-serif;
		    border-collapse: collapse;
		    width: 50%;
		}

		td, th {
		    border: 1px solid #dddddd;
		    text-align: left;
		    padding: 8px;
		}



	</style>
</head>
<body style="background:#FFFFFF">

<div class="panel panel-success" ">
	<div class="panel panel-heading">
	</div>

	<hr>

	<div id="gantt_chart" class="panel panel-body" style='width:100%'>
		<table border='0'>
			<tr>
				<td><center><img src="<?php echo LINK .'assets/images/AvegaLogo.jpg'?>" alt="Avega_Logo"></center></td>
				<td colspan="4">
					<h2 class="bt"><b><?php echo $schedule_log['company_name']?></b></h3>
	    			<h2 class="btx"><?php echo $schedule_log['company_address']?></h3>
	    			<h2 class="btx"><?php echo $schedule_log['company_contact']?></h3>
				</td>
			</tr>
			
			<?php

				if(isset($schedule_log['updated_on'])){
					$gantt_date = date('Y-m-d',strtotime($schedule_log['updated_on']));
				}else{
					$gantt_date =  date('Y-m-d',strtotime($schedule_log['created_on']));
				}

			?>

			<div style="position:absolute;margin-top:20px; margin-left:1120px" > 
				Date: <?php echo $gantt_date;?>
			</div>
			<div style="position:absolute;margin-top:40px; margin-left:1120px" class='bt'> 
				Control No. <?php echo $schedule_log['control_number']?>
			</div>
			
		</table>
		<br>
		

		<?php if($schedule_log['type']=='Vessel'){?>

			<table border='0' cellpadding='1'>
				<tr>
					<td class='bt'>&nbsp&nbspDry Dock Schedule</td>
				</tr>
				<tr>
					<td class='btx'>&nbsp&nbsp(Gantt Chart)</td>
				</tr>
			</table>
			<br>

			<table border='1' cellpadding='5'>
				<tr>
					<td class='tdy'>Name of Vessel:</td>
					<td><?php echo $schedule_log['asset_name']?></td>
					<td class='tdy'>Work Order No.:</td>
					<td><?php echo $schedule_log['report_form_no']?></td>
				</tr>
				<tr>
					<td class='tdy'>Dry Dock Date:</td>
					<td><?php echo $schedule_log['dry_docking_date']?></td>
					<td class='tdy'>Survey Form No.:</td>
					<td><?php echo $schedule_log['evaluation_form_no']?></td>
				</tr>
				<tr>
					<td class='tdy'>Dry Dock Location:</td>
					<td><?php echo $schedule_log['dry_docking_location']?></td>
					<td class='tdy'>Bill of Materials No.:</td>
					<td><?php echo $schedule_log['bill_of_materials_no']?></td>
				</tr>
			</table>
		<?php }?>

		<?php if($schedule_log['type']=='Truck'){?>

			<table border='0' cellpadding='1'>
				<tr>
					<td class='bt'>&nbsp&nbspMotorpool Repairs and Maintenance Schedule Logs</td>
				</tr>
				<tr>
					<td class='btx'>&nbsp&nbsp(Gantt Chart)</td>
				</tr>
			</table>
			<br>

			<table border='1' cellpadding='5'>
				<tr>
					<td class='tdy'>Truck Plate No.:</td>
					<td><?php echo $schedule_log['asset_name']?></td>
					<td class='tdy'>TRMRF No.:</td>
					<td><?php echo $schedule_log['report_form_no']?></td>
				</tr>
				<tr>
					<td class='tdy'>Engine No.:</td>
					<td><?php echo $schedule_log['engine_number']?></td>
					<td class='tdy'>Chassis No.:</td>
					<td><?php echo $schedule_log['chassis_number']?></td>
				</tr>
				<tr>
					<td class='tdy'>Make-Model:</td>
					<td><?php echo $schedule_log['make_model']?></td>
					<td class='tdy'>Bill of Materials No.:</td>
					<td><?php echo $schedule_log['bill_of_materials_no']?></td>
				</tr>
			</table>
		<?php }?>

		<br>
		<div id="ganttChart" style="width: 2500px"></div>
		<br/><br/>
		<div id="eventMessage"></div>

		<!--<div>
			<table style="width: 800px">
				<tr>
					<td><b>Prepared By:</b></td>
					<td><b>Verified By:</b></td>
					<td><b>Approved By:</b></td>
				</tr>
				<tr>
					<td><br><br><br></td>
					<td><br><br><br></td>
					<td><br><br><br></td>
				</tr>
				<tr>
					<td>Name:</td>
					<td>Name:</td>
					<td>Name:</td>
				</tr>
				<tr>
					<td>Date:</td>
					<td>Date:</td>
					<td>Date:</td>
				</tr>
			</table>
		</div>-->

	</div>

</div>


	<link rel="stylesheet" type="text/css" href="<?php echo LINK.'assets/ganttChart/lib/jquery-ui-1.8.4.css';?>">
	<link rel="stylesheet" type="text/css" href="<?php echo LINK.'assets/ganttChart/jquery.ganttView.css';?>">

<?php
		$i = 1;
		$gantt_data = "";
		$count = count($schedule_log_tasks);

		foreach($schedule_log_tasks as $task){

			$plan_start_date_year =  date("Y",strtotime($task['plan_start_date']));
			$plan_start_date_month = date("m",strtotime($task['plan_start_date']));
			$plan_start_date_day = date("d",strtotime($task['plan_start_date']));
			$plan_end_date =  date("Y-m-d",strtotime($task['plan_start_date'].' + '.$task['estimated_time_to_complete'] ." days"));

			$plan_end_date_year =  date("Y",strtotime($plan_end_date));
			$plan_end_date_month = date("m",strtotime($plan_end_date));
			$plan_end_date_day = date("d",strtotime($plan_end_date));


			if($task['actual_start_date']!=0){
				$actual_start_date_year =  date("Y",strtotime($task['actual_start_date']));
				$actual_start_date_month = date("m",strtotime($task['actual_start_date']));
				$actual_start_date_day = date("d",strtotime($task['actual_start_date']));
				$actual_end_date =  date("Y-m-d",strtotime($task['actual_start_date'].' + '.$task['actual_work_duration'] ." days"));
			}else{
				$actual_start_date_year =  $plan_start_date_year;
				$actual_start_date_month = $plan_start_date_month;
				$actual_start_date_day = $plan_start_date_day;
				$actual_end_date =  date("Y-m-d",strtotime($task['plan_start_date']));
			}

			
			$actual_end_date_year =  date("Y",strtotime($actual_end_date));
			$actual_end_date_month = date("m",strtotime($actual_end_date));
			$actual_end_date_day = date("d",strtotime($actual_end_date));

			$this_task = $this->Mmm->sanitize($task['scope_of_work']);
			$this_person = $this->Mmm->sanitize($task['personnel_in_charge']);

			$gantt_data .= "{";
				$gantt_data .= "id:".$i.",work_description:'".$this_task."',plan_start_date:'".$task['plan_start_date']."',personnel_in_charge:'".$this_person."',estimated_time_completion:'".$task['estimated_time_to_complete']." Day(s)'".",actual_start_date:'".$task['actual_start_date']."',actual_work_duration:'".$task['actual_work_duration']."',remarks:'".$task['remarks']."',percentage:'".$task['percentage']."',series: [{name:'Planned',start: new Date(".$plan_start_date_year.",".($plan_start_date_month-01).",".$plan_start_date_day."),end: new Date(".$plan_end_date_year.",".($plan_end_date_month-01).",".$plan_end_date_day."),color:'#426da0'},{name:'Actual',start: new Date(".$actual_start_date_year.",".($actual_start_date_month-01).",".$actual_start_date_day."),end: new Date(".$actual_end_date_year.",".($actual_end_date_month-01).",".$actual_end_date_day."),color:'#b90505'}]";

			if($i==$count){
				$gantt_data .= "}";
			}else{
				$gantt_data .= "},";
			}
			
			
			$i++;

		}

		

		?>

	<script type="text/javascript">

		var ganttData = [<?php echo $gantt_data;?>];

		var window_width = $(window).width();

		$(function () {
			$("#ganttChart").ganttView({ 
				data: ganttData,
				slideWidth: 950,
				behavior: {
					onClick: function (data) { 
						var msg = "You clicked on an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
						$("#eventMessage").text(msg);
					},
					onResize: function (data) { 
						var msg = "You resized an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
						$("#eventMessage").text(msg);
					},
					onDrag: function (data) { 
						var msg = "You dragged an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
						$("#eventMessage").text(msg);
					}
				}
			});
			
			// $("#ganttChart").ganttView("setSlideWidth", 600);
		});
	</script>

</body>


</html>