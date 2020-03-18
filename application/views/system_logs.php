<h2>System Logs</h2>
<a href="<?php echo HTTP_PATH.'system/logs'; ?>" class="btn btn-dark force-pageload">Refresh</a>
<table data-toggle="table" id="system_logs" class="table table-bordered table-striped table-hover" data-url="<?php echo HTTP_PATH."system/logs/json"; ?>" data-cache="false" data-pagination="true" data-side-pagination="server" data-sort-name="timestamp" data-sort-order="desc" data-show-columns="true" data-search="true" data-page-list="[5, 10, 20, 50, 100]" data-filter-control="true" data-filter-strict-search="false" data-pagination-v-align="both">
	<thead>
		<tr>
			<th data-filter-control="input" data-sortable="false" data-visible="true" data-align="center" data-field='ip'>IP</th>
			<th data-filter-control="input" data-sortable="false" data-visible="true" data-align="center" data-field='session_id'>Session ID</th>
			<th data-filter-control="input" data-sortable="true" data-visible="true" data-align="center" data-field='timestamp'>Timestamp</th>
			<th data-filter-control="input" data-sortable="false" data-visible="true" data-align="center" data-field='query'>Query</th>
			<th data-filter-control="input" data-sortable="false" data-visible="true" data-align="center" data-field='user'>User</th>
			<th data-filter-control="input" data-sortable="false" data-visible="true" data-align="center" data-field='action'>Action</th>
			<th data-filter-control="input" data-sortable="false" data-visible="true" data-align="center" data-field='page'>Page</th>
			<th data-filter-control="input" data-sortable="false" data-visible="true" data-align="center" data-field='referrer'>Referrer</th>
			<th data-filter-control="input" data-sortable="false" data-visible="true" data-align="center" data-field='source'>Source</th>
		</tr>
	</thead>
</table>