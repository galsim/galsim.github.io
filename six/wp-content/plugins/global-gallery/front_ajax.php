<?php

/////////////////////////////////////
////// PAGINATION ///////////////////
/////////////////////////////////////

function gg_pagination() {
	if(isset($_POST['gg_type']) && $_POST['gg_type'] == 'gg_pagination') {
		include_once(GG_DIR . '/functions.php');
		include_once(GG_DIR . '/classes/gg_overlay_manager.php');
		
		if(!isset($_POST['gid']) || !filter_var($_POST['gid'], FILTER_VALIDATE_INT)) {die('Gallery ID is missing');}
		$gid = (int)$_POST['gid'];
		
		if(!isset($_POST['gg_page'])|| !filter_var($_POST['gg_page'], FILTER_VALIDATE_INT)) {die('wrong page number');}
		$page = (int)$_POST['gg_page'];
		
		// overlay
		if(!isset($_POST['gg_ol'])) {die('overlay is missing');}
		$overlay = $_POST['gg_ol'];
		
		// gallery images array 
		if(!isset($_POST['gg_images'])) {die('missing images object');}
		$all_images = gg_img_unserialize_decompress($_POST['gg_images']);
		

		// get the gallery data
		$type = get_post_meta($gid, 'gg_type', true);
		$raw_layout = get_post_meta($gid, 'gg_layout', true);
		$raw_paginate = get_post_meta($gid, 'gg_paginate', true);
		$thumb_q = get_option('gg_thumb_q', 80);
		$per_page = (int)gg_check_default_val($gid, 'gg_per_page', $raw_paginate);
		
		// WP gall pagination fix
		if(!$type) {$type = 'wp_gall';}

		// layout options
		$layout = gg_check_default_val($gid, 'gg_layout', $raw_layout);
		if($layout == 'standard') {
			$thumb_w = gg_check_default_val($gid, 'gg_thumb_w', $raw_layout);
			$thumb_h = gg_check_default_val($gid, 'gg_thumb_h', $raw_layout);
		}
		elseif($layout == 'masonry') { 
			$cols = gg_check_default_val($gid, 'gg_masonry_cols', $raw_layout); 
			(!get_option('gg_masonry_basewidth')) ? $default_w = 960 : $default_w = (int)get_option('gg_masonry_basewidth');
			$col_w = floor( $default_w / $cols );
		}
		else { $row_h = gg_check_default_val($gid, 'gg_photostring_h', $raw_layout); }
		
		
		// autopop vars
		$autopop = get_post_meta($gid, 'gg_autopop', true);
		if($autopop) {
			$show_authors = get_post_meta($gid, 'gg_auto_author', true);
			$show_titles = get_post_meta($gid, 'gg_auto_title', true);
			$show_descr = get_post_meta($gid, 'gg_auto_descr', true);
		}
		
		
		// gallery images offset
		$start = $per_page * ($page - 1);
		$images = array_slice($all_images, $start, count($all_images));	

		// pagination limit
		$tot_images_num = count($images);
		if($tot_images_num > $per_page) {
			$images = array_slice($images, 0, $per_page);
			$more = 1;	
		}
		else {$more = 0;}
		
		// image overlay code 
		$ol_man = new gg_overlay_manager($overlay, false, 'gall');
		
		
		// create new block of gallery HTML
		$gallery = '';
		foreach($images as $img) {
			if($autopop && !$show_titles) {$img['title'] = '';}
			if($autopop && !$show_authors) {$img['author'] = '';}
			if($autopop && !$show_descr) {$img['descr'] = '';}  
			
			if($autopop || !isset($img['thumb'])) {$img['thumb'] = 'c';}
			$thumb_src =  gg_img_src_on_type($img['img_src'], $type);
			
			if(isset($img['wm_path'])) {
				$img_url = $img['wm_url'];	
			} else {
				$img_url = gg_img_url_on_type($img['img_src'], $type);
			}
			
			// image link codes
			if(isset($img['link']) && trim($img['link']) != '') {
				if($img['link_opt'] == 'page') {$thumb_link = get_permalink($img['link']);}
				else {$thumb_link = $img['link'];}
				
				$open_tag = '<div gg-link="'.$thumb_link.'"';
				$add_class = "gg_linked_img";
				$close_tag = '</div>';
			} else {
				$open_tag = '<div';
				$add_class = "";
				$close_tag = '</div>';
			}
			
			// SEO noscript part for full-res image
		  	$noscript = '<noscript><img src="'.$img_url.'" alt="'.gg_sanitize_input($img['title']).'" /></noscript>';
			
			
			/////////////////////////
			// standard layout
			if($layout == 'standard') {	 
				
				$thumb = gg_thumb_src($thumb_src, $thumb_w, $thumb_h, $thumb_q, $img['thumb']);
				$gallery .= '
				'.$open_tag.' gg-url="'.$img_url.'" gg-title="'.gg_sanitize_input($img['title']).'" class="gg_img '.$add_class.'" gg-author="'.gg_sanitize_input($img['author']).'" gg-descr="'.gg_sanitize_input($img['descr']).'" rel="'.$gid.'">
				  <div class="gg_img_inner">';
					
					$gallery .= '
					<div class="gg_main_img_wrap">
						<img src="'.$thumb.'" alt="'.gg_sanitize_input($img['title']).'" class="gg_photo gg_main_thumb" />
						'.$noscript.'
					</div>';	
					
					$gallery .= '
					<div class="gg_overlays">'. $ol_man->get_img_ol($img['title'], $img['descr'], $img_url) .'</div>';	
					
				$gallery .= '</div>' . $close_tag;
			}
			
			
			/////////////////////////
			// masonry layout
			else if($layout == 'masonry') {
				
				$thumb = gg_thumb_src($thumb_src, ($col_w + 40), false, $thumb_q, $img['thumb']);	
				$gallery .= '
				'.$open_tag.' gg-url="'.$img_url.'" class="gg_img '.$add_class.'" gg-title="'.gg_sanitize_input($img['title']).'" gg-author="'.gg_sanitize_input($img['author']).'" gg-descr="'.gg_sanitize_input($img['descr']).'" rel="'.$gid.'">
				  <div class="gg_img_inner">
					<div class="gg_main_img_wrap">
						<img src="'.$thumb.'" alt="'.gg_sanitize_input($img['title']).'" class="gg_photo gg_main_thumb" />	
						'.$noscript.'
					</div>
					<div class="gg_overlays">'. $ol_man->get_img_ol($img['title'], $img['descr'], $img_url) .'</div>	
				</div>'.$close_tag;  
			}
			
			  
			/////////////////////////
			// photostring layout
			else {
	
				$thumb = gg_thumb_src($thumb_src, false, $row_h, $thumb_q, $img['thumb']);
				$gallery .= '
				'.$open_tag.' gg-url="'.$img_url.'" class="gg_img '.$add_class.'" gg-title="'.gg_sanitize_input($img['title']).'" gg-author="'.gg_sanitize_input($img['author']).'" gg-descr="'.gg_sanitize_input($img['descr']).'" rel="'.$gid.'">
				  <div class="gg_img_inner" style="height: '.$row_h.'px;">
					<div class="gg_main_img_wrap">
						<img src="'.$thumb.'" alt="'.gg_sanitize_input($img['title']).'" class="gg_photo gg_main_thumb" />	
						'.$noscript.'
					</div>
					<div class="gg_overlays">'. $ol_man->get_img_ol($img['title'], $img['descr'], $img_url) .'</div>	
				</div>'.$close_tag;  
			}	
		}
		
		$pag = array();
		$pag['html'] = $gallery;
		$pag['more'] = $more;
		
		echo json_encode($pag);
		die();
	}
}
add_action('init', 'gg_pagination');


//////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////
////// LOAD GALLERY INSIDE A COLLECTION ////
////////////////////////////////////////////

function gg_load_coll_gallery() {
	if(isset($_POST['gg_type']) && $_POST['gg_type'] == 'gg_load_coll_gallery') {
		if(!isset($_POST['gdata'])) {die('data is missing');}
		$gdata = explode(';', addslashes($_POST['gdata']));
		
		$resp = '';
		if(get_option('gg_coll_show_gall_title')) {
			$resp .= '<h3 class="gg_coll_gall_title">'. get_the_title($gdata[0]) .'</h3>';
		}
		
		$resp .= do_shortcode('[g-gallery gid="'.$gdata[0].'" random="'.$gdata[1].'" watermark="'.$gdata[2].'"]');
		die($resp);
	}
}
add_action('init', 'gg_load_coll_gallery');

