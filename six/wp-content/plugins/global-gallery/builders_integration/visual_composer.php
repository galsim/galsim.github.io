<?php
//  visual composer integration


function gg_on_visual_composer() {
    include_once(GG_DIR .'/admin_menu.php'); // be sure tax are registered
	register_cpt_gg_gallery();
	register_taxonomy_gg_collections();
	

	// galleries array
	$args = array(
		'post_type' => 'gg_galleries',
		'numberposts' => -1,
		'post_status' => 'publish'
	);
	$galleries = get_posts($args);
	
	$galls_arr = array(); 
	foreach($galleries as $gallery) {
    	$galls_arr[ $gallery->post_title ] = $gallery->ID;
    }
	
	
	// collections array array (use full list for now)
	$collections = get_terms('gg_collections', 'hide_empty=0');
	
	$colls_arr = array(); 
	foreach($collections as $collection) {
    	$colls_arr[ $collection->name ] = $collection->term_id;
    }
	
	
	///// OVERLAY MANAGER ADD-ON ///////////
	if(defined('GGOM_DIR')) {
		register_taxonomy_ggom(); // be sure tax are registered
		$overlays = get_terms('ggom_overlays', 'hide_empty=0');
		
		$ol_arr = array(
			__('default one', 'gg_ml') => ''
		);
		foreach($overlays as $ol) {
			$ol_arr[ $ol->name ] = $ol->term_id;	
		}
		
		$ggom_param = array(
			'type' 			=> 'dropdown',
			'heading' 		=> __('Custom Overlay', 'gg_ml'),
			'param_name' 	=> 'overlay',
			'admin_label' 	=> true,
			'value' 		=> $ol_arr,
		);
	}
	///////////////////////////////////////
	
	
	
	/**********************************************************************************************************/
	
	
	#########################################
	######## GALLERY SHORTCODE ##############
	#########################################
	
	// parameters
	$params = array(
		array(
			'type' 			=> 'dropdown',
			'heading' 		=> __('Gallery', 'gg_ml'),
			'param_name' 	=> 'gid',
			'admin_label' 	=> true,
			'value' 		=> $galls_arr,
			'description'	=> __('Select a gallery', 'gg_ml'),
		),
		array(
			'type' 			=> 'checkbox',
			'param_name' 	=> 'random',
			'value' 		=> array(
				'<strong>'. __('Random display?', 'gg_ml') .'</strong>' => 1
			),
			'description'	=> __('Display images randomly', 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'checkbox',
			'param_name' 	=> 'watermark',
			'value' 		=> array(
				'<strong>'. __('Use Watermark?', 'gg_ml') .'</strong>' => 1
			),
			'description'	=> __('Apply watermark to images (if available)', 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'dropdown',
			'heading' 		=> __('Pagination System', 'gg_ml'),
			'param_name' 	=> 'pagination',
			'admin_label' 	=> true,
			'value' 		=> array(
				__('Auto - follow global settings', 'gg_ml') => '',
				__('Standard', 'gg_ml') => 'standard',
				__('Infinite scroll', 'gg_ml') => 'inf_scroll',
			),
		),
	);
	
	if(isset($ggom_param)) {
		$params[] = $ggom_param;	
	}  
	
	// compile
	vc_map(
        array(
            'name' 			=> 'GG - '. __('Gallery', 'gg_ml'),
			'description'	=> __("Displays a gallery", 'gg_ml'),
            'base' 			=> 'g-gallery',
            'category' 		=> "Global Gallery",
			'icon'			=> GG_URL .'/img/vc_icon.png',
            'params' 		=> $params,
        )
    );
	
	
	
	
	
	#########################################
	####### COLLECTION SHORTCODE ############
	#########################################
	
	// parameters
	$params = array(
		array(
			'type' 			=> 'dropdown',
			'heading' 		=> __('Collection', 'gg_ml'),
			'param_name' 	=> 'cid',
			'admin_label' 	=> true,
			'value' 		=> $colls_arr,
			'description'	=> __('Select a collection', 'gg_ml'),
		),
		array(
			'type' 			=> 'checkbox',
			'param_name' 	=> 'filter',
			'value' 		=> array(
				'<strong>'. __('Allow Filters?', 'gg_ml') .'</strong>' => 1
			),
			'description'	=> __('Allow galleries filtering by category', 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'checkbox',
			'param_name' 	=> 'random',
			'value' 		=> array(
				'<strong>'. __('Random display?', 'gg_ml') .'</strong>' => 1
			),
			'description'	=> __('Display galleries randomly', 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
	);
	
	if(isset($ggom_param)) {
		$params[] = $ggom_param;	
	}
		  
	// compile
	vc_map(
        array(
            'name' 			=> 'GG - '. __('Collection', 'gg_ml'),
			'description'	=> __("Displays a galleries collection", 'gg_ml'),
            'base' 			=> 'g-collection',
            'category' 		=> "Global Gallery",
			'icon'			=> GG_URL .'/img/vc_icon.png',
            'params' 		=> $params,
        )
    );
	
	
	
	
	
	
	#########################################
	######## SLIDER SHORTCODE ###############
	#########################################
	
	// parameters
	$params = array(
		array(
			'type' 			=> 'dropdown',
			'heading' 		=> __('Gallery', 'gg_ml'),
			'param_name' 	=> 'gid',
			'admin_label' 	=> true,
			'value' 		=> $galls_arr,
			'description'	=> __('Select a gallery', 'gg_ml'),
		),
		array(
			'type' 			=> 'gg_num_unit',
			'heading' 		=> __('Width', 'gg_ml'),
			'param_name' 	=> 'width',
			'value' 		=> '100%',
			'description'	=> __("Define slider's width", 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-5 vc_column',
		),
		array(
			'type' 			=> 'gg_num_unit',
			'heading' 		=> __('Height', 'gg_ml'),
			'param_name' 	=> 'height',
			'value' 		=> '55%',
			'description'	=> __("Define slider's height (percentage is related to width)", 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-7 vc_column',
		),
		array(
			'type' 			=> 'checkbox',
			'param_name' 	=> 'random',
			'value' 		=> array(
				'<strong>'. __('Random display?', 'gg_ml') .'</strong>' => 1
			),
			'description'	=> __('Display images randomly', 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'checkbox',
			'param_name' 	=> 'watermark',
			'value' 		=> array(
				'<strong>'. __('Use Watermark?', 'gg_ml') .'</strong>' => 1
			),
			'description'	=> __('Apply watermark to images (if available)', 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'dropdown',
			'heading' 		=> __('Autoplay', 'gg_ml'),
			'param_name' 	=> 'autoplay',
			'admin_label' 	=> true,
			'value' 		=> array(
				__('(as default)', 'gg_ml') => 'auto',
				__('Yes', 'gg_ml') => 1,
				__('No', 'gg_ml') => 0,
			),
		),
	);
  
	// compile
	vc_map(
        array(
            'name' 			=> 'GG - '. __('Slider', 'gg_ml'),
			'description'	=> __("Displays an image slider", 'gg_ml'),
            'base' 			=> 'g-slider',
            'category' 		=> "Global Gallery",
			'class'			=> 'gg_slider_sc',
			'icon'			=> GG_URL .'/img/vc_icon.png',
            'params' 		=> $params,
        )
    );
	
	
	
	
	
	
	#########################################
	####### CAROUSEL SHORTCODE ##############
	#########################################
	
	// images per time array
	$img_x_time_arr = array();
	for($a=1; $a<=10; $a++) {
		$img_x_time_arr[$a] = $a;	 
	}
	
	// image rows
	$img_rows = array();
	for($a=1; $a<=4; $a++) {
		$img_rows[$a] = $a;	 
	}
	
	// parameters
	$params = array(
		array(
			'type' 			=> 'dropdown',
			'heading' 		=> __('Gallery', 'gg_ml'),
			'param_name' 	=> 'gid',
			'admin_label' 	=> true,
			'value' 		=> $galls_arr,
			'description'	=> __('Select a gallery', 'gg_ml'),
		),
		array(
			'type' 			=> 'textfield',
			'heading' 		=> __('Images Height', 'mg_ml'),
			'param_name' 	=> 'height',
			'admin_label' 	=> true,
			'value' 		=> '200',
			'description'	=> __("Carousel images height in pixels", 'mg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'dropdown',
			'heading' 		=> __('Images per time', 'gg_ml'),
			'param_name' 	=> 'per_time',
			'admin_label' 	=> true,
			'value' 		=> $img_x_time_arr,
			'description'	=> __("Choose how many images to show per time", 'mg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'dropdown',
			'heading' 		=> __('Rows', 'gg_ml'),
			'param_name' 	=> 'rows',
			'admin_label' 	=> true,
			'value' 		=> $img_x_time_arr,
			'description'	=> __("Choose how many image rows to use", 'mg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'checkbox',
			'param_name' 	=> 'multiscroll',
			'value' 		=> array(
				'<strong>'. __('Multiple Scroll?', 'gg_ml') .'</strong>' => 1
			),
			'description'	=> __('Slides multiple images per time', 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'checkbox',
			'param_name' 	=> 'center',
			'value' 		=> array(
				'<strong>'. __('Center mode?', 'gg_ml') .'</strong>' => 1
			),
			'description'	=> __('Enables center display mode', 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'checkbox',
			'param_name' 	=> 'random',
			'value' 		=> array(
				'<strong>'. __('Random display?', 'gg_ml') .'</strong>' => 1
			),
			'description'	=> __('Display images randomly', 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'checkbox',
			'param_name' 	=> 'watermark',
			'value' 		=> array(
				'<strong>'. __('Use Watermark?', 'gg_ml') .'</strong>' => 1
			),
			'description'	=> __('Apply watermark to images (if available)', 'gg_ml'),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' 			=> 'dropdown',
			'heading' 		=> __('Autoplay', 'gg_ml'),
			'param_name' 	=> 'autoplay',
			'admin_label' 	=> true,
			'value' 		=> array(
				__('(as default)', 'gg_ml') => 'auto',
				__('Yes', 'gg_ml') => 1,
				__('No', 'gg_ml') => 0,
			),
		),
	);
	
	if(isset($ggom_param)) {
		$params[] = $ggom_param;	
	}
  
	// compile
	vc_map(
        array(
            'name' 			=> 'GG - '. __('Carousel', 'gg_ml'),
			'description'	=> __("Displays an image carousel", 'gg_ml'),
            'base' 			=> 'g-carousel',
            'category' 		=> "Global Gallery",
			'class'			=> 'gg_slider_sc',
			'icon'			=> GG_URL .'/img/vc_icon.png',
            'params' 		=> $params,
        )
    );
	
	

	
	/**********************************************************************************************************/
	
	
	// add new field type
	function gg_vc_num_unit_field($settings, $value) {
		$px_sel = (!empty($value) && strpos($value, 'px') !== false) ? 'selected="selected"' : '';
	  	
		$min = (isset($settings['min_val'])) ? 'min="'. (int)$settings['min_val'] .'"' : '';
		$max = (isset($settings['max_val'])) ? 'max="'. (int)$settings['max_val'] .'"' : '';
		
		
	  	return 
		'<div class="gg_num_unit_wrap">
			<input name="'. esc_attr( $settings['param_name'] ) .'" type="hidden" class="wpb_vc_param_value '. esc_attr( $settings['param_name'] ) .'" value="'. esc_attr($value) .'" /> 
			
			<input name="'. esc_attr( $settings['param_name'] ) .'_val" class="wpb-textinput '. esc_attr( $settings['param_name'] ) .'" type="number" value="' . (int)str_replace(array('px', '%'), '', $value) . '" style="width: 100px;" '.$min.' '.$max.' />
				 
			<select name="'. esc_attr( $settings['param_name'] ) .'_unit" style="height: 28px; padding-bottom: 2px; padding-top: 2px; position: relative; top: -2px; width: 55px;">
				<option value="%">%</option>
				<option value="px" '. $px_sel .'>px</option>
			</select>
			
			
		</div>';
	}
	vc_add_shortcode_param('gg_num_unit', 'gg_vc_num_unit_field', GG_URL.'/js/vc_custom_field.js');
}
add_action('vc_before_init', 'gg_on_visual_composer');


