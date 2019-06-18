<?php

add_action('cornerstone_register_elements', 'gg_cornerstone_register_elements');
add_filter('cornerstone_icon_map', 'gg_cornerstone_icon_map', 900);


function gg_cornerstone_register_elements() {
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
	
	$GLOBALS['gg_galls_arr'] = array(); 
	foreach($galleries as $gallery) {
    	$GLOBALS['gg_galls_arr'][] = array(
			'value' => $gallery->ID,
			'label' => $gallery->post_title
		);
    }
	
	
	// collections array array (use full list for now)
	$collections = get_terms('gg_collections', 'hide_empty=0');
	
	$GLOBALS['gg_colls_arr'] = array(); 
	foreach($collections as $collection) {
    	$GLOBALS['gg_colls_arr'][] = array(
			'value' => $collection->term_id,
			'label' => $collection->name
		);
    }
	
	
	///// OVERLAY MANAGER ADD-ON ///////////
	if(defined('GGOM_DIR')) {
		register_taxonomy_ggom(); // be sure tax are registered
		$overlays = get_terms('ggom_overlays', 'hide_empty=0');
		
		$ol_arr = array(
			0 => array(
				'value' => '',
				'label' => __('default one', 'mg_ml')
			)
		);
		foreach($overlays as $ol) {
			$ol_arr[] = array(
				'value' => $ol->term_id,
				'label' => $ol->name
			);
		}
		
		$GLOBALS['ggom_cs_field'] = array(
			'type'    => 'select',
			'ui' => array(
				'title'   => __('Custom Overlay', 'gg_ml'),
				'tooltip' => '',
			),
			'options' => array(
				'choices' => $ol_arr
			),
		);
	}


	///////////////////////////////////////////////////////////
	
	
	cornerstone_register_element('lcweb_gg_gallery', 		'lcweb_gg_gallery', 	GG_DIR .'/builders_integration/cs_elements/gallery');
	cornerstone_register_element('lcweb_gg_collection', 	'lcweb_gg_collection', 	GG_DIR .'/builders_integration/cs_elements/collection');
	cornerstone_register_element('lcweb_gg_slider', 		'lcweb_gg_slider', 		GG_DIR .'/builders_integration/cs_elements/slider');
	cornerstone_register_element('lcweb_gg_carousel', 		'lcweb_gg_carousel', 	GG_DIR .'/builders_integration/cs_elements/carousel');
}


function gg_cornerstone_icon_map( $icon_map ) {
	$icon_map['lcweb_gg_gallery'] 		= GG_URL .'/img/cs_icon.svg';
	$icon_map['lcweb_gg_collection'] 	= GG_URL .'/img/cs_icon.svg';
	$icon_map['lcweb_gg_slider'] 		= GG_URL .'/img/cs_icon.svg';
	$icon_map['lcweb_gg_carousel'] 		= GG_URL .'/img/cs_icon.svg';
	
	return $icon_map;
}
