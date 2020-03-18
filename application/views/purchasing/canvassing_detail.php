<?php
	//$this->Mmm->debug($request);
	//$this->Mmm->debug($parent);
	if($parent['packaging']==''){
		$itemtitle	=	$parent['quantity']." ".strtolower($parent['item_details']['unit'])." of ".$parent['item_details']['description'];
	}else{
		$itemtitle	=	$parent['quantity']." ".strtolower($parent['packaging'])." of ".$parent['item_details']['description'];
	}
	
	$canvassedstring	=	"<td colspan='99' class='text-center emptycanvass'>No canvass details yet! Contact a supplier for this item now!</td>";
	$canvassedstring	=	"";
	if(!empty($canvassed)) {
		foreach($canvassed as $ctr=>$c) {
			// $this->Mmm->debug($c);
			$s	=	$this->Abas->getSupplier($c['supplier_id']);
			$manage	=	"<a class='request-item-cancel-btn btn btn-danger btn-xs' onclick='javascript: confirmCancelCanvassItem(".$c['id'].")'><span class='glyphicon glyphicon-remove'></span></a>";
			if(strtolower($c['status'])==strtolower("Cancelled")) {
				$manage	=	$c['status'];
			}
			$canvassedstring	.=	"<tr>";
				$canvassedstring	.=	"<td>".$s['name']."</td>";
				$canvassedstring	.=	"<td class='text-right'>P".number_format($c['unit_price'],2)."</td>";
				$canvassedstring	.=	"<td>".$c['remark']."</td>";
				$canvassedstring	.=	"<td>".ucwords(strtolower($c['status']))."</td>";
				$canvassedstring	.=	"<td>".$manage."</td>";
			$canvassedstring	.=	"</tr>";
		}
	}
	$historytable	=	"<table class='table table-striped table-bordered'>";
	if(empty($history)) { $historytable.="<tr><th colspan=99>No previous canvass data found!</th></tr>"; }
	else {
		$historytable	.=	"<tr>";
			$historytable	.=	"<th></th>";
		$historytable	.=	"</tr>";
	}
	$historytable	.=	"</table>";
?>
<div class="panel panel-primary">
	<div class="panel-heading" role="tab" id="headingCanvass">
		<h4 class="panel-title">
			<a role="button" data-toggle="collapse" href="#bodyCanvassForm" aria-expanded="true" aria-controls="bodyCanvassForm">
				Add Canvass
			</a>
			<button type="button" class="pull-right btn btn-xs btn-success" onclick="javascript:confirmSaveCanvass();">Submit</button>
			<button type="button" class="pull-right btn btn-xs btn-danger" data-dismiss="modal">Cancel</button>
		</h4>
	</div>
	<div id="bodyCanvass" role="tabpanel" aria-labelledby="headingCanvass">
		<div class="panel-body">
			<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-7 col-md-offset-3" ailgn="left">
				<?php echo $parent['remark']; ?>
			</div>
			<div class="clearfix"><br/></div>
			<div class="col-xs-12 col-sm-12 col-md-12" ailgn="left">
				<div class="panel panel-success">
					<div class="panel-heading" role="tab" id="headingCanvassForm">
						<h4 class="panel-title">
							<?php echo $itemtitle; ?>
						</h4>
					</div>
					<div id="bodyCanvassForm" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingCanvassForm">
						<div class="panel-body">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<form role='form' method='POST' id='canvass_draft_form' onsubmit='javascript: checkcanvassdraftform()' enctype='multipart/form-data'>
									<?php echo $this->Mmm->createCSRF(); ?>
									<div class='col-sm-12 col-md-9'>
										<label for='supplier'>Supplier</label>
										<input type='text' id='supplier_name' name='supplier_name'  placeholder='Supplier' class='form-control' value='' />
										<input type='text' id='supplier_id' name='supplier_id'  placeholder='' class='hide' value='' />
									</div>
									<div class='col-sm-12 col-md-3'>
										<label for='unit_price'>Unit Price</label>
										<input type='number' id='unit_price' name='unit_price'  placeholder='Unit Price' class='form-control' value='' />
									</div>
									<div class='col-sm-12 col-md-12'>
										<label for='remarks'>Remarks</label>
										<textarea name='remark' id='remark' class='form-control'></textarea>
									</div>
									<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
									<div class='col-xs-12 col-sm-12 col-lg-12'>
										<input type='button' value='Add' name='btnSubmit' class='btn btn-primary btn-block' onclick='javascript: checkcanvassdraftform()' />
									</div>
									<div class='col-xs-12 col-sm-12 col-lg-12 clearfix'><br/></div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"><br/></div>
			<form action="<?php echo HTTP_PATH; ?>purchasing/canvass_details/<?php echo $parent['id']; ?>/canvass" role='form' method='POST' id='canvass_form' enctype='multipart/form-data'>
			<table id="datatable-responsive" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Supplier</th>
						<th>Unit Price</th>
						<th>Remarks</th>
						<th>Status</th>
						<th>Manage</th>
					</tr>
				</thead>
				<tbody class="canvassed-content<?php echo $parent['id']; ?>">
					<?php echo $canvassedstring; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	function checkcanvassdraftform() {
		<?php $n=$this->security->get_csrf_token_name(); $h=$this->security->get_csrf_hash(); ?>
		var msg="";
		//var patt1=/^[0-9]+$/i;
		var patt1=/^\d+(\.\d+)*$/i;
		var csrf="<?php echo $h; ?>";
		var supplier=document.forms.canvass_draft_form.supplier_name.value;
		if (supplier==null || supplier=="" || supplier=="Supplier") {
			msg+="Supplier is required! <br/>";
		}
		else {
			var supplierid=document.forms.canvass_draft_form.supplier_id.value;
			if (supplierid==null || supplierid=="") {
				msg+="Please select a supplier from the dropdown! <br/>";
			}
		}

		var unit_price=document.forms.canvass_draft_form.unit_price.value;
		if (unit_price==null || unit_price=="" || unit_price=="Unit Price") {
			msg+="Unit Price is required! <br/>";
		}
		else if (!patt1.test(unit_price)) {
			msg+="Only numbers are allowed in Unit Price! <br/>";
		}
		var remark=document.forms.canvass_draft_form.remark.value;
		if(msg!="") {
			toastr['warning'](msg,"ABAS Says");
			return false;
		}
		else {
			var appendhere	=	$(".canvassed-content<?php echo $parent['id']; ?>");
			appendhere.append(
				"<tr>"+
					"<td>"+document.getElementById('supplier_name').value+"</td>"+
					"<td class='text-right'>"+document.getElementById('unit_price').value+"</td>"+
					"<td>"+document.getElementById('remark').value+"</td>"+
					"<td>Canvass Draft</td>"+
					"<td>"+
						"<a class='request-item-cancel-btn btn btn-danger btn-xs' onclick='javascript: $(this).parent().parent().remove()'><span class='glyphicon glyphicon-remove'></span></a>"+
						"<input type='hidden' name='supplier_id[]' value='"+document.getElementById('supplier_id').value+"' /><input type='hidden' name='unit_price[]' value='"+document.getElementById('unit_price').value+"' /><input type='hidden' name='remark[]' value='"+document.getElementById('remark').value+"' />"+
					"</td>"+
				"</tr>"
			);
			toastr['success']("","Canvass Added!");
			document.getElementById('supplier_name').value='';
			document.getElementById('supplier_id').value='';
			document.getElementById('unit_price').value='';
			document.getElementById('remark').value='';
			document.getElementById('supplier_name').focus();
			/*
			var dataString = {
				"<?php echo $n;?>":"<?php echo $h; ?>",
				"supplier_id":supplierid,
				"unit_price":unit_price,
				"remark":remark,
			};
			toastr.clear();
			toastr['info']("","Please wait...");
			$.ajax({
				type: "POST",
				url: "<?php echo HTTP_PATH; ?>purchasing/canvass_details/<?php echo $parent['id']; ?>/draft",
				//url: "<?php echo HTTP_PATH; ?>purchasing/canvass_details/<?php echo $parent['id']; ?>/canvass/ajax",
				data: dataString,
				cache: false,
				success: function(html) {
					$(".emptycanvass").remove();
					toastr.clear();

				},
				error: function(html) {
					toastr['error']("","Canvass Not Added!");
				}
			});
			// document.getElementById("canvass_form").submit(); return true;
			*/
		}
	}
	$(document).ready(function () {
		$( "#supplier_name" ).autocomplete({
			source: "<?php echo HTTP_PATH; ?>home/autocomplete/suppliers/name",
			minLength: 2,
			search: function(event, ui) {
				toastr['info']('Loading, please wait...');
			},
			response: function(event, ui) {
				if (ui.content.length === 0) {
					toastr.clear();
					toastr['warning']	=	"No suppliers found";
				}
				else {
					toastr.clear();
				}
			},
			select: function( event, ui ) {
				$( this ).val( ui.item.label );
				$( "#supplier_id" ).val( ui.item.value );
				return false;
			}
		});
	});
</script>