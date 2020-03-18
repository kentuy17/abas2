<?php
		
		$arr=array();
		$sql = "SELECT id, item_code, description
        		FROM inventory_items
				WHERE description LIKE '%".mysql_real_escape_string($_GET['chars'])."%'
				ORDER BY description LIMIT 0, 10";   
		//var_dump($sql);exit;
		$db = $this->db->query($sql);		
		$res = $db->result_array();
		//var_dump($res);exit;
		$new_row = array();
		
		foreach($res as $row){		
			
			$new_row['label']=htmlentities(stripslashes($row['description']));
			$new_row['value']=htmlentities(stripslashes($row['id']));
			$row_set[] = $new_row; //build an array
				
		}
		echo json_encode($row_set);		
?>