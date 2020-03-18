<?php
if(isset($filter)) {
	if($filter=="approved"){
		$listview = HTTP_PATH.'purchasing/job_order/load/approved';
		$heading_titles = "Approved";
	}elseif($filter=="unapproved"){
		$listview = HTTP_PATH.'purchasing/job_order/load/unapproved';
		$heading_titles = "Unapproved";
	}elseif($filter=="all"){
		$listview = HTTP_PATH.'purchasing/job_order/load/all';
		$heading_titles = "";
	}
}
?>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo $heading_titles;?> Job Orders</div>
	<div class="panel-body">
		<table data-toggle="table" id="joborder-table" class="table table-bordered table-striped table-hover" data-url="<?php echo $listview;?>" data-cache="false" data-side-pagination="server" data-sort-name="id" data-sort-order="desc" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
			<thead>
				<tr>
					<th data-field="request_id" data-align="left" data-sortable="false"data-visible="false">Requisition </br>Transaction Code</th>
					<th data-field="id" data-align="left" data-sortable="false" data-visible="true">JO <br/>Transaction<br/>Code</th>
					<th data-field="control_number" data-align="center" data-sortable="true"data-visible="true">JO <br/>Control No.</th>
					<th data-field="company_name" data-align="left" data-sortable="false"data-visible="true">Company</th>
					<th data-field="tdate" data-align="center" data-sortable="true"data-visible="true">Date</th>
					<th data-field="supplier_name" data-align="left" data-sortable="false"data-visible="true">Supplier</th>
					<th data-field="vessel_name" data-align="left" data-sortable="false"data-visible="true">Vessel/Office</th>
					<th data-field="department_name" data-align="left" data-sortable="false"data-visible="false">Department</th>
					<th data-field="amount" data-align="left" data-formatter="currencyFormatter" data-sortable="false" data-visible="true">Amount</th>
					<th data-field="status" data-align="left" data-sortable="false" data-visible="true">Status</th>
					<th data-field="service_status" data-align="left" data-sortable="false" data-visible="true">Service </br>Status</th>
					<?php if($filter!="unapproved"){ ?>
					<th data-field="approver_name" data-align="left" data-visible="false" data-sortable="false">Approved By</th>
					<th data-field="approved_on" data-align="left" data-visible="false" data-sortable="false">Approved On</th>
					<?php } ?>
					<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-align="center" data-align="center">Manage</th>
				</tr>
			</thead>
		</table>
	</div>
	<script type="text/javascript">
	
		function operateFormatter(value, row, index) {
			return [
				'<a class="btn btn-primary btn-xs btn-block" href="<?php echo HTTP_PATH.'purchasing/job_order/view/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">View</a> ',
				'<a class="btn btn-info btn-xs btn-block" href="<?php echo HTTP_PATH.'purchasing/requisition/view/'; ?>'+row['request_id']+'">Request</a> ',
			].join('');
		}
		$(function () {
			var $table = $('#joborder-table');
			$table.bootstrapTable();
		});
		function currencyFormatter(value, row, index) {
			return "P"+value;
		}
	</script>
</div>
<script>
	function confirmCancelJo(jo_id) {
		toastr.clear();
		toastr['warning']('This will cancel this JO and marks the request status to "Cancelled" <a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/job_order/cancel/'+jo_id+'">Continue</a>', "Are you sure?");
	}
</script>