
		<h2 id="glyphicons-glyphs">Truck Repairs and Maintenance Report Form</h2>
		
		<?php if($this->Abas->checkPermissions("asset_management|add_truck_repairs_report",FALSE)){?>
			<a href="<?php echo HTTP_PATH.CONTROLLER.'/add/TRMRF';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Add</a> 
		<?php }?>
		<a href="<?php echo HTTP_PATH.CONTROLLER."/listview/TRMRF"; ?>" class="btn btn-dark force-pageload">Refresh</a> 

			<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.CONTROLLER.'/load/am_truck_repairs'; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
				<thead>
					<tr>
						<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
						<th data-field="company_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
						<th data-field="truck_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Plate No.</th>
						<th data-field="driver" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Driver</th>
						<th data-field="location" data-align="center" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Current Location</th>
						<th data-field="priority" data-align="center" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Priority</th>
						<th data-field="created_by" data-align="left" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>
						<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created On</th>
						<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>

						<th data-field="details" data-formatter="details" data-events="operateEvents"  data-align="center" data-align="center">Details</th>

					</tr>
				</thead>
			</table>


<script>
	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.CONTROLLER.'/view/TRMRF'; ?>/'+ row['id'] +'">View</a>'
			//'<a class="btn btn-danger btn-xs btn-block" href="#" onclick="deleteTRMRF('+row['id']+','+row['control_number']+');">Delete</a>'
		].join('');
	}

	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

	function deleteTRMRF(id,control_no){

    	bootbox.confirm({
       					size: "small",
					    title: "Delete TRMRF",
					    message: "Are you sure you want to delete this Truck Repairs and Maintenance Report Form with Control No."+control_no+"? (This cannot be undone)",
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

					    		window.location.href = "../delete/TRMRF/" + id;
					    	}
				
					    }
					});
    }

</script>