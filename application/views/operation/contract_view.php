<?php
$action			=	HTTP_PATH."operation/contract_details/".$contract['id']."insert";
$title			=	"";
$vesseloptions	=	"<select name='vessel' id='vessel0' class='form-control'><option value=''>Choose One</option>";
$vesselstring	=	"";
if(!empty($vessels)) {
	foreach($vessels as $v) {
		$vesseloptions	.=	"<option value='".$v->id."'>".$v->name."</option>";
		$vesselstring	.=	$v->name."||";
	}
}
$vesselstring	=	rtrim($vesselstring,"||");
$vesseloptions	.=	"</select>";

$generalcharter[]	=	array("caption"=>"Origin", "name"=>"origin", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$generalcharter[]	=	array("caption"=>"Destination", "name"=>"destination", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$generalcharter[]	=	array("caption"=>"Cargo", "name"=>"cargo", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$generalcharter[]	=	array("caption"=>"Rate", "name"=>"rate", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$generalcharter[]	=	array("caption"=>"Quantity", "name"=>"quantity", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");

$lighterage[]	=	array("caption"=>"Vessel", "name"=>"vessel", "class"=>"col-sm-12 col-lg-12", "datatype"=>"custom", "validation"=>"string", "value"=>$vesseloptions);
$lighterage[]	=	array("caption"=>"Origin", "name"=>"origin", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$lighterage[]	=	array("caption"=>"Destination", "name"=>"destination", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$lighterage[]	=	array("caption"=>"Cargo", "name"=>"cargo", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$lighterage[]	=	array("caption"=>"Rate", "name"=>"rate", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$lighterage[]	=	array("caption"=>"Quantity", "name"=>"quantity", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");

$timecharter[]	=	array("caption"=>"Vessel", "name"=>"vessel", "class"=>"col-sm-12 col-lg-12", "datatype"=>"custom", "validation"=>"string", "value"=>$vesseloptions);
$timecharter[]	=	array("caption"=>"Unit", "name"=>"unit", "class"=>"col-sm-12 col-lg-12", "datatype"=>"select", "validation"=>"string", "value"=>"Days||Weeks||Months||Years", "selected"=>"");
$timecharter[]	=	array("caption"=>"Rate", "name"=>"rate", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$timecharter[]	=	array("caption"=>"Quantity", "name"=>"quantity", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");

$trucking[]	=	array("caption"=>"Vessel", "name"=>"vessel", "class"=>"col-sm-12 col-lg-12", "datatype"=>"custom", "validation"=>"string", "value"=>$vesseloptions);
$trucking[]	=	array("caption"=>"Origin", "name"=>"origin", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$trucking[]	=	array("caption"=>"Destination", "name"=>"destination", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$trucking[]	=	array("caption"=>"Cargo", "name"=>"cargo", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$trucking[]	=	array("caption"=>"Rate", "name"=>"rate", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");
$trucking[]	=	array("caption"=>"Quantity", "name"=>"quantity", "class"=>"col-sm-12 col-lg-12", "datatype"=>"text", "validation"=>"string", "value"=>"");

if($contract['type']=="General Charter") { $fields	=	$generalcharter; }
elseif($contract['type']=="Timecharter") { $fields	=	$timecharter; }
elseif($contract['type']=="Lighterage") { $fields	=	$lighterage; }
elseif($contract['type']=="Lighterage") { $fields	=	$lighterage; }
elseif($contract['type']=="Trucking") { $fields	=	$trucking; }

$detailform	=	$this->Mmm->createInput2($action, $title, $fields, "default");

?>
<div class="panel panel-primary employee-profile">
	<div class="panel-heading">
		<?php echo $contract['type']." - ".$contract['charterer_name']; ?>
		<button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
	</div>
  	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#pinfo-tab">Basic Information</a></li>
			<li><a data-toggle="tab" href="#winfo-tab">Details</a></li>
		</ul>
        <div class="tab-content">
			<div id="pinfo-tab" class="tab-pane fade in active">
				<div class="table-responsive">
					<table data-toggle="table" class="table table-striped table borderless " data-cache="false">
						<tbody>
							<?php $this->Mmm->debug($contract); ?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="winfo-tab" class="tab-pane fade">
				<div class="pull-right">
					<a href="#" id="add_detail" class="btn btn-success btn-sm" data-placement="left">Add <?php echo $contract['type']; ?> Detail</a>
				</div>
				<div class="clearfix"><br/></div>
				<div class="table-responsive">
					<table data-toggle="table" class="table table-striped table borderless " data-cache="false">
						<tbody>
							<?php $this->Mmm->debug($details); ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="add_detail_head" class="hide">
	Add <?php echo $contract['type']; ?> Detail
</div>
<div id="add_detail_content" class="hide nopadding">
	<?php echo $detailform; ?>
</div>
<script>
$('#add_detail').popover({
	html : true,
	title: function() {
	  return $("#add_detail_head").html();
	},
	content: function() {
	  return $("#add_detail_content").html();
	}
});
</script>