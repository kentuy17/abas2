<?php
	if(!isset($_GET['date_from']) or !isset($_GET['date_to'])){
		$for_approval = true;
		$year = date('Y');
		$date_from = $year.'-01-31';
		$date_to = $year.'-12-31';
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
		$date_from = $_GET['date_from'];
		$date_to = $_GET['date_to'];
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
<h2 id="glyphicons-glyphs">Application Approval</h2>
<div class="panel-group" role="tablist" aria-multiselectable="true">
	<div class="panel panel-default">
		<div class="panel-body panel-primary">
			<ul class="nav nav-tabs">
				<li class="nav-danger">
					<a href="<?=HTTP_PATH?>Corporate_Services/approval/leave">
						Leave <?=$leave_count > 0 ? "($leave_count)" : ""?>
					</a>
				</li>
				<li class="active nav-warning">
					<a data-toggle="tab" href="#overtime-approval">
						Overtime <?=$overtime_count > 0 ? "($overtime_count)" : ""?>
					</a>
				</li>
				<li class="nav-info"><a data-toggle="tab" href="#undertime-approval">Undertime</a></li>
			</ul>
			<div class="tab-content">
				<div id="leave-approval" class="tab-pane fade in active" >
					<form method="POST" action="<?=HTTP_PATH.'Corporate_Services/approval/filter?form=overtime'?>">
						<div class="panel panel-primary" style="margin-top: 10px;">
							<div class="panel-heading">
								<h3 class="panel-title">Overtime Applications</h3>
							</div>
							<div class="panel-body">
								<div class="form-group">
								    <label for="date_from" class="col-md-1 col-sm-2 col-xs-1">From:</label>
								    <div class="col-lg-3">
								    	<input type="date" name="date_from" class="form-control" value="<?=$date_from?>" required>
								    </div>
								</div><br/><br/>
								<div class="form-group">
								    <label for="date_to" class="col-md-1 col-sm-2 col-xs-1">To:</label>
								    <div class="col-lg-3">
								    	<input type="date" name="date_to" class="form-control" value="<?=$date_to?>" required>
								    </div>
								</div><br/><br/>
								<div class="form-group col-md-12" style="display: inline-block">
									<div class="form-group" >
										<label class="cbStyle1">Show Leave with Status:</label>
										
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
									<a href="<?=HTTP_PATH.'Corporate_Services/approval/overtime'?>" class="btn btn-dark">Refresh</a>
								</div>
							</div>
						</div>
					</form>
					<div class="panel panel-primary" style="padding: 20px;margin-top: 20px">
						<table data-toggle="table" id="overtime-table" class="table table-bordered table-striped table-hover" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
						<thead>
							<tr>
								<th>Date Filed</th>
								<th>Employee Name</th>
								<th>Date to Render</th>
								<th>Duration</th>
								<th>Total Hr/s</th>
								<th>Reason</th>
								<th data-visible="false">Approver</th>
								<th>Status</th>
								<th>Manage</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($overtime as $ctr => $row) { 
								$employee = $this->Abas->getEmpName($row->employee_id);
								$date_filed = $this->Abas->dateFormat($row->date_filed);
								$render_date = $this->Abas->dateFormat($row->render_date);
								$duration = $row->time_from.' - '.$row->time_to;
								$approver = $this->Abas->getEmpName($row->approver_id);
						?>
							<tr>
								<td><?=$date_filed?></td>
								<td><?=$employee?></td>
								<td><?=$render_date?></td>
								<td><?=$duration?></td>
								<td><?=$row->total_hours?></td>
								<td><?=$row->reason?></td>
								<td><?=$approver?></td>
								<td><?=$row->status?></td>
								<td>
									<?php if($row->status == 'FOR APPROVAL'){ ?>
									<div class="dropdown">
										<button class="btn btn-warning btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li>
												<a href="<?=HTTP_PATH.'Corporate_Services/approval/approve/'.$row->id.'?form=overtime'?>">Approve</a>
											</li>
											<li>
												<a href="<?=HTTP_PATH.'Corporate_Services/approval/reject/'.$row->id.'?form=overtime'?>">Reject</a>
											</li>
										</ul>
									</div>
									<?php } elseif($row->status == 'FOR PROCESSING') {?>
									<div class="dropdown">
										<button class="btn btn-warning btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li>
												<a href="<?=HTTP_PATH.'Corporate_Services/approval/cancel/'.$row->id.'?form=overtime'?>">Cancel</a>
											</li>
										</ul>
									</div>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					</div>
				</div>
				<div id="overtime-approval" class="tab-pane fade">
					
				</div>
				<div id="undertime-approval" class="tab-pane fade">
				</div>
			</div>
		</div>
	</div>
</div>

<script>

	function view(value, row, index) {
		return [
			'<a class="btn btn-danger btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'budget/edit_percentage'; ?>/'+ row['id'] +'" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Edit</a>'
		].join('');
	}
	$(function () {
		var $table = $('#leave-table');
		$table.bootstrapTable();
	});

	$(function () {
		var $table = $('#overtime-table');
		$table.bootstrapTable();
	});

	function for_verification(value, row, index) {
		return [
			'<a class="btn btn-warning btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'budget/verify_item_view'; ?>/'+ row['id'] +'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#for-verification-table');
		$table.bootstrapTable();
	});

	function for_approval(value, row, index) {
		return [
			'<a href="<?php echo HTTP_PATH.'budget/verify_item_view';?>/'+ row['id'] +'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" ><button class="btn btn-info btn-xs btn-block force-pageload">View</button></a>'
		].join('');
	}
	$(function () {
		var $table = $('#for-approval-table');
		$table.bootstrapTable();
	});


	function approved(value, row, index) {
		return [
			'<a class="btn btn-success btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.'budget/verify_item_view/'; ?>'+ row['id'] +'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#approved-table');
		$table.bootstrapTable();
	});

</script>

