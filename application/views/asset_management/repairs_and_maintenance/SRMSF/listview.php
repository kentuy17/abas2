
		<h2 id="glyphicons-glyphs">Ship Repairs and Maintenance Survey Forms</h2>
		
		<?php if($this->Abas->checkPermissions("asset_management|add_vessel_evaluation_form",FALSE)){?>
			<a href="<?php echo HTTP_PATH.CONTROLLER.'/add/SRMSF';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Add</a>
		<?php }?>
		<a href="<?php echo HTTP_PATH.CONTROLLER."/listview/SRMSF"; ?>" class="btn btn-dark force-pageload">Refresh</a> 

			<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.CONTROLLER.'/load/am_vessel_evaluation'; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
				<thead>
					<tr>
						<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
						<th data-field="WO_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">WO No.</th>
						<th data-field="company_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
						<th data-field="vessel_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Vessel</th>
						<th data-field="dry_docking_date" data-align="center" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Dry-Docking Date</th>
						<th data-field="dry_docking_location" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Dry-Docking Location</th>
						<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>
						<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created On</th>
						<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>

						<th data-field="details" data-formatter="details" data-events="operateEvents"  data-align="center" data-align="center">Details</th>

					</tr>
				</thead>
			</table>


<script>

	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.CONTROLLER.'/view/SRMSF'; ?>/'+ row['id'] +'">View</a>'//,
			//'<a class="btn btn-danger btn-xs btn-block" href="#" onclick="deleteSRMSF('+row['id']+','+row['control_number']+');">Delete</a>'
		].join('');
	}

	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

	function deleteSRMSF(id,control_no){

    	bootbox.confirm({
       					size: "small",
					    title: "Delete SRMSF",
					    message: "Are you sure you want to delete this Ship Repair and Maintenanance Survey Form with Control No."+control_no+"? (This cannot be undone)",
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

					    		window.location.href = "../delete/SRMSF/" + id;
					    	}
				
					    }
					});
    }

</script>