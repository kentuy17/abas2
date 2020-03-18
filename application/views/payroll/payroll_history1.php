<?php
$company	=	$this->Abas->getCompany($summary->company_id);
$table		=	"";
$payroll	=	array("salary"=>0, "allowance"=>0, "others"=>0, "wtax"=>0, "sss"=>0, "ph"=>0, "pi"=>0, "elf"=>0, "loan"=>0, "netpay"=>0);
if(!empty($details)) {
	// $old_dept		=	0;
	$old_vessel		=	0;
	$vessel_total	=	array();
	foreach($details AS $display) {
		$employee_details	=	$this->Abas->getEmployee($display['emp_id']);
		$vessel	=	$employee_details['vessel_id'];
		if($vessel!=$old_vessel) {
			$vessel	=	$this->Abas->getVessel($employee_details['vessel_id']);
			$vessel_total[$vessel->name]	=	(isset($vessel_total[$vessel->name])?$vessel_total[$vessel->name]:0)+($display['salary']+$display['allowance']+$display['holiday_overtime_amount']+$display['regular_overtime_amount']+$display['bonus']);
		}
	}
	$old_vessel		=	0;
	foreach($details AS $ctr=>$display) {
		// $this->Mmm->debug($display);
		$employee_details	=	$this->Abas->getEmployee($display['emp_id']);
		// $this->Mmm->debug($employee_details);
		if($display['salary'] > 0) {

			$color	=	"";
			$row	=	"";
			if($display['net_pay'] <= 1000) { $color="background-color:#FFFF55;"; }
			if($display['net_pay'] <= 0) { $color="background-color:#FF5555;"; }
			$display['emp_id']		=	!empty($employee_details['employee_id'])?$employee_details['employee_id']:"-";
			$display['full_name']	=	!empty($employee_details['full_name'])?$employee_details['full_name']:"-";
			$display['position']	=	!empty($employee_details['position_name'])?$employee_details['position_name']:"-";
			$subtotal=	$display['salary']+$display['allowance']+$display['holiday_overtime_amount']+$display['regular_overtime_amount']+$display['bonus'];
			// $dept	=	$employee_details['department'];
			$vessel	=	$this->Abas->getVessel($employee_details['vessel_id']);
			$taxcode=	$employee_details['tax_code'];
			$loans	=	$display['sss_loan'] + $display['pagibig_loan'] + $display['cash_advance'];
			if($employee_details['vessel_id']!=$old_vessel) {
				// $old_dept	=	$employee_details['department'];
				$old_vessel	=	$employee_details['vessel_id'];
				$table.=	"<tr><td colspan='15' style='background-color:#CCCCCC; text-align:left; text-decoration:bold;'><span style='float:left; margin:0px;'>".$vessel->name."</span></td></tr>";
			}
			// $row	.=	"<tr href=".HTTP_PATH.'payroll_history/payslips/employee/'.$display['id']." class='' data-toggle='modal' data-target='#modalDialog' title='Payslip' style='".$color."cursor:pointer; font-size:10px;'>";
			$row	.=	"<tr href=".HTTP_PATH.'payroll_history/edit/'.$display['id']." class='' data-toggle='modal' data-target='#modalDialog' title='Payslip' style='".$color."cursor:pointer; font-size:10px;'>";
			$row	.=	"<td class='c-align'>".$display['emp_id']."</td>";
			$row	.=	"<td class='l-align'>".$display['full_name']."</td>";
			$row	.=	"<td class='l-align'>".ucwords(strtolower($display['position']))."</td>";
			$row	.=	"<td>".$this->Abas->currencyFormat($display['salary'])."</td>";
			$row	.=	"<td>".$this->Abas->currencyFormat($display['allowance'])."</td>";
			$row	.=	"<td>".$this->Abas->currencyFormat($display['undertime_amount'])."</td>";
			$row	.=	"<td>".$this->Abas->currencyFormat($display['regular_overtime_amount'])."</td>";
			$row	.=	"<td>".$this->Abas->currencyFormat($display['holiday_overtime_amount'])."</td>";
			$row	.=	"<td>".$this->Abas->currencyFormat($display['bonus'])."</td>";
			$row	.=	"<td>".$this->Abas->currencyFormat($subtotal)."</td>";
			$row	.=	"<td>".$this->Abas->currencyFormat($display['tax'])."</td>";
			if($summary->payroll_coverage == "2nd-half") {
				$row	.=	"<td>".$this->Abas->currencyFormat($display['sss_contri_ee'])."</td>";
				$row	.=	"<td>".$this->Abas->currencyFormat($display['phil_health_contri'])."</td>";
			}
			if($summary->payroll_coverage == "1st-half") {
				$row	.=	"<td>".$this->Abas->currencyFormat($display['pagibig_contri'])."</td>";
			}
			$row	.=	"<td>".$this->Abas->currencyFormat($loans)."</td>";
			$row	.=	"<td>".$this->Abas->currencyFormat($display['net_pay'])."</td>";
			$row	.=	"</tr>";

			if(isset($details[$ctr+1])) {
				$next_emp		=	$this->Abas->getEmployee($details[$ctr+1]['emp_id']);
				$next_vessel_id	=	$next_emp['vessel_id'];
				$next_vessel	=	$this->Abas->getVessel($next_vessel_id);
				if($next_vessel->id!=$old_vessel) {
					$row	.=	"<tr><td colspan='15'><span style='float:right; margin:0px;'>Sub-total: Php ".number_format($vessel_total[$vessel->name],2)."</span></td></tr>";
				}
			}
			else {
				$row	.=	"<tr><td colspan='15'><span style='float:right; margin:0px;'>Sub-total: Php ".number_format($vessel_total[$vessel->name],2)."</span></td></tr>";
			}
			$payroll['salary']		=	$payroll['salary'] + $display['salary'];
			$payroll['allowance']	=	$payroll['allowance'] + $display['allowance'];
			$payroll['others']	=	$payroll['others'] + $display['regular_overtime_amount'] + $display['holiday_overtime_amount'];
			$payroll['wtax']	=	$payroll['wtax'] + $display['tax'];
			$payroll['sss']	=	$payroll['sss'] + $display['sss_contri_ee'];
			$payroll['ph']	=	$payroll['ph'] + $display['phil_health_contri'];
			$payroll['pi']	=	$payroll['pi'] + $display['pagibig_contri'];
			$payroll['elf']	=	$payroll['elf'] + $display['elf_contri'];
			$payroll['netpay']	=	$payroll['netpay'] + $display['net_pay'];
			$table	.=	$row;
		}
	}
}
else {
	$row	=	"<tr><td colspan='99'>No details found!</td></tr>";
}
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


</style>
<?php if(!isset($payroll_buttons)) : ?>
<div style="margin-top:-20px; background:#F4F4F4; height:40px; border-bottom:thin #CCCCCC solid;" id="print_icons;">
	&nbsp;
    <div style="margin-left:20px; margin-top:-10px">
    <a href="<?php echo HTTP_PATH.'payroll_history/payslips/payroll/'.$summary->id; ?>"  class=""  title="Payslips" style="cursor:pointer;" <?php /*onclick="var e = '<?php echo HTTP_PATH.'payroll_history/payslips/payroll/'.$summary->id; ?>' ;printReport(e);" */?> data-toggle="modal" data-target="#modalDialog" >
        <img src="<?php echo HTTP_PATH.'assets/images/icons/1592919953744704985.png' ?>" width="25px" align="absmiddle" border="1" style="border:#FF0000 thick" /> Payslips
	</a>
	|
	<a href="<?php echo HTTP_PATH.'payroll_history/bir_report/'.$summary->id; ?>" class=""  title="Income Tax Report" style="cursor:pointer;" <?php /*onclick="var e = '<?php echo HTTP_PATH.'payroll_history/bir_report/'.$summary->id; ?>' ; printReport(e); "*/ ?> data-toggle="modal" data-target="#modalDialog" >
		<img src="<?php echo HTTP_PATH.'assets/images/bir_logo1.jpg' ?>" width="20px" align="absmiddle" border="1" style="border:#FF0000 thick" /> Tax Report
	</a>
    |
	<a href="<?php echo HTTP_PATH.'payroll_history/bank_report/'.$summary->id; ?>" class=""  title="Bank Remitance Report" style="cursor:pointer;" <?php /* onclick="var e = '<?php echo HTTP_PATH.'payroll_history/bank_report/'.$summary->id; ?>' ; printReport(e);"*/ ?> data-toggle="modal" data-target="#modalDialog" >
		<img src="<?php echo HTTP_PATH.'assets/images/bir_logo1.jpg' ?>" width="20px" align="absmiddle" border="1" style="border:#FF0000 thick" /> Bank Remitance Report
	</a>
    |
	<a href="<?php echo HTTP_PATH.'payroll_history/sss_report/'.$summary->id; ?>" class=""  title="SSS Contribution Report" style="cursor:pointer;" <?php /* onclick="var e = '<?php echo HTTP_PATH.'payroll_history/sss_report/'.$summary->id; ?>' ; printReport(e);"*/ ?> data-toggle="modal" data-target="#modalDialog" >
		<img src="<?php echo HTTP_PATH.'assets/images/sss_logo.png' ?>" width="25px" align="absmiddle" /> SSS Report
	</a>
    |
    <a href="<?php echo HTTP_PATH.'payroll_history/ph_report/'.$summary->id; ?>" class=""  title="PhilHealth Contribution Report" style="cursor:pointer;" <?php /* onclick="var e = '<?php echo HTTP_PATH.'payroll_history/ph_report/'.$summary->id; ?>' ; printReport(e);" */ ?> data-toggle="modal" data-target="#modalDialog" >
		<img src="<?php echo HTTP_PATH.'assets/images/ph_logo.jpg' ?>" width="25px" align="absmiddle" /> PhilHealth Report
	</a>
    |
    <a href="<?php echo HTTP_PATH.'payroll_history/pi_report/'.$summary->id; ?>" class=""  title="PagIbig Contribution Report" style="cursor:pointer;" <?php /* onclick="var e = '<?php echo HTTP_PATH.'payroll_history/pi_report/'.$summary->id; ?>' ; printReport(e);" */ ?> data-toggle="modal" data-target="#modalDialog" >
		<img src="<?php echo HTTP_PATH.'assets/images/pagibig_logo.jpg' ?>" width="15px" align="absmiddle" /> Pag-Ibig Report
	</a>
    </div>
</div>
<?php endif; ?>

<script>
	$( "#printMe" ).on( "click", function() { //the print button has the class .print-now
		event.preventDefault(); // prevent normal button action
	   $('.btn-lg').removeClass('btn-lg'); // remove the form-control class
		window.print(); // print the page
		$('button').addClass('btn-lg'); // return the class after printing
	});



	function printReport(site){

		var site = '';
		var wid = 1020;
		var leg = 540;
		var left = (screen.width/2)-(wid/2);
		var top = (screen.height/2)-(leg/2);
		window.open(site,'popuppage','width='+wid+',toolbar=0,resizable=1,location=no,scrollbars=no,height='+leg+',top='+top+',left='+left);

	}
	</script>

	<style>
	@media print {
	  a[href],button {
		display: none !important;
	  }
	  input,
	  textarea {
		border: none !important;
		box-shadow: none !important;
		outline: none !important;
	  }
	}
</style>

<div style="margin-top:10px; float:right; margin-right:30px">
	<a href="<?php echo HTTP_PATH."payroll_history/summary_printable/".$summary->id; ?>" target="_new"><button id="printMe" type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-print"></span> Print</button></a>
</div>
<div class = "panel panel-default" style="margin-left:30px; width:96%; border:#999999 thin solid; margin-top:45px">
	<div class = "panel-heading" style=" background:#CCC; color:#000">
		<h3 class = "panel-title">
			<strong>PAYROLL</strong>  (Period: <?php echo $summary->payroll_coverage.", ".date("F Y",strtotime($summary->payroll_date."-01")); ?>)
			<span style="float:right; margin-right:10px"><strong><?php echo $company->name; ?></strong></span>
		</h3>
	</div>
	<div class = "panel-body">
		<table class="table table-condensed table-bordered table-hover table-condensed" style="font-size:12px">
			<thead style="background:#000; color:#FFFFFF;" >
				<tr>
					<th width="5%" class="text-center">EID</th>
					<th width="12%" class="text-center">Name</th>
					<th width="13%" class="text-center">Position</th>
					<th width="5%" class="text-center">Salary</th>
					<th width="4%" class="text-center">Allowance</th>
					<th width="4%" class="text-center">Undertime</th>
					<th width="4%" class="text-center">Regular OT</th>
					<th width="4%" class="text-center">Holiday OT</th>
					<th width="5%" class="text-center">Others</th>
					<th width="5%" class="text-center">Sub-Total</th>
					<th width="5%" class="text-center">W-Tax</th>
					<?php if($summary->payroll_coverage == "2nd-half") : ?>
					<th width="4%" class="text-center">SSS</th>
					<th width="4%" class="text-center">PhilHealth</th>
					<?php endif; ?>
					<?php if($summary->payroll_coverage == "1st-half") : ?>
					<th width="4%" class="text-center">Pagibig</th>
					<?php endif; ?>
					<th width="5%" class="text-center">Loan</th>
					<th width="5%" class="text-center">Net Pay</th>
				</tr>
			</thead>
			<tbody>
				<?php
				echo $table;
				?>
			</tbody>
            <tfoot>
            	<tr style="background:#333333; color:#FFFFFF; font-size:14px">
                	<td colspan="15" align="right">Total Payroll: Php <?php echo $this->Abas->currencyFormat($summary->payroll_amount); ?></td>

                </tr>
            </tfoot>
		 </table>
	</div>
	<div class = "panel-footer">
		<div style="margin-bottom:10px">
			<table width="100%" cellpadding="1px" cellspacing="5">
				<thead>
					<tr>
						<th width="35%" class="text-left">Prepared by:</th>
						<th width="35%" class="text-left">Checked by:</th>
						<th width="30%" class="text-left">Noted by:</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th width="35%" class="text-left">_________________________________</th>
						<th width="35%" class="text-left">_________________________________</th>
						<th width="30%" class="text-left">_________________________________</th>
					</tr>
					<tr>
						<th width="35%" class="text-left"><?php echo $_SESSION['abas_login']['fullname']; ?></th>
						<th width="35%" class="text-left">Arnel T. Sagdullas / Joy G. Obenita</th>
						<th width="30%" class="text-left">Belma A. Hipolito</th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
function statistics() {
	toastr['info']("Total payroll this cutoff is PHP<?php echo $this->Abas->currencyFormat($total_payroll);?>","ABAS Says");
	toastr['info']("Total taxes paid this cutoff is PHP<?php echo $this->Abas->currencyFormat($total_taxes);?>","ABAS Says");
	toastr['info']("Total ELF contribution this cutoff is PHP<?php echo $this->Abas->currencyFormat($total_elf);?>","ABAS Says");
	toastr['info']("Total SSS contribution this cutoff is PHP<?php echo $this->Abas->currencyFormat($total_sss);?>","ABAS Says");
}
</script>
