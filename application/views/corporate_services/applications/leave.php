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

<form method="POST" action="<?=HTTP_PATH.'Corporate_Services/leave/filter'?>">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">My Leave Applications</h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
			    <label for="date_from" class="col-md-2 col-sm-1 col-xs-1">Leave Credits: <u><?=$balance?></u></label>
			</div><br/>
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
				<a href="<?=HTTP_PATH.'Corporate_Services/leave'?>" class="btn btn-dark">Refresh</a>
			</div>
		</div>
	</div>
</form>

<div class="panel panel-primary" style="padding: 20px">
	<!--table id="leave-table" class="table table-bordered table-striped table-hover" data-toggle="table" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false"-->
		<table class="table table-dark table-bordered table-striped table-hover" data-sortable="true" id="leave-table"  data-toggle="table" style="overflow-x:auto">
		<!--table id="leave-table" class="tab"-->
			<thead>
				<tr>
					<th data-sortable="true">Date Filed</th>
					<th data-sortable="true">Type</th>
					<th data-sortable="true">From</th>
					<th data-sortable="true">To</th>
					<th data-sortable="true" data-align="center">No. of Days</th>
					<th data-sortable="true" data-align="center">Payed</th>
					<th data-sortable="true">Approver</th>
					<!--th data-sortable="true">Processed by</th-->
					<th data-sortable="true">Status</th>
					<th data-align="center">Manage</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			if(isset($leave)){
				foreach ($leave as $ctr => $row) { 
					$pay = ($row->is_with_pay == 1 ? "<span class='glyphicon glyphicon-ok'/>" : "<span class='glyphicon glyphicon-remove'/>");
					$approver = $this->Abas->getEmpName($row->approver_id);
			?>
				<tr>
					<td><?=$row->date_filed?></td>
					<td><?=$row->type?></td>
					<td><?=$row->date_from?></td>
					<td><?=$row->date_to?></td>
					<td><?=$row->days?></td>
					<td><?=$pay?></td>
					<td><?=$approver?></td>
					<!--td><?=$row->processed_by?></td-->
					<td><?=$row->status?></td>
					<td>
						<div class="dropdown">
							<button class="btn btn-warning btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions <span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li><a href="<?=HTTP_PATH.'Corporate_Services/leave/view/'.$row->id?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">View</a></li>
								<li>
									<!--?php if($row->status != 'APPROVED' or $row->status != 'PROCESSED') {
										echo '<a onclick="confirmDelete('.$row->id.')">Cancel</a>';
									}else{
										echo '<a onclick="alertDelete()">Delete</a>';
									} ?-->
									<?php if($row->status != 'PROCESSED') { ?>
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
	<a href="<?=HTTP_PATH.'Corporate_Services/leave/add'?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">
		File Leave
	</a>
</div>
<!---------------------------------------------------------------->

<!--a href="#" class="btn btn-dark">Back</a-->


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
		    		window.location.href = "<?=HTTP_PATH?>"+"Corporate_Services/leave/cancel/"+id;
		    	}
		    }
		});
    }
</script>
