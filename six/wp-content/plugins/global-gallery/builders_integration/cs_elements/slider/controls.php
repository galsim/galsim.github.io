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

	'width' => array(
		'type'    => 'number',
		'ui' => array(
			'title'   => __('Width', 'gg_ml'),
			'tooltip' => __("Define slider's width", 'gg_ml'),
		),
	),
	'width_unit' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Width Unit', 'gg_ml'),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => array(
				array('value' => '%', 	'label' => '%'),
				array('value' => 'px', 	'label' => 'px'),
			)
		),
	),

	'height' => array(
		'type'    => 'number',
		'ui' => array(
			'title'   => __('Height', 'gg_ml'),
			'tooltip' => __("Define slider's height (percentage is related to width)", 'gg_ml'),
		),
	),
	'height_unit' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Height Unit', 'gg_ml'),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => array(
				array('value' => '%', 	'label' => '%'),
				array('value' => 'px', 	'label' => 'px'),
			)
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

	'autoplay' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __('Autoplay', 'gg_ml'),
			'tooltip' => '',
		),
		'options' => array(
			'choices' => array(
				array('value' => 'auto',	'label' => __('(as default)', 'gg_ml')),
				array('value' => 1, 		'label' => __('Yes', 'gg_ml')),
				array('value' => 0,			'label' => __('No', 'gg_ml')),
			)
		),
	),
);



return $fields;
