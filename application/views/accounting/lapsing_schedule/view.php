<h2>Lapsing Schedule</h2>
<div>
<?php
	if($lapsing_schedule->status=='Active'){
		if($this->Abas->checkPermissions("accounting|add_lapsing_schedule",FALSE)){
			echo '<a href="#" onclick="recalculateLapsingSchedule('.$lapsing_schedule->id.');" class="btn btn-success exclude-pageload" target="">Recompute</a>';
			echo '<a href="#" onclick="lockLapsingSchedule('.$lapsing_schedule->id.');" class="btn btn-danger exclude-pageload" target="">Lock</a>';
		}
	}
	echo '<a href="'.HTTP_PATH.'accounting/lapsing_schedule/print/'.$lapsing_schedule->id.'" class="btn btn-info exclude-pageload" target="_blank">Print</a>';
	echo '<a href="'.HTTP_PATH.'accounting/lapsing_schedule/listview" class="btn btn-dark force-pageload">Back</a>';
?>
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Transaction Code No. <?php echo $lapsing_schedule->id;?> |
				Control No. <?php echo $lapsing_schedule->control_number; ?>
				<span class="pull-right">Status: <?php echo $lapsing_schedule->status;?></span>
			</h3>
		</div>
	
	<div class="panel-body">
		<h3 class="text-center"><?php echo $lapsing_schedule->company_name; ?></h3>
		<h4 class="text-center"><?php echo $lapsing_schedule->company_address; ?></h3>
		<h4 class="text-center"><?php echo $lapsing_schedule->company_contact; ?></h4>

		<table class="table table-striped table-bordered">
			<tr>
				<th>For the Year Ended:</th>
				<td><?php echo $lapsing_schedule->year; ?></td>
			</tr>
			<tr>
				<th>Created By:</th>
				<td><?php echo $lapsing_schedule->created_by; ?></td>
			</tr>
			<tr>
				<th>Date Created:</th>
				<td><?php echo  date("F d, Y", strtotime($lapsing_schedule->created_on)); ?></td>
			</tr>
			<?php if($lapsing_schedule->modified_by!=0){ ?>
			<tr>
				<th>Last Updated By:</th>
				<td><?php echo $lapsing_schedule->modified_by; ?></td>
			</tr>
			<tr>
				<th>Last Updated:</th>
				<td><?php echo  date("F d, Y", strtotime($lapsing_schedule->modified_on)); ?></td>
			</tr>
		<?php } ?>
		</table>
	</div>

</div>

<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	<div class="panel-body" style="overflow-x: auto">
		<a href="<?php echo HTTP_PATH.'accounting/lapsing_schedule/add_fixed_asset/'.$lapsing_schedule->id;?>" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static" data-keyboard="false">Add New Fixed-Asset</a>
		<table class="table table-striped table-bordered">
		<?php 

			 $grandtotal_begin_accumulated=0;
             $grandtotal_begin_netbook=0;
             $grandtotal_jan=0;
             $grandtotal_feb=0;
             $grandtotal_mar=0;
             $grandtotal_apr=0;
             $grandtotal_may=0;
             $grandtotal_jun=0;
             $grandtotal_jul=0;
             $grandtotal_aug=0;
             $grandtotal_sep=0;
             $grandtotal_oct=0;
             $grandtotal_nov=0;
             $grandtotal_dec=0;
             $grandtotal_end_accumulated=0;
             $grandtotal_end_netbook=0;
			foreach($categories as $category){ 
				$xx =0;
				 foreach($lapsing_schedule_details as $asset){
	                if($category['category']==$asset->category){
	                	$xx++;
	                }
	             }
             	if($xx>0){
             ?>
						<tr>
							<th colspan="28"><?php echo $category['category'];?></th>
						</tr>
		                <tr>
		                  <th>#</th>
		                  <th>Fixed Asset Code</th>
		                  <th>Asset Description</th>
		                  <th>Department</th>
		                  <th>Date Acquired</th>
		                  <th>Total Cost</th>
		                  <th>Salvage Value</th>
		                  <th>Depreciable Amount</th>
		                  <th>Useful Life</th>
		                  <th>Annual Depreciation</th>
		                  <th>Monthly Depreciation</th>
		                  <th>BEG - Accum. Depreciation</th>
		                  <th>BEG -Net Book Value</th>
		                  <th>Jan</th>
		                  <th>Feb</th>
		                  <th>Mar</th>
		                  <th>Apr</th>
		                  <th>May</th>
		                  <th>Jun</th>
		                  <th>Jul</th>
		                  <th>Aug</th>
		                  <th>Sep</th>
		                  <th>Oct</th>
		                  <th>Nov</th>
		                  <th>Dec</th>
		                  <th>END - Accum. Depreciation</th>
		                  <th>END -Net Book Value</th>
		                  <?php if($lapsing_schedule->status!='Locked'){ ?>
		                  	<?php if($this->Abas->checkPermissions("accounting|edit_lapsing_schedule",FALSE)){?>
		                  		<th>Manage</th>
		                  	<?php } ?>
		                  <?php } ?>
		                </tr>
		              <?php 
		                 $ctr=1;
		                 $subtotal_begin_accumulated=0;
		                 $subtotal_begin_netbook=0;
		                 $subtotal_jan=0;
		                 $subtotal_feb=0;
		                 $subtotal_mar=0;
		                 $subtotal_apr=0;
		                 $subtotal_may=0;
		                 $subtotal_jun=0;
		                 $subtotal_jul=0;
		                 $subtotal_aug=0;
		                 $subtotal_sep=0;
		                 $subtotal_oct=0;
		                 $subtotal_nov=0;
		                 $subtotal_dec=0;
		                 $subtotal_end_accumulated=0;
		                 $subtotal_end_netbook=0;
		                 $grandtotal =0;
		                foreach($lapsing_schedule_details as $asset){
		                	if($category['category']==$asset->category){
			                	echo "<tr>";
			                	echo "<td>".$ctr."</td>";
			                	echo "<td>".$asset->asset_code."</td>";
			                  	echo "<td>".$asset->item_name. ", " . $asset->item_particular."</td>";
			                  	echo "<td>".$asset->department."</td>";
			                  	echo "<td>".$asset->date_acquired."</td>";
			                  	echo "<td>".number_format($asset->total_cost,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->salvage_value,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->depreciable_amount,2,'.',',')."</td>";
			                  	echo "<td>".$asset->useful_life."</td>";
			                  	echo "<td>".number_format($asset->annual_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->monthly_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->begin_accumulated_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->begin_net_book_value,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->january_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->february_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->march_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->april_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->may_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->june_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->july_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->august_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->september_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->october_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->november_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->december_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->end_accumulated_depreciation,2,'.',',')."</td>";
			                  	echo "<td>".number_format($asset->end_net_book_value,2,'.',',')."</td>";
			                  	if($lapsing_schedule->status!='Locked'){
			                  		if($this->Abas->checkPermissions("accounting|edit_lapsing_schedule",FALSE)){
		                  				echo '<td><a href="'.HTTP_PATH.'accounting/lapsing_schedule/edit/'.$asset->id.'" class="btn btn-warning btn-xs exclude-pageload" data-toggle="modal" data-target="#modalDialogNorm" data-backdrop="static" data-keyboard="false">Edit</a></td>';
		                  			}
		                  		}
		                  		echo "</tr>";
			                  	$ctr++;
			                  	 $subtotal_begin_accumulated=$subtotal_begin_accumulated+$asset->begin_accumulated_depreciation;
				                 $subtotal_begin_netbook=$subtotal_begin_netbook+$asset->begin_net_book_value;
				                 $subtotal_jan=$subtotal_jan+$asset->january_depreciation;
				                 $subtotal_feb=$subtotal_feb+$asset->february_depreciation;
				                 $subtotal_mar=$subtotal_mar+$asset->march_depreciation;
				                 $subtotal_apr=$subtotal_apr+$asset->april_depreciation;
				                 $subtotal_may= $subtotal_may+$asset->may_depreciation;
				                 $subtotal_jun= $subtotal_jun+$asset->june_depreciation;
				                 $subtotal_jul=$subtotal_jul+$asset->july_depreciation;
				                 $subtotal_aug=$subtotal_aug+$asset->august_depreciation;
				                 $subtotal_sep=$subtotal_sep+$asset->september_depreciation;
				                 $subtotal_oct=$subtotal_oct+$asset->october_depreciation;
				                 $subtotal_nov=$subtotal_nov+$asset->november_depreciation;
				                 $subtotal_dec=$subtotal_dec+$asset->december_depreciation;
				                 $subtotal_end_accumulated=$subtotal_end_accumulated+$asset->end_accumulated_depreciation;
				                 $subtotal_end_netbook=$subtotal_end_netbook+$asset->end_net_book_value;
			                 }
		                }
		                echo '<tr><td colspan=\'11\' style="text-align:right">Sub-total</td>';
			                echo '<td>'.number_format($subtotal_begin_accumulated,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_begin_netbook,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_jan,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_feb,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_mar,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_apr,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_may,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_jun,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_jul,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_aug,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_sep,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_oct,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_nov,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_dec,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_end_accumulated,2,'.',',').'</td>';
			                echo '<td>'.number_format($subtotal_end_netbook,2,'.',',').'</td>';
		                echo '</tr>';
		              ?>
		  		<?php 
		  		 $grandtotal_begin_accumulated=$grandtotal_begin_accumulated+$subtotal_begin_accumulated;
                 $grandtotal_begin_netbook=$grandtotal_begin_netbook+$subtotal_begin_netbook;
                 $grandtotal_jan=$grandtotal_jan+$subtotal_jan;
                 $grandtotal_feb=$grandtotal_feb+$subtotal_feb;
                 $grandtotal_mar=$grandtotal_mar+$subtotal_mar;
                 $grandtotal_apr=$grandtotal_apr+$subtotal_apr;
                 $grandtotal_may= $grandtotal_may+$subtotal_may;
                 $grandtotal_jun= $grandtotal_jun+$subtotal_jun;
                 $grandtotal_jul=$grandtotal_jul+$subtotal_jul;
                 $grandtotal_aug=$grandtotal_aug+$subtotal_aug;
                 $grandtotal_sep=$grandtotal_sep+$subtotal_sep;
                 $grandtotal_oct=$grandtotal_oct+$subtotal_oct;
                 $grandtotal_nov=$grandtotal_nov+$subtotal_nov;
                 $grandtotal_dec=$grandtotal_dec+$subtotal_dec;
                 $grandtotal_end_accumulated=$grandtotal_end_accumulated+$subtotal_end_accumulated;
                 $grandtotal_end_netbook=$grandtotal_end_netbook+$subtotal_end_netbook;
		  		} ?>
  		<?php } ?>
  		
  			<?php 
  			 echo '<tr><td colspan=\'11\' style="text-align:right"><b>Grand Total</b></td>';
                echo '<td><b>'.number_format($grandtotal_begin_accumulated,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_begin_netbook,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_jan,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_feb,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_mar,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_apr,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_may,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_jun,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_jul,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_aug,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_sep,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_oct,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_nov,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_dec,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_end_accumulated,2,'.',',').'</b></td>';
                echo '<td><b>'.number_format($grandtotal_end_netbook,2,'.',',').'</b></td>';
            echo '</tr>';
		    ?>
  		</table>
	</div>
</div>

<script type="text/javascript">
function lockLapsingSchedule(id){
	bootbox.confirm({
		size: "small",
	    title: "Lock Lapsing Schedule",
	    message: "Are you sure you want to lock this Lapsing Schedule? (Once locked, it will be no longer recomputed.)",
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
	    		window.location.href = "<?php echo HTTP_PATH; ?>accounting/lapsing_schedule/lock/" + id;
	    	}

	    }
	});
}
function recalculateLapsingSchedule(id){
	bootbox.confirm({
		size: "small",
	    title: "Recalculate Lapsing Schedule",
	    message: "Are you sure you want to recompute the depreciation this month?",
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
	    		window.location.href = "<?php echo HTTP_PATH; ?>accounting/lapsing_schedule/recalculate/" + id;
	    	}

	    }
	});
}
</script>