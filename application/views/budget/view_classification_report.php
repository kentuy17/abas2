<?php
	$prev_year = date('Y') - 1;
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title"><?=$item_name?>
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<table id="percentage-table" class="table table-bordered table-striped table-hover" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
		<thead>
			<tr>
				<th>ID</th>
				<th>Company</th>
				<th>Department</th>
				<th>Account Name</th>
				<th>Budget Last Year</th>
				<th>Budget This Year</th>
				<th data-visible="false">Manage</th>
			</tr>
			
		</thead>
		<tbody>
		<?php 
		if(isset($classification)){
		foreach($classification as $ctr => $row) { ?>
			<tr>
				<td><?=$row['id']?></td>
				<td><?=$row['company']?></td>
				<td><?=$row['department']?></td>
				<td><?=$row['account_name']?></td>
				<td><?=number_format($row['last_year'],2)?></td>
				<td><?=number_format($row['this_year'],2)?></td>
				<td>
					<a href="<?=HTTP_PATH.'manager/add_account/'.$row['id']?>">
						<button class="btn btn-info btn-xs btn-block">view</button>
					</a>
				</td>
			</tr>
		<?php } } ?>
		</tbody>
	</table>
	<script>
		$(document).ready(function () {
		  $('#percentage-table').bootstrapTable();
 		});
	</script>
</div>
					

