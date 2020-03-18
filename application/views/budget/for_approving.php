	<?php 

		if($status == 'draft'){
			$draft = true;
		}else{
			$draft = false;
		}
		
		//$summary_id = $_GET[]
	?>

	<h3><?=$type.': '.number_format($sum,2)?></h3>
	<style type="text/css">
		.link{
			text-decoration: underline;
			color: blue;
			cursor: pointer;
		}
	</style>

	<!--button class="btn btn-primary">Add Account</button-->
	<a href="<?php echo HTTP_PATH.'budget/add_account_dialog/'.$id?>" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">
		<button <?php if(!$draft) echo "disabled"; ?> class="btn btn-primary">Add Account</button>
	</a> 
	<table data-toggle="table" class="table table-bordered table-striped table-hover" data-cache="false" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
	<!--table data-toggle="table" id="percentage-table" class="table table-bordered table-striped table-hover" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false"-->
		<thead>
			<tr>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">ID</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Code</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Account Name</th>
				<th data-align="right" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Budget Last Year</th>
				<!--th data-field="increment" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Increment %</th-->
				<th data-align="right" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Increment %</th>
				<!--th data-field="curr_budget_amt" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Estimated Budget</th-->
				<th data-align="right" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Estimated Budget</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Company</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Department</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Vessel</th>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Added by</th>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Added on</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($items as $key => $value) { ?>
				<tr>
					<td><?=$value['id']?></td>
					<td><?=$value['code']?></td>
					<td><?=$value['account']?></td>
					<td><?=$value['prev_budget']?></td>
					<td>
						<?php
							if($status == 'draft'){
								echo '<a class="link" onclick="editPercent('.$value['id'].')">'.$value['increment'].'</a>';
							}else{
								echo $value['increment'];
							}
						?></td>
					<td>
						<?php
							if($status == 'draft'){
								echo '<a class="link" onclick="editAmount('.$value['id'].')">'.$value['curr_budget'].'</a>';
							}else{
								echo $value['curr_budget'];	
							}
						?>
					</td>
					<td><?=$value['company']?></td>
					<td><?=$value['department']?></td>
					<td><?=$value['vessel']?></td>
					<td><?=$value['generated_by']?></td>
					<td><?=$value['generated_on']?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

<script type="text/javascript">
	function editAmount(id){
    	bootbox.prompt({
    		size: "small",
		    title: "Input Amount",
		    centerVertical: 'true',
		    inputType: 'number',
		    callback: function (result) {
		    	if(result != null){
		    		window.location.href = "<?=HTTP_PATH.'budget/edit_amount/'?>"+id+'/'+result;	
		    	}
		    }
		});
    }

    function editPercent(id){
    	bootbox.prompt({
    		size: "small",
		    title: "Input Percent",
		    centerVertical: 'true',
		    inputType: 'number',
		    callback: function (result) {
		    	if(result != null){
		    		window.location.href = "<?=HTTP_PATH.'budget/edit_percent/'?>"+id+'/'+result;	
		    	}
		    }
		});
    }
</script>