<?php
	$database_tables=	$this->db->query("SHOW TABLES");
	$database_tables=	$database_tables->result_array();
	$dbt	=	array();
	foreach($database_tables as $tbl) {
		$dbt[]	=	$tbl['Tables_in_'.DBNAME];
	}
	$abas_structure	=	array( // cannot contain spaces or special characters!
		"database"	=>	array(
			"query", 
			"encoding",
			"update",
			"tools"),
		"users"	=>	array(
			"view", 
			"add", 
			"edit", 
			"permissions", 
			"reset_password", 
			"deactivate_account"),
		"manager"	=>	array(
			"view", 
			"requests",
			"canvass",
			"purchase_orders",
			"job_orders",
			"verify_rfp_level_1",
			"approve_rfp_level_1",
			"verify_rfp_level_2",
			"approve_rfp_level_2",
			"verify_rfp_level_3",
			"approve_rfp_level_3",
			"verify_cv",
			"approve_cv",
			"verify_accountability_form",
			"approve_accountability_form",
			"verify_disposal_slip",
			"approve_disposal_slip",
			"reports",
			"view_budget",
			"generate_budget",
			"generate_all_budget",
			"edit_budget_percentage",
			"budget_verify",
			"budget_approval",
			"summary_report"
		),
		"admin"	=>	array(
			"view"),
		"human_resources"	=>	array(
			"view",
			"add",
			"edit",
			"loan",
			"leave",
			"elf",
			"overtime",
			"salary_viewing",
			"salary_editing",
			"approve_bonus",
			"reports",
			"forced_editing"
		),
		"payroll"	=>	array(
			"view", 
			"add", 
			"approve",
			"view_staff_payroll",
			"reports"
		),
		"operations"=>	array(
			"view",
			"phone_registration",
			"vessel_monitoring",
			"view_contract",
			"add_contract",
			"approve_contract",
			"view_service_order",
			"add_service_order",
			"approve_service_order",
			"view_out_turn_summary",
			"add_out_turn_summary",
			"verify_out_turn_summary",
			"approve_out_turn_summary",
			"view_vessel_certificates",
			"add_vessel_certificates"
		),
		"inventory"=>	array(
			"view",
			"add_item",
			"edit_item",
			"set_item_qty",
			"add_receiving",
			"add_issuance",
			"add_transfer",
			"add_return",
			"company_quantity_transfer",
			"purchase_report",
			//"unit_conversion",
			"add_inventory_audit",
			"count_inventory_audit",
			"verify_inventory_audit",
			"note_inventory_audit",
			"approve_inventory_audit",
			//"transaction_history",
			"add_notice_of_discrepancy",
			"verify_notice_of_discrepancy",
			"approve_notice_of_discrepancy_level_1",
			"approve_notice_of_discrepancy_level_2",
			"approve_notice_of_discrepancy_level_3",
			"add_monthly_inventory_report",
		),
		"purchasing"=>	array(
			"view",
			"view_requests",
			"view_approved_items",
			"view_canvassed_items",
			"view_purchase_orders",
			"view_job_orders",
			"canvass_item",
			"approve_request",
			"approve_canvass",
			"cancel_item",
			"create_po",
			"cancel_po",
			"approve_low_amount_po",
			"approve_medium_amount_po",
			"approve_high_amount_po",
			"cancel_jo",
			"approve_low_amount_jo",
			"approve_medium_amount_jo",
			"approve_high_amount_jo"
		),
		"accounting"	=>	array(
			"view",
			"view_vouchers",
			"add_accounts_payable_voucher",
			"add_check_voucher",
			"export_check_voucher",
			"verify_vouchers",
			"approve_vouchers",
			"add_request_for_payment",
			"add_lapsing_schedule",
			"edit_lapsing_schedule",
			"approve_journal_vouchers",
			"view_journal_vouchers",
			"view_chart_of_accounts",
			"edit_chart_of_accounts",
			"view_transaction_journal",
			"edit_transaction_journal",
			"edit_transaction_journal_entries",
			"view_request_payments",
			"view_reports",
			"summarize_entries"
		),
		"finance"	=>	array(
			"view",
			"fund_approval",
			"view_banks",
			"add_banks",
			"view_bank_recon",
			"add_bank_recon",
			"disbursement",
			"view_statement_of_account",
			"add_statement_of_account",
			"approve_statement_of_account",
			"view_payments",
			"add_payments",
			"add_dccrr",
			"releasing"
		),
		"asset_management"	=>	array(
			"view",
			"view_vessels",
			"view_trucks",
			"add_vessel_schedule_log",
			"verify_vessel_schedule_log",
			"approve_vessel_schedule_log",
			"add_truck_schedule_log",
			"verify_truck_schedule_log",
			"approve_truck_schedule_log",
			"add_vessel_work_order",
			"verify_vessel_work_order",
			"approve_vessel_work_order",
			"add_truck_repairs_report",
			"verify_truck_repairs_report",
			"approve_truck_repairs_report",
			"add_vessel_evaluation_form",
			"verify_vessel_evaluation_form",
			"approve_vessel_evaluation_form",
			"add_truck_evaluation_form",
			"verify_truck_evaluation_form",
			"approve_truck_evaluation_form",
			"add_vessel_bill_of_materials",
			"verify_vessel_bill_of_materials",
			"approve_vessel_bill_of_materials",
			"add_truck_bill_of_materials",
			"verify_truck_bill_of_materials",
			"approve_truck_bill_of_materials",
			"add_vessel_evaluation_item",
			"add_truck_evaluation_item",
			"view_fixed_asset_register",
			"add_fixed_asset_register",
			"view_accountability_form",
			"add_accountability_form",
			"view_disposal_slip",
			"add_disposal_slip"
		),
		"compliance_management"	=>	array(
			"view"
		),
		"mastertables"	=>	array(
			"view_vessels",
			"edit_vessels",
			"view_trucks",
			"edit_trucks",
			"view_suppliers",
			"edit_suppliers",
			"view_salary_grades",
			"edit_salary_grades",
			"view_tax_codes",
			"edit_tax_codes",
			"view_positions",
			"edit_positions",
			"view_companies",
			"edit_companies",
			"view_departments",
			"edit_departments",
			"view_db_activity",
			"view_clients",
			"edit_clients",
			"view_ports",
			"edit_ports",
			"view_leave_credit_codes",
			"edit_leave_credit_codes"
		),
		"encoding"	=>	$dbt
	);
	$allowed_pages=array();
	$username=$lastname=$firstname=$middlename=$email=$role="";
	if(isset($existing)) {
		$u				=	$user;
		foreach($existing as $e) {
			$allowed_pages[]	=	$e->page;
		}
		$formaction	=	HTTP_PATH.'users/update_permissions/'.$u->id;
	}
?>

<form class="form-horizontal" role="form" id="employee_permissions" name="employee_permissions"  action="<?php echo $formaction; ?>" method="post" enctype='multipart/form-data'>
	<?php echo $this->Mmm->createCSRF(); ?>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
		<div class="panel panel-primary">
			<div class="panel-heading"><h2 class="panel-title">
				User Permissions of <b><?php echo isset($user->username) ? $user->username : "a user" ; ?></b>
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2>
			</div>
		</div>
		<div class='panel-body'>
		<?php
			if(count($abas_structure)>0) :
		foreach($abas_structure as $tli=>$functions) : // $tli = top level index (module) ?>
		
		<div class="panel panel-info">
			<div class="panel-heading" role="tab" id="heading<?php echo $tli; ?>">
				<h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $tli; ?>acc" aria-expanded="false" aria-controls="<?php echo $tli; ?>acc">
					<?php echo $tli; ?>
					<span class="glyphicon glyphicon-chevron-down pull-right"></span>
					</a>
				</h4>
			</div>
			<div id="<?php echo $tli;?>acc" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $tli;?>">
				<div class="panel-body">
					<?php
						foreach($functions as $fi=>$f) { // $fi = function index (counter)
							$uniqid	=	$tli.'|'.$f;
							$label	=	'<label for="'.$uniqid.'">'.$f.'</label>';
							$checkbox	=	'<input type="checkbox" '.(in_array($uniqid, $allowed_pages) ? 'checked="checked"' : '').' name="'.$tli.'[]" id="'.$uniqid.'" value="'.$f.'" />';
							echo '<div class="col-lg-3 col-sm-2 col-xs-2 well text-center">'.$label.'<br/>'.$checkbox.'</div>';
						}
					?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</div>
	<div class='col-xs-12 col-xs-12 col-lg-12'>
		<span class="pull-right">
			<input class="btn btn-success" type="submit"  value="Save" id="submitbtn">
			<input type="button" class="btn btn-danger btn-m" value="Cancel" data-dismiss="modal">
		</span>
		<br><br><br>
	</div>
</form>
