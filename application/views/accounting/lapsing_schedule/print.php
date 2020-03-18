<?php
$created_by	=	"<u>".$lapsing_schedule->created_by."<br><br>Date:".date('j F Y',strtotime($lapsing_schedule->created_on))."</u>";
$modified_by	=	"<u>".$lapsing_schedule->modified_by."</u><br><br>Date:<u>".date('j F Y',strtotime($lapsing_schedule->modified_by))."</u>";
$content =  '
			<style type="text/css">
				 h1 { font-size:200%;text-align:center; }
				 h2 { font-size:150%;text-align:center; }	
				 h3 { font-size:100%;text-align:center; }
				 h5 { border-bottom: double 3px; }
				 td {font-size:160%;}
				 th { font-weight:bold;font-size:150%;text-align:left;background-color:#ddd}
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
			<br>
		    <div>
				<table>
					<tr>
						<td><img src="'. PDF_LINK .'assets/images/AvegaLogo.jpg" alt="Avega_Logo"></td>
						<td colspan="4">
							<h1 class="text-center">'. $lapsing_schedule->company_name .'</h1>
			    			<h3 class="text-center">'. $lapsing_schedule->company_address.'</h3>
			    			<h3 class="text-center">'. $lapsing_schedule->company_contact.'</h3>
						</td>
						<td></td>
					</tr>
				</table>
			</div>
			<div>
				<h1 class="text-center" style="font-size:250%;">Lapsing Schedule</h1>
				<h2 class="text-center">For the Year Ended: '. $lapsing_schedule->year.'</h2>
			</div>';  

$content .= '<table border="1">';
			 $grandtotal_begin_accumulated=0;
             $grandtotal_begin_netbook=0;
             $grandtotal_jan=0;
             $grandtotal_feb=0;
             $grandtotal_mar=0;
             $grandtotal_apr=0;
             $grandtotal_may=0;
             $grandtotal_jun=0;
             $grandtotal_jul=0;
             $grandtotal_aug=0;
             $grandtotal_sep=0;
             $grandtotal_oct=0;
             $grandtotal_nov=0;
             $grandtotal_dec=0;
             $grandtotal_end_accumulated=0;
             $grandtotal_end_netbook=0;
			foreach($categories as $category){
				$xx =0;
				 foreach($lapsing_schedule_details as $asset){
	                if($category['category']==$asset->category){
	                	$xx++;
	                }
	             }
             	if($xx>0){
					$content .= '<tr>
									<th colspan="27">'.$category['category'].'</th>
								</tr>
				                <tr>
				                  <th>#</th>
				                  <th>Fixed Asset Code</th>
				                  <th>Asset Description</th>
				                  <th>Department</th>
				                  <th>Date Acquired</th>
				                  <th>Total Cost</th>
				                  <th>Salvage Value</th>
				                  <th>Depreciable Amount</th>
				                  <th>Useful Life</th>
				                  <th>Annual Depreciation</th>
				                  <th>Monthly Depreciation</th>
				                  <th>BEG - Accum. Depreciation</th>
				                  <th>BEG -Net Book Value</th>
				                  <th>Jan</th>
				                  <th>Feb</th>
				                  <th>Mar</th>
				                  <th>Apr</th>
				                  <th>May</th>
				                  <th>Jun</th>
				                  <th>Jul</th>
				                  <th>Aug</th>
				                  <th>Sep</th>
				                  <th>Oct</th>
				                  <th>Nov</th>
				                  <th>Dec</th>
				                  <th>END - Accum. Depreciation</th>
				                  <th>END -Net Book Value</th>';
					 $content .= '</tr>';
	                 $ctr=1;
	                 $subtotal_begin_accumulated=0;
	                 $subtotal_begin_netbook=0;
	                 $subtotal_jan=0;
	                 $subtotal_feb=0;
	                 $subtotal_mar=0;
	                 $subtotal_apr=0;
	                 $subtotal_may=0;
	                 $subtotal_jun=0;
	                 $subtotal_jul=0;
	                 $subtotal_aug=0;
	                 $subtotal_sep=0;
	                 $subtotal_oct=0;
	                 $subtotal_nov=0;
	                 $subtotal_dec=0;
	                 $subtotal_end_accumulated=0;
	                 $subtotal_end_netbook=0;
	                 $grandtotal =0;
		                foreach($lapsing_schedule_details as $asset){
		                	if($category['category']==$asset->category){
			                	$content .= "<tr>";
			                	$content .= "<td>".$ctr."</td>";
			                	$content .= "<td>".$asset->asset_code."</td>";
			                  	$content .= "<td>".$asset->item_name. ", " . $asset->item_particular."</td>";
			                  	$content .= "<td>".$asset->department."</td>";
			                  	$content .= "<td>".$asset->date_acquired."</td>";
			                  	$content .= "<td>".number_format($asset->total_cost,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->salvage_value,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->depreciable_amount,2,'.',',')."</td>";
			                  	$content .= "<td>".$asset->useful_life."</td>";
			                  	$content .= "<td>".number_format($asset->annual_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->monthly_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->begin_accumulated_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->begin_net_book_value,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->january_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->february_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->march_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->april_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->may_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->june_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->july_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->august_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->september_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->october_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->november_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->december_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->end_accumulated_depreciation,2,'.',',')."</td>";
			                  	$content .= "<td>".number_format($asset->end_net_book_value,2,'.',',')."</td></tr>";
			                  	$ctr++;
			                  	 $subtotal_begin_accumulated=$subtotal_begin_accumulated+$asset->begin_accumulated_depreciation;
				                 $subtotal_begin_netbook=$subtotal_begin_netbook+$asset->begin_net_book_value;
				                 $subtotal_jan=$subtotal_jan+$asset->january_depreciation;
				                 $subtotal_feb=$subtotal_feb+$asset->february_depreciation;
				                 $subtotal_mar=$subtotal_mar+$asset->march_depreciation;
				                 $subtotal_apr=$subtotal_apr+$asset->april_depreciation;
				                 $subtotal_may= $subtotal_may+$asset->may_depreciation;
				                 $subtotal_jun= $subtotal_jun+$asset->june_depreciation;
				                 $subtotal_jul=$subtotal_jul+$asset->july_depreciation;
				                 $subtotal_aug=$subtotal_aug+$asset->august_depreciation;
				                 $subtotal_sep=$subtotal_sep+$asset->september_depreciation;
				                 $subtotal_oct=$subtotal_oct+$asset->october_depreciation;
				                 $subtotal_nov=$subtotal_nov+$asset->november_depreciation;
				                 $subtotal_dec=$subtotal_dec+$asset->december_depreciation;
				                 $subtotal_end_accumulated=$subtotal_end_accumulated+$asset->end_accumulated_depreciation;
				                 $subtotal_end_netbook=$subtotal_end_netbook+$asset->end_net_book_value;
			                }
		                }
		                $content .= '<tr><td colspan="11" style="text-align:right">Sub-total</td>';
			                $content .= '<td>'.number_format($subtotal_begin_accumulated,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_begin_netbook,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_jan,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_feb,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_mar,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_apr,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_may,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_jun,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_jul,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_aug,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_sep,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_oct,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_nov,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_dec,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_end_accumulated,2,'.',',').'</td>';
			                $content .= '<td>'.number_format($subtotal_end_netbook,2,'.',',').'</td>';
		                $content .= '</tr>';
		              
				  		 $grandtotal_begin_accumulated=$grandtotal_begin_accumulated+$subtotal_begin_accumulated;
		                 $grandtotal_begin_netbook=$grandtotal_begin_netbook+$subtotal_begin_netbook;
		                 $grandtotal_jan=$grandtotal_jan+$subtotal_jan;
		                 $grandtotal_feb=$grandtotal_feb+$subtotal_feb;
		                 $grandtotal_mar=$grandtotal_mar+$subtotal_mar;
		                 $grandtotal_apr=$grandtotal_apr+$subtotal_apr;
		                 $grandtotal_may= $grandtotal_may+$subtotal_may;
		                 $grandtotal_jun= $grandtotal_jun+$subtotal_jun;
		                 $grandtotal_jul=$grandtotal_jul+$subtotal_jul;
		                 $grandtotal_aug=$grandtotal_aug+$subtotal_aug;
		                 $grandtotal_sep=$grandtotal_sep+$subtotal_sep;
		                 $grandtotal_oct=$grandtotal_oct+$subtotal_oct;
		                 $grandtotal_nov=$grandtotal_nov+$subtotal_nov;
		                 $grandtotal_dec=$grandtotal_dec+$subtotal_dec;
		                 $grandtotal_end_accumulated=$grandtotal_end_accumulated+$subtotal_end_accumulated;
		                 $grandtotal_end_netbook=$grandtotal_end_netbook+$subtotal_end_netbook;
		  		}
  			}
  		
  			 $content .= '<tr>';
  			    $content .='<td colspan="11" style="text-align:right"><b>Grand Total</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_begin_accumulated,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_begin_netbook,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_jan,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_feb,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_mar,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_apr,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_may,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_jun,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_jul,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_aug,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_sep,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_oct,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_nov,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_dec,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_end_accumulated,2,'.',',').'</b></td>';
                $content .= '<td><b>'.number_format($grandtotal_end_netbook,2,'.',',').'</b></td>';
            $content .= '</tr>';
             $content .='</table>';

$data['orientation']		=	"L";
$data['pagetype']			=	"legal";
$data['title']				=	"Lapsing Schedule - Control No." . $lapsing_schedule->control_number;
$data['content']			=	$content;
$data['control_number']		=	"Transaction Code No." . $lapsing_schedule->id;
$this->load->view('pdf-container.php',$data);
?>