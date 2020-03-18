<?php
	if($this->Abas->checkPermissions("purchasing|view_requests",false)) { $start_in	=	"requisitions"; }
	if($this->Abas->checkPermissions("purchasing|view_approved_items",false)) { $start_in	=	"pre-canvas"; }
	if($this->Abas->checkPermissions("purchasing|view_canvassed_items",false)) { $start_in	=	"canvassed"; }
	if($this->Abas->checkPermissions("purchasing|view_purchase_orders",false)) { $start_in	=	"purchasing"; }
?>
<?php if(SIDEMENU==null) : ?>
<div class="under-navbar">
	<div class="pull-right">
		<a class="btn btn-info" href="<?php echo HTTP_PATH ?>inventory/item_form" data-toggle="modal" data-target="#modalDialog"><i class="glyphicon glyphicon-plus"></i> <span class='hidden-sm hidden-xs'>Add</span> Inventory Item</a>
		<a class="btn btn-primary" href="<?php echo HTTP_PATH; ?>mastertables/supplier/add" data-toggle="modal" data-target="#modalDialog"><i class="glyphicon glyphicon-plus"></i> Add Supplier</a>
		<a class="btn btn-primary" href="<?php echo HTTP_PATH ?>purchasing/requisition/add" data-toggle="modal" data-target="#modalDialog"><i class="glyphicon glyphicon-plus"></i> <span class='hidden-sm hidden-xs'>Add</span> Requisition</a>
	</div>
</div>
<?php endif; ?>
<div class="panel-group" role="tablist" aria-multiselectable="true">
	<div class="panel panel-default">
		<div class="panel-body">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#requests">Requisitions</a></li>
				<?php if($this->Abas->checkPermissions("purchasing|view_approved_items",false)) : ?><li><a data-toggle="tab" href="#approved_items"><span class='hidden-sm hidden-xs'>For</span> Canvassing</a></li><?php endif; ?>
				<?php if($this->Abas->checkPermissions("purchasing|view_purchase_orders",false)) : ?><li><a data-toggle="tab" href="#purchasing"><span class='hidden-sm hidden-xs'>Purchase Orders</span><span class='hidden-md-* hidden-lg'>POs</span></a></li><?php endif; ?>
			</ul>
			<div class="tab-content">
				<div id="requests" class="tab-pane fade in active">
					<?php
						if($this->Abas->checkPermissions("purchasing|view_requests",false)) { require_once(WPATH."application/views/purchasing/requests.php"); }
						else { require_once(WPATH."application/views/prohibited.php"); }
					?>
				</div>
				<div id="approved_items" class="tab-pane fade">
					<?php
						if($this->Abas->checkPermissions("purchasing|view_approved_items",false)) { require_once(WPATH."application/views/purchasing/canvassing.php"); }
						else { require_once(WPATH."application/views/prohibited.php"); }
					?>
				</div>
				<div id="purchasing" class="tab-pane fade">
					<?php
						if($this->Abas->checkPermissions("purchasing|view_purchase_orders",false)) { require_once(WPATH."application/views/purchasing/purchase_orders.php"); }
						else { require_once(WPATH."application/views/prohibited.php"); }
					?>
				</div>
			</div>
		</div>
	</div>
</div>
