<?php

/**
 * Element Controls
 */
 

/* FIELDS */
$fields =  array(
	'gid' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Gallery', 'gg_ml'),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => $GLOBALS['gg_galls_arr']
		),
	),

	'random' => array(
		'type'    => 'toggle',
		'ui' => array(
			'title'   => __('Random display?', 'gg_ml'),
			'tooltip' => __('Display images randomly', 'gg_ml'),
		),
	),
	
	'watermark' => array(
		'type'    => 'toggle',
		'ui' => array(
			'title'   => __('Use Watermark?', 'gg_ml'),
			'tooltip' => __('Apply watermark to images (if available)', 'gg_ml'),
		),
	),

	'pagination' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Pagination System', 'gg_ml'),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => array(
				array('value' => '', 			'label' => __('Auto - follow global settings', 'gg_ml')),
				array('value' => 'standard', 	'label' => __('Standard', 'gg_ml')),
				array('value' => 'inf_scroll',	'label' => __('Infinite scroll', 'gg_ml')),
			)
		),
	),
);



///// OVERLAY MANAGER ADD-ON ///////////
if(isset($GLOBALS['ggom_cs_field'])) {
	$fields['overlay'] = $GLOBALS['ggom_cs_field'];
}
////////////////////////////////////////


return $fields;
