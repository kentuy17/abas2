<?php
if(isset($recid)) {
	$record		=	$this->db->query("SELECT * FROM ".$table." WHERE id=".$recid);
	$record		=	(array)$record->row();
}
$view	=	"<table data-toggle='table' id='single_database_entry' class='table table-bordered table-striped table-hover'data-pagination='true' data-show-columns='true'>";
foreach($record AS $caption=>$value) {
	// if($caption!="id") {
		if($caption=="tdate"||$caption=="created_on"||$caption=="date_created"||$caption=="voucher_date") { $value=date("j F Y H:i:s",strtotime($value)); }
		if($caption=="created_by") {
			$user	=	$this->Abas->getUser($value);
			$value	=	$user['full_name'];
		}
		if($caption=="supplier_id"||$caption=="payee") {
			$supplier	=	$this->Abas->getSupplier($value);
			$value		=	$supplier['name'];
		}
		if($caption=="company_id") {
			$caption	=	"company";
			$company	=	$this->Abas->getCompany($value);
			$value		=	$company->name;
		}
		if($caption=="vessel_id") {
			$caption	=	"vessel";
			$vessel	=	$this->Abas->getVessel($value);
			$value		=	$vessel['name'];
		}
		$view	.=	"<tr><th>".ucwords(str_replace("_"," ",$caption))."</th><td>".$value."</td></tr>";
	// }
}
$view	.=	"</table>";
echo $view;
?>
