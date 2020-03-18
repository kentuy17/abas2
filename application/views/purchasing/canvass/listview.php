<?php
if(isset($filter)) {
	if($filter=="approved"){
		$listview = HTTP_PATH.'purchasing/canvass/load/approved';
		$heading_titles = "Approved";
	}elseif($filter=="unapproved"){
		$listview = HTTP_PATH.'purchasing/canvass/load/unapproved';
		$heading_titles = "Unapproved";
	}
}
?>
<div class="panel panel-danger">
	<div class="panel-heading"><?php echo $heading_titles;?> Canvass</div>
	<div class="panel-body">
		<table data-toggle="table" id="canvass-table" class="table table-bordered table-striped table-hover" data-url="<?php echo $listview;?>" data-cache="false" data-side-pagination="server" data-sort-name="id" data-sort-order="desc" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
			<thead>
				<tr>
					<th data-field="id" data-align="left" data-sortable="false" data-visible="false">Transaction Code No.</th>
					<th data-field="control_number" data-align="center" data-sortable="true"data-visible="true">Requisition No.</th>
					<th data-field="company_name" data-align="left" data-sortable="false"data-visible="true">Company</th>
					
					<th data-field="vessel_name" data-align="left" data-sortable="false"data-visible="true">Vessel/Office</th>
					<th data-field="department_name" data-align="left" data-sortable="false"data-visible="true">Department</th>
					<th data-field="canvassed_on" data-align="left" data-sortable="false"data-visible="true">Canvassed On</th>
					<th data-field="canvassed_by" data-align="left" data-sortable="false"data-visible="true">Canvassed By</th>
					<?php if($filter!="unapproved"){ ?>
						<th data-field="approved_on" data-align="left" data-visible="true" data-sortable="false">Approved On</th>
						<th data-field="approved_by" data-align="left" data-visible="true" data-sortable="false">Approved By</th>
					<?php } ?>
					<th data-field="status" data-align="left" data-sortable="false" data-visible="true">Status</th>
					<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
				</tr>
			</thead>
		</table>
	</div>
	<script type="text/javascript">
	
		function operateFormatter(value, row, index) {
			return [
			<?php if($filter=='approved'){ ?>
				'<a class="btn btn-primary btn-xs btn-block" href="<?php echo HTTP_PATH.'purchasing/canvass/print/'; ?>'+row['request_id']+'" target="_blank">Canvass Report</a> ',
			
			<?php }?>
				'<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.'purchasing/requisition/view/'; ?>'+row['request_id']+'">View Request</a> ',
			].join('');
		}
		$(function () {
			var $table = $('#canvass-table');
			$table.bootstrapTable();
		});
		function currencyFormatter(value, row, index) {
			return "P"+value;
		}
	</script>
</div>