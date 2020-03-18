
<?php

		$table_header = null;
		$table_data = null;
		$vessel = null;
		$supplier = null;
		$ctr = 1;

		if(isset($history_type)){

			if($history_type=="issuance"){

				$table_header = "<thead>
								<tr>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='id'>Transaction Code</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='issue_date'>Issuance Date</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='control_number'>MSIS No.</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='company'>Company</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='issued_to'>Issued To</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='vessel_id'>Issued For</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='from_location'>Location</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='amount'>Amount</th>
									<th data-filter-control='input' data-visible='false' data-sortable='true' data-align='center' data-field='remark'>Remarks</th>
									<th data-align='center'>Details</th>
								</tr>
								</thead>";

				if(isset($history_main)){
					foreach($history_main as $record){
						$vessel_name = "";
						$table_data .= "<tr>";
						$table_data .= "<td>" . $record['id'] . "</td>";
						$table_data .= "<td>" .  date('Y-m-d',strtotime($record['issue_date'])) . "</td>";
						$table_data .= "<td>" . $record['control_number'] . "</td>";

						$vessel = $this->Abas->getVessel($record['vessel_id']);
						if(!empty($vessel->name)){
							$vessel_name = $vessel->name;
							$vessel_company= $vessel->company;
						}else{
							$vessel_name="";
						}

						$company= $this->Abas->getCompany($vessel_company);
						if(!empty($company->name)){
							$company_name = $company->name;
						}else{
							$company_name ="";
						}
							
						$table_data .= "<td>" . $company_name . "</td>";	
						$table_data .= "<td>" . $record['issued_to'] . "</td>";
						$table_data .= "<td>" . $vessel_name . "</td>";
						$table_data .= "<td>" . $record['from_location'] . "</td>";

						$issued_items = $this->Inventory_model->getIssuanceDetails($record['id']);
						$amount =0;
						foreach($issued_items as $item){
							$amount = $amount + ($item['unit_price'] *  $item['qty']);
						}
						$table_data .= "<td>" . number_format($amount,2,'.',',') . "</td>";

						$table_data .= "<td>" . $record['remark'] . "</td>";
						$table_data .= "<td>
										<a href='../view_transaction_history_details/" . $history_type . "/" . $record['id'] ."' class='btn btn-info btn-xs btn-block' data-toggle='modal' data-target='#modalDialog'>View</a>
										</td>";
						$table_data .= "</tr>";
						$ctr++;
					}

				}

			}
			elseif($history_type=="delivery"){

				$table_header = "<thead>
								 <tr>
								 	
								 	<th data-filter-control='input' data-sortable='true' data-align='center' data-field='id'>Transaction Code</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='tdate'>Delivery Date</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='control_number'>RR No.</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='company'>Company</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='sales_invoice_no'>Sales Invoice No.</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='po_no'>PO No.</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='supplier_id'>Supplier</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='amount'>Amount</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='location'>Location</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-visible='false' data-field='remark'>Purpose</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-visible='false' data-field='created_on'>Created On</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-visible='false' data-field='created_by'>Created By</th>
									<th data-align='center'>Details</th>
								</tr>
				</thead>";

				if(isset($history_main)){
					foreach($history_main as $record){
						$table_data .= "<tr>";
						
						$table_data .= "<td>" . $record['id'] . "</td>";
						$table_data .= "<td>" .  date('Y-m-d',strtotime($record['tdate'])) . "</td>";
						$table_data .= "<td>" . $record['control_number'] . "</td>";

						$company= $this->Abas->getCompany($record['company_id']);
						if(!empty($company->name)){
							$company_name = $company->name;
						}else{
							$company_name ="";
						}

						$table_data .= "<td>" . $company_name . "</td>";
						$table_data .= "<td>" . $record['sales_invoice_no'] . "</td>";
						$table_data .= "<td>" . $record['po_no'] . "</td>";

							$supplier  = $this->Abas->getSupplier($record['supplier_id']);
							if(!empty($supplier['name'])){
								$supplier_name = $supplier['name'];
							}else{
								$supplier_name ="";
							}

						$table_data .= "<td>" . $supplier_name  . "</td>";
						$table_data .= "<td>" . number_format($record['amount'],2,'.',',') . "</td>";
						$table_data .= "<td>" . $record['location'] . "</td>";
						$table_data .= "<td>" . $record['remark'] . "</td>";
						$table_data .= "<td>" . date('Y-m-d',strtotime($record['created_on'])) . "</td>";
						$created_by = $this->Abas->getUser($record['created_by']);
						$table_data .= "<td>" . $created_by['full_name']. "</td>";
						$table_data .= "<td>
										<a href='../view_transaction_history_details/" . $history_type . "/" . $record['id'] ."' class='btn btn-info btn-xs btn-block' data-toggle='modal' data-target='#modalDialog'>View</a>
										</td>";
						$table_data .= "</tr>";
						
					}


				}

			}
			elseif($history_type=="transfer"){
				$table_header = "<thead>
								 <tr>
								 	
								 	<th data-filter-control='input' data-sortable='true' data-align='center' data-field='id'>Transaction Code</th>
								 	<th data-filter-control='input' data-sortable='true' data-align='center' data-field='transfer_date'>Issued Date</th>
								 	<th data-filter-control='input' data-sortable='true' data-align='center' data-field='control_number'>STR No.</th>
								 	<th data-filter-control='input' data-sortable='true' data-align='center' data-field='company_id'>Company</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='transfered_by'>Transferred By</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='from_location'>From Warehouse</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='to_location'>To Warehouse</th>
									<th data-filter-control='input' data-sortable='true' data-visible='false' data-align='center' data-field='remark'>Remarks</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='status'>Status</th>
									<th data-align='center'>Details</th>
								</tr>
				</thead>";

				if(isset($history_main)){
					foreach($history_main as $record){

						$company= $this->Abas->getCompany($record['company_id']);
						if(!empty($company->name)){
							$company_name = $company->name;
						}else{
							$company_name ="";
						}

						$table_data .= "<tr>";
						
						$table_data .= "<td>" . $record['id'] . "</td>";
					    $table_data .= "<td>" . date('Y-m-d',strtotime($record['transfer_date'])) . "</td>";
						$table_data .= "<td>" . $record['control_number'] . "</td>";
						$table_data .= "<td>" . $company_name . "</td>";
						$table_data .= "<td>" . $record['transfered_by'] . "</td>";
						$table_data .= "<td>" . $record['from_location'] . "</td>";
						$table_data .= "<td>" . $record['to_location'] . "</td>";
						$table_data .= "<td>" . $record['remark'] . "</td>";
						if($record['is_received']==1){
							$table_data .= "<td>Received</td>";
							$table_data .= "<td>
											<a href='../view_transaction_history_details/" . $history_type . "/" . $record['id'] ."' class='btn btn-info btn-xs btn-block force-pageload' data-toggle='modal' data-target='#modalDialog'>View</a>
											</td>";
						}else{

							$table_data .= "<td>For Receiving</td>";
							if($record['to_location']==$_SESSION['abas_login']['user_location']){
								$table_data .= "<td>
												<a href='../transfer_receiving/" . $record['id'] ."' class='btn btn-success btn-xs btn-block force-pageload' data-toggle='modal' data-target='#modalDialogNorm'>Receive</a>
												</td>";
							}
						}
						$table_data .= "</tr>";
						
					}
				}


			}
			elseif($history_type=="return"){
				$table_header = "<thead>
								 <tr>
								 	
								 	<th data-filter-control='input' data-sortable='true' data-align='center' data-field='id'>Transaction Code</th>
								 	<th data-filter-control='input' data-sortable='true' data-align='center' data-field='transfer_date'>Return Date</th>
								 	<th data-filter-control='input' data-sortable='true' data-align='center' data-field='control_number'>MSRS No.</th>
								 	<th data-filter-control='input' data-sortable='true' data-align='center' data-field='company_id'>Company</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='return_from'>Returned From</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='return_by'>Returned By</th>
									<th data-filter-control='input' data-sortable='true' data-align='center' data-field='return_to'>Returned to Warehouse</th>
									<th data-filter-control='input' data-sortable='true' data-visible='false' data-align='center' data-field='remark'>Remarks</th>
									<th data-align='center'>Details</th>
								</tr>
				</thead>";

				if(isset($history_main)){
					foreach($history_main as $record){

						$company= $this->Abas->getCompany($record['company_id']);
						if(!empty($company->name)){
							$company_name = $company->name;
						}else{
							$company_name ="";
						}

						$vessel= $this->Abas->getVessel($record['return_from'] );
						if(!empty($vessel->name)){
							$vessel_name = $vessel->name;
						}else{
							$vessel_name ="";
						}

						$table_data .= "<tr>";
						
						$table_data .= "<td>" . $record['id'] . "</td>";
					    $table_data .= "<td>" . date('Y-m-d',strtotime($record['return_date'])) . "</td>";
						$table_data .= "<td>" . $record['control_number'] . "</td>";
						$table_data .= "<td>" . $company_name . "</td>";
						$table_data .= "<td>" . $vessel_name . "</td>";
						$table_data .= "<td>" . $record['return_by'] . "</td>";
						$table_data .= "<td>" . $record['return_to'] . "</td>";
						$table_data .= "<td>" . $record['remark'] . "</td>";
						
						$table_data .= "<td>
										<a href='../view_transaction_history_details/" . $history_type . "/" . $record['id'] ."' class='btn btn-info btn-xs btn-block force-pageload' data-toggle='modal' data-target='#modalDialog'>View</a>
										</td>";
						
						$table_data .= "</tr>";
						
					}
				}

				
			}

		}

	?>

<h2><?php echo ucwords( $history_type . ' Transaction History');?></h2>


		<a id="filterDate" href="<?php echo '../filter_transaction_history/' . $history_type; ?>" class="glyphicon-th-user btn btn-success pull-center exclude-pageload" data-toggle="modal" data-target="#modalDialog" title="Filter">Filter</a>
		<a id="reset" href="<?php echo HTTP_PATH."inventory/transaction_history/" . $history_type; ?>" class="glyphicon-th-user btn btn-dark pull-center exclude-pageload" title="Refresh">Refresh</a>
		<a id="print" href="<?php echo '../print_transaction_history?type=' . $history_type . '&filter=' . $filter .'&date_from=' . $date_from . '&date_to=' . $date_to; ?>" class="glyphicon-th-user btn btn-info pull-center exclude-pageload" title="Print" target="_blank">Print</a>
		<table data-toggle="table" id="transaction_history" data-show-columns="true" data-search="true" data-cache="false" data-pagination="true" data-filter-control="true" data-filter-strict-search="false" data-page-list="[5,10,20,50,100,1000,10000]"  data-sort-name="id" data-sort-order="desc" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">

			<?php echo $table_header; ?>

			<tbody>
				<?php echo $table_data; ?>
			</tbody>

		</table>

 
	

<script type="text/javascript">
	
$(function () {
    $('#transaction_history').bootstrapTable({
        showExport: true,
        exportOptions: {
            fileName: 'custom_file_name'
        }
    });
});

</script>
									
							
				   