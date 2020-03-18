<?php
   $id = '';
   $company = '';
   $photo_path = '';
   //var_dump($company);exit;
   $name = '';
   $ex_name = '';
   $price_sold = '';
   $price_paid = '';
   $length_loa = '';
   $length_lr = '';
   $length_lbp	 = '';							
   $breadth = '';
   $depth	 = '';								
   $draft = '';
   $year_built = '';
   $builder = '';
   $place_built = '';
   $jap_dwt = '';
   $bale_capacity = '';
   $grain_capacity = '';
   $hatch_size = '';
   $hatch_type = '';
   $phil_dwt = '';
   $gross_tonnage = '';
   $net_tonnage = '';
   $main_engine = '';
   $main_engine_rating = '';
   $main_engine_actual_rating = '';
   $model_serial_no = '';
   $estimated_fuel_consumption = '';
   $bow_thrusters = '';
   $propeller = '';
   $call_sign = '';
   $imo_no = '';
   $monthly_amortization_no_of_months = '';
   $hm_agreed_value = '';
   $maiden_voyage = '';
   //var_dump($maiden_voyage);exit;
   $sound_value = '';
   $market_value = '';
   $status = '';
   $vessel_account_num = '';
   $bank_account_name = '';
   $date_acquired = '';
     
     if(isset($vessel)){
     		//var_dump($item[0]['id']);exit;
     		$id = $vessel[0]['id'];
   	$company = $vessel[0]['company'];
   	$photo_path = $vessel[0]['photo_path'];
   	//var_dump($photo_path);exit;
   	$name = $vessel[0]['name'];
   	$ex_name = $vessel[0]['ex_name'];
   	$price_sold = $vessel[0]['price_sold'];
   	$price_paid = $vessel[0]['price_paid'];
   	$length_loa = $vessel[0]['length_loa'];
   	$length_lr = $vessel[0]['length_lr'];
   	$length_lbp	 = $vessel[0]['length_lbp'];							
   	$breadth = $vessel[0]['breadth'];
   	$depth	 = $vessel[0]['depth'];								
   	$draft = $vessel[0]['draft'];
   	$year_built = $vessel[0]['year_built'];
   	$builder = $vessel[0]['builder'];
   	$place_built = $vessel[0]['place_built'];
   	$jap_dwt = $vessel[0]['jap_dwt'];
   	$bale_capacity = $vessel[0]['bale_capacity'];
   	$grain_capacity = $vessel[0]['grain_capacity'];
   	$hatch_size = $vessel[0]['hatch_size'];
   	$hatch_type = $vessel[0]['hatch_type'];
   	$phil_dwt = $vessel[0]['phil_dwt'];
   	$gross_tonnage = $vessel[0]['gross_tonnage'];
   	$net_tonnage = $vessel[0]['net_tonnage'];
   	$main_engine = $vessel[0]['main_engine'];
   	$main_engine_rating = $vessel[0]['main_engine_rating'];
   	$main_engine_actual_rating = $vessel[0]['main_engine_actual_rating'];
   	$model_serial_no = $vessel[0]['model_serial_no'];
   	$estimated_fuel_consumption = $vessel[0]['estimated_fuel_consumption'];
   	$bow_thrusters = $vessel[0]['bow_thrusters'];
   	$propeller = $vessel[0]['propeller'];
   	$call_sign = $vessel[0]['call_sign'];
   	$imo_no = $vessel[0]['imo_no'];
   	$monthly_amortization_no_of_months = $vessel[0]['monthly_amortization_no_of_months'];
   	$hm_agreed_value = $vessel[0]['hm_agreed_value'];
   	//$maiden_voyage = date("Y-m-d",strtotime($vessel[0]['date_acquired']));
   	$sound_value = $vessel[0]['sound_value'];
   	$market_value = $vessel[0]['market_value'];
   	$status = $vessel[0]['status'];
   	$vessel_account_num = $vessel[0]['vessel_account_num'];
   	$bank_account_name = $vessel[0]['bank_account_name'];
   	$date_acquired = date("Y-m-d",strtotime($vessel[0]['date_acquired']));
   	$maiden_voyage = date("Y-m-d",strtotime($vessel[0]['maiden_voyage']));
   	//var_dump($maiden_voyage);exit;
   	
   	//var_dump($date_acquired);exit;
     }
     ?>
<style>
   .modal-dialog{width: 400px !important;height: 300px;margin-left: 200px;}
   .modal-content{height: 1%;}
   .form-control.input-sm{height:26px !important;}
</style>
<form class="form-horizontal" role="form" action="<?php echo HTTP_PATH.'forms/add_vessel'; ?>" enctype='multipart/form-data' method="post">
   <?php echo $this->Mmm->createCSRF() ?>
   <input type="hidden" name="stat" value="1">
   <input type="hidden" name="id" value="<?php echo $id;  ?>">
   <!--- waybill activity --->
   <div style="width:250px; float:left; margin-left:0px;">
      <div class="container">
         <div class="panel panel-primary" style="font-size:12px; width:1153px;">
            <div class="panel-heading" role="tab" id="headingOne">
               <strong>Vessel Information&nbsp;</strong>
            </div>
            <div class="panel-body" role="tab">
               <div class="panel panel-info">
                  <div class="panel-heading">Label with asterisk (*) is required</div>
                  <div class="panel-body">
                     <ul class="list-inline">
                        <li class="list-inline-item">
                           <div class="form-group">
                              <label>Company: <i>(required)</i></label>
                              <div>
                                 <input type="text" class="form-control input-sm" name="company" value="<?php echo $company;  ?>" required />
                              </div>
                           </div>
                        </li>
                        <li class="list-inline-item">
                           <div class="form-group">
                              <label>Photo Path: </label>
                              <div>
                                 <?php if ($photo_path==''){
                                    echo 'Select Image';
                                    }else{ ?>
                                 <img src="<?php echo LINK.'assets/images/vessel_photos/'.$photo_path; ?>" width="40px" height="40px">
                                 <?php } ?>
                                 <input type="file" name="photo_path" value="<?php echo $photo_path;  ?>"/>
                              </div>
                           </div>
                        </li>
                        <li class="list-inline-item">
                           <div class="form-group" style="display: none;">
                              <label>Photo Path: </label>
                              <div>
                                 <img src="<?php echo LINK.'assets/images/vessel_photos/'.$photo_path; ?>" width="40px" height="40px">
                              </div>
                           </div>
                        </li>
                        <li class="list-inline-item">
                           <div class="form-group">
                              <label>Name: <i>(required)</i></label>
                              <div>
                                 <input type="text" class="form-control input-sm" name="name" value="<?php echo $name;  ?>" required />
                              </div>
                           </div>
                        </li>
                        <li class="list-inline-item">
                           <div class="form-group">
                              <label>Ex Name: <i>(required)</i></label>
                              <div>
                                 <input type="text" class="form-control input-sm" name="ex_name" value="<?php echo $ex_name;  ?>" required />
                              </div>
                           </div>
                        </li>
                        <li class="list-inline-item">
                           <div class="form-group">
                              <label>Price Sold: </label>
                              <div>
                                 <input type="text" class="form-control input-sm" name="price_sold" value="<?php echo $price_sold;  ?>">
                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
               </div>
               <div class="panel-group">
                  <div class="panel panel-default">
                     <div class="panel-heading">
                        <h4 class="panel-title">
                           <a data-toggle="collapse" href="#collapse1">Click here to fill up more data</a>
                        </h4>
                     </div>
                     <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                           <ul class="list-inline">
                              <li class="list-inline-item">
                                 <div class="form-group">
                                    <label>Length Loa: </label>
                                    <div>
                                       <input type="text" class="form-control input-sm" name="length_loa" value="<?php echo $length_loa;  ?>">
                                    </div>
                                 </div>
                              </li>
                              <li class="list-inline-item">
                                 <div class="form-group">
                                    <label>Length Lr: </label>
                                    <div>
                                       <input type="text" class="form-control input-sm" name="length_lr" value="<?php echo $length_lr;  ?>">
                                    </div>
                                 </div>
                              </li>
                              <li class="list-inline-item">
                                 <div class="form-group">
                                    <label>Length Lbp: </label>
                                    <div>
                                       <input type="text" class="form-control input-sm" name="length_lbp" value="<?php echo $length_lbp;  ?>">
                                    </div>
                                 </div>
                              </li>
                              <li class="list-inline-item">
                                 <div class="form-group">
                                    <label>Breadth: </label>
                                    <div>
                                       <input type="text" class="form-control input-sm" name="breadth" value="<?php echo $breadth;  ?>">
                                    </div>
                              </li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Depth: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="depth" value="<?php echo $depth;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Depth: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="depth" value="<?php echo $depth;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Draft: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="draft" value="<?php echo $draft;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Year Built: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="year_built" value="<?php echo $year_built;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Builder: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="builder" value="<?php echo $builder;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Place Built: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="place_built" value="<?php echo $place_built;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Jap Dwt: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="jap_dwt" value="<?php echo $jap_dwt;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Bale Capacity: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="bale_capacity" value="<?php echo $bale_capacity;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Grain Capacity: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="grain_capacity" value="<?php echo $grain_capacity;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Hatch Size: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="hatch_size" value="<?php echo $hatch_size;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Hatch Type: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="hatch_type" value="<?php echo $hatch_type;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Phil Dwt: </label>
                              <div>
                              <input type="number" class="form-control input-sm" name="phil_dwt" value="<?php echo $phil_dwt;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Gross Tonnage: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="gross_tonnage" value="<?php echo $gross_tonnage;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Net Tonnage: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="net_tonnage" value="<?php echo $net_tonnage;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Main Engine: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="main_engine" value="<?php echo $main_engine;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Main Engine Rating: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="main_engine_rating" value="<?php echo $main_engine_rating;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Engine Actual Rating: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="main_engine_actual_rating" value="<?php echo $main_engine_actual_rating;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Model Serial No: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="model_serial_no" value="<?php echo $model_serial_no;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Fuel Consumption: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="estimated_fuel_consumption" value="<?php echo $estimated_fuel_consumption;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Bow Thrusters: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="bow_thrusters" value="<?php echo $bow_thrusters;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"> <div class="form-group">
                              <label>Call Sign: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="call_sign" value="<?php echo $call_sign;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Imo No: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="imo_no" value="<?php echo $imo_no;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Monthly Amortization: </label>
                              <div>
                              <input type="number" class="form-control input-sm" name="monthly_amortization_no_of_months" value="<?php echo $monthly_amortization_no_of_months;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Hm Agreed Value: </label>
                              <div>
                              <input type="number" class="form-control input-sm" name="hm_agreed_value" value="<?php echo $hm_agreed_value;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"> <div class="form-group">
                              <label>Maiden Voyage: </label>
                              <div>
                              <input type="text" id="maiden_voyage" class="date-picker form-control input-sm" name="maiden_voyage" value="<?php echo $maiden_voyage;  ?>">
                              <script>$("#maiden_voyage").datepicker({changeYear: true,yearRange: "-100:+10"});</script>
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Sound Value: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="sound_value" value="<?php echo $sound_value;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Market Value: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="market_value" value="<?php echo $market_value;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"> <div class="form-group">
                              <label>Status: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="status" value="<?php echo $status;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Vessel Account No: </label>
                              <div>
                              <input type="number" class="form-control input-sm" name="vessel_account_num" value="<?php echo $vessel_account_num;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Bank Account Name: </label>
                              <div>
                              <input type="text" class="form-control input-sm" name="bank_account_name" value="<?php echo $bank_account_name;  ?>">
                              </div>
                              </div></li>
                              <li class="list-inline-item"><div class="form-group">
                              <label>Date Acquired: </label>
                              <div>
                              <input type="text" id="date_acquired" class="date-picker form-control input-sm" name="date_acquired" value="<?php echo $date_acquired;  ?>">
                              <script>$("#date_acquired").datepicker({changeYear: true,yearRange: "-100:+10"});</script>
                              </div>
                              </div></li>
                           </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-xs" data-dismiss="modal" onclick="myFunction()">Close</button>
                  <button type="submit" class="btn btn-primary btn-xs">Save</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</form>