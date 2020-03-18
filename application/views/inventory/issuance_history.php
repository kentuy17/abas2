<?php
	$issuance=$this->db->query("SELECT * from inventory_issuance");
	$issuance=$issuance->result_array();
	$this->Mmm->debug($issuance);
?>
<table data-toggle="table" id="inventory_issuance-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."inventory/issuance_history/json"; ?>" data-search="true" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5,10,20,50,100]">
	<thead>
	<tr>
	<th data-sortable="true" data-align="center" data-field='issue_date'>Issuance Date</th>
	<th data-sortable="true" data-align="center" data-field='request_no'>Request No</th>
	<th data-sortable="true" data-align="center" data-field='issuance_no'>Issuance No</th>
	<th data-sortable="true" data-align="center" data-field='issued_to'>Issued To</th>
	<th data-sortable="true" data-align="center" data-field='Issued_for'>Issued_for</th>
	<th data-sortable="true" data-align="center" data-field='from_location'>Location</th>
	<th data-sortable="true" data-align="center" data-field='stat'>Stat</th>
	<th data-sortable="true" data-align="center" data-field='remark'>Remark</th>
	</tr>
	</thead>
</table>

