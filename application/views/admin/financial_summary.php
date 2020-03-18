<?php

$date_now = date('Y-m-d');
$date_yesterday = date('Y-m-d',strtotime('-1 days'));
$beginning_date = date('Y-m-d',strtotime(date('Y-01-01')));

$content = '<style>
				.btx {text-align:center;font-weight:bold}
			</style>
			<h2>Daily Financial Summary as of '.date('F j, Y',strtotime($date_now)).'</h2>
				<table border="1" class="table table-bordered table-striped table-hover">
					<tr>
						<thead>
							<td class="btx">Company Name</td>
							<td class="btx" colspan="2">Revenues</td>
							<td class="btx" colspan="2">Direct Cost</td>
							<td class="btx" colspan="2">Operating Expenses</td>
						</thead>
					<tr>
					<tr>
						<thead>
							<td class="btx"></td>
							<td class="btx">Yesterday</td>
							<td class="btx">Today</td>
							<td class="btx">Yesterday</td>
							<td class="btx">Today</td>
							<td class="btx">Yesterday</td>
							<td class="btx">Today</td>
						</thead>
					<tr>';
foreach($companies as $company){
		$financial_yesterday = $this->Accounting_model->getStatementOfIncome($beginning_date,$date_yesterday,$company->id);
		$financial_today = $this->Accounting_model->getStatementOfIncome($beginning_date,$date_now,$company->id);
		$content .= '<tr>';
			$content .= '<td>'.$company->name.'</td>';
			$content .= '<td style="text-align:right">'.number_format($financial_yesterday['revenue'],2,'.',',').'</td>';
			$content .= '<td style="text-align:right">'.number_format($financial_today['revenue'],2,'.',',').'</td>';
			$content .= '<td style="text-align:right">'.number_format($financial_yesterday['direct_cost'],2,'.',',').'</td>';
			$content .= '<td style="text-align:right">'.number_format($financial_today['direct_cost'],2,'.',',').'</td>';
			$content .= '<td style="text-align:right">'.number_format($financial_yesterday['operating_expense'],2,'.',',').'</td>';
			$content .= '<td style="text-align:right">'.number_format($financial_today['operating_expense'],2,'.',',').'</td>';
		$content .= '</tr>';
		$total_revenue_yesterday = $total_revenue_yesterday + $financial_yesterday['revenue'];
		$total_direct_cost_yesterday = $total_direct_cost_yesterday + $financial_yesterday['direct_cost'];
		$total_operating_expense_yesterday = $total_operating_expense_yesterday + $financial_yesterday['operating_expense'];

		$total_revenue_today = $total_revenue_today + $financial_today['revenue'];
		$total_direct_cost_today = $total_direct_cost_today + $financial_today['direct_cost'];
		$total_operating_expense_today = $total_operating_expense_today + $financial_today['operating_expense'];
}
$content .= '<tr>
				<td class="btx" style="text-align:right">Total</td>
				<td class="btx" style="text-align:right">'.number_format($total_revenue_yesterday,2,'.',',').'</td>
				<td class="btx" style="text-align:right">'.number_format($total_revenue_today,2,'.',',').'</td>
				<td class="btx" style="text-align:right">'.number_format($total_direct_cost_yesterday,2,'.',',').'</td>
				<td class="btx" style="text-align:right">'.number_format($total_direct_cost_today,2,'.',',').'</td>
				<td class="btx" style="text-align:right">'.number_format($total_operating_expense_yesterday,2,'.',',').'</td>
				<td class="btx" style="text-align:right">'.number_format($total_operating_expense_today,2,'.',',').'</td>
			</tr>
</table>';

?>
<div>
	<?php echo $content;?>
</div>