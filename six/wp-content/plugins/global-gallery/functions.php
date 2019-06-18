<?php

// get the current URL
function gg_curr_url() {
	$pageURL = 'http';
	
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://" . $_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];

	return $pageURL;
}
	

// get file extension from a filename
function gg_stringToExt($string) {
	// remove url parameters
	if(strpos($string, '?') !== false) {
		$arr = explode('?', $string);
		$string = $arr[0];	
	}
	
	$pos = strrpos($string, '.');
	$ext = strtolower(substr($string,$pos));
	return $ext;	
}


// get filename without extension
function gg_stringToFilename($string, $raw_name = false) {
	$pos = strrpos($string, '.');
	$name = substr($string,0 ,$pos);
	if(!$raw_name) {$name = ucwords(str_replace('_', ' ', $name));}
	return $name;	
}


// string to url format // NEW FROM v1.11 for non-latin characters 
function gg_stringToUrl($string){
	
	// if already exist at least an option, use the default encoding
	if(!get_option('mg_non_latin_char')) {
		$trans = array("à" => "a", "è" => "e", "é" => "e", "ò" => "o", "ì" => "i", "ù" => "u");
		$string = trim(strtr($string, $trans));
		$string = preg_replace('/[^a-zA-Z0-9-.]/', '_', $string);
		$string = preg_replace('/-+/', "_", $string);	
	}
	
	else {$string = trim(urlencode($string));}
	
	return $string;
}


// normalize a url string
function gg_urlToName($string) {
	$string = ucwords(str_replace('_', ' ', $string));
	return $string;	
}


// remove a folder and its contents
function gg_remove_folder($path) {
	if($objs = @glob($path."/*")){
		foreach($objs as $obj) {
			@is_dir($obj)? gg_remove_folder($obj) : @unlink($obj);
		}
	 }
	@rmdir($path);
	return true;
}


// checkbox checked attribute
function gg_checkbox_check($val) {
	return ($val == 1) ? 'checked="checked"' : '';	
}


// sanitize input field values
function gg_sanitize_input($val) {	
	global $wp_version;
	
	// not sanitize quotes  in WP 4.3 and newer
	if ($wp_version >= 4.3) {
		return trim(esc_attr($val));	
	}
	else {
		return trim(
			str_replace(array('\'', '"', '<', '>', '&'), array('&apos;', '&quot;', '&lt;', '&gt;', '&amp;'), (string)$val)
		);	
	}
}


// know if server supports cURL followlocation command
function gg_followlocation() {
	return (!ini_get('open_basedir') && !ini_get('safe_mode')) ? true : false; 	
}


// convert HEX to RGB
function gg_hex2rgb($hex) {
   	// if is RGB or transparent - return it
   	$pattern = '/^#[a-f0-9]{6}$/i';
	if(empty($hex) || $hex == 'transparent' || !preg_match($pattern, $hex)) {return $hex;}
  
	$hex = str_replace("#", "", $hex);
   	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
  
	return 'rgb('. implode(",", $rgb) .')'; // returns the rgb values separated by commas
}


// convert RGB to HEX
function gg_rgb2hex($rgb) {
   	// if is hex or transparent - return it
   	$pattern = '/^#[a-f0-9]{6}$/i';
	if(empty($rgb) || $rgb == 'transparent' || preg_match($pattern, $rgb)) {return $rgb;}

  	$rgb = explode(',', str_replace(array('rgb(', ')'), '', $rgb));
  	
	$hex = "#";
	$hex .= str_pad(dechex( trim($rgb[0]) ), 2, "0", STR_PAD_LEFT);
	$hex .= str_pad(dechex( trim($rgb[1]) ), 2, "0", STR_PAD_LEFT);
	$hex .= str_pad(dechex( trim($rgb[2]) ), 2, "0", STR_PAD_LEFT);

	return $hex; 
}


// get the upload directory (for WP MU)
function gg_wpmu_upload_dir() {
	$dirs = wp_upload_dir();
	$basedir = $dirs['basedir'] . '/YEAR/MONTH';
	
	return $basedir;	
}


// image ID to path
function gg_img_id_to_path($img_src) {
	if(is_numeric($img_src)) {
		$wp_img_data = wp_get_attachment_metadata((int)$img_src);
		if($wp_img_data) {
			$upload_dirs = wp_upload_dir();
			$img_src = $upload_dirs['basedir'] . '/' . $wp_img_data['file'];
		}
	}
	
	return $img_src;
}


// thumbnail source switch between timthumb and ewpt
function gg_thumb_src($img_id, $width = false, $height = false, $quality = 80, $alignment = 'c', $resize = 1, $canvas_col = 'FFFFFF', $fx = array()) {
	if(!$img_id) {return false;}
	
	if(get_option('gg_use_timthumb')) {
		$thumb_url = GG_TT_URL.'?src='.gg_img_id_to_path($img_id).'&w='.$width.'&h='.$height.'&a='.$alignment.'&q='.$quality.'&zc='.$resize.'&cc='.$canvas_col;
	} else {
		$thumb_url = easy_wp_thumb($img_id, $width, $height, $quality, $alignment, $resize, $canvas_col , $fx);
	}	
	
	return $thumb_url;
}


// link field generator
function gg_link_field($src, $val = '') {
	if($src == 'page') {
		$code = '<select name="gg_item_link[]" class="gg_link_field">';
		
		foreach(get_pages() as $pag) {
			($val == $pag->ID) ? $selected = 'selected="selected"' : $selected = '';
			$code .= '<option value="'.$pag->ID.'" '.$selected.'>'.$pag->post_title.'</option>';
		}
		
		return $code . '</select>';
	}
	else if($src == 'custom') {
		return '<input type="text" name="gg_item_link[]" value="'.gg_sanitize_input($val).'" class="gg_link_field" />';
	}
	else {
		return '<input type="hidden" name="gg_item_link[]" value="" />';
	}
}


// giving a gallery ID returns the associated categories
function gg_gallery_cats($gid, $return = 'list') {
	$terms = wp_get_post_terms($gid, 'gg_gall_categories');	
	
	if(count($terms) == 0) {
		return ($return == 'list') ? '' : array();	
	}
	
	$to_return = array();
	foreach($terms as $term) {
		// WPML fix - get original ID
		if (function_exists('icl_object_id')) {
			global $sitepress;
			$term_id = icl_object_id($term->term_id, 'gg_gall_categories', true);
			$term = get_term($term_id, 'gg_gall_categories');
		}
		
		if($return == 'list') {$to_return[] = $term->name;}
		elseif($return == 'class_list') {$to_return[] = 'ggc_'.$term->term_id;}
		else {$to_return[] = $term->term_id;}	
	}
	
	if($return == 'list') {return implode(', ', $to_return);}
	elseif($return == 'class_list') {return implode(' ', $to_return);}
	else {return $to_return;}	
}


// get the gallery first image
function gg_get_gall_first_img($gid, $return = 'img') {
	$autopop = get_post_meta($gid, 'gg_autopop', true);
	$images = gg_gall_data_get($gid, $autopop);

	if(isset($images[0])) { 
		$type = get_post_meta($gid, 'gg_type', true);
		$img_src = gg_img_src_on_type($images[0]['img_src'], $type);

		if($return == 'img') {return $img_src;}
		else {
			$align = (isset($images[0]['thumb'])) ? $images[0]['thumb'] : 'c';
			
			return array(
				'src' => $img_src,
				'align' => $align
			);	
		}
	}
	else {return false;}
}


// giving a category, return the associated galleries
function gg_cat_galleries($cat) {
	if(!$cat) {return false;}
	
	$args = array(
		'posts_per_page'  => -1,
		'post_type'       => 'gg_galleries',
		'post_status'     => 'publish'
	);
	
	if($cat != 'all') {
		$term_data = get_term_by( 'id', $cat, 'gg_gall_categories');	
		$args['gg_gall_categories'] = $term_data->slug;		
	}	
	$raw_galleries = get_posts($args);
	
	$galleries = array();
	foreach($raw_galleries as $gallery) {
		$gid = $gallery->ID;
		$img = gg_get_gall_first_img($gid);
		
		if($img) { 
			$galleries[] = array(  
				'id' =>	$gid,
				'title' => $gallery->post_title,
				'img' => $img,
				'cats' => gg_gallery_cats($gid)
			);
		}
	}
	
	
	if(count($galleries) > 0) {  
		return $galleries;
	} else { 
		return false; 
	}
}


// get all the custom post types
function gg_get_cpt() {
	$args = array(
		'public'   => true,
		'publicly_queryable' => true,
		'_builtin' => false
	);
	$cpt_obj = get_post_types($args, 'objects');
	
	if(count($cpt_obj) == 0) { return false;}
	else {
		$cpt = array();
		foreach($cpt_obj as $id => $obj) {
			$cpt[$id] = $obj->labels->name;	
		}
		
		return $cpt;
	}	
}


// get affected post types for WP gall management
function gg_affected_wp_gall_ct() {
	$basic = array('post','page');	
	$cpt = get_option('gg_extend_wp_gall'); 

	if(is_array($cpt)) {
		$pt = array_merge((array)$basic, (array)$cpt);	
	}
	else {$pt = $basic;}

	return $pt;
}


// return the gallery categories by the chosen order
function gg_order_coll_cats($terms) {
	$ordered = array();
	
	foreach($terms as $term_id) {
		$ord = (int)get_option("gg_cat_".$term_id."_order");
		
		// check the final order
		while( isset($ordered[$ord]) ) {
			$ord++;	
		}
		
		$ordered[$ord] = $term_id;
	}
	
	ksort($ordered, SORT_NUMERIC);
	return $ordered;	
}


// return the collections filter code
function gg_coll_filter_code($terms, $return = 'html') {
	if(!$terms) { return false; }
	else {
		$terms = gg_order_coll_cats($terms);
		$terms_data = array();
		
		$a = 0;
		foreach($terms as $term) {
			$term_data = get_term_by('id', $term, 'gg_gall_categories');
			
			// icon code
				$icon = get_option("mg_cat_".$term['id']."_icon");
				if(!empty($icon)) {
					$icon_code = '<i class="mg_cat_icon fa '.$icon.'"></i>';	
				} 
				else {$icon_code = '';}
			
			
			
			if(is_object($term_data)) {
				$icon = get_option("gg_cat_".$term."_icon");
				$icon = (!empty($icon)) ? '<i class="mg_cat_icon fa '.$icon.'"></i>' : ''; 
				
				$terms_data[$a] = array('id' => $term, 'name' => $term_data->name, 'slug' => $term_data->slug, 'icon' => $icon); 		
				$a++;
			}
		}
		
		if($return == 'html') {
			$coll_terms_list = '<a class="gg_cats_selected ggf ggf_all" rel="*" href="javascript:void(0)">'.__('All', 'gg_ml').'</a>';
			$separator = (get_option('gg_use_old_filters')) ? '<span>/</span>' : '';
			
			foreach($terms_data as $term) {
				$coll_terms_list .= $separator.'<a class="ggf_id_'.$term['id'].' ggf" rel="'.$term['id'].'" href="javascript:void(0)">'.$term['icon'] . $term['name'].'</a>';	
			}
			
			return $coll_terms_list;
		}
		
		elseif($return == 'dropdown') {
			$code = '<select class="gg_mobile_filter_dd" autocomplete="off">';
			$code .= '<option value="*">'.__('All', 'gg_ml').'</option>';	

			foreach($terms_data as $term) {
				$code .= '<option value="'.$term['id'].'">'.$term['name'].'</option>';	
			}
				
			return $code . '</select>';	
		}
	}
}


// clean emoticons from instagram texts
function gg_clean_emoticons($text) {
    $clean_text = "";

    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);

    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);

    return $clean_text;
}


// get RGB color from hex
function gg_hex_to_rgb($hex, $alpha = false) {
	if($alpha) {$alpha = (int)$alpha / 100;}
	
	$hex = str_replace("#", "", $hex);
	if(strlen($hex) == 3) {
	  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
	  $r = hexdec(substr($hex,0,2));
	  $g = hexdec(substr($hex,2,2));
	  $b = hexdec(substr($hex,4,2));
	}
	
	$rgb = implode(', ', array($r, $g, $b));
	if($alpha) {$rgb .= ', '.$alpha;}
	
	return ($alpha) ? 'rgba('.$rgb.')' : 'rgb('.$rgb.')'; 
}


// preloader code
function gg_preloader() {
	return '
	<div class="gg_loader">
	  <div class="ggl_1"></div>
	  <div class="ggl_2"></div>
	  <div class="ggl_3"></div>
	  <div class="ggl_4"></div>
	</div>';	
}


// pagination layouts
function gg_pag_layouts($type = false) {
	$types = array(
		'standard' 	 => __('Commands + full text', 'gg_ml'),
		'only_num'  => __('Commands + page numbers', 'gg_ml'),
		'only_arr_mb'=> __('Only arrows', 'gg_ml'),
		'only_arr'	 => __('Only arrows - monoblock', 'gg_ml')
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// slider cropping methods
function gg_galleria_crop_methods($type = false) {
	$types = array(
		'true' 		=> __('Fit, center and crop', 'gg_ml'),
		'false' 	=> __('Scale down', 'gg_ml'),
		'height'	=> __('Scale to fill the height', 'gg_ml'),
		'width'		=> __('Scale to fill the width', 'gg_ml'),
		'landscape'	=> __('Fit images with landscape proportions', 'gg_ml'),
		'portrait' 	=> __('Fit images with portrait proportions', 'gg_ml')
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// slider effects
function gg_galleria_fx($type = false) {
	$types = array(
		'fadeslide' => __('Fade and slide', 'gg_ml'),
		'fade' 		=> __('Fade', 'gg_ml'),
		'flash'		=> __('Flash', 'gg_ml'),
		'pulse'		=> __('Pulse', 'gg_ml'),
		'slide'		=> __('Slide', 'gg_ml'),
		''			=> __('None', 'gg_ml')
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// slider thumbs visibility options
function gg_galleria_thumb_opts($type = false) {
	$types = array(
		'always'	=> __('Always', 'gg_ml'),
		'yes' 		=> __('Yes with toggle button', 'gg_ml'),
		'no' 		=> __('No with toggle button', 'gg_ml'),
		'never' 	=> __('Never', 'gg_ml'),
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// use cURL to get external url contents
function gg_curl_get_contents($url, $followlocation = false) {
	$data = wp_remote_get($img_src, array('timeout' => 8, 'redirection' => 3));

	// nothing got - use cURL 
	if(is_wp_error($data) || 200 != wp_remote_retrieve_response_code($data) || empty($data['body'])) {
	
		@ini_set( 'memory_limit', '256M');
		$ch = curl_init();
	
		//curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		
		// followlocation only if needed
		if($followlocation) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		}
		else {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, gg_followlocation());
		}
		
		$data = curl_exec($ch);
		
		// store last URL if followlocation has been performed
		if(strpos($url, 'pinterest.com') !== false || $followlocation) {
			$GLOBALS['gg_curl_true_url'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); 
		}
		
		curl_close($ch);
		return $data;
	}
	else {
		return $data['body'];	
	}
}


// check remote file existence
function gg_rm_file_exists($url) {
	$ch = curl_init();

	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, gg_followlocation());
	curl_setopt($ch, CURLOPT_URL, $url);
	
	curl_exec($ch);
	$answer = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	return ($answer != '200') ? false : true;
}


/////////////////////////////////////////////////////////////////////////////


// get imagesize with cURL
function gg_getimagesize($url) {
	@ini_set( 'memory_limit', '256M');
	$ext = gg_stringToExt($url);
	
	// ranges for img type
	switch($ext) {
		case '.jpg': case '.jpeg': $range = 32768; break;
		case '.png': $range = 24; break;
		case '.gif': $range = 10; break;
		default: $range = 32768; break; // efault use JPG
	}

	// without curl or for local images
	if(!function_exists('curl_init') || !filter_var($url, FILTER_VALIDATE_URL) || strpos($url, site_url()) !== false) {
		$data = @file_get_contents($url, 0, NULL, 0, $range);
	} 
	else {
		$curlOpt = array(
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_HEADER	 => false, 
			CURLOPT_FOLLOWLOCATION => gg_followlocation(),
			CURLOPT_ENCODING => '', 
			CURLOPT_AUTOREFERER => true,
			CURLOPT_FAILONERROR	 => true,
			CURLOPT_CONNECTTIMEOUT => 2,
			CURLOPT_TIMEOUT => 2, 
			CURLOPT_MAXREDIRS => 3, 
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_RANGE => '0-'.$range
		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $curlOpt);
		$data = curl_exec($ch);
		curl_close($ch);
	}
	if(strlen($data) == 0) {return false;}


	if($ext == '.png') {
		// avoid errors on tiny png
		if(strlen($data) < 24 || 1==1) {
			list($w, $h) = @getimagesize($url);
			return ($w && $h) ? array($w, $h) : false; 
		}
		
		// The identity for a PNG is 8Bytes (64bits)long
		$ident = unpack('Nupper/Nlower', $data);
		
		// Make sure we get PNG
		if($ident['upper'] !== 0x89504E47 || $ident['lower'] !== 0x0D0A1A0A) {
			return false;
		}

		// Grab the first chunk tag, should be IHDR
		$data = substr($data, 8);
		$chunk = unpack('Nlength/Ntype', $data);
		
		// IHDR must come first, if not we return false
		if($chunk['type'] === 0x49484452) {
			$data = substr($data, 8);
			$info = unpack('NX/NY', $data);
			
			$width = $info['X'];
			$height = $info['Y'];
		}
		else {return false;}
	}
	
	elseif($ext == '.gif') {
		// avoid errors on tiny png
		if(strlen($data) < 10) {
			list($w, $h) = @getimagesize($url);
			return ($w && $h) ? array($w, $h) : false; 
		}
		
		$ident = unpack('nupper/nmiddle/nlower', $data);
		
		// Make sure we get GIF 87a or 89a
		if($ident['upper'] !== 0x4749 || $ident['middle'] !== 0x4638 || ($ident['lower'] !== 0x3761 && $ident['lower'] !== 0x3961)) {
			return false;
		}
		
		$data = substr($data, 6);
		$info = unpack('vX/vY', $data);
		
		$width = $info['X'];
		$height = $info['Y'];
	}
	
	else {
		$im = @imagecreatefromstring($data); // use @ - is normal it returns warnings
		if(!$im) {return false;}
		
		$width = imagesx($im);
		$height = imagesy($im);		
		imagedestroy($im);
	}
			
	return ($width) ? array($width, $height) : false;	
}


/////////////////////////////////////////////////////////////////////////////

// gallery data compress and save
function gg_gall_data_save($gid, $data, $autopop = false, $wp_gall_hash = '') {
	$str = serialize($data);
	if(function_exists('gzcompress') && function_exists('gzuncompress')) {
		$str = gzcompress($str, 9);
	}
	$str = base64_encode($str);
	
	if($autopop){
		delete_post_meta($gid, 'gg_autopop_cache');
		add_post_meta($gid, 'gg_autopop_cache', $str, true); 
	} else {
		delete_post_meta($gid, 'gg_gallery'.$wp_gall_hash);
		add_post_meta($gid, 'gg_gallery'.$wp_gall_hash, $str, true); 
	}

	return true;
}

// gallery data uncompress and get 
function gg_gall_data_get($gid, $autopop = false, $wp_gall_hash = '') {
	if(!$autopop){ $data = get_post_meta($gid, 'gg_gallery'.$wp_gall_hash, true); }
	else 		 { $data = get_post_meta($gid, 'gg_autopop_cache', true) ;}
	
	if(!is_array($data) && !empty($data)) {
		$string = base64_decode($data);
		if(function_exists('gzcompress') && function_exists('gzuncompress') && !empty($string)) {
			$string = gzuncompress($string);
		}
		$data = (array)unserialize($string);
	}
	
	if(!is_array($data) || (count($data) == 1 && !$data[0])) {$data = false;}
	return $data;
}


// images array serialization and compress for pagination
function gg_img_serialize_compress($images_array) {
	$str = serialize($images_array);
	if(function_exists('gzcompress') && function_exists('gzuncompress')) {
		$str = gzcompress($str, 9);
	}
	return base64_encode($str);
}

// images array unserialization and decompress for pagination
function gg_img_unserialize_decompress($string) {
	$string = base64_decode($string);
	if(function_exists('gzcompress') && function_exists('gzuncompress')) {
		$string = gzuncompress($string);
	}
	return unserialize($string);
}

/////////////////////////////////////////////////////////////////////////////

// gallery types
function gg_types($type = false) {
	$types = array(
		'wp' 		=> __('Wordpress Library', 'gg_ml'),
		'wp_cat' 	=> __('Wordpress Category', 'gg_ml'),
		'cpt_tax' 	=> __('Custom post type Taxonomy', 'gg_ml'),
		'gg_album'	=> __('Global Gallery Album', 'gg_ml'),
		'flickr'	=> __('Flickr Album / Photostream / Tag URL', 'gg_ml'),
		'instagram'	=> __('Instagram', 'gg_ml'),
		'pinterest' => __('Pinterest Board', 'gg_ml'),
		'fb'		=> __('Facebook Page Album', 'gg_ml'),
		'picasa'	=> __('Google+ Album', 'gg_ml'),
		'g_drive'	=> __('Google Drive', 'gg_ml'),
		'dropbox'	=> __('Dropbox', 'gg_ml'),
		'twitter'	=> __('Twitter', 'gg_ml'),
		'tumblr'	=> __('Tumblr Blog', 'gg_ml'),
		'ngg'		=> __('nextGEN Gallery', 'gg_ml'),
		'500px'		=> __('500px User', 'gg_ml'),
		'rss'		=> __('RSS Feed', 'gg_ml')
	);
	
	/*** remove sources if PHP version is old ***/
	$php_ver = (float)substr(PHP_VERSION, 0, 3);

	if($php_ver < 5.3) {unset($types['dropbox']);}
	if($php_ver < 5.4) {unset($types['fb']);}	
	if($php_ver < 5.4) {unset($types['picasa']);}	
	if($php_ver < 5.4) {unset($types['g_drive']);}	

	return (empty($type)) ? $types : $types[$type];
}


// username field label depending on the type
function gg_username_label($type) {
	switch($type) {
		case 'flickr': 		return __('Set / Profile / Tag URL', 'gg_ml'); break; 
		case 'pinterest': 	return __('Board URL', 'gg_ml'); break;
		case 'instagram':	return __('Username', 'gg_ml'); break; //return __('Username or #hashtag', 'gg_ml'); break;
		case 'twitter':		return __('@Username or #hashtag', 'gg_ml'); break;
		case 'tumblr':		return __('Blog URL', 'gg_ml'); break;
		case '500px':		return __('User URL', 'gg_ml'); break;
		case 'rss':			return __('Feed URL', 'gg_ml'); break;
		default: 			return __('Username', 'gg_ml'); break;	
	}
}


// cache intervals
function gg_cache_intervals($time = false) {
	$times = array(
		'1' 	=> __('1 Hour', 'gg_ml'),
		'2' 	=> __('2 Hours', 'gg_ml'),
		'6'		=> __('6 Hours', 'gg_ml'),
		'12'	=> __('12 Hours', 'gg_ml'),
		'24'	=> __('1 Day', 'gg_ml'),
		'72'	=> __('3 Days', 'gg_ml'),
		'168'	=> __('One week', 'gg_ml'), 
		'none'	=> __('Never', 'gg_ml')
	);
	
	if($time === false) {return $times;}
	else {return $times[$time];}	
}


// collection thumb widths
function gg_coll_widths($width = false) {
	$widths = array(
		'1' 		=> '1 '. __('Column', 'gg_ml'),
		'0.5' 		=> '2 '. __('Columns', 'gg_ml'),
		'0.3333'	=> '3 '. __('Columns', 'gg_ml'),
		'0.25'		=> '4 '. __('Columns', 'gg_ml'),
		'0.2'		=> '5 '. __('Columns', 'gg_ml'),
		'0.1666' 	=> '6 '. __('Columns', 'gg_ml'),
	);
	
	if($width === false) {return $widths;}
	else {return $widths[$width];}	
}


// turns float widths into columns number
function gg_float_to_cols_num($float) {	
	$cols = array(
		'1' 		=> 1,
		'0.5' 		=> 2,
		'0.3333'	=> 3,
		'0.25'		=> 4,
		'0.2'		=> 5,
		'0.1666' 	=> 6,
	);	
	return $cols[(string)$float];			
}


// img url grab from a string
function gg_string_to_url($string) {
	preg_match_all('/img[^>]*src *= *["\']?([^"\']*)/i', $string, $output, PREG_PATTERN_ORDER);
	if(isset($output[0][0])) {
		$raw_url = $output[0][0];	
		$url = substr($raw_url, 9);
		
		return $url;
	}
	else {return '';}
}


// get the LCweb lightbox patterns list 
function gg_lcl_patterns_list() {
	$patterns = array();
	$patterns_list = scandir(GG_DIR."/js/lightboxes/lcweb.lightbox/img/patterns");
	
	foreach($patterns_list as $pattern_name) {
		if($pattern_name != '.' && $pattern_name != '..') {
			$patterns[$pattern_name] = substr($pattern_name, 0, -4);
		}
	}
	return $patterns;	
}


// retrieve the gallery option or the default gallery value
function gg_check_default_val($gid, $key, $pointer) {
	if((empty($pointer) || $pointer == 'default') && $pointer !== '0') {return get_option($key);}
	else {return get_post_meta($gid, $key, true);}	
}


// create the frontend css and js
function gg_create_frontend_css() {	
	if(!ini_get('allow_url_fopen')) {return false;} // locked server
	
	ob_start();
	include_once(GG_DIR .'/frontend_css.php');
	
	$css = ob_get_clean();
	if(!empty($css)) {
		if(!@file_put_contents(GG_DIR.'/css/custom.css', $css, LOCK_EX)) {$error = true;}
	}
	else {
		if(file_exists(GG_DIR.'/css/custom.css')) {unlink(GG_DIR.'/css/custom.css');}
	}
	
	if(isset($error)) {return false;}
	else {return true;}
}


//////////////////////////////////////////////

// retrieve data for an hub connection
function gg_get_conn_hub_data($gid, $conn_id = false) {
	if(empty($conn_id)) {
		$conn_id = get_post_meta($gid, 'gg_connect_id', true);
		if(empty($conn_id)) {return false;}
	}
	
	$term = get_term($conn_id, 'gg_connect_hub');
	
	if(!is_object($term)) {return false;}
	return  unserialize(base64_decode($term->description));
}


// check if an array key exists and return its value or false
function gg_get_arr_key($array, $key) {
	return (empty($array) || !is_array($array) || !isset($array[$key]))	? false : $array[$key];
}


// get instagram user ID 
function gg_instagram_user_id($username, $token) {
	$api_url = 'https://api.instagram.com/v1/users/search/?q='.urlencode($username).'&access_token='.urlencode( trim($token));
	$json = gg_curl_get_contents($api_url);
	
	if($json === false ) {die( __('Error connecting to Instagram', 'gg_ml') .' ..');}
	$data = json_decode($json, true);

	if($data['meta']['code'] == 400) {return false;}
	else {
		$user_id = false;
		
		// search the exact username
		foreach($data['data'] as $user) {
			if(isset($user['id']) && strtolower($user['username']) == strtolower($username)) {
				$user_id = $user['id'];	
			}
		}
		
		if(!$user_id) {die( __('Username not found', 'gg_ml') .' ..');}
		return $user_id;
	}	
}


// get Flickr subject from given url
function gg_flickr_subj($url) {
	if(strpos($url, "flickr.com") === false) {return false;}	
	
	if		(strpos($url, "/sets/") !== false || strpos($url, "/albums/") !== false) {return 'set';}
	else if (strpos($url, "/photos/") !== false) {return 'photostream';}
	else if (strpos($url, "/tags/") !== false) {return 'tag';}
	else {return false;}
}


// get Flickr set ID or username or tag from url
function gg_flickr_subj_id($url) {
	$url_arr = explode('/', untrailingslashit($url));
	return end($url_arr);
}


// get 500px username
function gg_500px_username($url) {
	$url_arr = explode('/', $url);
	return end($url_arr);	
}


// dropbox img path to usable one
function gg_dropbox_img_path_man($path) {
	$arr = explode('/', substr($path, 1));
	
	$last = end($arr);
	unset($arr[0]);
	array_pop($arr);
	$arr[2] = rawurlencode($arr[2]);

	$name_clean = rawurlencode(gg_stringToFilename($last, true)) . strtolower(gg_stringToExt($last));
	return '/' . implode('/', $arr) . '/' . $name_clean;	
}


// get GG albums subfolders
function gg_get_albums() {
	$albums = glob( get_option('gg_albums_basepath', GGA_DIR).DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);
	
	if(!is_array($albums)) {return array();}
	else {
		$new_albums = array();
		foreach($albums as $album) {
			$arr = explode(DIRECTORY_SEPARATOR, $album);
			$folder = end($arr);
			$new_albums[$folder] = ucwords( str_replace(array('_', '-'), array(' ', ' '), $folder) );
		}
		return $new_albums;
	}
}


// get custom post types and taxonomies
function gg_get_cpt_with_tax() {
	$cpt = get_post_types(array('_builtin' => false), 'objects');
	$usable = array(); 
	
	foreach($cpt as $pt) {
		$tax = get_object_taxonomies($pt->name, 'objects');
		
		// add only if has a taxonomy
		if(is_array($tax) && !empty($tax)) {
			$tax_array = array();
			
			foreach($tax as $slug => $data) {
				$tax_array[$slug] = $data->labels->name;	
			}
			
			$usable[ $pt->name ] = array(
				'name' => $pt->labels->name,
				'tax' => $tax_array
			);		
		}
	}
	
	return (empty($usable)) ? array() : $usable;
}


// given cpt + taxonomy - get taxonomy terms in a select field
function gg_get_taxonomy_terms($cpt_tax, $sel_val = '') {
	$arr = explode('|||', $cpt_tax);
	$cats = get_terms($arr[1], 'orderby=name&hide_empty=0');

	$code = '
	<select data-placeholder="'. __('Select a term', 'gg_ml') .' .." name="gg_cpt_tax_term" id="gg_cpt_tax_term" class="lcweb-chosen">
		<option value="">'. __('all', 'gg_ml') .'</option>';
		
		if(is_array($cats)) {
			foreach($cats as $cat ) {
				$sel =  ($cat->term_id == $sel_val) ? 'selected="selected"' : '';
				$code .= '<option value="'.$cat->term_id.'" '.$sel.'>'.$cat->name.'</option>'; 
			}
		}

	return $code . '</select>'; 
}


// get nextGEN galleries
function gg_get_ngg_galleries($gid = false) {
	global $wpdb;
	$table_name = $wpdb->prefix . "ngg_gallery";	
	
	// check table existing
	if($wpdb->get_var("SHOW TABLES LIKE '". $table_name ."'") != $table_name) {
		die( __('nextGEN gallery plugin seems missing. No trace in the database', 'gg_ml') );	
	}
	
	// specific gallery path condition
	$search = ($gid) ? 'WHERE gid = '. (int)$gid : '';
	$query = $wpdb->get_results("SELECT gid, title, path FROM ". $table_name ." ".$search, ARRAY_A);

	if($gid) {
		// clean base to be usable with WP constants
		$base = $query[0]['path'];
		 
		if(substr($base, 0, 1) == DIRECTORY_SEPARATOR) {$base = substr($base, 1);}
		$base = explode(DIRECTORY_SEPARATOR, $base);
		unset($base[0]);
		
		return implode(DIRECTORY_SEPARATOR, $base);	
	} else {
		return $query;	
	}
}


// given the gallery type - return the image path ready to be used
function gg_img_src_on_type($raw_src, $type) {
	if($type == 'wp' || $type == 'wp_cat' || $type == 'wp_gall' || $type == 'cpt_tax') {
		$img_full_src = gg_img_id_to_path($raw_src);	
	} 
	elseif($type == 'gg_album') {
		$img_full_src = get_option('gg_albums_basepath', GGA_DIR) .'/'. $raw_src;	
	}
	elseif($type == 'ngg') {
		if(strpos($raw_src, WP_CONTENT_DIR) !== false) {$img_full_src = str_replace(WP_CONTENT_DIR.'/', '', $raw_src);} // fix old error in path calculation
		else {$img_full_src = $raw_src;}
		
		if(strpos($img_full_src, 'wp-content/') !== false) {$img_full_src = str_replace('wp-content/', '', $img_full_src);} // fix old error in path calculation
		else {$img_full_src = $img_full_src;}
		
		$img_full_src = (strpos($img_full_src, WP_CONTENT_DIR) === false) ? WP_CONTENT_DIR.'/'.$img_full_src : $img_full_src;	
	}
	else {$img_full_src = $raw_src;}	
	
	return str_replace(' ', '%20', $img_full_src);
}


// given the gallery type - return the image url ready to be used
function gg_img_url_on_type($raw_src, $type) {
	if($type == 'wp' || $type == 'wp_cat' || $type == 'wp_gall') {
		$img_url = $src = wp_get_attachment_image_src($raw_src, 'full');
		$img_url = $img_url[0];
	} 
	elseif($type == 'gg_album') {
		$img_url = get_option('gg_albums_baseurl', GGA_URL) .'/'. $raw_src;	
	}
	elseif($type == 'ngg') {
		if(strpos($raw_src, WP_CONTENT_DIR) !== false) {$img_url = str_replace(WP_CONTENT_DIR.'/', '', $raw_src);} // fix old error in path calculation
		else {$img_url = $raw_src;}
		
		if(strpos($img_url, 'wp-content/') !== false) {$img_url = str_replace('wp-content/', '', $img_url);} // fix old error in path calculation
		else {$img_url = $raw_src;}
		
		$img_url = (strpos($img_url, WP_CONTENT_URL) === false) ? WP_CONTENT_URL.'/'.$img_url : $img_url;
	}
	else {$img_url = $raw_src;}	
	
	return str_replace(' ', '%20', $img_url);
}


// check for deleted images in a gallery
function gg_gallery_img_exists($images, $gall_type) {
	if(!is_array($images)) {return array();}
	
	$expired = array();
	foreach($images as $index => $val) {
		$img_src = gg_img_src_on_type($val['img_src'], $gall_type);
		
		if(!function_exists('curl_init') || !filter_var($img_src, FILTER_VALIDATE_URL)) {
			if(!@file_get_contents($img_src)) {$expired[] = $index;}
		}
		else {
			if(!gg_rm_file_exists($img_src)) {$expired[] = $index;}
		}
	}
	
	foreach($expired as $index) {
		unset($images[$index]);	
	}
	
	return $images;
}


// check for expired images in gallery
function gg_expir_img_check($gid, $images, $gall_type, $autopop = false) {
	return $images; // KEEP DISABLED FOR NOW	
	//////////////////
	
	$timestamp = current_time('timestamp');	
	$last_check = (int)get_post_meta($gid, 'gg_last_check', true);
	$check_interval = (int)get_option('gg_check_interval');
	
	if($check_interval != 'none' && ini_get('allow_url_fopen') && $timestamp - $last_check >= $check_interval) {
		$old_images = $images;
		$images = gg_gallery_img_exists($images, $type);
		
		// if there are differences - overwrite
		if(count($old_images) != count($images)) {
			if($autopop) {
				update_post_meta($gid, 'gg_autopop_time', $timestamp);
			}
			
			gg_gall_data_save($gid, $images); 	
		}
		
		update_post_meta($gid, 'gg_last_check', $timestamp);
		return $images;	
	}	
	else {
		return $images;	
	}
}


// update auto-population cache
function gg_autopop_update_cache($gid, $manual_attr = array()) {
	include_once(GG_DIR . '/classes/gg_img_fetcher.php');
	
	$type 		= (empty($manual_attr)) ? get_post_meta($gid, 'gg_type', true) : $manual_attr['type'];
	$o_max_img 	= (empty($manual_attr)) ? get_post_meta($gid, 'gg_max_images', true) : $manual_attr['max_images'];
	$max_img 	= (empty($manual_attr)) ? get_post_meta($gid, 'gg_max_images', true) : $manual_attr['max_images'];
	$random 	= (empty($manual_attr)) ? get_post_meta($gid, 'gg_auto_random', true) : $manual_attr['random'];

	// extra data
	if(empty($manual_attr)) {
		switch($type) {
			case 'wp_cat'	: $extra = get_post_meta($gid, 'gg_wp_cat', true); 	break;
			case 'cpt_tax'	: $extra = array('cpt_tax' => get_post_meta($gid, 'gg_cpt_tax', true), 'term' => get_post_meta($gid, 'gg_cpt_tax_term', true)); 	break;
			case 'gg_album'	: $extra = get_post_meta($gid, 'gg_album', true); 	break;
			case 'fb'		: $extra = get_post_meta($gid, 'gg_fb_album', true); 	break;
			case 'picasa'	: $extra = get_post_meta($gid, 'gg_picasa_album', true); break;
			case 'dropbox'	: $extra = get_post_meta($gid, 'gg_dropbox_album', true); break;
			case 'g_drive'	: $extra = get_post_meta($gid, 'gg_gdrive_album', true); break;
			case 'ngg'		: $extra = get_post_meta($gid, 'gg_ngg_gallery', true); break;
			default			: $extra = ''; break; 	
		}
	}
	else {$extra = $manual_attr['extra'];}

	// images fetcher 
	$fetcher = new gg_img_fetcher($gid, $type, $page = 1, 9999, '', $extra);
	$img_data = $fetcher->get;
	
	$images = $img_data['img'];
	if($max_img >= count($images)) {$max_img = count($images);}
	
	if($random == '1') { 
		shuffle($images);
		
		$to_display = array();
		for($a=0; $a < $max_img; $a++) {
			$to_display[]	= $images[$a];
		}
	}
	else {
		$to_display = array();
		for($a=0; $a < $max_img; $a++) {
			if(isset($images[$a])) { $to_display[] = $images[$a]; }
		}
	}
	
	$to_save = array();
	foreach($to_display as $img) {
		if($type == 'wp' || $type == 'wp_cat') {$img_src = $img['id'];} 
		elseif($type == 'gg_album' || $type == 'ngg') {$img_src = $img['path'];}
		else {$img_src = $img['url'];}

		$to_save[] = array( 
			'url' 		=> $img['url'],
			'img_src'	=> $img_src,
			'author'	=> $img['author'],
			'title'		=> $img['title'],
			'descr'		=> $img['descr']
		);	
	}
	
	// if the maximum number is not reached, try to add the old images - only if erase past if false
	if(empty($manual_attr) || !$manual_attr['erase_past']) {
		if(count($to_save) < $o_max_img) {
			$old_img = gg_gall_data_get($gid, true);
			if(is_array($old_img)) {
					
				$a = 0;
				while($o_max_img > count($to_save) && isset($old_img[$a]))	 {
					$exists = false;
					foreach($to_save as $img) {
						if($old_img[$a]['img_src'] == $img['img_src']) {$exists = true;}
					}
					
					if(!$exists) {$to_save[] = $old_img[$a];}
					
					$a++;	
				}
			}
		}
	}
	
	// save the autopop cache
	gg_gall_data_save($gid, $to_save, true);

	// save creation time
	update_post_meta($gid, 'gg_autopop_time', current_time('timestamp'));
	
	return $to_save;
}


// check autopop creation time - if outdated refetch - and return the images array
function gg_autopop_expiry_check($gid) {
	$last_update = (int)get_post_meta($gid, 'gg_autopop_time', true);
	$update_interval = (int)get_post_meta($gid, 'gg_cache_interval', true) * 60 * 60;
	$timestamp = (int)current_time('timestamp');
	
	if($update_interval && $update_interval != 'none' && ($timestamp - $last_update) >= $update_interval) {
		$images = gg_autopop_update_cache($gid);
	}
	else {$images = gg_gall_data_get($gid, true);}
	
	return $images;
}


// get an existing page ID (for watermark lightbox)
function gg_a_page_id() {
	$args = array(
		'number' => 1,
		'post_status' => 'publish,draft'
	);
	$pages = get_pages();
	
	if(!is_array($pages)) {return 0;}
	else {return $pages[0]->ID;}
}


//////////////////////////////////////////


// Wordpress gallery images - get and cache
function gg_wp_gall_images($post_id, $img_list, $use_captions = false) {
	$gall_hash = '-'.md5($img_list); 
	$cached_list = get_post_meta($post_id, 'gg_new_wp_gall_img_list'.$gall_hash, true); 
	
	// if equal to the cached - do anything
	if($img_list == $cached_list) {return true;}
	
	// otherwise fetch everything and compose the gallery array
	else {
		$args = array(
			'post_type' => 'attachment', 
			'post_mime_type' =>'image', 
			'post_status' => 'inherit', 
			'posts_per_page' => -1,
			'orderby' => 'post__in',
			'post__in' => explode(',', $img_list)
		);
		$query = new WP_query($args);

		$images = array();
		foreach($query->posts as $image) {
			if(trim($image->guid) != '') {
				$images[] = array(
					'img_src'	=> $image->ID,
					'thumb' 	=> 'c',
					'author'	=> '',  
					'title'		=> $image->post_title,
					'descr'		=> $image->post_content,
					'link_opt'	=> '', 
					'link'		=> ''
				);
			}
		} 
	
		gg_gall_data_save($post_id, $images, $autopop = false, $gall_hash);
		
		delete_post_meta($post_id, 'gg_new_wp_gall_img_list'.'-'.md5($cached_list));
		delete_post_meta($post_id, 'gg_new_wp_gall_img_list'.$gall_hash);
		add_post_meta($post_id, 'gg_new_wp_gall_img_list'.$gall_hash, $img_list, true); 
	}
	
	return true;
}

///////////////////////////////////////////////////////////////////


// watermarker
function gg_watermark($img_url) {
	$cache_dir = GG_DIR.'/cache';
	$wm = get_option('gg_watermark_img');
	$pos = get_option('gg_watermark_pos');
	$opacity = get_option('gg_watermark_opacity');	
	
	$img_ext = substr(gg_stringToExt($img_url), 1);
	$img_name = gg_stringToFilename($img_url, true);	
	
	$encrypted_name = 'gg_watermarked_'.md5($img_name).'_'.$pos.'_'.$opacity.'.'.$img_ext;
	$destination = $cache_dir.'/'.$encrypted_name;
	
	// check for cached images
	if(file_exists($destination)) { return array(
		'path' => GG_DIR.'/cache/'.$encrypted_name, 
		'url' => GG_URL.'/cache/'.$encrypted_name
	);}
	else {
		include_once(GG_DIR . '/classes/PHPImageWorkshop/src/ImageWorkshop.php');
		@ini_set( 'memory_limit', '256M');

		/*if(!filter_var($img_url, FILTER_VALIDATE_URL)) {
			$imgLayer = ImageWorkshop::initFromPath($img_url);
		} else {
			$imgLayer = ImageWorkshop::initFromUrl($img_url);	
		}*/
		$imgLayer = ImageWorkshop::initFromPath($img_url);
				
		$watermarkLayer = ImageWorkshop::initFromPath($wm);
		$watermarkLayer->opacity($opacity);
		
		$imgLayer->addLayer(1, $watermarkLayer, 12, 12, $pos);		 
		$new_image = $imgLayer->getResult();
		
		switch($img_ext) {
			case 'gif' : imagegif($new_image, $destination);
				break;
			case 'png' : imagepng($new_image, $destination, 2);
				break;
			case 'jpg' :
			case 'jpeg' : imagejpeg($new_image, $destination, 95);
				break;
				
			default : 
				// get extension with cURL
				if(!filter_var($img_url, FILTER_VALIDATE_URL) === false) {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_AUTOREFERER, true);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_USERAGENT, true);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
		
					$ch = curl_init($img_url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_exec($ch);
					
					$mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

					if(strpos($mime, 'jpg') !== false || strpos($mime, 'jpeg') !== false) {
						imagejpeg($new_image, $destination, 95);	
					}
					elseif(strpos($mime, 'png') !== false) {
						imagepng($new_image, $destination, 2);
					}
					elseif(strpos($mime, 'gif') !== false) {
						imagejpeg($new_image, $destination, 95);
					}
					else {
						die('watermarker - format not supported "'.$img_ext.'"');	
					}
				}
				else {
					die('watermarker - format not supported "'.$img_ext.'"');
				}
				break;
		}	
		imagedestroy($new_image);
			
		if(!file_exists($destination)) {die( __('error during the image creation', 'gg_ml') );}
		else {return array(
			'path' => GG_DIR.'/cache/'.$encrypted_name, 
			'url' => GG_URL.'/cache/'.$encrypted_name
		);}	
	}
}


///////////////////////////////////////////////////////////////////

// predefined styles 
function gg_predefined_styles($style = '') {
	$styles = array(
		// LIGHTS
		'Light - Standard' => array(
			'gg_standard_hor_margin' => 5,
			'gg_standard_ver_margin' => 5,
			'gg_masonry_margin' => 7,
			'gg_photostring_margin' => 7,
			
			'gg_img_border' => 4,
			'gg_img_radius' => 4,
			'gg_img_shadow' => 'outshadow',
			'gg_img_border_color' => '#FFFFFF',
			
			'gg_main_ol_color' => '#ffffff',
			'gg_main_ol_opacity' => 80,
			'gg_main_ol_txt_color' => '#222222',
			'gg_sec_ol_color' => '#555555',
			'gg_icons_col' => '#fcfcfc',
			'gg_txt_u_title_color' => '#444444',
			'gg_txt_u_descr_color' => '#555555',
			
			'preview' => 'light_standard.jpg'
		),
		
		'Light - Minimal' => array(
			'gg_standard_hor_margin' => 6,
			'gg_standard_ver_margin' => 6,
			'gg_masonry_margin' => 8,
			'gg_photostring_margin' => 8,
			
			'gg_img_border' => 4,
			'gg_img_radius' => 1,
			'gg_img_shadow' => 'outline',
			'gg_img_outline_color' => '#bbbbbb', 
			'gg_img_border_color' => 'transparent',
			
			'gg_main_ol_color' => '#ffffff',
			'gg_main_ol_opacity' => 90,
			'gg_main_ol_txt_color' => '#222222',
			'gg_sec_ol_color' => '#555555',
			'gg_icons_col' => '#fefefe',
			'gg_txt_u_title_color' => '#444444',
			'gg_txt_u_descr_color' => '#555555',
			
			'preview' => 'light_minimal.jpg'
		),
		
		'Light - No Border' => array(
			'gg_standard_hor_margin' => 5,
			'gg_standard_ver_margin' => 5,
			'gg_masonry_margin' => 5,
			'gg_photostring_margin' => 5,
			
			'gg_img_border' => 0,
			'gg_img_radius' => 2,
			'gg_img_shadow' => 'outshadow',
			'gg_img_border_color' => '#FFFFFF',
			
			'gg_main_ol_color' => '#FFFFFF',
			'gg_main_ol_opacity' => 80,
			'gg_main_ol_txt_color' => '#222222',
			'gg_sec_ol_color' => '#555555',
			'gg_icons_col' => '#fcfcfc',
			'gg_txt_u_title_color' => '#444444',
			'gg_txt_u_descr_color' => '#555555',
			
			'preview' => 'light_noborder.jpg'
		),
		
		'Light - Photo Wall' => array(
			'gg_standard_hor_margin' => 0,
			'gg_standard_ver_margin' => 0,
			'gg_masonry_margin' => 0,
			'gg_photostring_margin' => 0,
			
			'gg_img_border' => 0,
			'gg_img_radius' => 0,
			'gg_img_shadow' => 'outshadow',
			'gg_img_border_color' => '#CCCCCC',

			'gg_main_ol_color' => '#FFFFFF',
			'gg_main_ol_opacity' => 80,
			'gg_main_ol_txt_color' => '#222222',
			'gg_sec_ol_color' => '#555555',
			'gg_icons_col' => '#fcfcfc',
			'gg_txt_u_title_color' => '#444444',
			'gg_txt_u_descr_color' => '#555555',
			
			'preview' => 'light_photowall.jpg'
		),
	
		// DARKS
		'Dark - Standard' => array(
			'gg_standard_hor_margin' => 5,
			'gg_standard_ver_margin' => 5,
			'gg_masonry_margin' => 7,
			'gg_photostring_margin' => 7,
			
			'gg_img_border' => 4,
			'gg_img_radius' => 4,
			'gg_img_shadow' => 'outshadow',
			'gg_img_border_color' => '#888888',
			
			'gg_main_ol_color' => '#141414',
			'gg_main_ol_opacity' => 90,
			'gg_main_ol_txt_color' => '#ffffff',
			'gg_sec_ol_color' => '#bbbbbb',
			'gg_icons_col' => '#555555',
			'gg_txt_u_title_color' => '#fefefe',
			'gg_txt_u_descr_color' => '#f7f7f7',
			
			'preview' => 'dark_standard.jpg'
		),
		
		'Dark - Minimal' => array(
			'gg_standard_hor_margin' => 6,
			'gg_standard_ver_margin' => 6,
			'gg_masonry_margin' => 8,
			'gg_photostring_margin' => 8,
			
			'gg_img_border' => 4,
			'gg_img_radius' => 1,
			'gg_img_shadow' => 'outline',
			'gg_img_outline_color' => '#777777', 
			'gg_img_border_color' => 'transparent',
			
			'gg_main_ol_color' => '#141414',
			'gg_main_ol_opacity' => 90,
			'gg_main_ol_txt_color' => '#ffffff',
			'gg_sec_ol_color' => '#bbbbbb',
			'gg_icons_col' => '#555555',
			'gg_txt_u_title_color' => '#fefefe',
			'gg_txt_u_descr_color' => '#f7f7f7',
			
			'preview' => 'dark_minimal.jpg'
		),

		'Dark - No Border' => array(
			'gg_standard_hor_margin' => 5,
			'gg_standard_ver_margin' => 5,
			'gg_masonry_margin' => 5,
			'gg_photostring_margin' => 5,
			
			'gg_img_border' => 0,
			'gg_img_radius' => 2,
			'gg_img_shadow' => 'outshadow',
			'gg_img_border_color' => '#999999',
			
			'gg_main_ol_color' => '#141414',
			'gg_main_ol_opacity' => 90,
			'gg_main_ol_txt_color' => '#ffffff',
			'gg_sec_ol_color' => '#bbbbbb',
			'gg_icons_col' => '#555555',
			'gg_txt_u_title_color' => '#fefefe',
			'gg_txt_u_descr_color' => '#f7f7f7',
			
			'preview' => 'dark_noborder.jpg'
		),
		
		'Dark - Photo Wall' => array(
			'gg_standard_hor_margin' => 0,
			'gg_standard_ver_margin' => 0,
			'gg_masonry_margin' => 0,
			'gg_photostring_margin' => 0,
			
			'gg_img_border' => 0,
			'gg_img_radius' => 0,
			'gg_img_shadow' => 'outshadow',
			'gg_img_border_color' => '#999999',
			
			'gg_main_ol_color' => '#141414',
			'gg_main_ol_opacity' => 90,
			'gg_main_ol_txt_color' => '#ffffff',
			'gg_sec_ol_color' => '#bbbbbb',
			'gg_icons_col' => '#555555',
			'gg_txt_u_title_color' => '#fefefe',
			'gg_txt_u_descr_color' => '#f7f7f7',

			'preview' => 'dark_photowall.jpg'
		),
	);
		
		
	if($style == '') {return $styles;}
	else {return $styles[$style];}	
}
