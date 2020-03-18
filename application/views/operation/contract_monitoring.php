<style>#content{ margin-top:-20px; }</style>
<div class="panel-group" id="content">
	<div class="panel panel-default">
		<div class="panel-heading" style="display:block">
			
			</a>
            &nbsp;&nbsp;&nbsp;
            <a href="<?php echo HTTP_PATH.'Service/tool_registry_form'; ?>" class="" data-toggle="modal" data-target="#modalDialog" title="Register Report Tool" style="cursor:pointer; float:right;">
				
                <img src="<?php echo HTTP_PATH.'assets/images/button_icons/24X24/monitor.png"' ?>" align="absmiddle" style="border:#FF0000 thick" />
			</a>
            &nbsp;&nbsp;&nbsp;
           <a href="<?php echo HTTP_PATH.'Service/monitoring'; ?>" class=""  title="Ship Monitoring" style="cursor:pointer; float:right; margin-right:30px; font-size:11px">
				<button class="button button-warning">
                Ship Monitoring
                </button>
			</a>
            
            
            
             
			<h4 style="float:left;"><strong><span style="background:#000099; color:#FFFFFF">Operation</span><span style="background:#FF0000; color:#F4F4F4">Monitoring</span></strong></h4>
            
		</div>
		<div class="panel-body">
        
			<div>
            	<form class="form-horizontal" role="form" id="employee_info" name="employee_info"  action="<?php  ?>" method="post" enctype='multipart/form-data'>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-primary">
			
				
			
		</div>
		<div class="panel panel-warning" style="font-size:12px;">
			
            
            
            <div class="box">
                                
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-striped table-bordered">
                                        <tr>
                                            <th width="7%">Contract ID</th>
                                            <th width="10%">Client</th>
                                            <th width="80%" colspan="5">Overall Activity</th>                                            
                                            <th width="3%">*</th>
                                        </tr>
                                        <tr>
                                            <th width="15%" colspan="2"></th>
                                            <th>Vessel Activity</th>
                                            <th>Trucking Activity</th>
                                            <th>Warehouse Activity</th>
                                            <th>Billing</th>
                                            <th>Payment</th>                                            
                                            <th width="5%">Status</th>
                                        </tr>
                                        <?php  
											
											$ctr = count($activity);											
											foreach($activity as $act){
											
											$v = $act["vid"];
											//var_dump($act['activity']->remark);
										?>
                                        <tr>
                                            <td>12345</td>
                                            <td>Bounty</td>
                                            <td>
                                            	<span style="font-size:14px; font-weight:600">
                                                
                                                <a href="<?php echo HTTP_PATH.'Service/ship_profile/'.$v ;?>" class="" data-toggle="modal" data-target="#modalDialog" title="Vessel Profile"><?php echo $act['name']; ?></a>
                                                </span>
                                                <div style="font-size:11px; color:#FF6600">
                                                	Loaded to Vessel: 
                                                    <br />
                                                    Unloaded to Port:
                                                    <br />
                                                    Damaged:
                                                </div>
                                            </td>
                                            
                                            <td>
                                            	
                                                <span style="font-size:14px; font-weight:600; color:#336600">                                                
                                                	Number of Trucks Loaded:
                                                </span>
                                                <div style="font-size:11px; color:#FF6600">
                                                    Loaded to truck:
                                                    <br />
                                                    Unloaded to Warehouse:
                                                    <br />
                                                    Damaged:
                                                </div>                                    
                                            
                                            </td>
                                            <td>
                                            	
                                                <span style="font-size:14px">
													
                                                    Number of Trucks Unloaded:
                                                    
                                                	<br />
                                                	<span style="font-size:12px; color:#FF0000;">
                                                    	Unloaded from truck:
                                                        <br />
                                                        Qty Moved:
                                                        <br />
                                                    	Damaged:
                                                  	</span> 
                                                                                        
                                                </span>
                                            </td>
                                            <td>
                                            	<span class="fa-bullhorn"></span>
                                                
                                                <span style="font-size:12px; color:#FF0000">( Billing Status...)</span>
                                                
                                           </td>
                                            <td>
                                            	<span style="font-size:12px; color:#FF0000">( Payment Status...)</span>
                                            </td>
                                            <td>
                                            	Ongoing...
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                    </table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
            
            
            
            
			
                
                   
                
			</div>
		</div>
        
   </div>
            
 </div>
            
<div>
        
        <div class="progress progress-striped active">
   		<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
    	</div>
</div>


 <!--- Modal Form--->   
    <!-- Modal HTML -->
   
 	<div id="modalDialog" class="modal fade">
        <div class="modal-dialog" style="width:1000px; margin-top:30px">
            <div class="modal-content">
                <p class="loading-text">Loading Content...</p>
            </div>
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
