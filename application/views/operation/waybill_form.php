<style>#content{ margin-top:-20px; }</style>
<div class="panel-group" id="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			
            <a href="<?php echo HTTP_PATH.'employee_profile/add'; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="New Entry" style="cursor:pointer; float:right;">
				<img src="<?php echo HTTP_PATH.'assets/images/button_icons/24X24/calculator.png' ?>" align="absmiddle" style="border:#FF0000 thick" />
			</a>
            &nbsp;&nbsp;&nbsp;
           
            
            <a href="<?php echo HTTP_PATH.'Service/waybill'; ?>" class="" data-toggle="modal" style="display:none" data-target="#modalDialog" title="Waybill Entry">            
            	<button type="button" class="btn btn-primary btn-sm" id="waybill"  style="cursor:pointer; margin-top:-5px;  float:right; margin-right:10px">WayBill Entry</button>
            
            </a>
            
            
			<h4><strong><span style="background:#000099; color:#FFFFFF">OPERA</span><span style="background:#FF0000; color:#F4F4F4">TiON</span></strong></h4>
            
		</div>
		<div class="panel-body">
        
			<div>
            	<form class="form-horizontal" role="form" id="employee_info" name="employee_info"  action="<?php  ?>" method="post" enctype='multipart/form-data'>
                <?php echo $this->Mmm->createCSRF() ?>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-primary">
			
				
			
		</div>
		<div class="panel panel-default" style="font-size:12px">
			<div class="panel-heading" role="tab" id="headingOne">
			<h4 class="panel-title">
				<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				Waybill Information Entry
				</a>
			</h4>
            	<span style="float:right; margin-right:10px; margin-top:-35px">
					<input class="btn btn-success btn-sm" type="button"  value="Save" onclick="javascript:checkform()" id="submitbtn" style="width:100px; margin-left:30px; margin-top:10px">
					<input class="btn btn-default btn-sm"  value="Cancel" data-dismiss="modal" style="width:100px; margin-left:10px; margin-top:10px">
				</span>
				
			</div>
			<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body" style="background:#B0C6DF">
					
					
                    
                    
                    
                    <div style="width:250px; margin-left:30px; float:left; display:table;">
						
						<div class="form-group">
							<label for="first_name">Waybill No.:</label>
							<div>
								<input class="form-control input-sm" type="text" name="first_name" id="first_name" value="<?php  ?>" />
							</div>
						</div>
                        <div class="form-group">
							<label for="last_name">Contract Reference Id:</label>
							<input class="form-control input-sm"  type="text" name="last_name" id="last_name" value="<?php   ?>" />
						</div>
						<div class="form-group">
							<label for="gender">Service Type:</label>
							<div>
								<select class="form-control input-sm" name="gender" id="gender">
									<option></option>
									<option <?php  ?> value="Male">Vessel</option>
									<option <?php  ?> value="Female">Trucking</option>
                                    <option <?php  ?> value="Female">Handling</option>
                                    <option <?php  ?> value="Female">Equipment</option>
								</select>
							</div>
						</div>
                        <div class="form-group">
							<label for="gender">Servicer:</label>
							<div>
								<select class="form-control input-sm" name="gender" id="gender">
									<option></option>
									<option <?php  ?> value="Male">Visayan</option>
									<option <?php  ?> value="Female">Avega Trucking</option>
                                    <option <?php  ?> value="Female">Handlers</option>
								</select>
							</div>
						</div>
                        <div class="form-group">
							<label for="gender">Vessel (select if vessel):</label>
							<div>
								<select class="form-control input-sm" name="gender" id="gender">
									<option></option>
									<option <?php  ?> value="Male">M/V Mark</option>
									<option <?php  ?> value="Female">M/V Ligaya</option>
                                  
								</select>
							</div>
						</div>
                        <div class="form-group">
							<label for="address">Voyage No.(optional):</label>
							<div>
							<input class="form-control input-sm" type="text" name="address" id="address" value="<?php  ?>" /> 											</div>
						</div>
						
						
                    </div>   
                        
					<!---end of div float left --->
					<!---div float right --->
					<div style="width:250px; float:left; margin-left:80px; margin-top:0px">
						<div class="form-group">
							<label for="birth_date">Date Issued:</label>
							<div>
								<input class="form-control input-sm" type="text" name="birth_date" id="birth_date" value="<?php  ?>" />
								<script>$("#birth_date").datepicker({/*changeYear: true,yearRange: "-100:+10"*/});</script>
							</div>
						</div>
                        
                        <div class="form-group">
							<label for="address">Load At:</label>
							<div>
							<input class="form-control input-sm" type="text" name="address" id="address" value="<?php  ?>" /> 											</div>
						</div>
						<div class="form-group">
							<label  for="city">Unload At:</label>
							<div>
							<input class="form-control input-sm" type="text" name="city" id="city" value="<?php   ?>" />
							</div>
						</div>
                        <div class="form-group">
							<label  for="city">&nbsp;</label>
							<div style="text-decoration:underline">
							<br />
                            For Hauling Service:
                            
							</div>
						</div>
                        <div class="form-group">
							<label  for="city">Truck Plate No.:</label>
							<div>
							<input class="form-control input-sm" type="text" name="city" id="city" value="<?php   ?>" />
							</div>
						</div>
                        <div class="form-group">
							<label  for="city">Driver:</label>
							<div>
							<input class="form-control input-sm" type="text" name="city" id="city" value="<?php   ?>" />
							</div>
						</div>
						
					<!---end div float right --->
				</div>
                
                <!--- waybill activity --->
                <div style="width:250px; float:left; margin-left:80px; margin-top:0px">
                	<div class="container">
  						
							<div class="panel panel-default" style="font-size:12px; width:710px; height:395px">
								<div class="panel-heading" role="tab" id="headingOne">
                            		<strong>Accomplishment:</strong>
                                </div>                        
                                <div class="panel-body" role="tab" >
                                	
                                    <div style="width:200px; margin-left:20px; float:left">
                                        <div class="form-group">
                                            <label for="gender">WayBill Type:</label>
                                            <div>
                                                <select class="form-control input-sm" name="gender" id="gender">
                                                    <option></option>
                                                    <option <?php  ?> value="Male">Importation</option>
                                                    <option <?php  ?> value="Female">Transfer</option>
                                                    <option <?php  ?> value="Female">Dispersal</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="city">Cargo Description:</label>
                                            <div>
                                            <input class="form-control input-sm" type="text" name="city" id="city" value="<?php   ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div style="width:110px; float:left">
                                                <label  for="city">Gross Qty:</label>
                                                <div>
                                                <input class="form-control input-sm" type="text" name="city" id="city" value="<?php   ?>" />
                                                </div>
                                            </div>
                                            <div style="width:110px; float:left; margin-left:10px">
                                                <label  for="city">Net Qty:</label>
                                                <div>
                                                <input class="form-control input-sm" type="text" name="city" id="city" value="<?php   ?>" />
                                                </div>
                                            </div>
                                            
                                                
                                        </div>
                                        <div class="form-group">
                                            <label  for="unit">Unit:</label>
                                                <div>
                                                <select class="form-control input-sm" name="unit" id="unit">
                                                        <option></option>
                                                        <option <?php  ?> value="Male">bags</option>
                                                        <option <?php  ?> value="Female">metric tons</option>
                                                        <option <?php  ?> value="Female">kgs</option>
                                                    </select>
                                                </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label  for="city">Unit Price:</label>
                                            <div>
                                            <input class="form-control input-sm" type="text" name="city" id="city" value="<?php   ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div style="width:200px; margin-left:70px; float:left">
                                        <div class="form-group">
                                            <label for="gender">Loading Date:</label>
                                            <div>
                                                <select class="form-control input-sm" name="gender" id="gender">
                                                    <option></option>
                                                    <option <?php  ?> value="Male">Importation</option>
                                                    <option <?php  ?> value="Female">Transfer</option>
                                                    <option <?php  ?> value="Female">Dispersal</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="city">Loading Start Time:</label>
                                            <div>
                                            <input class="form-control input-sm" type="text" name="city" id="city" value="<?php   ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="city">Loading End Time:</label>
                                            <div>
                                            <input class="form-control input-sm" type="text" name="city" id="city" value="<?php   ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="city">Accomplished By:</label>
                                            <div>
                                            <select class="form-control input-sm" name="gender" id="gender">
                                                    <option></option>
                                                    <option <?php  ?> value="Male">bags</option>
                                                    <option <?php  ?> value="Female">metric tons</option>
                                                    <option <?php  ?> value="Female">kgs</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                        	<div>
                            	
                            </div>
                		
                    </div>
                </div>
                
			</div>
		</div>
        
        </div>
            
            </div>
            
		</div>
        <div class="progress progress-striped active">
   <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
    </div>
</div>
 <div class="row tasks">
    <div class="col-md-6">
      <p><span>title</span>description</p>
    </div>
    <div class="col-md-2">
      <label>date</label>
    </div>
    <div class="col-md-2">
      <input type="checkbox" name="progress" class="progress" value="35">
    </div>
    <div class="col-md-2">
      <input type="checkbox" name="done" class="done" value="100">
    </div>
  </div>
		<div class="panel-footer success text-right" style="color:#000099"><strong>AVEGA<span style="color:#FF0000">iT</span>.2015</strong></div>
	</div>
</div>
 <!-- Modal HTML -->
<script>
	function operateFormatter(value, row, index) {
		id = row['id']; //alert(id);
		return [
            '<a class="like" href="<?php echo HTTP_PATH.'hr/employee_profile/view/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Profile">',
                '<i class="glyphicon glyphicon-list-alt"></i> View',
            '</a><br/>',
            '<a class="edit ml10" href="<?php echo HTTP_PATH.'hr/employee_profile/edit/'; ?>'+row['id']+'" data-toggle="modal" data-target="#modalDialog" title="Edit">',
                '<i class="glyphicon glyphicon-edit"></i> Edit',
            '</a> '
        ].join('');
    }
	window.operateEvents = {
        'click .like': function (e, value, row, index) {
            p = row["sid"];
			var wid = 940;
			var leg = 680;
			var left = (screen.width/2)-(wid/2);
            var top = (screen.height/2)-(leg/2);
            // window.open('studProfile.cfm?pid='+p,'popuppage','width='+wid+',toolbar=0,resizable=1,location=no,scrollbars=no,height='+leg+',top='+top+',left='+left);
        },
        'click .edit': function (e, value, row, index) {
			p = row["sid"];
			// addForm(p);
        }
    };
	$(function () {
        var $table = $('#hr-table');
        $table.bootstrapTable();
    });
	
	
	
	$('input').on('click', function(){
	  var valeur = 0;
	  $('input:checked').each(function(){
		   if ( $(this).attr('value') > valeur )
		   {
			   valeur =  $(this).attr('value');
		   }
	  });
	  $('.progress-bar').css('width', valeur+'%').attr('aria-valuenow', valeur);    
	});
	
</script>
