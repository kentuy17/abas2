<?php

$content =  '<style type="text/css">
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
                            <h1 style="text-align:left">'. $company->name .'</h1>
                            <h3 style="text-align:left">'. $company->address.'</h3>
                            <h3 style="text-align:left">'. $company->telephone_no.'</h3>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>

            <div>
                <table border="0" cellspacing="5">
                    <tr>
                        <td colspan="9" class="btx">MSRS No.</td>
                        <td colspan="1" class="btx" align="left">'.$summary[0]['control_number'].'</td>
                    </tr>
                    <tr>
                        <td colspan="10"><h2>MATERIALS AND SUPPLIES RETURN SLIP<br></h2></td>
                    </tr> 
                   <tr>
                        <td align="left" colspan="7"><b>Department/Vessel: </b>'.$summary[0]['returned_from'].'</td>
                        <td align="left" colspan="3"><b>Date Returned: </b>'.date('m-d-Y', strtotime($summary[0]['return_date'])).'</td>
                    </tr>
                   
                </table><br>';

$content .= '<div>
            <table border="1px" cellpadding="5">
                <thead>
                    <tr bgcolor="#d4d4d4">
                    <td width="20%" align="center"><b>Item Code</b></td>
                    <td width="50%" align="center"><b>Description</b></td>
                    <td width="15%" align="center"><b>Qty</b></td>
                    <td width="15%" align="center"><b>Unit</b></td>
                    </tr>
                </thead>
                <tbody>';

foreach($details as $detail){
  $itemInfo = $this->Inventory_model->getItems($detail['item_id']);
  $qty = ($detail['qty'] != 0 || $detail['qty'] != '' ? $detail['qty'] : 0);
  $content .='<tr>
                <td width="20%" align="center">'.$itemInfo[0]['item_code'].'</td>
                <td width="50%" align="center">'.$itemInfo[0]['description'].", ".$itemInfo[0]['particular'].'</td>
                <td width="15%" align="center">'.$detail['qty'].'</td>
                <td width="15%" align="center">'.$detail['unit'].'</td>
              </tr>';
} 

$content .='</tbody>
            </table></div>';

$content .='<div align="left" class="btx"><b>Returned to Warehouse: </b>'.$summary[0]['return_to'].
             '<br>Remarks:'.$summary[0]['remark'].'</div>';

$content .='<div><br><br><table>
                <tr>
                    <td align="left" colspan="4"><b>Returned by: </b>_____________________<br></td>
                    <td align="left" colspan="2"></td>
                    <td align="left" colspan="2"></td>
                    <td align="left" colspan="4"><b>Approved by: </b>_____________________<br></td>
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
                    <td align="left" colspan="4"><b>Received by:</b>_____________________</td>
                    <td align="left" colspan="2"></td>
                    <td align="left" colspan="2"></td>
                    <td align="left" colspan="4"></td>
                </tr>
                 <tr>
                    <td align="center" colspan="4">Signature over printed name</td>
                    <td align="left" colspan="2"><br></td>
                    <td align="left" colspan="2"><br></td>
                    <td align="center" colspan="4"><br></td>
                </tr>
                <tr>
                    <td align="center" colspan="4"><br><br>Date:_______________</td>
                    <td align="left" colspan="2"><br><br></td>
                    <td align="left" colspan="2"><br></td>
                    <td align="center" colspan="4"><br><br></td>
                </tr>
            </table>
            </div>';

$data['orientation']    =   "P";
$data['pagetype']       =   "letter";
$data['title']          =   "Materials and Supplies Return Slip - Control No." . $summary[0]['control_number'];
$copy_for1 = "(Warehouse Copy)<hr>";
$copy_for2 = "(Accounting Copy)<hr>";
$data['content'][0]     =   $content.$copy_for1;
$data['content'][1]     =   $content.$copy_for2;
$data['control_number']     =   "Transaction Code No." . $summary[0]['id'];
$this->load->view('pdf-container.php',$data);         
?>