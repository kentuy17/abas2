<h2>Overtime Applications</h2>
<a href="<?=HTTP_PATH.'hr/overtime_approval/add'?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">
	Add Overtime Application
</a>
<button onclick="filter()" class="btn btn-primary pull-right">Filter</button>
<table id="overtime-table" class="table table-bordered table-striped table-hover" data-toggle="table" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
	<thead>
		<tr>
			<th data-sortable="true">Date Filed</th>
			<th data-sortable="true">Employee Name</th>
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
			$employee = $this->Abas->getEmpName($row->employee_id);
	?>
		<tr>
			<td><?=$date_filed?></td>
			<td><?=$employee?></td>
			<td><?=$render_date?></td>
			<td><?=$row->time_from.' - '.$row->time_to?></td>
			<td><?=$row->total_hours?></td>
			<td><?=$row->reason?></td>
			<td><?=$approver?></td>
			<td><?=$row->status?></td>
			<td>
				<a href="<?=HTTP_PATH.'hr/overtime_approval/view/'.$row->id?>" class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">View</a>
			</td>
		</tr>
	<?php 
		} 
	} 
	?>
	</tbody>
</table>

<script type="text/javascript">
	$(document).ready( function () {
	    $('#overtime-table').bootstrapTable();
	} );

	function alertDelete(){
    	bootbox.alert(
    	{
			size: "small",
		    title: "Cannot Delete",
		    message: "Approved Application Cannot be Deleted",
		});
    }

    function confirmDelete(id){
    	bootbox.confirm(
    	{
			size: "small",
		    title: "Confirm Delete",
		    message: "Are you sure you want to delete this Application?",
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
		    		window.location.href = "<?=HTTP_PATH?>"+"corporate_services/leave/delete/"+id;
		    	}
		    }
		});
    }

    function filter(){
    	bootbox.prompt({
    		size: "small",
		    title: "Filter Leave Status!",
		    inputType: 'select',
		    inputOptions: [
		    {
		        text: 'Choose one...',
		        value: '',
		    },
		    {
		        text: 'All',
		        value: 'all',
		    },
		    {
		        text: 'For Processing',
		        value: 'for_processing',
		    },
		    {
		        text: 'Processed',
		        value: 'processed',
		    },
		    {
		        text: 'Rejected',
		        value: 'rejected',
		    }
		    ],
		    callback: function (result) {
		        if(result != ''){
		        	window.location.href = "<?=HTTP_PATH?>"+"hr/overtime_approval?filter="+result;
		        }
		    }
		});
    }
</script>

