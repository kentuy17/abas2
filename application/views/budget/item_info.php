<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title"><?=strtoupper($index->status)?>
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<table data-toggle="table" class="table table-bordered table-striped table-hover" data-cache="false">
		<thead>
			<tr>
				<th data-align="center">ID</th>
				<th data-align="center">Account Type</th>
				<th data-align="center">Company</th>
				<th data-align="center">Department</th>
				<th data-align="center">Vessel</th>
				<th data-align="right">Previous Budget</th>
				<th data-align="right">Estimated Budget</th>
				<th data-align="center" >Manage</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				foreach ($item as $ctr => $row) { 
					$department = $row['department'];
					$vessel = $row['vessel'];
					if($department == null){
						$department = 'All';
					}
					if($vessel == null){
						$vessel = 'All';
					}
			?>
				<tr>
					<td><?=$row['id']?></td>
					<td><?=$row['type']?></td>
					<td><?=$row['company']?></td>
					<td><?=$department?></td>
					<td><?=$vessel?></td>
					<td><?=number_format($row['prev_budget'],2)?></td>
					<td><?=number_format($row['total_amount'],2)?></td>
					<td><a class="btn btn-primary btn-xs btn-block force-pageload" href="<?=HTTP_PATH.'budget/view_account_type/'.$row['id']?>" target="_blank">View</a></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<button class="btn btn-danger pull-right" data-dismiss="modal">Cancel</button>
	<?php if($index->status == 'draft') {
		echo '<a href="'.HTTP_PATH.'budget/approve/submit/'.$index->id.'">
				<button class="btn btn-success pull-right">Submit</button>
			 </a>';
	} ?>
	
	<br/>
</div>
