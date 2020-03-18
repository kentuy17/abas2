<?php
// $this->Mmm->debug($items_for_canvassing);
$itemstring	=	"No Items Found!";
$counter	=	array("Low"=>0, "Medium"=>0, "High"=>0);
if(!empty($items_for_canvassing)) {
	$itemstring="";
	foreach($items_for_canvassing as $i) {
		$counter[$i['priority']]++;
		$sql				=	"SELECT * FROM inventory_request_details WHERE request_id=".$i['request_id']." AND item_id=".$i['item_id']." AND supplier_id<>0 AND (status LIKE 'For Purchase' OR status LIKE 'For Delivery' OR status LIKE 'For Payment' OR status LIKE 'Paid')";
		$approved_children	=	$this->db->query($sql);
		if($approved_children)	$itemstring	.=	$this->Purchasing_model->renderCanvassingItem($i['id']);
	}
}
?>
<div class="panel panel-primary">
	<div class="panel-heading">Items that require canvassing</div>
	<div class="panel-body">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><span class="glyphicon glyphicon-user"></span> = unassigned</div>
		<div class="clearfix"><br/></div>
		<?php echo $itemstring; ?>
	</div>
</div>
<script>
function toggleHide(e) {
	$(".canvassdetails"+e).toggleClass("hide");
}
</script>