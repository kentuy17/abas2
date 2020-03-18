<?php

    //initialize display of forms
    $shipping_display = 'none';
    $lighterage_display = 'none';
    $timecharter_display = 'none';
    $trucking_display = 'none';
    $handling_display = 'none';
    $towing_display = 'none';
    $storage_display = 'none';
    $rental_display = 'none';
    $remarks_display = 'none';

    $contract_id = "";
    $company_id = "";
    $date_needed = "";
    $type = "";
    $vessel_options = "<option value=''>Select</option>";
    $truck_options = "<option value=''>Select</option>";
    $vessel_id = "";
    $source_vessel="";
    $craft_towed = "";
    $cargo_description = "";
    $qty = "";
    $unit = "";
    $loading_port = "";
    $unloading_port = "";
    $source_vessel_location = "";
    $discharge_location = "";
    $from_location = "";
    $to_location = "";
    $start_location = "";
    $start_datetime = "";
    $end_location = "";
    $end_datetime = "";
    $destination = "";
    $warehouse = "";
    $loading_point = "";
    $unloading_point = "";
    $num_of_moves = "";
    $remarks = "";

    $drop_off_points = "";
    $drop_off_point1 = "";
    $drop_off_point2 = "";
    $drop_off_point3 = "";
    $drop_off_point4 = "";

    $drop_off_quantity1 = "";
    $drop_off_quantity2 = "";
    $drop_off_quantity3 = "";
    $drop_off_quantity4 = "";
    $storage_location="";
    $start_date="";
    $end_date="";

    if(!isset($SO)){

        $action = HTTP_PATH.'operation/service_order/insert';
        $title = "Add Service Order";

    }else{
        
        $action = HTTP_PATH.'operation/service_order/update/'.$SO->id;
        $title = "Edit Service Order";

        $contract_id = $SO->service_contract_id;
        $company_id = $SO->company_id;
        $date_needed = $SO->date_needed;
        $type = $SO->type;
         $remarks = $SO->remarks;

        if($SO->type=="Shipping"){

            $shipping_display = 'block';
            $remarks_display = 'block';

            $vessel_id = $SO_detail->vessel_id;
            $vessel_options = "<option value=''>Select</option>";
            foreach($vessels as $vessel){
                if($vessel_id==$vessel->id){
                    $vessel_options .= "<option value='".$vessel->id."' selected>".$vessel->name."</option>";
                }else{
                    $vessel_options .= "<option value='".$vessel->id."'>".$vessel->name."</option>";
                }
            }
             $vessel_options .= "<option value='0'>3rd-Party Vessel</option>";
            
            $cargo_description = $SO_detail->cargo_description;
            $qty = $SO_detail->quantity;
            $unit = $SO_detail->unit;
            $loading_port = $SO_detail->from_location;
            $unloading_port = $SO_detail->to_location;

        }elseif($SO->type=="Lighterage"){

            $lighterage_display = 'block';
            $remarks_display = 'block';

            $vessel_id = $SO_detail->vessel_id;
            $source_vessel = $SO_detail->source_vessel;
            $vessel_options = "<option value=''>Select</option>";
            foreach($vessels as $vessel){
                if($vessel_id==$vessel->id){
                    $vessel_options .= "<option value='".$vessel->id."' selected>".$vessel->name."</option>";
                }else{
                    $vessel_options .= "<option value='".$vessel->id."'>".$vessel->name."</option>";
                }
            }
             $vessel_options .= "<option value='0'>3rd-Party Vessel</option>";
            
            $cargo_description = $SO_detail->cargo_description;
            $qty = $SO_detail->quantity;
            $unit = $SO_detail->unit;
            $source_vessel_location = $SO_detail->vessel_location;
            $discharge_location = $SO_detail->discharge_location;

        }elseif($SO->type=="Time Charter"){
            $timecharter_display = 'block';
            $remarks_display = 'block';

            $vessel_id = $SO_detail->vessel_id;
            $vessel_options = "<option value=''>Select</option>";
            foreach($vessels as $vessel){
                if($vessel_id==$vessel->id){
                    $vessel_options .= "<option value='".$vessel->id."' selected>".$vessel->name."</option>";
                }else{
                    $vessel_options .= "<option value='".$vessel->id."'>".$vessel->name."</option>";
                }
            }
             $vessel_options .= "<option value='0'>3rd-Party Vessel</option>";
            
            $cargo_description = $SO_detail->cargo_description;
            $qty = $SO_detail->quantity;
            $unit = $SO_detail->unit;
            $start_location = $SO_detail->start_location;
            $start_datetime = $SO_detail->start_datetime;
            $end_location = $SO_detail->end_location;
            $end_datetime = $SO_detail->end_datetime;

        }elseif($SO->type=="Towing"){
            $towing_display = 'block';
            $remarks_display = 'block';

            $vessel_id = $SO_detail->vessel_id;
            $craft_towed = $SO_detail->craft_towed;
            $vessel_options = "<option value=''>Select</option>";
            foreach($vessels as $vessel){
                if($vessel_id==$vessel->id){
                    $vessel_options .= "<option value='".$vessel->id."' selected>".$vessel->name."</option>";
                }else{
                    $vessel_options .= "<option value='".$vessel->id."'>".$vessel->name."</option>";
                }
            }
             $vessel_options .= "<option value='0'>3rd-Party Vessel</option>";

            
            $cargo_description = $SO_detail->cargo_description;
            $qty = $SO_detail->quantity;
            $unit = $SO_detail->unit;
            $from_location = $SO_detail->from_location;
            $to_location = $SO_detail->to_location;

        }elseif($SO->type=="Trucking"){

            $trucking_display = 'block';
            $remarks_display = 'block';
            
            $cargo_description = $SO_detail->cargo_description;
            $qty = $SO_detail->quantity;
            $unit = $SO_detail->unit;
            $loading_point = $SO_detail->from_location;
            /*$drop_off_points = explode(" | ",$SO_detail->to_location);
            $drop_off_point1 = $drop_off_points[0];
            $drop_off_point2 = $drop_off_points[1];
            $drop_off_point3 = $drop_off_points[2];
            $drop_off_point4 = $drop_off_points[3];*/

             $drop_off_point1 = $SO_detail->drop_off_point_1;
             $drop_off_point2 = $SO_detail->drop_off_point_2;
             $drop_off_point3 = $SO_detail->drop_off_point_3;
             $drop_off_point4 = $SO_detail->drop_off_point_4;

             $drop_off_quantity1 = $SO_detail->drop_off_quantity_1;
             $drop_off_quantity2 = $SO_detail->drop_off_quantity_2;
             $drop_off_quantity3 = $SO_detail->drop_off_quantity_3;
             $drop_off_quantity4 = $SO_detail->drop_off_quantity_4;

        }elseif($SO->type=="Handling"){
            $handling_display = 'block';
            $remarks_display = 'block';

            $cargo_description = $SO_detail->cargo_description;
            $qty = $SO_detail->quantity;
            $unit = $SO_detail->unit;

        }elseif($SO->type=="Storage"){
            $storage_display = 'block';
            $remarks_display = 'block';

            $cargo_description = $SO_detail->cargo_description;
            $storage_location = $SO_detail->storage_location;
            $qty = $SO_detail->quantity;
            $unit = $SO_detail->unit;
            $start_date = $SO_detail->start_date;
            $end_date = $SO_detail->end_date;

        }elseif($SO->type=="Equipment Rental"){
            $rental_display = 'block';
            $remarks_display = 'block';

            $equipment_name = $SO_detail->equipment_name;
            $description = $SO_detail->description;
            $qty = $SO_detail->quantity;
            $unit = $SO_detail->unit;
            $start_date = $SO_detail->start_date;
            $end_date = $SO_detail->end_date;
            $from_location = $SO_detail->from_location;
            $to_location = $SO_detail->to_location;

        }

    }

?>
	
                <div class="panel panel-primary" style="font-size:12px">
                    <div class='panel-heading'>
                        <div class='panel-title'>
                            <text><?php echo $title;?></text>
                            <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
                        </div>
                    </div>
                </div>
                
                <form role="form" id="form_ServiceOrder" name="form_ServiceOrder" action='<?php echo $action; ?>' method='POST' enctype='multipart/form-data'>
                    <?php echo $this->Mmm->createCSRF(); ?>

                    <div class="panel-body" style="height:550px" name="ServiceOrder" id="ServiceOrder">
                            <!--Left -->
                            <div class='col-md-2 col-sm-2 col-xs-12' style="width:32%; float:left;">

                                <label>Contract*</label>
                            	<div class="input-group">

                                    <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                                    <input type="text" id="searchInput" name="searchInput" class="form-control" placeholder="Search"  />
                                </div>
                      
                                <div style="background:#FFFFFF; overflow:scroll; height:440px">
                                    <table id="contract_list" class="table table-striped table-hover table-responsive table-bordered" data-search="true" style="font-size:10px;">
                                        <thead>
                                        	<tr>
                                            	<th></th>
                                                <th>Ref No.</th>
                                                <th>Company</th>
                                                <th>Client</th>
                                                <th>Service</th>
                                            </tr>
                                        </thead>
                                    	<tbody>
                                        	<?php 
        										$ctr=0;
        										foreach($contracts as $contract){
        										  
        										  $client = $this->Abas->getClient($contract['client_id']);
                                                  $company = $this->Abas->getCompany($contract['company_id']);

        									?>    
                                                <tr>
                                                	<td><input type="radio" value="<?php echo $contract['id'] ?>" name="contract_radio" id="contract_radio<?php echo $ctr ?>" <?php if($contract['id']==$contract_id){ echo 'checked="checked"';}?> <?php //if(isset($SO)){ echo "disabled";}?>/></td>
                                                    <td><?php echo $contract['reference_no'] ?></td>
                                                    <td><?php echo $company->name ?></td>
                                                    <td><?php echo $client['company'] ?></td>        
                                                    <td><?php echo $contract['type'] ?></td>
                                                </tr>
                                                  
                                        	<?php $ctr++; } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="hidden">
                                    <input type="text" id="company_id" name="company_id" value='<?php echo $company_id;?>' required/>
                                    <input type="text" id="contract_id" name="contract_id" value='<?php echo $contract_id;?>' required/>
                                </div>

                            </div> 
                        
                            <!--Right -->
                            <div style="width:68%; float:right">
                                 
                                 <div class='col-md-4 col-sm-4 col-xs-12' style='margin-left:35px'>
                                    <label>Service Order Date*</label>
                                     <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card" style="font-size:16"></i></span>
                                        <input type="date" name="date_needed" id="date_needed" class="form-control" placeholder="Date Needed" value='<?php echo $date_needed;?>' required>
                                    </div>
                                </div>



                                <div class='col-md-6 col-sm-6 col-xs-12' style='margin-left:35px'>
                                    <label>Service Order Type*</label>
                                    <div class="input-group">
                                   		<span class="input-group-addon"><i class="glyphicon glyphicon-paste" style="font-size:16"></i></span>
                                        <select class="form-control" name="service_type" id="service_type" required onchange="javascipt: showForm(this.value)" <?php if(!isset($SO)){ echo 'disabled';}?>>
                                        	<option value="">Select</option>
                                            <option <?php if($type=='Shipping'){ echo 'selected';}?> value="Shipping">Shipping</option>
                                            <option <?php if($type=='Lighterage'){ echo 'selected';}?> value="Lighterage">Lighterage</option>
                                            <option <?php if($type=='Time Charter'){ echo 'selected';}?> value="Time Charter">Time Charter</option>
                                            <option <?php if($type=='Towing'){ echo 'selected';}?> value="Towing">Towing</option>
                                            <option <?php if($type=='Trucking'){ echo 'selected';}?> value="Trucking">Trucking</option>
                                            <option <?php if($type=='Handling'){ echo 'selected';}?> value="Handling">Handling</option>
                                            <option <?php if($type=='Storage'){ echo 'selected';}?> value="Storage">Storage</option>
                                             <option <?php if($type=='Equipment Rental'){ echo 'selected';}?> value="Equipment Rental">Equipment Rental</option>
                                           	<!--<?php //foreach($services as $s){ ?>
                                                <option value="<?php //echo $s->id ?>"><?php //echo $s->type ?></option>
                                            <?php //} ?>-->
                                        </select>
                                     </div>
                                </div>               

                               <!--- SHIPPING --> 
                               <div id="shipping" style="background:#F4F4F4;height:380px; display:<?php echo $shipping_display; ?>;" class='col-md-12 col-sm-12 col-xs-12'>
                               		<br>
                                    <div style="width:90%; margin-left:20px">
            							
                                        	<div class='col-md-12 col-sm-12 col-xs-12'>
                                                <label>Vessel*</label>
                                                <select class="form-control input-sm" name="vessel1" id="vessel1" required>
                                                    <?php echo $vessel_options;?>
                                                </select>
                                            </div>
                                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                                <label>Cargo Description*</label>
                                                <input class="form-control input-sm" type="text" name="cargo_description1" id="cargo_description1" value="<?php echo $cargo_description;?>" required/>
                                            </div>
                                            <div class="form-group">
                                                <div class='col-md-6 col-sm-12 col-xs-12'>
                                                    <label>Total Quantity/Weight*</label>
                                                    <input class="form-control input-sm" type="number" name="qty1" id="qty1" value="<?php echo $qty;?>" required/>
                                                </div>
                                                <div class='col-md-6 col-sm-12 col-xs-12'>
                                                    <label>Unit*</label>
                                                    <select class="form-control input-sm" name="unit1" id="unit1" required>
                                                        <option></option>
                                                        <option <?php if($unit=='bags'){ echo 'selected';}?> value="bags">bags</option>
                                                        <option <?php if($unit=='metric tons'){ echo 'selected';}?> value="metric tons">metric tons</option>
                                                        <option <?php if($unit=='kilograms'){ echo 'selected';}?> value="kilograms">kilograms</option>
                                                    </select>
                                                </div>                                       
                                            </div>                                	
                                       		<div class="form-group">
                                                <div class='col-md-6 col-sm-12 col-xs-12'>
                                                    <label>Loading Port*</label>
                                                    <input class="form-control input-sm" type="text" name="loading_port1" id="loading_port1" value="<?php echo $loading_port;?>" required/>
                                                </div>                                   
                                                <div class='col-md-6 col-sm-12 col-xs-12'>
                                                    <label>Unloading Port*</label>
                                                    <input class="form-control input-sm" type="text" name="unloading_port1" id="unloading_port1" value="<?php echo $unloading_port;?>" required/>
                                                </div>                      
                                            </div>  

                                    </div>
                                    <br>
                               </div>
                               <!--- END SHIPPING --> 

                               
                               <div id="timecharter" style="background:#F4F4F4;height:430px;display:<?php echo $timecharter_display; ?>" class='col-md-12 col-sm-12 col-xs-12'>
                                     <!---Time Charter Form -->
                                       
                                        <br>

                                        <div style="width:90%; margin-left:20px">
                                       
                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Vessel*</label>
                                                    <select class="form-control input-sm" name="vessel2" id="vessel2" required>
                                                        <?php echo $vessel_options;?>
                                                    </select>
                                                </div>
                                                    
                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Cargo Description*</label>
                                                    <input class="form-control input-sm" type="text" name="cargo_description2" id="cargo_description2" value="<?php echo $cargo_description;?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Duration*</label>
                                                        <input class="form-control input-sm" type="number" name="qty2" id="qty2" value="<?php echo $qty;?>" required/>
                                                    </div>
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Unit*</label>
                                                         <select class="form-control input-sm" name="unit2" id="unit2" required>
                                                            <option>Select</option>
                                                            <option <?php if($unit=='day(s)'){ echo 'selected';}?> value="days(s)">day(s)</option>
                                                            <option <?php if($unit=='months(s)'){ echo 'selected';}?> value="months(s)">month(s)</option>
                                                            <option <?php if($unit=='hour(s)'){ echo 'selected';}?> value="hour(s)">hour(s)</option>
                                                        </select>
                                                    </div>                                       
                                                </div>                                  
                                                <div class="form-group">
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Start Location*</label>
                                                        <input class="form-control input-sm" type="text" name="start_location2" id="start_location2" value="<?php echo $start_location;?>" required/>
                                                    </div>
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Start Date-Time*</label>
                                                        <input class="form-control input-sm" type="datetime-local" name="start_datetime2" id="start_datetime2" value="<?php  echo $start_datetime;?>" required/>
                                                    </div>
                                                </div>
                                                <div class="form-group">                                 
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>End Location*</label>
                                                        <input class="form-control input-sm" type="text" name="end_location2" id="end_location2" value="<?php echo $end_location?>" required/>
                                                    </div>   
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>End Date-Time*</label>
                                                        <input class="form-control input-sm" type="datetime-local" name="end_datetime2" id="end_datetime2" value="<?php  echo $end_datetime;?>" required/>
                                                    </div>                  
                                                </div>  
                     
                                        </div>
                                        <!--- end Time Charter Form -->
                               </div>                               
                               
                               <div id="lighterage" style="background:#F4F4F4;height:430px;display:<?php echo $lighterage_display; ?>" class='col-md-12 col-sm-12 col-xs-12'>
                                    <br>
                                    <!---Lighterage Form -->
                                        <div style="width:90%; margin-left:20px">

                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Servicing Vessel*</label>
                                                    <select class="form-control input-sm" name="vessel3" id="vessel3" required>
                                                        <?php echo $vessel_options;?>
                                                    </select>
                                                </div>

                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Source/Vessel*</label>
                                                     <input class="form-control input-sm" type="text" name="source_vessel3" id="source_vessel3" value="<?php echo $source_vessel;?>" required/>
                                                </div>
                                                    
                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Cargo Description*</label>
                                                    <input class="form-control input-sm" type="text" name="cargo_description3" id="cargo_description3" value="<?php echo $cargo_description;?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Total Quantity/Weight*</label>
                                                        <input class="form-control input-sm" type="number" name="qty3" id="qty3" value="<?php echo $qty;?>" required/>
                                                    </div>
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Unit*</label>
                                                        <select class="form-control input-sm" name="unit3" id="unit3" required>
                                                            <option>Select</option>
                                                            <option <?php if($unit=='bags'){ echo 'selected';}?> value="bags">bags</option>
                                                            <option <?php if($unit=='metric tons'){ echo 'selected';}?> value="metric tons">metric tons</option>
                                                            <option <?php if($unit=='kilograms'){ echo 'selected';}?> value="kilograms">kilograms</option>
                                                        </select>
                                                    </div>                                       
                                                </div>

                                                 <div class="form-group">                                 
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Source Location*</label>
                                                        <input class="form-control input-sm" type="text" name="source_location3" id="source_location3" value="<?php  echo $source_vessel_location;?>" required/>
                                                    </div>   
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Discharge Location*</label>
                                                        <input class="form-control input-sm" type="text" name="discharge_location3" id="discharge_location3" value="<?php  echo $discharge_location?>" required/>
                                                    </div>                  
                                                </div>

                                         </div>  
                                </div> 
                                <!---end Lighterage Form -->

                                <div id="towing" style="background:#F4F4F4;height:430px;display:<?php echo $towing_display; ?>" class='col-md-12 col-sm-12 col-xs-12'>
                                      <!---Towing Form -->
                                       
                                        <br>

                                        <div style="width:90%; margin-left:20px">
                                       
                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Servicing Vessel*</label>
                                                    <select class="form-control input-sm" name="vessel4" id="vessel4" required>
                                                        <?php echo $vessel_options?>
                                                    </select>
                                                </div>

                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Craft Towed*</label>
                                                    <input class="form-control input-sm" type="text" name="craft_towed4" id="craft_towed4" value="<?php echo $craft_towed;?>" required/>
                                                </div>
                                                    
                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Cargo Description*</label>
                                                    <input class="form-control input-sm" type="text" name="cargo_description4" id="cargo_description4" value="<?php echo $cargo_description;?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Duration*</label>
                                                        <input class="form-control input-sm" type="number" name="qty4" id="qty4" value="<?php echo $qty;?>" required/>
                                                    </div>
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Unit*</label>
                                                         <select class="form-control input-sm" name="unit4" id="unit4" required>
                                                            <option>Select</option>
                                                            <option <?php if($unit=='day(s)'){ echo 'selected';}?> value="days(s)">day(s)</option>
                                                            <option <?php if($unit=='months(s)'){ echo 'selected';}?> value="months(s)">month(s)</option>
                                                            <option <?php if($unit=='hour(s)'){ echo 'selected';}?> value="hour(s)">hour(s)</option>
                                                        </select>
                                                    </div>                                       
                                                </div>     

                                                <div class="form-group">
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>From Location*</label>
                                                        <input class="form-control input-sm" type="text" name="from_location4" id="from_location4" value="<?php  echo $from_location;?>" required/>
                                                    </div>                                   
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>To Location*</label>
                                                        <input class="form-control input-sm" type="text" name="to_location4" id="to_location4" value="<?php echo $to_location;?>" required/>
                                                    </div>                      
                                                </div>   
                                                                      
                                        </div>
                                        <!--- end Towing Form -->
                               </div>

                               <div id="trucking" style="background:#F4F4F4;height:475px;display:<?php echo $trucking_display; ?>" class='col-md-12 col-sm-12 col-xs-12'>
                               		<!---Trucking Form -->
                                       
                                        <br>

                                        <div style="width:90%; margin-left:20px">
                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Cargo Description*</label>
                                                    <input class="form-control input-sm" type="text" name="cargo_description5" id="cargo_description5" value="<?php echo $cargo_description;?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <!--<div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Total Quantity/Weight/Duration*</label>
                                                        <input class="form-control input-sm" type="number" name="qty5" id="qty5" value="<?php echo $qty;?>" required/>
                                                    </div>-->
                                                                                         
                                                </div>  

                                                <div class="form-group">
                                                   
                                                    <div class='col-md-6 col-sm-6 col-xs-12'>
                                                        <label>Loading Point*</label>
                                                        <input class="form-control input-sm" type="text" name="from_location5" id="from_location5" value="<?php echo $loading_point;?>" required/>
                                                    </div>    

                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Unit*</label>
                                                         <select class="form-control input-sm" name="unit5" id="unit5" required>
                                                            <option>Select</option>
                                                            <option <?php if($unit=='bags'){ echo 'selected';}?> value="bags">bags</option>
                                                            <option <?php if($unit=='kilograms'){ echo 'selected';}?> value="kilograms">kilograms</option>
                                                             <option <?php if($unit=='day(s)'){ echo 'selected';}?> value="days(s)">day(s)</option>
                                                            <option <?php if($unit=='months(s)'){ echo 'selected';}?> value="months(s)">month(s)</option>
                                                            <option <?php if($unit=='hour(s)'){ echo 'selected';}?> value="hour(s)">hour(s)</option>
                                                            <option <?php if($unit=='trip(s)'){ echo 'selected';}?> value="trip(s)">trip(s)</option>
                                                        </select>
                                                    </div>  

                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Drop-off Point 1*</label>
                                                        <input class="form-control input-sm" type="text" name="to_location5" id="to_location5" value="<?php echo $drop_off_point1;?>" required/>
                                                    </div>
                                                     <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Quantity*</label>
                                                        <input class="form-control input-sm" type="number" name="drop_qty1" id="drop_qty1" value="<?php echo $drop_off_quantity1;?>" required/>
                                                    </div>
                                                                                    
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Drop-off Point 2</label>
                                                        <input class="form-control input-sm" type="text" name="to_location6" id="to_location6" value="<?php echo $drop_off_point2;?>" placeholder=''/>
                                                    </div> 
                                                     <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Quantity</label>
                                                        <input class="form-control input-sm" type="number" name="drop_qty2" id="drop_qty2" value="<?php echo $drop_off_quantity2;?>"/>
                                                    </div>
                                                                                    
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Drop-off Point 3</label>
                                                        <input class="form-control input-sm" type="text" name="to_location7" id="to_location7" value="<?php echo $drop_off_point3;?>" placeholder=''/>
                                                    </div>
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Quantity</label>
                                                        <input class="form-control input-sm" type="number" name="drop_qty3" id="drop_qty3" value="<?php echo $drop_off_quantity3;?>"/>
                                                    </div>
                                                                                   
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Drop-off Point 4</label>
                                                        <input class="form-control input-sm" type="text" name="to_location8" id="to_location8" value="<?php echo $drop_off_point4;?>" placeholder=''/>
                                                    </div>
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Quantity</label>
                                                        <input class="form-control input-sm" type="number" name="drop_qty4" id="drop_qty4" value="<?php echo $drop_off_quantity4;?>"/>
                                                    </div>                  
                                                </div>            
                                        </div>
                                        <!--- end Truckung Form -->
                               </div>
                               
                               <div id="handling" style="background:#F4F4F4;height:265px;display:<?php echo $handling_display; ?>" class='col-md-12 col-sm-12 col-xs-12'>
                               		<!--Handling Form -->
                                       
                                        <br>

                                        <div style="width:90%; margin-left:20px">
                                                    
                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Cargo Description*</label>
                                                    <input class="form-control input-sm" type="text" name="cargo_description6" id="cargo_description6" value="<?php echo $cargo_description; ?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Total Quantity/Weight*</label>
                                                        <input class="form-control input-sm" type="number" name="qty6" id="qty6" value="<?php echo $qty;?>" required/>
                                                    </div>
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Unit*</label>
                                                         <select class="form-control input-sm" name="unit6" id="unit6" required>
                                                            <option>Select</option>
                                                            <option <?php if($unit=='bags'){ echo 'selected';}?> value="bags">bags</option>
                                                            <option <?php if($unit=='metric tons'){ echo 'selected';}?> value="metric tons">metric tons</option>
                                                            <option <?php if($unit=='kilograms'){ echo 'selected';}?> value="kilograms">kilograms</option>
                                                        </select>
                                                    </div>                                       
                                                </div>            
                               
                                        </div>
                                        <!--- end Handling Form -->
                               </div>

                               <div id="storage" style="background:#F4F4F4;height:370px;display:<?php echo $storage_display; ?>" class='col-md-12 col-sm-12 col-xs-12'>
                                    <!---Storage Form -->
                                       
                                        <br>

                                        <div style="width:90%; margin-left:20px">
                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Cargo Description*</label>
                                                    <input class="form-control input-sm" type="text" name="cargo_description7" id="cargo_description7" value="<?php echo $cargo_description;?>" required/>
                                                </div>
                                                 <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Storage Location*</label>
                                                    <input class="form-control input-sm" type="text" name="storage_location7" id="storage_location7" value="<?php echo $storage_location;?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Total Quantity/Weight*</label>
                                                        <input class="form-control input-sm" type="number" name="qty7" id="qty7" value="<?php echo $qty;?>" required/>
                                                    </div>
                                                     
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Unit*</label>
                                                         <select class="form-control input-sm" name="unit7" id="unit7" required>
                                                            <option>Select</option>
                                                            <option <?php if($unit=='bags'){ echo 'selected';}?> value="bags">bags</option>
                                                            <option <?php if($unit=='pieces'){ echo 'selected';}?> value="pieces">pieces</option>
                                                            <option <?php if($unit=='boxes'){ echo 'selected';}?> value="boxes">boxes</option>
                                                            <option <?php if($unit=='crates'){ echo 'selected';}?> value="crates">crates</option>
                                                            <option <?php if($unit=='kilograms'){ echo 'selected';}?> value="kilograms">kilograms</option>
                                                            <option <?php if($unit=='metric tons'){ echo 'selected';}?> value="metric tons">metric tons</option>
                                                        </select>
                                                    </div>     
                                                </div>     
                                                 <div class="form-group">                    
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Start Date*</label>
                                                        <input class="form-control input-sm" type="date" name="start_date7" id="start_date7" value="<?php  echo $start_date;?>" required/>
                                                    </div>

                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>End Date*</label>
                                                        <input class="form-control input-sm" type="date" name="end_date7" id="end_date7" value="<?php  echo $end_date;?>" required/>
                                                    </div>                  
                                                </div>  
                                        </div>
                                        <!--- end Storage Form -->
                               </div>

                               <div id="rental" style="background:#F4F4F4;height:420px;display:<?php echo $rental_display; ?>" class='col-md-12 col-sm-12 col-xs-12'>
                                    <!---Equipment Rental Form -->
                                       
                                        <br>

                                        <div style="width:90%; margin-left:20px">
                                                 <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Equipment Name/Type*</label>
                                                    <input class="form-control input-sm" type="text" name="equipment_name8" id="equipment_name8" value="<?php echo $equipment_name;?>" required/>
                                                </div>
                                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                                    <label>Description*</label>
                                                    <input class="form-control input-sm" type="text" name="description8" id="description8" value="<?php echo $description;?>" required/>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Quantity*</label>
                                                        <input class="form-control input-sm" type="number" name="qty8" id="qty8" value="<?php echo $qty;?>" required/>
                                                    </div>
                                                     
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Unit*</label>
                                                         <select class="form-control input-sm" name="unit8" id="unit8" required>
                                                            <option>Select</option>
                                                            <option <?php if($unit=='metric tons'){ echo 'selected';}?> value="metric tons">metric tons</option>
                                                            <option <?php if($unit=='days'){ echo 'selected';}?> value="days">days</option>
                                                            <option <?php if($unit=='hours'){ echo 'selected';}?> value="hours">hours</option>
                                                        </select>
                                                    </div>     
                                                </div>     
                                                 <div class="form-group">                    
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Rental Start Date/Time*</label>
                                                        <input class="form-control input-sm" type="datetime-local" name="start_date8" id="start_date8" value="<?php  echo $start_date;?>" required/>
                                                    </div>

                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>Rental End Date/Time*</label>
                                                        <input class="form-control input-sm" type="datetime-local" name="end_date8" id="end_date8" value="<?php  echo $end_date;?>" required/>
                                                    </div>                  
                                                </div> 
                                                <div class="form-group">                    
                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>From (If applicable)</label>
                                                        <input class="form-control input-sm" type="input" name="from_location8" id="from_location8" value="<?php  echo $from_location;?>"/>
                                                    </div>

                                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                                        <label>To (If applicable)</label>
                                                        <input class="form-control input-sm" type="input" name="to_location8" id="to_location8" value="<?php  echo $to_location;?>"/>
                                                    </div>                  
                                                </div>
                                        </div>
                                        <!--- end Storage Form -->
                               </div>
                                
                                 <div id="remarks" style="background:#F4F4F4;display:<?php echo $remarks_display; ?>" class='col-md-12 col-sm-12 col-xs-12'>

                                     <div style="width:90%; margin-left:20px;margin-top:-140px">
                                         <div class="form-group">
                                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                                <label>Remarks</label>
                                                <textarea name='remarks' id='remarks' rows="2" style="width:500px" required><?php echo $remarks;?></textarea>
                                            </div>                                                  
                                        </div>                         
                                           
                                        <div class='col-md-12 col-sm-12 col-xs-12'>
                                            <input class="btn btn-danger btn-sm pull-right"  value="Discard"  data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:10px">
                                            <input class="btn btn-success btn-sm pull-right" type="button"  value="Save" onclick="javascript: checkForm()" id="submitbtn" style="width:100px; margin-left:30px; margin-top:10px">                                               
                                        </div>
                                    </div>
                                </div>
   
                            </div>          
                    </div>
                </form>
       
    
 
 <!-- Modal HTML -->
<script>

$(document).ready(function(){
  $("#searchInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#contract_list tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

	
	function showForm(){
		
		var v = $( "#service_type option:selected" ).text();
		
		if (v.indexOf('Shipping') > -1) {
		  	$('#shipping').show();
            $('#remarks').show();
			$('#lighterage').hide();
			$('#timecharter').hide();
            $('#towing').hide();
			$('#trucking').hide();
			$('#handling').hide();
            $('#storage').hide(); 
            $('#rental').hide();   
		}else if(v.indexOf('Lighterage') > -1){
			$('#lighterage').show();
            $('#remarks').show();
			$('#shipping').hide();
			$('#timecharter').hide();
             $('#towing').hide();
			$('#trucking').hide();
			$('#handling').hide();
            $('#storage').hide();  
            $('#rental').hide(); 
		}else if(v.indexOf('Time Charter') > -1){
			$('#timecharter').show();
            $('#remarks').show();
			$('#lighterage').hide();
			$('#shipping').hide();
            $('#towing').hide();
			$('#trucking').hide();
			$('#handling').hide();
            $('#storage').hide();
            $('#rental').hide(); 
        }else if(v.indexOf('Towing') > -1){
            $('#towing').show();
            $('#remarks').show();
            $('#timecharter').hide();
            $('#lighterage').hide();
            $('#shipping').hide();
            $('#trucking').hide();
            $('#handling').hide();
            $('#storage').hide(); 
            $('#rental').hide();  
		}else if(v.indexOf('Trucking') > -1){
			$('#trucking').show();
            $('#remarks').show();
			$('#timecharter').hide();
			$('#lighterage').hide();
            $('#towing').hide();
			$('#shipping').hide();
			$('#handling').hide();
            $('#storage').hide();
            $('#rental').hide(); 
		}else if(v.indexOf('Handling') > -1){
			$('#handling').show();
            $('#remarks').show();
			$('#trucking').hide();
			$('#timecharter').hide();
            $('#towing').hide();
			$('#lighterage').hide();
			$('#shipping').hide();
            $('#storage').hide();
            $('#rental').hide();  
		}else if(v.indexOf('Storage') > -1){
            $('#handling').hide();
            $('#remarks').show();
            $('#trucking').hide();
            $('#timecharter').hide();
            $('#towing').hide();
            $('#lighterage').hide();
            $('#shipping').hide();
            $('#storage').show();
            $('#rental').hide(); 
        }else if(v.indexOf('Equipment Rental') > -1){
            $('#handling').hide();
            $('#remarks').show();
            $('#trucking').hide();
            $('#timecharter').hide();
            $('#towing').hide();
            $('#lighterage').hide();
            $('#shipping').hide();
            $('#storage').hide();
            $('#rental').show(); 
        }
		

	}


    $('input[name="contract_radio"]').click(function(){

        var selected_contract_id = $('input[name="contract_radio"]:checked').val();

        $.ajax({
             type:"POST",
             url:"<?php echo HTTP_PATH;?>/operation/get_contract_details/"+selected_contract_id,
             success:function(data){

                var contract = $.parseJSON(data); 
                $('#contract_id').val(contract.id); 
                $('#company_id').val(contract.company_id); 

             }

          });

        $('input[name="contract_radio"]').prop("disabled",true);
        $('#service_type').prop("disabled",false);

    });

    $('#service_type').change(function(){

        var service_type = $('#service_type').val();
        var vessel_field = "";

        if(service_type=='Shipping'){
            vessel_field = '#vessel1';
        }else if(service_type=='Time Charter'){
            vessel_field = '#vessel2';
        }else if(service_type=='Lighterage'){
            vessel_field = '#vessel3';
        }else if(service_type=='Towing'){
            vessel_field = '#vessel4';
        }

        if(service_type=='Trucking'){
            $.ajax({
                 type:"POST",
                 url:"<?php echo HTTP_PATH;?>/operation/get_trucks_by_company/"+$('#company_id').val(),
                 success:function(data){

                    var truck_plate_number = $.parseJSON(data); 

                    $('#truck5').find('option').remove().end().append('<option value="">Select</option>').val('');

                    for(var i = 0; i < truck_plate_number.length; i++){
                        var truck = truck_plate_number[i];
                        var option = $('<option />');
                        option.attr('value', truck.id).text(truck.plate_number + " (" + truck.make + "-" + truck.model + " " + truck.type +  ")");
                        $('#truck5').append(option);
                    }

                 }

              });
        }else{
            $.ajax({
             type:"POST",
             url:"<?php echo HTTP_PATH;?>/operation/get_vessels_by_company/"+$('#company_id').val(),
             success:function(data){

                var vessel_names = $.parseJSON(data); 

                $(vessel_field).find('option').remove().end().append('<option value="">Select</option>').val('');

                for(var i = 0; i < vessel_names.length; i++){
                    var vessel = vessel_names[i];
                    var option = $('<option />');
                    option.attr('value', vessel.id).text(vessel.name);
                    $(vessel_field).append(option);
                }

                var option3rdParty = $('<option />');
                option3rdParty.attr('value', 0).text('3rd-Party Vessel');
                $(vessel_field).append(option3rdParty);

             }

          });
        }
 
    });


    function checkForm() {

        var all_selects = $("#ServiceOrder").find('select').filter('[required]:visible');
        var all_inputs = $("#ServiceOrder").find('input').filter('[required]:visible');

        var select_flag=0;
        for(var i = 0; i < all_selects.length; i++){      
            if (all_selects[i].value==""){
                select_flag=1;
            } 
        }
        var input_flag=0;
        for(var x = 0; x < all_inputs.length; x++){
            if (all_inputs[x].value==""){
                if(all_inputs[x].name!="searchInput"){
                    input_flag=1;
                }
            }
        }
        
        if(select_flag==1 || input_flag==1) {
            toastr["error"]("Please fill-out all required* fields!","ABAS Says:");
            return false;
        }
        else {

            $('body').addClass('is-loading'); 
            $('#modalDialog').modal('toggle'); 

            $("#form_ServiceOrder").submit();
            return true;
        }
    }

	
</script>
