<br/>
<h2 style="text-align: center"><?=$vessel->name?></h2>
<?php  ?>
<a href="<?=HTTP_PATH.'hr/crew_movement_summary'?>" class="btn btn-dark">Back</a>
<table id="vessel-info" class="table table-bordered table-striped table-hover" data-toggle="table" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th>Employee ID</th>
			<th>Employee Status</th>
			<th>Name</th>
			<th>Department</th>
			<th>Position</th>
			<!--th>Manage</th-->
		</tr>
	</thead>
	<tbody>
	<?php foreach ($employees as $ctr => $row) { ?>
		<tr>
			<td><?=$row['id']?></td>
			<td><?=$row['employee_status']?></td>
			<td><?=$row['name']?></td>
			<td><?=$row['department']?></td>
			<td><?=$row['position']?></td>
			<!--td><a href="<?=$row['id']?>" class="btn btn-info btn-xs btn-block">edit</a></td-->
		</tr>
	<?php } ?>
	</tbody>
</table>

<script type="text/javascript">
	$(document).ready( function () {
	    $('#vessel-info').bootstrapTable();
	} );
</script>

<br/><br/>
<a href="<?=HTTP_PATH.'hr/crew_movement_summary/add/'.$vessel->id?>" class="btn btn-success" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">
	Add Position
</a><br/><br/>
<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th>Position</th>
			<th>Required Personnel</th>
			<th>Personel Count</th>
			<th>Status</th>
			<th>Date Added</th>
			<th>Added by</th>
			<th>Manage</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($vessel_positions as $ctr => $row) { ?>
		<tr>
			<td><?=$row['position']?></td>
			<td><?=$row['quantity']?></td>
			<td><?=$row['position_count']?></td>
			<td><?=$row['status']?></td>
			<td><?=date("F j, Y",strtotime($row['date_added']))?></td>
			<td><?=$row['added_by']?></td>
			<td>
				<!--a href="<?=$row['id']?>" class="btn btn-info btn-xs btn-block">edit</a-->
				<a href="<?=HTTP_PATH.'hr/crew_movement_summary/edit/'.$row['id']?>" class="btn btn-warning btn-xs btn-block" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Edit</a>
				<a href="<?=HTTP_PATH.'hr/crew_movement_summary/delete/'.$row['id']?>" class="btn btn-xs btn-danger btn-block">Delete</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<br/><br/><br/><br/><br/><br/><br/><br/>