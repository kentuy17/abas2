<h2>Employees For AWOL</h2>
<a href="<?php echo HTTP_PATH.'hr'; ?>" class="btn btn-dark force-pageload">Back</a>
<table data-toggle="table" id="hr-table" class="table table-bordered table-striped table-hover" data-pagination="true" data-show-columns="true" data-page-list="[5, 10, 20, 50, 100, 200, 500, 1000, 2500, 5000, 10000]" data-search="true">
	<thead>
		<tr>
		
			<th>Name</th>
			<th>Company</th>
			<th>Department</th>
			<th>Vessel/Office</th>
			<th>Position</th>
			<th>On-leave until</th>
			<th>15 days to AWOL</th>
			<th>Contact No.</th>
			<th>Manage</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			if(isset($employees)){
				foreach($employees as $row){
					if($row->last_name!=null){
						$company = $this->Abas->getCompany($row->company_id);
						$department = $this->Abas->getDepartment($row->department);
						$vessel = $this->Abas->getVessel($row->vessel_id);
						$position = $this->Abas->getPosition($row->position);
						echo "<tr>";
							echo "<td>".$row->last_name.", ".$row->first_name." ".$row->middle_name."</td>";
							echo "<td>".$company->name."</td>";
							echo "<td>".$department->name."</td>";
							echo "<td>".$vessel->name."</td>";
							echo "<td>".$position->name."</td>";
							echo "<td>".date('F d, Y',strtotime($row->to_date))."</td>";
							echo "<td>".date('F d, Y',strtotime($row->to_date .'+ 15 days'))."</td>";
							echo "<td>".$row->mobile."</td>";
							echo "<td><a class='btn btn-info btn-xs btn-block' href='".HTTP_PATH."hr/employee_profile/view/".$row->id."'>View</a></td>";
						echo "</tr>";
					}
				}
			}
		?>
	</tbody>
</table>

<script>
	$(function () {
        var $table = $('#hr-table');
        $table.bootstrapTable();
    });
</script>