<h2>Leave Applications</h2>
<a href="<?=HTTP_PATH.'hr/leave/add'?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">
	Add Leave Application
</a>
<button onclick="filter()" class="btn btn-primary pull-right">Filter</button>
<!--a href="#" class="btn btn-dark">Back</a-->
<table id="leave-table" class="table table-bordered table-striped table-hover" data-toggle="table" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
	<thead>
		<tr>
			<th>Date Filed</th>
			<th>Employee</th>
			<th>Type</th>
			<th>From</th>
			<th>To</th>
			<th data-align="center">Credit/s</th>
			<th data-align="center">No. of Days</th>
			<th data-align="center">Payed</th>
			<th>Status</th>
			<th data-align="center">Manage</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	if(isset($leave)){
		foreach ($leave as $ctr => $row) { ?>
		<tr>
			<td><?=$row['date_filed']?></td>
			<td><?=$row['emp_name']?></td>
			<td><?=$row['type']?></td>
			<td><?=$row['date_from']?></td>
			<td><?=$row['date_to']?></td>
			<td><?=$row['credit']?></td>
			<td><?=$row['days']?></td>
			<td><?=$row['pay']?></td>
			<td><?=$row['status']?></td>
			<td>
				<!--div class="dropdown">
					<button class="btn btn-warning btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions <span class="caret"></span></button>
					<ul class="dropdown-menu">
						<li><a href="<?=HTTP_PATH.'corporate_services/leave/edit/'.$row->id?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Edit</a></li>
						<li><a href="<?=HTTP_PATH.'corporate_services/leave/view/'.$row->id?>">View</a></li>
						<li>
							<?php if($row->status != 'APPROVED') {
								echo '<a onclick="confirmDelete('.$row->id.')">Delete</a>';
							}else{
								echo '<a onclick="alertDelete()">Delete</a>';
							} ?>
						</li>
					</ul>
				</div-->
				<a href="<?=HTTP_PATH.'hr/leave/view/'.$row['id']?>" class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">View</a>
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
	    $('#leave-table').bootstrapTable();
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
		        	window.location.href = "<?=HTTP_PATH?>"+"hr/leave?filter="+result;
		        }
		    }
		});
    }
</script>

