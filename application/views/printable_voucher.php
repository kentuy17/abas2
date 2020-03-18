<?php
	$this->Mmm->debug($delivery_summary);
	$supplier = $this->Abas->getSupplier($delivery_summary[0]['supplier_id']);
	$tin = explode('-',$supplier['tin']);
	$payto = $delivery_summary[0]['supplier_id'];
 	$company = $this->Accounting_model->getPoOwner($delivery_summary[0]['po_no']);
?>
<div id="voucherPrint" >
	<table style="width:600; border-bottom:#CCCCCC thin solid" >
		<tr>
			<td colspan="3" id="title" align="center"><?php echo strtoupper($company[0]['name']) ?></td>
		</tr>
		<tr>
			<td colspan="3" align="center"><?php echo $company[0]['address'] ?></td>
		</tr>
		<tr>
			<td style="font-size: 8px"align="center" align="center" colspan="3">Telephone: <?php echo $company[0]['telephone_no'] ?>  &nbsp; Fax: <?php echo $company[0]['fax_no'] ?></td>
		</tr>
		<tr>
			<td align="right" colspan="3" >&nbsp;</td>
		</tr>
		<tr>
			<td align="center" id="title" colspan="3"><strong><?php echo strtoupper($voucher[0]['type']) ?></strong></td>
		</tr>
		<tr>
			<td align="right" colspan="3" >&nbsp;</td>
		</tr>
		<tr>
			<td align="right" colspan="3" ></td>
		</tr>
		<tr id="sub-title" style="font-size: 10">
			<td align="left"><strong>Pay To: <?php echo $supplier['name'] ?></strong></td>
			<td align="left"></td>
			<td align="right"><strong style="color:#FF0000">Voucher No: <?php echo strtoupper($voucher[0]['voucher_number']) ?></strong></td>

		</tr>
		<tr id="sub-title" style="font-size: 10">
			<td align="left"><strong>Check Number: <?php echo $voucher[0]['check_num']  ?></strong></td>
			<td align="left"></td>
			<td align="right"><strong>Date: <?php echo date('F j, Y')  ?></strong></td>

		</tr>
		<tr id="sub-title" style="font-size:10px" >
			<td align="left"><strong>Invoice No.: <?php echo $delivery_summary[0]['receipt_num'] ?></strong></td>
			<td align="left"></td>
			<td align="right"><strong>Purchase Order #: <?php echo $delivery_summary[0]['po_no'] ?></strong></td>
		</tr>
		<br>
	</table>
	<br>
<br>
<br>
	<div style="margin-top:0px">
		<table id="datatable-responsive" style="margin-top:30px"  cellspacing="0" width="100%">
			<thead id="sub-title">

				<tr bgcolor="#F4F4F4" style="font-size: 11 ">

					<th width="80%" style="border-right:#CCCCCC thin solid" >Explanation of Payment</th>
					<th width="20%">Amount</th>

				</tr>
			</thead>


			<tbody>
				<tr>
					<td widht= "100%" bgcolor="#F4F4F4" style="border-right:#CCCCCC thin solid; border-top:#CCCCCC thin solid; border-bottom:#CCCCCC thin solid" colspan="2">
						<div style="margin-top:30px; margin-bottom:50px">
							<table width="95%" cellpadding="10" cellspacing="10">
								<?php
									$gtotal = 0;
									$wtax = 0;
									$vat = 0;
									foreach($delivery_detail as $d){

										//get item info
										$item = $this->Inventory_model->getItem($d['item_id']);

										$line_total = $d['quantity'] * $d['unit_price'];
										$gtotal = $gtotal + $line_total;

									?>
									<tr bgcolor="#F4F4F4"  style="font-size: 8px">
										<td width="10%"><?php echo $item[0]['item_code'] ?></td>
										<td width="30%" align="left"><?php echo $item[0]['description'] ?></td>
										<td width="10%" align="right"><?php echo $d['quantity'] ?>&nbsp;&nbsp;</td>
										<td width="5%" align="left"><?php echo $d['unit'] ?></td>
										<td width="15%" align="right">@ <?php echo $d['unit_price'] ?></td>
										<td width="20%" align="right"><?php echo number_format($line_total,2); ?></td>
									</tr>

									<?php
									}
								?>
							</table>
						</div>
					</td>
				</tr>
				<?php
					//compute tax

					if($voucher[0]['vtax']!=''){
						//compute vat
						$vat = $this->Accounting_model->computeVat($voucher[0]['vtax'],$gtotal);
					}

					if($voucher[0]['wtax']!=''){
						//compute vat
						$wtax = $this->Accounting_model->computeWTax($voucher[0]['wtax'],$gtotal);
					}

					$gtotal_afterTax = ($gtotal - $vat) - $wtax;

				?>

				<tfoot>
					<tr>
<br>
<br>
<br>

						<td width ="70%" align="right" style="font-size: 8px">
							<h4><span>Total:</span><br /><br>
							<span>WTax:</span> <br/><br>
							<span>VAT:</span> <br /><br>
							<span>Grand Total:</span></h4>
						</td>

						<td width="10%" align="right" style="font-size: 8px">

							<span style=" width:100%; margin-right:35px">Php  <?php echo number_format($gtotal,2); ?>&nbsp;</span><br /><br>
							<span style=" width:100%; margin-right:35px">  (<?php echo number_format($wtax,2); ?>)&nbsp;</span><br /><br>
							<span style=" width:100%; margin-right:35px">  (<?php echo number_format($vat,2); ?>)&nbsp;</span><br /><br>
							<span style=" width:100%; margin-right:35px; font-weight:600">Php  <?php echo number_format($gtotal_afterTax,2); ?>&nbsp;</span><br />

						</td>

					</tr>
					<br>
					<br>
					<tr>
			<td width="100%" style="border-top:#CCCCCC thin solid"></td>
		</tr>
					<br>
					<br>
					<br>
					<tr>

						<td style="font-size:16px; font-weight:600" align="center" colspan="2">
							<br />
							Received the amount of: <?php echo $this->Mmm->chequeTextFormat($gtotal_afterTax);?>
						</td>
					</tr>

				</tfoot>

			</tbody>
		</table>

	</div>
<br>
					<br>
					<br>
	<div align="center" style="margin-top:50px; margin-left:30px">
		<table style="width:650px; margin-top:20px; font-size:12px" >

			<tr>
				<td>PREPARED BY:</td>
				<td>_________________________</td>
				<td>CHECKED BY:</td>
				<td>_________________________</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td>APPROVED BY:</td>
				<td>_________________________</td>
				<td></td>
				<td>_________________________</td>
			</tr>
		</table>

	</div>



	<br>


</div>