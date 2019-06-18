<?php
/* 
Plugin Name: Global Gallery
Plugin URI: http://www.lcweb.it/global-gallery
Description: Display photos in your website easily and with style. Catch images from socials or use WP images. Finally design your galleries, choose the lightbox and protect them with watermark.
Author: Luca Montanari
Version: 5.112
Author URI: http://www.lcweb.it
*/  


/////////////////////////////////////////////
/////// MAIN DEFINES ////////////////////////
/////////////////////////////////////////////

// plugin path
$wp_plugin_dir = substr(plugin_dir_path(__FILE__), 0, -1);
define('GG_DIR', $wp_plugin_dir);

// plugin url
$wp_plugin_url = substr(plugin_dir_url(__FILE__), 0, -1);
define('GG_URL', $wp_plugin_url);


// timthumb url - also for MU
if(is_multisite()){ define('GG_TT_URL', GG_URL . '/classes/timthumb_MU.php'); }
else { define('GG_TT_URL', GG_URL . '/classes/timthumb.php'); }


// Global Gallery albums basepath
$path = $wp_plugin_dir . '/albums';
define('GGA_DIR', $path);

// Global Gallery albums baseurl
$url = $wp_plugin_url . '/albums';
define('GGA_URL', $url);


// plugin version
define('GG_VER', 5.112);



/////////////////////////////////////////////
/////// MULTILANGUAGE SUPPORT ///////////////
/////////////////////////////////////////////

function gg_multilanguage() {
  $param_array = explode(DIRECTORY_SEPARATOR, GG_DIR);
  $folder_name = end($param_array);
  
  if(is_admin()) {
	 load_plugin_textdomain( 'gg_ml', false, $folder_name . '/lang_admin');  
  }
  else {
	 load_plugin_textdomain( 'gg_ml', false, $folder_name . '/languages');  
  }
}
add_action('init', 'gg_multilanguage', 1);



/////////////////////////////////////////////
/////// MAIN SCRIPT & CSS INCLUDES //////////
/////////////////////////////////////////////

// check for jQuery UI slider
function gg_register_scripts() {
    global $wp_scripts;
    if( !is_object( $wp_scripts ) ) {return;}
	
    if( !isset( $wp_scripts->registered['jquery-ui-slider'] ) ) {
		wp_register_script('lcwp-jquery-ui-slider', GG_URL.'/js/jquery.ui.slider.min.js', 999, '1.8.16', true);
		wp_enqueue_script('lcwp-jquery-ui-slider');
	}
	else {wp_enqueue_script('jquery-ui-slider');}
 
	return true;
}


// global script enqueuing
function gg_admin_scripts() {
	gg_register_scripts();
	wp_enqueue_style('gg_admin', GG_URL . '/css/admin.css', 999, GG_VER);
	
	// chosen
	wp_enqueue_style( 'lcwp-chosen-style', GG_URL.'/js/chosen/chosen.css', 999);
	
	// lcweb switch
	wp_enqueue_style( 'lc-switch', GG_URL.'/js/lc-switch/lc_switch.css', 999);
	
	// LCWP jQuery ui
	wp_enqueue_style( 'lcwp-ui-theme', GG_URL.'/css/ui-wp-theme/jquery-ui-1.8.17.custom.css', 999);
	
	// colorpicker
	wp_enqueue_style( 'gg-colpick', GG_URL.'/js/colpick/css/colpick.css', 999);
	
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-tabs' );
	
	// lightbox and thickbox
	
	if(function_exists('wp_enqueue_media')) {
		wp_enqueue_media();	
	}
	
	wp_enqueue_style('thickbox');
	wp_enqueue_script('thickbox');
}
add_action('admin_enqueue_scripts', 'gg_admin_scripts');


function gg_global_scripts() {
	wp_enqueue_script('jquery');

	// force latest fontawesome version
	wp_dequeue_style('fontawesome');
	wp_enqueue_style('fontawesome', GG_URL . '/css/font-awesome/css/font-awesome.min.css', 999, '4.3.0');
	
	if(!is_admin()) {
		// frontent JS on header or footer
		if(get_option('gg_js_head') != '1') {
			wp_enqueue_script('gg-frontend-js', GG_URL . '/js/frontend.js', 999, GG_VER, true);
		}
		else { wp_enqueue_script('gg-frontend-js', GG_URL . '/js/frontend.js', 99, GG_VER); }
		
		
		// frontend css
		if(!get_option('gg_inline_css') && !get_option('gg_force_inline_css')) {
			wp_enqueue_style('gg-custom-css', GG_URL. '/css/custom.css', 100, GG_VER);	
		}
		else {add_action('wp_head', 'gg_inline_css', 999);}
	}
}
add_action('wp_enqueue_scripts', 'gg_global_scripts', 900);



// USE FRONTEND CSS INLINE
function gg_inline_css(){
	echo '<style type="text/css">';
	require_once(GG_DIR.'/frontend_css.php');
	echo '</style>';
}



/////////////////////////////////////////////
/////// MAIN INCLUDES ///////////////////////
/////////////////////////////////////////////

// admin menu and cpt and taxonomy
include_once(GG_DIR . '/admin_menu.php');

// gallery taxonomy options
include_once(GG_DIR . '/taxonomy_options.php');

// connection hub taxonomy
include_once(GG_DIR . '/connect_hub_tax.php');

// gallery builder
include_once(GG_DIR . '/gallery_builder.php');

// shortcode
include_once(GG_DIR . '/shortcodes.php');

// wp galleries management
include_once(GG_DIR . '/wp_gallery_manag.php');

// tinymce btn
include_once(GG_DIR . '/tinymce_btn.php');

// admin ajax
include_once(GG_DIR . '/admin_ajax.php');

// frontend  ajax
include_once(GG_DIR . '/front_ajax.php');

// dynamic footer javascript
include_once(GG_DIR . '/dynamic_js.php');

// gallery previews
include_once(GG_DIR . '/gallery_preview.php');

// lightboxes switch
include_once(GG_DIR . '/lightboxes.php');



// visual composer integration
include_once(GG_DIR . '/builders_integration/visual_composer.php');

// cornerstone integration
include_once(GG_DIR . '/builders_integration/cornerstone.php');




////////////
// EASY WP THUMBS + forcing system
function gg_ewpt() {
	if(get_option('gg_ewpt_force')) {
		$_REQUEST['ewpt_force'] = true;
		define('GG_EWPT_URL', GG_URL . '/classes/easy_wp_thumbs_force.php');
	} else {
		define('GG_EWPT_URL', GG_URL . '/classes/easy_wp_thumbs.php');
	}
	
	include_once(GG_DIR . '/classes/easy_wp_thumbs.php');	
}
add_action('init', 'gg_ewpt', 1);
////////////



////////////
// AUTO UPDATE DELIVER
include_once(GG_DIR . '/classes/lc_plugin_auto_updater.php');
function gg_auto_updates() {
	$upd = new lc_wp_autoupdate(__FILE__, 'http://updates.lcweb.it', 'lc_updates', 'gg_init_custom_css', true);
}
add_action('admin_init', 'gg_auto_updates', 1);
////////////



/////////////////////////////////////////////
////// ACTIONS ON PLUGIN ACTIVATION /////////
/////////////////////////////////////////////

function gg_init_custom_css() {
	include_once(GG_DIR . '/functions.php');
	
	// create custom CSS
	if(!gg_create_frontend_css()) {
		if(!get_option('gg_inline_css')) {update_option('gg_inline_css', 1);}
	}
	else {delete_option('gg_inline_css');}
	
	
	// update galleries (for versions < 2.0)
	gg_update_galleries_structure_v2();
	
	// update galleries (for versions < 3.0)
	gg_update_galleries_structure_v3();
	
	// connections creation (for versions < 5.0)
	gg_setup_connections_v5();
}
register_activation_hook(__FILE__, 'gg_init_custom_css');



// update the galleries structure to v2.0
function gg_update_galleries_structure_v2() {
	if(!get_option('gg_v2_update_done')) {
		global $wpdb;
		
		// retrieve all galleries
		$args = array(
			'numberposts' => -1, 
			'post_type' => 'gg_galleries',
		);
		$posts_array = get_posts($args);
		
		if(is_array($posts_array)) {
			foreach($posts_array as $post) {
				$gall_type = get_post_meta($post->ID, 'gg_type', true);
				$autopop = get_post_meta($post->ID, 'gg_autopop', true);
				
				if(!$autopop) { $images = get_post_meta($post->ID, 'gg_gallery', true); }
				else { $images = get_post_meta($post->ID, 'gg_autopop_cache', true); }
				
				if(is_array($images) && count($images) > 0 && !isset($images[0]['img_src'])) {
					
					$new_structure = array();
					foreach($images as $img_data) {
						$temp_data = $img_data;
						
						// retrieve image source
						if($gall_type == 'wp' || $gall_type == 'wp_cat') {
							$query = "SELECT ID FROM ".$wpdb->posts." WHERE guid='".addslashes($temp_data['url'])."'";
							$id = (int)$wpdb->get_var($query);
						
							if(!$id || !is_int($id)) {
								// image not found in the DB - remove from the gallery
								////var_dump($id); die(' error during the galleries database update');
								$temp_data['img_src'] = 'to_remove';
							} 
							else {$temp_data['img_src'] = $id;}
						}
						elseif($gall_type == 'gg_album') {
							$temp_data['img_src'] = str_replace(GG_URL, '', $temp_data['url']);
						}
						else {
							$temp_data['img_src'] = $temp_data['url'];
						}
						
						unset($temp_data['url']);
						if(isset($temp_data['path'])) {unset($temp_data['path']);}
						
						if($temp_data['img_src'] != 'to_remove') {
							$new_structure[] = $temp_data;
						}
					}
					
					// update
					delete_post_meta($post->ID, 'gg_autopop_cache');
					delete_post_meta($post->ID, 'gg_gallery');
					
					if(!$autopop) {
						add_post_meta($post->ID, 'gg_gallery', $new_structure, true);
					} else {
						add_post_meta($post->ID, 'gg_autopop_cache', $new_structure, true);	
					}	
				}
			}
			
			update_option('gg_v2_update_done', 1);
		}
	}
	
	return true;
}


// update the galleries structure to v3.0
function gg_update_galleries_structure_v3() {
	if(!get_option('gg_v3_update_done')) {
		
		// retrieve all galleries
		$args = array(
			'numberposts' => -1, 
			'post_type' => 'gg_galleries',
		);
		$posts_array = get_posts($args);
		
		if(is_array($posts_array)) {
			foreach($posts_array as $post) {
				$gall_type = get_post_meta($post->ID, 'gg_type', true);
				$autopop = get_post_meta($post->ID, 'gg_autopop', true);
				
				if(!$autopop) { $images = get_post_meta($post->ID, 'gg_gallery', true); }
				else { $images = get_post_meta($post->ID, 'gg_autopop_cache', true); }
				
				if(is_array($images) && count($images) > 0) {
					
					$new_structure = array();
					foreach($images as $img_data) {
						$temp_data = $img_data;
						
						// retrieve image source
						if($gall_type == 'gg_album') {
							// remove the /album/ base to be compatible with custom paths
							$temp_data['img_src'] = str_replace('/albums/', '', $temp_data['img_src']);
						}

						$new_structure[] = $temp_data;
					}
					
					// update
					delete_post_meta($post->ID, 'gg_autopop_cache');
					delete_post_meta($post->ID, 'gg_gallery');
					
					if(!$autopop) {
						add_post_meta($post->ID, 'gg_gallery', $new_structure, true);
					} else {
						add_post_meta($post->ID, 'gg_autopop_cache', $new_structure, true);	
					}	
				}
			}
			
			update_option('gg_v3_update_done', 1);
		}
	}
	
	return true;
}


// get galleries data to estup connections for v5.0
function gg_setup_connections_v5() {
	if(!get_option('gg_v5_update_done')) {
		include_once(GG_DIR .'/classes/gg_connections_hub.php');
		
		// retrieve all galleries
		$args = array(
			'numberposts' => -1, 
			'post_type' => 'gg_galleries',
		);
		$posts_array = get_posts($args);
		
		if(is_array($posts_array)) {
			gg_conn_taxonomy(); // be sure taxonomy is registered
			
			foreach($posts_array as $post) {
				$gid = $post->ID;
				
				$ch = new gg_connection_hub($gid);
				if($ch->src == 'g_drive' || !in_array($ch->src, $ch->to_consider)) {continue;}
			
				switch($ch->src) {
					case 'fb' :
						$page_url = get_post_meta($gid, 'gg_username', true);
						
						$ch->ajax_data = array(
							'conn_name'		=> $page_url,
							'fb_src_switch'	=> 'page',
							'fb_page_url' 	=> $page_url
						);
						if($ch->test_connection() !== true) {continue;}
						break;	
					
					
					case 'picasa' :
						$username = get_post_meta($gid, 'gg_username', true);
						
						$ch->ajax_data = array(
							'conn_name'		=> $username,
							'gplus_user'	=> $username
						);
						
						$stored = (array)get_option('gg_gplus_base_tokens_db', array());
						if(!isset($stored[$username])) {
							$force_continue = true;	
						}
						break;	
					

					case 'dropbox' :
						$username = get_post_meta($gid, 'gg_username', true);
						
						$ch->ajax_data = array(
							'conn_name'	=> $username,
							'user_id'	=> $username,
							'token'		=> get_post_meta($gid, 'gg_psw', true)
						);
						break;	
				}
				if(isset($force_continue) && $force_continue) {
					$force_continue = false;
					continue;		
				}
				
				
				// create connection
				if(!empty($ch->ajax_data)) {
					// check against already created connections for this source
					if($term = get_term_by('name', $ch->ajax_data['conn_name'], 'gg_connect_hub')) {
						update_post_meta($gid, 'gg_connect_id', $term->term_id);
					}
					else {
						$result = $ch->save_connection();
						if($result === true) {
							update_post_meta($gid, 'gg_connect_id', $ch->connect_id);	
						}
					}
				}
				$ch->ajax_data = array(); // reset
			}
		}
		
		update_option('gg_v5_update_done', 1);
	}
}


			