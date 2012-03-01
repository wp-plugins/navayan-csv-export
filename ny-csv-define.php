<?php
	# DEFINES
	define('NY_PLUGIN_CSV_EXPORT_LOCATION', 'http://wordpress.org/extend/plugins/navayan-csv-export');
	define('NY_PLUGIN_CSV_EXPORT_NAME', 'Navayan CSV Export');
	define('NY_PLUGIN_CSV_EXPORT_SLUG', 'navayan-csv-export');
	define('NY_PLUGIN_CSV_EXPORT_DIR', WP_PLUGIN_URL.'/'.NY_PLUGIN_CSV_EXPORT_SLUG.'/');	
	define('NY_PLUGIN_CSV_EXPORT_INFO', 'Export the data of <big>'. get_option('blogname') .'</big> from any table in CSV format');
	define('NY_PLUGIN_CSV_EXPORT_ABOUT', '<a href="'.NY_PLUGIN_CSV_EXPORT_LOCATION.'" target="_blank">'.NY_PLUGIN_CSV_EXPORT_NAME.'</a> is the easist way to exports all your wordpress table data in CSV (Comma Separate Format)');
	define('NY_PLUGIN_AUTHOR', 'Amol Nirmala Waman');
	define('NY_AUTHOR_EMAIL', 'amolnw2778@gmail.com');
	define('NY_PLUGIN_URL', 'http://blog.navayan.com/');
	define('NY_SITE', 'navayan.com');
	define('NY_DONATE_TEXT', 'We call \'Donation\' as \'<strong><em>Dhammadana</em></strong>\'. It will help us to develop more plugins and themes');
	define('AUTHOR_PROFILE', 'http://in.linkedin.com/pub/amol-nirmala-waman/27/b33/b3');
?>
<?php function ny_csv_export_widgets(){ ?>
	<div class="ny-widgets nybox">
		<h3>About</h3>
		<div class="ny-widgets-desc">
			<p id="navayan_logo"><a href="http://www.<?php echo NY_SITE;?>" target="_blank"><img src="http://www.<?php echo NY_SITE;?>/img/navayan-logo.jpg" alt="<?php echo NY_SITE;?>" /></a></p>
			<p><?php echo NY_PLUGIN_CSV_EXPORT_ABOUT;?></p>
			<form method="post" action="https://www.paypal.com/cgi-bin/webscr" style="text-align:center">
				<input type="hidden" name="cmd" value="_donations">
				<input type="hidden" name="business" value="<?php echo NY_AUTHOR_EMAIL;?>">
				<input type="hidden" name="lc" value="US">
				<input type="hidden" name="item_name" value="<?php echo NY_SITE;?>">
				<input type="hidden" name="no_note" value="0">
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			</form>			
			<p><?php echo NY_DONATE_TEXT;?></p>
		</div>
	</div>
	
	<?php
		# Get latest posts
		include_once(ABSPATH . WPINC . '/feed.php');
		// Get a SimplePie feed object from the specified feed source.
		$rss = fetch_feed( NY_PLUGIN_URL . 'feed/' );
		if (!is_wp_error( $rss ) ) { // Checks that the object is created correctly 
			$max = $rss->get_item_quantity(5);
			$rss_items = $rss->get_items(0, $max); 
		}
		if ($max > 0) :
	?>
		<div class="ny-widgets nybox">
			<h3>Recent Posts</h3>
			<div class="ny-widgets-desc">
				<ul>
					<?php
						foreach ( $rss_items as $item ){
							echo "<li><a href='". esc_url( $item->get_permalink() ) ."' target='_blank'>". esc_html( $item->get_title() ) ."</a></li>";
						}
					?>
				</ul>
			</div>
		</div>
	<?php endif; ?>
<?php } ?>