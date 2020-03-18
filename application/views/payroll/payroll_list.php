<?php
// $this->Mmm->debug($_SESSION['payroll']['summary']);
// $this->Mmm->debug($_SESSION['payroll']['data'][0]);
$table	=	"";
$total_payroll	=	0;
$total_taxes	=	0;
$total_sss		=	0;
$total_ph		=	0;
$total_pi		=	0;
$total_elf		=	0;
$grandtotal		=	array(
						"allowance" => 0,
						"absences" => 0,
						"ut" => 0,
						"regularOT" => 0,
						"restdayOT" => 0,
						"legalholidayOT" => 0,
						"legalholiday_restdayOT" => 0,
						"specialholidayOT" => 0,
						"specialholiday_restdayOT" => 0,
						"holidayOT" => 0,
						"adjustment" => 0,
						"gross" => 0,
						"wtax" => 0,
						"sss" => 0,
						"ph" => 0,
						"pi" => 0,
						"loanSSS" => 0,
						"loanPI" => 0,
						"loanPH" => 0,
						"cashadv" => 0,
						"elf" => 0,
						"net" => 0
					);
// $this->Mmm->debug($_SESSION['payroll']['data']);
if(!empty($all_employees_per_vessel)) {
	// $this->Mmm->debug($all_employees_per_vessel);
	foreach($all_employees_per_vessel as $vid=>$vdata) {
		// $department_name	=	$this->Abas->getDepartment($did);
		// $this->Mmm->debug($vessel_name);
		// $department_name	=	$department_name->name;
		$vessel_label=1;
		foreach($_SESSION['payroll']['data'] as $prid=>$aepd) {
			if($aepd['vessel_id'] == $vid) {

				if($vessel_label==1){
					$vessel_name		=	$this->Abas->getVessel($vid);
					$vessel_name		=	$vessel_name->name;
					$table	.=	"<tr><td colspan='99' style='background-color:#CCCCCC; text-align:left;'>".$vessel_name."</td></tr>";

					$table	.='<tr>
								<th class="text-center value-column">EID</th>
								<th class="text-center name-column">Name</th>
								<th class="text-center name-column">Position</th>
								<th class="text-center value-column">Basic</th>
								<th class="text-center value-column">Allowance</th>
								<th class="text-center value-column">Absences</th>
								<th class="text-center value-column">Late/UT</th>
								<th class="text-center value-column">Regular OT</th>
								<th class="text-center value-column">Rest Day OT</th>
								<th class="text-center value-column">Legal Holiday OT</th>
								<th class="text-center value-column">Legal Holiday on Rest Day OT</th>
								<th class="text-center value-column">Special Holiday OT</th>
								<th class="text-center value-column">Special Holiday on Rest Day OT</th>
								<th class="text-center value-column">Total Holiday OT</th>
								<th class="text-center value-column">Night Differential</th>
								<th class="text-center value-column">Bonus</th>
								<th class="text-center value-column">Adjustments/ Others</th>
								<th class="text-center value-column">Gross</th>
								<th class="text-center value-column">W-Tax</th>';

							if($_SESSION['payroll']['period'] == "2nd-half"){
								$table	.='<th class="text-center value-column">SSS</th>';
								$table	.='<th class="text-center value-column">PHIC</th>';
							}elseif($_SESSION['payroll']['period'] == "1st-half"){
								$table	.='<th class="text-center value-column">HMDF</th>';
							}

					$table	.='<th class="text-center value-column">SSS Loan</th>
								<th class="text-center value-column">HMDF Loan</th>
								<th class="text-center value-column">ELF Loan</th>
								<th class="text-center value-column">Cash Advance</th>
								<th class="text-center value-column">ELF Contri</th>
								<th class="text-center value-column">Net Pay</th>
								<th class="text-center value-column">Manage</th>
						</tr>';

					$vessel_label=0;
				}

				$sss_loans				=	0;
				$pi_loans				=	0;
				$ca_loans				=	0;
				$elf_loans			=	0;
				if(isset($aepd['paid_loans'])) {
					foreach($aepd['paid_loans'] as $loan_id=>$loan_payment_amount) {
						$loandata	=	$this->Payroll_model->getLoan($loan_id);
						if($loandata['loan_type'] == "SSS") {
							$sss_loans	=	$sss_loans + $loan_payment_amount;
						}
						elseif($loandata['loan_type'] == "PagIbig") {
							$pi_loans	=	$pi_loans + $loan_payment_amount;
						}
						elseif($loandata['loan_type'] == "Cash Advance") {
							$ca_loans	=	$ca_loans + $loan_payment_amount;
						}
						else {
							$elf_loans	=	$elf_loans + $loan_payment_amount;
						}
					}
				}
				$color="";
				if($aepd['net_pay'] <= 1000) { $color="background-color:#FFFF55;"; }
				if($aepd['net_pay'] <= 0) { $color="background-color:#FF5555;"; }
				$gross				=	($aepd['rates']['per_cutoff'] + $aepd['allowance'] + $aepd['ot']['regular'] + $aepd['ot']['restday'] + $aepd['ot']['legal_holiday'] + $aepd['ot']['legal_holiday_restday'] + $aepd['ot']['special_holiday'] + $aepd['ot']['special_holiday_restday'] + $aepd['nd'] + $aepd['bonus']+ $aepd['others']) - ($aepd['ut']+$aepd['absences_amount']);
				$employee_details	=	$this->Abas->getEmployee($aepd['employee_id']);
				$tablerow			=	"<tr>";
					$tablerow			.=	"<td class='text-center value-column'>".$employee_details['employee_id']."</td>";
					$tablerow			.=	"<td class='text-left name-column'>".$employee_details['full_name']."</td>";
					$tablerow			.=	"<td class='text-left name-column'>".ucwords(strtolower($employee_details['position_name']))."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['rates']['per_cutoff'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['allowance'])."</td>";
					$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($aepd['absences_amount']).")</td>";
					$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($aepd['ut']).")</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['ot']['regular'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['ot']['restday'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['ot']['legal_holiday'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['ot']['legal_holiday_restday'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['ot']['special_holiday'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['ot']['special_holiday_restday'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['ot']['holiday'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['nd'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['bonus'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['others'])."</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($gross)."</td>";
					$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($aepd['withholding']).")</td>";
					if($_SESSION['payroll']['period'] == "2nd-half") :
						$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($aepd['sss']['payable']).")</td>";
						$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($aepd['ph']['payable']).")</td>";
					endif;
					if($_SESSION['payroll']['period'] == "1st-half") :
						$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($aepd['pi']['payable']).")</td>";
					endif;
					// $tablerow			.=	"<td>".$this->Abas->currencyFormat($aepd['elf']['payable'])."</td>";
					$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($sss_loans).")</td>";
					$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($pi_loans).")</td>";
					$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($aepd['elf']['loan']).")</td>";
					$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($ca_loans).")</td>";
					$tablerow			.=	"<td class='value-column'>(".$this->Abas->currencyFormat($aepd['elf']['payable']).")</td>";
					$tablerow			.=	"<td class='value-column'>".$this->Abas->currencyFormat($aepd['net_pay'] - ($aepd['elf']['payable']+$aepd['elf']['loan']))."</td>";
						$tablerow			.=	"<td class='value-column'><a class='btn btn-xs btn-block btn-warning' href=".HTTP_PATH.'payroll/edit/'.$prid." data-toggle='modal' data-target='#modalDialog'>Edit</a></td>";
				$tablerow			.=	"</tr>";
				$table	.=	$tablerow;
			}
		}
	}
}


$summary		=	$_SESSION['payroll']['summary'];
?>

<style>
	.l-align { text-align:left; }
	.r-align { text-align:right; }
	.c-align { text-align:center; }
	#content{ margin-top:-20px}
    .demo-content{
        padding: 15px;
        font-size: 18px;
        background: #dbdfe5;
        margin-bottom:0px;
    }
    .demo-content.bg-alt{
        background: #abb1b8;
    }
	#heading{ min-height: 50px;}
	table tbody tr td {
		text-align:right;
	}

	.name-column {
		width:7.5%;
	}
	.value-column {
		width:4.72%;
	}
</style>
<h2>Payroll Preparation</h2>
<a class="btn btn-success exclude-pageload" title="Save" onclick='javascript:confirmSubmit()'>Save</a>
<a href="<?php echo HTTP_PATH?>payroll/" class="btn btn-dark force-pageload">Back</a>

<div class = "panel panel-primary" >
	<div class = "panel-heading">
		<h3 class = "panel-title">
			<span style="float:left">
				Transaction Code No. - | Control No. -
			</span>
			<span class="pull-right">Status: Draft</span>
		</h3>
		<br>
	</div>
	<div class="panel-body">
		
			<h3 class="text-center"><?php echo $_SESSION['payroll']['company_name']; ?></h3>
			<h4 class="text-center">(Period: <?php echo $_SESSION['payroll']['period'].", ".$_SESSION['payroll']['month']." ".$_SESSION['payroll']['year']; ?>)</h4>


		<div class="table-responsive">
			<table class="table table-bordered table-hovered">
				<tbody>
					<?php
					echo $table;
					?>
				</tbody>
			 </table>
		</div>
	</div>
</div>
<script>
jQuery(function($) {
	$(document).ready( function() {
		$('.payroll-table-head').stickUp();
	});
});
function confirmSave() {
	toastr['info']('<a class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalDialog" href="<?php echo HTTP_PATH.'payroll/save'; ?>">Save Payroll</a>', "Are you sure?");
}

function confirmSubmit(){

	bootbox.confirm({
   					size: "small",
   					title: "Payroll Preparation",
				    message: "Are you sure you want to save this Payroll for Approval?",
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
				    	if(result){
				    		window.location.href = "<?php echo HTTP_PATH.'payroll/save'; ?>";
				    	}
				    }
				});
	}
</script>