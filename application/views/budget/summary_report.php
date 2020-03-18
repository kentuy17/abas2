<?php

	//$this->Mmm->debug($revenue);

?>
<style type="text/css">
	.header,.sub-header{
		text-align: center;
	}
	.bold{
		font-weight: bold;
		padding-left: 50px;
	}
	.amount{
		text-align: right;
	}
	.line-breaker{
		padding-bottom: 10px;
		/*padding-top: 10px;*/
	}
	.sub-opex{
		padding-left: 100px;
	}
</style>
<?php
	$company_name = 'All Companies';
	if(isset($company)){
		$company_name = $company;
	}
	//$this->Mmm->debug($company);
?>
<h1 class="header"><?=$company_name?></h1>
<h2 class="sub-header">Summary Report for <?=(isset($department_name) ? $department_name : 'All' )?> 
	<?=(isset($vessel_name) ? $vessel_name : '' )?> (<?=$target_year?>)</h2>
<br/><br/><br/><hr/>
<!-- Renue -->
<div class="row">
  <div class="col-sm-6 bold">Revenues</div>
</div>
<?php foreach ($revenue_accounts as $key => $value) { ?>
	<div class="row">
	  <div class="col-sm-6 sub-opex"><?=$key?></div>
	  <div class="col-sm-4 amount"><?=number_format($value,2)?></div>
	</div>
<?php } ?>
<hr/>
<!-- Direct Cost -->
<div class="row">
  <div class="col-sm-6 bold">Direct Cost</div>
</div>
<?php foreach ($direct_cost_accounts as $key => $value) { ?>
	<div class="row">
	  <div class="col-sm-6 sub-opex"><?=$key?></div>
	  <div class="col-sm-4 amount"><?=number_format($value,2)?></div>
	</div>
<?php } ?>
<hr/><!------------------------------------------------------------->
<div class="row">
  <div class="col-sm-6 bold">Total-Revenues</div>
  <div class="col-sm-4 amount"><b><?=number_format($revenue->curr_budget,2)?></b></div>
</div>
<div class="row">
  <div class="col-sm-6 bold">Total Direct Cost</div>
  <div class="col-sm-4 amount"><b><?=number_format($direct_cost->curr_budget,2)?></b></div>
</div>
<hr/><!------------------------------------------------------------->
<div class="row">
  <div class="col-sm-6 bold">Gross Income</div>
  <div class="col-sm-4 amount"><b><?=number_format($gross_income,2)?></b></div>
</div>
<div class="pull-right line-breaker">
	===========================================================================================
</div>
<br/>
<div class="row">
  <div class="col-sm-6 bold">Operating Expenses</div>
</div>
<?php foreach ($operating_expenses as $key => $value) { ?>
	<div class="row">
	  <div class="col-sm-6 sub-opex"><?=$key?></div>
	  <div class="col-sm-4 amount"><?=number_format($value,2)?></div>
	</div>
<?php } ?>
<div class="row">
  <div class="col-sm-6 bold">Sub-total</div>
  <div class="col-sm-4 amount"><b><?=number_format($sub_total,2)?></b></div>
</div>
<hr/><!------------------------------------------------------------->
<?php
	$net_operating_income = $gross_income - $sub_total;
	$net_income = ($net_operating_income - $interest_expense) + $other_income;
?>
<div class="row">
  <div class="col-sm-6 bold">Net Operating Income</div>
  <div class="col-sm-4 amount"><b><?=number_format($net_operating_income,2)?></b></div>
</div>
<div class="pull-right line-breaker">
	===========================================================================================
</div>
<div class="row">
  <div class="col-sm-6 bold">Other Income/(Expense)</div>
</div>
<div class="row">
  <div class="col-sm-6 sub-opex">Interest Expense</div>
  <div class="col-sm-4 amount"><?=number_format($interest_expense,2)?></div>
</div>
<?php foreach ($other_income_accounts as $key => $value) { ?>
	<div class="row">
	  <div class="col-sm-6 sub-opex"><?=$key?></div>
	  <div class="col-sm-4 amount"><?=number_format($value,2)?></div>
	</div>
<?php } ?>
<!--div class="row">
  <div class="col-sm-6 sub-opex">Other Income</div>
  <div class="col-sm-4 amount"><?=number_format(3131313.2222,2)?></div>
</div>
<div class="row">
  <div class="col-sm-6 sub-opex">Other Expense</div>
  <div class="col-sm-4 amount"><?=number_format(0,2)?></div>
</div-->
<hr/><!------------------------------------------------------------>
<div class="row">
  <div class="col-sm-6 bold">Net Income</div>
  <div class="col-sm-4 amount"><b><?=number_format($net_income,2)?></b></div>
</div>


