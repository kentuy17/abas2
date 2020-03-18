<?php

 $content2 =  '<style type="text/css">
                 h1 { font-size:200%;text-align:center; }
                 h2 { font-size:150%;text-align:center; }   
                 h3 { font-size:100%;text-align:center; }
                 h5 { border-bottom: double 3px; }
                 td {font-size:160%;}
                 th { font-weight:bold;font-size:150%;text-align:center}
                  p { text-align:left;font-size:150%; }
                .bt { font-weight:bold; text-align:right}
                .btx { font-weight:bold; text-align:right; font-size:190%}
                .tg {border-collapse:collapse;border-spacing:0;}
                .tg td{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;overflow:hidden;word-break:normal;}
                .tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:10px 5px;overflow:hidden;word-break:normal;}
                .tg .tg-yw4l{vertical-align:top}
                .tg .tg-9hbo{font-weight:bold;vertical-align:top;horizontal-align:left}
                .underline {text-decoration:underline;border-bottom: 2px;font-weight:bold}
                .doubleUnderline {text-decoration:underline;text-decoration-style:double;}
                .bot {vertical-align:bottom;}
            </style>
            <div style="margin-top:2000px">
                <table width="100%">
                    <tr>
                        <td><br><br><br><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
                        <td colspan="4">
                            <h1 style="text-align:left">'. $company_issuance->name .'</h1>
                            <h3 style="text-align:left">'. $company_issuance->address.'</h3>
                            <h3 style="text-align:left">'. $company_issuance->telephone_no.'</h3>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div>
                <table border="0" cellspacing="5">
                    <tr>
                        <td colspan="9" class="btx">MSIS No.</td>
                        <td colspan="1" class="btx" align="left">'.$summary_issuance[0]['control_number'].'</td>
                    </tr>
                    <tr>
                        <td colspan="10"><h2>MATERIALS AND SUPPLIES ISSUANCE SLIP<br></h2></td>
                    </tr> 
                    <tr>
                        <td align="left" colspan="7"><b>Department/Vessel: '.$vessel_issuance->name.'</b></td>
                        <td align="right" colspan="3"><b>Date Issued: '.date('m-d-Y', strtotime($summary_issuance[0]['issue_date'])).'</b></td>
                    </tr>
                    <tr>
                        <td align="left" colspan="4"><b>Requisition No.: </b>'.$request['control_number'].' (TS Code No.'.$request['id'].')</td>
                    </tr>
                </table><br>';

$content2 .= '<div><table border="1px" cellpadding="5">
                <thead>
                    <tr bgcolor="#d4d4d4">
                        <td width="10%" align="center"><b>Item Code</b></td>
                        <td width="50%" align="center"><b>Description</b></td>
                        <td width="5%" align="center"><b>Qty</b></td>
                        <td width="15%" align="center"><b>Unit</b></td>
                        <td width="10%" align="right"><b>Unit Price</b></td>
                        <td width="10%" align="right"><b>Line Total</b></td>
                    </tr>
                </thead>
                <tbody>';

$gtotal = 0;

foreach($details_issuance as $detail){
  $itemInfo = $this->Inventory_model->getItems($detail['item_id']);
  $qty = ($detail['qty'] != 0 || $detail['qty'] != '' ? $detail['qty'] : 0);
  $unit_price = ($detail['unit_price'] != 0 || $detail['unit_price'] != '' ? $detail['unit_price'] : $itemInfo[0]['unit_price']);
  $lineTotal = $qty * $unit_price;
  $content2 .='<tr>
                <td width="10%" align="center">'.$itemInfo[0]['item_code'].'</td>
                <td width="50%" align="center">'.$itemInfo[0]['description'].", ".$itemInfo[0]['particular'].'</td>
                <td width="5%" align="center">'.$detail['qty'].'</td>
                <td width="15%" align="center">'.$detail['unit'].'</td>
                <td width="10%" align="right">'.number_format($unit_price, 2, '.', ',').'</td>
                <td width="10%" align="right">'.number_format($lineTotal,2, '.', ',').'</td>
              </tr>';
 $gtotal = $lineTotal + $gtotal;
}  

$content2 .='<tr>
                <td colspan="5"></td>
                <td align="right">'.number_format($gtotal,2, '.', ',').'</td>
            </tr>
            </tbody>
            </table></div>';

$content2 .='<div align="left" class="btx">Issued to: '.$summary_issuance[0]['issued_to'].'<br>Remarks:'.$summary_issuance[0]['remark'].'</div>';

$content2 .='<div><br><br><table>
                <tr>
                    <td align="left" colspan="4"><b>Prepared by: </b>_____________________<br></td>
                    <td align="left" colspan="2"></td>
                    <td align="left" colspan="2"></td>
                    <td align="left" colspan="4"><b>Noted by: </b>_____________________<br></td>
                </tr>
                <tr>
                    <td align="center" colspan="4">Signature over printed name</td>
                    <td align="left" colspan="2"><br></td>
                    <td align="left" colspan="2"><br></td>
                    <td align="center" colspan="4">Signature over printed name</td>
                </tr>
                <tr>
                    <td align="center" colspan="4"><br><br>Date:_______________</td>
                    <td align="left" colspan="2"><br><br></td>
                    <td align="left" colspan="2"><br><br></td>
                    <td align="center" colspan="4"><br><br>Date:_______________</td>
                </tr>
                <tr>
                    <td align="left" colspan="2"><br></td>
                    <td align="left" colspan="2"><br></td>
                    <td align="left" colspan="2"><br></td>
                    <td align="left" colspan="2"><br></td>
                </tr>
                <tr>
                    <td align="left" colspan="4"><b>Released by:</b>_____________________</td>
                    <td align="left" colspan="2"></td>
                    <td align="left" colspan="2"></td>
                    <td align="left" colspan="4"><b>Received by:</b>_____________________</td>
                </tr>
                 <tr>
                    <td align="center" colspan="4">Signature over printed name</td>
                    <td align="left" colspan="2"><br></td>
                    <td align="left" colspan="2"><br></td>
                    <td align="center" colspan="4">Signature over printed name</td>
                </tr>
                <tr>
                    <td align="center" colspan="4"><br><br>Date:_______________</td>
                    <td align="left" colspan="2"><br><br></td>
                    <td align="left" colspan="2"><br></td>
                    <td align="center" colspan="4"><br><br>Date:_______________</td>
                </tr>
            </table>
            </div>';

$data['orientation']    =   "P";
$data['pagetype']       =   "letter";
$data['title']          =   "Material and Supplies Issuance Slip - Control No." . $summary_issuance[0]['control_number'];
$data['control_number']     =   "Transaction Code No." .$summary_issuance[0]['id'];
$copy_for1 = "(Warehouse Copy)<hr>";
$copy_for2 = "(Accounting Copy)<hr>";
$copy_for3 = "(Receiving Personnel Copy)<hr>";
$data['content'][0]     =   $content2.$copy_for1;
$data['content'][1]     =   $content2.$copy_for2;
$data['content'][2]     =   $content2.$copy_for3;

$this->load->view('pdf-container.php',$data);
?>