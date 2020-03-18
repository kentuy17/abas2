<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Database extends CI_Controller {
		public function __construct() {
			parent::__construct();
			date_default_timezone_set('Asia/Manila');
			session_start();
			// $this->load->database();
			$this->load->model("Abas");
			$this->load->model("Purchasing_model");
			$this->load->model("Inventory_model");
			$this->load->model("Mmm");
			$this->output->enable_profiler(FALSE);
			define("SIDEMENU","Home");
			if(isset($_SESSION['failed_login_attempts'])) { if($_SESSION['failed_login_attempts'] > 5) { die("Maximum number of login attempts reached. Please stop."); } }
			if(!isset($_SESSION['abas_login'])) { $this->Abas->redirect(HTTP_PATH); }
			$this->Abas->checkPermissions("database|update");
		}

		public function update_for_version_2_5_5(){
			$sql1 = "CREATE TABLE `hr_overtime_rate` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `type` varchar(45) NOT NULL,
					  `rate` double NOT NULL,
					  `status` tinyint(4) NOT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `id_UNIQUE` (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1";
			$this->Mmm->query($sql1,"Added new table for overtime");

			$sql2 = "CREATE TABLE `employee_overtime` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `date_filed` datetime NOT NULL,
					  `render_date` date NOT NULL,
					  `time_from` time NOT NULL,
					  `time_to` time NOT NULL,
					  `total_hours` time NOT NULL,
					  `reason` text NOT NULL,
					  `status` varchar(45) NOT NULL,
					  `approver_id` int(11) NOT NULL,
					  `date_approved` datetime DEFAULT NULL,
					  `processed_by` int(11) DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `id_UNIQUE` (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1";
			$this->Mmm->query($sql2,"Added new table for overtime");

			$sql3 = "INSERT INTO `hr_overtime_rate` VALUES 
					(1,'Regular Day',25,1),
					(2,'Rest Day',30,1),
					(3,'Legal Holiday',200,1),
					(4,'Legal Holiday on Restday',260,1),
					(5,'Special Holiday',30,1),
					(6,'Special Holiday on Restday',50,1)";
			$this->Mmm->query($sql3,"Added values table hr_overtime_rate");
		}

		public function update_for_version_2_5_4(){ //V2.5.4
			$sql1 = "CREATE TABLE `hr_crew_movements` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `vessel_from` int(11) NOT NULL,
					  `added_by` varchar(45) DEFAULT NULL,
					  `added_on` date DEFAULT NULL,
					  `vessel_to` int(11) NOT NULL,
					  `embarkation_start` date NOT NULL,
					  `embarkation_end` date NOT NULL,
					  `stat` tinyint(4) NOT NULL,
					  `transfer_date` date NOT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `id_UNIQUE` (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1";
			$this->Mmm->query($sql1,"Added new table for crew movements");

			$sql2 = "CREATE TABLE `hr_vessel_positions` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `position_id` int(11) NOT NULL,
					  `vessel_id` int(11) NOT NULL,
					  `quantity` int(11) NOT NULL,
					  `added_by` int(11) DEFAULT NULL,
					  `date_added` date NOT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `id_UNIQUE` (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1";
			$this->Mmm->query($sql2,"Added to trace vessel positions");

			$sql3 = "CREATE TABLE `employee_leave` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `date_filed` date NOT NULL,
					  `type` varchar(45) NOT NULL,
					  `is_with_pay` tinyint(4) NOT NULL,
					  `date_from` date NOT NULL,
					  `date_to` date NOT NULL,
					  `days` int(11) NOT NULL,
					  `status` varchar(45) NOT NULL,
					  `address` varchar(100) DEFAULT NULL,
					  `contact_number` varchar(45) DEFAULT NULL,
					  `reason` text,
					  `dept_id` int(11) NOT NULL,
					  `date_approved` date DEFAULT NULL,
					  `date_processed` date DEFAULT NULL,
					  `processed_by` varchar(45) DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `id_UNIQUE` (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1";
			$this->Mmm->query($sql3,"Added for employee leave");

			$sql4 = "CREATE TABLE `employee_approver` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `department_id` int(11) NOT NULL,
					  `approver_id` int(11) NOT NULL,
					  `document` varchar(45) NOT NULL,
					  `role` varchar(45) NOT NULL,
					  `status` tinyint(4) NOT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `id_UNIQUE` (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1";
			$this->Mmm->query($sql4,"Added to assign employee to approve in specific documents");

			$sql5 = "CREATE TABLE `department_sub_sections` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(45) NOT NULL,
					  `section_id` int(11) NOT NULL,
					  `added_by` int(11) DEFAULT NULL,
					  `date_added` date NOT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `id_UNIQUE` (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1";
			$this->Mmm->query($sql5,"Added for HR sub_section");


			$sql6 = "ALTER TABLE `hr_employees` 
					ADD `sub_section_id` int(11) NOT NULL;";
			$this->Mmm->query($sql6,"Added in hr_employees table as foriegnkey");
		}

		public function update_for_version_2_5_0(){ //V2.5.0
			$sql0 = "CREATE TABLE IF NOT EXISTS `inventory_quantity` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `item_id` int(11) NOT NULL,
					  `company_id` int(11) NOT NULL,
					  `location` varchar(45) NOT NULL,
					  `unit` varchar(45) NOT NULL,
					  `quantity` double NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql0,"Added new table for use in checking if the particular company and location has sufficient quantity per item.");

			$sql1 = "ALTER TABLE `inventory_deliveries` ADD `received_by` varchar(90) NOT NULL AFTER  `created_by`, ADD `issuance_id` int(11) NOT NULL AFTER `is_issued`, ADD `status` varchar(45) NOT NULL AFTER `company_id`;";
			$this->Mmm->query($sql1,"Added Issuance ID to be used if the RR is direct delivery.");	

			$sql2 = "ALTER TABLE `inventory_notice_of_discrepancy` ADD `verified_on` DATETIME NOT NULL AFTER `verified_by`, ADD `level1_approved_on` DATETIME NOT NULL AFTER `level3_approved_by`, ADD `level2_approved_on` DATETIME NOT NULL AFTER `level1_approved_on`, ADD `level3_approved_on` DATETIME NOT NULL AFTER `level2_approved_on`, ADD `cancelled_on` DATETIME NOT NULL AFTER `cancelled_by`;";
			$this->Mmm->query($sql2,"Added verified and approved dates on Notice of Discrepancy.");	

			$sql3 = "ALTER TABLE `inventory_notice_of_discrepancy_details` ADD `unit` varchar(45) NOT NULL AFTER `item_id`,ADD `packaging` varchar(45) NOT NULL AFTER `unit`, ADD `unit_price` DOUBLE NOT NULL AFTER `packaging`;";
			$this->Mmm->query($sql3,"Added unit price on Notice of Discrepancy details so it can be picked-up on the Receiving Report.");	

			$sql4 = "ALTER TABLE `inventory_issuance` ADD `reference_number` varchar(45) NOT NULL AFTER `delivery_id`;";
			$this->Mmm->query($sql4,"Added project reference number for issuance so it can be included in the Vessel Repairs Statistics report.");

			$sql5 = "CREATE TABLE `user_locations` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `location_name` varchar(255) NOT NULL,
					  `address` varchar(255) NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql5,"Create new table for user_locations to be used for user registration and warehouse location");

			$sql7 = "CREATE TABLE `inventory_conversions` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_id` int(11) NOT NULL,
					  `location` varchar(45) NOT NULL,
					  `item_id` int(11) NOT NULL,
					  `converted_item_id` int(11) NOT NULL,
					  `from_unit` varchar(45) NOT NULL,
					  `to_unit` varchar(45) NOT NULL,
					  `from_quantity` double NOT NULL,
					  `to_quantity` double NOT NULL,
					  `from_price` double NOT NULL,
					  `to_price` double NOT NULL,
					  `created_on` datetime NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql7,"Create new view for inventory per company.");

			$sql8 = "CREATE TABLE `inventory_packaging` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `item_id` int(11) NOT NULL,
					  `packaging` varchar(45) NOT NULL,
					  `conversion` double NOT NULL,
					  `unit` varchar(45) NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql8,"Create new table for inventory item packaging.");

			$sql9 = "ALTER TABLE `inventory_request_details` ADD `unit` VARCHAR(45) NOT NULL AFTER `item_id`, ADD `packaging` VARCHAR(45) NOT NULL AFTER `unit`;";
			$this->Mmm->query($sql9,"Added unit and packaging purchase requisition.");

			$sql10 = "ALTER TABLE `inventory_po_details` ADD `packaging` VARCHAR(45) NOT NULL AFTER `unit_price`;";
			$this->Mmm->query($sql10,"Added unit and packaging purchase order.");

			$sql11 = "ALTER TABLE `inventory_quantity` ADD `quantity_issued` DOUBLE NOT NULL AFTER `quantity`, ADD `delivery_id` INT NOT NULL AFTER `item_id`, ADD `unit_price` DOUBLE NOT NULL AFTER `unit`;";
			$this->Mmm->query($sql11,"Added columns needed for FIFO implementation.");

			$sql20 = "ALTER TABLE `inventory_items` ADD `brand` VARCHAR(90) NULL AFTER `description`;";
			$this->Mmm->query($sql20,"Added Brand column on Inventory Items.");	

			$sql12 = "CREATE VIEW `inventory_items_per_company`  AS SELECT
					    `inventory_items`.`id` AS `id`,
					    `inventory_items`.`item_code` AS `item_code`,
					    `inventory_items`.`asset_code` AS `asset_code`,
					    `inventory_items`.`description` AS `description`,
					    `inventory_items`.`brand` AS `brand`,
					    `inventory_items`.`particular` AS `particular`,
					    `inventory_items`.`unit` AS `unit`,
					    `inventory_items`.`unit_price` AS `unit_price`,
					    `inventory_items`.`reorder_level` AS `reorder_level`,
					    `inventory_items`.`discontinued` AS `discontinued`,
					    `inventory_items`.`sub_category` AS `sub_category`,
					    `inventory_items`.`stat` AS `stat`,
					    `inventory_items`.`category` AS `category`,
					    `inventory_items`.`stock_location` AS `stock_location`,
					    `inventory_items`.`type` AS `type`,
					    `inventory_items`.`picture` AS `picture`,
					    `inventory_items`.`account_type` AS `account_type`,
					    `inventory_items`.`requested` AS `requested`,
					    `inventory_items`.`created_on` AS `created_on`,
					    `inventory_items`.`created_by` AS `created_by`,
					    `inventory_quantity`.`item_id` AS `item_id`,
					    `inventory_quantity`.`company_id` AS `company_id`,
					    `inventory_quantity`.`location` AS `location`,
					    SUM(
					        `inventory_quantity`.`quantity`
					    ) AS `total_quantity_received`,
					    SUM(
					        `inventory_quantity`.`quantity_issued`
					    ) AS `total_quantity_issued`
					FROM
					    (
					        `inventory_items`
					    JOIN `inventory_quantity` ON
					        (
					            `inventory_items`.`id` = `inventory_quantity`.`item_id`
					        )
					    )
					WHERE
					    `inventory_items`.`stat` = 1 AND `inventory_quantity`.`stat` = 1
					GROUP BY
					    `inventory_quantity`.`item_id`, `inventory_quantity`.`company_id`,`inventory_quantity`.`location`;";
			$this->Mmm->query($sql12,"Create new view for inventory per company.");

			$sql13 = "ALTER TABLE `inventory_transfer` ADD requested_for int NOT NULL AFTER `transfer_date`, ADD `created_by` int NOT NULL AFTER `company_id`, ADD `created_on` datetime NOT NULL AFTER `created_by`, ADD `status` varchar(45) NOT NULL;";
			$this->Mmm->query($sql13,"Added additional columns for inventory transfer.");

			$sql130 = "ALTER TABLE `inventory_transfer_details` CHANGE `qty` `qty` double NOT NULL;";
			$this->Mmm->query($sql130,"Change qty from int to double in inventory transfer details.");

			$sql14 = "CREATE TABLE `inventory_transfer_receipt_details` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `transfer_id` int(11) NOT NULL,
						  `item_id` int(11) NOT NULL,
						  `unit` varchar(45) NOT NULL,
						  `unit_price` double NOT NULL,
						  `qty` double NOT NULL,
						  `stat` tinyint(1) NOT NULL,
						  `received_on` datetime NOT NULL,
						  `received_by` int(11) NOT NULL,
						  `remarks` varchar(255) NOT NULL,
					  	   PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql14,"Added new table for inventory transfer receipt.");

			$sql15 = "ALTER TABLE `inventory_gatepass` ADD `stock_transfer_receipt_id` INT NOT NULL AFTER `issuance_id`, ADD `company_id` INT NOT NULL AFTER `vessel_id`;";
			$this->Mmm->query($sql15,"Added column for stock stock transfer receipt id.");

			$sql16 = "ALTER TABLE `inventory_requests` ADD `company_id` INT NOT NULL AFTER `requisitioner`;";
			$this->Mmm->query($sql16,"Added company_id column in reqisition to address issue of incorrect control numbering.");

			$sql17 = "ALTER TABLE `inventory_return` ADD `created_by` INT NOT NULL AFTER `remark`, ADD `created_on` DATETIME NOT NULL AFTER `created_by`, ADD `is_cleared` BOOLEAN NOT NULL AFTER `stat`, ADD `status` VARCHAR(45) NOT NULL AFTER `is_cleared`;";
			$this->Mmm->query($sql17,"Added required columns on inventory return table.");

			$sql18 = "ALTER TABLE `inventory_return_details` CHANGE `qty` `qty` DOUBLE NOT NULL";
			$this->Mmm->query($sql18,"Change datatype of qty column from int to double.");

			$sql19 = "ALTER TABLE `inventory_audit_details` ADD `inventory_quantity_id` INT NOT NULL AFTER `audit_id`, ADD `unit` VARCHAR(45) NOT NULL AFTER `item_id`, ADD `unit_price` DOUBLE NOT NULL AFTER `unit`, ADD `location` VARCHAR(45) NOT NULL AFTER `shelf_number`;";
			$this->Mmm->query($sql19,"Added new columns for Inventory audit details table.");

			$sql21 = "CREATE TABLE `inventory_monthly_reports` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_id` int(11) NOT NULL,
					  `location` varchar(45) NOT NULL,
					  `created_on` datetime NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `status` varchar(45) NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql21,"Added new table for Monthly Inventory Report.");

			$sql22 = "CREATE TABLE `inventory_monthly_report_details` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `monthly_report_id` int(11) NOT NULL,
					  `item_id` int(11) NOT NULL,
					  `location` varchar(45) NOT NULL,
					  `code` varchar(2) NOT NULL,
					  `remarks` text NOT NULL,
					  `costing_method` varchar(45) NOT NULL,
					  `unit` varchar(45) NOT NULL,
					  `unit_price` double NOT NULL,
					  `quantity` double NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					   PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql22,"Added new table for Monthly Inventory Report Details.");

			$sql23 = "CREATE VIEW `ac_inventory_returns_for_clearing`
					 AS SELECT inventory_return.*,companies.name AS company_name FROM inventory_return INNER JOIN companies ON companies.id=inventory_return.company_id WHERE is_cleared=0;";
			$this->Mmm->query($sql23,"Create view for Inventory Returns - For Clearing");

			$sql24	=	"CREATE VIEW `ac_inventory_returns_for_posting` AS SELECT inventory_return.*,ac_transactions.id AS transaction_id, companies.name AS company_name FROM inventory_return INNER JOIN ac_transactions ON ac_transactions.reference_id=inventory_return.id INNER JOIN companies ON companies.id=inventory_return.company_id WHERE ac_transactions.reference_table='inventory_return' AND ac_transactions.status='Active' AND ac_transactions.stat=0;";
			$this->Mmm->query($sql24, "Create view for Inventory Returns - For Posting");

			$sql25	=	"CREATE VIEW `ac_inventory_returns_posted` AS SELECT inventory_return.*,ac_transactions.id AS transaction_id, companies.name AS company_name FROM inventory_return INNER JOIN ac_transactions ON ac_transactions.reference_id=inventory_return.id INNER JOIN companies ON companies.id=inventory_return.company_id WHERE ac_transactions.reference_table='inventory_return' AND ac_transactions.status='Active' AND ac_transactions.stat=1;";
			$this->Mmm->query($sql25, "Create view for Inventory Returns - Posted");


		}
		public function update_for_version_2_4_2(){ //released
			$sql1 = "ALTER TABLE `am_schedule_logs` ADD `trial_date` DATE NOT NULL AFTER `type`;";
			$this->Mmm->query($sql1,"Added column trial_date for Drydock and Motorpool schedule logs");
		}
		public function update_for_version_2_4_0(){ //released
			$sql1 = "ALTER TABLE `ac_accounts` ADD `classification` INTEGER NOT NULL,
						ADD `type` varchar(45),
						ADD `sub-type` varchar(45)";
			$this->Mmm->query($sql1,"Added columns");

			$sql2 = "CREATE TABLE `ac_accounts_classification` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `name` varchar(500) NOT NULL DEFAULT '',
					  `type` varchar(45) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB CHARSET=latin1;";
			$this->Mmm->query($sql2,"Added new table ac_accounts_classification");
		
		}
		public function update_for_budget() //released
		{
			$query1 = "CREATE TABLE `budget_opex` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `account_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `department_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `year` int(10) unsigned NOT NULL DEFAULT '0',
					  `increment` int(10) unsigned NOT NULL DEFAULT '0',
					  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
					  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					  `updated_by` int(10) unsigned DEFAULT NULL,
					  `date_updated` datetime DEFAULT NULL,
					  `prev_budget` double NOT NULL DEFAULT '0',
					  `curr_budget` double NOT NULL DEFAULT '0',
					  `company_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `budget_id` int(10) unsigned NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=2859 DEFAULT CHARSET=latin1;";
			$this->Mmm->query($query1,"Added budget table");

			$query2 = "CREATE TABLE `budget_default_percentage` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `percentage` int(10) unsigned NOT NULL DEFAULT '0',
					  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
					  `created_on` int(10) unsigned NOT NULL DEFAULT '0',
					  `status` tinyint(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;";
			$this->Mmm->query($query2,"Added budget_default_percentage table");

			$query3 = "CREATE TABLE `budget_percentage` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `account_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `updated_by` int(10) unsigned DEFAULT NULL,
					  `updated_on` datetime DEFAULT NULL,
					  `percentage` int(10) unsigned NOT NULL DEFAULT '0',
					  `year` int(10) unsigned NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=latin1;";
			$this->Mmm->query($query3,"Added budget_percentage table");

			$query4 = "CREATE TABLE  `budget_summary` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `status` varchar(45) NOT NULL DEFAULT '',
					  `total_amount` bigint(20) unsigned NOT NULL DEFAULT '0',
					  `type` varchar(45) NOT NULL DEFAULT '',
					  `year` int(10) unsigned NOT NULL DEFAULT '0',
					  `generated_by` int(10) unsigned NOT NULL DEFAULT '0',
					  `verified_by` int(10) unsigned DEFAULT NULL,
					  `approved_by` int(10) unsigned DEFAULT NULL,
					  `generated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					  `verified_on` datetime DEFAULT NULL,
					  `approved_on` datetime DEFAULT NULL,
					  `department_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `company_id` int(10) unsigned NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;";
			$this->Mmm->query($query4,"Added budget_summary table");

			$query5 = "ALTER TABLE users ADD 
					  `department_id` int(10) unsigned DEFAULT NULL";
			$this->Mmm->query($query5,"Added department_id column to users table");

			$query6 = "ALTER TABLE hr_employees ADD
					  `user_id` int(10) unsigned DEFAULT NULL";
			$this->Mmm->query($query6,"Added user_id column to hr_employees table");
		}
		public function update_for_version_2_3_0() //released
		{
			$sql0 = "CREATE TABLE `ac_ap_voucher_attachments` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `ap_voucher_id` int(11) NOT NULL,
					  `document_name` varchar(255) NOT NULL,
					  `document_file` varchar(255) NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql0,"Added new table for APV attachments");

			$sql1 = "ALTER TABLE `ac_vouchers` ADD `is_exported` BOOLEAN NOT NULL AFTER `stat`;";
			$this->Mmm->query($sql1,"Added column to check if the CV is exported and loaded to Union Bank Check Writer System");

			$sql2 = "CREATE TABLE `ac_ap_voucher_wtax` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `ap_voucher_id` int(11) NOT NULL,
					  `wtax_amount` double NOT NULL,
					  `atc` varchar(45) NOT NULL,
					  `atc_description` varchar(255) NOT NULL,
					  `taxable_amount` double NOT NULL,
					  `tax_rate` double NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql2,"Create new table for APV identification of wtax to be included in the UB template export.");

			$sql3 = "CREATE VIEW `ac_accounts_payable_for_clearing` AS SELECT `d`.`id` AS `id`,`d`.`tdate` AS `tdate`,`d`.`delivery_no` AS `delivery_no`,`d`.`sales_invoice_no` AS `sales_invoice_no`,`d`.`po_no` AS `po_no`,`d`.`supplier_id` AS `supplier_id`,`d`.`amount` AS `amount`,`d`.`stat` AS `stat`,`d`.`location` AS `location`,`d`.`remark` AS `remark`,`d`.`voucher_id` AS `voucher_id`,`d`.`is_cleared` AS `is_cleared`,`d`.`doc_rr` AS `doc_rr`,`d`.`doc_others` AS `doc_others`,`d`.`doc_dr` AS `doc_dr`,`d`.`doc_si` AS `doc_si`,`d`.`doc_po` AS `doc_po`,`d`.`control_number` AS `control_number`,`d`.`is_issued` AS `is_issued`,`d`.`notice_of_discrepancy_id` AS `notice_of_discrepancy_id`,`d`.`created_on` AS `created_on`,`d`.`created_by` AS `created_by`,`d`.`company_id` AS `company_id`,`j`.`id` AS `journal_id`,`j`.`coa_id` AS `coa_id`,`j`.`reconciling_id` AS `reconciling_id` FROM (`ac_transaction_journal` `j` join `inventory_deliveries` `d` on(`j`.`reference_id` = `d`.`id`)) where `j`.`coa_id` = 291 and `j`.`reconciling_id` is null";
			$this->Mmm->query($sql3,"Create new view for accoutns payable for clearing.");

			$sql4 = "CREATE TABLE `ac_expanded_wtax_codes` (
					`id` int(11) NOT NULL AUTO_INCREMENT, 
					`tax_code` varchar(25) NOT NULL, 
					`description` varchar(500) NOT NULL, 
					`tax_rate` double NOT NULL, 
					`tax_type` varchar(25) NOT NULL, 
					`stat` tinyint(1) NOT NULL,
					 PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql4,"Create new table for etax codes.");

			$sql5 = "INSERT INTO `ac_expanded_wtax_codes` (`id`, `tax_code`, `description`, `tax_rate`, `tax_type`, `stat`) VALUES (1, 'WI 010', 'Professional fees (Lawyers, CPA\'s, Engineers, etc.) if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (2, 'WI 011', 'Professional fees (Lawyers, CPA\'s, Engineers, etc.) if gross income is more than 3M or VAT registered regardlessof amount', 0.10, 'WE', 1),
 (3, 'WC 010', 'Professional fees (Lawyers, CPA\'s, Engineers, etc.) if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (4, 'WC 011', 'Professional fees (Lawyers, CPA\'s, Engineers, etc.) if gross income exceeds P720,000', 0.15, 'WE', 1),
 (5, 'WI 020', 'Professional entertainer such as, but not limited to actors and actresses, singers, lyricist, composers, emcees if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (6, 'WI 021', 'Professional entertainer such as, but not limited to actors and actresses, singers, lyricist, composers, emcees if gross income is more than 3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (7, 'WC 020', 'Professional entertainer such as, but not limited to actors and actresses, singers, lyricist, composers, emcees if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (8, 'WC 021', 'Professional entertainer such as, but not limited to actors and actresses, singers, lyricist, composers, emcees if gross income exceeds P720,000', 0.15, 'WE', 1),
 (9, ' WI 030', 'Professional athletes including basketball players, pelotaris and jockeys if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (10, 'WI 031', 'Professional athletes including basketball players, pelotaris and jockeys if gross income is more than 3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (11, 'WC 030', 'Professional athletes including basketball players, pelotaris and jockeys if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (12, 'WC 031', 'Professional athletes including basketball players, pelotaris and jockeys if gross income exceeds P720,000', 0.15, 'WE', 1),
 (13, 'WI 040', 'All directors and producers involved in movies, stage, television and musical productions if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (14, 'WI 041', 'All directors and producers involved in movies, stage, television and musical productions if gross income is more than 3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (15, 'WC 040', 'All directors and producers involved in movies, stage, television and musical productions if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (16, 'WC 041', 'All directors and producers involved in movies, stage, television and musical productions if gross income exceeds P720,000', 0.15, 'WE', 1),
 (17, 'WI 050', 'Management and technical consultants if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (18, 'WI 051 ', 'Management and technical consultants if gross income is more than 3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (19, 'WC 050', 'Management and technical consultants if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (20, 'WC 051', 'Management and technical consultants if gross income exceeds P720,000', 0.15, 'WE', 1),
 (21, 'WI 060', 'Business and Bookkeeping agents and agencies if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (22, 'WI 061', 'Business and Bookkeeping agents and agencies if gross income is more than 3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (23, 'WC 060', 'Business and Bookkeeping agents and agencies if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (24, 'WC 061', 'Business and Bookkeeping agents and agencies if gross income exceeds P720,000', 0.15, 'WE', 1),
 (25, 'WI 070', 'Insurance agents and insurance adjusters if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (26, 'WI 071', 'Insurance agents and insurance adjusters if gross income is more than 3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (27, 'WC 070', 'Insurance agents and insurance adjusters if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (28, 'WC 071', 'Insurance agents and insurance adjusters if gross income exceeds P720,000', 0.15, 'WE', 1),
 (29, 'WI 080', 'Other Recipients of Talent Fees if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (30, 'WI 081', 'Other Recipients of Talent Fees if gross income is more than 3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (31, 'WC 080', 'Other Recipients of Talent Fees if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (32, 'WC 081', 'Other Recipients of Talent Fees if gross income exceeds P720,000', 0.15, 'WE', 1),
 (33, 'WI 090', 'Fees of Director who are not employees of the company if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (34, 'WI 091', 'Fees of Director who are not employees of the company if gross income is more than 3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (35, 'WI 100', 'Rentals Oon gross rental or lease for the continued use or possession of personal property in excess of P10,000 annually and real property used in business which the payor or obligor has not taken title or is not taking title, or in which has no equity; poles, satellites, transmission facilities and billboards', 0.05, 'WE', 1),
 (36, 'WI 110', 'Cinemathographic film rentals and other payments to resident indivduals and corporate cinematographic film owners, lessors and distributors', 0.05, 'WE', 1),
 (37, 'WI 120', 'Income payments to certain contractors', 0.02, 'WE', 1),
 (38, 'WC 100', 'Rentals Oon gross rental or lease for the continued use or possession of personal property in excess of P10,000 annually and real property used in business which the payor or obligor has not taken title or is not taking title, or in which has no equity; poles, satellites, transmission facilities and billboards', 0.05, 'WE', 1),
 (39, 'WC 110', 'Cinemathographic film rentals and other payments to resident indivduals and corporate cinematographic film owners, lessors and distributors', 0.05, 'WE', 1),
 (40, 'WC 120', 'Income payments to certain contractors', 0.02, 'WE', 1),
 (41, 'WI 130', 'Income distribution to the beneficiaries of estate and trusts', 0.15, 'WE', 1),
 (42, 'WI 139', 'Gross Commission of service fees of customs, insurance, stock, immigration and commercial brokers, fees of agents of professional entertainers and real estate service practitioners (RESPs)(i.e. real estate consultants, real estate appraisers and real estate brokers if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (43, 'WI 140', 'Gross Commission of service fees of customs, insurance, stock, immigration and commercial brokers, fees of agents of professional entertainers and real estate service practitioners (RESPs)(i.e. real estate consultants, real estate appraisers and real estate brokers if gross income is more than 3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (44, 'WC 139', 'Gross Commission of service fees of customs, insurance, stock, immigration and commercial brokers, fees of agents of professional entertainers and real estate service practitioners (RESPs)(i.e. real estate consultants, real estate appraisers and real estate brokers if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (45, 'WC 140', 'Gross Commission of service fees of customs, insurance, stock, immigration and commercial brokers, fees of agents of professional entertainers and real estate service practitioners (RESPs)(i.e. real estate consultants, real estate appraisers and real estate brokers if gross income exceeds P720,000', 0.15, 'WE', 1),
 (46, 'WI 151', 'Professional fees paid to medical practitioners (includes doctors of medicine, doctors of veterinary science & dentist)  by hospitals & clinics or paid directly by HMO and/or other semilar establishments if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (47, 'WI 150', 'Professional fees paid to medical practitioners (includes doctors of medicine, doctors of veterinary science & dentist)  by hospitals & clinics or paid directly by HMO and/or other semilar establishments if gross income is more than 3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (48, 'WC 151', 'Professional fees paid to medical practitioners (includes doctors of medicine, doctors of veterinary science & dentist)  by hospitals & clinics or paid directly by HMO and/or other semilar establishments if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (49, 'WC 150', 'Professional fees paid to medical practitioners (includes doctors of medicine, doctors of veterinary science & dentist)  by hospitals & clinics or paid directly by HMO and/or other semilar establishments if gross income exceeds P720,000', 0.15, 'WE', 1),
 (50, 'WI 152', 'Payment by the General Professional Partnership (GPPs) to its partners if gross income for the current year did not exceed P720,000', 0.10, 'WE', 1),
 (51, 'WI 153', 'Payment by the General Professional Partnership (GPPs) to its partners if gross income exceeds P720,000', 0.15, 'WE', 1),
 (52, 'WI 158', 'Income payments made by credit card companies', 0.01, 'WE', 1),
 (53, 'WC 158', 'Income payments made by credit card companies', 0.01, 'WE', 1),
 (54, 'WI 159', 'Additional Income Payments to govt personnel from importers, shipping and airline companies or their agents for overtime services', 0.15, 'WE', 1),
 (55, 'WI 640 ', 'Income Payment made by NGAs, LGU, & etc to its local/resident suppliers of goods other than those covered by other rates of withholding tax', 0.01, 'WE', 1),
 (56, 'WI 157 ', 'Income Payment made by NGAs, LGU, & etc to its local/resident suppliers of services other than those covered by other rates of withholding tax', 0.02, 'WE', 1),
 (57, 'WI 158', 'Income Payment made by top withholding agents to their local/resident suppliers of goods other than those covered by other rates of withholding tax', 0.01, 'WE', 1),
 (58, 'WI 160', 'Income Payment made by top withholding agents to their local/resident suppliers of services other than those covered by other rates of withholding tax', 0.02, 'WE', 1),
 (59, 'WI 515', 'Commissions, rebates, discounts and other similar considerations paid/granted to independent and/or exclusive sales representatives and marketing agents and sub-agents of companies, including multi-level marketing companies if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (60, 'WI 516', 'Commissions, rebates, discounts and other similar considerations paid/granted to independent and/or exclusive sales representatives and marketing agents and sub-agents of companies, including multi-level marketing companies  if the gross income is more than P3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (61, 'WC 640', 'Income Payment made by NGAs, LGU, & etc to its local/resident suppliers of goods other than those covered by other rates of withholding tax', 0.01, 'WE', 1),
 (62, 'WC 157', 'Income Payment made by NGAs, LGU, & etc to its local/resident suppliers of services other than those covered by other rates of withholding tax', 0.02, 'WE', 1),
 (63, 'WC 158', 'Income Payment made by top withholding agents to their local/resident suppliers of goods other than those covered by other rates of withholding tax', 0.01, 'WE', 1),
 (64, 'WC 160', 'Income Payment made by top withholding agents to their local/resident suppliers of services other than those covered by other rates of withholding tax', 0.02, 'WE', 1),
 (65, 'WC 515', 'Commissions, rebates, discounts and other similar considerations paid/granted to independent and/or exclusive sales representatives and marketing agents and sub-agents of companies, including multi-level marketing companies if the gross income for the current year did not exceed P3M', 0.05, 'WE', 1),
 (66, 'WC 516', 'Commissions, rebates, discounts and other similar considerations paid/granted to independent and/or exclusive sales representatives and marketing agents and sub-agents of companies, including multi-level marketing companies  if the gross income is more than P3M or VAT registered regardless of amount', 0.10, 'WE', 1),
 (67, 'WI 530', 'Gross payments to embalmers by funeral parlors', 0.01, 'WE', 1),
 (68, 'WI 535', 'Payments made by pre-need companies to funeral parlors', 0.01, 'WE', 1),
 (69, 'WI 540', 'Tolling fees paid to refineries', 0.05, 'WE', 1),
 (70, 'WI 610', 'Income payments made to suppliers of agricultural supplier products in excess of cumulative amount of P300,000 within the same taxable year', 0.01, 'WE', 1),
 (71, 'WI 630', 'Income payments on purchases of minerals, mineral products and quarry resources, such as but not limited to silver, gold, granite, gravel, sand, boulders and other mineral products except purchases by Bangko Sentral ng Pilipinas', 0.05, 'WE', 1),
 (72, 'WI 632', 'Income payments on purchases of minerals, mineral products and quarry resources by Bangko Sentral ng Pilipinas ((BSP) from gold miners/suppliers under PD 1899, as amended by RA No. 7076', 0.01, 'WE', 1),
 (73, 'WI 650', 'On gross amount of refund given by MERALCO to customers with active contracts as classified by MERALCO', 0.15, 'WE', 1),
 (74, 'WI 651', 'On gross amount of refund given by MERALCO to customers with terminated contracts as classified by MERALCO', 0.15, 'WE', 1),
 (75, 'WI 660', 'On gross amount of interest on the refund of meter deposits whether paid directly to the customers or applied against customer\'s billings of Residential and General Service customers whose monthly electricity consumption exceeds 200 kwh as classified by MERALCO', 0.10, 'WE', 1),
 (76, 'WI 661', 'On gross amount of interest on the refund of meter deposits whether paid directly to the customers or applied against customer\'s billings of Non-Residential customers whose monthly electricity consumption exceeds 200 kwh as classified by MERALCO', 0.10, 'WE', 1),
 (77, 'WI 662', 'On gross amount of interest on the refund of meter deposits whether paid directly to the customers or applied against customer\'s billings of Residential and General Service customers whose monthly electricity consumption exceeds 200 kwh as classified by other  by other electric Distribution Utilities (DU)', 0.10, 'WE', 1),
 (78, 'WI 663', 'On gross amount of interest on the refund of meter deposits whether paid directly to the customers or applied against customer\'s billings of Non-Residential customers whose monthly electricity consumption exceeds 200 kwh as classified by other electric Distribution Utilities (DU)', 0.10, 'WE', 1),
 (79, 'WI 680', 'Income payments made by political parties and candidates of local and national elections on all their purchases of goods and services relkated to campaign expenditures, and income payments made by individuals or juridical persons for their purchases of goods and services intented to be given as campaign contribution to political parties and candidates', 0.05, 'WE', 1),
 (80, 'WC 535', 'Payments made by pre-need companies to funeral parlors', 0.01, 'WE', 1),
 (81, 'WC 540', 'Tolling fees paid to refineries', 0.05, 'WE', 1),
 (82, 'WC 610', 'Income payments made to suppliers of agricultural supplier products in excess of cumulative amount of P300,000 within the same taxable year', 0.01, 'WE', 1),
 (83, 'WC 630', 'Income payments on purchases of minerals, mineral products and quarry resources, such as but not limited to silver, gold, granite, gravel, sand, boulders and other mineral products except purchases by Bangko Sentral ng Pilipinas', 0.05, 'WE', 1),
 (84, 'WC 632', 'Income payments on purchases of minerals, mineral products and quarry resources by Bangko Sentral ng Pilipinas ((BSP) from gold miners/suppliers under PD 1899, as amended by RA No. 7076', 0.01, 'WE', 1),
 (85, 'WC 650', 'On gross amount of refund given by MERALCO to customers with active contracts as classified by MERALCO', 0.15, 'WE', 1),
 (86, 'WC 651', 'On gross amount of refund given by MERALCO to customers with terminated contracts as classified by MERALCO', 0.15, 'WE', 1),
 (87, 'WC 660', 'On gross amount of interest on the refund of meter deposits whether paid directly to the customers or applied against customer\'s billings of Residential and General Service customers whose monthly electricity consumption exceeds 200 kwh as classified by MERALCO', 0.10, 'WE', 1),
 (88, 'WC 661', 'On gross amount of interest on the refund of meter deposits whether paid directly to the customers or applied against customer\'s billings of Non-Residential customers whose monthly electricity consumption exceeds 200 kwh as classified by MERALCO', 0.10, 'WE', 1),
 (89, 'WC 662', 'On gross amount of interest on the refund of meter deposits whether paid directly to the customers or applied against customer\'s billings of Residential and General Service customers whose monthly electricity consumption exceeds 200 kwh as classified by other  by other electric Distribution Utilities (DU)', 0.10, 'WE', 1),
 (90, 'WC 663', 'On gross amount of interest on the refund of meter deposits whether paid directly to the customers or applied against customer\'s billings of Non-Residential customers whose monthly electricity consumption exceeds 200 kwh as classified by other electric Distribution Utilities (DU)', 0.10, 'WE', 1),
 (91, 'WC 680', 'Income payments made by political parties and candidates of local and national elections on all their purchases of goods and services relkated to campaign expenditures, and income payments made by individuals or juridical persons for their purchases of goods and services intented to be given as campaign contribution to political parties and candidates', 0.05, 'WE', 1),
 (92, 'WC 690', 'Income payments received by Real Estate Investment Trust (REIT)', 0.01, 'WE', 1),
 (93, 'WI 710', 'Interest income denied from any other debt instruments not within the coverage of deposit substitutes and Revenue Regulations 14-2012', 0.15, 'WE', 1),
 (94, 'WI 720', 'Income payments on locally produced raw sugar', 0.01, 'WE', 1),
 (95, 'WC 710', 'Interest income denied from any other debt instruments not within the coverage of deposit substitutes and Revenue Regulations 14-2012', 0.15, 'WE', 1),
 (96, 'WC 720', 'Income payments on locally produced raw sugar', 0.01, 'WE', 1);";
		    $this->Mmm->query($sql5,"Populate data for etax codes.");

		$sql6 = "UPDATE `ac_vouchers` SET control_number=voucher_number";
			$this->Mmm->query($sql6,"Copy the voucher numbers column to the control number column.");

		$sql7 = "ALTER TABLE `ac_vouchers` ADD `multi_apv_no` VARCHAR(255) NOT NULL AFTER `apv_no`;";
			$this->Mmm->query($sql7,"Added column for inserting multi-apv reference ids.");

		$sql8 = "ALTER TABLE `ac_ap_vouchers` ADD `check_voucher_id` int(11) NOT NULL AFTER `journal_id`;";
			$this->Mmm->query($sql8,"Added column for CV reference ID once created.");
		}
		public function update_for_version_2_2_12(){ //V2.2.12 released
			$sql1 = "ALTER TABLE `am_fixed_assets` ADD `include_lapsing` BOOLEAN NOT NULL AFTER `stat`;";
			$this->Mmm->query($sql1,"Inserted new column for fixed asset register for determning if it is to include on the lapsing schedule.");
		}
		public function update_for_version_2_2_10(){ //V2.2.10 released
			$sql1 = "ALTER TABLE `am_fixed_assets` ADD `item_name` VARCHAR(45) NOT NULL AFTER `item_id`, ADD `particular` VARCHAR(255) NOT NULL AFTER `item_name`, ADD `unit` VARCHAR(45) NOT NULL AFTER `particular`, ADD `picture` VARCHAR(90) NOT NULL AFTER `unit`;";
			$this->Mmm->query($sql1,"Inserted new columns for fixed asset register for recording items that are not in the inventory.");
		}
		public function update_for_version_2_2_0(){ //V2.2.0 released

			$sql0 = "INSERT INTO `inventory_category` (`id`, `category`, `stat`, `parent`, `code`) VALUES (NULL, 'Building', '1', '0', 'BLDG'), (NULL, 'Vessels', '1', '0', 'VSL'), (NULL, 'Intangible Assets', '1', '0', 'IA'), (NULL, 'Tugboats and Barges', '1', '0', 'TB'), (NULL, 'Land Improvements', '1', '0', 'LI'), (NULL, 'Crane', '1', '0', 'CRNE'), (NULL, 'Drydocking Cost', '1', '0', 'DC');";
			$this->Mmm->query($sql0,"Inserted new categories in ABAS.");

			$sql1 = "ALTER TABLE `inventory_items` ADD `type` VARCHAR(45) NOT NULL AFTER `stock_location`, ADD `picture` VARCHAR(90) NULL AFTER `type`;";
			$this->Mmm->query($sql1,"Added Type (Capex and Non-Capex) and Picture(Link) columns on Inventory Items.");	

			$sql2 = "CREATE TABLE IF NOT EXISTS `am_fixed_assets` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `item_id` int(11) NOT NULL,
					  `category_id` int(11) NOT NULL,
					  `company_id` int(11) NOT NULL,
					  `department_id` int(11) NOT NULL,
					  `location` int(11) NOT NULL,
					  `description` text NOT NULL,
					  `control_number` int(11) NOT NULL,
					  `asset_code` varchar(45) NOT NULL,
					  `purchase_cost` double NOT NULL,
					  `date_acquired` date NOT NULL,
					  `useful_life` int(11) NOT NULL,
					  `status` varchar(45) NOT NULL,
					  `created_on` datetime NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `modified_by` int(11) NOT NULL,
					  `modified_on` datetime NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql2,"Create new table for Fixed Assets.");

			$sql3 = "CREATE TABLE IF NOT EXISTS `am_fixed_asset_accountability` (
					  `id` int(11) NOT NULL  AUTO_INCREMENT,
					  `company_id` int(11) NOT NULL,
					  `control_number` int(11) NOT NULL,
					  `status` varchar(45) NOT NULL,
					  `requested_by` int(11) NOT NULL,
					  `requested_on` date NOT NULL,
					  `created_on` datetime NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `modified_by` int(11) NOT NULL,
					  `modified_on` datetime NOT NULL,
					  `verified_on` datetime NOT NULL,
					  `verified_by` int(11) NOT NULL,
					  `approved_on` datetime NOT NULL,
					  `approved_by` int(11) NOT NULL,
					  `stat` tinyint(1) NOT NULL,
			 		  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;
					";
			$this->Mmm->query($sql3,"Create new table for Asset Accountability.");

			$sql4 = "CREATE TABLE IF NOT EXISTS `am_fixed_asset_accountability_details` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `accountability_id` int(11) NOT NULL,
					  `fixed_asset_id` int(11) NOT NULL,
					  `remarks` text NOT NULL,
					  `status` varchar(45) NOT NULL,
					  `date_issued` datetime NOT NULL,
					  `date_returned` datetime NOT NULL,
					  `received_by` int(11) NOT NULL,
					  `condition_of_returned_item` TEXT NOT NULL,
					  `stat` tinyint(1) NOT NULL,
			 		  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql4,"Create new table for Asset Accountability Details.");

			$sql5 ="CREATE TABLE IF NOT EXISTS `ac_lapsing_schedules` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `control_number` int(11) NOT NULL,
					  `company_id` int(11) NOT NULL,
					  `year` year(4) NOT NULL,
					  `status` varchar(45) NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `created_on` datetime NOT NULL,
					  `modified_by` int(11) NOT NULL,
					  `modified_on` datetime NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql5,"Create new table for Lapsing Schedules.");
			
			$sql6 = "CREATE TABLE IF NOT EXISTS `ac_lapsing_schedule_details` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `lapsing_schedule_id` int(11) NOT NULL,
					  `fixed_asset_id` int(11) NOT NULL,
					  `total_cost` double NOT NULL,
					  `salvage_value` double NOT NULL,
					  `depreciable_amount` double NOT NULL,
					  `useful_life` int(11) NOT NULL,
					  `annual_depreciation` double NOT NULL,
					  `monthly_depreciation` double NOT NULL,
					  `begin_accumulated_depreciation` double NOT NULL,
					  `begin_net_book_value` double NOT NULL,
					  `january_depreciation` double NOT NULL,
					  `february_depreciation` double NOT NULL,
					  `march_depreciation` double NOT NULL,
					  `april_depreciation` double NOT NULL,
					  `may_depreciation` double NOT NULL,
					  `june_depreciation` double NOT NULL,
					  `july_depreciation` double NOT NULL,
					  `august_depreciation` double NOT NULL,
					  `september_depreciation` double NOT NULL,
					  `october_depreciation` double NOT NULL,
					  `november_depreciation` double NOT NULL,
					  `december_depreciation` double NOT NULL,
					  `end_accumulated_depreciation` double NOT NULL,
					  `end_net_book_value` double NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql6,"Create new table for Lapsing Schedule Details.");

			$sql8 = "CREATE TABLE IF NOT EXISTS `am_fixed_asset_disposals` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `company_id` int(11) NOT NULL,
				  `control_number` int(11) NOT NULL,
				  `requested_by` int(11) NOT NULL,
				  `requested_on` date NOT NULL,
				  `checked_by` int(11) NOT NULL,
				  `checked_on` date NOT NULL,
				  `manner_of_disposal` varchar(45) NOT NULL,
				  `others` varchar(45) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_on` datetime NOT NULL,
				  `modified_by` int(11) NOT NULL,
				  `modified_on` datetime NOT NULL,
				  `verified_by` int(11) NOT NULL,
				  `verified_on` datetime NOT NULL,
				  `approved_by` int(11) NOT NULL,
				  `approved_on` datetime NOT NULL,
				  `status` varchar(45) NOT NULL,
				  `stat` int(11) NOT NULL,
			 	  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql8,"Create new table for Asset Disposal.");

			$sql9= "CREATE TABLE IF NOT EXISTS `am_fixed_asset_disposal_details` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `disposal_id` int(11) NOT NULL,
				  `fixed_asset_id` int(11) NOT NULL,
				  `net_book_value` double NOT NULL,
				  `proceeds` double NOT NULL,
				  `is_gain` tinyint(1) NOT NULL,
				  `reason_for_disposal` text NOT NULL,
				  `stat` tinyint(1) NOT NULL,
			 	  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql9,"Create new table for Asset Disposal Details.");

			$sql10= "ALTER TABLE `am_bill_of_materials` ADD `verified_by` INT NOT NULL AFTER `created_on`, ADD `verified_on` DATETIME NOT NULL AFTER `verified_by`, ADD `approved_by` INT NOT NULL AFTER `verified_on`, ADD `approved_on` DATETIME NOT NULL AFTER `approved_by`";
			$this->Mmm->query($sql10,"Added verify and approve by on BOM table.");

			$sql11= "ALTER TABLE `am_schedule_logs` ADD `verified_by` INT NOT NULL AFTER `created_on`, ADD `verified_on` DATETIME NOT NULL AFTER `verified_by`, ADD `approved_by` INT NOT NULL AFTER `verified_on`, ADD `approved_on` DATETIME NOT NULL AFTER `approved_by`;";
			$this->Mmm->query($sql11,"Added verify and approve by on Schedule Logs table.");

			$sql12= "ALTER TABLE `am_vessel_work_order` ADD `verified_by` INT NOT NULL AFTER `created_on`, ADD `verified_on` DATETIME NOT NULL AFTER `verified_by`, ADD `approved_by` INT NOT NULL AFTER `verified_on`, ADD `approved_on` DATETIME NOT NULL AFTER `approved_by`";
			$this->Mmm->query($sql12,"Added verify and approve by on Vesssel Work Order table.");

			$sql13= "ALTER TABLE `am_truck_repairs` ADD `verified_by` INT NOT NULL AFTER `created_on`, ADD `verified_on` DATETIME NOT NULL AFTER `verified_by`, ADD `approved_by` INT NOT NULL AFTER `verified_on`, ADD `approved_on` DATETIME NOT NULL AFTER `approved_by`;";
			$this->Mmm->query($sql13,"Added verify and approve by on TRMF table.");

			$sql14= "ALTER TABLE `am_vessel_evaluation` ADD `verified_by` INT NOT NULL AFTER `created_on`, ADD `verified_on` DATETIME NOT NULL AFTER `verified_by`, ADD `approved_by` INT NOT NULL AFTER `verified_on`, ADD `approved_on` DATETIME NOT NULL AFTER `approved_by`;";
			$this->Mmm->query($sql14,"Added verify and approve by on SRMSF table.");

			$sql15= "ALTER TABLE `am_truck_evaluation` ADD `verified_by` INT NOT NULL AFTER `created_on`, ADD `verified_on` DATETIME NOT NULL AFTER `verified_by`, ADD `approved_by` INT NOT NULL AFTER `verified_on`, ADD `approved_on` DATETIME NOT NULL AFTER `approved_by`;";
			$this->Mmm->query($sql15,"Added verify and approve by on MTDE table.");

			$sql16 = "CREATE TABLE IF NOT EXISTS `ac_voucher_attachments` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `check_voucher_id` int(11) NOT NULL,
					  `document_name` varchar(90) NOT NULL,
					  `filename` varchar(90) NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql16,"Create new table for Check Releasing Attachments.");

			$sql17= "ALTER TABLE `ac_vouchers` ADD `released_by` INT NOT NULL AFTER `released_date`;";
			$this->Mmm->query($sql17,"Added relesed_by on ac_voucher.");

			$sql18= "ALTER TABLE `ac_vouchers` CHANGE `released_date` `released_date` DATE NULL DEFAULT NULL;";
			$this->Mmm->query($sql18,"Change relesed_date from datetime to date.");

			$sql19 = "ALTER TABLE `inventory_po` ADD `file_path` VARCHAR(45) NOT NULL AFTER `approved_on`;";
			$this->Mmm->query($sql19,"Added file attachment on PO.");

			$sql20 = "ALTER TABLE `inventory_job_orders` ADD `file_path` VARCHAR(45) NOT NULL AFTER `approved_on`;";
			$this->Mmm->query($sql20,"Added file attachment on JO.");

			$sql21 = "ALTER TABLE `am_schedule_logs` ADD `reference_number` VARCHAR(45) NOT NULL AFTER `company_id`;";
			$this->Mmm->query($sql21,"Added Project Reference No. on Schedule Logs (Vessels & Trucks)");

			$sql22 = "ALTER TABLE `inventory_requests` ADD `truck_id` INT NOT NULL AFTER `vessel_id`, ADD `reference_number` VARCHAR(45) NOT NULL AFTER `truck_id`;";
			$this->Mmm->query($sql22,"Added Project Reference No. and Truck Plate No. on Requisition Form");
		}
		public function update_for_version_2_1_7(){ //V2.1.7 released
			$sql1 = "ALTER TABLE `service_orders` ADD `approved_by` INT NOT NULL AFTER `created_by`, ADD `approved_on` DATETIME NOT NULL AFTER `approved_by`;";
			$this->Mmm->query($sql1,"Added column approved_by for Service Orders.");
		}
		public function update_for_version_2_1_6(){ //V2.1.6 released
			$sql1 = "ALTER TABLE `ac_request_payment_details` ADD `wtax` varchar(20) NOT NULL, ADD `vat_amount` double NOT NULL, ADD `input_tax_amount` double NOT NULL, ADD `wtax_amount` double NOT NULL;";
			$this->Mmm->query($sql1,"Added column with-holding tax for request for payment.");
		}
		public function update_for_version_2_1_5(){ //V2.1.5 released
			$sql1 = "CREATE TABLE IF NOT EXISTS `ac_request_payment_attachments` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `request_payment_id` int(11) NOT NULL,
					  `file_name` varchar(90) NOT NULL,
					  `file_path` varchar(254) NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql1,"Create new table for request for payment attachments.");
		}
		public function update_for_version_2_1_0(){ //V2.1.0 released
			$sql1 = "CREATE TABLE IF NOT EXISTS `ac_request_payment_details` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `request_payment_id` int(11) NOT NULL,
					  `particulars` varchar(90) NOT NULL,
					  `amount` double NOT NULL,
					  `charge_to` int(11) NOT NULL,
					  `stat` tinyint(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql1,"Create new table for request for payment details.");

			$sql2 = "ALTER TABLE `ac_request_payment` ADD `verified_by` INT NOT NULL AFTER `created_by`, ADD `verified_on` DATETIME NOT NULL AFTER `verified_by`, ADD `approved_by` INT NOT NULL AFTER `verified_on`, ADD `approved_on` DATETIME NOT NULL AFTER `approved_by`;";
			$this->Mmm->query($sql2,"Added verified and approved by on request for payment table.");

			$sql3 = "ALTER TABLE `ac_request_payment` CHANGE `vessel_id` `vessel_id_old` INT(5) UNSIGNED NULL DEFAULT NULL;";
			$this->Mmm->query($sql3,"Rename column name vessel_id so not to conflict during creation of control number.");

			$sql4 = "CREATE VIEW `inventory_requests_for_approval` AS SELECT `inventory_requests`.`id` AS `id`,`inventory_requests`.`control_number` AS `control_number`,`inventory_requests`.`tdate` AS `tdate`,`inventory_requests`.`requisitioner` AS `requisitioner`,`inventory_requests`.`vessel_id` AS `vessel_id`,`inventory_requests`.`department_id` AS `department_id`,`inventory_requests`.`remark` AS `remark`,`inventory_requests`.`stat` AS `stat`,`inventory_requests`.`status` AS `status`,`inventory_requests`.`priority` AS `priority`,`inventory_requests`.`added_by` AS `added_by`,`inventory_requests`.`purpose` AS `purpose`,`inventory_requests`.`added_on` AS `added_on`,`inventory_requests`.`approved_by` AS `approved_by`,`inventory_requests`.`approved_on` AS `approved_on` FROM (`inventory_request_details` JOIN `inventory_requests` on((`inventory_request_details`.`request_id` = `inventory_requests`.`id`))) WHERE (`inventory_request_details`.`status` = 'For Request Approval') GROUP BY `inventory_requests`.`id`;";
			$this->Mmm->query($sql4,"Create a view table to be used on Purchase Request with status 'for approval' in Managers Dashboard");
		}
		public function update_for_version_2_0_1(){ // v.2.0.0 released
			$sql1 = "CREATE TABLE IF NOT EXISTS `hr_bonus` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `release_date` date NOT NULL,
					  `amount` double NOT NULL,
					  `type` varchar(45) NOT NULL,
					  `remarks` varchar(190) NOT NULL,
					  `added_by` int(11) NOT NULL,
					  `added_on` datetime NOT NULL,
					  `approved_by` tinyint(1) NOT NULL,
					  `approved_on` datetime NOT NULL,
					  `is_computed` tinyint(1) NOT NULL,
					  `stat` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql1,"Create new table for employee bonuses/13th month pay recording.");

			$sql2 = "ALTER TABLE `hr_overtime` ADD `type` VARCHAR(45) NOT NULL AFTER `rate`;";
			$this->Mmm->query($sql2,"Added Overtime type.");

			$sql3 = "ALTER TABLE `hr_payroll_details` ADD `restday_overtime_hr` DECIMAL(11,2) NOT NULL AFTER `regular_overtime_amount`, ADD `restday_overtime_amount` DOUBLE NOT NULL AFTER `restday_overtime_hr`,ADD `specialholiday_overtime_hr` DECIMAL(11,2) NOT NULL AFTER `restday_overtime_amount`, ADD `specialholiday_overtime_amount` DOUBLE NOT NULL AFTER `specialholiday_overtime_hr`, ADD `specialholiday_restday_overtime_hr` DECIMAL(11,2) NOT NULL AFTER `specialholiday_overtime_amount`, ADD `specialholiday_restday_overtime_amount` DOUBLE NOT NULL AFTER `specialholiday_restday_overtime_hr`, ADD `legalholiday_overtime_hr` DECIMAL(11,2) NOT NULL AFTER `specialholiday_restday_overtime_amount`, ADD `legalholiday_overtime_amount` DOUBLE NOT NULL AFTER `legalholiday_overtime_hr`, ADD `legalholiday_restday_overtime_hr` DECIMAL(11,2) NOT NULL AFTER `legalholiday_overtime_amount`, ADD `legalholiday_restday_overtime_amount` DOUBLE NOT NULL AFTER `legalholiday_restday_overtime_hr`;";
			$this->Mmm->query($sql3,"Added Overtime columns for restday,special holiday, and legal holiday.");

			$sql4 = "UPDATE users_permissions SET page='human_resources|edit' WHERE page='employee_profile|edit'";
			$this->Mmm->query($sql4,"Change name of employee profile permission to human resources");

			$sql5 = "UPDATE users_permissions SET page='human_resources|add' WHERE page='employee_profile|add'";
			$this->Mmm->query($sql5,"Change name of employee profile permission to human resources");

			$sql6 = "UPDATE users_permissions SET page='human_resources|insert' WHERE page='emmployee_profile|insert'";
			$this->Mmm->query($sql6,"Change name of employee profile permission to human resources");

			$sql7 = "UPDATE users_permissions SET page='human_resources|view' WHERE page='employee_profile|view'";
			$this->Mmm->query($sql7,"Change name of employee profile permission to human resources");

			$sql8 = "UPDATE users_permissions SET page='human_resources|update' WHERE page='employee_profile|update'";
			$this->Mmm->query($sql8,"Change name of employee profile permission to human resources");

			$sql9 = "UPDATE users_permissions SET page='human_resources|loan' WHERE page='employee_profile|loan'";
			$this->Mmm->query($sql9,"Change name of employee profile permission to human resources");

			$sql10 = "UPDATE users_permissions SET page='human_resources|leave' WHERE page='employee_profile|leave'";
			$this->Mmm->query($sql10,"Change name of employee profile permission to human resources");

			$sql11 = "UPDATE users_permissions SET page='human_resources|elf' WHERE page='employee_profile|elf'";
			$this->Mmm->query($sql11,"Change name of employee profile permission to human resources");

			$sql12 = "UPDATE users_permissions SET page='human_resources|overtime' WHERE page='employee_profile|overtime'";
			$this->Mmm->query($sql12,"Change name of employee profile permission to human resources");

			$sql13 = "UPDATE users_permissions SET page='human_resources|salary_viewing' WHERE page='employee_profile|salary_viewing'";
			$this->Mmm->query($sql13,"Change name of employee profile permission to human resources");

			$sql14 = "UPDATE users_permissions SET page='human_resources|salary_editing' WHERE page='employee_profile|salary_editing'";
			$this->Mmm->query($sql14,"Change name of employee profile permission to human resources");

			$sql15 = "UPDATE users_permissions SET page='human_resources|approve_bonus' WHERE page='employee_profile|approve_bonus'";
			$this->Mmm->query($sql15,"Change name of employee profile permission to human resources");

			$sql16 = "UPDATE users_permissions SET page='human_resources|reports' WHERE page='employee_profile|reports'";
			$this->Mmm->query($sql16,"Change name of employee profile permission to human resources");

			$sql17 = "UPDATE users_permissions SET page='human_resources|forced_editing' WHERE page='employee_profile|forced_editing'";
			$this->Mmm->query($sql17,"Change name of employee profile permission to human resources");
		}
		public function update_for_version_2_0_0(){ //v.2.0.0 released
			$sql1 = 'ALTER TABLE `hr_employment_history` ADD `from_date` DATE NOT NULL AFTER `to_val`, ADD `to_date` DATE NOT NULL AFTER `from_date`;';
			$this->Mmm->query($sql1,"Added from/to date on employment history table for use on Suspension status.");

			$sql2='ALTER TABLE `hr_employment_history` CHANGE `added_on` `added_on` DATETIME NULL DEFAULT NULL;';
			$this->Mmm->query($sql2,"Update added_on from Int to Datetime");

			$sql3='UPDATE `hr_employment_history` SET value_changed="Employment Status" WHERE value_changed="Employee Status"';
			$this->Mmm->query($sql3,"Change Employee Status value to Employment Status in HR ehistory");

			$sql4='ALTER TABLE `hr_employee_dependents` ADD `dependent_relationship` VARCHAR(45) NOT NULL AFTER `birth_date`;';
			$this->Mmm->query($sql4,"Added Dependent's relationship to employee on employee form.");

			$sql5='ALTER TABLE `hr_employee_dependents` CHANGE `birth_date` `birth_date` DATE NOT NULL;';
			$this->Mmm->query($sql5,"Change dependents birthdate from datetime to date.");

			$sql6='UPDATE `hr_employment_history` SET value_changed="Assigned To" WHERE value_changed="Vessel"';
			$this->Mmm->query($sql6,"Change Vessel value to Assigned To in HR ehistory");

			$sql7='CREATE TABLE IF NOT EXISTS `hr_night_differential` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `night_diff_date` datetime NOT NULL,
					  `night_diff_hours` time NOT NULL,
					  `rate` double NOT NULL,
					  `reason` varchar(45) NOT NULL,
					  `added_by` int(11) NOT NULL,
					  `added_on` DATETIME NOT NULL,
					  `is_computed` tinyint(1) NOT NULL,
					  `computed_on` datetime NOT NULL,
					  `stat` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
			$this->Mmm->query($sql7,"Create new table for night differential computation.");

			$sql8='ALTER TABLE `hr_payroll_details` ADD `night_differential_hr` DECIMAL(11,2) NOT NULL AFTER `holiday_overtime_amount`, ADD `night_differential_amount` DOUBLE NOT NULL AFTER `night_differential_hr`;';
			$this->Mmm->query($sql8,"Added columns for night differential amount in Payroll.");

			$sql9='ALTER TABLE `hr_payroll` ADD `approved_by` INT NOT NULL AFTER `created_by`, ADD `approved_on` DATETIME NOT NULL AFTER `approved_by`;';
			$this->Mmm->query($sql9,"Added columns approved by and on for Payroll.");
			
		}

		public function update20181212(){ //v.1.0.0 released
			$sql1 = 'ALTER TABLE `inventory_audit` ADD `company_id` INT NOT NULL AFTER `id`, ADD `created_on` DATETIME NOT NULL AFTER `stat`, ADD `created_by` varchar(45) NOT NULL AFTER `created_on`, ADD `verified_by` INT NOT NULL, ADD `noted_by` INT NOT NULL, ADD `approved_by` INT NOT NULL, ADD `posted_by` INT NOT NULL, ADD `verified_on` DATETIME NOT NULL AFTER `status`, ADD `noted_on` DATETIME NOT NULL AFTER `verified_on`, ADD `approved_on` DATETIME NOT NULL AFTER `noted_on`, ADD `posted_on` DATETIME NOT NULL AFTER `approved_on` CHANGE `remarks` `type_of_inventory` TEXT, ADD status varchar(45) NOT NULL;';
			$this->Mmm->query($sql1,"Added required columns for inventory_audit table.");

			$sql2 ='CREATE TABLE IF NOT EXISTS `inventory_audit_cutoff_documents` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `audit_id` int(11) NOT NULL,
					  `document_name` varchar(255) NOT NULL,
					  `last_used` int(11) NOT NULL,
					  `date_last_used` date NOT NULL,
					  `first_unused` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
			$this->Mmm->query($sql2,"Add new table for Inventory Audit Cut-off Documents.");

			$sql3 = 'ALTER TABLE `inventory_audit_details` ADD `shelf_number` VARCHAR(45) NOT NULL AFTER `counted_qty`;';
			$this->Mmm->query($sql3,"Add new column shelf no. for Inventory Audit Details.");

		}
		public function update20181204(){
			$sql1 = 'CREATE TABLE IF NOT EXISTS `inventory_gatepass` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `vessel_id` int(11) NOT NULL,
					  `control_number` int(11) NOT NULL,
					  `issuance_id` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
			$this->Mmm->query($sql1,"Add new table for Inventory Issuance Gatepass.");
		}
		public function update20181203(){
			$sql1 = "ALTER TABLE `inventory_deliveries` ADD `notice_of_discrepancy_id` int(11) NOT NULL AFTER `is_issued`;";
			$this->Mmm->query($sql1,"Add new column on inventory_deliveries table for Notice of Discrepancy ID.");

			$sql2 = "CREATE TABLE IF NOT EXISTS `inventory_notice_of_discrepancy` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `control_number` int(11) NOT NULL,
					  `company_id` int(11) NOT NULL,
					  `supplier_id` int(11) NOT NULL,
					  `purchase_order_id` int(11) NOT NULL,
					  `reason_of_discrepancy` varchar(60) NOT NULL,
					  `date_of_delivery` date NOT NULL,
					  `delivery_receipt_number` varchar(45) NOT NULL,
					  `vehicle_plate_number` varchar(45) NOT NULL,
					  `name_of_driver` varchar(60) NOT NULL,
					  `other_remarks` varchar(255) NOT NULL,
					  `verified_by` int(11) NOT NULL,
					  `level1_approved_by` int(11) NOT NULL,
					  `level2_approved_by` int(11) NOT NULL,
					  `level3_approved_by` int(11) NOT NULL,
					  `cancelled_by` int(11) NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `created_on` datetime NOT NULL,
					  `status` varchar(45) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql2,"Add new new table for Notice of Discrepancy.");

			$sql3= "CREATE TABLE IF NOT EXISTS `inventory_notice_of_discrepancy_details` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `notice_of_discrepancy_id` int(11) NOT NULL,
					  `item_id` int(11) NOT NULL,
					  `quantity_po` int(11) NOT NULL,
					  `quantity_dr` int(11) NOT NULL,
					  `quantity_received` int(11) NOT NULL,
					  `remarks` varchar(45) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql3,"Add new new table for Notice of Discrepancy Details.");

		}
		public function update20181123(){
			//ok
			$sql1 = "CREATE TABLE IF NOT EXISTS `inventory_return` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `control_number` int(11) NOT NULL,
					  `company_id` int(11) NOT NULL,
					  `return_date` datetime NOT NULL,
					  `return_to` varchar(45) NOT NULL,
					  `return_from` varchar(45) NOT NULL,
					  `return_by` varchar(45) NOT NULL,
					  `remark` varchar(255) NOT NULL,
					  `stat` tinyint(1) NOT NULL DEFAULT '1',
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

			$sql2 = "CREATE TABLE IF NOT EXISTS `inventory_return_details` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `return_id` int(11) NOT NULL,
					  `item_id` int(11) NOT NULL,
					  `unit` varchar(45) NOT NULL,
					  `unit_price` double NOT NULL,
					  `qty` int(11) NOT NULL,
					  `old_qty` int(11) NOT NULL,
					  `stat` tinyint(1) NOT NULL DEFAULT '1',
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

			$this->Mmm->query($sql1,"Add new table for inventory return.");
			$this->Mmm->query($sql2,"Add new table for inventory return details.");
		}
		public function update20181009(){
			$this->Mmm->query("ALTER TABLE `statement_of_accounts` ADD `out_turn_summary_id` INT NULL AFTER `reference_number`","Add Out-turn ID as reference for SOA General for gservices: Shipping/Charter/Rental etc");
		}
		public function index() {$data=array();
			$this->Abas->redirect(HTTP_PATH."mastertables/db_activity");
		}
		public function update20181119(){
			$this->Mmm->query('ALTER TABLE `am_schedule_log_tasks` ADD `bill_of_materials_id` INT NULL AFTER `task_id`;', 'Added field BOM ID for schedule logs tasks table.');
		}
		public function update20180927(){
			$this->Mmm->query('ALTER TABLE `hr_payroll` ADD `is_cleared` BOOLEAN NOT NULL DEFAULT FALSE AFTER `control_number`', 'Added is_cleared column for the payroll entries clearing.');

			$this->Mmm->query('CREATE VIEW `ac_payroll_entries_for_clearing` AS SELECT * FROM hr_payroll WHERE is_cleared<>1 AND locked=1`', 'Added new views for payroll entries for clearing.');

			$this->Mmm->query("CREATE  VIEW `ac_payroll_entries_for_posting` AS SELECT ac_transactions.id AS id, hr_payroll.id AS payroll_id, hr_payroll.payroll_date, hr_payroll.payroll_coverage, hr_payroll.company_id, hr_payroll.payroll_amount,ac_transactions.id as trans_id, ac_transactions.date,ac_transactions.remark,ac_transactions.status FROM (`hr_payroll` JOIN `ac_transactions` ON((`hr_payroll`.`id` = `ac_transactions`.`reference_id`))) WHERE ((`ac_transactions`.`reference_table` = 'hr_payroll') and (`ac_transactions`.`status` = 'Active') and (`ac_transactions`.`stat` = 0))", "Added new view for payroll entries for posting.");

			$this->Mmm->query("CREATE  VIEW `ac_payroll_entries_posted` AS SELECT ac_transactions.id AS id, hr_payroll.id AS payroll_id, hr_payroll.payroll_date, hr_payroll.payroll_coverage, hr_payroll.company_id, hr_payroll.payroll_amount,ac_transactions.id as trans_id, ac_transactions.date,ac_transactions.remark,ac_transactions.status FROM (`hr_payroll` JOIN `ac_transactions` ON((`hr_payroll`.`id` = `ac_transactions`.`reference_id`))) WHERE ((`ac_transactions`.`reference_table` = 'hr_payroll') and (`ac_transactions`.`status` = 'Active') and (`ac_transactions`.`stat` = 1))", "Added new view for payroll entries for posted.");
		}
		public function update20180925(){
			$this->Mmm->query('ALTER TABLE `trucks` ADD `photo_path` VARCHAR(255) NULL, ADD `created_by` INT NULL, ADD `created_on` DATETIME NULL, ADD `modified_by` INT NULL, ADD `modified_on` DATETIME NULL', 'Added fields for the trucks table.');
			$this->Mmm->query('ALTER TABLE `trucks` CHANGE `date_acquired` `date_acquired` DATE NULL DEFAULT NULL', 'Change data type of Date Acquired column.');
		}
		public function update20180906(){
			$this->Mmm->query("ALTER TABLE `ac_journal_vouchers` ADD `filing_number` int(11) NULL AFTER `control_number`", "Added filing number for JV based on user location.");
			$this->Mmm->query("ALTER TABLE `ac_journal_vouchers` ADD `created_at` varchar(45) NULL AFTER `created_on`", "Added 'created at' for JV based on user location.");
		}
		public function update20180730(){
			$this->Mmm->query("ALTER TABLE `inventory_items` ADD `asset_code` varchar(256) NULL AFTER `item_code`", "Added Asset/Equipment Code field for items considered high valuable asset.");
		}
		public function update20180727() {
			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary` ADD `submitted_by` INT NULL AFTER `created_on`, ADD `submitted_on` DATETIME NULL AFTER `submitted_by`", "Alter table for out turn summary to capture details of submission.");
			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary` ADD `verified_by` INT NULL AFTER `submitted_on`, ADD `verified_on` DATETIME NULL AFTER `verified_by`", "Alter table for out turn summary to capture details of verification.");
			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary` ADD `approved_by` INT NULL AFTER `verified_on`, ADD `approved_on` DATETIME NULL AFTER `approved_by`", "Alter table for out turn summary to capture details of approval.");
			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary` ADD `times_returned_to_draft` INT NULL AFTER `approved_on`", "Alter table for out turn summary to capture how many times an out turn summary is returned to draft status.");
		}
		public function update20180726(){
			$this->Mmm->query("ALTER TABLE `payments_bank_transfer_breakdown` ADD `acknowledgement_receipt_id` INT NULL AFTER `payment_id`","Alter table for payment bank/fund transfer to allow Acknowledgement Receipt.");
		}
		public function update20180721(){
			$this->Mmm->query("ALTER TABLE `payments_daily_report_details` ADD `official_receipt_id` INT NULL AFTER `payment_id`, ADD `acknowledgement_receipt_id` INT NULL AFTER `official_receipt_id`", "Alter table for DCCRR details to capture OR and AR.");
		}
		public function update20180713(){
			$this->Mmm->query("ALTER TABLE `am_schedule_log_tasks` ADD `remarks` TEXT NULL AFTER `percentage`","Added remarks/ commenting on Schedule log tasks.");
		}
		public function update20180704(){

			$this->Mmm->query("ALTER TABLE `statement_of_accounts` ADD `is_cleared` BOOLEAN NOT NULL DEFAULT FALSE AFTER `status`", "Alter table for SOA for checking if it already cleared on accounting.");

			$this->Mmm->query("ALTER TABLE `payments` ADD `is_cleared` BOOLEAN NOT NULL DEFAULT FALSE AFTER `status`", "Alter table for payments for checking if it already cleared on accounting.");

			$this->Mmm->query("ALTER TABLE `payments` ADD `comments` VARCHAR(255) NULL AFTER `is_cleared`;", "Alter table to add comments for inputting reason for cancelled payments.");

			$this->Mmm->query("ALTER VIEW ac_accounts_receivable_for_clearing AS SELECT * FROM statement_of_accounts WHERE is_cleared<>1 AND (statement_of_accounts.status='Approved' OR statement_of_accounts.status='Waiting for Payment')", "Alter view table for Accounts Receivable to optimize and improve loading time");

			$this->Mmm->query("ALTER VIEW ac_accounts_collection_for_clearing AS SELECT * FROM payments WHERE is_cleared<>1", "Alter view table for Accounts Collection to optimize and improve loading time");

		}
		public function update20180705() {
			$sql		=	"CREATE TABLE IF NOT EXISTS `pageloads` (
								`id` int(11) NOT NULL AUTO_INCREMENT,
								`page` varchar(256) DEFAULT NULL,
								`parameters` text DEFAULT NULL,
								`loadtime` varchar(255) DEFAULT NULL,
								`loaded_by` int(11) DEFAULT NULL,
								`loaded_on` datetime DEFAULT NULL,
								`stat` tinyint(1) DEFAULT NULL,
								PRIMARY KEY (`id`)
							)";
			$this->Mmm->query($sql, "Page load time database for identifying 'heavy' systems");
		}
		public function update20180517(){
			$this->Mmm->query("ALTER TABLE `sms_reports` ADD COLUMN `weather` varchar(256) NULL;", "Enable tracking of weather at coordinates via API call");
		}
		public function update20180613(){
			$sql = "CREATE TABLE IF NOT EXISTS `service_order_detail_rental` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `service_order_id` int(11) DEFAULT NULL,
					  `equipment_name` varchar(45) DEFAULT NULL,
					  `description` varchar(255) DEFAULT NULL,
					  `quantity` double DEFAULT NULL,
					  `unit` varchar(45) DEFAULT NULL,
					  `start_date` datetime DEFAULT NULL,
					  `end_date` datetime DEFAULT NULL,
					  `from_location` varchar(255) DEFAULT NULL,
					  `to_location` varchar(255) DEFAULT NULL,
					  `stat` tinyint(1) DEFAULT NULL,
					   PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "Added new table for Service Order Details - Equipment Rental");
		}
		public function update20180531(){
			$sql1 = "CREATE TABLE IF NOT EXISTS `inventory_job_orders` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `tdate` timestamp NULL DEFAULT NULL,
					  `deliver_on` datetime DEFAULT NULL,
					  `supplier_id` int(11) DEFAULT NULL,
					  `amount` double DEFAULT NULL,
					  `stat` tinyint(4) DEFAULT NULL,
					  `location` varchar(45) DEFAULT NULL,
					  `remark` varchar(255) DEFAULT NULL,
					  `purpose` varchar(255) DEFAULT NULL,
					  `company_id` int(11) DEFAULT NULL,
					  `reference_num` varchar(45) DEFAULT NULL,
					  `extended_tax` varchar(256) DEFAULT NULL,
					  `value_added_tax` varchar(256) DEFAULT NULL,
					  `discount` double DEFAULT NULL,
					  `added_by` int(11) DEFAULT NULL,
					  `added_on` datetime DEFAULT NULL,
					  `request_id` int(11) DEFAULT NULL,
					  `control_number` int(11) DEFAULT NULL,
					  `status` varchar(256) DEFAULT NULL,
					  `payment_terms` varchar(256) DEFAULT NULL,
					  `approved_by` int(11) DEFAULT NULL,
					  `approved_on` datetime DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

			$this->Mmm->query($sql1, "Added new table for Job Orders");

			$sql2 = "CREATE TABLE IF NOT EXISTS `inventory_job_order_details` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `job_order_id` int(11) DEFAULT NULL,
					  `item_id` int(11) DEFAULT NULL,
					  `unit` varchar(45) DEFAULT NULL,
					  `unit_price` double DEFAULT NULL,
					  `quantity` double DEFAULT NULL,
					  `stat` tinyint(1) DEFAULT NULL,
					  `remarks` varchar(255) DEFAULT NULL,
					  `request_id` int(11) DEFAULT NULL,
					  `request_detail_id` int(11) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

			$this->Mmm->query($sql2, "Added new table for Job Order Details");
		}
		public function update20180510(){
			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary_output` CHANGE `shipper_number_of_bags` `shipper_number_of_bags` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary_output` CHANGE `shipper_weight` `shipper_weight` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary_output` CHANGE `consignee_number_of_bags` `consignee_number_of_bags` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary_output` CHANGE `consignee_weight` `consignee_weight` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary_output` CHANGE `variance_number_of_bags` `variance_number_of_bags` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary_output` CHANGE `variance_weight` `variance_weight` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary_output` CHANGE `good_number_of_bags` `good_number_of_bags` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary_output` CHANGE `damaged_number_of_bags` `damaged_number_of_bags` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `ops_out_turn_summary_output` CHANGE `total_number_of_bags` `total_number_of_bags` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$sql11 = "CREATE TABLE IF NOT EXISTS `am_schedule_logs` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `control_number` int(11) DEFAULT NULL,
					  `company_id` int(11) DEFAULT NULL,
					  `bill_of_materials_id` int(11) DEFAULT NULL,
					  `asset_id` int(11) DEFAULT NULL,
					  `type` varchar(45) DEFAULT NULL,
					  `created_by` int(11) DEFAULT NULL,
					  `created_on` datetime DEFAULT NULL,
					  `updated_by` int(11) DEFAULT NULL,
					  `updated_on` datetime DEFAULT NULL,
					  `status` varchar(45) DEFAULT NULL,
					   PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql1, "Added new table for Schedule Logs");


			$sql2 = "CREATE TABLE IF NOT EXISTS `am_schedule_log_tasks` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `schedule_log_id` int(11) DEFAULT NULL,
					  `task_id` int(11) DEFAULT NULL,
					  `personnel_in_charge` varchar(255) DEFAULT NULL,
					  `plan_start_date` date DEFAULT NULL,
					  `plan_end_date` date DEFAULT NULL,
					  `actual_start_date` date DEFAULT NULL,
					  `actual_end_date` date DEFAULT NULL,
					  `actual_work_duration` double DEFAULT NULL,
					  `percentage` int(11) DEFAULT NULL,
					   PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

			$this->Mmm->query($sql2, "Added new table for Schedule Log Tasks");
		}
		public function update20180508(){
	      $sql_view1 = "ALTER VIEW `ac_inventory_issuances_for_clearing` AS SELECT * FROM inventory_issuance WHERE is_cleared<>1";
	      $this->Mmm->query($sql_view1, "Changed View for Inventory Issuance - For Clearing");

	      $sql_view2 = "ALTER TABLE `inventory_issuance` ADD COLUMN `is_cleared` tinyint(1)";
	      $this->Mmm->query($sql_view2, "Added column for inventory issuances for checking if is cleared already on accounting");

	      $this->Mmm->query("ALTER TABLE `statement_of_accounts` ADD COLUMN `wtax_15_percent` tinyint(1) AFTER `vat_5_percent`;","For SOA Trucking additional charges");

	    }
		public function update20180426(){

			$this->Mmm->query("ALTER TABLE `statement_of_account_cargo_out_turn` CHANGE `quantity` `quantity` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `statement_of_account_cargo_out_turn` CHANGE `total_weight` `total_weight` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `statement_of_account_cargo_out_turn` CHANGE `rate` `rate` DOUBLE(11,3) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `statement_of_account_details` CHANGE `quantity` `quantity` DOUBLE(11,4) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$this->Mmm->query("ALTER TABLE `statement_of_account_details` CHANGE `rate` `rate` DOUBLE(11,3) NULL DEFAULT NULL;","Change to double with 4 decimal places");

			$sql	=	"CREATE TABLE IF NOT EXISTS `ac_transaction_attachments` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `transaction_id` int(11) DEFAULT NULL,
						  `doucment_name` varchar(255) DEFAULT NULL,
						  `document_file` varchar(255) DEFAULT NULL,
						   PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "Added missing table for Accounts Attachments");


		}
		public function update20180414(){
			$sql_view_1	=	"CREATE  VIEW `ac_inventory_issuances_for_clearing` AS select `inventory_issuance`.`id` AS `id`,`inventory_issuance`.`issue_date` AS `issue_date`,`inventory_issuance`.`request_no` AS `request_no`,`inventory_issuance`.`issuance_no` AS `issuance_no`,`inventory_issuance`.`issued_to` AS `issued_to`,`inventory_issuance`.`vessel_id` AS `vessel_id`,`inventory_issuance`.`from_location` AS `from_location`,`inventory_issuance`.`stat` AS `stat`,`inventory_issuance`.`remark` AS `remark`,`inventory_issuance`.`control_number` AS `control_number` from `inventory_issuance` where (not(exists(select `ac_transaction_journal`.`id` from `ac_transaction_journal` where ((`ac_transaction_journal`.`reference_table` = 'inventory_issuance') and (`ac_transaction_journal`.`reference_id` = `inventory_issuance`.`id`)))));";
			$this->Mmm->query($sql_view_1, "Create veiw for Inventory Issuance - For Clearing");

			$sql_view_2	=	"CREATE  VIEW `ac_inventory_issuances_for_posting` AS select `inventory_issuance`.`id` AS `id`,`inventory_issuance`.`issue_date` AS `issue_date`,`inventory_issuance`.`request_no` AS `request_no`,`inventory_issuance`.`issuance_no` AS `issuance_no`,`inventory_issuance`.`issued_to` AS `issued_to`,`inventory_issuance`.`vessel_id` AS `vessel_id`,`inventory_issuance`.`from_location` AS `from_location`,`inventory_issuance`.`remark` AS `remark`,`inventory_issuance`.`control_number` AS `control_number`,`ac_transactions`.`id` AS `transaction_id`,`ac_transactions`.`reference_table` AS `reference_table`,`ac_transactions`.`reference_id` AS `reference_id`,`ac_transactions`.`status` AS `status`,`ac_transactions`.`stat` AS `stat` from (`inventory_issuance` join `ac_transactions` on((`inventory_issuance`.`id` = `ac_transactions`.`reference_id`))) where ((`ac_transactions`.`reference_table` = 'inventory_issuance') and (`ac_transactions`.`status` = 'Active') and (`ac_transactions`.`stat` = 0));";
			$this->Mmm->query($sql_view_2, "Create veiw for Inventory Issuance - For Posting");

			$sql_view_3	=	"CREATE  VIEW `ac_inventory_issuances_posted` AS select `inventory_issuance`.`id` AS `id`,`inventory_issuance`.`issue_date` AS `issue_date`,`inventory_issuance`.`request_no` AS `request_no`,`inventory_issuance`.`issuance_no` AS `issuance_no`,`inventory_issuance`.`issued_to` AS `issued_to`,`inventory_issuance`.`vessel_id` AS `vessel_id`,`inventory_issuance`.`from_location` AS `from_location`,`inventory_issuance`.`remark` AS `remark`,`inventory_issuance`.`control_number` AS `control_number`,`ac_transactions`.`id` AS `transaction_id`,`ac_transactions`.`reference_table` AS `reference_table`,`ac_transactions`.`reference_id` AS `reference_id`,`ac_transactions`.`status` AS `status`,`ac_transactions`.`stat` AS `stat` from (`inventory_issuance` join `ac_transactions` on((`inventory_issuance`.`id` = `ac_transactions`.`reference_id`))) where ((`ac_transactions`.`reference_table` = 'inventory_issuance') and (`ac_transactions`.`status` = 'Active') and (`ac_transactions`.`stat` = 1));";
			$this->Mmm->query($sql_view_3, "Create veiw for Inventory Issuance - Posted");

		}
		public function update201804010(){
			$this->Mmm->query("DROP TABLE IF EXISTS `payments_daily_report_details`;", "Remove old table for DCCRR");

			$sql	=	"CREATE TABLE IF NOT EXISTS `payments_daily_report_details` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `daily_report_id` int(11) DEFAULT NULL,
						  `payment_id` int(11) DEFAULT NULL,
						  `payment_status` varchar(45) DEFAULT NULL,
						  `payment_mode` varchar(45) DEFAULT NULL,
						  `check_bank` varchar(255) DEFAULT NULL,
						  `check_amount` double(11,2) DEFAULT NULL,
						  `cash_denomination` double(11,2) DEFAULT NULL,
						  `cash_quantity` int(11) DEFAULT NULL,
						   PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "Added new table DCCRR");
		}
		public function update20180412_HOTFIX(){
			$sql_view1 = "ALTER VIEW `ac_accounts_receivable_for_clearing` AS select `statement_of_accounts`.`id` AS `id`,`statement_of_accounts`.`control_number` AS `control_number`,`statement_of_accounts`.`contract_id` AS `contract_id`,`statement_of_accounts`.`client_id` AS `client_id`,`statement_of_accounts`.`company_id` AS `company_id`,`statement_of_accounts`.`reference_number` AS `reference_number`,`statement_of_accounts`.`terms` AS `terms`,`statement_of_accounts`.`services` AS `services`,`statement_of_accounts`.`type` AS `type`,`statement_of_accounts`.`add_tax` AS `add_tax`,`statement_of_accounts`.`vat_12_percent` AS `vat_12_percent`,`statement_of_accounts`.`vat_5_percent` AS `vat_5_percent`,`statement_of_accounts`.`wtax_2_percent` AS `wtax_2_percent`,`statement_of_accounts`.`wtax_1_percent` AS `wtax_1_percent`,`statement_of_accounts`.`description` AS `description`,`statement_of_accounts`.`sent_to_client_on` AS `sent_to_client_on`,`statement_of_accounts`.`created_on` AS `created_on`,`statement_of_accounts`.`created_by` AS `created_by`,`statement_of_accounts`.`status` AS `status`,`statement_of_accounts`.`comments` AS `comments` from `statement_of_accounts` where ((`statement_of_accounts`.`status` = 'Approved' OR `statement_of_accounts`.`status` = 'Waiting for Payment') and (not(exists(select `ac_transactions`.`id` from `ac_transactions` where ((`ac_transactions`.`reference_table` = 'statement_of_accounts') and (`statement_of_accounts`.`id` = `ac_transactions`.`reference_id`))))));";
			$this->Mmm->query($sql_view1, "Changed View for Accounts Receivables - For Clearing");
		}
		public function update20180319(){
			$this->Mmm->query("ALTER TABLE `service_contracts_rates` ADD COLUMN `quantity` double DEFAULT NULL, ADD COLUMN `unit` varchar(45) DEFAULT NULL, ADD COLUMN `additional_charges` double DEFAULT NULL","Add qty, unit, charges per rates on Contract.");
		}
		public function update20180315_HOTFIX(){
			//hotfix for Accounts Receivables/Collection incorrect Status appearing due to missing 'Stat' column that is being used in the condition for showing the actual status.

			$sql_view1="ALTER VIEW `ac_accounts_receivable_for_posting` AS select `statement_of_accounts`.`id` AS `id`,`statement_of_accounts`.`control_number` AS `control_number`,`statement_of_accounts`.`contract_id` AS `contract_id`,`statement_of_accounts`.`client_id` AS `client_id`,`statement_of_accounts`.`company_id` AS `company_id`,`statement_of_accounts`.`reference_number` AS `reference_number`,`statement_of_accounts`.`terms` AS `terms`,`statement_of_accounts`.`services` AS `services`,`statement_of_accounts`.`type` AS `type`,`statement_of_accounts`.`add_tax` AS `add_tax`,`statement_of_accounts`.`vat_12_percent` AS `vat_12_percent`,`statement_of_accounts`.`vat_5_percent` AS `vat_5_percent`,`statement_of_accounts`.`wtax_2_percent` AS `wtax_2_percent`,`statement_of_accounts`.`wtax_1_percent` AS `wtax_1_percent`,`statement_of_accounts`.`description` AS `description`,`statement_of_accounts`.`sent_to_client_on` AS `sent_to_client_on`,`statement_of_accounts`.`created_on` AS `created_on`,`statement_of_accounts`.`created_by` AS `created_by`,`statement_of_accounts`.`status` AS `status`,`statement_of_accounts`.`comments` AS `comments`,`ac_transactions`.`reference_table` AS `reference_table`,`ac_transactions`.`reference_id` AS `reference_id`,`ac_transactions`.`id` AS `transaction_id`,`ac_transactions`.`created_on` AS `trans_created_on`,`ac_transactions`.`created_by` AS `trans_created_by`,`ac_transactions`.`stat` AS `stat` from (`ac_transactions` join `statement_of_accounts` on((`ac_transactions`.`reference_id` = `statement_of_accounts`.`id`))) where ((`ac_transactions`.`reference_table` = 'statement_of_accounts') and (`ac_transactions`.`status` = 'Active') and (`ac_transactions`.`stat` = 0));";
			 $this->Mmm->query($sql_view1, "Added Stat column on AR view - For posting");

			 $sql_view2="ALTER VIEW `ac_accounts_receivable_posted` AS select `statement_of_accounts`.`id` AS `id`,`statement_of_accounts`.`control_number` AS `control_number`,`statement_of_accounts`.`contract_id` AS `contract_id`,`statement_of_accounts`.`client_id` AS `client_id`,`statement_of_accounts`.`company_id` AS `company_id`,`statement_of_accounts`.`reference_number` AS `reference_number`,`statement_of_accounts`.`terms` AS `terms`,`statement_of_accounts`.`services` AS `services`,`statement_of_accounts`.`type` AS `type`,`statement_of_accounts`.`add_tax` AS `add_tax`,`statement_of_accounts`.`vat_12_percent` AS `vat_12_percent`,`statement_of_accounts`.`vat_5_percent` AS `vat_5_percent`,`statement_of_accounts`.`wtax_2_percent` AS `wtax_2_percent`,`statement_of_accounts`.`wtax_1_percent` AS `wtax_1_percent`,`statement_of_accounts`.`description` AS `description`,`statement_of_accounts`.`sent_to_client_on` AS `sent_to_client_on`,`statement_of_accounts`.`created_on` AS `created_on`,`statement_of_accounts`.`created_by` AS `created_by`,`statement_of_accounts`.`status` AS `status`,`statement_of_accounts`.`comments` AS `comments`,`ac_transactions`.`reference_table` AS `reference_table`,`ac_transactions`.`reference_id` AS `reference_id`,`ac_transactions`.`id` AS `transaction_id`,`ac_transactions`.`created_on` AS `trans_created_on`,`ac_transactions`.`created_by` AS `trans_created_by`,`ac_transactions`.`stat` AS `stat` from (`ac_transactions` join `statement_of_accounts` on((`ac_transactions`.`reference_id` = `statement_of_accounts`.`id`))) where ((`ac_transactions`.`reference_table` = 'statement_of_accounts') and (`ac_transactions`.`status` = 'Active') and (`ac_transactions`.`stat` = 1));";
			$this->Mmm->query($sql_view2, "Added Stat column on AR View - Posted");

			$sql_view3 = "ALTER VIEW `ac_accounts_collection_for_posting` AS select `payments`.`id` AS `id`,`payments`.`control_number` AS `control_number`,`payments`.`company_id` AS `company_id`,`payments`.`payment_type` AS `payment_type`,`payments`.`soa_id` AS `soa_id`,`payments`.`payor` AS `payor`,`payments`.`TIN` AS `TIN`,`payments`.`address` AS `address`,`payments`.`business_style` AS `business_style`,`payments`.`mode_of_collection` AS `mode_of_collection`,`payments`.`particulars` AS `particulars`,`payments`.`gross_amount` AS `gross_amount`,`payments`.`vat_type` AS `vat_type`,`payments`.`tax_12_percent` AS `tax_12_percent`,`payments`.`vatable_amount` AS `vatable_amount`,`payments`.`tax_5_percent` AS `tax_5_percent`,`payments`.`tax_2_percent` AS `tax_2_percent`,`payments`.`tax_1_percent` AS `tax_1_percent`,`payments`.`discount` AS `discount`,`payments`.`other_deductions` AS `other_deductions`,`payments`.`senior_citizen_id` AS `senior_citizen_id`,`payments`.`person_with_disability_id` AS `person_with_disability_id`,`payments`.`net_amount` AS `net_amount`,`payments`.`received_on` AS `received_on`,`payments`.`received_by` AS `received_by`,`payments`.`status` AS `status`,`ac_transaction_journal`.`reference_table` AS `reference_table`,`ac_transaction_journal`.`reference_id` AS `reference_id`,`ac_transactions`.`created_by` AS `created_by`,`ac_transactions`.`id` AS `transaction_id`,`ac_transaction_journal`.`stat` AS `stat` from ((`ac_transactions` join `ac_transaction_journal` on((`ac_transactions`.`id` = `ac_transaction_journal`.`transaction_id`))) join `payments` on((`ac_transaction_journal`.`reference_id` = `payments`.`id`))) where ((`ac_transaction_journal`.`reference_table` = 'payments') and (`ac_transaction_journal`.`stat` = 0)) group by `payments`.`id`;";
			$this->Mmm->query($sql_view3, "Added Stat column on AC View - For posting");

			$sql_view4 = "ALTER VIEW `ac_accounts_collection_posted` AS select `payments`.`id` AS `id`,`payments`.`control_number` AS `control_number`,`payments`.`company_id` AS `company_id`,`payments`.`payment_type` AS `payment_type`,`payments`.`soa_id` AS `soa_id`,`payments`.`payor` AS `payor`,`payments`.`TIN` AS `TIN`,`payments`.`address` AS `address`,`payments`.`business_style` AS `business_style`,`payments`.`mode_of_collection` AS `mode_of_collection`,`payments`.`particulars` AS `particulars`,`payments`.`gross_amount` AS `gross_amount`,`payments`.`vat_type` AS `vat_type`,`payments`.`tax_12_percent` AS `tax_12_percent`,`payments`.`vatable_amount` AS `vatable_amount`,`payments`.`tax_5_percent` AS `tax_5_percent`,`payments`.`tax_2_percent` AS `tax_2_percent`,`payments`.`tax_1_percent` AS `tax_1_percent`,`payments`.`discount` AS `discount`,`payments`.`other_deductions` AS `other_deductions`,`payments`.`senior_citizen_id` AS `senior_citizen_id`,`payments`.`person_with_disability_id` AS `person_with_disability_id`,`payments`.`net_amount` AS `net_amount`,`payments`.`received_on` AS `received_on`,`payments`.`received_by` AS `received_by`,`payments`.`status` AS `status`,`ac_transaction_journal`.`reference_table` AS `reference_table`,`ac_transaction_journal`.`reference_id` AS `reference_id`,`ac_transactions`.`created_by` AS `created_by`,`ac_transactions`.`id` AS `transaction_id`
				,`ac_transaction_journal`.`stat` AS `stat` from ((`ac_transactions` join `ac_transaction_journal` on((`ac_transactions`.`id` = `ac_transaction_journal`.`transaction_id`))) join `payments` on((`ac_transaction_journal`.`reference_id` = `payments`.`id`))) where ((`ac_transaction_journal`.`reference_table` = 'payments') and (`ac_transaction_journal`.`stat` = 1)) group by `payments`.`id`;";
			$this->Mmm->query($sql_view4, "Added Stat column on AC View - Posted");

		}
		public function update20180313(){
			$this->Mmm->query("ALTER TABLE `statement_of_account_cargo_out_turn` ADD COLUMN `tail_end_handling` tinyint(1) DEFAULT NULL AFTER `empty_sacks`","Add Drop-off quantity columns in SO for trucking");
		}
		public function update20180228(){
			$this->Mmm->query("ALTER TABLE `service_order_detail_trucking` ADD COLUMN `drop_off_point_1` VARCHAR(255) AFTER `to_location`, ADD COLUMN `drop_off_point_2` VARCHAR(255) AFTER `drop_off_point_1`, ADD COLUMN `drop_off_point_3` VARCHAR(255) AFTER `drop_off_point_2`, ADD COLUMN `drop_off_point_4` VARCHAR(255) AFTER `drop_off_point_3`","Add Drop-off points columns in SO for trucking");
			$this->Mmm->query("ALTER TABLE `service_order_detail_trucking` ADD COLUMN `drop_off_quantity_1` VARCHAR(255) AFTER `drop_off_point_4`, ADD COLUMN `drop_off_quantity_2` VARCHAR(255) AFTER `drop_off_quantity_1`, ADD COLUMN `drop_off_quantity_3` VARCHAR(255) AFTER `drop_off_quantity_2`, ADD COLUMN `drop_off_quantity_4` VARCHAR(255) AFTER `drop_off_quantity_3`","Add Drop-off quantity columns in SO for trucking");

			$sql1	=	"CREATE TABLE IF NOT EXISTS `service_order_detail_storage` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `service_order_id` int(11) DEFAULT NULL,
						  `cargo_description` varchar(255) DEFAULT NULL,
						  `storage_location` varchar(255) DEFAULT NULL,
						  `quantity` double DEFAULT NULL,
						  `unit` varchar(45) DEFAULT NULL,
						  `start_date` date DEFAULT NULL,
						  `end_date` date DEFAULT NULL,
						  `stat` tinyint(1) DEFAULT NULL,
						   PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql1, "Added new table for SO -Storage");

			$sql2	=	"CREATE TABLE IF NOT EXISTS `logout_failures` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `session_id` varchar(256) DEFAULT NULL,
						  `user_id` int(11) DEFAULT NULL,
						  `created_on` datetime DEFAULT NULL,
						   PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql2, "Created logout failure table for users who idle too long");

			$sql	=	"CREATE TABLE IF NOT EXISTS `logout_failures` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `session_id` varchar(256) DEFAULT NULL,
						  `user_id` int(11) DEFAULT NULL,
						  `created_on` timestamp DEFAULT NULL,
						   PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "Created logout failure table for users who idle too long");
		}
		public function update20180220(){
			$sql1 = "ALTER TABLE `service_contracts` ADD `vat_type` VARCHAR(45) NULL AFTER `terms_of_payment`;";
			$this->Mmm->query($sql1, "Added VAT Type identification on Service Contracts");

			$sql2 = "CREATE TABLE IF NOT EXISTS `service_contracts_rates` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `service_contract_id` int(11) DEFAULT NULL,
					  `warehouse` varchar(255) DEFAULT NULL,
					  `quantity` double DEFAULT NULL,
					  `unit` varchar(45) DEFAULT NULL,
					  `rate` double DEFAULT NULL,
					  `additional_charges` double DEFAULT NULL,
					  PRIMARY KEY (`id`)
					);";
			$this->Mmm->query($sql2, "Added new table to store the per Warehouse Rates on Service Contracts");
		}
		public function update20180219(){
			$this->Mmm->query("ALTER TABLE db_activity ADD COLUMN is_valid_sql tinyint DEFAULT NULL", "Add indicator whether SQL is valid or not");
		}
		public function update20180218(){
			if(ENVIRONMENT=="production") $this->Abas->redirect(HTTP_PATH);
			$sql_view1 = "CREATE VIEW `ac_accounts_collection_for_clearing` AS select `payments`.`id` AS `id`,`payments`.`control_number` AS `control_number`,`payments`.`company_id` AS `company_id`,`payments`.`payment_type` AS `payment_type`,`payments`.`soa_id` AS `soa_id`,`payments`.`payor` AS `payor`,`payments`.`TIN` AS `TIN`,`payments`.`address` AS `address`,`payments`.`business_style` AS `business_style`,`payments`.`mode_of_collection` AS `mode_of_collection`,`payments`.`particulars` AS `particulars`,`payments`.`gross_amount` AS `gross_amount`,`payments`.`vat_type` AS `vat_type`,`payments`.`tax_12_percent` AS `tax_12_percent`,`payments`.`vatable_amount` AS `vatable_amount`,`payments`.`tax_5_percent` AS `tax_5_percent`,`payments`.`tax_2_percent` AS `tax_2_percent`,`payments`.`tax_1_percent` AS `tax_1_percent`,`payments`.`discount` AS `discount`,`payments`.`other_deductions` AS `other_deductions`,`payments`.`senior_citizen_id` AS `senior_citizen_id`,`payments`.`person_with_disability_id` AS `person_with_disability_id`,`payments`.`net_amount` AS `net_amount`,`payments`.`received_on` AS `received_on`,`payments`.`received_by` AS `received_by`,`payments`.`status` AS `status` from `payments` where (not(exists(select `ac_transaction_journal`.`id` from `ac_transaction_journal` where ((`ac_transaction_journal`.`reference_table` = 'payments') and (`payments`.`id` = `ac_transaction_journal`.`reference_id`)))));";
			$this->Mmm->query($sql_view1, "Create View for Accounts Collection - For Clearing");

			$sql_view2 = "CREATE VIEW `ac_accounts_collection_for_posting` AS select `payments`.`id` AS `id`,`payments`.`control_number` AS `control_number`,`payments`.`company_id` AS `company_id`,`payments`.`payment_type` AS `payment_type`,`payments`.`soa_id` AS `soa_id`,`payments`.`payor` AS `payor`,`payments`.`TIN` AS `TIN`,`payments`.`address` AS `address`,`payments`.`business_style` AS `business_style`,`payments`.`mode_of_collection` AS `mode_of_collection`,`payments`.`particulars` AS `particulars`,`payments`.`gross_amount` AS `gross_amount`,`payments`.`vat_type` AS `vat_type`,`payments`.`tax_12_percent` AS `tax_12_percent`,`payments`.`vatable_amount` AS `vatable_amount`,`payments`.`tax_5_percent` AS `tax_5_percent`,`payments`.`tax_2_percent` AS `tax_2_percent`,`payments`.`tax_1_percent` AS `tax_1_percent`,`payments`.`discount` AS `discount`,`payments`.`other_deductions` AS `other_deductions`,`payments`.`senior_citizen_id` AS `senior_citizen_id`,`payments`.`person_with_disability_id` AS `person_with_disability_id`,`payments`.`net_amount` AS `net_amount`,`payments`.`received_on` AS `received_on`,`payments`.`received_by` AS `received_by`,`payments`.`status` AS `status`,`ac_transaction_journal`.`reference_table` AS `reference_table`,`ac_transaction_journal`.`reference_id` AS `reference_id`,`ac_transactions`.`created_by` AS `created_by`,`ac_transactions`.`id` AS `transaction_id` from ((`ac_transactions` join `ac_transaction_journal` on((`ac_transactions`.`id` = `ac_transaction_journal`.`transaction_id`))) join `payments` on((`ac_transaction_journal`.`reference_id` = `payments`.`id`))) where ((`ac_transaction_journal`.`reference_table` = 'payments') and (`ac_transaction_journal`.`stat` = 0)) group by `payments`.`id`;";
			$this->Mmm->query($sql_view2, "Create View for Accounts Collection - For Posting");

			$sql_view3 = "CREATE VIEW `ac_accounts_collection_posted` AS select `payments`.`id` AS `id`,`payments`.`control_number` AS `control_number`,`payments`.`company_id` AS `company_id`,`payments`.`payment_type` AS `payment_type`,`payments`.`soa_id` AS `soa_id`,`payments`.`payor` AS `payor`,`payments`.`TIN` AS `TIN`,`payments`.`address` AS `address`,`payments`.`business_style` AS `business_style`,`payments`.`mode_of_collection` AS `mode_of_collection`,`payments`.`particulars` AS `particulars`,`payments`.`gross_amount` AS `gross_amount`,`payments`.`vat_type` AS `vat_type`,`payments`.`tax_12_percent` AS `tax_12_percent`,`payments`.`vatable_amount` AS `vatable_amount`,`payments`.`tax_5_percent` AS `tax_5_percent`,`payments`.`tax_2_percent` AS `tax_2_percent`,`payments`.`tax_1_percent` AS `tax_1_percent`,`payments`.`discount` AS `discount`,`payments`.`other_deductions` AS `other_deductions`,`payments`.`senior_citizen_id` AS `senior_citizen_id`,`payments`.`person_with_disability_id` AS `person_with_disability_id`,`payments`.`net_amount` AS `net_amount`,`payments`.`received_on` AS `received_on`,`payments`.`received_by` AS `received_by`,`payments`.`status` AS `status`,`ac_transaction_journal`.`reference_table` AS `reference_table`,`ac_transaction_journal`.`reference_id` AS `reference_id`,`ac_transactions`.`created_by` AS `created_by`,`ac_transactions`.`id` AS `transaction_id` from ((`ac_transactions` join `ac_transaction_journal` on((`ac_transactions`.`id` = `ac_transaction_journal`.`transaction_id`))) join `payments` on((`ac_transaction_journal`.`reference_id` = `payments`.`id`))) where ((`ac_transaction_journal`.`reference_table` = 'payments') and (`ac_transaction_journal`.`stat` = 1)) group by `payments`.`id`;";
			$this->Mmm->query($sql_view3, "Create View for Accounts Collection - Posted");

			$sql_view4 = "CREATE VIEW `ac_accounts_receivable_for_clearing` AS select `statement_of_accounts`.`id` AS `id`,`statement_of_accounts`.`control_number` AS `control_number`,`statement_of_accounts`.`contract_id` AS `contract_id`,`statement_of_accounts`.`client_id` AS `client_id`,`statement_of_accounts`.`company_id` AS `company_id`,`statement_of_accounts`.`reference_number` AS `reference_number`,`statement_of_accounts`.`terms` AS `terms`,`statement_of_accounts`.`services` AS `services`,`statement_of_accounts`.`type` AS `type`,`statement_of_accounts`.`add_tax` AS `add_tax`,`statement_of_accounts`.`vat_12_percent` AS `vat_12_percent`,`statement_of_accounts`.`vat_5_percent` AS `vat_5_percent`,`statement_of_accounts`.`wtax_2_percent` AS `wtax_2_percent`,`statement_of_accounts`.`wtax_1_percent` AS `wtax_1_percent`,`statement_of_accounts`.`description` AS `description`,`statement_of_accounts`.`sent_to_client_on` AS `sent_to_client_on`,`statement_of_accounts`.`created_on` AS `created_on`,`statement_of_accounts`.`created_by` AS `created_by`,`statement_of_accounts`.`status` AS `status`,`statement_of_accounts`.`comments` AS `comments` from `statement_of_accounts` where ((`statement_of_accounts`.`status` = 'Waiting for Payment') and (not(exists(select `ac_transactions`.`id` from `ac_transactions` where ((`ac_transactions`.`reference_table` = 'statement_of_accounts') and (`statement_of_accounts`.`id` = `ac_transactions`.`reference_id`))))));";
			$this->Mmm->query($sql_view4, "Create View for Accounts Receivables - For Clearing");

			$sql_view5="CREATE VIEW `ac_accounts_receivable_for_posting` AS select `statement_of_accounts`.`id` AS `id`,`statement_of_accounts`.`control_number` AS `control_number`,`statement_of_accounts`.`contract_id` AS `contract_id`,`statement_of_accounts`.`client_id` AS `client_id`,`statement_of_accounts`.`company_id` AS `company_id`,`statement_of_accounts`.`reference_number` AS `reference_number`,`statement_of_accounts`.`terms` AS `terms`,`statement_of_accounts`.`services` AS `services`,`statement_of_accounts`.`type` AS `type`,`statement_of_accounts`.`add_tax` AS `add_tax`,`statement_of_accounts`.`vat_12_percent` AS `vat_12_percent`,`statement_of_accounts`.`vat_5_percent` AS `vat_5_percent`,`statement_of_accounts`.`wtax_2_percent` AS `wtax_2_percent`,`statement_of_accounts`.`wtax_1_percent` AS `wtax_1_percent`,`statement_of_accounts`.`description` AS `description`,`statement_of_accounts`.`sent_to_client_on` AS `sent_to_client_on`,`statement_of_accounts`.`created_on` AS `created_on`,`statement_of_accounts`.`created_by` AS `created_by`,`statement_of_accounts`.`status` AS `status`,`statement_of_accounts`.`comments` AS `comments`,`ac_transactions`.`reference_table` AS `reference_table`,`ac_transactions`.`reference_id` AS `reference_id`,`ac_transactions`.`id` AS `transaction_id`,`ac_transactions`.`created_on` AS `trans_created_on`,`ac_transactions`.`created_by` AS `trans_created_by` from (`ac_transactions` join `statement_of_accounts` on((`ac_transactions`.`reference_id` = `statement_of_accounts`.`id`))) where ((`ac_transactions`.`reference_table` = 'statement_of_accounts') and (`ac_transactions`.`status` = 'Active') and (`ac_transactions`.`stat` = 0));";
			$this->Mmm->query($sql_view5, "Create View for Accounts Receivables - For Posting");

			$sql_view6="CREATE VIEW `ac_accounts_receivable_posted` AS select `statement_of_accounts`.`id` AS `id`,`statement_of_accounts`.`control_number` AS `control_number`,`statement_of_accounts`.`contract_id` AS `contract_id`,`statement_of_accounts`.`client_id` AS `client_id`,`statement_of_accounts`.`company_id` AS `company_id`,`statement_of_accounts`.`reference_number` AS `reference_number`,`statement_of_accounts`.`terms` AS `terms`,`statement_of_accounts`.`services` AS `services`,`statement_of_accounts`.`type` AS `type`,`statement_of_accounts`.`add_tax` AS `add_tax`,`statement_of_accounts`.`vat_12_percent` AS `vat_12_percent`,`statement_of_accounts`.`vat_5_percent` AS `vat_5_percent`,`statement_of_accounts`.`wtax_2_percent` AS `wtax_2_percent`,`statement_of_accounts`.`wtax_1_percent` AS `wtax_1_percent`,`statement_of_accounts`.`description` AS `description`,`statement_of_accounts`.`sent_to_client_on` AS `sent_to_client_on`,`statement_of_accounts`.`created_on` AS `created_on`,`statement_of_accounts`.`created_by` AS `created_by`,`statement_of_accounts`.`status` AS `status`,`statement_of_accounts`.`comments` AS `comments`,`ac_transactions`.`reference_table` AS `reference_table`,`ac_transactions`.`reference_id` AS `reference_id`,`ac_transactions`.`id` AS `transaction_id`,`ac_transactions`.`created_on` AS `trans_created_on`,`ac_transactions`.`created_by` AS `trans_created_by` from (`ac_transactions` join `statement_of_accounts` on((`ac_transactions`.`reference_id` = `statement_of_accounts`.`id`))) where ((`ac_transactions`.`reference_table` = 'statement_of_accounts') and (`ac_transactions`.`status` = 'Active') and (`ac_transactions`.`stat` = 1));";
			$this->Mmm->query($sql_view6, "Create View for Accounts Receivables - Posted");

		}
		public function update20180213_transaction_journal() {
			$this->Mmm->query("INSERT INTO ac_transaction_journal (company_id, coa_id, debit_amount, credit_amount, posted_by, posted_on) VALUES (1,174,0,0.41,6,'".date("Y-m-d H:i:s")."'),(2,72,0,0.08,6,'".date("Y-m-d H:i:s")."'),(3,279,0.10,0,6,'".date("Y-m-d H:i:s")."'),(8,279,1.98,0,6,'".date("Y-m-d H:i:s")."'),(9,72,0,1770.75,6,'".date("Y-m-d H:i:s")."')", "Adjusting entries as per Sir Cris");
		}
		public function update20180213() { // from Sir MJ query to prod database
			$this->Mmm->query("ALTER TABLE `ac_ap_vouchers` ADD COLUMN `created_on` DATETIME AFTER `ac_vouchers_id`, ADD COLUMN `created_by` INTEGER UNSIGNED AFTER `created_on`;","for user activity tracking");
			$this->Mmm->query("ALTER TABLE `ac_vouchers` ADD COLUMN `created_on` DATETIME AFTER `check_date`, ADD COLUMN `created_by` INTEGER UNSIGNED AFTER `created_on`;","for user activity tracking");
			$this->Mmm->query("ALTER TABLE `ac_vouchers` ADD COLUMN `created_on` DATETIME AFTER `check_date`, ADD COLUMN `created_by` INTEGER UNSIGNED AFTER `created_on`;","for user activity tracking");
			$this->Mmm->query("ALTER TABLE `ac_request_payment` ADD COLUMN `created_on` DATETIME AFTER `accounting_entry_id`, ADD COLUMN `created_by` INTEGER UNSIGNED AFTER `created_on`;","for user activity tracking");
			$this->Mmm->query("ALTER TABLE `inventory_deliveries` ADD COLUMN `created_on` DATETIME AFTER `is_issued`, ADD COLUMN `created_by` INTEGER UNSIGNED AFTER `created_on`","for user activity tracking");
			$this->Mmm->query("ALTER TABLE `inventory_issuance` ADD COLUMN `created_on` DATETIME AFTER `company_id`, ADD COLUMN `created_by` INTEGER UNSIGNED AFTER `created_on`","for user activity tracking");

		}
		public function update20180210_password() { // Password expiration update
			$this->Mmm->query("create table password_history (id int(11) not null auto_increment, user_id int(11), password varchar(256), archived_on datetime, primary key(id))","Create table to store previously used passwords");
		}
		public function update20180208() { // Jan-June Journal Voucher for Integrated
			$this->Mmm->query("
INSERT INTO `ac_transaction_journal` (`coa_id`, `company_id`, `vessel_id`, `contract_id`, `department_id`, `debit_amount`, `credit_amount`, `reconciling_id`, `posted_by`, `posted_on`, `reference_table`, `reference_id`, `checked_by`, `date_checked`, `remark`, `stat`, `created_on`) VALUES
(204, 1, 0, 0, 14, 6617703.22, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:58'),
(271, 1, 0, 0, 7, 2513485.84, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:57'),
(282, 1, 0, 0, 7, 93399.19, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:57'),
(279, 1, 0, 0, 7, 0.02, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:56'),
(357, 1, 0, 0, 7, 4175252.09, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:56'),
(231, 1, 0, 0, 7, 2550.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:56'),
(279, 1, 0, 0, 7, 18412.85, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:55'),
(124, 1, 0, 0, 0, 0.00, 178490.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:55'),
(127, 1, 0, 0, 0, 0.00, 2152676.43, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:54'),
(122, 1, 0, 0, 0, 0.00, 11883.71, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:54'),
(127, 1, 0, 0, 0, 0.00, 11680.63, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:53'),
(125, 1, 0, 0, 0, 16800.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:53'),
(127, 1, 0, 0, 0, 0.00, 18206.73, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:52'),
(74, 1, 0, 0, 7, 100504.40, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:52'),
(279, 1, 0, 0, 7, 521502.80, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:51'),
(336, 1, 0, 0, 7, 3012229.54, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:51'),
(347, 1, 0, 0, 7, 2685621.94, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:50'),
(350, 1, 0, 0, 7, 2448709.47, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:50'),
(234, 1, 0, 0, 7, 1157310.18, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:49'),
(254, 1, 0, 0, 7, 14585971.12, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:49'),
(261, 1, 0, 0, 7, 1334873.78, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:48'),
(168, 1, 0, 0, 7, 4071636.85, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:48'),
(168, 1, 0, 0, 7, 1823678.75, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:47'),
(168, 1, 0, 0, 7, 1829062.57, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:47'),
(207, 1, 0, 0, 7, 14867675.12, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:46'),
(168, 1, 0, 0, 7, 7062838.01, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:46'),
(168, 1, 0, 0, 7, 78575.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:45'),
(240, 1, 0, 0, 7, 4940870.25, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:45'),
(242, 1, 0, 0, 7, 2921867.22, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:45'),
(226, 1, 0, 0, 7, 283000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:44'),
(253, 1, 0, 0, 7, 45899.46, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:44'),
(257, 1, 0, 0, 7, 793524.52, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:43'),
(279, 1, 0, 0, 7, 6390.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:43'),
(267, 1, 0, 0, 7, 1928.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:42'),
(279, 1, 0, 0, 7, 10602.25, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:42'),
(235, 1, 0, 0, 7, 13296.16, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:41'),
(279, 1, 0, 0, 7, 57633.39, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:41'),
(231, 1, 0, 0, 7, 2315401.32, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:41'),
(279, 1, 0, 0, 7, 331221.60, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:40'),
(217, 1, 0, 0, 7, 31071.43, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:40'),
(221, 1, 0, 0, 7, 1786554.45, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:39'),
(255, 1, 0, 0, 7, 24304.73, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:39'),
(223, 1, 0, 0, 7, 838073.51, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:38'),
(279, 1, 0, 0, 7, 70440.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:38'),
(152, 1, 0, 0, 14, 870329.60, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:38'),
(181, 1, 0, 0, 14, 23262.67, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:37'),
(151, 1, 0, 0, 14, 1454610.76, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:37'),
(229, 1, 0, 0, 7, 133734.09, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:37'),
(228, 1, 0, 0, 7, 215679.33, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:36'),
(237, 1, 0, 0, 7, 327935.85, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:36'),
(267, 1, 0, 0, 7, 175304.10, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:36'),
(279, 1, 0, 0, 7, 8130.63, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:35'),
(275, 1, 0, 0, 7, 172296.17, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:35'),
(263, 1, 0, 0, 7, 161000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:35'),
(255, 1, 0, 0, 7, 642589.88, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:34'),
(266, 1, 0, 0, 7, 808399.23, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:34'),
(279, 1, 0, 0, 7, 357000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:33'),
(283, 1, 0, 0, 7, 122117.02, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:33'),
(259, 1, 0, 0, 7, 15840.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:33'),
(218, 1, 0, 0, 7, 123274.92, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:33'),
(219, 1, 0, 0, 7, 296035.85, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:32'),
(216, 1, 0, 0, 7, 38375.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:32'),
(210, 1, 0, 0, 7, 1717082.60, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:32'),
(212, 1, 0, 0, 7, 371912.50, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:31'),
(211, 1, 0, 0, 7, 223031.25, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:31'),
(280, 1, 0, 0, 7, 145613.28, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:31'),
(178, 1, 0, 0, 7, 30081.55, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:30'),
(174, 1, 0, 0, 7, 698086.32, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:30'),
(182, 1, 0, 0, 7, 10968.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:30'),
(182, 1, 0, 0, 7, 253192.22, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:29'),
(169, 1, 0, 0, 14, 1664882.78, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:29'),
(191, 1, 0, 0, 14, 26641.38, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:29'),
(132, 1, 0, 0, 14, 73158.87, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:28'),
(171, 1, 0, 0, 14, 122770.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:28'),
(133, 1, 0, 0, 14, 613587.73, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:28'),
(139, 1, 0, 0, 14, 25675.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:27'),
(185, 1, 0, 0, 14, 347610.06, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:27'),
(185, 1, 0, 0, 14, 11617759.41, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:27'),
(202, 1, 0, 0, 14, 314351.96, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:26'),
(185, 1, 0, 0, 14, 4157748.79, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:26'),
(343, 1, 0, 0, 14, 100.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:26'),
(145, 1, 0, 0, 14, 121540.29, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:25'),
(183, 1, 0, 0, 14, 3378140.36, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:25'),
(137, 1, 0, 0, 14, 572007.62, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:25'),
(203, 1, 0, 0, 14, 0.00, 174088.05, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:24'),
(261, 1, 0, 0, 14, 141090.06, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:24'),
(186, 1, 0, 0, 14, 11100.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:24'),
(162, 1, 0, 0, 14, 172627.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:24'),
(153, 1, 0, 0, 14, 9837759.59, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:23'),
(253, 1, 0, 0, 14, 5475.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:23'),
(143, 1, 0, 0, 14, 17800.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:23'),
(343, 1, 0, 0, 14, 23127.45, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:22'),
(343, 1, 0, 0, 14, 281142.97, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:22'),
(141, 1, 0, 0, 14, 67843.23, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:22'),
(235, 1, 0, 0, 14, 15.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:21'),
(140, 1, 0, 0, 14, 52304.38, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:21'),
(279, 1, 0, 0, 14, 312916.64, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:21'),
(178, 1, 0, 0, 14, 5555.98, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:20'),
(201, 1, 0, 0, 14, 99866.87, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:20'),
(147, 1, 0, 0, 14, 39674.74, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:20'),
(186, 1, 0, 0, 14, 101214.39, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:20'),
(190, 1, 0, 0, 14, 56198.64, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:19'),
(279, 1, 0, 0, 14, 1850.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:19'),
(186, 1, 0, 0, 14, 18392633.97, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:19'),
(186, 1, 0, 0, 14, 463156.40, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:18'),
(186, 1, 0, 0, 14, 1859891.26, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:18'),
(164, 1, 0, 0, 14, 2684098.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:18'),
(164, 1, 0, 0, 14, 1976634.61, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:17'),
(167, 1, 0, 0, 14, 139399.83, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:17'),
(185, 1, 0, 0, 14, 10395880.68, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:17'),
(161, 1, 0, 0, 14, 37026.67, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:17'),
(138, 1, 0, 0, 14, 77617.14, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:16'),
(204, 1, 0, 0, 14, 475165.81, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:16'),
(182, 1, 0, 0, 14, 33000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:16'),
(182, 1, 0, 0, 14, 22450.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:15'),
(343, 1, 0, 0, 14, 246172.14, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:15'),
(165, 1, 0, 0, 14, 79323.26, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:15'),
(162, 1, 0, 0, 14, 218172.50, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:14'),
(343, 1, 0, 0, 14, 4866.07, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:14'),
(282, 1, 0, 0, 14, 556.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:14'),
(137, 1, 0, 0, 14, 1254922.89, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:14'),
(137, 1, 0, 0, 14, 473637.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:13'),
(123, 1, 0, 0, 0, 0.00, 656796.20, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:13'),
(123, 1, 0, 0, 0, 0.00, 589447.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:13'),
(110, 1, 0, 0, 0, 0.00, 4995663.07, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:13'),
(114, 1, 0, 0, 0, 0.00, 32245459.10, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:12'),
(108, 1, 0, 0, 0, 0.00, 77781221.87, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:12'),
(108, 1, 0, 0, 0, 0.00, 254356.38, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:12'),
(108, 1, 0, 0, 0, 0.00, 6622037.29, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:11'),
(108, 1, 0, 0, 0, 0.00, 300005.96, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:11'),
(108, 1, 0, 0, 0, 0.00, 19385261.42, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:11'),
(108, 1, 0, 0, 0, 0.00, 16990262.88, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:11'),
(108, 1, 0, 0, 0, 0.00, 1702272.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:10'),
(108, 1, 0, 0, 0, 0.00, 1124910.23, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:10'),
(108, 1, 0, 0, 0, 0.00, 655296.41, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:10'),
(108, 1, 0, 0, 0, 0.00, 53449.31, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:10'),
(108, 1, 0, 0, 0, 0.00, 812740.89, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:09'),
(108, 1, 0, 0, 0, 0.00, 64000.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:09'),
(108, 1, 0, 0, 0, 0.00, 7513600.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:09'),
(116, 1, 0, 0, 0, 0.00, 22796219.87, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:08'),
(114, 1, 0, 0, 0, 0.00, 9579527.30, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:08'),
(340, 1, 0, 0, 0, 0.00, 31000.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:08'),
(357, 1, 0, 0, 7, 1000000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:08'),
(85, 1, 0, 0, 0, 27067777.61, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:07'),
(340, 1, 0, 0, 0, 0.00, 10594.50, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:07'),
(79, 1, 0, 0, 0, 831149.20, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:07'),
(80, 1, 0, 0, 0, 0.00, 20509.02, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:07'),
(83, 1, 0, 0, 0, 122506.25, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:06'),
(81, 1, 0, 0, 0, 124350.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:06'),
(82, 1, 0, 0, 0, 0.00, 6066.04, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:06'),
(76, 1, 0, 0, 0, 0.00, 1150.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:06'),
(76, 1, 0, 0, 0, 1365.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:05'),
(76, 1, 0, 0, 0, 0.00, 179006.46, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:05'),
(76, 1, 0, 0, 0, 0.00, 46639.89, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:05'),
(75, 1, 0, 0, 0, 1292565.64, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:05'),
(77, 1, 0, 0, 0, 0.00, 5206269.01, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:04'),
(341, 1, 0, 0, 0, 3496.26, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:04'),
(339, 1, 0, 0, 0, 1018659.76, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:04'),
(339, 1, 0, 0, 0, 360190.39, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:03'),
(71, 1, 0, 0, 0, 0.00, 5905047.52, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:03'),
(12, 1, 0, 0, 0, 189795.46, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:03'),
(27, 1, 0, 0, 0, 3300613.59, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:03'),
(28, 1, 0, 0, 0, 0.00, 210036.83, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:02'),
(28, 1, 0, 0, 0, 263812.77, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:02'),
(28, 1, 0, 0, 0, 3211296.84, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:02'),
(66, 1, 0, 0, 0, 0.00, 419731.34, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:02'),
(66, 1, 0, 0, 0, 1681854.34, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:01');
			","Create entries for journal voucher of jan-june integrated");
			$this->Mmm->query("
INSERT INTO `ac_transaction_journal` (`coa_id`, `company_id`, `vessel_id`, `contract_id`, `department_id`, `debit_amount`, `credit_amount`, `reconciling_id`, `posted_by`, `posted_on`, `reference_table`, `reference_id`, `checked_by`, `date_checked`, `remark`, `stat`, `created_on`) VALUES
(32, 1, 0, 0, 0, 380000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:01'),
(14, 1, 0, 0, 0, 12756.80, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:01'),
(12, 1, 0, 0, 0, 55605.26, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:01'),
(32, 1, 0, 0, 0, 2114925.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:00'),
(14, 1, 0, 0, 0, 37779.61, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:00'),
(14, 1, 0, 0, 0, 3734.29, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:00'),
(14, 1, 0, 0, 0, 17322.88, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:59'),
(14, 1, 0, 0, 0, 10000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:59'),
(15, 1, 0, 0, 0, 397.70, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:59'),
(15, 1, 0, 0, 0, 0.00, 227891.74, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:59'),
(15, 1, 0, 0, 0, 45867.79, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:59'),
(15, 1, 0, 0, 0, 2115969.47, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:58'),
(15, 1, 0, 0, 0, 318111.21, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:58'),
(15, 1, 0, 0, 0, 83463.85, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:58'),
(15, 1, 0, 0, 0, 141187.56, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:58'),
(15, 1, 0, 0, 0, 1554833.69, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:57'),
(15, 1, 0, 0, 0, 5561291.76, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:57'),
(15, 1, 0, 0, 0, 5376.03, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:57'),
(15, 1, 0, 0, 0, 0.00, 133334.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:57'),
(15, 1, 0, 0, 0, 0.00, 30180.60, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:57'),
(15, 1, 0, 0, 0, 55000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:56'),
(12, 1, 0, 0, 0, 0.00, 93038.06, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:56'),
(12, 1, 0, 0, 0, 0.00, 188051.92, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:56'),
(12, 1, 0, 0, 0, 1126282.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:56'),
(296, 1, 0, 0, 0, 411984.86, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:55'),
(13, 1, 0, 0, 0, 0.00, 7011766.50, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:55'),
(296, 1, 0, 0, 0, 0.00, 20000.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:55'),
(296, 1, 0, 0, 0, 0.00, 58998.57, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:55'),
(12, 1, 0, 0, 0, 17000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:55'),
(336, 1, 0, 0, 0, 1418063.43, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:54'),
(42, 1, 0, 0, 0, 0.00, 5134331.41, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:54'),
(37, 1, 0, 0, 0, 5292267.86, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:54'),
(32, 1, 0, 0, 0, 21413500.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:53'),
(47, 1, 0, 0, 0, 135000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:53'),
(29, 1, 0, 0, 0, 13999038.33, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:53'),
(7, 1, 0, 0, 0, 0.00, 1915866.39, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:53'),
(14, 1, 0, 0, 0, 1659.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:53'),
(14, 1, 0, 0, 0, 0.00, 384341.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:52'),
(14, 1, 0, 0, 0, 444856.48, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:52'),
(14, 1, 0, 0, 0, 2565851.35, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:52'),
(14, 1, 0, 0, 0, 0.00, 17972939.85, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:52'),
(14, 1, 0, 0, 0, 0.00, 13611863.42, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:51'),
(14, 1, 0, 0, 0, 0.00, 873362.62, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:51'),
(14, 1, 0, 0, 0, 10000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:51'),
(14, 1, 0, 0, 0, 0.00, 19593649.81, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:51'),
(14, 1, 0, 0, 0, 66386.56, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:51'),
(14, 1, 0, 0, 0, 814882.39, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:50'),
(14, 1, 0, 0, 0, 576861.90, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:50'),
(14, 1, 0, 0, 0, 0.00, 12725958.92, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:50'),
(14, 1, 0, 0, 0, 400.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:50'),
(186, 1, 0, 0, 14, 1305554.74, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:49'),
(186, 1, 0, 0, 14, 1167040.03, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:49'),
(12, 1, 0, 0, 0, 209476.06, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:49'),
(12, 1, 0, 0, 0, 15000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:49'),
(12, 1, 0, 0, 0, 30000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:49'),
(12, 1, 0, 0, 0, 44382.40, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:49'),
(12, 1, 0, 0, 0, 51500.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:48'),
(12, 1, 0, 0, 0, 0.00, 10000.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:48'),
(12, 1, 0, 0, 0, 130000.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:48'),
(12, 1, 0, 0, 0, 16284.95, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:48'),
(12, 1, 0, 0, 0, 53500.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:48'),
(12, 1, 0, 0, 0, 7690.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:47'),
(12, 1, 0, 0, 0, 1500.00, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:47'),
(16, 1, 0, 0, 0, 99599.34, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:47'),
(25, 1, 0, 0, 0, 0.00, 49280.04, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:46'),
(23, 1, 0, 0, 0, 1108877.05, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:46'),
(10, 1, 0, 0, 0, 35014678.76, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:46'),
(13, 1, 0, 0, 0, 5840126.25, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:46'),
(326, 1, 0, 0, 0, 0.00, 1556891.39, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:46'),
(327, 1, 0, 0, 0, 0.00, 1474943.96, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:45'),
(299, 1, 0, 0, 0, 0.00, 901445.20, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:45'),
(300, 1, 0, 0, 0, 7407498.24, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:45'),
(304, 1, 0, 0, 0, 0.00, 13886641.93, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:45'),
(304, 1, 0, 0, 0, 0.00, 7391247.36, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:44'),
(302, 1, 0, 0, 0, 3559358.62, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:44'),
(306, 1, 0, 0, 0, 330553.23, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:44'),
(308, 1, 0, 0, 0, 0.00, 251250.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:42:44'),
(203, 1, 0, 0, 14, 549273.92, 0.00, NULL, 6, '2017-06-30 00:00:00', 'ac_journal_vouchers', '696969', NULL, NULL, 'To record the Trial Balance of Avega Bros. Integrated Shipping Corp. for the Period Jan.-June 2017 (From QB extracted TB) for the Development of New System.', 1, '2018-01-31 10:43:58');
			","Create entries for journal voucher of jan-june integrated");
			$dbentries	=	$this->db->query("SELECT id FROM ac_transaction_journal WHERE reference_table='ac_journal_vouchers' AND reference_id=696969");
			$dbentries	=	$dbentries->result_array();
			$entries	=	array();
			foreach($dbentries as $entry) {
				$entries[]	=	$entry['id'];
			}
			$control_number	=	$this->Abas->getNextSerialNumber("ac_journal_vouchers","1");
			$this->Mmm->query("INSERT INTO `ac_journal_vouchers` (`company_id`, `control_number`, `journal_ids`, `posted_on`, `created_on`, `created_by`, `viewed_by`, `viewed_on`, `approved_on`, `approved_by`, `disapproved_by`, `disapproved_on`) VALUES (1, ".$control_number.", '".$this->Mmm->sanitize(json_encode($entries))."', '2017-06-30 00:00:00', '2018-01-31 10:43:59', 6, 6, NULL, '2018-01-31 14:41:25', 6, NULL, NULL);", "Create journal voucher for jan-june integrated 2017");
			$jv_id	=	$this->db->query("SELECT MAX(id) FROM ac_journal_vouchers");
			$jv_id	=	(array)$jv_id->row();
			$jv_id	=	$jv_id['MAX(id)'];
			$this->db->query("UPDATE ac_transaction_journal SET reference_id=".$jv_id." WHERE reference_table='ac_journal_vouchers' AND reference_id=696969");
		}
		public function update20180129_payroll() { // Payroll adjustment table creation
			$this->Mmm->query("CREATE TABLE IF NOT EXISTS `hr_payroll_detail_adjustments` ( `id` int(11) NOT NULL AUTO_INCREMENT, `payroll_detail_id` int(11) DEFAULT NULL, `type` varchar(256) DEFAULT NULL, `amount` double(11,2) DEFAULT NULL, `is_taxable` tinyint(1) DEFAULT 0, `created_by` int(11) DEFAULT NULL, `created_on` datetime DEFAULT NULL, `remarks` text, `stat` tinyint(1) DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1;","Table will store miscellaneous adjustments during payroll creation");
		}
		public function update20180129(){
			if(ENVIRONMENT=="production") $this->Abas->redirect(HTTP_PATH);
			$sql1 = "ALTER TABLE `payments` ADD `other_deductions` DOUBLE NULL AFTER `discount`;";
			$this->Mmm->query($sql1, "Update table for Payments");

			$sql2 = "ALTER TABLE ops_out_turn_summary_output
			        MODIFY COLUMN `shipper_number_of_bags` double(11,2) DEFAULT NULL;";
			$this->Mmm->query($sql2, "Update table for Out-Turn Summary Output");

			$sql3 = "ALTER TABLE ops_out_turn_summary_output
			        MODIFY COLUMN `consignee_number_of_bags` double(11,2) DEFAULT NULL;";
			$this->Mmm->query($sql3, "Update table for Out-Turn Summary Output");

			$sql4 = "ALTER TABLE ops_out_turn_summary_output
			        MODIFY COLUMN `variance_number_of_bags` double(11,2) DEFAULT NULL;";
			$this->Mmm->query($sql4, "Update table for Out-Turn Summary Output");

			$sql5 = "ALTER TABLE ops_out_turn_summary_output
			        MODIFY COLUMN `good_number_of_bags` double(11,2) DEFAULT NULL;";
			$this->Mmm->query($sql5, "Update table for Out-Turn Summary Output");

			$sql6 = "ALTER TABLE ops_out_turn_summary_output
			        MODIFY COLUMN `damaged_number_of_bags` double(11,2) DEFAULT NULL;";
			$this->Mmm->query($sql6, "Update table for Out-Turn Summary Output");

			$sql7 = "ALTER TABLE ops_out_turn_summary_output
			        MODIFY COLUMN `total_number_of_bags` double(11,2) DEFAULT NULL;";
			$this->Mmm->query($sql7, "Update table for Out-Turn Summary Output");

			$sql8 = "ALTER TABLE service_order_detail_trucking
			        MODIFY COLUMN `from_location` varchar(255) DEFAULT NULL;";
			$this->Mmm->query($sql8, "Update table for Service Order Trucking");

			$sql9 = "ALTER TABLE service_order_detail_trucking
			        MODIFY COLUMN `to_location` varchar(255) DEFAULT NULL;";
			$this->Mmm->query($sql9, "Update table for Service Order Trucking");

		}
		public function update20180118_tax(){ // Withholding tax update
			$this->Mmm->query("TRUNCATE annual_tax_codes","Preparation for 2018 tax code reform");
			$this->Mmm->query("insert into annual_tax_codes (from_sal, to_sal, over, amount, stat)VALUES(0.00,250000.00,0,0.00,1), (250001,400000,20,0.00,1), (400001,800000,25,30000,1), (800001,2000000,30,130000,1), (2000001,5000000,32,490000,1), (5000001,999999999.99,35,1450000,1)","Insert new tax table for 2018 onwards");
		}
		public function update20180210(){
		  if(ENVIRONMENT=="production") $this->Abas->redirect(HTTP_PATH);
	      $sql1 = "ALTER TABLE ops_out_turn_summary_deliveries
	              MODIFY COLUMN `quantity` double(11,4) DEFAULT NULL;";
	      $this->Mmm->query($sql1, "Update table for Out-Turn Summary Deliveries");

	      $sql2 = "ALTER TABLE ops_out_turn_summary_deliveries
	              MODIFY COLUMN `gross_weight` double(11,4) DEFAULT NULL;";
	      $this->Mmm->query($sql2, "Update table for Out-Turn Summary Deliveries");

	      $sql3 = "ALTER TABLE ops_out_turn_summary_deliveries
	              MODIFY COLUMN `tare_weight` double(11,4) DEFAULT NULL;";
	      $this->Mmm->query($sql3, "Update table for Out-Turn Summary Deliveries");

	      $sql4 = "ALTER TABLE ops_out_turn_summary_deliveries
	              MODIFY COLUMN `net_weight` double(11,4) DEFAULT NULL;";
	      $this->Mmm->query($sql4, "Update table for Out-Turn Summary Deliveries");

	    }
		public function update20180209($type){
			if(ENVIRONMENT=="production") $this->Abas->redirect(HTTP_PATH);
			if($type=='billing'){

				$sql = "update ac_transaction_journal as tj set tj.posted_on=(select soa.created_on from statement_of_accounts as soa where soa.id=tj.reference_id) where tj.reference_table='statement_of_accounts'";
				$this->Mmm->query($sql, "gets date from SOA and puts it into posted_on in transaction journal");

			}elseif($type=='payment'){

				$sql = "update ac_transaction_journal as tj set tj.posted_on=(select p.received_on from payments as p where p.id=tj.reference_id) where tj.reference_table='payments'";
				$this->Mmm->query($sql, "gets date from official receipt and puts it into posted_on in transaction journal");

			}
		}
		public function update20180118(){
			if(ENVIRONMENT=="production") $this->Abas->redirect(HTTP_PATH);
			$sql1 = "ALTER TABLE statement_of_account_cargo_out_turn
			        ADD COLUMN `empty_sacks` tinyint(1) DEFAULT NULL";
			$this->Mmm->query($sql1, "Update table for SOA with Out-turn Summary");

			$sql2 = "ALTER TABLE statement_of_account_cargo_out_turn
			        ADD COLUMN `consignee` varchar(45) DEFAULT NULL";
			$this->Mmm->query($sql2, "Update table for SOA with Out-turn Summary");

			$sql3 = "ALTER TABLE statement_of_account_cargo_out_turn
			        ADD COLUMN `commodity_cargo` varchar(45) DEFAULT NULL";
			$this->Mmm->query($sql3, "Update table for SOA with Out-turn Summary");

			$sql4 = "ALTER TABLE statement_of_account_cargo_out_turn
			        ADD COLUMN `destination` varchar(45) DEFAULT NULL";
			$this->Mmm->query($sql4, "Update table for SOA with Out-turn Summary");
		}
		public function update20180109(){
			$insert = "insert into ac_financial_statement_labels (code,name)values('1214','Advances to Related Parties'),('2123', 'Dividends Payable'),('2122','Income Tax Payable'),('2234','Other Noncurrent Liabilities');";
			$update[0] = "update ac_financial_statement_labels set code='2125' where id=17";
			$update[1] = "update ac_financial_statement_labels set code='2124' where id=16;";
			$this->Mmm->query($insert, "Add labels to financial statement, visible in statement of financial position");
			$this->Mmm->query($update[0], "Change financial statement code for specific record");
			$this->Mmm->query($update[1], "Change financial statement code for specific record");
		}
		public function update20180103(){
			if(ENVIRONMENT=="production") $this->Abas->redirect(HTTP_PATH);
			$sql = "ALTER TABLE ops_out_turn_summary
			        ADD COLUMN `service_contract_id` int(11) DEFAULT NULL";
			$this->Mmm->query($sql, "Update table for Out-turn Summary");

			$sql2 = "ALTER TABLE statement_of_account_details
			        MODIFY COLUMN `charges` double(11,3) DEFAULT NULL;";
			$this->Mmm->query($sql2, "Update table for SOA Details");

			$sql3 = "ALTER TABLE statement_of_account_details
			        MODIFY COLUMN `balance` double(11,3) DEFAULT NULL;";
			$this->Mmm->query($sql3, "Update table for SOA Details");

		}
		public function update20171219(){
			if(ENVIRONMENT=="production") $this->Abas->redirect(HTTP_PATH);
			$sql1	= "ALTER TABLE ops_out_turn_summary_details
						ADD COLUMN `lighterage_receipt_number` varchar(45) DEFAULT NULL,
						ADD COLUMN `trip_ticket_number` varchar(45) DEFAULT NULL,
						ADD COLUMN `statement_of_facts_number` varchar(45) DEFAULT NULL,
						ADD COLUMN `barge_patron` varchar(45) DEFAULT NULL,
						ADD COLUMN `loading_batch_weight` double DEFAULT NULL,
						ADD COLUMN `unloading_batch_weight` double DEFAULT NULL";
			$this->Mmm->query($sql1, "Update table for Out-turn Summary Details");

			$sql2	= "ALTER TABLE ops_out_turn_summary_deliveries
						MODIFY COLUMN `number_of_moves` double DEFAULT NULL;";
			$this->Mmm->query($sql2, "Update table for Out-turn Summary Deliveries");

			$sql3	= "ALTER TABLE statement_of_account_cargo_out_turn
						MODIFY COLUMN `number_of_moves` double DEFAULT NULL;";
			$this->Mmm->query($sql3, "Update table for Statement of Account - Out-turn Summary");

			$sql4	= "ALTER TABLE service_order_detail_handling
						MODIFY COLUMN `quantity` double DEFAULT NULL;";
			$this->Mmm->query($sql4, "Update table for Service Order Detail Handling");

		}
		public function update20171201 () {
			if(ENVIRONMENT=="production") $this->Abas->redirect(HTTP_PATH);

			$this->Mmm->query("DROP TABLE IF EXISTS `ops_out_turn_summary`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `ops_out_turn_summary_attachments`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `ops_out_turn_summary_deliveries`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `ops_out_turn_summary_details`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `ops_out_turn_summary_output`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `service_orders`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `service_order_detail_handling`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `service_order_detail_lighterage`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `service_order_detail_timecharter`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `service_order_detail_towing`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `service_order_detail_trucking`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `service_order_detail_voyage`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `service_contracts`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `statement_of_account_cargo_out_turn`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `payments_daily_report`;", "insert action here");
			$this->Mmm->query("DROP TABLE IF EXISTS `payments_daily_report_details`;", "insert action here");

			$this->Mmm->query("UPDATE trucks SET company=5", "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `ops_out_turn_summary` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `control_number` int(11) DEFAULT NULL,
			  `service_order_id` int(11) DEFAULT NULL,
			  `company_id` int(11) DEFAULT NULL,
			  `type_of_service` varchar(255) DEFAULT NULL,
			  `created_by` int(11) DEFAULT NULL,
			  `created_on` datetime DEFAULT NULL,
			  `remarks` text,
			  `stat` tinyint(1) DEFAULT NULL,
			  `status` varchar(255) DEFAULT NULL,
			  `comments` text,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `ops_out_turn_summary_attachments` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `out_turn_summary_id` int(11) DEFAULT NULL,
			  `document_name` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `ops_out_turn_summary_deliveries` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `sorting` int(11) DEFAULT NULL,
			  `out_turn_summary_id` int(11) DEFAULT NULL,
			  `delivery_date` date DEFAULT NULL,
			  `trucking_company` varchar(45) DEFAULT NULL,
			  `truck_plate_number` varchar(45) DEFAULT NULL,
			  `truck_driver` varchar(45) DEFAULT NULL,
			  `warehouse` varchar(45) DEFAULT NULL,
			  `quantity` int(11) DEFAULT NULL,
			  `gross_weight` double DEFAULT NULL,
			  `tare_weight` double DEFAULT NULL,
			  `net_weight` double DEFAULT NULL,
			  `number_of_moves` int(11) DEFAULT NULL,
			  `variety_item` varchar(255) DEFAULT NULL,
			  `transaction` varchar(255) DEFAULT NULL,
			  `delivery_receipt_number` varchar(45) DEFAULT NULL,
			  `weighing_ticket_number` varchar(45) DEFAULT NULL,
			  `warehouse_issuance_form_number` varchar(45) DEFAULT NULL,
			  `warehouse_receipt_form_number` varchar(45) DEFAULT NULL,
			  `way_bill_number` varchar(45) DEFAULT NULL,
			  `authority_to_load_number` varchar(45) DEFAULT NULL,
			  `cargo_receipt_number` varchar(45) DEFAULT NULL,
			  `others` varchar(45) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `ops_out_turn_summary_details` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `out_turn_summary_id` int(11) DEFAULT NULL,
			  `bill_of_lading_number` int(11) DEFAULT NULL,
			  `quantity_per_bill_of_lading` double DEFAULT NULL,
			  `weight_per_bill_of_lading` double DEFAULT NULL,
			  `shipper` varchar(255) DEFAULT NULL,
			  `consignee` varchar(255) DEFAULT NULL,
			  `surveyor` varchar(255) DEFAULT NULL,
			  `arrastre` varchar(255) DEFAULT NULL,
			  `vessel_id` int(11) DEFAULT NULL,
			  `mother_vessel` varchar(255) DEFAULT NULL,
			  `voyage_number` int(11) DEFAULT NULL,
			  `port_of_origin` varchar(255) DEFAULT NULL,
			  `port_of_destination` varchar(255) DEFAULT NULL,
			  `loading_arrival` date DEFAULT NULL,
			  `loading_start` datetime DEFAULT NULL,
			  `loading_ended` datetime DEFAULT NULL,
			  `loading_departure` date DEFAULT NULL,
			  `loading_quantity_volume` double DEFAULT NULL,
			  `unloading_arrival` date DEFAULT NULL,
			  `unloading_start` datetime DEFAULT NULL,
			  `unloading_ended` datetime DEFAULT NULL,
			  `unloading_departure` date DEFAULT NULL,
			  `unloading_quantity_volume` double DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `ops_out_turn_summary_output` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `out_turn_summary_id` int(11) DEFAULT NULL,
			  `shipper_number_of_bags` int(11) DEFAULT NULL,
			  `shipper_weight` double DEFAULT NULL,
			  `consignee_number_of_bags` int(11) DEFAULT NULL,
			  `consignee_weight` double DEFAULT NULL,
			  `variance_number_of_bags` int(11) DEFAULT NULL,
			  `variance_weight` double DEFAULT NULL,
			  `percentage_number_of_bags` double DEFAULT NULL,
			  `percentage_weight` double DEFAULT NULL,
			  `good_number_of_bags` int(11) DEFAULT NULL,
			  `damaged_number_of_bags` int(11) DEFAULT NULL,
			  `total_number_of_bags` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `service_orders` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `service_contract_id` int(11) NOT NULL,
			  `control_number` int(11) DEFAULT NULL,
			  `company_id` int(11) DEFAULT NULL,
			  `type` varchar(45) DEFAULT NULL,
			  `date_needed` date DEFAULT NULL,
			  `created_on` datetime DEFAULT NULL,
			  `created_by` int(11) DEFAULT NULL,
			  `remarks` varchar(255) DEFAULT NULL,
			  `comments` text,
			  `stat` tinyint(3) unsigned DEFAULT NULL,
			  `status` varchar(45) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `service_order_detail_handling` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `service_order_id` int(10) unsigned NOT NULL,
			  `warehouse` varchar(45) DEFAULT NULL,
			  `number_of_moves` int(10) unsigned DEFAULT NULL,
			  `cargo_description` varchar(255) DEFAULT NULL,
			  `quantity` int(10) unsigned DEFAULT NULL,
			  `unit` varchar(45) DEFAULT NULL,
			  `stat` tinyint(1) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `service_order_detail_lighterage` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `service_order_id` int(10) unsigned NOT NULL,
			  `vessel_id` int(11) DEFAULT NULL,
			  `source_vessel` text,
			  `vessel_location` text,
			  `discharge_location` text,
			  `cargo_description` varchar(45) DEFAULT NULL,
			  `unit` varchar(45) DEFAULT NULL,
			  `quantity` double DEFAULT NULL,
			  `stat` tinyint(1) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `service_order_detail_timecharter` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `service_order_id` int(10) unsigned NOT NULL,
			  `vessel_id` int(10) unsigned NOT NULL,
			  `start_datetime` datetime DEFAULT NULL,
			  `end_datetime` datetime DEFAULT NULL,
			  `start_location` varchar(255) DEFAULT NULL,
			  `end_location` varchar(255) DEFAULT NULL,
			  `cargo_description` varchar(45) DEFAULT NULL,
			  `unit` varchar(45) DEFAULT NULL,
			  `quantity` double DEFAULT NULL,
			  `stat` tinyint(1) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `service_order_detail_towing` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `service_order_id` int(10) unsigned NOT NULL,
			  `vessel_id` int(11) DEFAULT NULL,
			  `craft_towed` varchar(255) NOT NULL,
			  `from_location` varchar(255) NOT NULL,
			  `to_location` varchar(255) NOT NULL,
			  `cargo_description` varchar(45) DEFAULT NULL,
			  `unit` varchar(45) DEFAULT NULL,
			  `quantity` double unsigned DEFAULT NULL,
			  `stat` tinyint(3) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `service_order_detail_trucking` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `service_order_id` int(10) unsigned NOT NULL,
			  `truck_id` int(11) DEFAULT NULL,
			  `from_location` varchar(45) DEFAULT NULL,
			  `to_location` varchar(45) DEFAULT NULL,
			  `destination` varchar(45) DEFAULT NULL,
			  `warehouse` varchar(45) DEFAULT NULL,
			  `cargo_description` varchar(45) DEFAULT NULL,
			  `quantity` double DEFAULT NULL,
			  `unit` varchar(45) DEFAULT NULL,
			  `stat` tinyint(3) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `service_order_detail_voyage` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `service_order_id` int(10) unsigned NOT NULL,
			  `vessel_id` int(11) DEFAULT NULL,
			  `from_location` varchar(45) DEFAULT NULL,
			  `to_location` varchar(45) DEFAULT NULL,
			  `cargo_description` text,
			  `unit` varchar(45) DEFAULT NULL,
			  `quantity` double DEFAULT NULL,
			  `stat` tinyint(1) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `service_contracts` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `contract_date` datetime DEFAULT NULL,
			  `company_id` int(10) unsigned NOT NULL,
			  `client_id` int(11) DEFAULT NULL,
			  `type` varchar(256) DEFAULT NULL,
			  `rate` double DEFAULT NULL,
			  `quantity` double(12,2) DEFAULT NULL,
			  `unit` varchar(256) DEFAULT NULL,
			  `amount` double(12,2) DEFAULT NULL,
			  `details` text,
			  `date_effective` date DEFAULT NULL,
			  `reference_no` varchar(45) NOT NULL,
			  `sub_reference_no` varchar(45) NOT NULL,
			  `stat` tinyint(1) unsigned NOT NULL,
			  `status` varchar(256) DEFAULT NULL,
			  `created_on` datetime DEFAULT NULL,
			  `created_by` int(10) DEFAULT NULL,
			  `parent_contract_id` int(11) DEFAULT NULL,
			  `control_number` int(11) unsigned DEFAULT NULL,
			  `terms_of_payment` int(11) DEFAULT NULL,
			  `updated_on` datetime DEFAULT NULL,
			  `updated_by` int(11) DEFAULT NULL,
			  `remark` text,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `statement_of_account_cargo_out_turn` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `soa_id` int(11) DEFAULT NULL,
			  `out_turn_summary_id` int(11) DEFAULT NULL,
			  `date_of_delivery` date DEFAULT NULL,
			  `warehouse` varchar(255) DEFAULT NULL,
			  `trucking_company` varchar(255) DEFAULT NULL,
			  `quantity` double DEFAULT NULL,
			  `total_weight` double DEFAULT NULL,
			  `transaction` varchar(255) DEFAULT NULL,
			  `number_of_moves` int(11) DEFAULT NULL,
			  `rate` double DEFAULT NULL,
			  `amount` double DEFAULT NULL,
			  `on_board_vessel` varchar(45) DEFAULT NULL,
			  `bill_of_lading_number` varchar(45) DEFAULT NULL,
			  `authority_to_issue_number` varchar(45) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `payments_daily_report` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `control_number` int(11) DEFAULT NULL,
			  `company_id` int(11) DEFAULT NULL,
			  `beginning_balance` double DEFAULT NULL,
			  `total_collection` double DEFAULT NULL,
			  `total_deposits` double DEFAULT NULL,
			  `ending_balance` double DEFAULT NULL,
			  `created_on` date DEFAULT NULL,
			  `created_by` int(11) DEFAULT NULL,
			  `status` varchar(45) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"CREATE TABLE IF NOT EXISTS `payments_daily_report_details` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `daily_report_id` int(11) DEFAULT NULL,
			  `payment_id` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$this->Mmm->query($sql, "insert action here");

			$sql	=	"INSERT INTO `am_evaluation_items` (`id`, `item_index`, `item_set`, `item_sub_set`, `item_name`, `type`, `ask_spec`, `enabled`) VALUES
			(1, 'A', 1, 1, 'External hull, topside plating (P/S)', 'vessel', 0, 1),
			(2, 'A', 1, 2, 'Superstructure plating and framings', 'vessel', 0, 1),
			(3, 'A', 1, 3, 'Water tight doors (external doors)', 'vessel', 0, 1),
			(4, 'A', 1, 4, 'Weather deck plating and drainage', 'vessel', 0, 1),
			(5, 'A', 1, 5, 'Hatchways and deck manholes', 'vessel', 0, 1),
			(6, 'A', 1, 6, 'Skylights and windows', 'vessel', 0, 1),
			(7, 'A', 1, 8, 'Hatch cover plating and framings', 'vessel', 0, 1),
			(8, 'A', 1, 9, 'Hatch cover rollers (P/S)', 'vessel', 0, 1),
			(9, 'A', 1, 10, 'Rain gutters/drainage', 'vessel', 0, 1),
			(10, 'A', 1, 11, 'Hatch cover arms and locks (P/S)', 'vessel', 0, 1),
			(11, 'A', 1, 12, 'Hatch coaming stay & top plate (P/S)', 'vessel', 0, 1),
			(12, 'A', 1, 13, 'Hatch cover hydraulic tosser & hose (P/S)', 'vessel', 0, 1),
			(13, 'A', 1, 14, 'Hatch cover tosser beam (P/S)', 'vessel', 0, 1),
			(14, 'A', 1, 15, 'Hatch cover chains (P/S)', 'vessel', 0, 1),
			(15, 'A', 1, 16, 'Wildcats and shaftings', 'vessel', 0, 1),
			(16, 'A', 2, 1, 'Cargo Hold Area: Inner coaming plate', 'vessel', 0, 1),
			(17, 'A', 2, 2, 'Cargo Hold Area: Stanchion Post & box frames', 'vessel', 0, 1),
			(18, 'A', 2, 3, 'Cargo Hold Area: Frames, stiffeners & pillars', 'vessel', 0, 1),
			(19, 'A', 2, 4, 'Cargo Hold Area: Tween deck plating & frames (P/S)', 'vessel', 0, 1),
			(20, 'A', 2, 5, 'Cargo Hold Area: Wing tank plating & frames (P/S)', 'vessel', 0, 1),
			(21, 'A', 2, 6, 'Cargo Hold Area: Watertight bulkhead and doors', 'vessel', 0, 1),
			(22, 'A', 2, 7, 'Cargo Hold Area: Tank top plating and manhole covers', 'vessel', 0, 1),
			(23, 'A', 2, 8, 'Cargo Hold Area: Bilge well', 'vessel', 0, 1),
			(24, 'A', 3, 1, 'Ballast tanks (P/S): Longitudinal frames', 'vessel', 0, 1),
			(25, 'A', 3, 2, 'Ballast tanks (P/S): Transverse frames', 'vessel', 0, 1),
			(26, 'A', 3, 3, 'Ballast tanks (P/S): Airvent pipes', 'vessel', 0, 1),
			(27, 'A', 3, 4, 'Ballast tanks (P/S): Sounding pipes', 'vessel', 0, 1),
			(28, 'A', 3, 5, 'Ballast tanks (P/S): Ballast pipes', 'vessel', 0, 1),
			(29, 'A', 3, 6, 'Ballast tanks (P/S): Sacrificial anodes', 'vessel', 0, 1),
			(30, 'A', 4, 1, 'Fresh water tanks (F/A): Water tank plating and frames', 'vessel', 0, 1),
			(31, 'A', 4, 2, 'Fresh water tanks (F/A): Fresh water pipings', 'vessel', 0, 1),
			(32, 'A', 4, 3, 'Fresh water tanks (F/A): Airvent and sounding pipe', 'vessel', 0, 1),
			(33, 'A', 4, 4, 'Fresh water tanks (F/A): Tank internal coat finish', 'vessel', 0, 1),
			(34, 'A', 5, 1, 'Fuel oil tanks : Fuel tank plating and frames', 'vessel', 0, 1),
			(35, 'A', 5, 2, 'Fuel oil tanks : Fuel oil pipings', 'vessel', 0, 1),
			(36, 'A', 5, 3, 'Fuel oil tanks : Airvent and sounding pipe', 'vessel', 0, 1),
			(37, 'A', 5, 4, 'Fuel oil tanks : Filling pipes and valves', 'vessel', 0, 1),
			(38, 'A', 6, 1, 'Bulwark and railings', 'vessel', 0, 1),
			(39, 'A', 6, 2, 'Fairlead rollers', 'vessel', 0, 1),
			(40, 'A', 6, 3, 'Bollards and mooring bits', 'vessel', 0, 1),
			(41, 'A', 6, 4, 'Sparling pipes', 'vessel', 0, 1),
			(42, 'A', 6, 5, 'Chain house pipes', 'vessel', 0, 1),
			(43, 'A', 6, 6, 'Chain locker platings', 'vessel', 0, 1),
			(44, 'A', 6, 7, 'Stairs and ladders', 'vessel', 0, 1),
			(45, 'A', 6, 8, 'Main and foreward mast', 'vessel', 0, 1),
			(46, 'B', 1, 1, 'Foreward windlass (P/S): Anchors, anchor chains & wildcats (P/S)', 'vessel', 0, 1),
			(47, 'B', 1, 2, 'Foreward windlass (P/S): Hydraulic pumps and motor', 'vessel', 0, 1),
			(48, 'B', 1, 3, 'Foreward windlass (P/S): Hydraulic control lever', 'vessel', 0, 1),
			(49, 'B', 1, 4, 'Foreward windlass (P/S): Hydraulic pipings and hydraulic hose', 'vessel', 0, 1),
			(50, 'B', 2, 1, 'Aft windlass (P/S): Hydraulic pumps and motor', 'vessel', 0, 1),
			(51, 'B', 2, 2, 'Aft windlass (P/S): Hydraulic control lever', 'vessel', 0, 1),
			(52, 'B', 2, 3, 'Aft windlass (P/S): Hydraulic pipings and hydraulic hose', 'vessel', 0, 1),
			(53, 'B', 2, 4, 'Aft windlass (P/S): Boat davit (Boat deck)', 'vessel', 0, 1),
			(54, 'B', 3, 1, 'Ship Crane: Engine condition and controls', 'vessel', 0, 1),
			(55, 'B', 3, 2, 'Ship Crane: Electrical wirings and safety apparatus', 'vessel', 0, 1),
			(56, 'B', 3, 3, 'Ship Crane: Gears, drums & drive mechanism', 'vessel', 0, 1),
			(57, 'B', 3, 4, 'Ship Crane: Topping lift pulley', 'vessel', 0, 1),
			(58, 'B', 3, 5, 'Ship Crane: Topping lift wire rope', 'vessel', 0, 1),
			(59, 'B', 3, 6, 'Ship Crane: Cargo runner pulley', 'vessel', 0, 1),
			(60, 'B', 3, 7, 'Ship Crane: Cargo runner wire rope', 'vessel', 0, 1),
			(61, 'B', 3, 8, 'Ship Crane: Cargo block pulley and hook', 'vessel', 0, 1),
			(62, 'B', 3, 9, 'Ship Crane: Boom stopper', 'vessel', 0, 1),
			(63, 'B', 4, 1, 'Boom braces and joining pins', 'vessel', 0, 1),
			(64, 'B', 4, 2, 'Boom line cable and joining pins', 'vessel', 0, 1),
			(65, 'B', 4, 3, 'Hydraulic pumps and coolers', 'vessel', 0, 1),
			(66, 'B', 4, 4, 'Crane body condition and glass windows', 'vessel', 0, 1),
			(67, 'B', 4, 5, 'Fuel, cooling & exhaust system', 'vessel', 0, 1),
			(68, 'B', 4, 6, 'Brake shoe rebonding', 'vessel', 0, 1),
			(69, 'C', 1, 1, 'Liners and piston', 'vessel', 0, 1),
			(70, 'C', 1, 2, 'Cylinder heads and jacket side', 'vessel', 0, 1),
			(71, 'C', 1, 3, 'Intake valves', 'vessel', 0, 1),
			(72, 'C', 1, 4, 'Exhaust valves', 'vessel', 0, 1),
			(73, 'C', 1, 5, 'Fuel oil valves', 'vessel', 0, 1),
			(74, 'C', 1, 6, 'Safety valves', 'vessel', 0, 1),
			(75, 'C', 1, 7, 'Main bearings', 'vessel', 0, 1),
			(76, 'C', 1, 8, 'Connecting rod bearings', 'vessel', 0, 1),
			(77, 'C', 1, 9, 'Outboard bearings', 'vessel', 0, 1),
			(78, 'C', 2, 1, 'Thrust bearings', 'vessel', 0, 1),
			(79, 'C', 2, 2, 'Air starting valves', 'vessel', 0, 1),
			(80, 'C', 2, 3, 'Air coolers', 'vessel', 0, 1),
			(81, 'C', 2, 4, 'Fuel oil injection pumps (P&B, delivery valve)', 'vessel', 0, 1),
			(82, 'C', 2, 5, 'Fuel injector/nozzle tip', 'vessel', 0, 1),
			(83, 'C', 2, 6, 'Fuel oil filter/strainer', 'vessel', 0, 1),
			(84, 'C', 2, 7, 'Lube oil filter/strainer (Sampling for analysis)', 'vessel', 0, 1),
			(85, 'C', 2, 8, 'Air suction filter', 'vessel', 0, 1),
			(86, 'C', 2, 9, 'Fresh water coolers', 'vessel', 0, 1),
			(87, 'C', 2, 10, 'Lube oil coolers', 'vessel', 0, 1),
			(88, 'C', 3, 1, 'Turbocharger (Bearings, bushing & blades)', 'vessel', 0, 1),
			(89, 'C', 3, 2, 'Air distribution valves', 'vessel', 0, 1),
			(90, 'C', 3, 3, 'Gauges and metering devices', 'vessel', 0, 1),
			(91, 'D', 1, 1, 'Auxillary Engine 1: Main bearings', 'vessel', 0, 1),
			(92, 'D', 1, 2, 'Auxillary Engine 1: Connecting rod bearings', 'vessel', 0, 1),
			(93, 'D', 1, 3, 'Auxillary Engine 1: Piston rings', 'vessel', 0, 1),
			(94, 'D', 1, 4, 'Auxillary Engine 1: Piston and liners', 'vessel', 0, 1),
			(95, 'D', 1, 5, 'Auxillary Engine 1: Exhaust & intake valves', 'vessel', 0, 1),
			(96, 'D', 1, 6, 'Auxillary Engine 1: Fuel oil nozzle tip', 'vessel', 0, 1),
			(97, 'D', 1, 7, 'Auxillary Engine 1: Fuel oil injection pump', 'vessel', 0, 1),
			(98, 'D', 1, 8, 'Auxillary Engine 1: Turbocharger (if any)', 'vessel', 0, 1),
			(99, 'D', 1, 9, 'Auxillary Engine 1: Filters (air, fuel and lube)', 'vessel', 0, 1),
			(100, 'D', 1, 10, 'Auxillary Engine 1: Fresh water pump', 'vessel', 0, 1),
			(101, 'D', 2, 1, 'Auxillary Engine 1: Sea water pump', 'vessel', 0, 1),
			(102, 'D', 2, 2, 'Auxillary Engine 1: Fresh water cooler', 'vessel', 0, 1),
			(103, 'D', 2, 3, 'Auxillary Engine 1: Lube oil cooler', 'vessel', 0, 1),
			(104, 'D', 2, 4, 'Auxillary Engine 1: Starter and alternator', 'vessel', 0, 1),
			(105, 'D', 2, 5, 'Auxillary Engine 1: Battery and gauges/meterings', 'vessel', 0, 1),
			(106, 'D', 3, 1, 'Auxillary Engine 2: Main bearings', 'vessel', 0, 1),
			(107, 'D', 3, 2, 'Auxillary Engine 2: Connecting rod bearings', 'vessel', 0, 1),
			(108, 'D', 3, 3, 'Auxillary Engine 2: Piston rings', 'vessel', 0, 1),
			(109, 'D', 3, 4, 'Auxillary Engine 2: Piston and liners', 'vessel', 0, 1),
			(110, 'D', 3, 5, 'Auxillary Engine 2: Exhaust & intake valves', 'vessel', 0, 1),
			(111, 'D', 3, 6, 'Auxillary Engine 2: Fuel oil nozzle tip', 'vessel', 0, 1),
			(112, 'D', 3, 7, 'Auxillary Engine 2: Fuel oil injection pump', 'vessel', 0, 1),
			(113, 'D', 3, 8, 'Auxillary Engine 2: Turbocharger (if any)', 'vessel', 0, 1),
			(114, 'D', 3, 9, 'Auxillary Engine 2: Filters (air, fuel and lube)', 'vessel', 0, 1),
			(115, 'D', 3, 10, 'Auxillary Engine 2: Fresh water pump', 'vessel', 0, 1),
			(116, 'D', 4, 1, 'Auxillary Engine 2: Sea water pump', 'vessel', 0, 1),
			(117, 'D', 4, 2, 'Auxillary Engine 2: Fresh water cooler', 'vessel', 0, 1),
			(118, 'D', 4, 3, 'Auxillary Engine 2: Lube oil cooler', 'vessel', 0, 1),
			(119, 'D', 4, 4, 'Auxillary Engine 2: Starter and alternator', 'vessel', 0, 1),
			(120, 'D', 4, 5, 'Auxillary Engine 2: Battery and gauges/meterings', 'vessel', 0, 1),
			(121, 'D', 5, 1, 'Auxillary Engine 3: Main bearings', 'vessel', 0, 1),
			(122, 'D', 5, 2, 'Auxillary Engine 3: Connecting rod bearings', 'vessel', 0, 1),
			(123, 'D', 5, 3, 'Auxillary Engine 3: Piston rings', 'vessel', 0, 1),
			(124, 'D', 5, 4, 'Auxillary Engine 3: Piston and liners', 'vessel', 0, 1),
			(125, 'D', 5, 5, 'Auxillary Engine 3: Exhaust & intake valves', 'vessel', 0, 1),
			(126, 'D', 5, 6, 'Auxillary Engine 3: Fuel oil nozzle tip', 'vessel', 0, 1),
			(127, 'D', 5, 7, 'Auxillary Engine 3: Fuel oil injection pump', 'vessel', 0, 1),
			(128, 'D', 5, 8, 'Auxillary Engine 3: Turbocharger (if any)', 'vessel', 0, 1),
			(129, 'D', 5, 9, 'Auxillary Engine 3: Filters (air, fuel and lube)', 'vessel', 0, 1),
			(130, 'D', 5, 10, 'Auxillary Engine 3: Fresh water pump', 'vessel', 0, 1),
			(131, 'D', 6, 1, 'Auxillary Engine 3: Sea water pump', 'vessel', 0, 1),
			(132, 'D', 6, 2, 'Auxillary Engine 3: Fresh water cooler', 'vessel', 0, 1),
			(133, 'D', 6, 3, 'Auxillary Engine 3: Lube oil cooler', 'vessel', 0, 1),
			(134, 'D', 6, 4, 'Auxillary Engine 3: Starter and alternator', 'vessel', 0, 1),
			(135, 'D', 6, 5, 'Auxillary Engine 3: Battery and gauges/meterings', 'vessel', 0, 1),
			(136, 'E', 1, 1, 'Electric Motors: Megger reading of all electric motors', 'vessel', 0, 1),
			(137, 'E', 1, 2, 'Electric Motors: Renewal of wear bearings in motors', 'vessel', 0, 1),
			(138, 'E', 1, 3, 'Electric Motors: Clean and varnish motor windings', 'vessel', 0, 1),
			(139, 'E', 2, 1, 'Generator 1: Megger reading of all electric motors', 'vessel', 0, 1),
			(140, 'E', 2, 2, 'Generator 1: Renewal of wear bearings in motors', 'vessel', 0, 1),
			(141, 'E', 2, 3, 'Generator 1: Clean and varnish motor windings', 'vessel', 0, 1),
			(142, 'E', 3, 1, 'Generator 2: Megger reading of all electric motors', 'vessel', 0, 1),
			(143, 'E', 3, 2, 'Generator 2: Renewal of wear bearings in motors', 'vessel', 0, 1),
			(144, 'E', 3, 3, 'Generator 2: Clean and varnish motor windings', 'vessel', 0, 1),
			(145, 'E', 4, 1, 'Generator 3: Megger reading of all electric motors', 'vessel', 0, 1),
			(146, 'E', 4, 2, 'Generator 3: Renewal of wear bearings in motors', 'vessel', 0, 1),
			(147, 'E', 4, 3, 'Generator 3: Clean and varnish motor windings', 'vessel', 0, 1),
			(148, 'E', 5, 1, 'Couple Generator (Driven by main engine): Megger reading of all electric motors', 'vessel', 0, 1),
			(149, 'E', 5, 2, 'Couple Generator (Driven by main engine): Renewal of wear bearings in motors', 'vessel', 0, 1),
			(150, 'E', 5, 3, 'Couple Generator (Driven by main engine): Clean and varnish motor windings', 'vessel', 0, 1),
			(151, 'E', 5, 4, 'Couple Generator (Driven by main engine): Gear box condition', 'vessel', 0, 1),
			(152, 'E', 6, 1, 'Switchboards/Motor Control Center: Circuit breaker', 'vessel', 0, 1),
			(153, 'E', 6, 2, 'Switchboards/Motor Control Center: Motor contactors', 'vessel', 0, 1),
			(154, 'E', 6, 3, 'Switchboards/Motor Control Center: Switches', 'vessel', 0, 1),
			(155, 'E', 6, 4, 'Switchboards/Motor Control Center: Voltage relays', 'vessel', 0, 1),
			(156, 'E', 6, 5, 'Switchboards/Motor Control Center: Current transformers', 'vessel', 0, 1),
			(157, 'E', 6, 6, 'Switchboards/Motor Control Center: Pilot lights', 'vessel', 0, 1),
			(158, 'E', 6, 7, 'Switchboards/Motor Control Center: Battery charger', 'vessel', 0, 1),
			(159, 'F', 1, 1, 'Seawater pumps', 'vessel', 0, 1),
			(160, 'F', 1, 2, 'Fresh water pumps', 'vessel', 0, 1),
			(161, 'F', 1, 3, 'GS pump/Fire pump', 'vessel', 0, 1),
			(162, 'F', 1, 4, 'Fuel oil pump', 'vessel', 0, 1),
			(163, 'F', 1, 5, 'Lube oil pump', 'vessel', 0, 1),
			(164, 'F', 1, 6, 'Hydraulic oil pump', 'vessel', 0, 1),
			(165, 'F', 1, 7, 'Purifiers (Lube & Fuel)', 'vessel', 0, 1),
			(166, 'F', 1, 8, 'Air compressors', 'vessel', 0, 1),
			(167, 'F', 1, 9, 'Ventillation fan and blowers', 'vessel', 0, 1),
			(168, 'F', 2, 10, 'Bilge oily water separator', 'vessel', 0, 1),
			(169, 'F', 2, 11, 'Thruster engine', 'vessel', 0, 1),
			(170, 'F', 2, 12, 'Boat davit motor', 'vessel', 0, 1),
			(171, 'A', 1, 1, 'Engine starts and Idles Properly', 'truck', 0, 1),
			(172, 'A', 1, 2, 'Engine Noise Normal', 'truck', 0, 1),
			(173, 'A', 1, 3, 'Auto / Manual Transmission / Transaxle Operation Cold and Hot Shift Quality', 'truck', 0, 1),
			(174, 'A', 1, 4, 'Auto / Manual Transmission / Transaxle Noise Normal - Cold and Hot', 'truck', 0, 1),
			(175, 'A', 1, 5, 'Steers Normally (Response, Centering and Free Play)', 'truck', 0, 1),
			(176, 'A', 1, 6, 'Struts / Shocks Operate Properly', 'truck', 0, 1),
			(177, 'A', 1, 7, 'Brakes / ABS Operates Properly', 'truck', 0, 1),
			(178, 'B', 2, 1, 'Body Panels and Bumpers: Body Panel Inspection', 'truck', 0, 1),
			(179, 'B', 2, 2, 'Body Panels and Bumpers: Bumper Inspection ', 'truck', 0, 1),
			(180, 'B', 3, 1, 'Door, Hood, Decklid, Tailgate: Doors, Hood, Decklid / Tailgate roof Inspection', 'truck', 0, 1),
			(181, 'B', 3, 2, 'Door, Hood, Decklid, Tailgate: Doors, Hood, Decklid / Tailgate Alignment', 'truck', 0, 1),
			(182, 'B', 3, 3, 'Door, Hood, Decklid, Tailgate: Automtic / Manual Release Mechanisms, Hinges, Gas Struts Operate Properly', 'truck', 0, 1),
			(183, 'B', 4, 1, 'Glass and Mirrors: Side, Front & Rear Mirror', 'truck', 0, 1),
			(184, 'B', 4, 2, 'Glass and Mirrors: Windshield, Window Glass Inspection', 'truck', 0, 1),
			(185, 'B', 5, 1, 'Exterior Lights: Front-End Exterior Lights', 'truck', 0, 1),
			(186, 'B', 5, 2, 'Exterior Lights: Back-End Exterior Lights', 'truck', 0, 1),
			(187, 'B', 5, 3, 'Exterior Lights: Side-Exterior Lights', 'truck', 0, 1),
			(188, 'C', 1, 1, 'Airbags and Safety Belts: Safety Belts', 'truck', 0, 1),
			(189, 'C', 1, 2, 'Airbags and Safety Belts: Airbags', 'truck', 0, 1),
			(190, 'C', 2, 1, 'Airconditioner: Air Conditioning System', 'truck', 0, 1),
			(191, 'C', 3, 1, 'Interior Amenities: Tilt / Telescopic Steering Wheel', 'truck', 0, 1),
			(192, 'C', 3, 2, 'Interior Amenities: Steering Column Lock', 'truck', 0, 1),
			(193, 'C', 3, 3, 'Interior Amenities: Instrument Panel and Warning lights', 'truck', 0, 1),
			(194, 'C', 3, 4, 'Interior Amenities: Wipers', 'truck', 0, 1),
			(195, 'C', 3, 5, 'Interior Amenities: Washers', 'truck', 0, 1),
			(196, 'C', 4, 1, 'Carpet and Mats: Floor Mats', 'truck', 0, 1),
			(197, 'C', 4, 2, 'Carpet and Mats: Door Panel & Carpets', 'truck', 0, 1),
			(198, 'C', 5, 1, 'Windows, Door locks and Seats: Seats Upholstery', 'truck', 0, 1),
			(199, 'C', 5, 2, 'Windows, Door locks and Seats: Door Handles and Release Mechanisms', 'truck', 0, 1),
			(200, 'C', 5, 3, 'Windows, Door locks and Seats: Windows Control', 'truck', 0, 1),
			(201, 'D', 1, 1, 'Fluids: Engine Oil / Filter and Chasis Lube', 'truck', 0, 1),
			(202, 'D', 1, 2, 'Fluids: Coolant', 'truck', 0, 1),
			(203, 'D', 1, 3, 'Fluids: Brake Fluid', 'truck', 0, 1),
			(204, 'D', 1, 4, 'Fluids: Automatic Transaxle / Transmission Fluid', 'truck', 0, 1),
			(205, 'D', 1, 5, 'Fluids: Transfer Case Fluid', 'truck', 0, 1),
			(206, 'D', 1, 6, 'Fluids: Drive Axle Fluid', 'truck', 0, 1),
			(207, 'D', 1, 7, 'Fluids: Power Steering Fluid', 'truck', 0, 1),
			(208, 'D', 1, 8, 'Fluids: Manual Transaxle / Transmission Hydraulic Clutch Fluid', 'truck', 0, 1),
			(209, 'D', 1, 9, 'Fluids: Washer Fluid', 'truck', 0, 1),
			(210, 'D', 2, 1, 'Engine: Fluids Leaks', 'truck', 0, 1),
			(211, 'D', 2, 2, 'Engine: Hoses, Lines and Fittings', 'truck', 0, 1),
			(212, 'D', 2, 3, 'Engine: Belts', 'truck', 0, 1),
			(213, 'D', 2, 4, 'Engine: Water, Sludge or Engine Coolant in Oil', 'truck', 0, 1),
			(214, 'D', 2, 5, 'Engine: Oil Pressure', 'truck', 0, 1),
			(215, 'D', 2, 6, 'Engine: Timing Belt / Chain', 'truck', 0, 1),
			(216, 'D', 2, 7, 'Engine: Engine Mounts', 'truck', 0, 1),
			(217, 'D', 3, 1, 'Cooling System: Radiator', 'truck', 0, 1),
			(218, 'D', 3, 2, 'Cooling System: Pressure-Test Radiator Cap', 'truck', 0, 1),
			(219, 'D', 3, 3, 'Cooling System: Cooling Fans, Clutches and Motors', 'truck', 0, 1),
			(220, 'D', 3, 4, 'Cooling System: Coolant Recovery Tank', 'truck', 0, 1),
			(221, 'D', 4, 1, 'Fuel System: Fuel Pump Pressure', 'truck', 0, 1),
			(222, 'D', 4, 2, 'Fuel System: Fuel Filter', 'truck', 0, 1),
			(223, 'D', 4, 3, 'Fuel System: Engine Air Filter', 'truck', 0, 1),
			(224, 'D', 5, 1, 'Electrical System: Starter Operation', 'truck', 0, 1),
			(225, 'D', 5, 2, 'Electrical System: Ignition System', 'truck', 0, 1),
			(226, 'D', 5, 3, 'Electrical System: Battery', 'truck', 0, 1),
			(227, 'D', 5, 4, 'Electrical System: Alternator Output', 'truck', 0, 1),
			(228, 'E', 1, 1, 'Exhaust System: Exhaust System Condition', 'truck', 0, 1),
			(229, 'E', 2, 1, 'Transmission, Transaxle, Differential, Transfer-case: Automatic Transmission / Transaxle', 'truck', 0, 1),
			(230, 'E', 2, 2, 'Transmission, Transaxle, Differential, Transfer-case: Manual Transmission / Transaxle, Differential and Transfer Case', 'truck', 0, 1),
			(231, 'E', 2, 3, 'Transmission, Transaxle, Differential, Transfer-case: Universal Joints, CV Joints and CV Joint Boots', 'truck', 0, 1),
			(232, 'E', 2, 4, 'Transmission, Transaxle, Differential, Transfer-case: Transmission Mounts', 'truck', 0, 1),
			(233, 'E', 2, 5, 'Transmission, Transaxle, Differential, Transfer-case: Differential / Drive Axle', 'truck', 0, 1),
			(234, 'E', 3, 1, 'Tires and Wheels: Tires and Wheels Match and Correct Size', 'truck', 0, 1),
			(235, 'E', 3, 2, 'Tires and Wheels: Tire Tread Depth', 'truck', 0, 1),
			(236, 'E', 3, 3, 'Tires and Wheels: Normal Tire Wear', 'truck', 0, 1),
			(237, 'E', 3, 4, 'Tires and Wheels: Tire Pressure', 'truck', 0, 1),
			(238, 'E', 3, 5, 'Tires and Wheels: Wheels', 'truck', 0, 1),
			(239, 'E', 3, 6, 'Tires and Wheels: Wheel Covers and Center Caps', 'truck', 0, 1),
			(240, 'E', 3, 7, 'Tires and Wheels: Linkage', 'truck', 0, 1),
			(241, 'E', 3, 8, 'Tires and Wheels: Control Arms, Bushings and Ball Joints', 'truck', 0, 1),
			(242, 'E', 3, 9, 'Tires and Wheels: Tie-Rods and Idle Arm', 'truck', 0, 1),
			(243, 'E', 3, 10, 'Tires and Wheels: Sway Bars, Links and Bushings', 'truck', 0, 1),
			(244, 'E', 3, 11, 'Tires and Wheels: Springs', 'truck', 0, 1),
			(245, 'E', 3, 12, 'Tires and Wheels: Struts and Shocks', 'truck', 0, 1),
			(246, 'E', 3, 13, 'Tires and Wheels: Power Steering Pump', 'truck', 0, 1),
			(247, 'E', 4, 1, 'Brake: Calipers and Wheel Cylinders', 'truck', 0, 1),
			(248, 'E', 4, 2, 'Brake: Brake Pads and Shoes', 'truck', 0, 1),
			(249, 'E', 4, 3, 'Brake: Rotors and Drums', 'truck', 0, 1),
			(250, 'E', 4, 4, 'Brake: Brake Lines, Hoses and Fittings', 'truck', 0, 1),
			(251, 'E', 4, 5, 'Brake: Parking Brake', 'truck', 0, 1),
			(252, 'E', 4, 6, 'Brake: Master Cylinder and Booster', 'truck', 0, 1);";
			$this->Mmm->query($sql, "insert action here");
		}
		public function update_staging() {
			##################################
			##################################
			####                          ####
			####   W A R N I N G ! ! !    ####
			####                          ####
			####     Test in local        ####
			####   at your own risk...    ####
			####                          ####
			##################################
			##################################
			if(ENVIRONMENT!="development") $this->Abas->redirect(HTTP_PATH);
			$origin_database		=	"avegabro_abas";
			$destination_database	=	"avegabro_abas_staging";
			$database_tables		=	$this->db->query("SHOW TABLES");
			$database_tables		=	$database_tables->result_array();
			$dbt					=	array();
			foreach($database_tables as $table) {
				$this->db->query('DROP TABLE ".$destination_database.".".$table."');
				$this->db->query('CREATE TABLE ".$destination_database.".".$table." SELECT * FROM ".$origin_database.".".$table."');
				$this->db->query('ALTER TABLE ".$destination_database.".".$table." ADD PRIMARY KEY (id)');
				$this->db->query('ALTER TABLE ".$destination_database.".".$table." MODIFY COLUMN id INT auto_increment');
				$dbt[]	=	$table['Tables_in_'.DBNAME];
			}
			echo "
				<h3>Do the following:</h3>
				<ol>
					<li>Upload 2016 balances - run script located at <a href='".HTTP_PATH."home/import_beginning_balances_from_file' target='_new'>Home->import_beginning_balances_from_file()</a> <strong>[DO THIS ONLY ONCE]</strong></li>
					<li>Upload 2017 Jan-Jun balances from Roel (<a href='".HTTP_PATH."accounting/transactions' target='_new'>Click Here</a>)</li>
					<li>Approve the newly created journal voucher (<a href='".HTTP_PATH."accounting/journal/view_vouchers' target='_new'>Click Here</a>)</li>
					<li>Grant permissions to checkers so they can verify</li>
				</ol>
			";
		}
	}
?>