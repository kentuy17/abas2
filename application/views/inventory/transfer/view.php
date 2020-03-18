<h2>Materials Transfer Request</h2>
<div>
<?php
	$user_location = $_SESSION['abas_login']['user_location'];
	if(count($STR_details)>0){
    if($user_location==$MTR[0]['from_location']){
		  echo '<a href="'.HTTP_PATH.'inventory/transfer/print_str/'.$MTR[0]['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print STR</a>';
      echo '<a href="'.HTTP_PATH.'inventory/transfer/print_gatepass/'.$MTR[0]['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print Gate-pass</a>';
    }
	}else{
		if($user_location==$MTR[0]['from_location']){
			echo '<a href="'.HTTP_PATH.'inventory/transfer/add_str/'.$MTR[0]['id'].'" class="btn btn-success exclude-pageload" data-toggle="modal" data-target="#modalDialog">Issue STR</a>';
		}else{
       echo '<a href="'.HTTP_PATH.'inventory/transfer/print_mtr/'.$MTR[0]['id'].'" class="btn btn-info exclude-pageload" target="_blank">Print MTR</a>';
    }
	}
	echo '<a href="'.HTTP_PATH.'inventory/transfer/listview" class="btn btn-dark force-pageload">Back</a>';
?>
</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">
				Transaction Code No. <?php echo $MTR[0]['id'];?> |
				Control No. <?php echo $MTR[0]['control_number']; ?>
				<span class='pull-right'>
					Status: <?php echo $MTR[0]['status'];?>
				</span>
			</h3>
			
		</div>
	
	<div class="panel-body">

		<h3 class="text-center"><?php echo $company->name; ?></h3>
		<h4 class="text-center"><?php echo $company->address; ?></h3>
		<h4 class="text-center"><?php echo $company->telephone_no." - ".$company->fax_no; ?></h4>

		<table class="table table-striped table-bordered">
			<tr>
				<th>Transfer Request Date:</th>
				<td><?php echo date('F j, Y',strtotime($MTR[0]['transfer_date'])); ?></td>
			</tr>
			<tr>
				<th>Requesting Warehouse:</th>
				<td><?php echo $MTR[0]['to_location']; ?></td>
			</tr>
			<tr>
				<th>Issuing Warehouse:</th>
				<td><?php echo $MTR[0]['from_location']; ?></td>
			</tr>
      <tr>
        <th>Requested For:</th>
        <td><?php 
          $requested_for =  $this->Abas->getVessel($MTR[0]['requested_for']);
          echo $requested_for->name; 
          ?></td>
      </tr>
			<tr>
				<th>Remarks:</th>
				<td><?php echo  $MTR[0]['remark']; ?></td>
			</tr>
		</table>
		<p>Created on <?php echo date("h:i:s a j F Y", strtotime($MTR[0]['created_on'])); ?> by <?php echo $created_by['full_name']; ?></p>
	</div>

</div>

<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Details</h3></div>
	
     <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
        <a class="panel-heading" role="tab" id="heading1" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
            <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Requested Items</h4>
        </a>
         <div id="collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
              <div class="panel-body" style="overflow-x: auto">
                <table class="table table-striped table-bordered">
                <thead>
                          <tr>
                            <th>#</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Particulars</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $ctr =1;
                            $total = 0;
                            foreach($MTR_details as $row){
                              $item = $this->Inventory_model->getItem($row['item_id']);
                              echo "<tr>";
                                echo "<td>".$ctr."</td>";
                                echo "<td>".$item[0]['item_code']."</td>";
                                echo "<td>".$item[0]['item_name']."</td>";
                                echo "<td>".$item[0]['brand']." ".$item[0]['particular']."</td>";
                                echo "<td>".$row['unit']."</td>";
                                echo "<td>".number_format($row['unit_price'],2,'.',',')."</td>";
                                echo "<td>".$row['qty']."</td>";
                                echo "<td>".number_format(($row['unit_price']*$row['qty']),2,'.',',')."</td>";
                              echo "</tr>";
                              $total = $total + ($row['unit_price']*$row['qty']);
                              $ctr++;
                            }
                            echo "<tr>
                                  <td colspan='7' style='text-align:right'><b>Total Amount</b></td>
                                  <td><b>".number_format($total,2,'.',',')."</b></td>
                                <tr>";
                          ?>
                </tbody>
                </table>
            </div>
         </div>
    </div>


    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
        <a class="panel-heading" role="tab" id="heading2" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2">
            <span class="glyphicon glyphicon-chevron-down pull-right"></span><h4 class="panel-title">Actual Transferred Items</h4>
        </a>
         <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
                <div class="panel-body" style="overflow-x: auto">
                <table class="table table-striped table-bordered">
                <thead>
                          <tr>
                            <th>#</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Particulars</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Received On</th>
                            <th>Received By</th>
                            <th>Remarks</th>
                          <?php if($user_location<>$MTR[0]['from_location']){ ?>
                            <th>Status</th>
                          <?php } ?>
                          </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $ctr =1;
                            $total = 0;
                            foreach($STR_details as $row){
                              $item = $this->Inventory_model->getItem($row['item_id']);
                              echo "<tr>";
                                echo "<td>".$ctr."</td>";
                                echo "<td>".$item[0]['item_code']."</td>";
                                echo "<td>".$item[0]['item_name']."</td>";
                                echo "<td>".$item[0]['brand']." ".$item[0]['particular']."</td>";
                                echo "<td>".$row['unit']."</td>";
                                echo "<td>".number_format($row['unit_price'],2,'.',',')."</td>";
                                echo "<td>".$row['qty']."</td>";
                                echo "<td>".number_format(($row['unit_price']*$row['qty']),2,'.',',')."</td>";
                                if($row['received_on']<>0){
                                  $received_on = date('F j, Y',strtotime($row['received_on']));
                                }else{
                                  $received_on = '--';
                                }
                                echo "<td>".$received_on."</td>";
                                
                                $receiver = $this->Abas->getUser($row['received_by']);
                                if($receiver==''){
                                  $received_by = '--';
                                }else{
                                  $received_by = $receiver['full_name'];
                                }
                                echo "<td>".$received_by."</td>";
                                if($row['remarks']==''){
                                  $remarks ='--';
                                }else{
                                  $remarks = $row['remarks'];
                                }
                                echo "<td>".$remarks."</td>";
                                if($user_location<>$MTR[0]['from_location']){
                                  if($received_by=='--'){
                                    echo '<td><input type="button" class="btn btn-success btn-xs exclude-pageload" onclick="javascript:receiveItem('.$MTR[0]['id'].','.$row['id'].');" value="Receive"/></td>';
                                  }else{
                                     echo '<td>Delivered</td>';
                                  }
                                }
                              echo "</tr>";
                              $total = $total + ($row['unit_price']*$row['qty']);
                              $ctr++;
                            }
                            echo "<tr>
                                  <td colspan='7' style='text-align:right'><b>Total Amount</b></td>
                                  <td><b>".number_format($total,2,'.',',')."</b></td>
                                <tr>";
                          ?>
                </tbody>
                </table>
            </div> 
         </div>
    </div>
</div>

<script type="text/javascript">
  function receiveItem(transfer_id,transfer_detail_id){
    bootbox.prompt({
            size: "medium",
            title: "Are you sure you want to receive this item? (Please provide remarks below.)",
            inputType: 'textarea',
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
              if(result==null || result==""){
                console.log("Do nothing");
              }else{
                $.ajax({
                   type:"POST",
                   url:"<?php echo HTTP_PATH;?>/inventory/transfer/receive_str/"+transfer_id+"/"+transfer_detail_id,
                   data: {comment:result}
                });
                //window.location.href = "<?php echo HTTP_PATH;?>/inventory/transfer/view/" + transfer_id;
                window.location.href = "<?php echo HTTP_PATH;?>/inventory/transfer/listview";
              }
              
            }
        });
  }

</script>