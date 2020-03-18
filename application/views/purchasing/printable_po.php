<?php
$po_supplier			=	$this->Abas->getSupplier($po['supplier_id']);
$po['supplier_name']	=	$po_supplier['name'];
$po_company				=	(array)$this->Abas->getCompany($po['company_id']);
$po['company_name']		=	$po_company['name'];
$added_by				=	$this->Abas->getUser($po['added_by']);
if(empty($added_by['full_name'])) {	$added_by['full_name']	=	"N/A <h1 style='color:#FF0000;'>This PO is NOT VALID</h1>"; }
if(empty($added_by['signature'])) {
	$added_by['signature']	=	"blank.png";
}
if(!file_exists(WPATH.'assets/images/digitalsignatures/'.$added_by['signature'])) {
	$added_by['signature']	=	"blank.png";
}

// $this->Mmm->debug($po_company);
// $this->Mmm->debug($po_supplier);
// $this->Mmm->debug($po);
// $this->Mmm->debug($po_content);

$table	=	"";
$totalcost	=	0;
foreach($po_content as $ctr=>$poi) {
	$item	=	$this->Inventory_model->getItem($poi['item_id']);
	$item	=	$item[0];
	$table	.=	'<tr style="text-align:center;">';
		$table	.= '<td>'.$item['item_code'].'</td>';
		$table	.= '<td>'.$poi['quantity'].'</td>';
		$table	.= '<td>'.$item['description'].'</td>';
		$table	.= '<td>'.$item['unit'].'</td>';
		$table	.= '<td style="text-align:right;">P'.number_format($poi['unit_price'],2).'</td>';
		$table	.= '<td style="text-align:right;">P'.number_format((round($poi['unit_price'],2)*$poi['quantity']),2).'</td>';
	$table	.=	'</tr>';
	$totalcost	=	$totalcost+($poi['unit_price']*$poi['quantity']);
}
$table	.=	'<tr style="text-align:right;">';
$table	.=	'<td colspan="5">Subtotal</td>';
$table	.=	'<td>P'.number_format($totalcost,2).'</td>';
$table	.=	'</tr>';

$data['orientation']	=	"P";
$data['pagetype']		=	"legal";
$data['content']		=	'
<style>
	div {
		font-size:12px;
	}
	table {
		font-size:10px;
	}
	.signature{ margin-top:-100px }
	.signatories{ margin-top:-100px }
</style>
<div style="text-align:center;">
	<h1>'.$po_company['name'].'</h1>
	<p style="margin-top:-20px">'.$po_company['address'].'<br>
		'.$po_company['telephone_no'].'
	</p>

	<br>
	<h1>Purchase Order TEst</h1>

</div>
<div style="float:right; text-align:right;">PO Number: '.$id.'</div>
<div style="margin-top:-50px">
	<table>
		<tr>
			<td style="width:20%; text-align:right;">Date:</td>
			<td style="width:80%;">'.date("j F Y", strtotime($po['tdate'])).'</td>
			<td>&nbsp;</td>
			<td style="text-align:right;">PO Number:</td>
			<td>'.$id.'</td>
		</tr>
		<tr>
			<td style="text-align:right;">Supplier:</td>
			<td>'.$po_supplier['name'].'</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td style="text-align:right;">Attention:</td>
			<td>'.$po_supplier['contact_person'].'</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td style="text-align:right;">Telephone Number:</td>
			<td>'.$po_supplier['telephone_no'].'</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td style="text-align:right;">Address:</td>
			<td>'.$po_supplier['address'].'</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

	</table>
</div>
<div style="clear:both;"><br/></div>
<table border="1" cellpadding="5" style="">
	<thead>
		<tr style="background-color:#000000; color:#FFFFFF; text-align:center;">
			<th>Item Code</th>
			<th>Quantity</th>
			<th>Description</th>
			<th>Unit</th>
			<th>Unit Price</th>
			<th>Total Price</th>
		</tr>
	</thead>
	<tbody>
		'.$table.'
	</tbody>
</table>
<br><br>
<div style="font-size:12px">Remark: '.$po['remark'].'</div>

<br><br><br><br><br>
<table>
	<tr>
		<td>Prepared by:</td>
		<td>Checked by:</td>
		<td>Approved by:</td>
	</tr>
</table>
<table id="signature">
	<tr>
		<td><img src="'.PDF_LINK.'assets/images/digitalsignatures/'.$added_by['signature'].'" /></td>
		<td><img src="'.PDF_LINK.'assets/images/digitalsignatures/55c44a7bfd3133286053a42ac3fe78da.png" /></td> '. //sir jojo
		'<td><img src="'.PDF_LINK.'assets/images/digitalsignatures/fd87612157d2ee30b2f670a3fb2a5d5a.png" /></td> '. //sir boyet
	'</tr>
</table>
<table id="signatories">
	<tr style="text-align:center;">
		<td>'.$added_by['full_name'].'</td>
		<td>Joel Hechanova</td>
		<td>Alec N. Vega</td>
	</tr>
</table>
';
?>