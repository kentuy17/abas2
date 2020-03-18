<?php
$current_login	=	$this->Abas->getUser($_SESSION['abas_login']['userid']);
$gitbranch		=	"Git repository not initialized!";
if(file_exists(WPATH.'.git/HEAD')) {
	$stringfromfile		=	file(WPATH.'.git/HEAD');
	$firstLine			=	$stringfromfile[0]; //get the string from the array
	$explodedstring		=	explode("/", $firstLine, 3); //seperate out by the "/" in the string
	$branchname			=	$explodedstring[2]; //get the one that is always the branch name
	$gitbranch			=	"Checked-out Branch: ".$branchname;
}

if($_SESSION['abas_login']['role']!="Administrator"){
	//auto forcelogut after 30 minutes of inactivity
	if(time() - $_SESSION['timestamp'] > 1800) { //subtract new timestamp from the old one
	    echo"<script>alert('10 Minutes over!');</script>";
	    header("Location: " .HTTP_PATH."home/forced_logout");
	    exit;
	}else{
		$_SESSION['timestamp'] = time();
	}
}
$hr_id = $this->Abas->getEmpId($_SESSION['abas_login']['userid']);
$this_user = $this->Abas->getItemById('hr_employees',array('id'=>$hr_id));
$leave_count = $this->Abas->getLeaveCount();
$overtime_count = $this->Abas->getOvertimeCount();
$for_approval_count = $leave_count + $overtime_count;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php if(ENVIRONMENT=="development") { echo "[DEV] "; } elseif(ENVIRONMENT=="testing") { echo "[STG] "; } ?>AVega Business Automation System</title>
		<link rel="icon" href="<?php echo LINK."assets/images/av.ico"; ?>" />
		<link rel="stylesheet" href="<?php echo LINK."assets/normalize.css"; ?>">
		<link rel="stylesheet" href="<?php echo LINK ?>assets/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo LINK ?>assets/gentelella-master/build/css/custom.min.css">
		<link rel="stylesheet" href="<?php echo LINK."assets/jqueryui/jqueryui.css"; ?>" />
		<link rel="stylesheet" href="<?php echo LINK."assets/toastr/toastr.css"; ?>" />
		<link rel="stylesheet" href="<?php echo LINK."assets/bootstrap-table-master/src/bootstrap-table.css"; ?>">
		<link rel="stylesheet" href="<?php echo LINK."assets/style.css"; ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo LINK?>assets/gentelella-master/vendors/bootstrap-table-filter/bootstrap-table-filter-control.css">
		<script src="<?php echo LINK.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/jquery/jQuery.print.js' ?>"></script>
		<script src="<?php echo LINK.'assets/jquery/jquery.printPage.js' ?>"></script>
		<script src="<?php echo LINK.'assets/jqueryui/jqueryui.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/bootstrap/js/bootstrap.min.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/bootstrap-table-master/src/bootstrap-table.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/toastr/toastr.js'; ?>"></script>
		<script src="<?php echo LINK.'assets/stickUp.min.js'; ?>"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootbox/bootbox.min.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootstrap-table-export/bootstrap-table-export.js"></script>
		<script src="<?php echo LINK ?>assets/gentelella-master/vendors/bootstrap-table-export/tableExport.js"></script>
		<script src="<?php echo LINK ?>assets/echarts/echarts.min.js"></script>
		<!--<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-80766509-1', 'auto');
			ga('send', 'pageview');
		</script>-->
		<style>
		       .ui-autocomplete {
		            max-height: 200px;
		            overflow-y: auto;
		            /* prevent horizontal scrollbar */
		            overflow-x: hidden;
		            /* add padding to account for vertical scrollbar */
		            padding-right: 20px;
		        }
		</style>
	</head>
	<body class="nav-md is-loading">
		<div class="container body">
			<div class="main_container">
				<div class="col-md-3 left_col">
					<div class="left_col scroll-view">
						<div class="navbar nav_title">
							<a href="<?php echo HTTP_PATH; ?>" class="site_title"><img src="<?php echo LINK.'assets/images/AvegaLogo.jpg'; ?>" width="50px" align="absmiddle" class=""> <span style="font-size: 25px; margin-top: 200px"><b>A B A S</b></a></span>
								<span class="pull-right" style="margin-top: -36px;margin-right: 13px">
								<?php 
									$v = $this->Abas->readChangeLog();
									if($v!=null){
									echo '<a class="btn btn-xs btn-dark hidden-small" style="color:white" href="'.HTTP_PATH.'system/open_change_log" data-toggle="modal" data-target="#modalDialog">'.$v['num'].'</a>';
									}
								;?>
								</span>
						</div>
						<div class="profile">
							<div class="profile_pic">
								<img src="<?php echo LINK.'assets/images/my_picture.jpg'; ?>" class="img-circle profile_img" height="55px" >
							</div>
							<div class="profile_info">
								<span>Welcome,</span>
								<h2><?php echo (isset($_SESSION['abas_login'])?$_SESSION['abas_login']['username']:"User"); ?></h2>
							</div>
						</div>
						<br />
						<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
							<div class="menu_section">
								<h3><center><?php echo "Role: ".$_SESSION['abas_login']['role']?></center></h3>
								<h3><center><?php echo "Location: ".$_SESSION['abas_login']['user_location']?></center></h3>
								<ul class="nav side-menu" style="display: inline !important;">
									<hr>
									<?php if(ENVIRONMENT=="development"): ?>
										<li style="background-color: #fb0707"><a href="#" ><b>Development Mode</b><br><?php echo $gitbranch;?></a></li>
									<?php endif; ?>
									<?php if(ENVIRONMENT=="testing"): ?>
										<li style="background-color: #e3680a"><a href="#" ><b>Staging/Testing Mode</b><br><?php echo $gitbranch;?></a></li>
									<?php endif; ?>
									<li><a href="<?php echo HTTP_PATH; ?>"><i class="glyphicon glyphicon-home"></i> Home</a></li>
									<?php if($_SESSION['abas_login']['role']=="Administrator"){ ?>
										<li>
											<a><span class="glyphicon glyphicon-chevron-down"></span> Administrator</a>
											<ul class="nav child_menu">
												<li><a href="<?php echo HTTP_PATH.'system/sys_info'; ?>" data-toggle="modal" data-target="#modalDialog"><span class="glyphicon glyphicon-hdd"></span> Server Info</a></li>
												<li><a href="<?php echo HTTP_PATH.'system/logs'; ?>"><span class="glyphicon glyphicon-alert"></span> System Logs</a></li>
												<li><a href="<?php echo HTTP_PATH.'mastertables/'; ?>"><span class="glyphicon glyphicon-align-justify"></span> Master Tables</a></li>
												<li><a href="<?php echo HTTP_PATH."system/db_encoding"; ?>"><span class="glyphicon glyphicon-floppy-disk"></span> Database Encoding</a></li>
												<li><a href="<?php echo HTTP_PATH."system/query"; ?>"><span class="glyphicon glyphicon-filter"></span> Database Manual Query</a></li>
												<li><a href="<?php echo HTTP_PATH.'users'; ?>"><span class="glyphicon glyphicon-sunglasses"></span> User Accounts</a></li>
											</ul>
										</li>
									<?php }?>
									<?php if(SIDEMENU!= null): ?>
										<?php if(SIDEMENU=="Manager's Dashboard"){ ?>
											<?php if($this->Abas->checkPermissions("manager|view",false)): ?>
												<li><a href="<?php echo HTTP_PATH.'manager/'; ?>" class='force-pageload'><i class="glyphicon glyphicon-check"></i> Manager's Approval Area</a></li>
												<?php if($this->Abas->checkPermissions("manager|reports",false)): ?>
													<li><a><span class="glyphicon glyphicon-chevron-down"></span> Management Reports </a>
														<ul class="nav child_menu">
															<li><a href="<?php echo HTTP_PATH.'manager/material_aging_requests_report/'; ?>"><span class="glyphicon glyphicon-duplicate"></span> Material Aging Requests Report</a></li>
															<li><a href="<?php echo HTTP_PATH.'manager/vessel_expenses_report/filter'; ?>" data-toggle="modal" data-target="#modalDialogNorm"><span class="glyphicon glyphicon-duplicate" ></span> Booked Vessel Expenses Report</a></li>
															<li><a href="<?php echo HTTP_PATH.'manager/vessel_repairs_statistics/filter'; ?>" data-toggle="modal" data-target="#modalDialogNorm"><span class="glyphicon glyphicon-duplicate"></span> Vessel Repairs Statistics</a></li>
															<!--<li><a href="<?php //echo HTTP_PATH.'manager/corporate_financial_summary/'; ?>" class='force-pageload'><span class="glyphicon glyphicon-duplicate"></span> Daily Corporate Financial Summary</a></li>-->
														</ul>
													</li>
												<?php endif; ?>
												<?php if($this->Abas->checkPermissions("manager|view_budget",false)): ?>
													<li><a><span class="glyphicon glyphicon-chevron-down"></span> Budget </a>
														<ul class="nav child_menu">
															<li>
																<a href="<?=HTTP_PATH.'budget/budget_view'?>">
																	<span class="glyphicon glyphicon-duplicate"></span> Generate Budget
																</a>
															</li>
															<?php if($this->Abas->checkPermissions("manager|budget_approval",false)){ ?>
															<li>
																<a href="<?php echo HTTP_PATH.'budget/budget_approval'; ?>">
																	<span class="glyphicon glyphicon-duplicate"></span> Budget Approval
																</a>
															</li>
															<?php } ?>
															<li>
																<a href="<?=HTTP_PATH.'budget/company_summary_report'?>">
																	<span class="glyphicon glyphicon-duplicate"></span> Company Budget
																</a>
															</li>
															<?php if($this->Abas->checkPermissions("manager|summary_report",false)){ ?>
															<!--li>
																<!--a href="<?php echo HTTP_PATH.'budget/graph'; ?>"-->
																<!--a onclick="selectGraphYear()">
																	<span class="glyphicon glyphicon-stats"></span> Graph
																</a>
															</li-->
															<li>
																<a data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static" href="<?php echo HTTP_PATH.'budget/summary_report/filter'; ?>">
																	<span class="glyphicon glyphicon-list"></span> Summary Report
																</a>
															</li>
															<?php } ?>
															
														</ul>
													</li>
												<?php endif; ?>
											<?php endif; ?>
										<?php } ?>
										<?php if(SIDEMENU=="Accounting"){ ?>
											<?php if($this->Abas->checkPermissions("accounting|view",false)): ?>
												<li><a href="<?php echo HTTP_PATH.'accounting/accounts_payable_voucher/listview'; ?>"><span class="glyphicon glyphicon-edit"></span> Accounts Payable Vouchers</a></li>
												<li><a href="<?php echo HTTP_PATH.'accounting/check_voucher/listview'; ?>"><span class="glyphicon glyphicon-edit"></span>  Check Vouchers</a></li>
												<li><a href="<?php echo HTTP_PATH.'accounting/request_for_payment/listview'; ?>"><span class="glyphicon glyphicon-edit"></span> Request For Payments</a></li>
												<li><a href="<?php echo HTTP_PATH.'accounting/lapsing_schedule/listview'; ?>"><span class="glyphicon glyphicon-edit"></span> Lapsing Schedule</a></li>
												<li>
													<a><span class="glyphicon glyphicon-chevron-down"></span> Accounts Receivables </a>
													<ul class="nav child_menu">
														<li><a href="<?php echo HTTP_PATH.'accounting/listview/accounts_receivables/for_clearing'; ?>"><span class="glyphicon glyphicon-columns"></span> For Clearing</a></li>
														<li><a href="<?php echo HTTP_PATH.'accounting/listview/accounts_receivables/for_posting'; ?>"><span class="glyphicon glyphicon-columns"></span> For Approval and Posting</a></li>
														<li><a href="<?php echo HTTP_PATH.'accounting/listview/accounts_receivables/posted'; ?>"><span class="glyphicon glyphicon-columns"></span> Posted</a></li>
													</ul>
												</li>
												<li>
													<a><span class="glyphicon glyphicon-chevron-down"></span> Accounts Collections </a>
													<ul class="nav child_menu">
														<li><a href="<?php echo HTTP_PATH.'accounting/listview/accounts_collection/for_clearing'; ?>"><span class="glyphicon glyphicon-columns"></span> For Clearing</a></li>
														<li><a href="<?php echo HTTP_PATH.'accounting/listview/accounts_collection/for_posting'; ?>"><span class="glyphicon glyphicon-columns"></span> For Approval and Posting</a></li>
														<li><a href="<?php echo HTTP_PATH.'accounting/listview/accounts_collection/posted'; ?>"><span class="glyphicon glyphicon-columns"></span> Posted</a></li>
													</ul>
												</li>
												<li>
													<a><span class="glyphicon glyphicon-chevron-down"></span> Inventory Issuances </a>
													<ul class="nav child_menu">
														<li><a href="<?php echo HTTP_PATH.'accounting/inventory_issuances/listview/for_clearing'; ?>"><span class="glyphicon glyphicon-columns"></span> For Clearing</a></li>
														<li><a href="<?php echo HTTP_PATH.'accounting/inventory_issuances/listview/for_posting'; ?>"><span class="glyphicon glyphicon-columns"></span> For Approval and Posting</a></li>
														<li><a href="<?php echo HTTP_PATH.'accounting/inventory_issuances/listview/posted'; ?>"><span class="glyphicon glyphicon-columns"></span> Posted</a></li>
													</ul>
												</li>
												<li>
													<a><span class="glyphicon glyphicon-chevron-down"></span> Inventory Returns </a>
													<ul class="nav child_menu">
														<li><a href="<?php echo HTTP_PATH.'accounting/inventory_returns/listview/for_clearing'; ?>"><span class="glyphicon glyphicon-columns"></span> For Clearing</a></li>
														<li><a href="<?php echo HTTP_PATH.'accounting/inventory_returns/listview/for_posting'; ?>"><span class="glyphicon glyphicon-columns"></span> For Approval and Posting</a></li>
														<li><a href="<?php echo HTTP_PATH.'accounting/inventory_returns/listview/posted'; ?>"><span class="glyphicon glyphicon-columns"></span> Posted</a></li>
													</ul>
												</li>
												<li>
													<a><span class="glyphicon glyphicon-chevron-down"></span> Payroll Entries</a>
													<ul class="nav child_menu">
														<li><a href="<?php echo HTTP_PATH.'accounting/payroll_entries/listview/for_clearing'; ?>"><span class="glyphicon glyphicon-columns"></span> For Clearing</a></li>
														<li><a href="<?php echo HTTP_PATH.'accounting/payroll_entries/listview/for_posting'; ?>"><span class="glyphicon glyphicon-columns"></span> For Approval and Posting</a></li>
														<li><a href="<?php echo HTTP_PATH.'accounting/payroll_entries/listview/posted'; ?>"><span class="glyphicon glyphicon-columns"></span> Posted</a></li>
													</ul>
												</li>
											<?php endif; ?>
											
											<?php if($this->Abas->checkPermissions("accounting|view_transaction_journal",false)): ?>
											<li>
												<a><span class="glyphicon glyphicon-chevron-down"></span> Transaction History </a>
												<ul class="nav child_menu">
													<li><a href="<?php echo HTTP_PATH.'accounting/transactions/'; ?>"><span class="glyphicon glyphicon-columns"></span> All Transactions</a></li>
													<li><a href="<?php echo HTTP_PATH.'accounting/journal/'; ?>"><span class="glyphicon glyphicon-columns"></span> All Journal Entries</a></li>
													<li><a href="<?php echo HTTP_PATH.'accounting/journal/view_vouchers'; ?>"><span class="glyphicon glyphicon-columns"></span> All Journal Vouchers</a></li>
												</ul>
											</li>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("accounting|view_chart_of_accounts",false)): ?>
												<li><a href="<?php echo HTTP_PATH.'accounting/chart_of_accounts/'; ?>"><i class="glyphicon glyphicon-book"></i> Chart of Accounts</a></li>
											<?php endif; ?>
											<li><a href="<?php echo HTTP_PATH.'accounting/books'; ?>" data-toggle="modal" data-target="#modalDialog"><i class="glyphicon glyphicon-book"></i> Books of Accounts</a></li>
											<li><a href="<?php echo HTTP_PATH.'accounting/general_ledger'; ?>" data-toggle="modal" data-target="#modalDialog"><i class="glyphicon glyphicon-book"></i> General Ledger</a></li>
											<?php if($this->Abas->checkPermissions("accounting|view_reports",false)): ?>
											<li><a href="<?php echo HTTP_PATH.'accounting/subsidiary_ledger/filter/'; ?>" data-toggle="modal" data-target="#modalDialog"><i class="glyphicon glyphicon-book"></i> Subsidiary Ledger</a></li>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("accounting|view_reports",false)): ?>
											<li>
												<a><span class="glyphicon glyphicon-chevron-down"></span> Accounting Reports </a>
												<ul class="nav child_menu">
													<!--<li><a href="<?php //echo HTTP_PATH.'accounting/financial_statement/filter'; ?>" data-toggle="modal" data-target="#modalDialog"><i class="glyphicon glyphicon-duplicate"></i> Financial Report</a></li>-->
													<li><a href="<?php echo HTTP_PATH.'accounting/trial_balance/filter'; ?>" data-toggle="modal" data-target="#modalDialog"><span class="glyphicon glyphicon-duplicate"></span> Trial Balance</a></li>
													<li><a href="<?php echo HTTP_PATH.'accounting/statement_of_financial_position/filter'; ?>" data-toggle="modal" data-target="#modalDialog"><span class="glyphicon glyphicon-duplicate"></span> Statement of Financial Position</a></li>
													<li><a href="<?php echo HTTP_PATH.'accounting/statement_of_income/filter'; ?>" data-toggle="modal" data-target="#modalDialog"><span class="glyphicon glyphicon-duplicate"></span> Statement of Income</a></li>
													<li><a href="<?php echo HTTP_PATH.'accounting/accounting_entries_summary_report/filter'; ?>" data-toggle="modal" data-target="#modalDialogNorm"><span class="glyphicon glyphicon-duplicate"></span> Accounting Entries Summary Report</a></li>
													<li><a href="<?php echo HTTP_PATH.'accounting/filter_summary_report/official_receipts'; ?>" data-toggle="modal" data-target="#modalDialogNorm"><span class="glyphicon glyphicon-duplicate"></span> Official Receipts Summary Report</a></li>
													<li><a href="<?php echo HTTP_PATH.'accounting/filter_summary_report/acknowledgement_receipts'; ?>" data-toggle="modal" data-target="#modalDialogNorm"><span class="glyphicon glyphicon-duplicate"></span> Acknowledgement Receipts Summary Report</a></li>
													<li><a href="<?php echo HTTP_PATH.'accounting/filter_summary_report/MSIS'; ?>" data-toggle="modal" data-target="#modalDialogNorm"><span class="glyphicon glyphicon-duplicate"></span> Material and Supplies Issuances Summary Report</a></li>
												</ul>
											</li>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("mastertables|view_suppliers", false)): ?>
											 <li><a href="<?php echo HTTP_PATH.'mastertables/suppliers'; ?>"><span class="glyphicon glyphicon-th-list"></span> Suppliers</a></li>
											 <?php endif; ?>
											 <?php if($this->Abas->checkPermissions("mastertables|view_clients", false)): ?>
												<li><a href="<?php echo HTTP_PATH.'mastertables/clients'; ?>"><span class="glyphicon glyphicon-th-list"></span> Clients</a></li>
											<?php endif; ?>
										<?php } ?>
										<?php if(SIDEMENU=="Finance"){ ?>
											<?php if($this->Abas->checkPermissions("finance|fund_approval",false)): ?>
												<li><a href="<?php echo HTTP_PATH.'finance/'; ?>"><i class="glyphicon glyphicon-check"></i> For Funding Approval</a></li>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("finance|view_banks",false)): ?>
												<li><a href="<?php echo HTTP_PATH.'finance/bank_view/'; ?>"><i class="glyphicon glyphicon-modal-window" aria-hidden="true"></i> Bank Accounts</a></li>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("finance|view_bank_recon",false)): ?>
												<li><a href="<?php echo HTTP_PATH.'finance/bank_recon_view/'; ?>"><i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Bank Reconciliation</a></li>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("finance|disbursement",false)): ?>
												<li>
													<a href="<?php echo HTTP_PATH.'finance/accounts_view/'; ?>"><i class="glyphicon glyphicon-repeat" aria-hidden="true"></i> Disbursement</a>
												</li>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("finance|view_statement_of_account",false)): ?>
												<li><a><span class="glyphicon glyphicon-chevron-down"></span> Billing</a>
													<ul class="nav child_menu">
														<li><a href="<?php echo HTTP_PATH.'statements_of_account/';?>"><span class="glyphicon glyphicon-flash"></span> Statement of Accounts</a></li>
														<li><a href="<?php echo HTTP_PATH.'statements_of_account/filter_SOA_aging_report';?>" data-toggle="modal" data-target="#modalDialogNorm"><span class="glyphicon glyphicon-time"></span> SOA Aging Report</a></li>
													</ul>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("finance|view_payments",false)): ?>
												<li><a><span class="glyphicon glyphicon-chevron-down"></span> Collection</a>
													<ul class="nav child_menu">
														<li><a href="<?php echo HTTP_PATH.'collection';?>"><span class="glyphicon glyphicon-print"></span> Payments</a></li>
														<li><a href="<?php echo HTTP_PATH.'collection/listview/PDC_monitoring';?>"><span class="glyphicon glyphicon-credit-card"></span> Post-Dated Checks Monitoring</a></li>
														<li><a href="<?php echo HTTP_PATH.'collection/listview/DCCRR';?>"><span class="glyphicon glyphicon-duplicate"></span> Daily Cash and Checks Received Report</a></li>
													</ul>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("finance|releasing",false)): ?>
												<li><a><span class="glyphicon glyphicon-chevron-down"></span> Releasing</a>
													<ul class="nav child_menu">
														<li><a href="<?php echo HTTP_PATH.'finance/check_releasing/listview';?>"><span class="glyphicon glyphicon-export"></span> Check Releasing</a></li>
														<li><a href="<?php echo HTTP_PATH.'finance/check_releasing/report';?>" data-toggle="modal" data-target="#modalDialogNorm"><span class="glyphicon glyphicon-file"></span> Released Checks Report</a></li>
													</ul>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("mastertables|view_suppliers", false)): ?>
											 <li><a href="<?php echo HTTP_PATH.'mastertables/suppliers'; ?>"><span class="glyphicon glyphicon-th-list"></span> Suppliers</a></li>
											 <?php endif; ?>
											<?php if($this->Abas->checkPermissions("mastertables|view_clients", false)): ?>
												<li><a href="<?php echo HTTP_PATH.'mastertables/clients'; ?>"><span class="glyphicon glyphicon-th-list"></span> Clients</a></li>
											<?php endif; ?>
										<?php } ?>

										<?php if(SIDEMENU=="Human Resources"){ ?>
											<li>
												<a href="<?php echo HTTP_PATH."hr/employees"; ?>">
													<span class="glyphicon glyphicon-user"></span> Employees
												</a>
											</li>
											<li><a href="<?php echo HTTP_PATH."whr/"; ?>">
												<span class="glyphicon glyphicon-user"></span> Warehouse Staff</a>
											</li>
											
											<?php if($this->Abas->checkPermissions("mastertables|view_departments",false)): ?>
												<li><a href="<?php echo HTTP_PATH."mastertables/departments/"; ?>" target="_new"><span class="glyphicon glyphicon-list"></span> Departments</a></li>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("mastertables|view_positions",false)): ?>
												<li><a href="<?php echo HTTP_PATH."mastertables/positions/"; ?>" target="_new"><span class="glyphicon glyphicon-list"></span> Positions</a></li>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("mastertables|view_salary_grades",false)): ?>
												<li><a href="<?php echo HTTP_PATH."mastertables/salary_grades/"; ?>" target="_new"><span class="glyphicon glyphicon-list"></span> Salary Grades</a></li>
											<?php endif; ?>
											<?php if($this->Abas->checkPermissions("mastertables|view_leave_credit_codes",false)){ ?>
												<li><a href="<?php echo HTTP_PATH."mastertables/leave_credit_codes/"; ?>" target="_new"><span class="glyphicon glyphicon-list"></span> Leave Credit Codes</a></li>
											<?php } ?>
											
											<!--<li><a href="<?php echo HTTP_PATH."hr/public_users"; ?>"><span class="glyphicon glyphicon-user"></span> Crew Users</a></li>-->
											
											<?php if($this->Abas->checkPermissions("human_resources|reports",false)): ?>
												<li><a><span class="glyphicon glyphicon-chevron-down"></span> HR Reports</a>
													<ul class="nav child_menu">
														<li><a href="<?php echo HTTP_PATH."hr/employee_report/"; ?>" data-toggle="modal" data-target="#modalDialog"><span class="glyphicon glyphicon-duplicate"></span> Employees Summary Report</a></li>
													<?php if($this->Abas->checkPermissions("human_resources|add",false) || $this->Abas->checkPermissions("human_resources|edit",false)): ?>
													<li><a href="<?php echo HTTP_PATH."hr/employees_for_awol/"; ?>"><span class="glyphicon glyphicon-remove"></span> Employees For AWOL</a></li>
													<?php endif; ?>
													<?php if($this->Abas->checkPermissions("human_resources|salary_viewing",false) || $this->Abas->checkPermissions("human_resources|salary_editing",false)): ?>
														<li><a href="<?php echo HTTP_PATH."hr/bonus_report/filter/"; ?>" data-toggle="modal" data-target="#modalDialogNorm"><span class="glyphicon glyphicon-check"></span> Bonus/13th Month Pay Report</a></li>
													<?php endif; ?>
													<li><a href="<?php echo HTTP_PATH."hr/crew_movement_summary/"; ?>" ><span class="glyphicon glyphicon-list-alt"></span> Crew Movement Summary Report</a></li>
												</ul>
												</li>
											<?php endif; ?>
										<?php } ?>
										<?php if(SIDEMENU=="Payroll"){ ?>
											<?php if($this->Abas->checkPermissions("payroll|reports", false)): ?>
												<li><a><span class="glyphicon glyphicon-chevron-down"></span> Payroll Reports </a>
												<ul class="nav child_menu">
													<?php if($_SESSION['abas_login']['role']=='Administrator' || $_SESSION['abas_login']['role']=='Payroll' || $_SESSION['abas_login']['role']=='Human Resources'){ ?>
													<li>
														<a href='<?php echo HTTP_PATH; ?>payroll/generate_reports/alphalist' data-toggle='modal' data-target='#modalDialogNorm'>
														<span class="glyphicon glyphicon-duplicate"></span> Alphalist
														</a>
													</li>
													<li>
														<a href='<?php echo HTTP_PATH; ?>payroll/generate_reports/annualization' data-toggle='modal' data-target='#modalDialogNorm'>
														<span class="glyphicon glyphicon-duplicate"></span> Annualization
														</a>
													</li>
													<li>
														<a href='<?php echo HTTP_PATH; ?>payroll/generate_reports/payroll' data-toggle='modal' data-target='#modalDialogNorm'>
														<span class="glyphicon glyphicon-duplicate"></span> Employee Payroll Summary
														</a>
													</li>
													<?php } ?>
													<li>
														<a href='<?php echo HTTP_PATH; ?>payroll/generate_reports/loan' data-toggle='modal' data-target='#modalDialogNorm'>
														<span class="glyphicon glyphicon-duplicate"></span> Loan Summary
														</a>
													</li>
													<li>
														<a href='<?php echo HTTP_PATH; ?>payroll/generate_reports/contribution' data-toggle='modal' data-target='#modalDialogNorm'>
														<span class="glyphicon glyphicon-duplicate"></span> Employee Contribution Summary
														</a>
													</li>
												</ul></li>
											<?php endif; ?>
										<?php } ?>
										<?php if(SIDEMENU=="Inventory"){ ?>
											<!--<li><a href="<?php //echo HTTP_PATH.'inventory/item_list'; ?>"><span class="glyphicon glyphicon-list-alt"></span> Inventory List (old)</a></li>
											<?php //if($this->Abas->checkPermissions("inventory|transaction_history",false)): ?>
											<li><a><span class="glyphicon glyphicon-chevron-down"></span> Transaction History</a>
												<ul class="nav child_menu">
													<li><a href="<?php //echo HTTP_PATH.'inventory/transaction_history/delivery'; ?>"><span class="glyphicon glyphicon-import"></span> Receiving</a></li>
													<li><a href="<?php //echo HTTP_PATH.'inventory/transaction_history/issuance'; ?>"><span class="glyphicon glyphicon-export"></span> Issuance</a></li>
													<li><a href="<?php //echo HTTP_PATH.'inventory/transaction_history/transfer'; ?>"><span class="glyphicon glyphicon-resize-full"></span> Transfer</a></li>
													<li><a href="<?php //echo HTTP_PATH.'inventory/transaction_history/return'; ?>"><span class="glyphicon glyphicon-download-alt"></span> Return</a></li>
												</ul>
											</li>
											<?php //endif; ?>-->
											<li><a href="<?php echo HTTP_PATH.'inventory/items/listview'; ?>"><span class="glyphicon glyphicon-list-alt"></span> Company Inventory</a></li>
											<li><a href="<?php echo HTTP_PATH.'inventory/receiving/listview'; ?>"><span class="glyphicon glyphicon-import"></span> Receiving Reports</a></li>
											<li><a href="<?php echo HTTP_PATH.'inventory/issuance/listview'; ?>"><span class="glyphicon glyphicon-export"></span> Material & Supplies Issuance Slips</a></li>
											<li><a href="<?php echo HTTP_PATH.'inventory/transfer/listview'; ?>"><span class="glyphicon glyphicon-resize-full"></span> Materials Transfer Requests</a></li>
											<li><a href="<?php echo HTTP_PATH.'inventory/return/listview'; ?>"><span class="glyphicon glyphicon-download-alt"></span> Material & Supplies Return Slip</a></li>
											<!--<li><a href="<?php //echo HTTP_PATH.'inventory/items/conversion_history'; ?>" data-toggle='modal' data-target='#modalDialogNorm'><span class="glyphicon glyphicon-time"></span> UOM Conversion History</a></li>-->
											<li><a href="<?php echo HTTP_PATH.'inventory/notice_of_discrepancy/listview'; ?>"><span class="glyphicon glyphicon-alert"></span> Notice of Discrepancy</a></li>
											<li><a href="<?php echo HTTP_PATH.'inventory/audit/listview'; ?>"><span class="glyphicon glyphicon-calendar"></span> Inventory Audit</a></li>
											<li><a href="<?php echo HTTP_PATH.'inventory/monthly_inventory_report/listview'; ?>"><span class="glyphicon glyphicon-duplicate"></span> Monthly Inventory Reports</a></li>
											<li><a><span class="glyphicon glyphicon-chevron-down"></span> Stock Reports</a>
												<ul class="nav child_menu">
													<li><a href="<?php echo HTTP_PATH.'inventory/stock_card/filter'; ?>" data-toggle='modal' data-target='#modalDialogNorm'><span class="glyphicon glyphicon-th-large"></span> Stock Card</a></li>
													<li><a href="<?php echo HTTP_PATH.'inventory/stock_in_out_summary/filter'; ?>" data-toggle='modal' data-target='#modalDialogNorm'><span class="glyphicon glyphicon-th-large"></span> Stock In/Out Summary</a></li>
													<li><a href="<?php echo HTTP_PATH.'inventory/dead_stocks_summary/filter'; ?>" data-toggle='modal' data-target='#modalDialogNorm'><span class="glyphicon glyphicon-th-large"></span> Dead Stocks Summary</a></li>
													<li><a href="<?php echo HTTP_PATH.'inventory/stock_level/filter'; ?>" data-toggle='modal' data-target='#modalDialogNorm'><span class="glyphicon glyphicon-th-large"></span> Stock Level</a></li>
												</ul>
											</li>
										<?php } ?>
										<?php if(SIDEMENU=="Operations"){ ?>
											<?php if($this->Abas->checkPermissions("operations|view_contract",FALSE)){?>
												<li><a href="<?php echo HTTP_PATH.'operation/'; ?>"><span class="glyphicon glyphicon-file"></span> Contracts</a></li>
											<?php } ?>
											<?php if($this->Abas->checkPermissions("operations|view_service_order",FALSE)){?>
												<li><a href="<?php echo HTTP_PATH.'operation/service_order/listview'; ?>"><span class="glyphicon glyphicon-time"></span> Service Orders</a></li>
											<?php } ?>
											<?php if($this->Abas->checkPermissions("operations|view_out_turn_summary",FALSE)){?>
												<li><a href="<?php echo HTTP_PATH.'operation/out_turn_summary/listview'; ?>"><span class="glyphicon glyphicon-indent-right"></span> Out-Turn Summary</a></li>
											<?php } ?>
											<li>
												<a><span class="glyphicon glyphicon-chevron-down"></span> Operations Reports</a>
												<ul class="nav child_menu">
													<li><a href="<?php echo HTTP_PATH.'operation/voyage_report/filter'; ?>" data-toggle='modal' data-target='#modalDialogNorm'><span class="glyphicon glyphicon-duplicate"></span> Voyage Report</a></li>
													<li><a href="<?php echo HTTP_PATH.'operation/out_turn_summary_aging_report/filter'; ?>" data-toggle='modal' data-target='#modalDialogNorm'><span class="glyphicon glyphicon-duplicate"></span> Out-turn Summary Aging Report</a></li>
												</ul>
											</li>
											<li>	
												<a><span class="glyphicon glyphicon-chevron-down"></span> Vessels</a>
												<ul class="nav child_menu">
													<li><a href="<?php echo HTTP_PATH.'vessels/'; ?>"><span class="glyphicon glyphicon-list"></span> Vessel Profiles</a></li>
													<?php
														if($this->Abas->checkPermissions("operations|view_vessel_certificates",false)){ ?>
															<li><a href="<?php echo HTTP_PATH.'vessels/vessel_certificates'; ?>"><span class="glyphicon glyphicon-bookmark"></span> Vessel Certificates</a></li>
													<?php } ?>
													<li><a href="<?php echo HTTP_PATH.'operation/vessel_monitoring/'; ?>"><span class="glyphicon glyphicon-map-marker"></span> Vessel Locator</a></li>
													<?php if($this->Abas->checkPermissions("operations|phone_registration", false)): ?>
														<li><a href="<?php echo HTTP_PATH.'operation/tool_registry/'; ?>" data-toggle="modal" data-target="#modalDialog"><span class="glyphicon glyphicon-phone"></span> Mobile Number Registry</a></li>
													<?php endif; ?>
												</ul>
											</li>
											<li>	
												<a><span class="glyphicon glyphicon-chevron-down"></span> Trucks</a>
												<ul class="nav child_menu">
													<li><a href="<?php echo HTTP_PATH.'operation/truck_profiles/listview'; ?>"><span class="glyphicon glyphicon-list"></span> Truck Profiles</a></li>
												</ul>
											</li>
										<?php } ?>
										<?php if(SIDEMENU=="Asset Management"){ ?>
											<li><a><span class="glyphicon glyphicon-chevron-down"></span> Company Assets</a>
													<ul class="nav child_menu">
														<?php if($this->Abas->checkPermissions("asset_management|view_vessels",FALSE)){ ?>
															<li><a href="<?php echo HTTP_PATH.'mastertables/vessels'; ?>"><span class="glyphicon glyphicon-list"></span> Vessel List</a></li>
														<?php } ?>
														<?php if($this->Abas->checkPermissions("asset_management|view_trucks",FALSE)){ ?>
															<li><a href="<?php echo HTTP_PATH.'mastertables/trucks'; ?>"><span class="glyphicon glyphicon-list"></span> Truck List</a></li>
														<?php } ?>
														<?php if($this->Abas->checkPermissions("asset_management|view_fixed_asset_register",FALSE)){ ?>
														<li><a href="<?php echo HTTP_PATH.'Asset_Management/fixed_asset_register/listview'; ?>"><span class="glyphicon glyphicon-th-list"></span> Fixed Asset Register</a>
														</li>
														<?php } ?>
														<?php if($this->Abas->checkPermissions("asset_management|view_accountability_form",FALSE)){ ?>
														<li><a href="<?php echo HTTP_PATH.'Asset_Management/accountability_form/listview'; ?>"><span class="glyphicon glyphicon-text-background"></span> Accountability Forms</a>
														</li>
														<?php } ?>
														<?php if($this->Abas->checkPermissions("asset_management|view_disposal_slip",FALSE)){ ?>
														<li><a href="<?php echo HTTP_PATH.'Asset_Management/disposal_slip/listview'; ?>"><span class="glyphicon glyphicon-trash"></span> Disposal Slips</a>
														</li>
														<?php } ?>
													</ul>
												</li>
											<li>
												<a><span class="glyphicon glyphicon-chevron-down"></span> Maintenance Schedules</a>
												<ul class="nav child_menu">
													<?php if($this->Abas->checkPermissions("asset_management|view_vessels",FALSE)){?>
													<li><a href="<?php echo HTTP_PATH. 'Asset_Management/listview/schedule_logs/Vessel'?>"></span><span class="glyphicon glyphicon-menu-right"></span> Dry-dock</a></li>
													<?php }; ?>
													<?php if($this->Abas->checkPermissions("asset_management|view_trucks",FALSE)){?>
													<li><a href="<?php echo HTTP_PATH. 'Asset_Management/listview/schedule_logs/Truck'?>"></span><span class="glyphicon glyphicon-menu-right"></span> Motorpool</a></li>
													<?php }; ?>
												</ul>
											</li>
											<li>
												<a>
													<span class="glyphicon glyphicon-chevron-down"></span> Repairs
												</a>
												<ul class="nav child_menu">
													<li>
														<a><span class="glyphicon glyphicon-chevron-down"></span> Requests</a>
														<ul class="nav child_menu">
															<?php if($this->Abas->checkPermissions("asset_management|view_vessels",FALSE)){?>
															<li><a href="<?php echo HTTP_PATH.'Asset_Management/listview/WO';?>"><span class="glyphicon glyphicon-edit"></span> Vessel Work Order</a></li>
															<?php }; ?>
															<?php if($this->Abas->checkPermissions("asset_management|view_trucks",FALSE)){?>
															<li><a href="<?php echo HTTP_PATH.'Asset_Management/listview/TRMRF';?>"><span class="glyphicon glyphicon-edit"></span> Truck Repairs and Maintenance Report Form</a></li>
															<?php }; ?>
														</ul>
													<li>
													<li>
														<a><span class="glyphicon glyphicon-chevron-down"></span> Survey Forms</a>
														<ul class="nav child_menu">
															<?php if($this->Abas->checkPermissions("asset_management|view_vessels",FALSE)){?>
															<li><a href="<?php echo HTTP_PATH.'Asset_Management/listview/SRMSF';?>"><span class="glyphicon glyphicon-edit"></span> Ship Repairs and Maintenance Survey Form</a></li>
															<?php }; ?>
															<?php if($this->Abas->checkPermissions("asset_management|view_trucks",FALSE)){?>
															<li><a href="<?php echo HTTP_PATH.'Asset_Management/listview/MTDE';?>"><span class="glyphicon glyphicon-edit"></span> Motorpool Truck Diagnostic Evaluation</a></li>
															<?php }; ?>
														</ul>
													<li>
													<li>
														<a><span class="glyphicon glyphicon-chevron-down"></span> Evaluation Items</a>
														<ul class="nav child_menu">
															<?php if($this->Abas->checkPermissions("asset_management|view_vessels",FALSE)){?>
															<li><a href="<?php echo HTTP_PATH. 'Asset_Management/listview/evaluation_items/Vessel'?>"></span><span class="glyphicon glyphicon-menu-right"></span> For Vessels</a></li>
															<?php }; ?>
															<?php if($this->Abas->checkPermissions("asset_management|view_trucks",FALSE)){?>
															<li><a href="<?php echo HTTP_PATH. 'Asset_Management/listview/evaluation_items/Truck'?>"></span><span class="glyphicon glyphicon-menu-right"></span> For Trucks</a></li>
															<?php }; ?>
														</ul>
													<li>
												</ul>
											</li>
											<li>
												<a><span class="glyphicon glyphicon-chevron-down"></span> Bill Of Materials</a>
												<ul class="nav child_menu">
													<?php if($this->Abas->checkPermissions("asset_management|view_vessels",FALSE)){?>
													<li><a href="<?php echo HTTP_PATH. 'Asset_Management/listview/BOM/Vessel'?>"></span><span class="glyphicon glyphicon-menu-right"></span> For Vessels</a></li>
													<?php }; ?>
													<?php if($this->Abas->checkPermissions("asset_management|view_trucks",FALSE)){?>
													<li><a href="<?php echo HTTP_PATH. 'Asset_Management/listview/BOM/Truck'?>"></span><span class="glyphicon glyphicon-menu-right"></span> For Trucks</a></li>
													<?php }; ?>
												</ul>
											</li>
										<?php } ?>
										<?php if(SIDEMENU=="Users"){ ?>
											<li>
												<a href="<?php echo HTTP_PATH.'users/'; ?>">
													<i class="glyphicon glyphicon-user"></i> User Accounts
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH.'users/summary_report/filter'; ?>" data-toggle="modal" data-target="#modalDialog">
													<i class="glyphicon glyphicon-duplicate"></i> Users Summary Report
												</a>
											</li>
										<?php } ?>
										<?php if(SIDEMENU=="Purchasing"){ ?>
											<li>
												<a href="<?php echo HTTP_PATH ?>purchasing/requisition/add" data-toggle="modal" data-target="#modalDialog">
													<i class="glyphicon glyphicon-plus"></i> <span class='hidden-sm hidden-xs'>Add Requisition </span> 
												</a>
											</li>
											<li>
												<a><span class="glyphicon glyphicon-chevron-down"></span> Active Purchases</a>
												<ul class="nav child_menu">
													<li>
														<a href="<?php echo HTTP_PATH ?>purchasing/requisition/?"><span class="glyphicon glyphicon-list"></span> Requisitions</a>
													</li>
													<li>
														<a><span class="glyphicon glyphicon-chevron-down"></span> Canvass</a>
														<ul class="nav child_menu">
															<li><a href="<?php echo HTTP_PATH ?>purchasing/canvass/listview/unapproved"><span class="glyphicon glyphicon-minus-sign"></span> View Unapproved</a></li>
															<li><a href="<?php echo HTTP_PATH; ?>purchasing/canvass/listview/approved"><span class="glyphicon glyphicon-ok-sign"></span> View Approved</a></li>
															<li><a href="<?php echo HTTP_PATH; ?>purchasing/canvass/filter" data-toggle="modal" data-target="#modalDialog"><span class="glyphicon glyphicon-duplicate"></span> Reports</a></li>
														</ul>
													</li>
													<li>
														<a><span class="glyphicon glyphicon-chevron-down"></span> Purchase Orders</a>
														<ul class="nav child_menu">
															<li><a href="<?php echo HTTP_PATH ?>purchasing/purchase_order"><span class="glyphicon glyphicon-info-sign"></span> View All</a></li>
															<li><a href="<?php echo HTTP_PATH ?>purchasing/purchase_order/view_unapproved"> <span class="glyphicon glyphicon-minus-sign"></span> View Unapproved</a></li>
															<li><a href="<?php echo HTTP_PATH; ?>purchasing/purchase_order/view_approved"><span class="glyphicon glyphicon-ok-sign"></span> View Approved</a></li>
															<li><a href="<?php echo HTTP_PATH; ?>purchasing/purchase_order_report/filter" data-toggle="modal" data-target="#modalDialog"><span class="glyphicon glyphicon-duplicate"></span> Reports</a></li>
														</ul>
													</li>
													<li>
														<a><span class="glyphicon glyphicon-chevron-down"></span> Job Orders</a>
														<ul class="nav child_menu">
															<li><a href="<?php echo HTTP_PATH ?>purchasing/job_order/listview/all"><span class="glyphicon glyphicon-info-sign"></span> View All</a></li>
															<li><a href="<?php echo HTTP_PATH ?>purchasing/job_order/listview/unapproved"><span class="glyphicon glyphicon-minus-sign"></span> View Unapproved</a></li>
															<li><a href="<?php echo HTTP_PATH; ?>purchasing/job_order/listview/approved"><span class="glyphicon glyphicon-ok-sign"></span> View Approved</a></li>
															<li><a href="<?php echo HTTP_PATH; ?>purchasing/job_order/filter" data-toggle="modal" data-target="#modalDialog"><span class="glyphicon glyphicon-duplicate"></span> Reports</a></li>
														</ul>
													</li>
												</ul>
											</li>
											<?php if($this->Abas->checkPermissions("mastertables|view_suppliers", false)): ?>
											<li>
												<a><span class="glyphicon glyphicon-chevron-down"></span> Suppliers</a>
												<ul class="nav child_menu">
													<li>
														<a href="<?php echo HTTP_PATH ?>mastertables/suppliers/add" data-toggle="modal" data-target="#modalDialog">
															<span class="glyphicon glyphicon-plus"></span> Add Supplier
														</a>
													</li>
													<li><a href="<?php echo HTTP_PATH."mastertables/suppliers"; ?>" target="_new"><span class="glyphicon glyphicon-list"></span> Suppliers List</a></li>
												</ul>
											</li>
											<?php endif; ?>
											
											<?php if($this->Abas->checkPermissions("inventory|view", false)): ?>
											<li>
												<a><span class="glyphicon glyphicon-chevron-down"></span> Inventory</a>
												<ul class="nav child_menu">
													<li>
														<a href="<?php echo HTTP_PATH ?>inventory/items/add" data-toggle="modal" data-target="#modalDialogNorm">
															<i class="glyphicon glyphicon-plus"></i> <span class='hidden-sm hidden-xs'>Add</span> Inventory Item
														</a>
													</li>
													<li><a href="<?php echo HTTP_PATH."inventory/items/listview"; ?>" target="_new"><span class="glyphicon glyphicon-list"></span> Inventory List</a></li>
												</ul>
											</li>
											<?php endif; ?>
											
											<li>
												<a><span class="glyphicon glyphicon-chevron-down"></span> Vessel</a>
												<ul class="nav child_menu">
													<li><a href="<?php echo HTTP_PATH.'vessels/'; ?>"><span class="glyphicon glyphicon-list"></span> Vessel Profiles</a></li>
													<li><a href="<?php echo HTTP_PATH.'purchasing/vessel_purchases/filter'; ?>" data-toggle="modal" data-target="#modalDialog"><span class="glyphicon glyphicon-duplicate"></span> Purchase Report</a></li>
												</ul>
											</li>

										<?php } ?>
										
										<?php if(SIDEMENU=="Master Tables"){ ?>
											<li><a href="<?php echo HTTP_PATH.'mastertables/companies/'; ?>"><span class="glyphicon glyphicon-th-list"></span> Companies</a></li>
											<li><a href="<?php echo HTTP_PATH.'mastertables/departments'; ?>"><span class="glyphicon glyphicon-th-list"></span> Departments</a></li>
											<li><a href="<?php echo HTTP_PATH.'mastertables/positions/'; ?>"><span class="glyphicon glyphicon-th-list"></span> Positions</a></li>
											<li><a href="<?php echo HTTP_PATH.'mastertables/salary_grades/'; ?>"><span class="glyphicon glyphicon-th-list"></span> Salary Grades</a></li>
											<li><a href="<?php echo HTTP_PATH.'mastertables/tax_codes/'; ?>"><span class="glyphicon glyphicon-th-list"></span> Tax Codes</a></li>
											<li><a href="<?php echo HTTP_PATH.'mastertables/suppliers/'; ?>"><span class="glyphicon glyphicon-th-list"></span> Suppliers</a></li>
											<li><a href="<?php echo HTTP_PATH.'mastertables/clients/'; ?>"><span class="glyphicon glyphicon-th-list"></span> Clients</a></li>
											<li><a href="<?php echo HTTP_PATH.'mastertables/vessels/'; ?>"><span class="glyphicon glyphicon-th-list"></span> Vessels</a></li>
											<li><a href="<?php echo HTTP_PATH.'mastertables/trucks/'; ?>"><span class="glyphicon glyphicon-th-list"></span> Trucks</a></li>
										<?php } ?>
									<?php endif; ?>
									<li>
										<a><span class="glyphicon glyphicon-chevron-down"></span> Corporate Services</a>
										<ul class="nav child_menu">
											<li>
												<a href="<?php echo HTTP_PATH."Corporate_Services/purchase_requests/listview/"; ?>" class='force-pageload'>
													<span class="glyphicon glyphicon-edit"></span> Materials/Services Requests
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH."Corporate_Services/request_for_payment/listview/"; ?>" class='force-pageload'>
													<span class="glyphicon glyphicon-edit"></span> Request for Payments
												</a>
											</li>
											<?php if($this->Abas->getLeaveApprover($hr_id)) { ?>
											<li>
												<a href="<?=HTTP_PATH."Corporate_Services/approval"?>">
													<span class="glyphicon glyphicon-ok"></span> Application Approval (<?=$for_approval_count?>)
												</a>
											</li>
											<?php } ?>
											<li>
												<a href="<?=HTTP_PATH."Corporate_Services/leave"?>">
													<span class="glyphicon glyphicon-duplicate"></span> Leave Application 
												</a>
											</li>
											<li>
												<a href="<?=HTTP_PATH."Corporate_Services/overtime"?>">
													<span class="glyphicon glyphicon-duplicate"></span> Overtime Application
												</a>
											</li>
											<!--li>
												<a href="<?=HTTP_PATH."Corporate_Services/undertime"?>">
													<span class="glyphicon glyphicon-duplicate"></span> Undertime Application
												</a>
											</li>-->

											<?php if($this->Abas->checkPermissions("Corporate_Services|view_budget",false)): ?>
												<li><a href="<?=HTTP_PATH.'Corporate_Services/budget_view'; ?>"><i class="glyphicon glyphicon-check"></i> Budget</a></li>
											<?php endif; ?>
										</ul>
									</li>
									<li>
										<a><span class="glyphicon glyphicon-chevron-down"></span> Company Manuals</a>
										<ul class="nav child_menu">
											<li>
												<a href="<?php echo HTTP_PATH."../assets/downloads/company_manuals/admin/"; ?>" class='exclude-pageload' target="_blank">
													<span class="glyphicon glyphicon-book"></span> Admin
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH."../assets/downloads/company_manuals/human_resources_and_payroll/"; ?>" class='exclude-pageload' target="_blank">
													<span class="glyphicon glyphicon-book"></span> Human Resources and Payroll
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH."../assets/downloads/company_manuals/purchasing/"; ?>" class='exclude-pageload' target="_blank">
													<span class="glyphicon glyphicon-book"></span> Purchasing
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH."../assets/downloads/company_manuals/inventory/"; ?>" class='exclude-pageload' target="_blank">
													<span class="glyphicon glyphicon-book"></span> Inventory
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH."../assets/downloads/company_manuals/accounting/"; ?>" class='exclude-pageload' target="_blank">
													<span class="glyphicon glyphicon-book"></span> Accounting
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH."../assets/downloads/company_manuals/finance/"; ?>" class='exclude-pageload' target="_blank">
													<span class="glyphicon glyphicon-book"></span> Finance
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH."../assets/downloads/company_manuals/marketing_and_operations/"; ?>" class='exclude-pageload' target="_blank">
													<span class="glyphicon glyphicon-book"></span> Marketing and Operations
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH."../assets/downloads/company_manuals/asset_management/"; ?>" class='exclude-pageload' target="_blank">
													<span class="glyphicon glyphicon-book"></span> Asset Management
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH."../assets/downloads/company_manuals/compliance_management/"; ?>" class='exclude-pageload' target="_blank">
													<span class="glyphicon glyphicon-book"></span> Compliance Management
												</a>
											</li>
											<li>
												<a href="<?php echo HTTP_PATH."../assets/downloads/company_manuals/it_solutions/"; ?>" class='exclude-pageload' target="_blank">
													<span class="glyphicon glyphicon-book"></span> IT Services
												</a>
											</li>
										</ul>
									</li>
									<li>
										<a><span class="glyphicon glyphicon-chevron-down"></span> IT Services</a>
										<ul class="nav child_menu">
											<li>
												<a href="http://support.avegabros.org/" data-trigger="hover" data-toggle="popover" data-content="Remember to attach a signed IT request form to your ticket!" target="_blank"><span class="glyphicon glyphicon-tags"></span>  Ticketing System
												</a>
											</li>
											<li><a href="<?php echo LINK."assets/downloads/it/IRAAF.xls"; ?>" id="chat-sidebar-item"><span class="glyphicon glyphicon-file"></span> Download IT Request Form</a></li>
										</ul>
									</li>
									<!--<li><a href="http://riot.im" id="chat-sidebar-item" target="_blank"><i class="glyphicon glyphicon-comment"></i> Chat</a></li>-->
									<li><a href="<?php echo HTTP_PATH."home/logout"; ?>"><i class="glyphicon glyphicon-off"></i> Logout</a></li>
								</ul>
							</div>
						</div>
						<div class="sidebar-footer hidden-small">
							<a class="btn btn-xs btn-dark" href="mailto:it@avegabros.org" target="_blank"><h6>Powered by AVegaIT © <?php echo date('Y');?></h6></a>
						</div>
					</div>
				</div>
				<div class="top_nav">
					<div class="nav_menu">
						<div class="nav toggle">
							<a id="menu_toggle" class="exclude-pageload"><i class="glyphicon glyphicon-menu-hamburger"></i></a>
						</div>
						<nav>
							<?php if(isset($_SESSION['abas_login'])): ?>
							<ul class="nav navbar-nav navbar-right">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> <span class="hidden-xs">Account</span></a>
									<ul class="dropdown-menu">
										<br>
										<li><center>User: <b><?php echo $_SESSION['abas_login']['fullname']."<br>";?></b>Role: <b><?php echo $_SESSION['abas_login']['role'];?></b><br>Location: <b><?php echo $_SESSION['abas_login']['user_location'];?></b></center></li>
										<li role="separator" class="divider"></li>
										<li><a href="<?php echo HTTP_PATH.'home/account_details'; ?>" class="" data-toggle="modal" data-target="#modalDialogNorm"  data-backdrop="static"><span class="glyphicon glyphicon-user"></span> Edit Details</a></li>
										<li><a href="https://mail.avegabros.com:2096" target="_blank"><span class="glyphicon glyphicon-envelope"></span> Webmail</span></a></li>
										<li role="separator" class="divider"></li>
										<li><a href="<?php echo HTTP_PATH."home/logout"; ?>"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
									</ul>
								</li>
								
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-th-large"></span> <span class="hidden-xs">Subsystems</span></a>
									<ul class="dropdown-menu">
										<?php if($this->Abas->checkPermissions("manager|view", false)): ?>
											<li><a href="<?php echo HTTP_PATH."manager"; ?>"><span class="glyphicon glyphicon-eye-open"></span> Manager's Dashboard</a></li>
										<?php endif; ?>
										<?php if($this->Abas->checkPermissions("human_resources|view", false)): ?>
											<li><a href="<?php echo HTTP_PATH."hr"; ?>"><span class="glyphicon glyphicon-user"></span> Human Resources</a></li>
										<?php endif; ?>
										<?php if($this->Abas->checkPermissions("payroll|view", false)): ?>
											<li><a href="<?php echo HTTP_PATH."payroll"; ?>"><span class="glyphicon glyphicon-list-alt"></span> Payroll</a></li>
										<?php endif; ?>
										<?php if($this->Abas->checkPermissions("purchasing|view_requests", false)): ?>
											<li><a href="<?php echo HTTP_PATH."purchasing"; ?>"><span class="glyphicon glyphicon-shopping-cart"></span> Purchasing</a></li>
										<?php endif; ?>
										<?php if($this->Abas->checkPermissions("inventory|view", false)): ?>
											<li><a href="<?php echo HTTP_PATH."inventory"; ?>"><span class="glyphicon glyphicon-transfer"></span> Inventory</a></li>
										<?php endif; ?>
										<?php if($this->Abas->checkPermissions("accounting|view", false)): ?>
											<li><a href="<?php echo HTTP_PATH."accounting"; ?>"><span class="glyphicon glyphicon-book"></span> Accounting</a></li>
										<?php endif; ?>
										<?php if($this->Abas->checkPermissions("operations|view", false)): ?>
											<li><a href="<?php echo HTTP_PATH."operation"; ?>"><span class="glyphicon glyphicon-move"></span> Marketing & Operations</a></li>
										<?php endif; ?>
										<?php if($this->Abas->checkPermissions("finance|view", false)): ?>
											<li><a href="<?php echo HTTP_PATH."finance"; ?>"><span class="glyphicon glyphicon-stats"></span> Finance</a></li>
										<?php endif; ?>
										<?php if($this->Abas->checkPermissions("asset_management|view", false)): ?>
											<li><a href="<?php echo HTTP_PATH."Asset_Management"; ?>"><span class="glyphicon glyphicon-wrench"></span> Asset Management</a></li>
										<?php endif; ?>
										<?php if($this->Abas->checkPermissions("compliance_management|view", false)): ?>
											<li><a href=""><span class="glyphicon glyphicon-thumbs-up"></span> Compliance Management</a></li>
										<?php endif; ?>
										<li><a href=""><span class="glyphicon glyphicon-flag"></span> Corporate Services</a></li>
										<li ><a href="" ><span class="glyphicon glyphicon-fire"></span> IT Services</a></li>
									</ul>
								</li>
								<li><a href="<?php echo HTTP_PATH ?>"><span class="glyphicon glyphicon-home"></span> <span class="hidden-xs">Home</span></a></li>
							<li class="dropdown"><a href="#"><span id="clockbox" class="hidden-xs"></span></a></li>
							</ul>
							<?php endif; ?>
						</nav>
					</div>
				</div>
				<div class="right_col" role="main">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<h2><b><?php 
											if(SIDEMENU!=null){
												if(SIDEMENU=='Home'){
													echo  SIDEMENU; 
												}elseif(SIDEMENU=="Manager's Dashboard"){
													echo  SIDEMENU;	
												}elseif(SIDEMENU=="Master Tables"){
													echo  SIDEMENU;
												}elseif(SIDEMENU=="Users"){
													echo  "Administrator";	
												}elseif(SIDEMENU=="Administrator"){
													echo  SIDEMENU;	
												}elseif(SIDEMENU=="Corporate Services"){
													echo  SIDEMENU;	
												}elseif(SIDEMENU=="Operations"){
													echo  "Marketing & ".SIDEMENU. " Subsystem";	
												}else{
													echo  SIDEMENU." Subsystem";
												}
											}else{
												echo "AVega Business Automation System"; 
											}
										?></b></h2>
									<div class="clearfix">&nbsp;</div>
								</div>
								<div class="x_content">
									<?php
										###################
										###################
										###             ###
										###   content   ###
										###    here!    ###
										###             ###
										###################
										###################
										if(!include($viewfile)) echo "The page you are requesting cannot be found. Please contact your administrator.";
										###################
										###################
										###             ###
										###   content   ###
										###    here!    ###
										###             ###
										###################
										###################
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="modalDialog" class="modal fade">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<p class="loading-text">Loading...</p>
				</div>
			</div>
		</div>
		<div id="modalDialogNorm" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<p class="loading-text">Loading...</p>
				</div>
			</div>
		</div>
        <div id="modalDialogSmall" class="modal fade modal-sm">
			<div class="modal-dialog">
				<div class="modal-content">
					<p class="loading-text">Loading...</p>
				</div>
			</div>
		</div>
        <div id="modalDialogLarge" class="modal fade modal-lg">
			<div class="modal-dialog">
				<div class="modal-content">
					<p class="loading-text">Loading...</p>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		
	</script>
	<script language="javascript" type="text/javascript">
		toastr.options.timeOut = <?php echo $current_login['notification_timeout']!=null ? $current_login['notification_timeout'] : 10000; ?>;
		toastr.options = {
			  "debug": false,
			  "positionClass": "toast-bottom-right",
			}
		$('[data-toggle="popover"]').popover();
		<?php $this->Abas->display_messages(); ?>
		$(document).ready(function() {
			$('body').on('hidden.bs.modal', '.modal', function () {
				$(this).removeData('bs.modal');
				$(".modal-content").html("<p class='loading-text'>Loading ...</p>");
				window.reload();
			});
		});
		
		function showNotifications() {
			$.ajax({
				url: "<?php echo HTTP_PATH; ?>home/ajaxNotifs/1",
				cache: false,
				dataType: 'json',
				success: function(html){
					for (var key in html) {
						if (html.hasOwnProperty(key)) {
							toastr[html[key].type](html[key].content, html[key].title);
						}
					}
				},
			});
		}
		function refreshNotifs() {
			$.ajax({
				url: "<?php echo HTTP_PATH; ?>home/ajaxNotifs",
				cache: false,
				dataType: 'json',
				success: function(html){
					for (var key in html) {
						if (html.hasOwnProperty(key)) {
							toastr[html[key].type](html[key].content, html[key].title);
						}
					}
				},
			});
		}
		$(function() {
			var	$window = $(window),
			$body = $('body');
			$window.on('load', function() {
				window.setTimeout(function() {
					$body.removeClass('is-loading');
				}, 0);
			});
			<?php if(isset($_SESSION['abas_login'])): ?>
			refreshNotifs();
			setInterval (refreshNotifs, 180000);
			<?php endif; ?>
		});

		function GetClock(){
			var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
			var days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
			d = new Date();
			day = d.getDay();
			date = d.getDate();
			month = d.getMonth();
			year = d.getFullYear();
			nhour  = d.getHours();
			nmin   = d.getMinutes();
			nsec   = d.getSeconds();
			if(nhour ==  0) {ap = " AM";nhour = 12;} 
			else if(nhour <= 11) {ap = " AM";} 
			else if(nhour == 12) {ap = " PM";} 
			else if(nhour >= 13) {ap = " PM";nhour -= 12;}
			if(nmin <= 9) {nmin = "0" +nmin;}
			document.getElementById('clockbox').innerHTML=months[month]+" "+date+", "+year+" ("+days[day]+") | "+nhour+":"+nmin+":"+nsec+" "+ap+" ";
			setTimeout("GetClock()", 1000);
		}
		window.onload=GetClock;
		/*<?php //if(isset($_SESSION['abas_login'])): ?>
		
			var timoutWarning = 30*60000; // Display warning in 30 minutes for inactivity
			var timoutNow = 1*60000; // Warning has been shown, give the user 1 minutes to interact 
			var warningTimer;
			var timeoutTimer;

			function StartWarningTimer() { // Start warning timer
				warningTimer = setTimeout("IdleWarning()", timoutWarning);
			}
			function ResetTimeOutTimer() { // Reset timers
				clearTimeout(timeoutTimer);
				StartWarningTimer();
			}
			function IdleWarning() { // Show idle timeout warning
			 	toastr['warning']("Due to inactivity, you will be automatically logged-out in 1 minute! Click <a class='bt btn-xs btn-success' href='javascript:ResetTimeOutTimer()'>here</a> to extend your session.", "ABAS Says");
			 	timeoutTimer = setTimeout("IdleTimeout()", timoutNow);
			 	clearTimeout(warningTimer);
			}
			function IdleTimeout() { // Logout the user
				 window.location = "<?php //echo HTTP_PATH."home/forced_logout"; ?>";
			}
			StartWarningTimer();

		<?php //endif; ?>*/
		$('a').not("[data-toggle='modal']").not("[data-toggle='collapse']").not("[target='_blank']").not(".nav > li > a").not(".nav-tabs > li > a").not(".view_canvasses-btn").not(".request-item-approve-btn").not(".request-item-cancel-btn").not(".exclude-pageload").click(function(){
			$('body').addClass('is-loading');
		});
		$('.force-pageload').click(function(){
			$('body').addClass('is-loading');
		});

	</script>
	<script src="<?php echo LINK?>assets/gentelella-master/vendors/bootstrap-table-filter/bootstrap-table-filter-control.js"></script>
	<script src="<?php echo LINK ?>assets/gentelella-master/build/js/custom.min.js"></script>
	
</html>
<?php if(ENVIRONMENT=="development" || ENVIRONMENT=="testing"){
	$memory = $this->benchmark->memory_usage();
	echo '<script>toastr["info"]("This page took {elapsed_time} seconds and uses '.$memory.' to load.","Page Timer");</script>';
	}
?>
