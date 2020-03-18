<?php

$index_options 	= "";
$type_options 	= "";

if(!isset($evaluation_item)){

	$item_id = 0;

	$title = "Add - Evaluation Items";
	$action = HTTP_PATH.CONTROLLER."/add/evaluation_items";

	$item_name = "";
	$item_index = "";
	$item_set = "";
	$item_sub_set = "";
	$type = "";
	$ask_spec	= 0;
	$enabled = 1;
	$is_edit = FALSE;

	foreach($index as $indices){
		$index_options	.=	"<option value='".$indices."'>".$indices."</option>";
	}

	$type_options .= "<option value='Vessel'>For Vessel Maintenance</option>";
	$type_options .= "<option value='Truck'>For Truck Maintenance</option>";

}
elseif(isset($evaluation_item)){

	$item_id = $evaluation_item['id'];

	$title = "Edit - Evaluation Items";
	$action = HTTP_PATH.CONTROLLER."/edit/evaluation_items/".$item_id;

	$item_name = $evaluation_item['item_name'];
	$item_index = $evaluation_item['item_index'];
	$item_set = $evaluation_item['item_set'];
	$item_sub_set = $evaluation_item['item_sub_set'];
	$ask_spec	= $evaluation_item['ask_spec'];
	$type = $evaluation_item['type'];
	$for = $type;
	$enabled = $evaluation_item['enabled'];
	$is_edit = TRUE;

	foreach($index as $indices){
		$index_options	.=	"<option ".($item_index==$indices ? "selected":"")." value='".$indices."'>".$indices."</option>";
	}

	$type_options .= "<option ".($type=="Vessel" ? "selected":"")." value='Vessel'>For Vessel Maintenance</option>";
	$type_options .= "<option ".($type=="Truck" ? "selected":"")." value='Truck'>For Truck Maintenance</option>";
}

?>

			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="panel-title">
						<text><?php echo $title;?></text>
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
					</div>
				</div>
			</div>
				
				<div class="panel-body panel">

				<?php
					$attributes = array('id'=>'evaluation_items','role'=>'form','data-toggle'=>'validator');
					echo form_open_multipart($action,$attributes);
					echo $this->Mmm->createCSRF();
				?>

				<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="x_panel">
							<div class="x-title"><h2>Indexing</h2></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<label>Index*</label>
	                      			<select name="index" id="index" class="form-control" required>
										<option value=""></option>
										<?php echo $index_options; ?>
									</select>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<label>Set*</label>
								<input type="number" min="1" max="99" name="set" id="set" class="form-control" value="<?php echo $item_set;?>" required>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<label>Sub-set*</label>
								<input type="number" min="1" max="99" name="sub_set" id="sub_set" class="form-control" value="<?php echo $item_sub_set;?>" required>
							</div>
							<br><br><br><br>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<label>Type</label>
								<input type='text' id='type' name='type' style='text-align:center' class='form-control' value='<?php echo $for;?>' required readonly>
								<!--<select name="type" id="type" class="form-control" required>
									<option value="">Select</option>
									<?php //echo $type_options;?>
								</select>-->
							</div>
						</div>
					</div>

					<div class="col-md-8 col-sm-8 col-xs-12">
						<div class="x_panel">
							<div class="x-title"><h2>Evaluation Item</h2></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<label>Item or Particulars*</label>
								<?php
									echo '<textarea id="item_name" name="item_name" class="input form-control" rows="4" required>' .$item_name. '</textarea>';
								?>

							</div>
							<div class="col-md-4 col-sm-4 col-xs-4 pull-right">
								<div class="col-md-8 col-sm-8 col-xs-8">
									<label>Ask Spec?
									<?php
										if($ask_spec==0){
											echo '<input type="checkbox" id="ask_spec" name="ask_spec" class="form-control" value=1/>';
										}
										elseif($ask_spec==1){
											echo '<input type="checkbox" id="ask_spec" name="ask_spec" class="form-control" value=1 checked/>';
										}
									?>	          
		                            </label>
								</div>
								<div class="col-md-4 col-sm-4 col-xs-4">
									<label>Enabled?

									<?php
										if($enabled==0){
		                              		echo '<input type="checkbox" id="enabled" name="enabled" class="form-control" value=1/>';
		                              	}
		                              	elseif($enabled==1){
		                              		echo '<input type="checkbox" id="enabled" name="enabled" class="form-control" value=1 checked/>';
		                              	}
		                             ?>

		                            </label>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="pull-right">
						<?php
							echo '<input type="button" class="btn btn-success" value="Save" name="save" id="save" onclick="chkSubmit(' . $item_id . ');"/>';
						?>
							<button type="submit" class="btn btn-danger" data-dismiss="modal">Discard</button>
						</div>
					</div>

				</form>
				</div>
			

<script type="text/javascript">
	function chkSubmit(id){

		  var item = document.getElementById('item_name').value;
	 	  var type = document.getElementById('type').value;
		  var index = document.getElementById('index').value;
		  var set = document.getElementById('set').value;
		  var sub_set = document.getElementById('sub_set').value;

		  if(item=="" || type=="" || index=="" || set=="" || sub_set==""){
		  	toastr['error']("Please complet all required fields.", "ABAS says:");
		  }else{

		  	if(id == 0){
		  		$url = "<?php echo HTTP_PATH.CONTROLLER;?>/check_indexing/"+type+"/"+index+"/"+set+"/"+sub_set+"/"+0;
		  	}
		  	else{
		  		$url = "<?php echo HTTP_PATH.CONTROLLER;?>/check_indexing/"+type+"/"+index+"/"+set+"/"+sub_set+"/"+id;
		  	}
		  	  //Ajax to check if indexing already exist
			  $.ajax({
			     type:"POST",
			     url:$url,
			     success:function(data){
			     	var data = JSON.parse(data);
			       
			        if(data==true){
			        	toastr['error']("This indexing was already assigned to other Evaluation Item. Please try another indexing.", "ABAS says:");
					}
					if(data==false){

						$('body').addClass('is-loading'); 
						$('#modalDialog').modal('toggle'); 

						document.getElementById("evaluation_items").submit();
					}

				}

			  })
			
		  }

	}
</script>

