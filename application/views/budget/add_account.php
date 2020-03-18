<?php
	$prev_year = date('Y') - 1;
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title">Add Account
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
				<th data-visible="false" >ID</th>
				<th data-sortable="true">Code</th>
				<th data-sortable="true">Account Name</th>
				<th data-sortable="true">Increment %</th>
				<th data-sortable="true">Type</th>
				<th>Manage</th>
			</tr>
			
		</thead>
		<tbody>
		<?php foreach($budget_percentage as $ctr => $row) { ?>
			<tr>
				<td data-visible="false"><?=$ctr+1?></td>
				<td><?=$row['code']?></td>
				<td><?=$row['name']?></td>
				<td><?=$row['percentage']?></td>
				<td><?=$row['type']?></td>
				<td>
					<button onclick="addAccount(<?=$summary_id?>,<?=$row['id']?>)" class="btn btn-info btn-xs btn-block">Add <?=$summary_id.' '.$row['id']?></button>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<script>

		$(document).ready(function () {
		  $('#percentage-table').bootstrapTable();
 		});

		function addAccount(summary_id,id){
	    	bootbox.prompt({
	    		size: "small",
			    title: "Input Amount",
			    centerVertical: 'true',
			    inputType: 'number',
			    callback: function (result) {
			    	if(result != null){
			    		window.location.href = "<?=HTTP_PATH.'budget/add_account/'?>"+summary_id+'/'+id+'/'+result;	
			    	}
			    }
			});
	    }
	</script>
</div>
					

