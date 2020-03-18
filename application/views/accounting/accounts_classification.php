	<a href="<?=HTTP_PATH.'accounting/chart_of_accounts'?>">
		<button class="btn btn-danger"><span class="glyphicon glyphicon glyphicon-arrow-left"></span> Back</button>
	</a>
	<a href="<?=HTTP_PATH.'accounting/accounts_classification_form'?>">
		<button class="btn btn-primary" data-toggle="modal" data-target="#modalDialog">
			<span class="glyphicon glyphicon glyphicon-plus"></span> Add
		</button>
	</a>
	
	<?php //$this->Mmm->debug($accounts) ?>
	<table data-toggle="table" id="accounts_classification" class="table table-bordered table-striped table-hover" data-cache="false" data-pagination="true" data-show-columns="true" data-search="true" data-page-list="[5, 10, 20, 50, 100]">
		<thead>
			<tr>
				<th data-sortable="false" data-visible="true" data-align="center">ID</th>
				<th data-sortable="false" data-visible="true" data-align="left">Classification name</th>
				<th data-sortable="false" data-visible="true" data-align="center">Type</th>
				<th data-sortable="false" data-visible="true" data-align="center">Manage</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($accounts as $ctr => $row) { ?>
			<tr>
				<td><?=$accounts[$ctr]->id?></td>
				<td><?=$accounts[$ctr]->name?></td>
				<td><?=$accounts[$ctr]->type?></td>
				<td>
					<a onclick="editPercentage(1)">
						<button class="btn btn-warning btn-xs">manage</button>
					</a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

	<script type="text/javascript">
		$(function () {
			var $table = $('#accounts_classification');
			$table.bootstrapTable();
		});

		function confirmDelete(id){
	    	bootbox.confirm(
	    	{
				size: "small",
			    title: "Remove Account",
			    message: "Are you sure you want to remove this account?",
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
			    	if(result==true){
			    		window.location.href = "<?=HTTP_PATH.'manager/del_account/'?>" + id;
			    	}
			    }
			});
	    }

	    function editPercentage(id){
			bootbox.prompt({
	    		size: "small",
			    title: "Input Percentage",
			    centerVertical: true,
			    inputType: 'email',
			    callback: function (result) {
			    	if(result != null){
			    		window.location.href = "<?=HTTP_PATH.'manager/edit_percent/'?>"+result+'/'+id;	
			    	}
			    }
			});
	    }
	</script>