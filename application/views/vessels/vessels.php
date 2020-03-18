
	<h2>Vessel Profiles</h2>
	<a href="<?php echo HTTP_PATH.'vessels'; ?>" class="btn btn-dark">Refresh</a>
	<table data-toggle="table" id="vessel-table" class="table table-striped table-hover" data-url="<?php echo HTTP_PATH."vessels/view_all_vessels"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 500,1000]" data-search="true" data-filter-control="true" data-filter-strict-search="false">
		<thead>
			<tr>
				<th data-field="company_name" data-align= "center" data-sortable="false" data-visible="true" data-filter-control='select'>Company</th>
				<th data-field="photo_path" data-align= "center" data-sortable="false" data-visible="false">Photo Path</th>
				<th data-field="name" data-align="center" data-sortable="false" data-visible="true" data-filter-control='select'>Name</th>
				<th data-field="ex_name" data-align="center" data-sortable="false" data-visible="false">Ex Name</th>
				<th data-field= "price_sold" data-align= "center" data-sortable="false" data-visible="false">Price Sold</th>
				<th data-field="price_paid" data-align="center" data-sortable="false" data-visible="false">Price Paid</th>
				<th data-field="length_loa" data-align="center" data-sortable="false" data-visible="false">Length Loa</th>
				<th data-field="length_lr" data-align="center" data-sortable="false" data-visible="false">Length LR</th>
				<th data-field="length_lbp" data-align="center" data-sortable="false" data-visible="false">Length LBP</th>
				<th data-field="breadth" data-align="center" data-sortable="false" data-visible="false">Breadth</th>
				<th data-field="depth" data-align="center" data-sortable="false" data-visible="false">Depth</th>
				<th data-field="draft" data-align="center" data-sortable="false" data-visible="false">Draft</th>
				<th data-field="year_built" data-align="center" data-sortable="false" data-visible="true" data-filter-control='input'>Year Built</th>
				<th data-field="builder" data-align="center" data-sortable="false" data-visible="true" data-filter-control='input'>Builder</th>
				<th data-field="place_built" data-align="center" data-sortable="false" data-visible="false">Place Built</th>
				<th data-field="jap_dwt" data-align="center" data-sortable="false" data-visible="false">Jap DWT</th>
				<th data-field="bale_capacity" data-align="center" data-sortable="false" data-visible="false">Bale Capacity</th>
				<th data-field="grain_capacity" data-align="center" data-sortable="false" data-visible="false">Grain Capacity</th>
				<th data-field="hatch_size" data-align="center" data-sortable="false" data-visible="false">Hatch Size</th>
				<th data-field="hatch_type" data-align="center" data-sortable="false" data-visible="false">Hatch Type</th>
				<th data-field="year_last_drydocked" data-align="center" data-sortable="false" data-visible="true" data-filter-control='input'>Year Last Drydocked</th>
				<th data-field="place_last_drydocked" data-align="center" data-sortable="false" data-visible="false">Place Last Drydocked</th>
				<th data-field="phil_dwt" data-align="center" data-sortable="false" data-visible="false">Phil DWT</th>
				<th data-field="gross_tonnage" data-align="center" data-sortable="false" data-visible="false">Gross Tonnage</th>
				<th data-field="net_tonnage" data-align="center" data-sortable="false" data-visible="false">Net Tonnage</th>
				<th data-field="main_engine" data-align="center" data-sortable="false" data-visible="false">Main Engine</th>
				<th data-field="main_engine_rating" data-align="center" data-sortable="false" data-visible="false">Main Engine Rating</th>
				<th data-field="main_engine_actual_rating" data-align="center" data-sortable="false" data-visible="false">Main Engine Actual Rating</th>
				<th data-field="model_serial_no" data-align="center" data-sortable="false" data-visible="false">Model Serial Number</th>
				<th data-field="estimated_fuel_consumption" data-align="center" data-sortable="false" data-visible="false">Estimated Fuel Consumption</th>
				<th data-field="bow_thrusters" data-align="center" data-sortable="false" data-visible="false">Bow Thrusters</th>
				<th data-field="propeller" data-align="center" data-sortable="false" data-visible="false">Propeller</th>
				<th data-field="call_sign" data-align="center" data-sortable="false" data-visible="false">Callsign</th>
				<th data-field="imo_no" data-align="center" data-sortable="false" data-visible="false">IMO No</th>
				<th data-field="monthly_amortization_no_of_months" data-align="center" data-sortable="false" data-visible="false">Monthly Amortization No of Months</th>
				<th data-field="tc_proj_mo_income" data-align="center" data-sortable="false" data-visible="false">TC Proj Monthly Income</th>
				<th data-field="hm_agreed_value" data-align="center" data-sortable="false" data-visible="false">HM Agreed Value</th>
				<th data-field="maiden_voyage" data-align="center" data-sortable="false" data-visible="false">Maiden Voyage</th>
				<th data-field="replacement_cost_new" data-align="center" data-sortable="false" data-visible="false">Replacement Cost New</th>
				<th data-field="sound_value" data-align="center" data-sortable="false" data-visible="false">Sound Value</th>
				<th data-field="market_value" data-align="center" data-sortable="false" data-visible="false">Market Value</th>
				<th data-field="status" data-align="center" data-sortable="false" data-filter-control='select'>Status</th>
				<th data-field="created_by" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>Created By</th>
				<th data-field="created" data-align="center" data-sortable="false" data-visible="false">Created On</th>
				<th data-field="modified_by" data-align="center" data-sortable="false" data-visible="false" data-filter-control='input'>Modified By</th>
				<th data-field="modified" data-align="center" data-sortable="false" data-visible="false">Modified On</th>

				<th data-field="bank_account_num" data-align="center" data-sortable="false" data-visible="false">Bank Account Number</th>
				<th data-field="bank_account_name" data-align="center" data-sortable="false" data-visible="false">Bank Account Name</th>
				<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
			</tr>
		</thead>
	</table>

<script>
function operateFormatter(value, row, index) {
	return [
	//'<a class="btn btn-xs btn-warning" href="<?php //echo HTTP_PATH.'mastertables/vessels/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit">',
	//'<i class="glyphicon glyphicon-edit"></i> Edit'
	'<a class="btn btn-xs btn-block btn-info" href="<?php echo HTTP_PATH.'vessels/profile/view/';?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog">View',
	'</a> ',
	].join('');

}
$(function () {
	var $table = $('#vessels-table');
	$table.bootstrapTable();
});
</script>
