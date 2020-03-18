<?php

	$delivery = $this->db->query("SELECT * FROM inventory_deliveries");
	$delivery= $delivery->result_array();
	$this->Mmm->debug($delivery);
	foreach($delivery as $ctr=>$d){
		$supplier= $this->db->query("SELECT name FROM suppliers WHERE id=".$d['id']);
		$supplier=(array)$supplier->row();
		$this->Mmm->debug($supplier);
		$delivery[$ctr]['supplier_id']= $supplier;
		}
	//$supplier= $this->db->query("SELECT * FROM suppliers");
	//$supplier = $suppliers->result_array();



?>
<table data-toggle="table" id="inventory_deliveries-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."inventory/delivery_history/json"; ?>" data-search="true" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5,10,20,50,100]">
	<thead>
		<tr>
			<th data-sortable="true" data-align="center" data-field='tdate'>Date</th>
			<th data-sortable="true" data-align="center" data-field='receipt_num'>Receipt Number</th>
			<th data-sortable="true" data-align="center" data-field='po_no'>PO Number</th>
			<th data-sortable="true" data-align="center" data-field='supplier_id'>Supplier</th>
			<th data-sortable="true" data-align="center" data-field='amount'>Amount</th>
			<th data-sortable="true" data-align="center" data-field='stat'>Stat</th>
			<th data-sortable="true" data-align="center" data-field='location'>Location</th>
			<th data-sortable="true" data-align="center" data-field='remark'>Remark</th>
			<th data-sortable="true" data-align="center" data-field='voucher_id'>Voucher</th>
			<th data-sortable="true" data-align="center" data-field='is_cleared'>IS Cleared</th>
			<th data-sortable="true" data-align="center" data-field='doc_rr'>RR</th>
			<th data-sortable="true" data-align="center" data-field='doc_dr'>DR</th>
			<th data-sortable="true" data-align="center" data-field='doc_po'>PO</th>
		</tr>
	</thead>
</table>


