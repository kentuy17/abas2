<style>
select.ui-datepicker-year, option {
	color:#000;
}
</style>
<div class="container">
	<div class="panel-group" id="loginForm">
		<div class="panel panel-primary">
			<?php
				$link			=	HTTP_PATH."printable/cheque";
				$title			=	"Cheque Formatter";
				$fields[0]		=	array("caption"=>"Pay to the Order of", "name"=>"payee", "datatype"=>"text", "validation"=>"str", "value"=>"");
				$fields[1]		=	array("caption"=>"Peso Amount", "name"=>"peso_amount", "datatype"=>"text", "validation"=>"", "value"=>"");
				$fields[2]		=	array("caption"=>"Cheque Date", "name"=>"cheque_date", "datatype"=>"text", "validation"=>"date", "value"=>"");
				// $fields[0]		=	array("caption"=>"Pay to the Order of", "name"=>"payee", "datatype"=>"text", "validation"=>"", "value"=>"");
				// $fields[1]		=	array("caption"=>"Peso Amount", "name"=>"peso_amount", "datatype"=>"text", "validation"=>"", "value"=>"");
				// $fields[2]		=	array("caption"=>"Cheque Date", "name"=>"cheque_date", "datatype"=>"text", "validation"=>"", "value"=>"");
				$fields[3]		=	array("caption"=>"Cheque type", "name"=>"cheque_type", "datatype"=>"select", "validation"=>"str", "value"=>"EastWest||EastWest-VS||EastWest-Arlyn||BPI||PNB||UnionBank-AV||UnionBank-SV||Bangkok Bank", "selected"=>"");
				echo	$this->Mmm->createInput($link,$title,$fields);
			?>
		</div>
	</div>
</div>