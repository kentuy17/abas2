
		<?php
		if(isset($for)){
			if($for=="Vessel"){
				$title = "Evaluation Items for Vessels";
				$eval_type = $for;
			}elseif($for=="Truck"){
				$title = "Evaluation Items for Trucks";
				$eval_type = $for;
			}
		}
		?>

		<h2 id="glyphicons-glyphs"><?php echo $title;?></h2>
		
		<?php if($this->Abas->checkPermissions("asset_management|add_vessel_evaluation_form",FALSE) || $this->Abas->checkPermissions("asset_management|add_truck_evaluation_form",FALSE)){?>
			<a href="<?php echo HTTP_PATH.CONTROLLER.'/add/evaluation_items/'.$eval_type;?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Add</a>
		<?php }?>

		<a href="<?php echo HTTP_PATH.CONTROLLER."/listview/evaluation_items/".$eval_type; ?>" class="btn btn-dark force-pageload">Refresh</a> 

			<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.CONTROLLER.'/load/am_evaluation_items/'.$eval_type; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="type" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
				<thead>
					<tr>
						<th data-field="item_index" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Index</th>
						<th data-field="item_set" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Set</th>
						<th data-field="item_sub_set" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Sub-set</th>
						<th data-field="item_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Item/Particulars</th>
						<th data-field="type" data-align="center" data-visible="true" data-sortable="true">Type</th>
						<th data-field="ask_spec" data-align="center" data-sortable="false" data-filter-control="select" data-filter-strict-search="false">Ask Specifications?</th>
						<th data-field="enabled" data-align="center" data-visible="true" data-sortable="false" data-filter-control="select" data-filter-strict-search="false">Enabled?</th>
						
						<?php if($this->Abas->checkPermissions("asset_management|add_vessel_evaluation_form",FALSE) || $this->Abas->checkPermissions("asset_management|add_truck_evaluation_form",FALSE)){?>
							<th data-field="manage" data-formatter="manage" data-events="operateEvents" data-align="center" data-align="center">Manage</th>
						<?php }?>
						
					</tr>
				</thead>
			</table>

<script>
	function manage(value, row, index) {

		return [
			'<a class="btn btn-warning btn-xs btn-block exclude-pageload" href="<?php echo HTTP_PATH.CONTROLLER.'/edit/evaluation_items'; ?>/'+ row['id'] +'" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">Edit</a>',
			'<a class="delete btn btn-danger btn-xs btn-block" onclick="deleteEI('+row['id']+ ',&#39' +row['item_index']+'.'+row['item_set']+'.'+row['item_sub_set'] +'&#39);">Delete</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

    function deleteEI(id, indexing){

    	bootbox.confirm({
       					size: "small",
					    title: "Delete Evaluation Item",
					    message: "Are you sure you want to delete this Evaluation Item - " + indexing +" ? (This cannot be undone)",
					    buttons: {
					        confirm: {
					            label: '<i class="fa fa-check"></i> Yes'
					        },
					        cancel: {
					            label: '<i class="fa fa-times"></i> No'
					        }
					    },
					    callback: function (result) {
					    	if(result==true){

					    		$('body').addClass('is-loading'); 
								$('#modalDialog').modal('toggle'); 

					    		window.location.href = "../../delete/evaluation_items/" + id;
					    	}
				
					    }
					});
    }

</script>