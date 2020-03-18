<?php
echo "<h2>Bonus/13th Month Pay Report</h2>";
$bonus_table = "";
$flag_not_approve_all=0;
if(!empty($bonuses)) {
	foreach($bonuses as $ctr=>$bonus) {
		$employee	=	$this->Abas->getEmployee($bonus['employee_id']);
		$approved_by	=	$this->Abas->getUser($bonus['approved_by']);
		$company    =   $this->Abas->getCompany($employee['company_id']);
		$bonus_table	.=	"<tr>";
			$bonus_table	.=	"<td>".$company->name."</td>";
			$bonus_table	.=	"<td>".$employee['last_name']."</td>";
			$bonus_table	.=	"<td>".$employee['first_name']."</td>";
			$bonus_table	.=	"<td>".$employee['middle_name']."</td>";
			$bonus_table	.=	"<td>".$bonus['release_date']."</td>";
			$bonus_table	.=	"<td>".$bonus['type']."</td>";
			$bonus_table	.=	"<td>".number_format($bonus['amount'],2,'.',',')."</td>";
			if($bonus['approved_by']<>0){
				$bonus_table	.=	"<td>".date('Y-m-d H:m:s A',strtotime($bonus['approved_on']))."</td>";
				$bonus_table	.=	"<td>".$approved_by['full_name']."</td>";
			}else{
				$bonus_table	.=	"<td>-</td>";
				$bonus_table	.=	"<td>-</td>";
				$flag_not_approve_all++;
			}
		$bonus_table	.=	"</tr>";
	}
}
?>
<form class="form-horizontal pull-left" role="form" id="approve_all_form" action="<?php echo HTTP_PATH.'hr/bonus_report/approve_all'; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF(); ?>
	<input type="hidden" id="release_date" name="release_date" value="<?php echo $release_date?>">
	<input type="hidden" id="type" name="type" value="<?php echo $type?>">
</form>

<?php 
if($flag_not_approve_all>0 && $this->Abas->checkPermissions("human_resources|approve_bonus",false)){
	echo '<input type="button" class="btn btn-success btn-m pull-left" value="Approve All" onclick="approveAllBonus();">';
}?>

<!--<form class="form-horizontal pull-left" role="form"  action="<?php //echo HTTP_PATH.'hr/bonus_report/print'; ?>" method="post" enctype='multipart/form-data'>
	<?php //echo $this->Mmm->createCSRF(); ?>
	<input type="hidden" id="release_date" name="release_date" value="<?php //echo $release_date?>">
	<input type="hidden" id="type" name="type" value="<?php //echo $type?>">
	<input class="btn btn-info btn-m pull-left" type="submit"  value="Print" id="printbtn">
</form>-->

<table data-toggle="table" id="bonus-table" class="table table-bordered table-striped table-hover" data-pagination="true" data-show-columns="true" data-sort-name="company" data-sort-order="desc" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">
	<thead>
		<tr>
			<th data-field="company" data-align="center" data-visible="true" data-sortable="true">Company</th>
			<th data-align="center" data-visible="true" data-sortable="true">Lastname</th>
			<th data-align="center" data-visible="true" data-sortable="true">Firstname</th>
			<th data-align="center" data-visible="true" data-sortable="true">Middlename</th>
			<th data-align="center" data-visible="true" data-sortable="true">Release Date</th>
			<th data-align="center" data-visible="true" data-sortable="true">Type</th>
			<th data-align="center" data-visible="true" data-sortable="false">Amount</th>
			<th data-align="center" data-visible="true" data-sortable="true">Approved On</th>
			<th data-align="center" data-visible="true" data-sortable="true">Approved By</th>
		</tr>
	</thead>
	<tbody>
		<?php echo $bonus_table; ?>
	</tbody>
</table>
<script type="text/javascript">
	function approveAllBonus(){

	var release_date = $('#release_date').val();
    var typex = $('#type').val();

    	bootbox.confirm({
       					size: "small",
					    title: "Approve All Bonus/13th Month Pays",
					    message: "Are you sure you want to approve all?",
						buttons: {
					        confirm: {
					            label: 'Yes',
					            className: 'btn-success'
					        },
					        cancel: {
					            label: 'No',
					            className: 'btn-danger'
					        }
					    },
					    callback: function (result) {
					    	if(result==true){
					    		$('#approve_all_form').submit();
					    	}
				
					    }
					});
    }
</script>