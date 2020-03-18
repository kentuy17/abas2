<div class="panel panel-primary">

	<div class="panel-heading" style="min-height">
		View Truck Profile
		 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	</div>
</div>

  	<div class="panel-body">
    	<ul class="nav nav-tabs" role="tablist">
			<li class="active" role="presentation"><a data-toggle="tab" href="#vinfo-tab" role="tab">General Info</a></li>
			<li role="presentation"><a data-toggle="tab" href="#vspec-tab" role="tab">Specifications</a></li>
		</ul>

        <div class="tab-content">
        	<div role="tabpanel" class="tab-pane fade active in" id="vinfo-tab" aria-labelledby="vinfo-tab">
        		<div class="x_panel" style="overflow-x: scroll; max-height: 500px;overflow-y: scroll;">
		        	<table id="info-table" class="table table-bordered table-striped table-hover">
		        		<tr>
		        			<td style="width:400px"><img src="<?php echo LINK.'assets/uploads/operations/trucks/'.$truck[0]['photo_path'];?>" width="400" height="400"><h2><center><?php echo $truck[0]['plate_number'];?></center></h2></td>
		        			<td>
		        				<?php
		        					echo "<table class='table table-bordered table-striped table-hover'>";
			        					echo "<tr><td colspan='2'><h2>".$company->name."</h2></td></tr>";
			        					echo "<tr><td><b>Make: </b></td><td>".$truck[0]['make']."</td></tr>";
			        					echo "<tr><td><b>Model/Year: </b></td><td>".$truck[0]['model']."</td></tr>";
			        					echo "<tr><td><b>Vehicle Type/Description: </b></td><td>".$truck[0]['type']."</td></tr>";
			        					echo "<tr><td><b>Date Acquired: </b></td><td>".date("j F Y",strtotime($truck[0]['date_acquired']))."</td></tr>";
			        					echo "<tr><td><b>Acquisition Cost: </b></td><td>".number_format($truck[0]['aquisition_cost'],2,".",",")."</td></tr>";
			        					echo "<tr><td><b>Registration Month: </b></td><td>".$truck[0]['registration_month']."</td></tr>";
			        					$status = ($truck[0]['stat']==1)?"Active":"Inactive";
			        					echo "<tr><td><b>Status: </b></td><td>".$status."</td></tr>";
		        					echo "</table>";
		        				?>
		        			</td>
		        		</tr>
		        	</table>
	      	    </div>
        	</div>

        	<div role="tabpanel" class="tab-pane fade" id="vspec-tab" aria-labelledby="vspec-tab">
    			<div class="x_panel" style="overflow-x: scroll; max-height: 500px;overflow-y: scroll;">
	        		<table id="spec-table" class="table table-bordered table-striped table-hover">
	        			<?php 
	        				echo "<tr><td><b>Plate Number</b></td><td>".$truck[0]['plate_number']."</td></tr>";
	        				echo "<tr><td><b>Engine Number</b></td><td>".$truck[0]['engine_number']."</td></tr>";
	        				echo "<tr><td><b>Chassis Number</b></td><td>".$truck[0]['chassis_number']."</td></tr>";
	        				echo "<tr><td><b>Color</b></td><td>".$truck[0]['color']."</td></tr>";
	        			?>
		        	</table>
        		</div>
        	</div>
		</div>
	</div>