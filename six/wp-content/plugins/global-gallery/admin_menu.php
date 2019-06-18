<?php
// declaring menu, custom post type and taxonomy

///////////////////////////////////
// SETTINGS PAGE

function gg_settings_page() {	
	add_submenu_page('edit.php?post_type=gg_galleries', __('Collections', 'gg_ml'), __('Collections', 'gg_ml'), 'publish_posts', 'gg_collections', 'gg_collections');
	add_submenu_page('edit.php?post_type=gg_galleries', __('Settings', 'gg_ml'), __('Settings', 'gg_ml'), 'install_plugins', 'gg_settings', 'gg_settings');	
}
add_action('admin_menu', 'gg_settings_page');


function gg_collections() {
	include_once(GG_DIR . '/collections_manager.php');	
}
function gg_settings() {
	include_once(GG_DIR . '/settings.php');	
}


///////////////////////////////////////
// GALLERY CUSTOM POST TYPE & TAXONOMY

add_action( 'init', 'register_cpt_gg_gallery' );
function register_cpt_gg_gallery() {

    $labels = array( 
        'name' => __( 'Galleries', 'gg_ml'),
        'singular_name' => __( 'Gallery', 'gg_ml'),
        'add_new' => __( 'Add New Gallery', 'gg_ml'),
        'add_new_item' => __( 'Add New Gallery', 'gg_ml'),
        'edit_item' => __( 'Edit Gallery', 'gg_ml'),
        'new_item' => __( 'New Gallery', 'gg_ml'),
        'view_item' => __( 'View Gallery', 'gg_ml'),
        'search_items' => __( 'Search Galleries', 'gg_ml'),
        'not_found' => __( 'No galleries found', 'gg_galleries' ),
        'not_found_in_trash' => __( 'No galleries found in Trash', 'gg_ml'),
        'parent_item_colon' => __( 'Parent Gallery:', 'gg_ml'),
        'menu_name' => __( 'Global Gallery', 'gg_ml'),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'supports' => array( 'title', 'thumbnail' ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 100,
		'menu_icon' => GG_URL . '/img/gg_logo_menu.png',
        'show_in_nav_menus' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => false,
        'capability_type' => 'post'
    );
    register_post_type( 'gg_galleries', $args );
	
	//////
	
	$labels = array( 
        'name' => __( 'Gallery Categories', 'gg_ml'),
        'singular_name' => __( 'Gallery Category', 'gg_ml' ),
        'search_items' => __( 'Search Gallery Categories', 'gg_ml' ),
        'popular_items' => NULL,
        'all_items' => __( 'All Gallery Categories', 'gg_ml' ),
        'parent_item' => __( 'Parent Gallery Category', 'gg_ml' ),
        'parent_item_colon' => __( 'Parent Gallery Category:', 'gg_ml' ),
        'edit_item' => __( 'Edit Gallery Category', 'gg_ml' ),
        'update_item' => __( 'Update Gallery Category', 'gg_ml' ),
        'add_new_item' => __( 'Add New Gallery Category', 'gg_ml' ),
        'new_item_name' => __( 'New Gallery Category', 'gg_ml' ),
        'separate_items_with_commas' => __( 'Separate item categories with commas', 'gg_ml' ),
        'add_or_remove_items' => __( 'Add or remove Gallery Categories', 'gg_ml' ),
        'choose_from_most_used' => __( 'Choose from most used Gallery Categories', 'gg_ml' ),
        'menu_name' => __( 'Gallery Categories', 'gg_ml' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
        'show_tagcloud' => false,
        'hierarchical' => true,
        'rewrite' => false,
        'query_var' => true
    );
    register_taxonomy( 'gg_gall_categories', array('gg_galleries'), $args );
}


////////////////////////
// COLLECTIONS TAXONOMY

add_action( 'init', 'register_taxonomy_gg_collections' );
function register_taxonomy_gg_collections() {
	
    $labels = array( 
        'name' => __( 'Collections', 'gg_ml'),
        'singular_name' => __( 'Collection', 'gg_ml'),
        'search_items' => __( 'Search Collections', 'gg_ml'),
        'popular_items' => __( 'Popular Collections', 'gg_ml'),
        'all_items' => __( 'All Collections', 'gg_ml'),
        'parent_item' => __( 'Parent Collection', 'gg_ml'),
        'parent_item_colon' => __( 'Parent Collection:', 'gg_ml'),
        'edit_item' => __( 'Edit Collection', 'gg_ml'),
        'update_item' => __( 'Update Collection', 'gg_ml'),
        'add_new_item' => __( 'Add New Collection', 'gg_ml'),
        'new_item_name' => __( 'New Collection', 'gg_ml'),
        'separate_items_with_commas' => __( 'Separate grids with commas', 'gg_ml'),
        'add_or_remove_items' => __( 'Add or remove Collections', 'gg_ml'),
        'choose_from_most_used' => __( 'Choose from most used Collections', 'gg_ml'),
        'menu_name' => __( 'Collections', 'gg_ml'),
    );

    $args = array( 
        'labels' => $labels,
        'public' => false,
        'show_in_nav_menus' => false,
        'show_ui' => false,
        'show_tagcloud' => false,
        'hierarchical' => false,
        'rewrite' => false,
        'query_var' => true
    );

    register_taxonomy('gg_collections', null, $args);
}



//////////////////////////////
// VIEW CUSTOMIZATORS

function gg_updated_messages( $messages ) {
  global $post;

  $messages['gg_galleries'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => __('Gallery updated', 'gg_ml'),
    2 => __('Gallery updated', 'gg_ml'),
    3 => __('Gallery deleted', 'gg_ml'),
    4 => __('Gallery updated', 'gg_ml'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Gallery restored to revision from %s', 'gg_ml'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => __('Gallery published', 'gg_ml'),
    7 => __('Gallery saved', 'gg_ml'),
    8 => __('Gallery submitted', 'gg_ml'),
    9 => sprintf( __('Gallery scheduled for: <strong>%1$s</strong>', 'gg_ml'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ))),
    10 => __('Gallery draft updated', 'gg_ml'),
  );

  return $messages;
}
add_filter('post_updated_messages', 'gg_updated_messages');



// edit submitbox - hide minor submit minor-publishing
add_action('admin_head', 'gg_galleries_custom_submitbox');
function gg_galleries_custom_submitbox() {
	global $post_type;

    if ($post_type == 'gg_galleries') {
		echo '<style type="text/css">
		#minor-publishing {
			display: none;	
		}
		#lcwp_slider_opt_box > .inside {
			padding: 0;	
		}
		#lcwp_slider_creator_box {
			background: none;
			border: none;	
		}
		#lcwp_slider_creator_box > .handlediv {
			display: none;	
		}
		#lcwp_slider_creator_box > h3.hndle {
			background: none;
			border: none;
			padding: 12px 0 6px 0;	
			font-size: 18px;
			border-radius: 0px 0px 0px 0px;
		}
		#add_slide {
			float: left;
			margin-top: -36px;
			margin-left: 132px;
			cursor: pointer;	
		}
		.slide_form_table {
			width: 100%;	
		}
		.slide_form_table td {
			vertical-align: top;	
		}
		.second_col {
			width: 50%;
			border-left: 1px solid #ccc; 
			padding-left: 30px;
		}
		</style>';
	}
}


// customize the grid items custom post type table
add_filter('manage_edit-gg_galleries_columns', 'gg_edit_pt_table_head', 10, 2);
function gg_edit_pt_table_head($columns) {
	$new_cols = array();
	
	$new_cols['cb'] = '<input type="checkbox" />';
	$new_cols['gid'] = 'ID';
	$new_cols['title'] = __('Title', 'column name');
	
	$new_cols['gg_type'] = __('Source', 'gg_ml');
	$new_cols['gg_layout'] = __('Layout', 'gg_ml');
	$new_cols['gg_pag'] = __('Pagination', 'gg_ml');
	$new_cols['gg_autopop'] = __('Auto Population', 'gg_ml');
	$new_cols['gg_img_num'] = __('Images', 'gg_ml');
	$new_cols['date'] = __('Date', 'column name');
	$new_cols['gg_preview'] = '';
	
	return $new_cols;
}

add_action('manage_gg_galleries_posts_custom_column', 'gg_edit_pt_table_body', 10, 2);
function gg_edit_pt_table_body($column_name, $id) {
	require_once(GG_DIR . '/functions.php');
	$type = get_post_meta($id, 'gg_type', true);
	$autopop = get_post_meta($id, 'gg_autopop', true);
	$layout = get_post_meta($id, 'gg_layout', true);
	$paginate = get_post_meta($id, 'gg_paginate', true);
	$watermark = get_post_meta($id, 'gg_watermark', true);
	
	$images = gg_gall_data_get($id, $autopop);

	switch ($column_name) {
		case 'gg_type' : 
			$type = gg_types( get_post_meta($id, 'gg_type', true) );		
			echo (is_array($type)) ? '' : $type;
			break;
		
		case 'gid' : echo $id;
			break;
		
		case 'gg_layout' : echo ($layout == 'string') ? 'PhotoString' : ucfirst($layout);
			break;
			
		case 'gg_pag' :
			if($paginate == '1') {_e('Yes');}
			elseif($paginate == '0') {_e('No');}
			else { echo 'Default'; }
			break;
		
		case 'gg_autopop' : echo ($autopop != '1') ? '' : '&#x2713;';
			break;
		
		case 'gg_img_num' : echo (!is_array($images)) ? '0' : count($images);
			break;
		
		case 'gg_preview' : 
			if(!is_array($images) || count($images) == 0) {echo '';}
			else {
				$to_display = array();
				for($a=0; $a<=4; $a++) {
					if(isset($images[$a])) { 
						if($autopop) {$images[$a]['thumb'] = 'c';}
						$img_src =  gg_img_src_on_type($images[$a]['img_src'], $type);
						
						$to_display[] = '<img src="'. gg_thumb_src($img_src, $width = 55, $height = 55, 80,$images[$a]['thumb']) .'" height="55" width="55"/>';
					}
					else {$to_display[] = '';}
				}
				echo '
				<table class="gg_gal_list_preview">
				  <tr><td>'.$to_display[0].'</td><td>'.$to_display[1].'</td></tr>
				  <tr><td>'.$to_display[2].'</td><td>'.$to_display[3].'</td></tr>
				</table>
				';
			}
			break;

		default:
			break;
	}
	return true;
}
