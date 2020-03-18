<?php
	
	$generate_all = true;
	if($this->Abas->checkPermissions("manager|generate_all_budget",false)){
		$generate_all = false;
	}
?>
<?php if($this->Abas->checkPermissions("manager|generate_budget",false)){?>
<a href="<?php echo HTTP_PATH.'budget/generate_budget_form';?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">
	<button <?php if($generate_all) echo "disabled"?> class="btn btn-success">Generate Budget</button>
</a>
<?php } ?>

<button class="btn btn-primary pull-right" onclick="javascript: filterYear()">Select Year</button>

	<table data-toggle="table" id="budget-table" class="table table-bordered table-striped table-hover" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
		<thead>
			<tr>
				<th>Year</th>
				<th>Company</th>
				<th>Department</th>
				<th>Vessel</th>
				<th data-align="right">Total Debit</th>
				<th data-align="right">Total Credit</th>
				<th data-align="center">Status</th>
				<th>Manage</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				foreach ($items as $key => $value) { 
					$department = $value['department'];
					$vessel = $value['vessel'];
					if($value['department'] == null){
						$department = 'All';
					}
					if($value['vessel'] == null){
						$vessel = 'All';
					}
			?>
				<tr>
					<td><?=$value['year']?></td>
					<td><?=$value['company']?></td>
					<td><?=$department?></td>
					<td><?=$vessel?></td>
					<td><?=number_format($value['total_debit'],2)?></td>
					<td><?=number_format($value['total_credit'],2)?></td>
					<td><?=$value['status']?></td>
					<td>
						<a class="btn btn-warning btn-xs btn-block" href="<?=HTTP_PATH.'budget/item_view/'.$value['id']?>" data-toggle="modal" data-target="#modalDialog">View</a>
						<button onclick="confirmDelete(<?=$value['id']?>)" class="btn btn-danger btn-xs btn-block">
							Delete
						</button>

					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

<script>

	function view(value, row, index) {
		return [
			'<a onclick="confirmDelete('+row['id']+')">',
				'<button class="btn btn-danger btn-xs" <?php if($draft) echo 'disabled';?>>Remove</button>',
			'</a>',
		].join('');
	}

	function budget(value, row, index) {
		return [
			<?php if(!$draft){?>
				'<a style="cursor:pointer; color:blue"',
				' onclick="editAmount('+row['id']+')">'+row['curr_budget_amt']+'</a>'
			<?php }else{ ?>
				row['curr_budget_amt']
			<?php } ?>
		].join('');
	}

	function percent(value, row, index) {
		return [
			<?php if(!$draft){?>
				'<a style="cursor:pointer; color:blue"',
				' onclick="editPercentage('+row['id']+')">'+row['increment']+'%</a>'
			<?php }else{ ?>
				row['increment']
			<?php } ?>
		].join('');
	}

	$(function () {
		var $table = $('#budget-table');
		$table.bootstrapTable();
	});
</script>
				
<script type="text/javascript">

    function confirmDelete(id){
    	console.log = "clicked";
    	bootbox.confirm(
    	{
			size: "small",
		    title: "Remove Budgets",
		    message: "Are you sure you want to remove this budget?",
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
		    		window.location.href = "<?=HTTP_PATH.'budget/del_budget/'?>" + id;
		    	}
		    }
		});
    }

	<?php
		$budget_year[0] = date('Y') - 1;
		$budget_year[1] = date('Y');
		$budget_year[2] = date('Y') + 1;
	?>	
	function filterYear(){
    	bootbox.prompt({
    		size: "small",
		    title: "Select Year",
		    centerVertical: 'true',
		    inputType: 'select',
		    inputOptions: [
			    {
			        text: 'All',
			        value: '',
			    },
		    <?php foreach($budget_year as $row) {?>
			    {
			        text: '<?=$row?>',
			        value: '<?=$row?>',
			    },
			<?php } ?>
		    ],
		    callback: function (result) {
		    	if(result != null){
		    		window.location.href = "<?=HTTP_PATH.'budget/budget_view/'?>"+result;	
		    	}
		    }
		});
    }
</script>