
		<?php
		if(isset($for)){
			if($for=="Vessel"){
				$title = "Bill Of Materials for Vessels";
				$asset_name = "Vessel Name";
				$bom_type = $for;
			}elseif($for=="Truck"){
				$title = "Bill Of Materials for Trucks";
				$asset_name = "Plate No.";
				$bom_type = $for;
			}
		}
		?>

		<h2 id="glyphicons-glyphs"><?php echo $title;?></h2>
		<?php if($this->Abas->checkPermissions("asset_management|add_vessel_bill_of_materials",FALSE) && $bom_type=='Vessel' ){?>
			<a href="<?php echo HTTP_PATH.CONTROLLER.'/add/BOM/Vessel';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Add</a>
		<?php }?>
		<?php if($this->Abas->checkPermissions("asset_management|add_truck_bill_of_materials",FALSE) && $bom_type=='Truck'){?>
			<a href="<?php echo HTTP_PATH.CONTROLLER.'/add/BOM/Truck';?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog" data-backdrop="static" data-keyboard="false">Add</a>
		<?php }?>
		<a href="<?php echo HTTP_PATH.CONTROLLER."/listview/BOM/".$bom_type; ?>" class="btn btn-dark force-pageload">Refresh</a> 

			<table data-toggle="table" id="data-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH.CONTROLLER.'/load/am_bill_of_materials/'.$bom_type; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-sort-name="created_on" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="true" data-filter-strict-search="false">
				<thead>
					<tr>
						<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
						<th data-field="control_number" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
						<th data-field="company_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
						<th data-field="evaluation_id" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Evaluation Form</th>
						<th data-field="asset_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false"><?php echo $asset_name;?></th>
						<th data-field="bom_type" data-align="center" data-visible="false" data-sortable="true">Type</th>
						<th data-field="start_date_of_repair" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Start Date of Repair</th>
						<th data-field="remarks" data-align="center" data-visible="false" data-sortable="true">Remarks</th>
						<th data-field="bom_amount" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Amount</th>
						<th data-field="created_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created By</th>
						<th data-field="created_on" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created On</th>
						<th data-field="status" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>

						<th data-field="details" data-formatter="details" data-events="operateEvents" data-align="center" data-align="center">Details</th>
					</tr>
				</thead>
			</table>

<script>

	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-xs btn-block force-pageload" href="<?php echo HTTP_PATH.CONTROLLER.'/view/BOM'; ?>/'+ row['id'] +'">View</a>'//,
			//'<a class="delete btn btn-danger btn-xs btn-block" onclick="deleteBOM('+row['id']+ ',' +row['control_number']+');">Delete</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table');
		$table.bootstrapTable();
	});

    function deleteBOM(id, control_number){

    	bootbox.confirm({
       					size: "small",
					    title: "Delete BOM",
					    message: "Are you sure you want to delete this Bill Of Materials with Control No. - " + control_number +" ? (This cannot be undone)",
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
					    		window.location.href = "../../delete/BOM/" + id;
					    	}
				
					    }
					});
    }

</script>