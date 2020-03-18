<?php
	if(!isset($_GET['date_from']) or !isset($_GET['date_to'])){
		$for_approval = true;
		$for_processing = true;
		$processed = true;
		$rejected = true;
		$cancelled = true;
	}else{
		if(isset($_GET['for_approval'])){
			$for_approval = $_GET['for_approval'];
		}
		if(isset($_GET['for_processing'])){
			$for_processing = $_GET['for_processing'];
		}
		if(isset($_GET['processed'])){
			$processed = $_GET['processed'];
		}
		if(isset($_GET['rejected'])){
			$rejected = $_GET['rejected'];
		}
		if(isset($_GET['cancelled'])){
			$cancelled = $_GET['cancelled'];
		}
	}
?>

<head>
	<style type="text/css">
		label.cbStyle {
			padding-left: 20px;
		}

		input.cbAlign {
			margin-top: 3px;
		}

		table.tableColor thead tr {
			background-color: blue;
		}
	</style>
</head>

<form method="POST" action="<?=HTTP_PATH.'Corporate_Services/overtime/filter'?>">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">My Overtime Applications</h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
			    <label for="date_from" class="col-md-1 col-sm-2 col-xs-1">From:</label>
			    <div class="col-lg-3">
			    	<input type="date" name="date_from" class="form-control" value="<?=$from?>" required>
			    </div>
			</div><br/><br/>
			<div class="form-group">
			    <label for="date_to" class="col-md-1 col-sm-2 col-xs-1">To:</label>
			    <div class="col-lg-3">
			    	<input type="date" name="date_to" class="form-control" value="<?=$to?>" required>
			    </div>
			</div><br/><br/>
			<div class="form-group col-md-12" style="display: inline-block">
				<div class="form-group" >
					<label class="cbStyle1">Show Overtime with Status:</label>
					
					<label class="cbStyle" for="cbForApproval">For Approval</label>
					<input type="checkbox" name="cbForApproval" class="cbAlign" id="cbForApproval" <?=isset($for_approval)?"checked":""?>>
					
					<label class="cbStyle" for="cbForProcessing">For Processing</label>
					<input type="checkbox" name="cbForProcessing" class="cbAlign" id="cbForProcessing" <?=isset($for_processing)?"checked":""?>>
					
					<label class="cbStyle" for="cbProcessed">Processed</label>
					<input type="checkbox" name="cbProcessed" class="cbAlign" id="cbProcessed" <?=isset($processed)?"checked":""?>>
					
					<label class="cbStyle" for="cbRejected">Rejected</label>
					<input type="checkbox" name="cbRejected" class="cbAlign" id="cbRejected" <?=isset($rejected)?"checked":""?>>
					
					<label class="cbStyle" for="cbCancelled">Cancelled</label>
					<input type="checkbox" name="cbCancelled" class="cbAlign" id="cbCancelled" <?=isset($cancelled)?"checked":""?>>
				</div>
			</div>
			<br/><hr/>
			<div class="form-group">
				<input type="submit" name="searchLeave" class="btn btn-success" value="Search">
				<a href="<?=HTTP_PATH.'Corporate_Services/overtime'?>" class="btn btn-dark">Refresh</a>
			</div>
		</div>
	</div>
</form>

<div class="panel panel-primary" style="padding: 20px">
	<table class="table table-dark table-bordered table-striped table-hover" data-sortable="true" id="leave-table"  data-toggle="table" style="overflow-x:auto">
		<thead>
			<tr>
				<th data-sortable="true">Date Filed</th>
				<th data-sortable="true">Date to Render</th>
				<th data-sortable="true">From - To</th>
				<th data-sortable="true" data-align="center">Total Hr/s</th>
				<th>Reason</th>
				<th data-sortable="true" data-visible="false">Approver</th>
				<th data-sortable="true">Status</th>
				<th data-align="center">Manage</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			if(isset($overtime)){
				foreach ($overtime as $ctr => $row) { 
					$approver = $this->Abas->getEmpName($row->approver_id);
					$date_filed = $this->Abas->dateFormat($row->date_filed);
					$render_date = $this->Abas->dateFormat($row->render_date);
					$status = $row->status;
		?>
			<tr>
				<td><?=$date_filed?></td>
				<td><?=$render_date?></td>
				<td><?=$row->time_from.' - '.$row->time_to?></td>
				<td><?=$row->total_hours?></td>
				<td><?=$row->reason?></td>
				<td><?=$approver?></td>
				<td><?=$status?></td>
				<td>
					<div class="dropdown">
						<button class="btn btn-warning btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions <span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li>
								<?php if($status == 'FOR APPROVAL') { ?>
									<a href="<?=HTTP_PATH.'Corporate_Services/overtime/edit/'.$row->id?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Edit</a>
								<?php }else{ ?>
									<a href="<?=HTTP_PATH.'Corporate_Services/overtime/view/'.$row->id?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">View</a>
								<?php } ?>
							</li>
							<li>
								<?php if($status == 'FOR PROCESSING' or $status == 'FOR APPROVAL') { ?>
										<a onclick="confirmDelete(<?=$row->id?>)">Cancel</a>
								<?php } ?>
							</li>
						</ul>
					</div>
				</td>
			</tr>
		<?php 
			} 
		} 
		?>
		</tbody>

	</table>
	<hr/>
	<a href="<?=HTTP_PATH.'Corporate_Services/overtime/add'?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">
		File Overtime
	</a>
</div>
<!---------------------------------------------------------------->

<script type="text/javascript">

	$(document).ready( function () {
	    $('#leave-table').bootstrapTable();
	} );

	function alertDelete(){
    	bootbox.alert(
    	{
			size: "small",
		    title: "Cannot Cancel",
		    message: "Approved Application Cannot be Cancelled",
		});
    }

    function confirmDelete(id){
    	bootbox.confirm(
    	{
			size: "small",
		    title: "Cancel Application",
		    message: "Are you sure you want to cancel this Application?",
		    buttons: {
		        confirm: {
		            label: 'Yes',
		            className: 'btn-success'
		        },
		        cancel: {
		            label: 'No',
		            className: 'btn-danger'
		        }
		    },
		    callback: function (result) {
		    	if(result == true){
		    		window.location.href = "<?=HTTP_PATH?>"+"Corporate_Services/overtime/cancel/"+id;
		    	}
		    }
		});
    }
</script>
