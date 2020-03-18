<div class="panel panel-primary" >
	<div class="panel-heading" style="min-height">
	   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	   <?php
	   		if($history_type=="delivery"){
	   			echo "Recieving Report Details (Transaction Code:" . $transaction_id . ")";
	   		}
	   		elseif($history_type=="issuance"){
	   			echo "Materials & Supplies Issuance Slip Details (Transaction Code:" . $transaction_id . ")";
	   		}
	   		elseif($history_type=="transfer"){
	   			echo "Stock Transfer Receipt Details (Transaction Code:" . $transaction_id .")";
	   		}
	   		elseif($history_type=="return"){
	   			echo "Materials & Supplies Return Slip Details (Transaction Code:" . $transaction_id .")";
	   		}
	   ?>
		
	</div>
</div>
		<div class="panel-body">
			<div class="col-xs-12 col-sm-12 table-responsive">
			<table data-toggle="table" id="history_details" class="table table-bordered table-striped table-hover" data-pagination="true" data-show-columns="true" data-page-list="[5,10,20,50,100]" data-search="true">

				<thead>
					<tr>
						<th data-field="" data-align="left" data-sortable="false">#</th>
						<th data-field="item_code" data-align="left" data-sortable="false">Item Code</th>
						<th data-field="description" data-align="left" data-sortable="false">Item Name</th>
						<th data-field="particular" data-align="left" data-sortable="false">Particular</th>
						<?php
							if($history_type=="return"){
								echo '<th data-field="quantity" data-align="left" data-sortable="false">Quantity Returned</th>';
								echo '<th data-field="quantity2" data-align="left" data-sortable="false">From</th>';
								echo '<th data-field="quantity3" data-align="left" data-sortable="false">To</th>';
							}else{
								echo '<th data-field="quantity" data-align="left" data-sortable="false">Quantity</th>';
							}
						?>
						<th data-field="unit" data-align="left" data-sortable="false">Unit</th>
						<th data-field="unit_price" data-align="right" data-sortable="false">Unit Price</th>
						<th data-field="" data-align="right" data-sortable="false">Amount</th>
					</tr>
				</thead>

				<tbody>
					
					<?php

						$ctr=1;
						$amount=0;
						$grand_total=0;

			
							foreach($history_details as $record){
								echo "<tr>";
								echo "<td>" . $ctr . "</td>";

								$qty =($history_type=="delivery")?$record['quantity']:$record['qty'];

								$item = $this->Inventory_model->getItems($record['item_id']);
								$unit_price = ($record['unit_price']!=''||$record['unit_price']!=0)?$record['unit_price']:$item[0]['unit_price'];
								$amount = $unit_price*$qty;

								echo "<td>" . $item[0]['item_code'] . "</td>";
								echo "<td>" . $item[0]['description'] . "</td>";
								echo "<td>" . $item[0]['brand']." ".$item[0]['particular'] . "</td>";
								echo "<td>" . $qty . "</td>";

								if($history_type=="return"){
									echo "<td>" . $record['old_qty'] . "</td>";
									echo "<td>" . ($qty+$record['old_qty']) . "</td>";
								}

								echo "<td>" . $record['unit'] . "</td>";
								echo "<td>" . number_format($unit_price,2,'.',',') . "</td>";
								echo "<td>" . number_format($amount,2,'.',',') . "</td>";
								echo "</tr>";

								$ctr++;
								$grand_total = $grand_total + $amount;
							}

							echo "<tr>";
							if($history_type=="return"){
								echo "<td colspan='8'></td>";
							}else{
								echo "<td colspan='6'></td>";
							}
							echo "<td><b>Total Amount:</b></td>";
							echo "<td><b>" . number_format($grand_total,2,'.',',') . "</b></td>";
							echo "</tr>";
						
							if($history_type=="transfer"){
								echo "<tr><td><b>Receiving Remarks:</b></td><td colspan='7'>". $history_main[0]['receiving_remark']."</td></tr>";
							}
							if($history_type=="return"){
								echo "<tr><td><b>Remarks:</b></td><td colspan='9'>". $history_main[0]['remark']."</td></tr>";
							}

					?>


					
				</tbody>

			</table>

			<div class='col-xs-12 col-sm-12'>
			<?php

				if($history_type=="delivery"){
					echo "<span class='pull-right'><a id='print_rr' href='". HTTP_PATH. "inventory/print_rr/". $transaction_id . "/1' class='glyphicon-th-user btn btn-success pull-center exclude-pageload' target='_blank'>Reprint Receiving Report</a></span>";
					//echo "<span class='pull-right'><a id='print_qr_codes' href='". HTTP_PATH. "inventory/print_rr_qr_code/". $transaction_id. "' class='glyphicon-th-user btn btn-warning pull-center exclude-pageload' target='_blank'>Print QR Codes</a></span>";
				}
				elseif($history_type=="issuance"){
					echo "<span class='pull-right'><a id='print_ir' href='". HTTP_PATH. "inventory/print_is/". $transaction_id . "/1' class='glyphicon-th-user btn btn-success pull-center exclude-pageload' target='_blank'>Reprint Material and Supplies Issuance Slip</a></span>";
					/*if(isset($gatepass)){
						echo "<span class='pull-right'><a id='print_ir' href='". HTTP_PATH. "inventory/print_gatepass/". $transaction_id . "' class='glyphicon-th-user btn btn-info pull-center exclude-pageload' target='_blank'>Print Gate Pass</a></span>";
					}*/
				}elseif($history_type=="transfer"){
					echo "<span class='pull-right'><a id='print_tr' href='". HTTP_PATH. "inventory/print_tr/". $transaction_id . "/1' class='glyphicon-th-user btn btn-success pull-center exclude-pageload' target='_blank'>Reprint Stock Transfer Receipt</a></span>";
				}elseif($history_type=="return"){
					echo "<span class='pull-right'><a id='print_tr' href='". HTTP_PATH. "inventory/print_rt/". $transaction_id . "/1' class='glyphicon-th-user btn btn-success pull-center exclude-pageload' target='_blank'>Reprint Material and Supplies Return Slip</a></span>";
				}
			?>
			</div>
		
		</div>
	</div>

