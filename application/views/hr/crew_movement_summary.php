<h2>Vessel Summary</h2>
<?php //$this->Mmm->debug($items) ?>

<table id="emp-table" class="table table-bordered table-striped table-hover" data-toggle="table" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
	<thead>
		<tr>
			<th>#</th>
			<th>Vessel</th>
			<th>Company</th>
			<th>Status</th>
			<th>Number of Crews</th>
			<th>Manage</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($items as $ctr => $row) { ?>
		<tr>
			<td><?=$ctr+1?></td>
			<td><?=$row['vessel']?></td>
			<td><?=$row['company']?></td>
			<td><?=$row['status']?></td>
			<td><?=$row['crew_count']?></td>
			<td><a href="<?=HTTP_PATH.'hr/crew_movement_summary/view/'.$row['id']?>" class="btn btn-info btn-xs btn-block">view</a></td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<script type="text/javascript">
	$(document).ready( function () {
	    $('#emp-table').bootstrapTable();
	} );
</script>
