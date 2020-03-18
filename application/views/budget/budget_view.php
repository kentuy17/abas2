<?php
	$year = date('Y');
	$prev_year = $year - 1;
	$gwapo = false;
	if($budget_summary == null){
		$status = 0;
		$budget_id = 0;
	}else{
		$status = $budget_summary->status;
		$budget_id = $budget_summary->id;
	}
	
	$generate_all = true;
	if($this->Abas->checkPermissions("manager|generate_all_budget",false)){
		$generate_all = false;
	}

	if($status == 'draft'){
		$draft = false;
	}else{
		$draft = true;
	}

	$get_year = $this->input->get('year');
	if(isset($get_year)){
		$disp_year = $get_year;
	}else{
		$disp_year = date('Y');
	}
	
?>
<h2 id="glyphicons-glyphs">
	Company: <?=$company->name?><br/>
	Department: <?=$department->name?>
</h2>
<?php if($this->Abas->checkPermissions("manager|generate_budget",false)){?>
<a href="<?php echo HTTP_PATH.'manager/generate_budget_form';?>" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">
	<button <?php if($generate_all){ if($count != 0) echo "disabled"; } ?> class="btn btn-success">Generate Budget</button>
</a>
<?php if($count != 0){ ?>
<a href="<?php echo HTTP_PATH.'manager/add_account_dialog'?>" data-toggle="modal" data-target="#modalDialog" data-backdrop="static">
	<button <?php if($draft) echo "disabled"; ?> class="btn btn-dark">Add Account</button>
</a> 
<?php } } ?>

<?php if($budget_id != 0){?>
<a href="<?=HTTP_PATH.'manager/generate_budget_submit/'.$budget_id?>">
	<button class="btn btn-primary pull-right" <?php if($draft) echo "disabled" ?>>Submit</button>
</a>
<?php } ?>
<!--button class="btn btn-secondary pull-right" data-toggle="modal" data-target="#modalDialogYear">Filter</button-->
<button class="btn btn-secondary pull-right" onclick="javascript: filterYear()">Filter</button>

<div id="modalDialogYear" class="modal fade modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Select Year
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">×</span>
						</button>
					</h2>
				</div>
			</div>
			<div class="panel-body">
				<form action="<?=HTTP_PATH.'manager/filter'?>" role="form" method="POST" enctype="multipart/form-data">
					<div class="panel panel-info">
						<div class="panel-body" id="summary_container">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<label>Increment:</label>
								<select class="form-control col-lg-3" name="year">
								<?php foreach ($budget_year as $value) { ?>
									<option value="<?=$value->year?>"><?=$value->year?></option>
								<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					<div class="col-xs-12 col-sm-12 col-lg-12"s>
						<br>
						<span class="pull-right">
							
							<input type="submit" value="Filter" class="btn btn-success btn-m"/>
							<input type="button" class="btn btn-danger btn-m" value="Discard" data-dismiss="modal">
						</span>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

	<table data-toggle="table" id="budget-table" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."manager/budget_items?year=".$get_year; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true" data-strict-search="false">
		<thead>
			<tr>
				<th data-field="id" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">ID</th>
				<th data-field="code" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Code</th>
				<th data-field="account_name" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Account Name</th>
				<th data-field="prev_budget_amt" data-align="right" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false"><?=$prev_year?></th>
				<!--th data-field="increment" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Increment %</th-->
				<th data-field="operate" data-formatter="percent" data-events="operateEvents"  data-halign="center" data-align="center">Increment %</th>
				<!--th data-field="curr_budget_amt" data-align="center" data-visible="true" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Estimated Budget</th-->
				<th data-field="operate" data-formatter="budget" data-events="operateEvents"  data-align="right" data-align="center">Estimated Budget</th>
				<th data-field="department" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Department</th>
				<th data-field="company" data-align="center" data-visible="true" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Company</th>
				<th data-field="author" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Added by</th>
				<th data-field="date_created" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Added on</th>
				<th data-field="updated_by" data-align="center" data-visible="false" data-sortable="true" data-filter-control="input" data-filter-strict-search="false">Updated by</th>
				<th data-field="date_updated" data-align="center" data-visible="false" data-sortable="true" data-filter-control="select" data-filter-strict-search="false">Updated on</th>
				<th data-field="operate" data-formatter="view" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
			</tr>
		</thead>
	</table>

 <?php //if($draft) echo 'disabled'; ?>

<script>

	function view(value, row, index) {
		return [
			'<a onclick="confirmDelete('+row['id']+')">',
				'<button class="btn btn-danger btn-xs" <?php if($draft) echo 'disabled';?>>Remove</button>',
			'</a>',
		].join('');
	}

	function budget(value, row, index) {
		return [
			<?php if(!$draft){?>
				'<a style="cursor:pointer; color:blue"',
				' onclick="editAmount('+row['id']+')">'+row['curr_budget_amt']+'</a>'
			<?php }else{ ?>
				row['curr_budget_amt']
			<?php } ?>
		].join('');
	}

	function percent(value, row, index) {
		return [
			<?php if(!$draft){?>
				'<a style="cursor:pointer; color:blue"',
				' onclick="editPercentage('+row['id']+')">'+row['increment']+'%</a>'
			<?php }else{ ?>
				row['increment']
			<?php } ?>
		].join('');
	}

	$(function () {
		var $table = $('#budget-table');
		$table.bootstrapTable();
	});

</script>
				
<script type="text/javascript">

    function confirmDelete(id){
    	bootbox.confirm(
    	{
			size: "small",
		    title: "Remove Account",
		    message: "Are you sure you want to remove this account?",
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
		    		window.location.href = "<?=HTTP_PATH.'manager/del_account/'?>" + id;
		    	}
		    }
		});
    }

    function editPercentage(id){
    	bootbox.prompt({
    		size: "small",
		    title: "Input Percentage",
		    centerVertical: true,
		    inputType: 'number',
		    callback: function (result) {
		    	if(result != null){
		    		window.location.href = "<?=HTTP_PATH.'manager/edit_percent/'?>"+result+'/'+id;	
		    	}
		    }
		});
    }

    function editAmount(id){
    	bootbox.prompt({
    		size: "small",
		    title: "Input Amount",
		    centerVertical: 'true',
		    inputType: 'number',
		    callback: function (result) {
		    	if(result != null){
		    		window.location.href = "<?=HTTP_PATH.'manager/edit_amount/'?>"+result+'/'+id;	
		    	}
		    }
		});
    }

    function filterYear(){
    	bootbox.prompt({
    		size: "small",
		    title: "Select Year",
		    centerVertical: 'true',
		    inputType: 'select',
		    inputOptions: [
			    {
			        text: 'Choose one...',
			        value: '',
			    },
		    <?php foreach($budget_year as $row) {?>
			    {
			        text: '<?=$row->year?>',
			        value: '<?=$row->year?>',
			    },
			<?php } ?>
		    ],
		    callback: function (result) {
		    	if(result != null){
		    		window.location.href = "<?=HTTP_PATH.'manager/budget_view?year='?>"+result;	
		    	}
		    }
		});
    }
</script>