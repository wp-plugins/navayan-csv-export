<?php include_once ('ny-csv-define.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo NY_PLUGIN_CSV_EXPORT_DIR; ?>ny-csv-ui.css" />

<div id="wrapper">
	<div class="titlebg"><span class="head i_mange_coupon"><h1><?php echo NY_PLUGIN_CSV_EXPORT_NAME;?></h1></span></div>
	<div id="page">
		<h4><?php echo NY_PLUGIN_CSV_EXPORT_INFO;?></h4>
		<div class="ny-left">
						
			<div class="ny-left-widgets">				 
				<table border="1" class="widefat" style="width:98%">
					<thead>
						<tr>
							<th class="manage-column">Sr.</th>
							<th class="manage-column">Table Name</th>
							<th class="manage-column">Action</th>
						</tr>
					</thead>
					<tbody>

					<?php
						$p='';
						$alltables = mysql_query("SHOW TABLES");
						while ($table = mysql_fetch_assoc($alltables)){
							$p += sizeof($table);
							foreach ($table AS $tablename){
					?>
						<tr class="<?php if($p%2 != 0) echo 'alternate';?> author-self status-publish iedit">
							<td><?php echo $p; ?></td>
							<td><strong><?php echo $tablename;?></strong></td>
							<td>
								<a href="<?php echo site_url();?>/wp-admin/tools.php?page=export&nycsv=<?php echo $tablename;?>">
									<input type="submit" class="button-secondary action" value="Export to CSV" name="submit"/>
								</a>
							</td>
						</tr>

					<?php } } ?>
          </tbody>
					<tfoot>
						<tr>
							<th class="manage-column" colspan="3">&nbsp;</th>
						</tr>
					</tfoot>
        </table>
			</div>
    </div><!-- .ny-left -->
          
		<div class="ny-right">
			<?php if( function_exists('ny_widgets')) ny_widgets(); ?>
		</div><!-- .ny-right -->
		
	</div>
</div>