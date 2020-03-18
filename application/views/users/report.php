<div>

	<div class="xx_title">
		<h2>Users Summary Report</h2>
	</div>

		<a id="reset" href="<?php echo HTTP_PATH."/users/summary_report/filter"; ?>" class="glyphicon-th-user btn btn-success pull-center exclude-pageload" title="Filter" data-toggle="modal" data-target="#modalDialog">Filter</a>
		
		<table class="table table-bordered table-striped table-hover" data-show-columns="true" data-search="true" data-cache="false" data-pagination="true" data-filter-control="false" data-url="" data-show-columns="true" data-export-data-type="all" data-show-export="true" data-export-types="['excel']">

			<thead>
				<tr>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Username</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Email</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>First Name</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Middle Name</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Last Name</th>
					<th data-filter-control='select' data-sortable='true' data-align='center'>Role</th>
					<th data-filter-control='select' data-sortable='true' data-align='center'>Location</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Added On</th>
					<th data-filter-control='select' data-sortable='true' data-align='center'>Status</th>
					<th data-filter-control='select' data-sortable='true' data-align='center'>Require Reset?</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Last activity</th>
					<th data-filter-control='input' data-sortable='true' data-align='center'>Permissions</th>
				</tr>
			</thead>

			<tbody>
				<?php
					$ctr=1;
					if(count($users)>0){
						foreach($users as $row){
							if($row['stat']==1){
								$stat = "Activated";
							}else{
								$stat = "Deactivated";
							}
							if($row['require_reset']==1){
								$reset = "Yes";
							}else{
								$reset = "No";
							}
							echo "<tr>";
								echo "<td>".$row['username']."</td>";
								echo "<td>".$row['email']."</td>";
								echo "<td>".$row['first_name']."</td>";
								echo "<td>".$row['middle_name']."</td>";
								echo "<td>".$row['last_name']."</td>";
								echo "<td>".$row['role']."</td>";
								echo "<td>".$row['user_location']."</td>";
								echo "<td>".date('Y-M-d',strtotime($row['created']))."</td>";
								echo "<td>".$stat."</td>";
								echo "<td>".$reset."</td>";
								echo "<td><a class='btn btn-xs btn-info exclude-pageload' onclick='javascript:showAct(".$ctr.");'>View</a></td>";
								echo "<td><a class='btn btn-xs btn-warning exclude-pageload' onclick='javascript:showPerm(".$ctr.");'>View</a></td>";
							echo "</tr>";

							echo "<tr id='act".$ctr."' class='hidden'><td colspan='12'>";

								echo "<table class='table table-bordered table-striped table-hover'>";
									echo "<tr><th>Action ID</th><th>Timestamp</th><th>Query</th><th>Action</th></tr>";
										$activities = $row['activities'];
										if(count($activities)>0){
											foreach($activities as $ac) {
												echo	"<tr>";
													echo "<td>".$ac['id']."</td>";
													echo "<td>".$ac['timestamp']."</td>";
													echo "<td>".$ac['query']."</td>";
													echo "<td>".$ac['action']."</td>";
												echo	"</tr>";
											}
										}else{
											echo "<tr><td colspan='4'><center>No Activities Found</center></td></tr>";
										}
								echo	"</table></td></tr>";


							echo "<tr id='perm".$ctr."' class='hidden'><td colspan='12'>";

								echo "<table class='table table-bordered table-striped table-hover'>";
									echo "<tr><th>Subsystem</th><th>Module</th></tr>";
										$permissions = $row['permissions'];
										if(count($permissions)>0){
											foreach($permissions as $perm) {
												$page = explode("|",$perm['page']);
												echo	"<tr>";
													echo "<td>".strtoupper($page[0])."</td>";
													echo "<td>".ucwords(str_replace('_',' ',$page[1]))."</td>";
												echo	"</tr>";
											}
										}else{
											echo "<tr><td colspan='2'><center>No Permissions Found</center></td></tr>";
										}
								echo	"</table></td></tr>";

							$ctr++;
						}
					}else{
						echo "<tr><td colspan='12'><center>No Record Found</center></td></tr>";
					}
				?>
			</tbody>

		</table>
	</div>

</div> 
<script>
function showAct(actid) {
	$("#act"+actid).toggleClass('hidden');
}
function showPerm(actid) {
	$("#perm"+actid).toggleClass('hidden');
}
</script>	
									
							
