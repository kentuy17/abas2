
		<?php
		if(isset($for)){
			if($for=="Vessel"){
				$title = "Dry Dock Schedules";
				$asset_name = "Vessel Name";
				$report_form_id = "WO No.";
				$evaluation_form_id = "SRMSF No.";
				$type = $for;
			}elseif($for=="Truck"){
				$title = "Motorpool Repairs and Maintenance Schedule Logs";
				$asset_name = "Plate No.";
				$report_form_id = "TRMRF No.";
				$evaluation_form_id = "MTDE No.";
				$type = $for;
			}
		}
		?>

		<h2 id="glyphicons-glyphs"><?php echo $title;?></h2>
	
		<?php if($this->Abas->checkPermissions("asset_management|add_vessel_schedule_log",FALSE) && $type=='Vessel'){?>
			<a href="<?php echo HTTP_PATH.CONTROLLER.'/add/schedule_logs/Vessel';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Add</a>
		<?php }?>
		<?php if($this->Abas->checkPermissions("asset_management|add_truck_schedule_log",FALSE)  && $type=='Truck'){?>
			<a href="<?php echo HTTP_PATH.CONTROLLER.'/add/schedule_logs/Truck';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Add</a>
		<?php }?>
		<a href="<?php echo HTTP_PATH.CONTROLLER."/listview/schedule_logs/".$type; ?>" class="btn btn-dark force-pageload">Refresh</a> 

			<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.CONTROLLER.'/load/am_schedule_logs/'.$type; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false">
				<thead>
					<tr>
						<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
						<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
						<th data-field="company_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
						<th data-field="reference_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Project Ref. No.</th>
						<th data-field="report_form_no" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false"><?php echo $report_form_id;?></th>
						<th data-field="evaluation_form_no" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false"><?php echo $evaluation_form_id;?></th>
						<th data-field="bill_of_materials_no" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Bill of Material No.</th>
						<th data-field="asset_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false"><?php echo $asset_name;?></th>
						<th data-field="schedule_type" data-align="center" data-visible="false" data-sortable="true">Type</th>
						<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>
						<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created On</th>
						<th data-field="overall_percentage" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Overall %</th>
						<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
						<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
					</tr>
				</thead>
			</table>

<script>

	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.CONTROLLER.'/view/schedule_logs'; ?>/'+ row['id'] +'">View</a>'//,
			//'<a class="delete btn btn-danger btn-xs btn-block" onclick="deleteSched('+row['id']+ ',' +row['control_number']+');">Delete</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

    function deleteSched(id, control_number){

    	bootbox.confirm({
       					size: "small",
					    title: "Delete Schedule Log",
					    message: "Are you sure you want to delete this Schedule Log with Control No. - " + control_number +" ? (This cannot be undone)",
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
					    		window.location.href = "../../delete/schedule_logs/" + id;
					    	}
				
					    }
					});
    }

</script>