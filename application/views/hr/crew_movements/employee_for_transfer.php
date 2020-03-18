<?php //$this->Mmm->debug($emp_for_transfer); ?>
<h2>Employee/s for Transfer</h2>
<a href="<?=HTTP_PATH.'hr'?>" class="btn btn-dark">Back</a>
<table  data-toggle="table" id="employee-info" class="table table-bordered table-striped table-hover" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
	<thead>
		<tr>
			<th>Employee ID</th>
			<th>Employee Name</th>
			<th>Assinged From</th>
			<th>Assigned To</th>
			<th>Embarkation Start</th>
			<th>Embarkation End</th>
			<th>Transfer Date</th>
			<th>Total Days</th>
			<th>Status</th>
			<th>Manage</th>
		</tr>
	</thead>
	<tbody>
<?php
	if($emp_for_transfer == null){
		echo "No Records Found!";
	}else{
?>
<?php foreach ($emp_for_transfer as $ctr => $row) { ?>
		<tr>
			<td><?=$row['id_no']?></td>
			<td><?=$row['name']?></td>
			<td><?=$row['vessel_from']?></td>
			<td><?=$row['vessel_to']?></td>
			<td><?=$row['embarkation_start']?></td>
			<td><?=$row['embarkation_end']?></td>
			<td><?=$row['transfer_date']?></td>
			<td><?=$row['days']?></td>
			<td><?=$row['status']?></td>
			<td><a href="<?=HTTP_PATH.'hr/employee_profile/view/'.$row['emp_id']?>" class="btn btn-info btn-block btn-xs" >View</a></td>
		</tr>
	<?php
	 	}
	 } 

	?>
	</tbody>
</table>
<script type="text/javascript">
	/*$(function () {
        var $table = $('#employee-info');
        $table.bootstrapTable();
    });*/
    $(document).ready(function(){
    	$('#employee-info').bootstrapTable();
    })
</script>