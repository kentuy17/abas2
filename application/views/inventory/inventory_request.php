<?php
$id = "";
$payee = "";
$payee_name = "";
$voucher_no = "";
$voucher_date = "";
$particular = "";
$amount = "";
$reference_no = "";
$voucher_no = "";
$vessel_id = "";
$vessel_name = "";
$include_on = "";
$classification = "";
$classification_name = "";

if(isset($viewExpense)){
	//var_dump($viewExpense[0]['id']);
	$id = $viewExpense[0]['id'];
	$voucher_no = $viewExpense[0]['check_voucher_no'];
	$voucher_date = $viewExpense[0]['check_voucher_date'];
	$particular = $viewExpense[0]['particulars'];;
	$amount = $viewExpense[0]['amount_in_php'];;
	$reference_no = $viewExpense[0]['reference_no'];
	$vessel_id = $viewExpense[0]['vessel_id'];
	if($vessel_id!=''){
		$v = $this->Abas->getVessel($vessel_id);
		$vessel_name = $v->name;
	}
	$payee = $viewExpense[0]['account_id'];
	if($payee!=''){
		$p = $this->Accounting_model->getSuppliers($payee);
		if(count($p) > 0){
			$payee_name = $p[0]['name'];
		}
	}
	$classification = $viewExpense[0]['expense_classification_id'];;
	if($classification!=''){
		$c = $this->Accounting_model->getExpenseClassification($classification);
		if(count($c) > 0){
			$classification_name = $c[0]['name'];
		}
	}
	$include_on = $viewExpense[0]['include_on'];
}
$link = HTTP_PATH.'Accounting/expense_report_form/';
?>

<script>
	
	 $(document).ready(function() {
       
		
		$('#item').on('change', function() {
			var val = this.checked ? this.value : '';
			alert(val);
			//$('#show').html(val);
		});
		
    });
	
	
	function selectItems(){
		
		var checkboxValues = [];
		var sels = [];
		$('input[name=sid]:checked').map(function() {
				var v = $(this).val();
				var qid = $(this).val() +'qty';
				var q = document.getElementById(qid).value;
				if(q == ''){
					alert('Please enter quantity.');
					document.getElementById(qid).focus();
					return false;	
				}else{
					
					sels = v+"|"+q;
					checkboxValues.push(sels);
					document.getElementById('reqItems').value = checkboxValues;
					
				}	
		});
	
		//alert(checkboxValues);
	}
	
	function submitForm(){
	
		//run this to make sure every seectd itms are included
		selectItems();
		
		//make sure somthing is selected
		var s = document.getElementById('reqItems').value;
		if(s == ''){
			alert('Please select items for request.');
			return false;
		}else{
		
			document.forms['reqForm'].submit();
			
		}
	}
	
</script>

<style>#content{ margin-top:-20px; }</style>
<div class="panel-group" id="content">
	<div class="panel panel-default">
		<div class="panel-heading" >
			<span style="float:left; margin-left:0px; margin-top:-10px">
			<h4><strong><span style="background:#000099; color:#FFFFFF">INVENT</span><span style="background:#FF0000; color:#F4F4F4">ORY</span></strong></h4>
            </span>
            <span style="float:right; margin-right:20px; margin-top:-5px">
				<span style="float:right; margin-right:10px; margin-top:5px">
				     <a class="like" href="<?php echo HTTP_PATH.'inventory/viewRequest'; ?>"  title="Create Request">
					<button type="button" id="createRequest" class="btn btn-info btn-xs" onclick="submitForm();">View Requests</button>
                	</a>         
                 	<a class="like" href="##"  title="Create Request">
					<button type="button" id="createRequest" class="btn btn-info btn-xs" onclick="submitForm();">Create Request</button>
                	</a>
                &nbsp;&nbsp;
				<a class="like" href="<?php echo HTTP_PATH ?>inventory/"  title="Back">
					<button type="button" class="btn btn-default btn-xs">Back</button>
                </a>                     
				<form name="reqForm" method="post" action="<?php echo HTTP_PATH.'inventory/addRequest'; ?>">
                	<input type="hidden" name="reqItems" id="reqItems" />
                </form>
                
            </span>
				
                
            </span>
		</div>
		<div class="panel-body">
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					
                    <div class="panel panel-primary"></div>
					<div class="panel panel-default" style="font-size:12px;background:#CCCCCC">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								Inventory Request
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body" style="background:#FFF">
								
								<div style="width:200px; float:left; margin-left:0px; margin-top:-15px">
                        <div class="container">
                			
                            <table data-toggle="table" id="hr-table" class="table table-striped table-hover table-responsive" data-url="<?php echo HTTP_PATH."inventory/view_all_items"; ?>" data-cache="false" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-page-size="5"  data-search="true" style="font-size:12px">
										<thead>
											<tr>
												<th style="width:1%"  data-align="center" >*</th>
                                                <th style="width:3%" data-field="item_code"  data-align="center" data-sortable="true">Item Code</th>
												<th  style="width:48%" data-field="description" data-align="left" data-sortable="true">Description</th>
												<th style="width:20%" data-field="particular"  data-align="left">Particular</th>
												<th style="width:15%" data-field="unit_price"  data-align="left" data-sortable="true">Category</th>
                                                
												<th style="width:5%" data-field="qty"  data-align="center" data-sortable="true">Qty on Hand</th>												<th style="width:5%" data-field="unit"  data-align="center" data-sortable="true">Unit</th>
												<th style="width:4%"  data-halign="center" data-align="center">Qty</th>
												<th style="width:1%" data-field="id"  data-align="center" >/</th>
											</tr>
										</thead>
											<tbody>
											<?php
												//var_dump($expenses);
												//exit;
												$ctr = 1;
												foreach($items as $item){
												
												$sq = "SELECT * FROM `inventory_category` WHERE id =".$item['category'];
												
												$r = $this->db->query($sq);
												$cat = $r->result_array();
											?>
												<tr>
													<td align="center"><?php echo $ctr; ?></td>
                                                    <td><?php echo $item['item_code']; ?></td>
													<td><?php echo $item['description']; ?></td>
													<td><?php echo $item['particular']; ?></td>
													<td><?php echo $cat[0]['category']; ?></td>													
													<td><?php echo $item['qty']; ?></td>
                                                    <td><?php echo $item['unit']; ?></td>
													<td align="center">
                                                    
                                                    <?php if($item['req'] == 1){  ?>
                                                    	
                                                    <?php }else{ ?>
                                                    
                                                    <input type="text" id="<?php echo $item['id'].'qty'; ?>" style="width:70px; text-align:center" />	
                                                     <?php } ?>
                                                    </td>
                                                    <td align="center">
                                                     <?php if($item['req'] == 1){  ?>
                                                    	<i class="glyphicon glyphicon-check"></i>
                                                    <?php }else{ ?>
                                                    <input type="checkbox" name="sid" value="<?php echo $item['id']; ?>" onclick="selectItems();">
                                                    <?php } ?>
                                                   </td>
												</tr>
											<?php $ctr = $ctr+1; } ?>
										</tbody>
									</table>
                            
                            
                        </div>
                    </div>            
								</div>
							</div>
						</div>
                        
                        

					</div>
                    
                        

				</div>




			<div class="panel-footer success text-right" style="color:#000099"><strong>AVEGA<span style="color:#FF0000">iT</span>.2015</strong></div>
		</div>
	</div>
</div>


<script>


	function operateFormatter(value, row, index) {
		id = row['id']; //alert(id);
		return [
            '<a class="like" href="<?php echo HTTP_PATH.'inventory/item_form/'; ?>'+row['id']+'" title="View" data-toggle="modal" data-target="#modalDialog">',
                '<i class="glyphicon glyphicon-list-alt"></i> View',
            '</a>',
        ].join('');
    }
	window.operateEvents = {
        'click .like': function (e, value, row, index) {
            p = row["sid"];
			var wid = 940;
			var leg = 680;
			var left = (screen.width/2)-(wid/2);
            var top = (screen.height/2)-(leg/2);
        },
        'click .edit': function (e, value, row, index) {
			p = row["sid"];
        }
    };
	/*
	function operateFormatter(value, row, index) {
		id = row['id']; //alert(id);
		return [
            '<a class="like" href="<?php echo HTTP_PATH.'accounting/viewExpense/'; ?>'+row['id']+'"  title="View">',
                '<i class="glyphicon glyphicon-list-alt"></i> View',
            '</a>'

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
	*/



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

	function newEntry(){
		window.location.assign("<?php echo HTTP_PATH.'Inventory' ?>")
		document.forms['itemForm'].reset();
	}

	function submitMe(){

		var id = document.getElementById('id').value;
		var i = document.getElementById('item_code').value;
		var d = document.getElementById('description').value;
		var p = document.getElementById('particular').value;		
		var u = document.getElementById('unit').value;
		var uc = document.getElementById('unit_cost').value;
		var q = document.getElementById('qty').value;
		

		//if(id !== ''){

			/*
			alert('Editing is not allowed');
			return false;
		}else{*/

			if(i == ''){
				alert('Please enter Item Code');
				document.getElementById('item_code').focus();
				return false;
			}else if(d == ''){
				alert('Please enter Description');
				document.getElementById('description').focus();
				return false;
			}else if(u == ''){
				alert('Please select unit');
				document.getElementById('unit').focus();
				return false;
			}else if(q == ''){
				alert('Please enter quantity on hand');
				document.getElementById('qty').focus();
				return false;					
			}else{
				document.forms['itemForm'].submit();
			}

		//}


	}

	function createReport(){

		document.forms['expenseReport'].submit();

	}

</script>
