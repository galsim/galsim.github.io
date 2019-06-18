<?php

/**
 * Shortcode handler
 */

if(!isset($id)) 		{$id = '';}
if(!isset($class)) 		{$class = '';}
if(!isset($style)) 		{$style = '';}
if(!isset($overlay)) 	{$overlay = 'default';}
 
 
cs_atts( array('id' => $id, 'class' => $class, 'style' => $style ) );

$atts = array(
	'gid' => $gid,
	'height' => $height,
	'per_time' => $per_time,
	'rows'	=> $rows,
	'multiscroll' => $multiscroll,
	'center' => $center,
	'random' => $random,
	'watermark' => $watermark,
	'autoplay' => $autoplay,
	
	'overlay' => $overlay
);

$params = '';
foreach($atts as $key => $val) {
	$params .= ' '. $key .'="'. esc_attr($val) .'"';
}

echo do_shortcode('[g-carousel '. $params .']');
