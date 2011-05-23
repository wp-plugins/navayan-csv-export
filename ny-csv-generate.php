<?php

ob_clean();

function fn_ny_csv_gen($table_name){
    global $wpdb;
    $req_table = isset($_REQUEST['nycsv']) ? $_REQUEST['nycsv'] : '';
    
    if($req_table){

        $field='';
        $getfield ='';

        $result = $wpdb->get_results("SELECT * FROM $req_table");      

        $r1 = mysql_query("SELECT * FROM ".$req_table);
        $fields_num = mysql_num_fields($r1);
        
        for($i=0; $i<$fields_num; $i++){
            $field = mysql_fetch_field($r1);
            $field = (object) $field;         
            $getfield .= $field -> name.',';
            $fieldname = $field -> name;
        }

        $sub = substr_replace($getfield, '', -1);
		$each_field = explode(',', $sub);

		foreach($result as $row){
			for($s = 0; $s < $fields_num; $s++){
				if($s == 0) $fields .= "\n";	  
				$fields .= escapeCSV($row -> $each_field[$s]);	  
			}			
		}

        header("Content-type: text/x-csv");
        header("Content-Transfer-Encoding: binary");
        header("Content-Disposition: attachment; filename=".$req_table.'_'.date('Ymd_His').".csv");
        header("Pragma: no-cache");
        header("Expires: 0");
		
		//echo $sub;
        echo $fields;        
    }
}
?>