<div class='panel panel-primary'>
  <div class='panel-heading'><h2 class="panel-title">Materials/Services Request Details<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button></h2>
  </div>
</div>
<div class="modal-body table-responsive">
  <div style="overflow-x: auto">
        <table class='table table-striped table-bordered'>
          <tr>
            <td colspan='2'><h2>Summary</h2></td>
          </tr>
          <tr>
            <td>Transaction Code No.</td>
            <td><?php echo $request_data['id'] ?></td>
          </tr>
          <tr>
            <td>Date Created</td>
            <td><?php echo date('F j, Y', strtotime($request_data['tdate'])) ?></td>
          </tr>
          <tr>
            <td>Requested by</td>
            <td> <?php echo $request_data['requisitioner'] ." for ". $request_data['vessel_name']?></td>
          </tr>
          <tr>
            <td>Approved by</td>
            <td><?php echo isset($request_data['approved_by_name'])?$request_data['approved_by_name']:"(Not yet approved)" ?></td>
          </tr>
          <tr>
            <td>Remark</td>
            <td><?php echo $request_data['remark'] ?></td>
          </tr>
        </table>
        <table style="margin-top:10px"  class="table table-striped table-bordered" cellspacing="0" width="120%">
          <thead>
            <tr>
              <th colspan="6"><h2>Details</h2></th>
            </tr>
            <tr>
              <th width="10%" align="center">Itemcode</th>
              <th width="40%">Description</th>
              <th width="10%">Unit</th>
              <th width="10%" >Qty</th>
              <th width="15%">Status</th>
              <th width="20%">Assigned To (Purchaser)</th>
            </tr>
          </thead>
          <tbody>
                <?php
										foreach($request_data['details'] as $item){ 
                    $assigned_to = $this->Abas->getUser($item['assigned_to']);
								?>
							<tr style="color:#333333">
								  <td align="center"> <?php echo $item['item_details']['item_code'] ?></td>
                  <td><?php echo $item['item_details']['description'] ?></td>
                  <td><?php echo $item['item_details']['unit']?></td>
                  <td align="right"><?php echo $item['quantity']?></td>
                  <td><?php echo ucwords($item['status'])?></td>
                  <td><?php echo isset($assigned_to['full_name'])?$assigned_to['full_name']:"(Unassigned)" ?></td>           
							</tr>        
				<?php  } ?>
            </tbody>
          </table>
    </div>
</div>
<div class="modal-footer" >
  <button type="button" class="btn btn-default btn-danger" data-dismiss="modal">Close</button>
</div