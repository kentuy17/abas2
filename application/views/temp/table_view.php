<style>

#content{ margin-top:-20px}

</style>

<div class="panel-group" id="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<a href="<?php echo HTTP_PATH.'home/encode/'.$table.'/add'; ?>" class="glyphicon glyphicon-th-user btn btn-primary pull-right" data-toggle="modal" data-target="#modalDialog" title="Add New Employee" >Add Record</a>
		</div>
		<div class="panel-body">
			<table data-toggle="table" id="hr-table" class="table table-striped table-hover" data-url="<?php echo HTTP_PATH."home/getTable/".$table; ?>" data-cache="false" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true">
				<thead>
				<tr>
					<?php
						foreach($tablefields as $ctr=>$tf) {
							// if($tf->COLUMN_NAME != "id") {
								echo '<th data-field="'.$tf->COLUMN_NAME.'" data-align="center" '.(($ctr > 5) ? 'data-visible="false"':'').' data-sortable="true">'.ucwords(str_replace("_"," ",$tf->COLUMN_NAME)).'</th>';
							// }
						}
					?>
					<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<script>


function operateFormatter(value, row, index) {
	return [
		// '<a class="like" href="<?php echo HTTP_PATH.'hr/employee_profile/view/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Profile">',
			// '<i class="glyphicon glyphicon-list-alt"></i>',
		// '</a> ',
		'<a class="edit ml10" href="<?php echo HTTP_PATH.'home/encode/'.$table.'/edit/'; ?>'+row['id']+'" title="Edit" data-toggle="modal" data-target="#modalDialog">',
			'<i class="glyphicon glyphicon-edit"></i> Edit',
		'</a> ',
		'<a class="like" href="<?php echo HTTP_PATH.'home/encode/'.$table.'/delete/'; ?>'+row['id']+'" title="Profile">',
			'<i class="glyphicon glyphicon-list-alt"></i> Delete',
		'</a> ',
	].join('');
}



window.operateEvents = {
	'click .like': function (e, value, row, index) {
		p = row["sid"];
		var wid = 940;
		var leg = 680;
		var left = (screen.width/2)-(wid/2);
		var top = (screen.height/2)-(leg/2);
		// window.open('studProfile.cfm?pid='+p,'popuppage','width='+wid+',toolbar=0,resizable=1,location=no,scrollbars=no,height='+leg+',top='+top+',left='+left);
	},
	'click .edit': function (e, value, row, index) {
		p = row["sid"];
		// addForm(p);
	}
};



$(function () {
	var $table = $('#hr-table');
	$table.bootstrapTable();
});
</script>
