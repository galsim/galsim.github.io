<?php

/**
 * Element Controls
 */
 

/* FIELDS */
$fields =  array(
	'cid' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Collection', 'gg_ml'),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => $GLOBALS['gg_colls_arr']
		),
	),
	
	'filter' => array(
		'type'    => 'toggle',
		'ui' => array(
			'title'   => __('Allow Filters?', 'gg_ml'),
			'tooltip' => __('Allow galleries filtering by category', 'gg_ml'),
		),
	),

	'random' => array(
		'type'    => 'toggle',
		'ui' => array(
			'title'   => __('Random display?', 'gg_ml'),
			'tooltip' => __('Display images randomly', 'gg_ml'),
		),
	),

);



///// OVERLAY MANAGER ADD-ON ///////////
if(isset($GLOBALS['ggom_cs_field'])) {
	$fields['overlay'] = $GLOBALS['ggom_cs_field'];
}
////////////////////////////////////////


return $fields;
