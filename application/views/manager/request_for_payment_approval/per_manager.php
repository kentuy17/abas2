<!--div class="form-group row">
	<div class="col-md-3 col-sm-3">
		<select class="form-control">
			<option>Choose option</option>
			<option>Option one</option>
			<option>Option two</option>
			<option>Option three</option>
			<option>Option four</option>
		</select>
	</div>
	<input type="submit" value="Filter" class="btn btn-primary">
</div-->
<h2>Request for Payments History</h2>
<div id="toolbar">
	<form id="form_search" name="form_search" method="post" action="<?=HTTP_PATH.'manager/rfp_view'?>" class="form-inline">
		<div class="form-group">
			<div class="input-group">
      			<select class="form-control" name="filter">
					<option value="null">Choose option</option>
					<option value="approved_by_me">Approved by me</option>
					<option value="verified_by_me">Verified by me</option>
				</select>
  				<span class="input-group-btn">
     				<!--button class="btn btn-primary" type="button">Filter</button-->
     				<input type="submit" class="btn btn-primary" value="Filter">
  				</span>
		   </div>
		</div>
	</form>
</div>

<table data-toggle="table" id="data-table-rfp" data-toolbar="#toolbar" class="table table-bordered table-striped table-hover" data-cache="false" data-pagination="true" data-show-columns="true" data-sort-name="id" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-filter-control="false" data-filter-strict-search="false" data-card-view="true">
	<thead>
		<tr>
			<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
			<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
			<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
			<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Payee Type</th>
			<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Payment To</th>
			<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Purpose</th>
			<th data-align="center" data-visible="True" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Prepared By</th>
			<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created On</th>
			<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Verified By</th>
			<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Verified On</th>
			<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Approved By</th>
			<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Approved On</th>
			<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Amount</th>
			<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Status</th>
			<th data-align="center">Details</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($rfp as $ctr => $row) { ?>
			<tr>
				<td><?=$row['transaction_code']?></td>
				<td><?=$row['control_number']?></td>
				<td><?=$row['company']?></td>
				<td><?=$row['payee_type']?></td>
				<td><?=$row['payment_to']?></td>
				<td><?=$row['purpose']?></td>
				<td><?=$row['prepared_by']?></td>
				<td><?=$row['created_on']?></td>
				<td><?=$row['verified_by']?></td>
				<td><?=$row['verified_on']?></td>
				<td><?=$row['approved_by']?></td>
				<td><?=$row['approved_on']?></td>
				<td><?=$row['amount']?></td>
				<td><b><?=$row['status']?></b></td>
				<td><a class="btn btn-info btn-sm force-pageload" data-toggle="modal" data-target="#modalDialog" href="<?=HTTP_PATH.'manager/request_for_payment/view/'.$row['transaction_code']?>">View</a></td>
			</tr>
		<?php } ?>
		
	</tbody>
</table>

<script type="text/javascript">
	function details(value, row, index) {
		return [
			'<a class="btn btn-info btn-sm force-pageload" data-toggle="modal" data-target="#modalDialog" href="<?php echo HTTP_PATH.'manager/request_for_payment/view'; ?>/'+ row['id'] +'">View</a>'
		].join('');
	}
	$(function () {
		var $table = $('#data-table-rfp');
		$table.bootstrapTable();
	});

</script>

