<a class="btn btn-dark" href="<?php echo HTTP_PATH."purchasing"; ?>">Back</a>
<?php
$rand1	=	rand(200, 800);
$rand2	=	rand(200, 800);
$pic	=	"<img src='http://placekitten.com/".$rand1."/".$rand2."' class='center' width='".$rand1."' height='".$rand2."' />";
echo isset($disp)?$disp:$pic;
?>
<script>
function toggleHide(e) {
	$(".canvassdetails"+e).toggleClass("hide");
}
function confirmSaveCanvass() {
	toastr.clear();
	toastr['warning']('<a class="btn btn-success btn-sm" onclick="javascript: document.getElementById(\'canvass_form\').submit();">Save Canvass</a>', "Are you sure?");
}
function createPO(requestid) {
	toastr.clear();
	toastr['warning']('This will create a PO/JO from all items/services that are marked as "for purchase" <a class="btn btn-success btn-sm" data-target="#modalDialog" data-toggle="modal" href="<?php echo HTTP_PATH; ?>purchasing/requisition/create_po/'+requestid+'">Continue</a>', "Are you sure?");
}
function cancelRequest(requestid) {
	toastr.clear();
	toastr['warning']('This will cancel all items in this request. <a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/requisition/cancel/'+requestid+'">Continue</a>', "Are you sure?");
}
function confirmApproveRequestItem(itemid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/requisition_item/'+itemid+'/approve_request">Approve Item</a>', "Are you sure?");
}
function confirmApproveCanvassItem(itemid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-success btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/requisition_item/'+itemid+'/approve_canvass">Approve Item</a>', "Are you sure?");
}
function confirmCancelRequestItem(itemid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-danger btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/requisition_item/'+itemid+'/cancel">Cancel Item</a>', "Are you sure?");
}
function confirmCancelCanvassItem(itemid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-danger btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/canvass_details/'+itemid+'/cancel">Cancel Item</a>', "Are you sure?");
}
function confirmCancelCanvassItemDraft(itemid, draftid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-danger btn-sm" href="<?php echo HTTP_PATH; ?>purchasing/canvass_details/'+itemid+'/cancel_draft/'+draftid+'">Cancel Item</a>', "Are you sure?");
}
function confirmApproveRequest(requestid) {
	toastr.clear();
	toastr['warning']('<a class="btn btn-success btn-sm" onclick="javascript: document.getElementById(\'request_approve_'+requestid+'\').submit();">Approve Selected</a>', "Are you sure?");
}
</script>