<?php
/*
Plugin Name: Navayan CSV Export
Description: Exports wordpress table data in CSV (Comma Separate Value) format with 'table_YYYYMMDD_HHMMSS.csv' file format.
Version: 1.0.7
Usage: Go to plugin's page and export the data in CSV format 
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=amolnw2778@gmail.com&item_name=NavayanCSVExport
Author: Amol Nirmala Waman
Plugin URI: http://blog.navayan.com/navayan-csv-export-easiest-way-to-export-all-wordpress-table-data-to-csv-format/
Author URI: http://www.navayan.com/
*/

define('CONST_NYCSV_LOCATION', 'http://wordpress.org/extend/plugins/navayan-subscribe');
define('CONST_NYCSV_NAME', 'Navayan CSV Export' );
define('CONST_NYCSV_SLUG', 'navayan-csv-export');
define('CONST_NYCSV_DIR', WP_PLUGIN_URL.'/'.CONST_NYCSV_SLUG.'/');	
define('CONST_NYCSV_INFO', 'Export data from following tables to \'table_YYYYMMDD_HHMMSS.csv\' CSV file format' );
define('CONST_NYCSV_URL', 'http://blog.navayan.com/');
define('CONST_NYCSV_DONATE_URL', 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=amolnw2778@gmail.com&item_name='.CONST_NYCSV_NAME );

add_action('admin_menu', 'NYCSV_MENU');
add_action('init', 'NYCSV_EXPORT');

/***************************************************
* PHP REQUIRED VERSION CHECK
* *************************************************/
function NYCSV_PHP( $v ){
	if (version_compare(PHP_VERSION, $v, '<') ) { // '5.3.0'
		_e( '<h3 style="font-weight: 400; padding:8px 14px; margin-right: 20px; background: #FFCFD1; border: 1px solid #FF3F47">'. CONST_NYCSV_NAME . ' requires PHP 5.3 or newer! Your PHP version is: ' . PHP_VERSION . '. Please ask your host provider to update it</h3>' );
	}
}

/***************************************************
* PLUGIN'S LINK UNDER 'TOOLS' MENU
* *************************************************/
function NYCSV_MENU() {
	if (function_exists('add_options_page')) {
		add_management_page( __( CONST_NYCSV_NAME, CONST_NYCSV_SLUG), __( CONST_NYCSV_NAME, CONST_NYCSV_SLUG), 'manage_options', CONST_NYCSV_SLUG, 'NYCSV_UI');
	}
}

/***************************************************
* GENERATE UI
* *************************************************/
function NYCSV_UI() {
	wp_enqueue_style( 'nycsv', CONST_NYCSV_DIR . 'ny-csv-ui.css', '', '1.0.6', 'screen' );
?>
	<div id="wrapper">
		<div class="titlebg" id="plugin_title">
			<span class="head i_mange_coupon"><h1><?php echo CONST_NYCSV_NAME;?></h1></span>
		</div>
		<div id="page">
			<p><?php _e(CONST_NYCSV_INFO);?></p>
			<p>
				<a href="<?php echo CONST_NYCSV_URL; ?>navayan-csv-export-easiest-way-to-export-all-wordpress-table-data-to-csv-format/" target="_blank"><?php _e("Plugin's Homepage");?></a> &nbsp; &nbsp;
				<a href="<?php echo CONST_NYCSV_URL; ?>wordpress/" target="_blank"><?php _e('Similar Topics');?></a> &nbsp; &nbsp; 
				<a href="<?php echo CONST_NYCSV_DONATE_URL; ?>" target="_blank"><?php _e('Make a donation');?></a> &nbsp; &nbsp; 
				<a href="<?php echo CONST_NYCSV_LOCATION; ?>" target="_blank"><?php _e('Rate this plugin');?></a>
			</p>
			<?php NYCSV_PHP( '5.3' );?>
			
			<div id="nycsvColumns">
				<div id="nycsvColumn1">
					<table cellspacing="0" class="wp-list-table widefat">
						<thead>
							<tr>
								<th>Sr.</th>
								<th>Export Tables to CSV</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$p = 0;
								$allTables = mysql_query("SHOW TABLES");
								while ($table = mysql_fetch_assoc($allTables)){
									$p = $p + 1;
									foreach ($table AS $tableName){
							?>
							<tr>
								<td><?php _e($p); ?></td>
								<td><a class="button" href="tools.php?page=<?php echo CONST_NYCSV_SLUG;?>&nycsv=<?php echo $tableName;?>">Export &nbsp; <strong><?php echo $tableName;?></strong></a></td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

<?php
}


/***************************************************
* PROMPT TO OPEN/SAVE EXPORTED DATA AS .CSV FILE
* *************************************************/
function NYCSV_EXPORT(){
	$getTable = isset($_REQUEST['nycsv']) ? $_REQUEST['nycsv'] : '';
	if ($getTable){          
		echo NYCSV_GENERATE($getTable);
		exit;
	}
}

/***************************************************
* CONVERT TABLE DATA INTO CSV FORMAT
* *************************************************/
function NYCSV_GENERATE($getTable){
	ob_clean();
	global $wpdb;
	$field='';
	$getField ='';
	
	if($getTable){
		$result = $wpdb->get_results("SELECT * FROM $getTable");
		$requestedTable = mysql_query("SELECT * FROM ".$getTable);
		$fieldsCount = mysql_num_fields($requestedTable);
		
		for($i=0; $i<$fieldsCount; $i++){
			$field = mysql_fetch_field($requestedTable);
			$field = (object) $field;         
			$getField .= $field->name.',';
		}

		$sub = substr_replace($getField, '', -1);
		$fields = $sub; # GET FIELDS NAME
		$each_field = explode(',', $sub);		
		$csv_file_name = $getTable.'_'.date('Ymd_His').'.csv'; # CSV FILE NAME WILL BE table_name_yyyymmdd_hhmmss.csv
		
		# GET FIELDS VALUES WITH LAST COMMA EXCLUDED
		foreach($result as $row){
			for($j = 0; $j < $fieldsCount; $j++){
				if($j == 0) $fields .= "\n"; # FORCE NEW LINE IF LOOP COMPLETE
				$value = str_replace(array("\n", "\n\r", "\r\n", "\r"), "\t", $row->$each_field[$j]); # REPLACE NEW LINE WITH TAB
				$value = str_getcsv ( $value , ",", "\"" , "\\"); # SEQUENCING DATA IN CSV FORMAT, REQUIRED PHP >= 5.3.0
				$fields .= $value[0].','; # SEPARATING FIELDS WITH COMMA
			}			
			$fields = substr_replace($fields, '', -1); # REMOVE EXTRA SPACE AT STRING END
		}
		
		header("Content-type: text/x-csv"); # DECLARING FILE TYPE
		header("Content-Transfer-Encoding: binary");
		header("Content-Disposition: attachment; filename=".$csv_file_name); # EXPORT GENERATED CSV FILE
		header("Pragma: no-cache");
		header("Expires: 0");

		return $fields;
  }
}


?>