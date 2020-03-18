<a class="btn btn-info" href="<?php echo HTTP_PATH.'mastertables/leave_credit_codes/add/'; ?>" data-toggle="modal" data-target="#modalDialog">Add New <span class="glyphicon glyphicon-plus"></span></a>
<table id="credit_codes-table" class="table table-bordered table-striped table-hover" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
			<th class="col-sm-2" data-align="left" data-sortable="true" data-visible="true">Code</th>
			<th data-align="left" data-sortable="true" data-visible="true">Tenurity</th>
			<th data-align="left" data-sortable="true" data-visible="false">VL</th>
			<th data-align="left" data-sortable="true" data-visible="false">VL Inc</th>
			<th data-align="left" data-sortable="true" data-visible="false">Max VL</th>
			<th data-align="left" data-sortable="true" data-visible="false">SL</th>
			<th data-align="left" data-sortable="true" data-visible="false">SL Inc</th>
			<th data-align="left" data-sortable="true" data-visible="false">Max SL</th>
			<th data-align="center" data-sortable="true" data-visible="true">Base</th>
			<th data-align="center" data-sortable="true" data-visible="true">Is office-based</th>
			<th data-align="left" data-sortable="true" data-visible="true"><center>Description</center></th>
			<th data-align="center">Manage</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($leave_credit_codes as $ctr => $row) { ?>
			<tr>
				<td><?=$row->code?></td>
				<td><?=$row->tenurity?></td>
				<td><?=$row->vacation_leave?></td>
				<td><?=$row->vl_inc?></td>
				<td><?=$row->max_vl?></td>
				<td><?=$row->sick_leave?></td>
				<td><?=$row->sl_inc?></td>
				<td><?=$row->max_sl?></td>
				<td><?=$row->base_credit?></td>
				<td><?=$row->is_office_based?></td>
				<td><?=$row->description?></td>
				<td>
					<div class="dropdown">
						<button class="btn btn-warning btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Actions <span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li>
								<a href="<?=HTTP_PATH.'mastertables/leave_credit_codes/view/'.$row->id?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Edit</a>
							</li>
							<li>
								<a href="<?=HTTP_PATH.'mastertables/leave_credit_codes/delete/'.$row->id?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Delete</a>
							</li>
						</ul>
					</div>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<script>
	$(function () {
		var $table = $('#credit_codes-table');
		$table.bootstrapTable();
	});
</script>