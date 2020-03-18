<?php
// $this->Mmm->debug($soa);
$detailtable	=	"<p>No details found!</p>";
if(!empty($soa['details'])) {
	if($soa['type']=="Avega") {
		$detailtable	=	"<table class='table table-striped table-bordered'>";
			$detailtable	.=	"<tr>";
				$detailtable	.=	"<th>Particular</th>";
				$detailtable	.=	"<th>Payment</th>";
				$detailtable	.=	"<th>Charges</th>";
				$detailtable	.=	"<th>Balance</th>";
			$detailtable	.=	"</tr>";
			foreach($soa['details'] as $ctr=>$detail) {
				if($detail['stat']==1) {
					$detailtable	.=	"<tr>";
						$detailtable	.=	"<td>".$detail['particular']."</td>";
						$detailtable	.=	"<td>".$detail['payment']."</td>";
						$detailtable	.=	"<td>".$detail['charges']."</td>";
						$detailtable	.=	"<td>".$detail['balance']."</td>";
					$detailtable	.=	"</tr>";
				}
			}
		$detailtable	.=	"</table>";
	}
	elseif($soa['type']=="NFA") {
		$detailtable	=	"<table class='table table-striped table-bordered'>";
			$detailtable	.=	"<tr>";
				//$detailtable	.=	"<th>Received On</th>";	
				//$detailtable	.=	"<th>WSI Number</th>";
				$detailtable	.=	"<th>Warehouse</th>";
				$detailtable	.=	"<th>No. of Bags</th>";
				$detailtable	.=	"<th>Gross Weight (Metric Ton)</th>";
				$detailtable	.=	"<th>Transaction</th>";
			$detailtable	.=	"</tr>";
			foreach($soa['details'] as $ctr=>$detail) {
				if($detail['status']=="Active") {
					$detailtable	.=	"<tr>";
						//$detailtable	.=	"<td>".$detail['received_on']."</td>";
						//$detailtable	.=	"<td>".$detail['wsi_number']."</td>";
						$detailtable	.=	"<td>".$detail['warehouse']."</td>";
						$detailtable	.=	"<td>".$detail['bag_quantity']."</td>";
						$detailtable	.=	"<td>".$detail['weight']."</td>";
						$detailtable	.=	"<td>".$detail['transaction']."</td>";
					$detailtable	.=	"</tr>";
				}
			}
		$detailtable	.=	"</table>";
	}
}
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			Reference Number: <?php echo $soa['reference_number']; ?>
			<span class="pull-right">
				<a href="<?php echo HTTP_PATH."billing/pay_soa/".$soa['id']; ?>" class="btn btn-success btn-xs" data-target="#modalDialog" data-toggle="modal">Receive Payment</a>
				<a href="<?php echo HTTP_PATH."statements_of_account/printable_templated/".$soa['id']; ?>" class="btn btn-success btn-xs" target="_new">Full Print</a>
				<?php if($soa['type']!="NFA"){
					echo "<a href='" . HTTP_PATH . "statements_of_account/printable_data/" .$soa['id']. "' class='btn btn-success btn-xs' target='_new'>Data Print</a>";
				}?>
			</span>
		</h3>
	</div>
	<div class="panel-body">
		<h3 class="text-center"><?php echo $soa['company']->name; ?></h3>
		<h4 class="text-center"><?php echo $soa['company']->address; ?></h3>
		<h4 class="text-center"><?php echo $soa['company']->telephone_no; ?></h4>
		<table class="table table-striped table-bordered">
			<tr>
				<th>Date</th>
				<th><?php echo date("j F Y", strtotime($soa['created_on'])); ?></th>
			</tr>
			<tr>
				<th>Client</th>
				<th><?php echo $soa['client']['company']; ?></th>
			</tr>
			<tr>
				<th>TIN</th>
				<th><?php echo $soa['client']['tin_no']; ?></th>
			</tr>
			<tr>
				<th>Address</th>
				<th><?php echo $soa['client']['address']; ?></th>
			</tr>
		</table>
		<?php echo $detailtable; ?>
		<p>Created on <?php echo date("h:i a j F Y", strtotime($soa['created_on'])); ?> by <?php echo $soa['created_by']['full_name']; ?></p>
	</div>
</div>