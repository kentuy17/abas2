<?php
	if($action == 'add')
	{
		$position_val = 0;
		$quantity_val = 0;
		$title = 'Add Position';
	}
	elseif($action == 'edit')
	{
		$position_val = $item->position_id;
		$quantity_val = $item->quantity;
		$title = 'Edit Position';
	}
?>
<style type="text/css">
	#modal-bot{
		padding-bottom: 15px;
	}
</style>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2 class="panel-title"><?=$title?>
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">×</span>
			</button>
		</h2>
	</div>
</div>
<div class="panel-body">
	<form action="<?=$submit?>" role="form" method="POST" enctype="multipart/form-data">
		<div class="panel panel-info">
			<div class="panel-body" id="summary_container">
				<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot">
					<label>Position:*</label>
					<select class="form-control" name="position" required>
						<option>-SELECT</option>
						<?php foreach ($positions as $key => $val) { ?>
							<option value="<?=$val->id?>" <?=($position_val==$val->id ? "SELECTED" : "")?>>
								<?=$val->name?>
							</option>
						<?php } ?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" id="modal-bot" required>
					<label>Quantity:*</label>
					<input type="number" name="quantity" value="<?=$quantity_val?>" class="form-control">
				</div>
			</div>
		</div>	
		<div class="col-xs-12 col-sm-12 col-lg-12"s>
			<br>
			<span class="pull-right">
				<input type="submit" value="Submit" class="btn btn-success btn-m"/>
				<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
			</span>
		</div>
	</form>
</div>
