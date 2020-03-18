<?php
	//var_dump($delivery_summary);
	//var_dump($delivery_detail);
	//var_dump($voucher);

	$supplier = $this->Abas->getSupplier($delivery_summary[0]['supplier_id']);

	//explode tin
	$tin = explode('-',$supplier['tin']);




	$payto = $delivery_summary[0]['supplier_id']; //direct to the who will receive the payment (company or person)
	//var_dump($supplier['name']);exit;
 	$company = $this->Accounting_model->getPoOwner($delivery_summary[0]['po_no']);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php if(ENVIRONMENT=="development") { echo "DEV - "; } ?>AVEGA Business Automation System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />

	<?php $this->load->view('includes_header'); ?>

<style>

#header{margin-top:30px}
#title{ font-size:22px; font-weight:600}
#sub-title{ font-size:16px;}
#ttype{ font-size:18px; font-weight:600; margin-top:20px}
#rr_no{ margin-top:-20px; float:right; font-weight:600;}
#receive_from{ margin-top:10px; float:left}
#date{ margin-top:10px; margin-right:100px;  float:right}
#po_no{ margin-top:30px;; margin-left:-100px; float:left}
#pr_no{ margin-top:30px; margin-right:-50px; float:right}
#si_no{ margin-top:50px; margin-left:-100px; float:left}
#dr_no{ margin-top:50px; margin-right:-50px; float:right}
#items{ margin-top:20px;}
#received_by{ margin-top:80px; float:left}
#inspected_by{ margin-top:80px; margin-left:200px; float:left}
#noted_by{ margin-top:-20px; margin-left:500px; float:left; width:150px}
#copy{ margin-top:0px; font-size:12px; font-weight:600; float:left; position:absolute}


</style>

</head>
<body style="background:#FFFFFF">

<div class="well" style="height:100px">
    <p style="font-size:16px">
    	Please select the type of document to print, the documents are different in sizes
        make sure to set print settings before printing.
    </p>
   <p>
    <div  style="position:absolute; margin-top:35px; margin-left:20px">
        <button id="printMe" type="button" class="btn-sm btn-success" onClick=' $("#voucherPrint").print(/*options*/);'><i class="fa fa-print" aria-hidden="true"></i>  Print Voucher</button>&nbsp;&nbsp;

        <button id="printMe" type="button" class="btn-sm btn-success" onClick=' $("#taxPrint").print(/*options*/);'><i class="fa fa-print" aria-hidden="true"></i>  Print 2307</button>&nbsp;&nbsp;
        <!---
        <button id="printMe" type="button" class="btn-xs btn-success" onClick=' $("#checkPrint").print(/*options*/);'><i class="fa fa-print" aria-hidden="true"></i>  Print Check</button>&nbsp;&nbsp;
        --->
        <a href="<?php echo HTTP_PATH ?>accounting/payables_view">
        <button type="button" class="btn-sm btn-default"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Back</button>
        </a>
    </div>
    </p>
</div>

<br />
<hr />
<br />

<div id="voucherPrint" >

	<table style="width:800px; " >

                <tr>
                	<td colspan="3" id="title" align="center"><?php echo strtoupper($company[0]['name']) ?></td>
                </tr>
                <tr>
                	<td colspan="3" align="center"><?php echo $company[0]['address'] ?></td>
                </tr>

                <tr>

                    <td align="center" align="center" colspan="3">Telephone: <?php echo $company[0]['telephone_no'] ?>   Fax: <?php echo $company[0]['fax_no'] ?></td>


                </tr>
                <tr>
                    <td align="right" colspan="3" >&nbsp;</td>
                </tr>

                <tr>
                    <td align="center" id="title" colspan="3"><strong><?php echo strtoupper($voucher[0]['type']) ?></strong></td>
                </tr>
                 <tr>
                    <td align="right" colspan="3" >&nbsp;</td>
                </tr>
                <tr>


                    <td align="right" colspan="3" ></td>

                </tr>
                <tr id="sub-title">
                	<td align="left"><strong>Pay To: <?php echo $supplier['name'] ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong style="color:#FF0000">Voucher No: <?php echo strtoupper($voucher[0]['voucher_number']) ?></strong></td>

                </tr>
                <tr id="sub-title">
                	<td align="left"><strong>Check Number: <?php echo $voucher[0]['check_num']  ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong>Date: <?php echo date('F j, Y')  ?></strong></td>

                </tr>
                 <tr id="sub-title">
                	<td align="left"><strong>Invoice No.: <?php echo $delivery_summary[0]['receipt_num'] ?></strong></td>
                    <td align="left"></td>
                    <td align="right"><strong>Purchase Order #: <?php echo $delivery_summary[0]['po_no'] ?></strong></td>

                </tr>
                <tr>
                    <td align="right" colspan="3" ><hr></td>
                </tr>

            </table>
            <br>

            <div style="margin-top:0px">
                <table id="datatable-responsive" style="margin-top:30px"  class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                              <thead id="sub-title">
                                                <tr bgcolor="#F4F4F4">

                                                  <th width="80%" style="border-right:#CCCCCC thin solid">Explanation of Payment</th>
                                                  <th width="20%">Amount</th>

                                                </tr>
                                              </thead>


                                              <tbody>
                                                <tr>
                                                  <td style="border-right:#CCCCCC thin solid" colspan="2">
                                                   <div style="margin-top:30px; margin-bottom:50px">
												     <table width="95%" cellpadding="10" cellspacing="10">
												   <?php
													$gtotal = 0;
													$wtax = 0;
													$vat = 0;
													foreach($delivery_detail as $d){

														//get item info
														$item = $this->Inventory_model->getItem($d['item_id']);

															$line_total = $d['quantity'] * $d['unit_price'];
															$gtotal = $gtotal + $line_total;

													?>


                                                   		<tr>
                                                    		<td width="10%"><?php echo $item[0]['item_code'] ?></td>
                                                            <td width="30%" align="left"><?php echo $item[0]['description'] ?></td>
                                                            <td width="10%" align="right"><?php echo $d['quantity'] ?>&nbsp;&nbsp;</td>
                                                            <td width="5%" align="left"><?php echo $d['unit'] ?></td>
                                                            <td width="10%" align="right">@ <?php echo $d['unit_price'] ?></td>
                                                            <td width="20%" align="right"><?php echo number_format($line_total,2); ?></td>



                                                    	</tr>


                                                     <?php




														}
													?>
                                                    </table>
                                                    </div>
                                                  </td>




                                                </tr>
                                              		<?php
												  		//compute tax

														if($voucher[0]['vtax']!=''){
															//compute vat
															$vat = $this->Accounting_model->computeVat($voucher[0]['vtax'],$gtotal);
														}

														if($voucher[0]['wtax']!=''){
															//compute vat
															$wtax = $this->Accounting_model->computeWTax($voucher[0]['wtax'],$gtotal);
														}

														$gtotal_afterTax = ($gtotal - $vat) - $wtax;

												  	?>

                                                <tfoot>
                                                <tr>


                                                   <td align="right">
                                                  	<span>Total:</span><br />
                                                    <span>WTax:</span> <br />
                                                    <span>VAT:</span> <br />
                                                    <span>Grand Total:</span>
                                                    </td>
                                                  <td width="10%" align="right">

                                                  	<span style=" width:100%; margin-right:35px">Php  <?php echo number_format($gtotal,2); ?>&nbsp;</span><br />
                                                    <span style=" width:100%; margin-right:35px">  (<?php echo number_format($wtax,2); ?>)&nbsp;</span><br />
                                                    <span style=" width:100%; margin-right:35px">  (<?php echo number_format($vat,2); ?>)&nbsp;</span><br />
                                                    <span style=" width:100%; margin-right:35px; font-weight:600">Php  <?php echo number_format($gtotal_afterTax,2); ?>&nbsp;</span><br />

                                                  </td>

                                                </tr>
                                                <tr>


                                                  <td class="a-center " style="font-size:16px; font-weight:600" align="center" colspan="2">
                                                  		<br />
                                                        Received the amount of: <?php echo $this->Mmm->chequeTextFormat($gtotal_afterTax);?>
                                                  </td>


                                                </tr>
                                              </tfoot>

                      </tbody>
                    </table>

            </div>

             <div align="center" style="margin-top:50px; margin-left:30px">
                <table style="width:800px; margin-top:20px; font-size:12px" >

                    <tr>
                    	<td>PREPARED BY::: </td>
                        <td>_________________________</td>
                        <td>CHECKED BY:</td>
                        <td>_________________________</td>
                    </tr>
                    <tr>
                    	<td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                    	<td>APPROVED BY:</td>
                        <td>_________________________</td>
                        <td></td>
                        <td>_________________________</td>
                    </tr>
                </table>

            </div>



            <br>


</div>

<div id="checkPrint" >
Check printing not yet ready.

</div>


</body>
</html>