<h2>Out-turn Summary Aging Report</h2>

<h6>From period of <?php echo date('F j, Y',strtotime($date_from)). " to " . date('F j, Y',strtotime($date_to)); ?></h6>

<div style='overflow-x: auto'>
	<table class="table table-bordered table-striped table-hover">
			<tr>
				<th rowspan='2'>#</th>
				<th rowspan='2'>Out-turn ID</th>
				<th rowspan='2'>Control No.</th>
				<th rowspan='2'>Company</th>
				<th rowspan='2'>Contract Ref. No.</th>
				<th rowspan='2'>Client</th>
				<th rowspan='2'>Service Type</th>
				<th rowspan='2'>Out-Turn Status</th>
				<th rowspan='2'>Unloading/Delivery Date Ended</th>
				<th rowspan='2'>Out-Turn Created On</th>
				<th rowspan='2'>Out-Turn Created By</th>
				<th rowspan='2'>No. of times edited</th>
				<th rowspan='2'>SOA ID</th>
				<th rowspan='2'>SOA Created On</th>
				<th rowspan='2'>SOA Amount</th>
				<th rowspan='2'>SOA Status</th>
				<th rowspan='2'>Date Received By Client</th>
				<th rowspan='2'>GL Posted On</th>
				<th colspan='2'>Aging</th>
			</tr>
			<tr>
				<th>From unload to out-turn</th>
				<th>From out-turn to billing</th>
			</tr>
			<?php
				$table = "";
	 			$sql_os = "SELECT * FROM ops_out_turn_summary WHERE status<>'Cancelled' AND created_on BETWEEN '".$date_from."' AND '".$date_to."'";
				$query_os = $this->db->query($sql_os);
				if($query_os){
					$result_os = $query_os->result();
					$ctr = 1;
					foreach($result_os as $os){
						echo  "<tr>";

							$created_on = $os->created_on;
							$created_by = $this->Abas->getUser($os->created_by);
							$created_by = $created_by['full_name'];
							$company = $this->Abas->getCompany($os->company_id);
							if($os->service_order_id!=0){
								$so = $this->Operation_model->getServiceOrder($os->service_order_id);
								$contract = $so->contract;
							}else{
								$contract =	$this->Abas->getContract($os->service_contract_id);
							}

							if($os->type_of_service=='Trucking' || $os->type_of_service=='Handling'){
								$sql_del = "SELECT * FROM ops_out_turn_summary_deliveries WHERE out_turn_summary_id=".$os->id." ORDER BY delivery_date DESC LIMIT 1";
								$query_del = $this->db->query($sql_del);
								if($query_del){
									$result_del = $query_del->row();
									$unloading_ended = $result_del->delivery_date;
								}
							}else{
								$detail = $this->Operation_model->getOutTurnSummaryDetails($os->id);
								if($detail->unloading_departure!=0){
									$unloading_ended = $detail->unloading_departure;
								}else{
									$unloading_ended = $detail->unloading_ended;
								}
							}

							echo  "<td>".$ctr."</td>";
							echo  "<td>".$os->id."</td>";
							echo  "<td>".$os->control_number."</td>";
							echo  "<td>".$company->name."</td>";
							echo  "<td>".$contract['reference_no']."</td>";
							echo  "<td>".$contract['client']['company']."</td>";
							echo  "<td>".$os->type_of_service."</td>";
							echo  "<td>".$os->status."</td>";
							echo  "<td>".date('F j, Y',strtotime($unloading_ended))."</td>";
							echo  "<td>".date('F j, Y',strtotime($created_on))."</td>";
							echo  "<td>".$created_by."</td>";
							if($os->times_returned_to_draft>0){
								$edited_count = $os->times_returned_to_draft;
							}else{
								$edited_count = 0;
							}
							echo  "<td>".$edited_count."</td>";

							$soa = $this->Operation_model->getSOAbyOutturn($os->id);
							if(isset($soa[0]->id)){
								echo  "<td>".$soa[0]->id."</td>";
								echo  "<td>".date('F j, Y',strtotime($soa[0]->created_on))."</td>";
								$soa_amount = $this->Billing_model->getSOAAmount($soa[0]->type,$soa[0]->id);
								$soa_amount = $soa_amount['grandtotal_tax'];

								echo  "<td>".number_format($soa_amount,2,'.',',')."</td>";
								echo  "<td>".$soa[0]->status."</td>";
								if(isset($soa[0]->sent_to_client_on) && $soa[0]->status<>'Draft'){
									echo  "<td>".date('F j, Y',strtotime($soa[0]->sent_to_client_on))."</td>";
								}else{
									echo  "<td>--</td>";
								}

								$entries = $this->Accounting_model->getTransactionJournalEntriesByReference('statement_of_accounts',$soa[0]->id);
								if(isset($entries[0]['posted_on'])){
									echo  "<td>".date('F j, Y',strtotime($entries[0]['posted_on']))."</td>";
								}else{
									echo  "<td>--</td>";
								}

							}else{
								echo  "<td>--</td>";
								echo  "<td>--</td>";
								echo  "<td>--</td>";
								echo  "<td>--</td>";
								echo  "<td>--</td>";
								echo  "<td>--</td>";
							}		
						
							$os_date = new DateTime($created_on);
							$unload_date = new DateTime($unloading_ended);
							$difference = $unload_date->diff($os_date);
							echo  "<td>".$difference->m." months & ".$difference->d." days</td>";

							if(isset($soa[0]->id)){
								$os_date = new DateTime($created_on);
								$soa_date = new DateTime($soa[0]->created_on);
								$differencex = $os_date->diff($soa_date);
								echo  "<td>".$differencex->m." months & ".$differencex->d." days</td>";
							}else{
								echo  "<td>--</td>";
							}

						$ctr++;
						echo  "</tr>";
					}
				}
			?>
	</table>
</div>