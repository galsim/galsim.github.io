 <?php 
require_once(GG_DIR . '/functions.php');
$cpt = gg_get_cpt();
?>

<div class="wrap lcwp_form">  
	<div class="icon32"><img src="<?php echo GG_URL.'/img/gg_logo.png'; ?>" alt="global gallery" /><br/></div>
    <?php echo '<h2 class="lcwp_page_title" style="border: none;">' . __( 'Global Gallery Settings', 'gg_ml') . "</h2>"; ?>  

    <?php
	// HANDLE DATA
	if(isset($_POST['lcwp_admin_submit'])) { 
		if (!isset($_POST['lcwp_nonce']) || !wp_verify_nonce($_POST['lcwp_nonce'], 'lcweb')) {die('<p>Cheating?</p>');};
		include(GG_DIR . '/classes/simple_form_validator.php');		
		
		$validator = new simple_fv;
		$indexes = array();
		
		$indexes[] = array('index'=>'gg_layout', 'label'=>__( 'Default Layout', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_masonry_basewidth', 'label'=>__( 'Galleries max-width', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_paginate', 'label'=>__( 'Use Pagination', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_per_page', 'label'=>__( 'Images per page', 'gg_ml' ), 'type'=>'int', 'required'=>true);
		$indexes[] = array('index'=>'gg_preview_pag', 'label'=>__( 'Preview page', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_link_target', 'label'=>__( 'Linked images behavior', 'gg_ml'));
		$indexes[] = array('index'=>'gg_affect_wp_gall', 'label'=>__( 'Apply the plugin to WP galleries', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_extend_wp_gall', 'label'=>__( 'WP gall management for cpt', 'gg_ml' ));
		
		$indexes[] = array('index'=>'gg_filters_align', 'label'=>__( 'Filters Alignment', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_use_old_filters', 'label'=>__( 'Use old filters style', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_dd_mobile_filter', 'label'=> 'Use dropdown on mobile screens');
		$indexes[] = array('index'=>'gg_coll_back_to', 'label'=>__( 'Back to collection - custom text', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_coll_show_gall_title', 'label'=>__( 'Show loaded collection galleries title ', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_coll_back_to_scroll', 'label' => 'Keep "back to collection" button visible on scroll');
		$indexes[] = array('index'=>'gg_delayed_fx', 'label'=>'Show all items without delay');
		
		$indexes[] = array('index'=>'gg_pag_style', 'label'=>__( 'Pagination button style', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_infinite_scroll', 'label'=>__( 'Infinite scroll', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_pag_align', 'label'=>__( 'Pagination button alignment', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_pag_layout', 'label'=>__( 'Pagination button layout', 'gg_ml' ));
		
		$indexes[] = array('index'=>'gg_slider_style', 'label'=>'Slider style');
		$indexes[] = array('index'=>'gg_slider_old_cmd', 'label'=>'Use old commands style');
		$indexes[] = array('index'=>'gg_slider_no_border', 'label'=>'Hide slider borders');
		$indexes[] = array('index'=>'gg_slider_crop', 'label'=>__( 'Slider crop method', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_slider_fx', 'label'=>__( 'Slider transition effect', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_slider_fx_time', 'label'=>__( 'Slider transition duration', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_slider_autoplay', 'label'=>__( 'Slider - Autoplay slideshow', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_slider_interval', 'label'=>__( 'Slider - Slideshow interval', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_slider_thumbs', 'label'=>__( 'Slider - thumbnails behavior', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_slider_thumb_w', 'label'=>__( 'Slider - thumbnails width', 'gg_ml' ), 'type'=>'int', 'min_val'=>20);
		$indexes[] = array('index'=>'gg_slider_thumb_h', 'label'=>__( 'Slider - thumbnails height', 'gg_ml' ), 'type'=>'int', 'min_val'=>40);
		$indexes[] = array('index'=>'gg_slider_tgl_info', 'label'=>__( 'Slider - hide texts by default', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_slider_to_hide', 'label'=>__( 'Slider - elements to hide', 'gg_ml' ));
		
		$indexes[] = array('index'=>'gg_car_elem_style', 'label'=>__( 'Carousel - elements style', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_car_hor_margin', 'label'=>__( 'Carousel - horizontal margin', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_car_ver_margin', 'label'=>__( 'Carousel - vertical margin', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_car_infinite', 'label'=>__('Carousel - infinite sliding', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_car_ss_time', 'label'=>__('Carousel - slideshow time', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_car_autoplay', 'label'=>__('Carousel - autoplay', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_car_pause_on_h', 'label'=>__('Carousel - pause on hover', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_car_hide_nav_elem', 'label'=>__('Carousel - navigation elements to hide', 'gg_ml' ));
		
		$indexes[] = array('index'=>'gg_albums_basepath', 'label'=>__( 'Global Gallery albums basepath', 'gg_ml' ), 'required'=>true);
		$indexes[] = array('index'=>'gg_albums_baseurl', 'label'=>__( 'Global Gallery albums baseurl', 'gg_ml' ), 'required'=>true, 'type' => 'url');
		
		$indexes[] = array('index'=>'gg_disable_rclick', 'label'=>__( 'Disable right click', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_thumb_q', 'label'=>__( 'Thumbnails Quality', 'gg_ml' ), 'type'=>'int', 'min_val'=>50, 'max_val'=>100);
		$indexes[] = array('index'=>'gga_img_title_src', 'label'=>__( 'Global Gallery Albums - images title', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_use_admin_thumbs', 'label'=>__( 'Use thumbnails in admin', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_enable_ajax', 'label'=>__( 'Enable AJAX mode', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_disable_dl', 'label'=>__( 'Disable Deeplinking', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_force_inline_css', 'label'=>__( 'Force inline css usage', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_ewpt_force', 'label'=>'EWPT Forcing system');
		$indexes[] = array('index'=>'gg_use_timthumb', 'label'=>'Use TimThumb');
		$indexes[] = array('index'=>'gg_js_head', 'label'=>'Javascript in head');
		
		$indexes[] = array('index'=>'gg_thumb_w', 'label'=>__( 'Thumbnails Width', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_thumb_h', 'label'=>__( 'Thumbnails Height', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_standard_hor_margin', 'label'=>__( 'Standard horizontal margin', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_standard_ver_margin', 'label'=>__( 'Standard vertical margin', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_masonry_cols', 'label'=>__( 'Masonry Columns', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_masonry_margin', 'label'=>__( 'Masonry Image Margin', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_masonry_min_width', 'label'=>__( 'Masonry min width', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_photostring_h', 'label'=>__( 'PhotoString Thumbs height', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_photostring_margin', 'label'=>__( 'PhotoString Image Margin', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_photostring_min_width', 'label'=>__( 'PhotoString min width', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_coll_thumb_w', 'label'=>__( 'Collection columns', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_coll_thumb_min_w', 'label'=>__( 'Collection columns min width', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_coll_thumb_h', 'label'=>__( 'Collection Height', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_coll_hor_margin', 'label'=>__( 'Collections horizontal margin', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_coll_ver_margin', 'label'=>__( 'Collections vertical margin', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_coll_title_under', 'label'=>__( 'Collections - Titles under images', 'gg_ml' ));
		
		$indexes[] = array('index'=>'gg_loader_color', 'label'=>__( 'Loader color', 'gg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'gg_img_border', 'label'=>__( 'Image Border', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_img_radius', 'label'=>__( 'Cells Border Radius', 'gg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'gg_img_border_color', 'label'=>__( 'Images Border Color', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_img_outline_color', 'label'=>__( 'Images Outline Color', 'gg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'gg_img_shadow', 'label'=>__( 'Cells Shadow', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_thumb_fx', 'label'=>__( 'Thumbnail Effect', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_overlay_type', 'label'=>__( 'Overlay Type', 'gg_ml' ));	
		$indexes[] = array('index'=>'gg_main_overlay', 'label'=>__( 'Main Overlay Type', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_main_ol_behav', 'label'=>__( 'Main Overlay behavior', 'gg_ml' )); 
		$indexes[] = array('index'=>'gg_main_ol_color', 'label'=>__( 'Main Overlay Color', 'gg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'gg_main_ol_opacity', 'label'=>__( 'Main Overlay Opacity', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_main_ol_txt_color', 'label'=>__( 'Main Overlay Text Color', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_sec_overlay', 'label'=>__( 'Secondary Overlay Position', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_sec_ol_color', 'label'=>__( 'Secondary Overlay Color', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_icons_col', 'label'=>__( 'Icons Color', 'gg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'gg_txt_u_title_color', 'label'=>__( 'Text under images - Title color', 'gg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'gg_txt_u_descr_color', 'label'=>__( 'Text under images - Description color', 'gg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'gg_filters_txt_color', 'label'=>__( 'Filters Text Color', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_filters_bg_color', 'label'=>__( 'Filters Background Color', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_filters_border_color', 'label'=>__( 'Filters Border Color', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_filters_txt_color_h', 'label'=>__( 'Filters Text Color - hover status', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_filters_bg_color_h', 'label'=>__( 'Filters Background Color - hover status', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_filters_border_color_h', 'label'=>__( 'Filters Border Color - hover status', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_filters_txt_color_sel', 'label'=>__( 'Filters Text Color - selected status', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_filters_bg_color_sel', 'label'=>__( 'Filters Background Color - selected status', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_filters_border_color_sel', 'label'=>__( 'Filters Border Color - selected status', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_filters_radius', 'label'=>__( 'Filter Border Radius', 'gg_ml' ), 'type'=>'int');
		
		$indexes[] = array('index'=>'gg_lightbox', 'label'=>__( 'Lightbox to Use', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_lcl_style', 'label'=>__( 'Lightbox style', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_col_style', 'label'=>__( 'Lightbox style', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_opacity', 'label'=>__( 'Lightbox overlay opacity', 'gg_ml' ),);
		$indexes[] = array('index'=>'gg_lb_ol_color', 'label'=>__( 'Lightbox overlay color', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_ol_pattern', 'label'=>__( 'Lightbox overlay pattern', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_max_w', 'label'=>__( 'Lightbox max width', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_max_h', 'label'=>__( 'Lightbox max height', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_padding', 'label'=>__( 'Lightbox padding', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_border_w', 'label'=>__( 'Lightbox border width', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_border_col', 'label'=>__( 'Lightbox border color', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_radius', 'label'=>__( 'Lightbox corner radius', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_txt_pos', 'label'=>__( 'Lightbox text position', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_thumbs', 'label'=>__( 'Lightbox thumb navigation', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_thumbs_full_img', 'label'=>__( 'Lightbox thumb navigation - use full images', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_fullscreen', 'label'=>__( 'Lightbox fullscreen mode', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_fs_only', 'label'=>__( 'Lightbox only fullscreen', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_fs_method', 'label'=>__( 'Lightbox fullscreen resize&crop', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_socials', 'label'=>__( 'Enable Socials', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_slideshow', 'label'=>__( 'Lightbox slideshow', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_ss_time', 'label'=>__( 'Lightbox slideshow interval', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_time', 'label'=>__( 'Lightbox animation time', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_lb_anim_behav', 'label'=>__( 'Lightbox animation behavior', 'gg_ml' ));
		
		$indexes[] = array('index'=>'gg_watermark_img', 'label'=>__( 'Watermark Image', 'gg_ml' ), 'type'=>'url');
		$indexes[] = array('index'=>'gg_watermark_opacity', 'label'=>__( 'Watermark Opacity', 'gg_ml' ));
		$indexes[] = array('index'=>'gg_watermark_pos', 'label'=>__( 'Watermark Position', 'gg_ml' ));
		
		$indexes[] = array('index'=>'gg_custom_css', 'label'=>__( 'Custom CSS', 'gg_ml' ));
		
		if(is_multisite() && get_option('gg_use_timthumb')) {
			$indexes[] = array('index'=>'gg_wpmu_path', 'label'=>__('JS for old jQuery', 'lcwp_ml'), 'required'=>true);		
		}
		
		//// overlay manager add-on ////////
		if(defined('GGOM_DIR')) {
			$indexes[] = array('index'=>'gg_gall_default_overlay', 'label'=>'Default galleries overlay');
			$indexes[] = array('index'=>'gg_coll_default_overlay', 'label'=>'Default collections verlay');
			$indexes[] = array('index'=>'gg_car_default_overlay', 'label'=>'Default carousels overlay');
		}
		
		$validator->formHandle($indexes);
		$fdata = $validator->form_val;
		$error = $validator->getErrors();
		
		if($error) {echo '<div class="error"><p>'.$error.'</p></div>';}
		else {
			// clean data and save options
			foreach($fdata as $key=>$val) {
				if(!is_array($val)) {
					$fdata[$key] = stripslashes($val);
				}
				else {
					$fdata[$key] = array();
					foreach($val as $arr_val) {$fdata[$key][] = stripslashes($arr_val);}
				}
				
				if($fdata[$key] === false) {delete_option($key);}
				else {
					update_option($key, $fdata[$key]);	
				}
			}
	
			// create frontend.css else print error
			if(!get_option('gg_inline_css')) {
				if(!gg_create_frontend_css()) {
					if(!get_option('gg_inline_css')) { add_option('gg_inline_css', '255', '', 'yes'); }
					update_option('gg_inline_css', 1);	
					
					echo '<div class="updated"><p>'. __('An error occurred during dynamic CSS creation. The code will be used inline anyway', 'gg_ml') .'</p></div>';
				}
				else {delete_option('gg_inline_css');}
			}
			
			echo '<div class="updated"><p><strong>'. __('Options saved.', 'gg_ml') .'</strong></p></div>';
		}
	}
	
	else {  
		// Normal page display
		$fdata['gg_layout'] = get_option('gg_layout');	
		$fdata['gg_masonry_basewidth'] = get_option('gg_masonry_basewidth', 1100);
		$fdata['gg_paginate'] = get_option('gg_paginate');
		$fdata['gg_per_page'] = get_option('gg_per_page');
		$fdata['gg_preview_pag'] = get_option('gg_preview_pag');	
		$fdata['gg_link_target'] = get_option('gg_link_target');
		$fdata['gg_affect_wp_gall'] = get_option('gg_affect_wp_gall');
		$fdata['gg_extend_wp_gall'] = get_option('gg_extend_wp_gall');
		
		$fdata['gg_filters_align'] = get_option('gg_filters_align');
		$fdata['gg_use_old_filters'] = get_option('gg_use_old_filters');
		$fdata['gg_dd_mobile_filter'] = get_option('gg_dd_mobile_filter');
		$fdata['gg_coll_back_to'] = get_option('gg_coll_back_to');
		$fdata['gg_coll_show_gall_title'] = get_option('gg_coll_show_gall_title');
		$fdata['gg_coll_back_to_scroll'] = get_option('gg_coll_back_to_scroll');
		$fdata['gg_delayed_fx'] = get_option('gg_delayed_fx');
		
		$fdata['gg_pag_style'] = get_option('gg_pag_style');
		$fdata['gg_infinite_scroll'] = get_option('gg_infinite_scroll');
		$fdata['gg_pag_align'] = get_option('gg_pag_align');
		$fdata['gg_pag_layout'] = get_option('gg_pag_layout');
		
		$fdata['gg_slider_style'] = get_option('gg_slider_style');
		$fdata['gg_slider_old_cmd'] = get_option('gg_slider_old_cmd');
		$fdata['gg_slider_no_border'] = get_option('gg_slider_no_border');
		$fdata['gg_slider_crop'] = get_option('gg_slider_crop');
		$fdata['gg_slider_fx'] = get_option('gg_slider_fx', 'fadeslide');
		$fdata['gg_slider_fx_time'] = get_option('gg_slider_fx_time', 400);
		$fdata['gg_slider_autoplay'] = get_option('gg_slider_autoplay');
		$fdata['gg_slider_interval'] = get_option('gg_slider_interval', 3000);
		$fdata['gg_slider_thumbs'] = get_option('gg_slider_thumbs', 'yes');
		$fdata['gg_slider_thumb_w'] = get_option('gg_slider_thumb_w', 60);
		$fdata['gg_slider_thumb_h'] = get_option('gg_slider_thumb_h', 40);
		$fdata['gg_slider_tgl_info'] = get_option('gg_slider_tgl_info');
		$fdata['gg_slider_to_hide'] = get_option('gg_slider_to_hide');
		
		$fdata['gg_car_elem_style'] = get_option('gg_car_elem_style', 'light');
		$fdata['gg_car_hor_margin'] = get_option('gg_car_hor_margin', 14);
		$fdata['gg_car_ver_margin'] = get_option('gg_car_ver_margin', 10);
		$fdata['gg_car_infinite'] = get_option('gg_car_infinite');
		$fdata['gg_car_ss_time'] = get_option('gg_car_ss_time', 4000);
		$fdata['gg_car_autoplay'] = get_option('gg_car_autoplay');
		$fdata['gg_car_pause_on_h'] = get_option('gg_car_pause_on_h');
		$fdata['gg_car_hide_nav_elem'] = get_option('gg_car_hide_nav_elem');
		
		$fdata['gg_albums_basepath'] = get_option('gg_albums_basepath', GGA_DIR);
		$fdata['gg_albums_baseurl'] = get_option('gg_albums_baseurl', GGA_URL);
		
		$fdata['gg_disable_rclick'] = get_option('gg_disable_rclick');
		$fdata['gg_thumb_q'] = get_option('gg_thumb_q');
		$fdata['gga_img_title_src'] = get_option('gga_img_title_src');
		$fdata['gg_use_admin_thumbs'] = get_option('gg_use_admin_thumbs');
		$fdata['gg_disable_dl'] = get_option('gg_disable_dl'); 
		$fdata['gg_force_inline_css'] = get_option('gg_force_inline_css');
		$fdata['gg_ewpt_force'] = get_option('gg_ewpt_force');  
		$fdata['gg_use_timthumb'] = get_option('gg_use_timthumb'); 
		$fdata['gg_enable_ajax'] = get_option('gg_enable_ajax');
		$fdata['gg_js_head'] = get_option('gg_js_head'); 
		$fdata['gg_wpmu_path'] = get_option('gg_wpmu_path');
		
		$fdata['gg_thumb_w'] = get_option('gg_thumb_w', 250);
		$fdata['gg_thumb_h'] = get_option('gg_thumb_h', 200);
		$fdata['gg_standard_hor_margin'] = get_option('gg_standard_hor_margin');
		$fdata['gg_standard_ver_margin'] = get_option('gg_standard_ver_margin');
		$fdata['gg_masonry_cols'] = get_option('gg_masonry_cols');
		$fdata['gg_masonry_margin'] = get_option('gg_masonry_margin');
		$fdata['gg_masonry_min_width'] = get_option('gg_masonry_min_width');
		$fdata['gg_photostring_h'] = get_option('gg_photostring_h'); 
		$fdata['gg_photostring_margin'] = get_option('gg_photostring_margin');
		$fdata['gg_photostring_min_width'] = get_option('gg_photostring_min_width', 120);
		$fdata['gg_coll_thumb_w'] = get_option('gg_coll_thumb_w', 0.3333);
		$fdata['gg_coll_thumb_min_w'] = get_option('gg_coll_thumb_min_w', 200);
		$fdata['gg_coll_thumb_h'] = get_option('gg_coll_thumb_h', 200);
		$fdata['gg_coll_hor_margin'] = get_option('gg_coll_hor_margin', 15);
		$fdata['gg_coll_ver_margin'] = get_option('gg_coll_ver_margin', 15);
		$fdata['gg_coll_title_under'] = get_option('gg_coll_title_under');
		
		$fdata['gg_loader_color'] = get_option('gg_loader_color', '#888888');
		$fdata['gg_img_border'] = get_option('gg_img_border');  
		$fdata['gg_img_radius'] = get_option('gg_img_radius'); 
		$fdata['gg_img_border_color'] = get_option('gg_img_border_color', '#aaaaaa'); 
		$fdata['gg_img_outline_color'] = get_option('gg_img_outline_color', '#777777'); 
		$fdata['gg_img_shadow'] = get_option('gg_img_shadow'); 
		$fdata['gg_thumb_fx'] = get_option('gg_thumb_fx'); 
		$fdata['gg_overlay_type'] = get_option('gg_overlay_type');
		$fdata['gg_main_overlay'] = get_option('gg_main_overlay'); 
		$fdata['gg_main_ol_behav'] = get_option('gg_main_ol_behav', 'show_on_h'); 
		$fdata['gg_main_ol_color'] = get_option('gg_main_ol_color'); 
		$fdata['gg_main_ol_opacity'] = get_option('gg_main_ol_opacity', 70); 
		$fdata['gg_main_ol_txt_color'] = get_option('gg_main_ol_txt_color'); 
		$fdata['gg_sec_overlay'] = get_option('gg_sec_overlay', 'tl');
		$fdata['gg_sec_ol_color'] = get_option('gg_sec_ol_color'); 
		$fdata['gg_icons_col'] = get_option('gg_icons_col', '#fcfcfc'); 
		$fdata['gg_txt_u_title_color'] = get_option('gg_txt_u_title_color', '#444444'); 
		$fdata['gg_txt_u_descr_color'] = get_option('gg_txt_u_descr_color', '#555555'); 
		$fdata['gg_filters_txt_color'] = get_option('gg_filters_txt_color', '#444444'); 
		$fdata['gg_filters_bg_color'] = get_option('gg_filters_bg_color', '#ffffff');
		$fdata['gg_filters_border_color'] = get_option('gg_filters_border_color', '#999999'); 
		$fdata['gg_filters_txt_color_h'] = get_option('gg_filters_txt_color_h', '#666666'); 
		$fdata['gg_filters_bg_color_h'] = get_option('gg_filters_bg_color_h', '#ffffff'); 
		$fdata['gg_filters_border_color_h'] = get_option('gg_filters_border_color_h', '#666666');
		$fdata['gg_filters_txt_color_sel'] = get_option('gg_filters_txt_color_sel', '#222222'); 
		$fdata['gg_filters_bg_color_sel'] = get_option('gg_filters_bg_color_sel', '#ffffff'); 
		$fdata['gg_filters_border_color_sel'] = get_option('gg_filters_border_color_sel', '#555555');
		$fdata['gg_filters_radius'] = get_option('gg_filters_radius', 2); 
			
		$fdata['gg_lightbox'] = get_option('gg_lightbox');
		$fdata['gg_lb_lcl_style'] = get_option('gg_lb_lcl_style');
		$fdata['gg_lb_col_style'] = get_option('gg_lb_col_style');
		$fdata['gg_lb_opacity'] = get_option('gg_lb_opacity', 70);
		$fdata['gg_lb_ol_color'] = get_option('gg_lb_ol_color');
		$fdata['gg_lb_ol_pattern'] = get_option('gg_lb_ol_pattern');
		$fdata['gg_lb_max_w'] = get_option('gg_lb_max_w');
		$fdata['gg_lb_max_h'] = get_option('gg_lb_max_h');
		$fdata['gg_lb_padding'] = get_option('gg_lb_padding', 20);
		$fdata['gg_lb_border_w'] = get_option('gg_lb_border_w', 4);
		$fdata['gg_lb_border_col'] = get_option('gg_lb_border_col');
		$fdata['gg_lb_radius'] = get_option('gg_lb_radius', 7);
		$fdata['gg_lb_txt_pos'] = get_option('gg_lb_txt_pos');
		$fdata['gg_lb_thumbs'] = get_option('gg_lb_thumbs');
		$fdata['gg_lb_thumbs_full_img'] = get_option('gg_lb_thumbs_full_img');
		$fdata['gg_lb_fullscreen'] = get_option('gg_lb_fullscreen');
		$fdata['gg_lb_fs_only'] = get_option('gg_lb_fs_only');
		$fdata['gg_lb_fs_method'] = get_option('gg_lb_fs_method');
		$fdata['gg_lb_socials'] = get_option('gg_lb_socials');
		$fdata['gg_lb_slideshow'] = get_option('gg_lb_slideshow');
		$fdata['gg_lb_ss_time'] = get_option('gg_lb_ss_time');
		$fdata['gg_lb_time'] = get_option('gg_lb_time');
		$fdata['gg_lb_anim_behav'] = get_option('gg_lb_anim_behav');
		
		$fdata['gg_watermark_img'] = get_option('gg_watermark_img');
		$fdata['gg_watermark_opacity'] = get_option('gg_watermark_opacity');
		$fdata['gg_watermark_pos'] = get_option('gg_watermark_pos');
		
		$fdata['gg_custom_css'] = get_option('gg_custom_css'); 
		
		//// overlay manager add-on ////////
		if(defined('GGOM_DIR')) {
			$fdata['gg_gall_default_overlay'] = get_option('gg_gall_default_overlay');
			$fdata['gg_coll_default_overlay'] = get_option('gg_coll_default_overlay');
			$fdata['gg_car_default_overlay'] = get_option('gg_car_default_overlay');
		}
	}  
	?>
    
	<br/>
    <div id="tabs">
    <form name="lcwp_admin" method="post" class="form-wrap" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    	
    <ul class="tabNavigation">
    	<li><a href="#main_opt"><?php _e('Main Options', 'gg_ml') ?></a></li>
    	<li><a href="#layouts"><?php _e('Layouts', 'gg_ml') ?></a></li>
        <li><a href="#styling"><?php _e('Styling', 'gg_ml') ?></a></li>
        <li><a href="#lightbox"><?php _e('Lightbox', 'gg_ml') ?></a></li>
        <li><a href="#watermark"><?php _e('Watermark', 'gg_ml') ?></a></li>
        <li><a href="#cust_css"><?php _e('Custom CSS', 'gg_ml') ?></a></li>
    </ul>    
    
    <div id="main_opt"> 
   		<h3><?php _e("Default Gallery Settings", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Gallery Layout", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e('Select a layout', 'gg_ml') ?> .." name="gg_layout" class="lcweb-chosen" tabindex="2">
                    <option value="standard">Standard</option>  
                    <option value="masonry" <?php if($fdata['gg_layout'] == 'masonry') {echo 'selected="selected"';} ?>>Masonry</option>
                    <option value="string" <?php if($fdata['gg_layout'] == 'string') {echo 'selected="selected"';} ?>>PhotoString</option>  
                </select>
            </td>
            <td><span class="info"><?php _e("Set default galleries layout", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Elements maximum width", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="20" max="1960" min="860"></div>
                <?php if((int)$fdata['gg_masonry_basewidth'] == 0) {$fdata['gg_masonry_basewidth'] = 1100;} ?>
                <input type="text" value="<?php echo(int)$fdata['gg_masonry_basewidth']; ?>" name="gg_masonry_basewidth" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set maximum container's width where galleries collections and carousels will be placed", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use pagination?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_paginate'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_paginate" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("Set if split galleries into pages by default", 'gg_ml'); ?></span></td>
          </tr>   
          <tr>
            <td class="lcwp_label_td"><?php _e("Images per page", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<?php if((int)$fdata['gg_per_page'] == 0) {$fdata['gg_per_page'] = 15;} ?>
                <div class="lcwp_slider" step="1" max="100" min="1"></div>
                <input type="text" value="<?php echo $fdata['gg_per_page']; ?>" name="gg_per_page" class="lcwp_slider_input" />
            </td>
            <td><span class="info"><?php _e("Set the default images number per page", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
          	<td class="lcwp_label_td"><?php _e("Preview container", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select name="gg_preview_pag" class="lcweb-chosen" data-placeholder="<?php _e("Select a page", 'gg_ml'); ?> .." tabindex="2">
                  <option value=""></option>
                  <?php
                  foreach(get_pages() as $pag) {
                      ($fdata['gg_preview_pag'] == $pag->ID) ? $selected = 'selected="selected"' : $selected = '';
                      echo '<option value="'.$pag->ID.'" '.$selected.'>'.$pag->post_title.'</option>';
                  }
                  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose the page to use as preview container", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use the plugin with Wordpress galleries?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_affect_wp_gall'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_affect_wp_gall" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked apply by default plugin systems to WP galleries", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
          	<td class="lcwp_label_td"><?php _e("Linked images behavior", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select name="gg_link_target" class="lcweb-chosen" data-placeholder="<?php _e("Select a page", 'gg_ml'); ?> .." tabindex="2">
                  <option value="_top"><?php _e('open link in same page', 'gg_ml') ?></option>
                  <option value="_blank" <?php if($fdata['gg_link_target'] == '_blank') {echo 'selected="selected"';} ?>><?php _e('open link in a new page', 'gg_ml') ?></option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose the behavior of linked images", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Show images without delay?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_delayed_fx'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_delayed_fx" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked, show gallery images without delayed effect', 'gg_ml') ?></span></td>
          </tr>
          
           <?php if($cpt) : ?>
           <tr>
             <td class="lcwp_label_td"><?php _e("Extend WP galleries management for these custom post types", 'gg_ml'); ?></td>
             <td class="lcwp_field_td" colspan="2">
               <select name="gg_extend_wp_gall[]" multiple="multiple" class="lcweb-chosen" data-placeholder="<?php _e("Select custom post types", 'gg_ml'); ?> .." style="width: 50%;">
				<?php
                foreach($cpt as $id => $name) {
                    (is_array($fdata['gg_extend_wp_gall']) && in_array($id, $fdata['gg_extend_wp_gall'])) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
                }
                ?>
              </select> 
             </td>
           </tr>
           <?php endif; ?>
    	</table>
        
        <h3><?php _e("Pagination Settings", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Style", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="gg_pag_style" class="lcweb-chosen" data-placeholder="<?php _e("Select a style", 'gg_ml'); ?> .." tabindex="2">
                  <option value="light">Light</option>
                  <option value="dark" <?php if($fdata['gg_pag_style'] == 'dark') {echo 'selected="selected"';} ?>>Dark</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the pagination buttons style", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use infinite scroll?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_infinite_scroll'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_infinite_scroll" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked use infinite scroll instead of normal pagination", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Button alignment", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="gg_pag_align" class="lcweb-chosen" data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." tabindex="2">
                  <option value="center">Center</option>
                  <option value="left" <?php if($fdata['gg_pag_align'] == 'left') {echo 'selected="selected"';} ?>>Left</option>
                  <option value="right" <?php if($fdata['gg_pag_align'] == 'right') {echo 'selected="selected"';} ?>>Right</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the pagination button alignment", 'gg_ml'); ?></span></td>
          </tr>	
          <tr>
            <td class="lcwp_label_td"><?php _e("Standard pagination - Layout", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="gg_pag_layout" class="lcweb-chosen" data-placeholder="<?php _e("Select a layout", 'gg_ml'); ?> .." tabindex="2">
				  <?php	
                  foreach(gg_pag_layouts() as $key => $val) {
                      ($key == $fdata['gg_pag_layout']) ? $sel = 'selected="selected"' : $sel = '';
                      echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
                  }
                  ?>
                </select> 
            </td>
            <td><span class="info"><?php _e("Select the layout to use for the standard pagination elements", 'gg_ml'); ?></span></td>
          </tr>	
        </table>
        
        <h3><?php _e("Collection Settings", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Filters Alignment", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="gg_filters_align" class="lcweb-chosen" data-placeholder="<?php _e("Select a style", 'gg_ml'); ?> .." tabindex="2">
                  <option value="left">Left</option>
                  <option value="center" <?php if($fdata['gg_filters_align'] == 'center') {echo 'selected="selected"';} ?>>Center</option>
                  <option value="right" <?php if($fdata['gg_filters_align'] == 'right') {echo 'selected="selected"';} ?>>Right</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the filters alignment", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use the old filters style?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_use_old_filters'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_use_old_filters" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked use the old Global Gallery filters style', 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use dropdown filters on mobile?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_dd_mobile_filter'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_dd_mobile_filter" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked shows a compact dropdown to filter on mobile devices', 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e('"Back to collection" - custom text', 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" value="<?php echo $fdata['gg_coll_back_to']; ?>" name="gg_coll_back_to" />
            </td>
            <td><span class="info"><?php _e('Use a custom string for the "back to collection" button', 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Display loaded galleries title?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_coll_show_gall_title'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_coll_show_gall_title" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked display the title on top of loaded galleries", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e('"Back to collection" - keep visible on scroll?', 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_coll_back_to_scroll'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_coll_back_to_scroll" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked "back to collection" button is kept visible on scroll', 'gg_ml'); ?></span></td>
          </tr>  
       </table> 
        
        <h3><?php _e("Slider Settings", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Style", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="gg_slider_style" class="lcweb-chosen" data-placeholder="<?php _e("Select a style", 'gg_ml'); ?> .." tabindex="2">
                  <option value="light">Light</option>
                  <option value="dark" <?php if($fdata['gg_slider_style'] == 'dark') {echo 'selected="selected"';} ?>>Dark</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the slider style", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use old commands style?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_slider_old_cmd'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_slider_old_cmd" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If enabled, uses the old commands graphic", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Hide borders and shadows?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_slider_no_border'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_slider_no_border" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If enabled, displays the slider without external borders and shadows", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Crop Method", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="gg_slider_crop" class="lcweb-chosen" data-placeholder="<?php _e("Select a method", 'gg_ml'); ?> .." tabindex="2">
				  <?php	
                  foreach(gg_galleria_crop_methods() as $key => $val) {
					  ($key == $fdata['gg_slider_crop']) ? $sel = 'selected="selected"' : $sel = '';
					  echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
                  }
                  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the cropping method for the shown image", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Transition effect", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="gg_slider_fx" class="lcweb-chosen" data-placeholder="<?php _e("Select a transition", 'gg_ml'); ?> .." tabindex="2">
                  <?php	
                  foreach(gg_galleria_fx() as $key => $val) {
					  ($key == $fdata['gg_slider_fx']) ? $sel = 'selected="selected"' : $sel = '';
					  echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
                  }
                  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the transition effect between slides", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Transition duration", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="50" max="1000" min="100"></div>
                <input type="text" value="<?php echo $fdata['gg_slider_fx_time']; ?>" name="gg_slider_fx_time" class="lcwp_slider_input" />
                <span>ms</span>
            </td>
            <td><span class="info"><?php _e("How much time the transition takes (in milliseconds - default 400)", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Autoplay slideshow?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_slider_autoplay'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_slider_autoplay" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("Check to autoplay slider slideshow by default", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Slideshow interval", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="500" max="8000" min="1000"></div>
                <input type="text" value="<?php echo $fdata['gg_slider_interval']; ?>" name="gg_slider_interval" class="lcwp_slider_input" />
                <span>ms</span>
            </td>
            <td><span class="info"><?php _e("How long each slide will be shown (in milliseconds - default 3000)", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Show thumbnails?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="gg_slider_thumbs" class="lcweb-chosen" data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." tabindex="2">
                  <?php	
                  foreach(gg_galleria_thumb_opts() as $key => $val) {
					  ($key == $fdata['gg_slider_thumbs']) ? $sel = 'selected="selected"' : $sel = '';
					  echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
                  }
                  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select if and how the thumbs will be shown", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Thumbnails size", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<input type="text" name="gg_slider_thumb_w" value="<?php echo $fdata['gg_slider_thumb_w'] ?>" maxlength="3" class="lcwp_slider_input" /> x 
            	<input type="text" name="gg_slider_thumb_h" value="<?php echo $fdata['gg_slider_thumb_h'] ?>" maxlength="3" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set slider thumbnails size", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Hide image's data by default?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_slider_tgl_info'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_slider_tgl_info" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("Check hide image's data by default", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Elements to remove", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
              <select name="gg_slider_to_hide[]" multiple="multiple" class="lcweb-chosen" data-placeholder="<?php _e('Select an option', 'gg_mg') ?> .." tabindex="2" style="width: 85%;">
                  <?php
                  $opts = array( 'play' => __('Play button', 'gg_ml'), 'lightbox' => __('Lightbox button', 'gg_ml'), 'info' => __("Image's data", 'gg_ml') );
                  foreach($opts as $id => $name) {
                      (is_array($fdata['gg_slider_to_hide']) && in_array($id, $fdata['gg_slider_to_hide'])) ? $selected = 'selected="selected"' : $selected = '';
                      echo '<option value="'.$id.'" '.$selected .'>'.$name.'</option>';
				  } ?>
              </select>   
            </td>
            <td><span class="info"><?php _e("Select the elements you want to remove in the slider", 'gg_ml'); ?></span></td>
          </tr>
        </table>
        
        <h3><?php _e("Carousel Settings", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Style", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="gg_car_elem_style" class="lcweb-chosen" data-placeholder="<?php _e("Select a style", 'gg_ml'); ?> .." autocomplete="off">
                  <option value="light">Light</option>
                  <option value="dark" <?php if($fdata['gg_car_elem_style'] == 'dark') {echo 'selected="selected"';} ?>>Dark</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select carousel elements style", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Horizontal Margin", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="40" min="0"></div>
                <input type="text" value="<?php echo(int)$fdata['gg_car_hor_margin']; ?>" name="gg_car_hor_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set horizontal margin between images", 'gg_ml'); ?></span></td>
          </tr>
		  <tr>
            <td class="lcwp_label_td"><?php _e("Vertical Margin", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="40" min="0"></div>
                <input type="text" value="<?php echo(int)$fdata['gg_car_ver_margin']; ?>" name="gg_car_ver_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set vertical margin between images", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Infinite loop sliding?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php $sel = ($fdata['gg_car_infinite'] == 1) ? 'checked="checked"' : ''; ?>
                <input type="checkbox" value="1" name="gg_car_infinite" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked, navigation won't stop if latest image is reached", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Slideshow interval", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<?php if((int)$fdata['gg_car_ss_time'] == 0) {$fdata['gg_car_ss_time'] = 5000;} ?>
            	<div class="lcwp_slider" step="200" max="15000" min="2000"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_car_ss_time']; ?>" name="gg_car_ss_time" class="lcwp_slider_input" />
                <span>ms</span>
            </td>
            <td><span class="info"><?php _e("Set slideshow interval time in milliseconds (default 5000)", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Autoplay slideshow?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_car_autoplay'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_car_autoplay" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("Check to autoplay carousel slideshow by default", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Pause slideshow on hover?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php $sel = ($fdata['gg_car_pause_on_h'] == 1) ? 'checked="checked"' : ''; ?>
                <input type="checkbox" value="1" name="gg_car_pause_on_h" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked, slideshow will be paused hovering an image", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Hide navigation elements", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
              <select name="gg_car_hide_nav_elem[]" multiple="multiple" class="lcweb-chosen" data-placeholder="<?php _e('Select an option', 'gg_mg') ?> .." style="width: 85%;">
                <?php
                $opts = array( 'arrows' => __('Side arrows', 'gg_ml'), 'dots' => __('Bottom dots', 'gg_ml') );
                foreach($opts as $id => $name) {
                    (is_array($fdata['gg_car_hide_nav_elem']) && in_array($id, $fdata['gg_car_hide_nav_elem'])) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$id.'" '.$selected .'>'.$name.'</option>';
                } ?>
              </select>
            </td>
            <td><span class="info"><?php _e("Select navigation elements to hide in carousels", 'gg_ml'); ?></span></td>
          </tr>
        </table>  
        
        <?php
		//// overlay manager add-on ////////
		if(defined('GGOM_DIR')) : 
		$overlays = get_terms('ggom_overlays', 'hide_empty=0');
		?>
        	
        <h3><?php _e("Overlay Manager add-on - Default Overlays", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Default galleries overlay", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
               <select name="gg_gall_default_overlay" class="lcweb-chosen" data-placeholder="<?php _e("Select an overlay", 'gg_ml'); ?> .." autocomplete="off">
                  <option value="">(<?php _e('original one', 'gg_ml') ?>)</option>
                  <?php
				  foreach($overlays as $ol) {
					  $sel = ($ol->term_id == $fdata['gg_gall_default_overlay']) ? 'selected="selected"' : '';
					  echo '<option value="'.$ol->term_id.'" '.$sel.'>'.$ol->name.'</option>'; 
				  }
				  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose the default overlay to apply to galleries", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Default collections overlay", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
               <select name="gg_coll_default_overlay" class="lcweb-chosen" data-placeholder="<?php _e("Select an overlay", 'gg_ml'); ?> .." autocomplete="off">
                  <option value="">(<?php _e('original one', 'gg_ml') ?>)</option>
                  <?php
				  foreach($overlays as $ol) {
					  $sel = ($ol->term_id == $fdata['gg_coll_default_overlay']) ? 'selected="selected"' : '';
					  echo '<option value="'.$ol->term_id.'" '.$sel.'>'.$ol->name.'</option>'; 
				  }
				  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose the default overlay to apply to collections", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Default carousels overlay", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
               <select name="gg_car_default_overlay" class="lcweb-chosen" data-placeholder="<?php _e("Select an overlay", 'gg_ml'); ?> .." autocomplete="off">
                  <option value="">(<?php _e('original one', 'gg_ml') ?>)</option>
                  <?php
				  foreach($overlays as $ol) {
					  $sel = ($ol->term_id == $fdata['gg_car_default_overlay']) ? 'selected="selected"' : '';
					  echo '<option value="'.$ol->term_id.'" '.$sel.'>'.$ol->name.'</option>'; 
				  }
				  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose the default overlay to apply to carousels", 'mg_ml'); ?></span></td>
          </tr>
        </table> 
        
		<?php endif; 
		////////////////////////////////// ?>
        
        
        <h3><?php _e("Image Protection", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Disable right click", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_disable_rclick'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_disable_rclick" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("Check to disable right click on gallery images", 'gg_ml'); ?></span></td>
          </tr>
        </table>  
        
        <h3>
			<?php _e("Global Gallery album bases", 'gg_ml'); ?> 
        	<small style="font-weight: normal; padding-left: 10px;">(<?php _e("changing these values could break albums, be careful", 'gg_ml'); ?>)</small>
        </h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td" rowspan="2"><?php _e("Albums basepath", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" value="<?php echo $fdata['gg_albums_basepath'] ?>" name="gg_albums_basepath" />
            </td>
          </tr>
          <tr>
          	<td><?php _e("Default one is", 'gg_ml'); ?> <em><?php echo GGA_DIR ?></em></td>
          </tr>
          <tr>
            <td class="lcwp_label_td" rowspan="2"><?php _e("Albums baseurl", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" value="<?php echo $fdata['gg_albums_baseurl'] ?>" name="gg_albums_baseurl" />
            </td>
          </tr>
          <tr>
          	<td><?php _e("Default one is", 'gg_ml'); ?> <em><?php echo GGA_URL ?></em></td>
          </tr>
        </table>  
        
        <h3><?php _e("Advanced", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Thumbnails quality", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="100" min="50"></div>
                <?php if((int)$fdata['gg_thumb_q'] == 0) {$fdata['gg_thumb_q'] = 100;} ?>
                <input type="text" value="<?php echo $fdata['gg_thumb_q']; ?>" name="gg_thumb_q" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e("Quality of the images thumbnail", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use thumbnails on admin side?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_use_admin_thumbs'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_use_admin_thumbs" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e("Check to use thumbnails on admin side. Could slow down the server in case of huge galleries", 'gg_ml'); ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Global Gallery Albums - images title", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." name="gga_img_title_src" class="lcweb-chosen" tabindex="2">
                    <option value="filename"><?php _e("File name", 'gg_ml'); ?></option>  
                    <option value="iptc" <?php if($fdata['gga_img_title_src'] == 'iptc') {echo 'selected="selected"';} ?>><?php _e("IPTC metadata", 'gg_ml'); ?></option>
                </select>
            </td>
            <td><span class="info"><?php _e("Choose which data is used to get GG album images title", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Enable AJAX support?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_enable_ajax'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_enable_ajax" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e("Enable support for AJAX-loaded galleries and collections", 'gg_ml'); ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Disable deeplinking?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_disable_dl'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_disable_dl" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('If checked, disable collection filters and galleries deeplinking', 'gg_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use custom CSS inline?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_force_inline_css'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_force_inline_css" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('If checked, uses custom CSS inline (useful for multisite installations)', 'gg_ml') ?></span>
            </td>
          </tr>
          <tr <?php if($fdata['gg_use_timthumb']) {echo 'style="display: none;"';} ?>>
            <td class="lcwp_label_td"><?php _e("Use Easy WP Thumbs forcing system?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_ewpt_force'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_ewpt_force" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('Try forcing thumbnails creation, check it ONLY if you note missing thumbnails', 'gg_ml') ?></span>
            </td>
          </tr>
          <tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use TimThumb?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_use_timthumb'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_use_timthumb" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e("If checked, use Timthumb instead of Easy WP Thumbs", 'gg_ml'); ?></span>
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Use javascript in website's head?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['gg_js_head'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_js_head" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e("Put javascript in website's head, check it ONLY IF you notice some incompatibilities", 'gg_ml'); ?></span>
            </td>
          </tr> 
        </table> 
        
        <?php if(is_multisite() && get_option('gg_use_timthumb')) : ?>
            <h3><?php _e("Timthumb basepath", 'gg_ml'); ?> <small>(<?php _e("for MU installations", 'gg_ml'); ?>)</small></h3>
            <table class="widefat lcwp_table">
              <tr>
                <td class="lcwp_label_td"><?php _e("Basepath of the WP MU images", 'gg_ml'); ?></td>
                <td>
                    <?php if(!$fdata['gg_wpmu_path'] || trim($fdata['gg_wpmu_path']) == '') { $fdata['gg_wpmu_path'] = gg_wpmu_upload_dir();} ?>
                    <input type="text" value="<?php echo gg_sanitize_input($fdata['gg_wpmu_path']) ?>" name="gg_wpmu_path" style="width: 90%;" />
                    
                    <p class="info" style="margin-top: 3px;"><?php _e("By default is", 'gg_ml'); ?>: 
                    	<span style="font-family: Tahoma, Geneva, sans-serif; font-size: 13px; color: #727272;"><?php echo gg_wpmu_upload_dir(); ?></span>
                    </p>
                </td>
              </tr> 
            </table> 
        <?php endif; ?>  

        <?php if(!get_option('gg_use_timthumb')) {ewpt_wpf_form();} ?>
    </div>   
    
    <!-- **************************************************** -->
    
    <div id="layouts">
    	<h3><?php _e("Standard Layout", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Thumbnail Sizes", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" name="gg_thumb_w" value="<?php echo $fdata['gg_thumb_w'] ?>" maxlength="4" class="lcwp_slider_input" /> x 
            	<input type="text" name="gg_thumb_h" value="<?php echo $fdata['gg_thumb_h'] ?>" maxlength="4" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the default sizes of the standard layout thumbnails", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Horizontal Margin", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="40" min="0"></div>
                <input type="text" value="<?php echo(int)$fdata['gg_standard_hor_margin']; ?>" name="gg_standard_hor_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the horizontal margin between the images", 'gg_ml'); ?></span></td>
          </tr>
		  <tr>
            <td class="lcwp_label_td"><?php _e("Vertical Margin", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="40" min="0"></div>
                <input type="text" value="<?php echo(int)$fdata['gg_standard_ver_margin']; ?>" name="gg_standard_ver_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the vertical margin between the images", 'gg_ml'); ?></span></td>
          </tr>
        </table>
        
        <h3><?php _e("Masonry Layout", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Image Columns", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<?php if((int)$fdata['gg_masonry_cols'] == 0) {$fdata['gg_masonry_cols'] = 4;} ?>
                <div class="lcwp_slider" step="1" max="20" min="1"></div>
                <input type="text" value="<?php echo $fdata['gg_masonry_cols']; ?>" name="gg_masonry_cols" class="lcwp_slider_input" />
            </td>
            <td><span class="info"><?php _e("Set the default columns number for the masonry layout", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Images Margin", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo(int)$fdata['gg_masonry_margin']; ?>" name="gg_masonry_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the margin between the images", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Minimum image width", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<?php if((int)$fdata['gg_masonry_min_width'] == 0) {$fdata['gg_masonry_min_width'] = 150;} ?>
                <input type="text" value="<?php echo $fdata['gg_masonry_min_width']; ?>" name="gg_masonry_min_width" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the minimum width for the images in the masonry gallery", 'gg_ml'); ?></span></td>
          </tr>
        </table>
        
        <h3><?php _e("PhotoString Layout", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Thumbnails Height", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<?php if((int)$fdata['gg_photostring_h'] == 0) {$fdata['gg_photostring_h'] = 185;} ?>
                <input type="text" value="<?php echo (int)$fdata['gg_photostring_h']; ?>" name="gg_photostring_h" maxlength="4" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the default columns number for the masonry layout", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Images Margin", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo(int)$fdata['gg_photostring_margin']; ?>" name="gg_photostring_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the margin between the images", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Minimum image width", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<?php if((int)$fdata['gg_photostring_min_width'] == 0) {$fdata['gg_photostring_min_width'] = 120;} ?>
                <input type="text" value="<?php echo $fdata['gg_photostring_min_width']; ?>" name="gg_photostring_min_width" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the minimum width for the images in the photostring gallery", 'gg_ml'); ?></span></td>
          </tr>
        </table>
        
        <h3><?php _e("Collections Layout", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Thumbnails Disposition", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select name="gg_coll_thumb_w" data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." class="lcweb-chosen" tabindex="2">
                  <?php 
                  $cols = gg_coll_widths();
                  foreach($cols as $val => $name) { 
				  	$sel = ($val == $fdata['gg_coll_thumb_w']) ? 'selected="selected"' : '';
				  	echo '<option value="'.$val.'" '.$sel.'>'.$name.'</option>'; 
				  }
                  ?>
                </select>
            </td>
            <td><span class="info"><?php _e("Set the collection columns", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Collection columns minimum width", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<input type="text" name="gg_coll_thumb_min_w" value="<?php echo $fdata['gg_coll_thumb_min_w'] ?>" maxlength="4" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set collection columns minimum width", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Thumbnails Height", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<input type="text" name="gg_coll_thumb_h" value="<?php echo $fdata['gg_coll_thumb_h'] ?>" maxlength="4" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the thumbs height for the collections", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Horizontal Margin", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="40" min="0"></div>
                <input type="text" value="<?php echo(int)$fdata['gg_coll_hor_margin']; ?>" name="gg_coll_hor_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the horizontal margin between the images", 'gg_ml'); ?></span></td>
          </tr>
		  <tr>
            <td class="lcwp_label_td"><?php _e("Vertical Margin", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="40" min="0"></div>
                <input type="text" value="<?php echo(int)$fdata['gg_coll_ver_margin']; ?>" name="gg_coll_ver_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the vertical margin between the images", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb lb_opt_colorbox lb_opt_prettyphoto">
            <td class="lcwp_label_td"><?php _e("Titles under images?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php ($fdata['gg_coll_title_under'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_coll_title_under" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked, galleries title will be displayed under the images", 'gg_ml'); ?></span></td>
          </tr>
        </table>
    </div> 
    
    <!-- **************************************************** -->
    
    <div id="styling"> 
    	<h3><?php _e("Predefined Styles", 'gg_ml'); ?></h3>
        
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Choose a style", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e("Select a style", 'gg_ml'); ?> .." name="gg_pred_styles" id="gg_pred_styles" class="lcweb-chosen" autocomplete="off">
                	<option value="" selected="selected"></option>
					<?php 
                    $styles = gg_predefined_styles();
                    foreach($styles as $style => $val) { 
                      echo '<option value="'.$style.'">'.$style.'</option>'; 
                    }
                    ?>
                </select>
            </td>
            <td>
            	<input type="button" name="gg_set_style" id="gg_set_style" value="Set" class="button-secondary" />
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Preview", 'gg_ml'); ?></td>
            <td class="lcwp_field_td" colspan="2">
            	<?php
				$styles = gg_predefined_styles();
                foreach($styles as $style => $val) { 
				  echo '<img src="'.GG_URL.'/img/pred_styles_demo/'.$val['preview'].'" class="gg_styles_preview" alt="'.$style.'" style="display: none;" />';	
				}
				?>
            </td>
          </tr>
        </table>
        
       
        <h3><?php _e("Images Layout", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Loader Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_loader_color']; ?>;"></span>
                	<input type="text" name="gg_loader_color" value="<?php echo $fdata['gg_loader_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Loading animation color', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Image Border Size", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="15" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_img_border']; ?>" name="gg_img_border" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the images border size", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Image Border Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_img_border_color']; ?>;"></span>
                	<input type="text" name="gg_img_border_color" value="<?php echo $fdata['gg_img_border_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Set images border color - accepts also "transparent" value', 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Image Outline Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_img_outline_color']; ?>;"></span>
                	<input type="text" name="gg_img_outline_color" value="<?php echo $fdata['gg_img_outline_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e("Set images outline color (must be enabled to be shown)", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Images Border Radius", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="25" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_img_radius']; ?>" name="gg_img_radius" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Set the corner radius for the images", 'gg_ml'); ?></span></td>
          </tr>          
          <tr>
            <td class="lcwp_label_td"><?php _e("Outer image effect", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." name="gg_img_shadow" class="lcweb-chosen" tabindex="2">
                    <option value=""><?php _e("No Effect", 'gg_ml'); ?></option>  
                    <option value="outshadow" <?php if($fdata['gg_img_shadow'] == 'outshadow') {echo 'selected="selected"';} ?>><?php _e("Soft shadow", 'gg_ml'); ?></option>
                    <option value="outline" <?php if($fdata['gg_img_shadow'] == 'outline') {echo 'selected="selected"';} ?>><?php _e("Outline", 'gg_ml'); ?></option>  
                </select>
            </td>
            <td><span class="info"><?php _e("Choose which effect use for images aspect", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Thumbnail Effect", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." name="gg_thumb_fx" class="lcweb-chosen" tabindex="2">
                    <option value=""><?php _e("No Effect", 'gg_ml'); ?></option>  
                    <option value="grayscale" <?php if($fdata['gg_thumb_fx'] == 'grayscale') {echo 'selected="selected"';} ?>><?php _e("Grayscale mask", 'gg_ml'); ?></option>
                    <option value="blur" <?php if($fdata['gg_thumb_fx'] == 'blur') {echo 'selected="selected"';} ?>><?php _e("Blurred on hover", 'gg_ml'); ?></option>  
                </select>
            </td>
            <td><span class="info"><?php _e("Set the thumbnails effect", 'gg_ml'); ?></span></td>
          </tr> 
        </table> 
        
        <h3><?php _e("Images Overlay", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Overlay Type", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." name="gg_overlay_type" class="lcweb-chosen" tabindex="2">
                    <option value=""><?php _e("No overlay", 'gg_ml'); ?></option>  
                    <option value="primary" <?php if($fdata['gg_overlay_type'] == 'primary') {echo 'selected="selected"';} ?>><?php _e("Only main overlay", 'gg_ml'); ?></option>
                    <option value="both" <?php if($fdata['gg_overlay_type'] == 'both') {echo 'selected="selected"';} ?>><?php _e("Both overlays", 'gg_ml'); ?></option>  
                </select>
            </td>
            <td><span class="info"><?php _e("Set the overlay for the images", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Main Overlay Type", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." name="gg_main_overlay" class="lcweb-chosen" tabindex="2">
                    <option value="bottom"><?php _e("Bottom Bar", 'gg_ml'); ?></option>  
                    <option value="top" <?php if($fdata['gg_main_overlay'] == 'top') {echo 'selected="selected"';} ?>><?php _e("Top Bar", 'gg_ml'); ?></option>
                    <option value="full" <?php if($fdata['gg_main_overlay'] == 'full') {echo 'selected="selected"';} ?>><?php _e("Full image", 'gg_ml'); ?></option>  
                </select>
            </td>
            <td><span class="info"><?php _e("Set the main overlay type", 'gg_ml'); ?></span></td>
          </tr>
          <tr> 
          <td class="lcwp_label_td"><?php _e("Main Overlay Behavior", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." name="gg_main_ol_behav" class="lcweb-chosen" tabindex="2">
                    <option value="show_on_h"><?php _e("Show on hover", 'gg_ml'); ?></option>  
                    <option value="always_shown" <?php if($fdata['gg_main_ol_behav'] == 'always_shown') {echo 'selected="selected"';} ?>><?php _e("Always shown", 'gg_ml'); ?></option>
                    <option value="hide_on_h" <?php if($fdata['gg_main_ol_behav'] == 'hide_on_h') {echo 'selected="selected"';} ?>><?php _e("Hide on hover", 'gg_ml'); ?></option>  
                </select>
            </td>
            <td><span class="info"><?php _e("Set main overlay behavior on hover", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Main Overlay Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_main_ol_color']; ?>;"></span>
                	<input type="text" name="gg_main_ol_color" value="<?php echo gg_rgb2hex($fdata['gg_main_ol_color']); ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e("Set the main overlay background color", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Main Overlay Opacity", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="100" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_main_ol_opacity']; ?>" name="gg_main_ol_opacity" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e("Set the overlay opacity (not for &lt; IE9)", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Main Overlay Text Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_main_ol_txt_color']; ?>;"></span>
                	<input type="text" name="gg_main_ol_txt_color" value="<?php echo gg_rgb2hex($fdata['gg_main_ol_txt_color']); ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e("Set the main overlay text color", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Secondary Overlay Position", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." name="gg_sec_overlay" class="lcweb-chosen" tabindex="2">
                    <option value="tl"><?php _e("Top-left corner", 'gg_ml'); ?></option>  
                    <option value="tr" <?php if($fdata['gg_sec_overlay'] == 'tr') {echo 'selected="selected"';} ?>><?php _e("Top-right corner", 'gg_ml'); ?></option>
                    <option value="bl" <?php if($fdata['gg_sec_overlay'] == 'bl') {echo 'selected="selected"';} ?>><?php _e("Bottom-left corner", 'gg_ml'); ?></option>
                    <option value="br" <?php if($fdata['gg_sec_overlay'] == 'br') {echo 'selected="selected"';} ?>><?php _e("Bottom-right corner", 'gg_ml'); ?></option>  
                </select>
            </td>
            <td><span class="info"></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Secondary Overlay Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_sec_ol_color']; ?>;"></span>
                	<input type="text" name="gg_sec_ol_color" value="<?php echo $fdata['gg_sec_ol_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e("Set the secondary overlay background color", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Secondary Overlay Icons Color", 'gg_ml'); ?></td>
           	<td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_icons_col']; ?>;"></span>
                	<input type="text" name="gg_icons_col" value="<?php echo $fdata['gg_icons_col']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Color of the icons in the secondary overlay', 'gg_ml') ?></span></td>
          </tr>
        </table>  
        
        <h3><?php _e("Collections - Texts under images", 'gg_ml'); ?></h3>
		<table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Title color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_txt_u_title_color']; ?>;"></span>
                	<input type="text" name="gg_txt_u_title_color" value="<?php echo $fdata['gg_txt_u_title_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e("Gallery title's color", 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Description color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_txt_u_descr_color']; ?>;"></span>
                	<input type="text" name="gg_txt_u_descr_color" value="<?php echo $fdata['gg_txt_u_descr_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e("Gallery description's color", 'gg_ml') ?></span></td>
          </tr>
      	</table> 
        
        <h3><?php _e("Collection Filters", 'gg_ml'); ?></h3>
		<table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Text Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_filters_txt_color']; ?>;"></span>
                	<input type="text" name="gg_filters_txt_color" value="<?php echo $fdata['gg_filters_txt_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters text color - default status', 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Background Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_filters_bg_color']; ?>;"></span>
                	<input type="text" name="gg_filters_bg_color" value="<?php echo $fdata['gg_filters_bg_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters background color - default status', 'gg_ml') ?> <?php _e('(not for old style)', 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_filters_border_color']; ?>;"></span>
                	<input type="text" name="gg_filters_border_color" value="<?php echo $fdata['gg_filters_border_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters border color - default status', 'gg_ml') ?> <?php _e('(not for old style)', 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Text Color (on mouse hover)", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_filters_txt_color_h']; ?>;"></span>
                	<input type="text" name="gg_filters_txt_color_h" value="<?php echo $fdata['gg_filters_txt_color_h']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters text color - mouse hover status', 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Background Color (on mouse hover)", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_filters_bg_color_h']; ?>;"></span>
                	<input type="text" name="gg_filters_bg_color_h" value="<?php echo $fdata['gg_filters_bg_color_h']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters background color - mouse hover status', 'gg_ml') ?> <?php _e('(not for old style)', 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Color (on mouse hover)", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_filters_border_color_h']; ?>;"></span>
                	<input type="text" name="gg_filters_border_color_h" value="<?php echo $fdata['gg_filters_border_color_h']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters border color - mouse hover status', 'gg_ml') ?> <?php _e('(not for old style)', 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Text Color (selected filter)", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_filters_txt_color_sel']; ?>;"></span>
                	<input type="text" name="gg_filters_txt_color_sel" value="<?php echo $fdata['gg_filters_txt_color_sel']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters text color - selected status', 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Background Color (selected filter)", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_filters_bg_color_sel']; ?>;"></span>
                	<input type="text" name="gg_filters_bg_color_sel" value="<?php echo $fdata['gg_filters_bg_color_sel']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters background color - selected status', 'gg_ml') ?> <?php _e('(not for old style)', 'gg_ml') ?></span></td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Color (selected filter)", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_filters_border_color_sel']; ?>;"></span>
                	<input type="text" name="gg_filters_border_color_sel" value="<?php echo $fdata['gg_filters_border_color_sel']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Filters border color - selected status', 'gg_ml') ?> <?php _e('(not for old style)', 'gg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Radius", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_filters_radius']; ?>" name="gg_filters_radius" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the border radius for each filter', 'gg_ml') ?> (<?php _e('not for old style', 'gg_ml') ?>)</span></td>
          </tr>
        </table>  
    </div>
    
    <!-- **************************************************** -->

	<div id="lightbox">
    	<table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Which lightbox use?" ); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." name="gg_lightbox" id="gg_lightbox" class="lcweb-chosen" autocomplete="off"style="width: 300px;">
                  <option value="lcweb">LCweb Lightbox (with fullscreen)</option> 
                  <option value="simplelb" <?php if($fdata['gg_lightbox'] == 'simplelb') {echo 'selected="selected"';} ?>>Simple Lightbox</option>
                  <option value="tosrus" <?php if($fdata['gg_lightbox'] == 'tosrus') {echo 'selected="selected"';} ?>>Tos "R"Us</option>
                  <option value="lightgall" <?php if($fdata['gg_lightbox'] == 'lightgall') {echo 'selected="selected"';} ?>>lightGallery</option> 
                  <option value="mag_popup" <?php if($fdata['gg_lightbox'] == 'mag_popup') {echo 'selected="selected"';} ?>>Magnific Popup</option> 
                  <option value="photobox" <?php if($fdata['gg_lightbox'] == 'photobox') {echo 'selected="selected"';} ?>>Photobox</option> 
                  <option value="fancybox" <?php if($fdata['gg_lightbox'] == 'fancybox') {echo 'selected="selected"';} ?>>Fancybox (not responsive)</option>
                  <option value="colorbox" <?php if($fdata['gg_lightbox'] == 'colorbox') {echo 'selected="selected"';} ?>>Colorbox</option>
                  <option value="prettyphoto" <?php if($fdata['gg_lightbox'] == 'prettyphoto') {echo 'selected="selected"';} ?>>PrettyPhoto (not responsive)</option> 
                </select>
            </td>
          </tr>
        </table> 
    
    	<h3 id="gg_lb_opt_title"><?php _e("Lightbox Settings", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table gg_lb_options">
         <tr class="lb_opt_lcweb lb_opt_simplelb">
            <td class="lcwp_label_td"><?php _e("Style", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select data-placeholder="<?php _e("Select a style", 'gg_ml'); ?> .." name="gg_lb_lcl_style" class="lcweb-chosen" tabindex="2">
                  <option value="light">Light</option>  
                  <option value="dark" <?php if($fdata['gg_lb_lcl_style'] == 'dark') {echo 'selected="selected"';} ?>>Dark</option>
                </select>
            </td>
            <td><span class="info"><?php _e("Select the lightbox style", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_colorbox">
            <td class="lcwp_label_td"><?php _e("Style", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select data-placeholder="<?php _e("Select a style", 'gg_ml'); ?> .." name="gg_lb_col_style" class="lcweb-chosen" tabindex="2">
				  <?php
				  for($a=1; $a<=5; $a++) {
					 ($fdata['gg_lb_col_style'] == $a) ? $sel = 'selected="selected"' : $sel = '';
					 echo '<option value="'.$a.'" '.$sel.'>Style '.$a.'</option>'; 
				  }
				  ?>
                </select>
            </td>
            <td><span class="info"><?php _e("Select the lightbox style", 'gg_ml'); ?></span></td>
          </tr>		
         
          <tr class="lb_opt_lcweb lb_opt_fancybox lb_opt_colorbox lb_opt_prettyphoto lb_opt_mag_popup lb_opt_tosrus lb_opt_simplelb lb_opt_lightgall">
            <td class="lcwp_label_td"><?php _e("Overlay Opacity", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="100" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_lb_opacity']; ?>" name="gg_lb_opacity" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e("Set the overlay opacity", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb lb_opt_fancybox lb_opt_mag_popup lb_opt_tosrus lb_opt_lightgall lb_opt_simplelb">
            <td class="lcwp_label_td"><?php _e("Overlay Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php if(!$fdata['gg_lb_ol_color']) {$fdata['gg_lb_ol_color'] = '#000000';} ?>
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_lb_ol_color']; ?>;"></span>
                	<input type="text" name="gg_lb_ol_color" value="<?php echo $fdata['gg_lb_ol_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e("Select the overlay color", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb lb_opt_mag_popup lb_opt_simplelb">
            <td class="lcwp_label_td"><?php _e("Overlay Pattern", 'gg_ml'); ?></td>
            <td class="lcwp_field_td" colspan="2">
            	<input type="hidden" value="<?php echo $fdata['gg_lb_ol_pattern']; ?>" name="gg_lb_ol_pattern" id="gg_lb_ol_pattern" />
            
            	<div class="gg_setting_pattern <?php if(!$fdata['gg_lb_ol_pattern'] || $fdata['gg_lb_ol_pattern'] == 'none') {echo 'gg_pattern_sel';} ?>" id="ggp_none"> no pattern </div>
                
                <?php 
				foreach(gg_lcl_patterns_list() as $file => $name) {
					($fdata['gg_lb_ol_pattern'] == $name) ? $sel = 'gg_pattern_sel' : $sel = '';  
					echo '<div class="gg_setting_pattern '.$sel.'" id="ggp_'.$name.'" style="background: url('.GG_URL.'/js/lightboxes/lcweb.lightbox/img/patterns/'.$file.') repeat top left transparent;"></div>';		
				}
				?>
            </td>
          </tr>
          
          <tr class="lb_opt_lcweb lb_opt_colorbox lb_opt_mag_popup lb_opt_simplelb">
            <td class="lcwp_label_td"><?php _e("Maximum Width", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php if((int)$fdata['gg_lb_max_w'] == 0) {$fdata['gg_lb_max_w'] = 85;} ?>
            	<div class="lcwp_slider" step="1" max="100" min="20"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_lb_max_w']; ?>" name="gg_lb_max_w" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e("Set the max width of the lightbox rapported to the browser", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb lb_opt_colorbox lb_opt_simplelb">
            <td class="lcwp_label_td"><?php _e("Maximum Height", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php if((int)$fdata['gg_lb_max_h'] == 0) {$fdata['gg_lb_max_h'] = 75;} ?>
            	<div class="lcwp_slider" step="1" max="100" min="20"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_lb_max_h']; ?>" name="gg_lb_max_h" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e("Set the max height of the lightbox rapported to the browser", 'gg_ml'); ?></span></td>
          </tr>
          
          <tr class="lb_opt_lcweb lb_opt_fancybox">
            <td class="lcwp_label_td"><?php _e("Content Padding", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_lb_padding']; ?>" name="gg_lb_padding" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Padding of the lightbox content", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb">
            <td class="lcwp_label_td"><?php _e("Border Width", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_lb_border_w']; ?>" name="gg_lb_border_w" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Border width of the lightbox", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb">
            <td class="lcwp_label_td"><?php _e("Border Color", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php if(!$fdata['gg_lb_border_col']) {$fdata['gg_lb_border_col'] = '#444444';} ?>
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['gg_lb_border_col']; ?>;"></span>
                	<input type="text" name="gg_lb_border_col" value="<?php echo $fdata['gg_lb_border_col']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e("Border color of the lightbox", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb lb_opt_simplelb">
            <td class="lcwp_label_td"><?php _e("Corner Radius", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="30" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_lb_radius']; ?>" name="gg_lb_radius" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Corner radius of the lightbox (not for IE < 9)", 'gg_ml'); ?></span></td>
          </tr>
          
          <tr class="lb_opt_lcweb lb_opt_fancybox">
            <td class="lcwp_label_td"><?php _e("Text position", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select data-placeholder="<?php _e("Select a position", 'gg_ml'); ?> .." name="gg_lb_txt_pos" class="lcweb-chosen" tabindex="2">
                  <option value="standard"><?php _e("Under the image", 'gg_ml'); ?></option>  
                  <option value="over" <?php if($fdata['gg_lb_txt_pos'] == 'over') {echo 'selected="selected"';} ?>><?php _e("Over the image", 'gg_ml'); ?></option>
                </select>
            </td>
            <td><span class="info"><?php _e("Select the text position in the lightbox", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb lb_opt_prettyphoto lb_opt_tosrus lb_opt_lightgall lb_opt_photobox">
            <td class="lcwp_label_td"><?php _e("Use thumbnails navigation?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php ($fdata['gg_lb_thumbs'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_lb_thumbs" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked, thumbnails will be shown in the lightbox", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb">
            <td class="lcwp_label_td"><?php _e("Use full images in thumbnails navigation?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php ($fdata['gg_lb_thumbs_full_img'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_lb_thumbs_full_img" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked, fills thumbnails with full-size images (enable ONLY if you note thumbs loading issues)", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb lb_opt_tosrus">
            <td class="lcwp_label_td"><?php _e("Enable fullscreen?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php ($fdata['gg_lb_fullscreen'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_lb_fullscreen" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked, allow to use the fullscreen mode", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb">
            <td class="lcwp_label_td"><?php _e("Use only fullscreen?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select data-placeholder="<?php _e("Select an option", 'gg_ml'); ?> .." name="gg_lb_fs_only" class="lcweb-chosen" tabindex="2">
                  <option value="none"><?php _e("No", 'gg_ml'); ?></option>  
                  <option value="mobile" <?php if($fdata['gg_lb_fs_only'] == 'mobile') {echo 'selected="selected"';} ?>><?php _e("Only for mobile", 'gg_ml'); ?></option>
                  <option value="always" <?php if($fdata['gg_lb_fs_only'] == 'always') {echo 'selected="selected"';} ?>><?php _e("Always", 'gg_ml'); ?></option>
                </select>
            </td>
            <td><span class="info"><?php _e("If checked, use only the fullscreen mode", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb">
            <td class="lcwp_label_td"><?php _e("Fullscreen image behaviour", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select data-placeholder="<?php _e("Select a behaviour", 'gg_ml'); ?> .." name="gg_lb_fs_method" class="lcweb-chosen" tabindex="2">
                  <option value="fill"><?php _e("Fill the screen", 'gg_ml'); ?></option>  
                  <option value="fit" <?php if($fdata['gg_lb_fs_method'] == 'fit') {echo 'selected="selected"';} ?>><?php _e("Fit the screen", 'gg_ml'); ?></option> 
                  <option value="smart" <?php if($fdata['gg_lb_fs_method'] == 'smart') {echo 'selected="selected"';} ?>><?php _e("Smart", 'gg_ml'); ?></option>
                  <option value="none" <?php if($fdata['gg_lb_fs_method'] == 'none') {echo 'selected="selected"';} ?>><?php _e("None", 'gg_ml'); ?></option>
                </select>
            </td>
            <td><span class="info"><?php _e("Select an image behaviour for the fullscreen view", 'gg_ml'); ?></span></td>
          </tr>
          
          <tr class="lb_opt_lcweb lb_opt_prettyphoto">
            <td class="lcwp_label_td"><?php _e("Enable socials?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php ($fdata['gg_lb_socials'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_lb_socials" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked, enables the social share", 'gg_ml'); ?></span></td>
          </tr>
          
          <tr class="lb_opt_lcweb lb_opt_colorbox lb_opt_prettyphoto lb_opt_lightgall lb_opt_photobox">
            <td class="lcwp_label_td"><?php _e("Auto start slideshow?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php ($fdata['gg_lb_slideshow'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_lb_slideshow" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e("If checked, starts the slideshow on opening", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb lb_opt_colorbox lb_opt_prettyphoto lb_opt_lightgall lb_opt_photobox">
            <td class="lcwp_label_td"><?php _e("Slideshow Interval", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php if((int)$fdata['gg_lb_ss_time'] == 0) {$fdata['gg_lb_ss_time'] = 5000;} ?>
            	<div class="lcwp_slider" step="200" max="15000" min="2000"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_lb_ss_time']; ?>" name="gg_lb_ss_time" class="lcwp_slider_input" />
                <span>ms</span>
            </td>
            <td><span class="info"><?php _e("Set the slideshow interval time in milliseconds (default 5000)", 'gg_ml'); ?></span></td>
          </tr>
          <tr class="lb_opt_lcweb lb_opt_fancybox lb_opt_colorbox lb_opt_prettyphoto lb_opt_lightgall lb_opt_simplelb">
            <td class="lcwp_label_td"><?php _e("Animation time", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
           		<?php if((int)$fdata['gg_lb_time'] == 0) {$fdata['gg_lb_time'] = 300;} ?>
            	<div class="lcwp_slider" step="10" max="2000" min="100"></div>
                <input type="text" value="<?php echo (int)$fdata['gg_lb_time']; ?>" name="gg_lb_time" class="lcwp_slider_input" />
                <span>ms</span>
            </td>
            <td><span class="info"><?php _e("Set the animation time between images in milliseconds (default 300)", 'gg_ml'); ?></span></td>
          </tr>
          
          <tr class="lb_opt_tosrus lb_opt_lightgall lb_opt_simplelb">
            <td class="lcwp_label_td"><?php _e("Animation behavior", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select data-placeholder="<?php _e("Select a style", 'gg_ml'); ?> .." name="gg_lb_anim_behav" class="lcweb-chosen" tabindex="2">
                  <option value="slide">Slide</option>  
                  <option value="fade" <?php if($fdata['gg_lb_anim_behav'] == 'fade') {echo 'selected="selected"';} ?>>Fade</option>
                </select>
            </td>
            <td><span class="info"><?php _e("Select the animation behavior navigating though images", 'gg_ml'); ?></span></td>
          </tr>
        </table> 
    </div>
    
    <!-- **************************************************** -->
    
    <div id="watermark"> 
    	<h3><?php _e("Watermark", 'gg_ml'); ?></h3>
        
        <?php
		// folder permissions check
		if(!is_writable(GG_DIR.'/cache')) :
		?>
        <br />
        <p><?php _e('The watermarker function requires writing permissions in', 'gg_ml') ?> <em>"<?php echo GG_DIR.'/cache' ?>"</em> <?php _e('by your server. Please check them.', 'gg_ml') ?></p>
  		<br/><br/>
        
        <?php else : ?>
        <table class="widefat lcwp_table">
          <tr>
          	<td class="lcwp_label_td"><?php _e("Watermark image", 'gg_ml'); ?></td>
			<td style="width: 308px;">
			  	<input type="text" name="gg_watermark_img" id="gg_watermark_img" value="<?php echo gg_sanitize_input($fdata['gg_watermark_img']) ?>" placeholder="<?php _e('use wizard or paste image URL', 'gg_ml') ?>" style="width: 265px; margin-bottom: 10px;" autocomplete="off" /><br/>
			  	<input type="button" value="<?php _e('Select image or upload a new one', 'gg_ml') ?>" id="gg_wm_media_manag" class="button-secondary" style="width: 265px;" />
            </td>
            <td>  
            	<div class="lcwp_upload_imgwrap">
				  <?php
                  if( !empty($fdata['gg_watermark_img']) && preg_match( '/(^.*\.jpg|jpeg|png|gif*)/i', strtolower($fdata['gg_watermark_img']))) {
					  echo '
					  <img src="'.$fdata['gg_watermark_img'].'" />
					  <span class="lcwp_del_ul_img" title="remove image"></span>';
				  }
				  else {echo '<div class="no_image"></div>';}
                  ?>
            	</div>      
			</td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Watermark Opacity", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<?php if((int)$fdata['gg_watermark_opacity'] == 0) {$fdata['gg_watermark_opacity'] = 80;} ?>
                <div class="lcwp_slider" step="1" max="100" min="10"></div>
                <input type="text" value="<?php echo $fdata['gg_watermark_opacity']; ?>" name="gg_watermark_opacity" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e("Set watermark opacity", 'gg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Watermark position", 'gg_ml'); ?></td>
            <td colspan="2">
            	<?php (!$fdata['gg_watermark_pos']) ? $sel = 'MM' : $sel = $fdata['gg_watermark_pos']; ?>
            	<input type="hidden" value="<?php echo $sel; ?>" name="gg_watermark_pos" id="gg_watermark_pos" />
                
                <table class="gg_sel_thumb_center">
                    <tr>
                        <td id="gg_LT"></td>
                        <td id="gg_MT"></td>
                        <td id="gg_RT"></td>
                    </tr>
                    <tr>
                        <td id="gg_LM"></td>
                        <td id="gg_MM"></td>
                        <td id="gg_RM"></td>
                    </tr>
                    <tr>
                        <td id="gg_LB"></td>
                        <td id="gg_MB"></td>
                        <td id="gg_RB"></td>
                    </tr>
                </table>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Create watermark cache", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<input type="button" value="<?php _e("Create", 'gg_ml'); ?>" id="gg_create_cache" class="button-secondary" style="width: auto;" />
            </td>
            <td><span class="gg_wm_create_status info" style="color: #222;"></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Clean watermark cache", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<input type="button" value="<?php _e("Clean", 'gg_ml'); ?>" id="gg_clean_cache" class="button-secondary" style="width: auto;" />
            </td>
            <td><span class="gg_wm_clean_status info" style="display: block; color: #222;"></span></td>
          </tr>
    	</table>
        <?php endif; // permissions check ?>  
    </div>   
    
    <!-- **************************************************** -->
    
    <div id="cust_css">    
        <h3><?php _e("Custom CSS", 'gg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_field_td">
            	<textarea name="gg_custom_css" style="width: 100%; min-height: 300px;" rows="6"><?php echo $fdata['gg_custom_css']; ?></textarea>
            </td>
          </tr>
        </table>
        
        <h3><?php _e("Elements Legend", 'gg_ml'); ?></h3> 
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td">.gg_gallery_wrap</td>
            <td><span class="info">Gallery container</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.gg_img</td>
            <td><span class="info">Single image block</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.gg_main_overlay</td>
            <td><span class="info">Primary overlay</span></td>
          </tr>	
          <tr>
            <td class="lcwp_label_td">.gg_img_title</td>
            <td><span class="info">Image title</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.gg_sec_overlay</td>
            <td><span class="info">Secondary overlay</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.gg_paginate</td>
            <td><span class="info">Pagination button wrapper</span></td>
          </tr>
        </table> 
    </div> 
   
   	<input type="hidden" name="lcwp_nonce" value="<?php echo wp_create_nonce('lcweb') ?>" /> 
    <input type="submit" name="lcwp_admin_submit" value="<?php _e('Update Options', 'gg_ml' ) ?>" class="button-primary" />  
    
	</form>
    </div>
</div>  

<?php // SCRIPTS ?>
<script src="<?php echo GG_URL; ?>/js/functions.js" type="text/javascript"></script>
<script src="<?php echo GG_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>
<script src="<?php echo GG_URL; ?>/js/lc-switch/lc_switch.min.js" type="text/javascript"></script>
<script src="<?php echo GG_URL; ?>/js/colpick/js/colpick.min.js" type="text/javascript"></script>


<script type="text/javascript">
jQuery(document).ready(function($) {
	
	// watermark - media image  manager 
	var file_frame = false;
	
	jQuery(document).delegate('#gg_wm_media_manag', 'click', function(e) {
		
		// If the media frame already exists, reopen it.
		if(file_frame){
		  file_frame.open();
		  return;
		}
	
		// Create the media frame
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: "<?php _e('Global Gallery - watermark selection', 'gg_ml') ?>",
		  button: {
			text: "<?php _e('Select') ?>",
		  },
		  library : {type : 'image'},
		  multiple: false
		});
	
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			var img_data = file_frame.state().get('selection').first().toJSON();
			
			jQuery('#gg_watermark_img').val(img_data.url);
			jQuery('.lcwp_upload_imgwrap').html('<img src="'+ img_data.url +'" /><span class="lcwp_del_ul_img" title="remove image"></span>');
		});

		file_frame.open();
	});
	
	
	// watermark removal
	jQuery(document).delegate('.lcwp_del_ul_img', 'click', function() {
		jQuery('#gg_watermark_img').val('');
		jQuery('.lcwp_upload_imgwrap').html('<div class="no_image"></div>');
	});
	


	// create watermark cache
	jQuery('body').delegate('#gg_create_cache', 'click', function() {
		var wm_img = '<?php echo $fdata['gg_watermark_img'] ?>';
		
		if(wm_img == '') { jQuery('.gg_wm_create_status').html("<?php _e("Watermark image hasn't been set", 'gg_ml' ) ?>"); }
		else {
			jQuery('.gg_wm_create_status').html('<div style="width: 30px; height: 30px;" class="lcwp_loading"></div>\
			<small style="padding-left: 15px;">(<?php _e('might take very long if you have many images to manage', 'gg_ml' ) ?>)</small>');
			
			var data = {action: 'gg_create_wm_cache'};
			jQuery.post(ajaxurl, data, function(response) {
				var resp = jQuery.trim(response);
				
				if(resp == 'success') { jQuery('.gg_wm_create_status').html('<?php _e('Cache created succesfully', 'gg_ml' ) ?>!'); }
				else {
					if(resp.indexOf("Maximum execution") != -1) {
						jQuery('.gg_wm_create_status').html('<?php _e('Process took too much time for your server. Try creating the cache again', 'gg_ml' ) ?>'); 
					}
					else if(resp.indexOf("bytes exhausted") != -1) {
						jQuery('.gg_wm_create_status').html('<?php _e('The process requires too much memory for your server. Try applying it to smaller images', 'gg_ml' ) ?>'); 	
					}
					else {
						jQuery('.gg_wm_create_status').html('<?php _e('Error during the cache creation', 'gg_ml' ) ?>'); 
					}
				}
			});	
		}
	});
	
	// clean watermark cache
	jQuery('body').delegate('#gg_clean_cache', 'click', function() {
		if( confirm('Every image cached will be deleted. Continue?') ) {
			jQuery('.gg_wm_clean_status').html('<div style="width: 30px; height: 30px;" class="lcwp_loading"></div>');
			
			var data = {action: 'gg_clean_wm_cache'};
			jQuery.post(ajaxurl, data, function(response) {
				var resp = jQuery.trim(response);
				
				if(resp == 'success') { jQuery('.gg_wm_clean_status').html('<?php _e('Cache cleaned succesfully', 'gg_ml' ) ?>!'); }
				else { jQuery('.gg_wm_clean_status').html('<?php _e('Error during the cache deletion', 'gg_ml' ) ?>'); }
			});	
		}
	});
	
	// set the watermark position
	function gg_watermark_position(position) {
		jQuery('.gg_sel_thumb_center td').removeClass('thumb_center');
		jQuery('.gg_sel_thumb_center #gg_'+position).addClass('thumb_center');
		
		jQuery('#gg_watermark_pos').val(position);	
	}
	gg_watermark_position( jQuery('#gg_watermark_pos').val() );
	
	jQuery('body').delegate('.gg_sel_thumb_center td', 'click', function() {
		var new_position = jQuery(this).attr('id').substr(3);
		gg_watermark_position(new_position);
	}); 
	
	
	// set a predefined style 
	jQuery('body').delegate('#gg_set_style', 'click', function() {
		var sel_style = jQuery('#gg_pred_styles').val();
		
		if(sel_style != '' && confirm('<?php _e('It will overwrite your current settings, continue?', 'gg_ml' ) ?>')) {
			var data = {
				action: 'gg_set_predefined_style',
				style: sel_style
			};
			
			jQuery(this).parent().html('<div style="width: 30px; height: 30px;" class="lcwp_loading"></div>');
			
			jQuery.post(ajaxurl, data, function(response) {
				location.reload(true)
			});	
		}
	});
	
	// predefined style  preview toggle
	jQuery('body').delegate('#gg_pred_styles', "change", function() {
		var sel = jQuery('#gg_pred_styles').val();
		
		jQuery('.gg_styles_preview').hide();
		jQuery('.gg_styles_preview').each(function() {
			if( jQuery(this).attr('alt') == sel ) {jQuery(this).fadeIn();}
		});
	});
	
	
	// select a pattern
	jQuery('body').delegate('.gg_setting_pattern', 'click', function() {
		var pattern = jQuery(this).attr('id').substr(4);
		
		jQuery('.gg_setting_pattern').removeClass('gg_pattern_sel');
		jQuery(this).addClass('gg_pattern_sel'); 
		
		jQuery('#gg_lb_ol_pattern').val(pattern);
	});
	
	
	// lightbox options toggle
	gg_lb_toggle('<?php echo $fdata['gg_lightbox']; ?>');
	jQuery('body').delegate('#gg_lightbox', 'change', function() {
		var lb_sel = jQuery(this).val();
		gg_lb_toggle(lb_sel);	
	});
	function gg_lb_toggle(lb_sel) {
		jQuery('.gg_lb_options tr').hide();
		jQuery('.gg_lb_options .lb_opt_'+lb_sel).fadeIn();	
		
		if(!lb_sel || lb_sel == '' || lb_sel == 'none') {
			jQuery('#gg_lb_opt_title').hide();
			jQuery('#gg_lb_opt_title').next('table').hide();
		}
		else {
			jQuery('#gg_lb_opt_title').slideDown();
			jQuery('#gg_lb_opt_title').next('table').show();
		}
	}
	
	
	/*** remove opt ***/
	jQuery('body').delegate('.lcwp_del_row', "click", function() {
		if(confirm('<?php _e('Delete the option', 'gg_ml') ?>?')) {
			jQuery(this).parent().parent().slideUp(function() {
				jQuery(this).remove();
			});	
		}
	});
	
	/*** sort opt ***/
	jQuery('#opt_builder table').each(function() {
        jQuery(this).children('tbody').sortable({ handle: '.lcwp_move_row' });
		jQuery(this).find('.lcwp_move_row').disableSelection();
    });

	
	// tabs
	jQuery("#tabs").tabs();
	
	
	//// keep tab shown even on settings save - must be used after tabs initialization
	var lcwp_settings_deeplink = function() {
		var $form = jQuery("#tabs > form").first();
		var form_act = $form.attr('action');
		
		// initial setup
		var init_tab = jQuery('li.ui-tabs-active.ui-state-active a').attr('href');
		$form.attr('action', form_act + init_tab);
		jQuery('html, body').animate({'scrollTop': 0}, 0); // scroll to top
		
		// on click
		jQuery(document.body).delegate('.ui-tabs-nav a', 'click', function() {
			$form.attr('action', form_act + jQuery(this).attr('href'));
		});
	}
	lcwp_settings_deeplink();
});
</script>
