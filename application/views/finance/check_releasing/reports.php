<h2>Released Checks</h2>

<a href="<?php echo HTTP_PATH.'finance/check_releasing/report'; ?>" data-toggle="modal" data-target="#modalDialogNorm"> <button type="button" class="btn btn-success exclude-pageload">Filter</button></a>
<div>
	<table data-toggle="table" id="data-table" data-show-columns="true" data-search="true" data-cache="false" data-pagination="true" data-page-list="[5,10,20,50,100,1000,10000]"  data-show-footer="false" data-export-data-type="all" data-search='false' data-export-data-type="all" data-show-export="true" data-export-types="['excel']">

	<thead>
		<tr>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Transaction Code No.</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Control No.</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Company</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Payee Type</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Payment To</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Voucher Date</th>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Type</th>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">APV / RFP</th>
				<th  data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Remark</th>

				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Bank</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Check No.</th>
				<th data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Amount</th>

				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Released On</th>

				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Created By</th>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Created On</th>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Verified By</th>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Verified On</th>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Approved By</th>
				<th data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Approved On</th>
			
				<th data-align="left" data-visible="false" data-sortable="true" data-filter-control="select">Status</th>
				<th data-align="center" data-align="center">Details</th>

		</tr>
	</thead>

	<tbody>
		<?php
			if(isset($released_checks)){
				foreach ($released_checks as $row) {
					echo "<tr>";
						echo "<td>".$row->id."</td>";
						echo "<td>".$row->voucher_number."</td>";
						echo "<td>".$row->company."</td>";
						echo "<td>".$row->payee_type."</td>";


						if($row->payee_type=='Employee'){
							$employee	=	$this->Abas->getEmployee($row->payee);
							$payee_name	=	$employee['full_name'];
						}else{
							$supplier	=	$this->Abas->getSupplier($row->payee);
							$payee_name	=	$supplier['name'];
						}

						echo "<td>".$payee_name."</td>";
						echo "<td>".$row->voucher_date."</td>";
						echo "<td>".$row->type."</td>";
						echo "<td>".$row->apv_no."</td>";
						echo "<td>".$row->remark."</td>";
						echo "<td>".$row->bank."</td>";
						echo "<td>".$row->check_num."</td>";
						echo "<td>".number_format($row->amount,2,'.',',')."</td>";
						echo "<td>".date('Y-m-d',strtotime($row->released_date))."</td>";
						echo "<td>".$row->created_by."</td>";
						echo "<td>".date('Y-m-d H:m:s',strtotime($row->created_on))."</td>";
						echo "<td>".$row->verified_by."</td>";
						echo "<td>".date('Y-m-d H:m:s',strtotime($row->verfied_on))."</td>";
						echo "<td>".$row->approved_by."</td>";
						echo "<td>".date('Y-m-d H:m:s',strtotime($row->approved_on))."</td>";
						echo "<td>".$row->status."</td>";
						echo "<td><a class='btn btn-info btn-xs btn-block' data-toggle='modal' data-target='#modalDialog' href='".HTTP_PATH."finance/check_releasing/view/".$row->id."'>View</a></td>";
					echo "</tr>";
				}
			}
		?>
	</tbody>
</table>
</div>