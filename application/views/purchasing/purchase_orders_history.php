<?php
if(isset($completed_po)) {
	if(!empty($completed_po)) {
		foreach($completed_po as $cpo) {
			$details	=	$this->Purchasing_model->getPurchaseOrderDetails($cpo['id']);
			$supplier	=	$this->Abas->getSupplier($cpo['supplier_id']);
			if(!empty($details)) {
				$itemstable	=	"";
				$totalcost	=	0;
				foreach($details as $d) {
					$item	=	$this->Inventory_model->getItem($d['item_id']);
					$item	=	$item[0];
					$totalcost	=	$totalcost;
					$itemstable	.=	"<tr>";
						$itemstable	.=	"<td>".$item['description']."</td>";
						$itemstable	.=	"<td>".$d['quantity']."</td>";
						$itemstable	.=	"<td>P".number_format($d['unit_price'],2)."</td>";
						$itemstable	.=	"<td>P".number_format(($d['unit_price']*$d['quantity']),2)."</td>";
					$itemstable	.=	"</tr>";
				}
			}
			echo '
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="headingRequest'.$cpo['id'].'">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#po_container" href="#bodyRequest'.$cpo['id'].'" aria-expanded="true" aria-controls="bodyRequest'.$cpo['id'].'">
								Supplier: <b>'.$supplier['name'].'</b> (P'.$totalcost.') - '.date("j F Y", strtotime($cpo['tdate'])).'
							</a>
						</h4>
					</div>
					<div id="bodyRequest'.$cpo['id'].'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRequest'.$cpo['id'].'">
						<div class="col-lg-12">
						</div>
						<div class="clearfix"><br/></div>
						<div class="table-responsive">
							<table class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Item</th>
										<th>Quantity</th>
										<th>Unit Price</th>
										<th>Total Price</th>
									</tr>
								<thead>
								<tbody>
									'.$itemstable.'
								</tbody>
								<tfoot class="text-right">
									<tr><td colspan=3>Subtotal</td><td>P'.$totalcost.'</td></tr>
									<!--tr><td colspan=3>Discount</td><td><input type="text" class="form-xs text-right" name="discount" id="discount'.$cpo['id'].'" value="" placeholder="0.00" /></td></tr>
									<tr><td colspan=3>12% VAT</td><td><input type="text" class="form-xs text-right" name="vat" id="vat'.$cpo['id'].'" value="" placeholder="'.number_format(($totalcost*.12),2).'" /></td></tr-->
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			';
		}
	}
}
?>