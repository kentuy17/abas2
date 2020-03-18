	<h2>Vessel Certificates</h2>
		<?php if($this->Abas->checkPermissions("operations|add_vessel_certificates",false)){ ?>
			<a href="<?php echo HTTP_PATH.'vessels/vessel_certificates/add'; ?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static" >Add</a>
		<?php } ?> 

		<a href="<?php echo HTTP_PATH.'vessels/vessel_certificates'; ?>" class="btn btn-dark force-pageload">Refresh</a>
	
    <table data-toggle="table" id="certificate-table" class="table table-striped table-hover" data-url="<?php echo HTTP_PATH."vessels/getVesselCerts"; ?>" data-show-columns="true" data-cache="false" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 500,1000]" data-search="true" data-export-data-type="all" data-show-export="true" data-export-types="['excel']" data-filter-control="true" data-filter-strict-search="false">
		<thead>
		<tr>
			<th data-field="vessel_id" data-align="center" data-sortable="true" data-filter-control='select'>Vessel Name</th>
			<th data-field="cert_date" data-align="center" data-sortable="true" data-filter-control='input'>Certificate Date</th>
			<th data-field="expiration_date" data-align="center" data-sortable="true" data-filter-control='input'>Expiration Date</th>
			<th data-field="type" data-align="center" data-sortable="true" data-filter-control='input'>Doc Type</th>
			<th data-field="status" data-align="center" data-sortable="true" data-filter-control='select'>Status</th>
			
			<?php if($this->Abas->checkPermissions("operations|add_vessel_certificates",false)){ ?>
					<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents"  data-halign="center" data-align="center" >Manage</th>
			<?php } ?> 
		</tr>
		</thead>
	</table>
<script>
	function operateFormatter(value, row, index) {
        return [
               '<a href="<?php echo HTTP_PATH.'vessels/vessel_certificates/edit/'; ?>'+row['id']+'" class="btn btn-warning btn-xs btn-block" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static">Edit</a>',
            '</a> '
			//'<a class="like" href="<?php //echo HTTP_PATH.'home/vessel_certs/delete/'; ?>'+row['id']+'" title="Profile">',
			//	'<i class="glyphicon glyphicon-list-alt"></i> Delete',
			//'</a> ',
        ].join('');
    }

	$(function () {
        var $table = $('#certificate-table');
        $table.bootstrapTable();
    });
</script>
