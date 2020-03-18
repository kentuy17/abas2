<style>
select.ui-datepicker-year, option {
	color:#000;
}
</style>
<div class="container">
	<div class="panel-group" id="loginForm">
		<div class="panel panel-primary">
			<?php
				$company_string	=	"Avega Bros Integrated Shipping Corp||Sandy Victor||Phil Tramp";
				$companies		=	$this->db->query("SELECT name FROM companies");
				if($companies) {
					if($companies->row()) {
						$companies	=	$companies->result_array();
						$company_string	=	"";
						foreach($companies as $c) {
							$company_string	.=	$c['name']."||";
						}
						$company_string	=	rtrim($company_string,"||");
					}
				}

				$link			=	HTTP_PATH."printable/ph_premium_payment_slip";
				$title			=	"PhilHealth Premium Payment Slip Formatter";
				$fields[]		=	array("caption"=>"PIN no dashes", "name"=>"pin", "datatype"=>"text", "validation"=>"str", "value"=>"");
				// $fields[1]		=	array("caption"=>"Business Name", "name"=>"business_name", "datatype"=>"text", "validation"=>"str", "value"=>"");
				$fields[]		=	array("caption"=>"Business Name", "name"=>"business_name", "datatype"=>"select", "validation"=>"str", "value"=>$company_string, "selected"=>"");
				$fields[]		=	array("caption"=>"Member Name", "name"=>"member_name", "datatype"=>"text", "validation"=>"", "value"=>"");
				$fields[]		=	array("caption"=>"Member Type", "name"=>"member_type", "datatype"=>"select", "validation"=>"str", "value"=>"Voluntary||OFW||Private||Government", "selected"=>"");
				$fields[]		=	array("caption"=>"Applicable Period FROM", "name"=>"period_from", "datatype"=>"text", "validation"=>"date", "value"=>"");
				$fields[]		=	array("caption"=>"Applicable Period TO", "name"=>"period_to", "datatype"=>"text", "validation"=>"date", "value"=>"");
				$fields[]		=	array("caption"=>"Amount Paid", "name"=>"amount_paid", "datatype"=>"text", "validation"=>"str", "value"=>"");
				echo	$this->Mmm->createInput($link,$title,$fields);
			?>
		</div>
	</div>
</div>