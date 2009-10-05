<?php
/*
Plugin Name: Page Cornr for October
Plugin URI: http://www.desperatelyseekingwp.com/2009/10/pink-for-october-plugin/
Description: Adds a page peel in the corner of the site with a pink ribbon and the text "Pink for October"
Version: 1.1.4
Author: Cathy Tibbles
Author URI: http://desperatelyseekingwp.com
*/

	// for version control and installation
	define('pagecornr_VERSION', '1.1.3');

  // detect the plugin path
	$pagecornr_path = get_option('siteurl').'/wp-content/plugins/page-cornr-for-october'; //don't change
	$pagecornr_img_corner = $pagecornr_path.'/corner.png';

  //force WordPress to include jQuery
  add_action( 'wp_print_scripts', 'pagecornr_add_jquery' );
  function pagecornr_add_jquery( ) {
    wp_enqueue_script( 'jquery' );
  }


	function pagecornr_addjs() {
    pagecornr_add_jquery();
		global $pagecornr_path;
		echo '<script type="text/javascript" src="' . $pagecornr_path . '/pagecornr.js"></script>' . "\n";
?>

<!--[if lt IE 7.]>
<style type="text/css">
img, div, a { behavior: url(<?php echo $pagecornr_path.'/iepngfix.htc'; ?>) }
</style>
<![endif]-->
		
<!-- Pagecornr styles -->
<link rel="stylesheet" href="<?php echo $pagecornr_path.'/pagecornr.css'; ?>" type="text/css" media="screen" /> 
<!-- Pagecornr styles end -->

<?php
		}
	
	function pagecornr() {
	global $pagecornr_path, $pagecornr_ad_url, $pagecornr_img_corner, $pagecornr_ad_msg;
?>
	
<div id="pagecornr">
	<a href="http://ww5.komen.org/">
		<img src="<?php echo $pagecornr_img_corner; ?>" alt="<?php echo $pagecornr_ad_msg ?>" />
		<span class="bg_msg"><?php echo $pagecornr_ad_msg ?></span>
	</a>
</div>

<?php
	}
	
	  // try to always get the values from the database
	$pagecornr_version = get_option(pagecornr_version);
	$pagecornr_ad_url = get_option(pagecornr_ad_url);
	$pagecornr_ad_msg = get_option(pagecornr_ad_msg);
	
	// if the database value returns empty use defaults
	if($pagecornr_version != pagecornr_VERSION) 
	{
		$pagecornr_version = pagecornr_VERSION; update_option('pagecornr_version', pagecornr_VERSION);
		$pagecornr_ad_url = 'http://ww5.komen.org'; update_option('pagecornr_ad_url', $pagecornr_ad_url);
		$pagecornr_ad_msg = ''; update_option('pagecornr_ad_msg', $pagecornr_ad_msg);
		
	}
	
	function pagecornr_pages() {
	    add_options_page('Page Cornr Options', 'Page Cornr Options', 5, 'pagecornroptions', 'pagecornr_options');
	}
	
	//print options page
	function pagecornr_options() 
	{
     	global $pagecornr_path, $pagecornr_version, $pagecornr_ad_url, $pageconrnr_ad_msg;
 
     	// if settings are updated
		if(isset($_POST['update_pagecornr'])) 
		{
			if(isset($_POST['pagecornr_ad_url'])) {
				update_option('pagecornr_ad_url', $_POST['pagecornr_ad_url']);
				$pagecornr_ad_url = $_POST['pagecornr_ad_url'];
			}	
			if(isset($_POST['pagecornr_ad_msg'])) {
				update_option('pagecornr_ad_msg', $_POST['pagecornr_ad_msg']);
				$pagecornr_ad_msg = $_POST['pagecornr_ad_msg'];
			}	

		}
		
		// if the user clicks the uninstall button, clean all options and show good-bye message
		if(isset($_POST['uninstall_pagecornr'])) 
		{
			delete_option(pagecornr_ad_url);
			delete_option(pagecornr_ad_msg);
			delete_option(pagecornr_version);
			echo '<div class="wrap"><h2>Good Bye!</h2><p>All Page Cornr settings were removed and you can now go to the <a href="plugins.php">plugin menu</a> and deactivate it.</p><h3>Thank you for using Page Cornr '.$pagecornr_version.'!</h3><p style="text-align:right"><small>if this happend by accident, <a href="options-general.php?page=pagecornroptions">click here</a> to reinstall</small></p></div>';
						
		} 
		else // show the menu
		{
			$pagecornr_version = get_option(pagecornr_version);
			$pagecornr_ad_url = get_option(pagecornr_ad_url);
			$pagecornr_ad_msg = get_option(pagecornr_ad_msg);
			

			// if the pagecornr_version is empty or unequal, 
			// write the defaults to the database
			/*if(trim($pagecornr_version) == '') 
			{
				$pagecornr_version = pagecornr_VERSION;
				$pagecornr_ad_url = 'http://ww5.komen.org';
				$pagecornr_ad_msg = '';
			}//	*/		
			
			echo '<div class="wrap"><h2>Page Cornr Options</h2><small style="display:block;text-align:right">Version: '.$pagecornr_version.'</small><form method="post" action="options-general.php?page=pagecornroptions">';		
			echo '<input type="hidden" name="update_pagecornr" value="true" />';
			
			echo '<table class="form-table">';
			
			echo '<tr valign="top">';
			echo '<th scope="row">Page Cornr Ad URL</th>';
			echo '<td><input type="text" value="'.$pagecornr_ad_url.'" name="pagecornr_ad_url" size="40" /><br/>URL to point to when clicked on Ad (begins with <strong>http://</strong>)</td>';
			echo '</tr>';		
			
			echo '<tr valign="top">';
			echo '<th scope="row">Alternative text Ad</th>';
			echo '<td><input type="text" value="'.$pagecornr_ad_msg.'" name="pagecornr_ad_msg" size="40" /><br/>Alternative text of the Ad</td>';
			echo '</tr>';		
	
			echo '</table>';
			echo '<p class="submit"><input type="submit" name="Submit" value="Update Options &raquo;" /></p>';
			
			echo '</form>';
			
			
			echo '<h2>Uninstall</h2><form method="post" action="options-general.php?page=pagecornroptions">';
			echo '<input type="hidden" name="uninstall_pagecornr" value="true" />';
			echo '<p class="submit"><input type="submit" name="Submit" value="Clear Settings &raquo;" /></p>';		
			echo '</form>';
			
			
			echo '<p>The plugin assumes all files are installed at:<br />'.$pagecornr_path.'/</p></div>';
			
		}
	}

	//add_action('admin_menu', 'pagecornr_pages');
	add_action('wp_head', 'pagecornr_addjs');
	add_action('wp_footer', 'pagecornr');
?>