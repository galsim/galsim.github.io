<?php
/*	GALLERY BUILDER HUB 
	MAIN CLASS SETTING UP PARAMETERES AND RETURNING SPECIFIC OPTIONS CODE 
*/

class gg_builder_hub {
	
	public $gid; // (int) gallery ID
	public $src; // (string) images source type
	public $gall_params = array(); // (array) associative array containing gallery arguments to show images (eg. username, psw)
		
	
	/* construct - setup gallery ID and eventually source type */
	public function __construct($gid, $src = false) {
		$this->gid = $gid;
		
		$this->src = (empty($src)) ? get_post_meta($gid, 'gg_type', true) : $src;
		if(empty($this->src)) {$this->src = 'wp';}   // still empty == new gallery == set to WP
	}
	
	
	
	/* LOAD PARAMETERS */
	public function load_params($params = false) {
		// whether to use passed params
		if(is_array($params)) {
			$this->gall_params['username'] 		= (isset($params['username'])) ? $params['username'] : '';
			$this->gall_params['psw'] 			= (isset($params['psw'])) ? $params['psw'] : '';	
			$this->gall_params['connect_id'] 	= (isset($params['connect_id'])) ? $params['connect_id'] : '';	
		}
		else {
			$this->gall_params['username'] 		= get_post_meta($this->gid, 'gg_username', true);
			$this->gall_params['psw'] 			= get_post_meta($this->gid, 'gg_psw', true);
			$this->gall_params['connect_id'] 	= get_post_meta($this->gid, 'gg_connect_id', true);
		}
	}
	
	
	/* SAVE PARAMETERS */
	public function save_params() {
		if(empty($this->gall_params)) {$this->load_params();}
		
		update_post_meta($this->gid, 'gg_username'	, $this->gall_params['username']);	
		update_post_meta($this->gid, 'gg_psw'		, $this->gall_params['psw']);
		update_post_meta($this->gid, 'gg_connect_id', $this->gall_params['connect_id']);
	}
	
	
	
	/* HAS GALLERY BEEN SET UP? 
	 * boolean resource to know whether to show options or not
	 * @param (bool) $check_db = whether to check post_meta or just use $this->gall_params
	 */
	public function is_gallery_ready($check_db = false) {
		if(empty($this->src)) {return false;}
		if(empty($this->gall_params)) {$this->load_params();}
		
		if(in_array($this->src, array('instagram')) && !$this->gall_params['psw']) {
			return false;
		}
		if(in_array($this->src, array('fb', 'dropbox', 'picasa', 'g_drive')) && !$this->gall_params['connect_id']) {
			return false;
		}
		if(!in_array($this->src, array('wp', 'wp_cat', 'cpt_tax', 'gg_album', 'ngg', 'dropbox', 'picasa', 'g_drive', 'fb')) && !$this->gall_params['username']) {
			return false;
		}
		
		return true;	
	}
	
	
	
	/* BUILDER SPECIFIC OPTIONS - RELATED TO SOURCE
	 * @param $params (array) - associative array containing data needed to connect to sources (eg. username - password)
	 * @return (string) html code to be used in builder's AJAX request or directly
	 */
	public function spec_opt($params = false) {
		include_once(GG_DIR .'/functions.php');
		
		// load parameters
		$this->load_params($params);

		$gid = $this->gid; 
		$code = '<div class="lcwp_mainbox_meta lcwp_form">';
		
		// if gallery isn't ready - only message
		if(!$this->is_gallery_ready()) {
			return '<em>' . __('Select gallery type and fill in data to get images', 'gg_ml') . '</em>';
		}
		
		
		// autopopulation (if allowed)
		if($this->src != 'wp') {
			$autopop = get_post_meta($gid, 'gg_autopop', true);
			$auto_author = get_post_meta($gid, 'gg_auto_author', true);
			$auto_title = get_post_meta($gid, 'gg_auto_title', true);
			$auto_descr = get_post_meta($gid, 'gg_auto_descr', true);
			$cache_interval = get_post_meta($gid, 'gg_cache_interval', true);
			$auto_random = get_post_meta($gid, 'gg_auto_random', true);
			$max_images = get_post_meta($gid, 'gg_max_images', true);	if(!$max_images) {$max_images = 20;}
			
			// switches
			$autopop_vis = ($autopop == 1) ?  '' : 'style="display: none;"';

			// automatic gallery population option
			$code .= '
			<h4>' . __('Auto Gallery Population', 'gg_ml') . '</h4>
			<table class="widefat lcwp_table lcwp_metabox_table">	 
			  <tr>
				<td class="lcwp_label_td">' . __('Auto Population?', 'gg_ml') . '</td>
				<td class="lcwp_field_td" id="gg_autopop">
					<input type="checkbox" value="1" name="gg_autopop" class="ip-checkbox" '.gg_checkbox_check($autopop).' autocomplete="off" />
				</td>     
				<td><span class="info">' . __('Check to enable automatic gallery population', 'gg_ml') . '</span></td>
			  </tr>
			  <tr class="gg_autopop_fields" '.$autopop_vis.'>
				<td class="lcwp_label_td">' . __('Display Authors?', 'gg_ml') . '</td>
				<td class="lcwp_field_td">
					<input type="checkbox" value="1" name="gg_auto_author" class="ip-checkbox" '.gg_checkbox_check($auto_author).' autocomplete="off" />
				</td>     
				<td><span class="info">' . __('Check to display fetched authors', 'gg_ml') . '</span></td>
			  </tr>
			  <tr class="gg_autopop_fields" '.$autopop_vis.'>
				<td class="lcwp_label_td">' . __('Display Titles?', 'gg_ml') . '</td>
				<td class="lcwp_field_td">
					<input type="checkbox" value="1" name="gg_auto_title" class="ip-checkbox" '.gg_checkbox_check($auto_title).' autocomplete="off" />
				</td>     
				<td><span class="info">' . __('Check to display fetched titles', 'gg_ml') . '</span></td>
			  </tr>
			  <tr class="gg_autopop_fields" '.$autopop_vis.'>
				<td class="lcwp_label_td">' . __('Display Descriptions?', 'gg_ml') . '</td>
				<td class="lcwp_field_td">
					<input type="checkbox" value="1" name="gg_auto_descr" class="ip-checkbox" '.gg_checkbox_check($auto_descr).' autocomplete="off" />
				</td>     
				<td><span class="info">' . __('Check to display fetched descriptions', 'gg_ml') . '</span></td>
			  </tr>
			  <tr class="gg_autopop_fields" '.$autopop_vis.'>
				<td class="lcwp_label_td">' . __('Cache Interval', 'gg_ml') . '</td>
				<td class="lcwp_field_td">
				  <select data-placeholder="' . __('Select an option', 'gg_ml') . ' .." name="gg_cache_interval" class="lcweb-chosen" autocomplete="off">';
				  
				  foreach(gg_cache_intervals() as $key => $val) {
					  $sel = ($key == $cache_interval) ? 'selected="selected"' : '';
					  $code .= '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
				  }
				  
				$code .= '
				  </select>
				</td>     
				<td><span class="info">' . __('Choose gallery images cache interval', 'gg_ml') . '</span></td>
			  </tr>
			  <tr class="gg_autopop_fields" '.$autopop_vis.'>
				<td class="lcwp_label_td">' . __('Random Selection?', 'gg_ml') . '</td>
				<td class="lcwp_field_td" id="gg_auto_random">
					<input type="checkbox" value="1" name="gg_auto_random" class="ip-checkbox" '.gg_checkbox_check($auto_random).' autocomplete="off" />
					<span class="gg_rebuild_cache">(' . __('rebuild cache', 'gg_ml') . ')</span>
				</td>     
				<td><span class="info">' . __('Check to randomly select images between available ones', 'gg_ml') . '</span></td>
			  </tr>
			  <tr class="gg_autopop_fields" '.$autopop_vis.'>
				<td class="lcwp_label_td">' . __('Max Images', 'gg_ml') . '</td>
				<td class="lcwp_field_td">
					<input type="text" name="gg_max_images" value="'.$max_images.'" maxlength="4" id="gg_max_images" style="width: 50px;" autocomplete="off" />
					<span class="gg_rebuild_cache">(' . __('rebuild cache', 'gg_ml') . ')</span>
				</td>     
				<td><span class="info">' . __('Maximum gallery images number', 'gg_ml') . '</span></td>
			  </tr>
			</table>';	
		} // autopop end

		
		// specific selections - depending on source
		switch($this->src) {
			case 'wp_cat' :
				$sel_cat = get_post_meta($gid, 'gg_wp_cat', true);
				
				$code .= '
				<h4>'. __('Images Source', 'gg_ml') .'</h4>
				<table class="widefat lcwp_table lcwp_metabox_table" style="border: none;">
				  <tr class="gg_imgpckr_cat_sel_wrap">
					<td class="lcwp_label_td">'. __('Choose category', 'gg_ml') .'</td>
					<td class="lcwp_field_td">
					  <select data-placeholder="'. __('Select a category', 'gg_ml') .' .." name="gg_wp_cat" id="gg_wp_cat" class="lcweb-chosen" autocomplete="off">';
						  
						  foreach( get_categories() as $cat ) {
							  $sel = ($cat->term_id == $sel_cat) ? 'selected="selected"' : '';
							  $code .= '<option value="'.$cat->term_id.'" '.$sel.'>'.$cat->name.'</option>'; 
						  }
			  
				  $code .= '	
					  </select>
					</td>     
					<td><span class="info">'. __('Choose posts category to use as images source', 'gg_ml') .'</span></td>
				  </tr>
				</table>';
				break;
			
			
			case 'cpt_tax' :
				$sel_tax = get_post_meta($gid, 'gg_cpt_tax', true);
				$sel_term = get_post_meta($gid, 'gg_cpt_tax_term', true);
				
				$code .= '
				<h4>'. __('Images Source', 'gg_ml') .'</h4>
				<table class="widefat lcwp_table lcwp_metabox_table" style="border: none;">
				  <tr class="gg_imgpckr_cat_sel_wrap">
					<td class="lcwp_label_td">'. __('Choose post type and taxonomy', 'gg_ml') .'</td>
					<td class="lcwp_field_td">
					  <select data-placeholder="'. __('Select a category', 'gg_ml') .' .." name="gg_cpt_tax" id="gg_cpt_tax" class="lcweb-chosen" autocomplete="off">';
						  
						  $a = 0;
						  foreach( gg_get_cpt_with_tax() as $slug => $data ) {
							  $b = 0;
							  foreach($data['tax'] as $tax_slug => $tax_name) {
								  $val = $slug.'|||'.$tax_slug;
								  if($a == 0 && $b == 0) {$first_cpt_cat = $val;} // save first value for first term query
								  
								  $sel = ($sel_tax == $val) ? 'selected="selected"' : '';
								  $code .= '<option value="'.$val.'" '.$sel.'>'.$tax_name.'</option>'; 
								  
								  $b++;
							  }
							  $a++;
						  }
			  
				 $code .= '	
					  </select>
					</td>     
					<td></td>
				  </tr>
				  <tr class="gg_imgpckr_cat_sel_wrap">
					<td class="lcwp_label_td">'. __('Choose term', 'gg_ml') .'</td>
					<td class="lcwp_field_td" id="gg_ctp_tax_term_wrap">';
					
					  if(empty($sel_tax)) {$sel_tax = $first_cpt_cat;}
					  $code .= gg_get_taxonomy_terms($sel_tax, $sel_term);
				   
				  $code .= '	
					</td>     
					<td></td>
				  </tr>
				</table>';
				break;
			
			
			case 'gg_album' :
				$sel_album = get_post_meta($gid, 'gg_album', true);
				$albums = gg_get_albums();	
				
				if(!$albums || count($albums) == 0) {return '<strong>'. __('No albums found', 'gg_ml') .'</strong>';}
				
				$code .= '
				<h4>'. __('Images Source', 'gg_ml') .'</h4>
				<table class="widefat lcwp_table lcwp_metabox_table" style="border: none;">
				  <tr class="gg_imgpckr_cat_sel_wrap">
					<td class="lcwp_label_td">'. __('Choose an album', 'gg_ml') .'</td>
					<td class="lcwp_field_td">
					  <select data-placeholder="'. __('Select an album', 'gg_ml') .' .." name="gg_album" id="gg_album" class="lcweb-chosen" autocomplete="off">';
			  
						  foreach($albums as $folder => $name ) {
							  ($folder == $sel_album) ? $sel = 'selected="selected"' : $sel = '';
							  $code .= '<option value="'.$folder.'" '.$sel.'>'.$name.'</option>'; 
						  }
			  
				 $code .= '	
					  </select>
					</td>     
					<td><span class="info">'. __('Choose the gallery to use as image source', 'gg_ml') .'</span></td>
				  </tr>
				</table>';
				break;
				
				
			case 'flickr' :
				$subj = gg_flickr_subj($this->gall_params['username']);
				$subj_id = gg_flickr_subj_id($this->gall_params['username']);
				
				switch($subj) {
					case 'set' 			: if(!$subj_id) {return '<strong>'. __('ID not found - please insert a valid set URL', 'gg_ml') .'</strong>';}
						break;
					case 'photostream' 	: if(!$subj_id) {return '<strong>'. __('Username not found - please insert a valid profile URL', 'gg_ml') .'</strong>';}
						break;
					case 'tag' 			: if(!$subj_id) {return '<strong>'. __('No tag found - please insert a valid tag URL', 'gg_ml') .'</strong>';} 	
						break;
				}
				break;	
			
		
			case 'instagram' :
				// token existence
				if(empty($this->gall_params['psw'])) {
					return '<strong>'. __('Please insert a valid Instagram Token', 'gg_ml') .'</strong>';	
				}
				
				/* check instagram connection
				if(strpos($this->gall_params['username'], '#') === false) {
					$insta_auth = gg_instagram_user_id($this->gall_params['username'], $this->gall_params['psw']);
					if(!$insta_auth) {return '<strong>'. __('Connection failed - Username or token are wrong', 'gg_ml') .'</strong>';}
				}*/
				break;	
			
			
			case 'pinterest' :
				$pos = strpos($this->gall_params['username'], 'pinterest.com/');
				if($pos === false)  {
					return '<strong>'. __('Invalid URL - please insert a valid board URL', 'gg_ml') .'</strong>';
				}
				break;
			
		
			case 'fb' :
				include_once(GG_DIR . '/classes/source_helpers/fb_integration.php');
				$fb = new gg_facebook_integration($this->gall_params['connect_id']);
				$albums = $fb->get_albums(); 
				
				if(!is_array($albums) || !count($albums)) {return false;}
				$sel_album = get_post_meta($gid, 'gg_fb_album', true);

				$code .= '
				<h4>'. __('Images Source', 'gg_ml') .'</h4>
				<table class="widefat lcwp_table lcwp_metabox_table" style="border: none;">
				<tr class="gg_imgpckr_cat_sel_wrap">
				  <td class="lcwp_label_td">'. __('Choose an album', 'gg_ml') .'</td>
				  <td class="lcwp_field_td" colspan="2">
					<select data-placeholder="'. __('Select an album', 'gg_ml') .' .." name="gg_fb_album" id="gg_fb_album" class="lcweb-chosen" style="width: 100%; max-width: 500px;">';
						
						foreach($albums as $album) {
							$sel = ($album['id'] == $sel_album) ? 'selected="selected"' : '';
							$code .= '<option value="'.$album['id'].'" '.$sel.'>'.$album['name'].'</option>'; 
						}
			
				$code .= '	
					  </select>
					</td>     
				  </tr>
				</table>';
				break;
				
	
			case 'picasa' : // google+
				include_once(GG_DIR .'/classes/source_helpers/gplus_integration.php');
				$gplus = new gg_gplus_integration($this->gall_params['connect_id']);
				
				// retrieve albums
				$sel_album = get_post_meta($gid, 'gg_picasa_album', true);
				$albums = $gplus->get_albums($this->gall_params['username']);
				
				if($albums === false) {
					return '<strong>'. __('Connection error', 'gg_ml') .'</strong>';
				}
				elseif(is_array($albums) && !count($albums)) {
					return '<strong>'. __('No albums found', 'gg_ml') .'</strong>';
				}

				$code .= '
				<h4>'. __('Images Source', 'gg_ml') .'</h4>
				<table class="widefat lcwp_table lcwp_metabox_table" style="border: none;">
				  <tr class="gg_imgpckr_cat_sel_wrap">
					<td class="lcwp_label_td">'. __('Choose an Album', 'gg_ml') .'</td>
					<td class="lcwp_field_td">
					  <select data-placeholder="'. __('Select an album', 'gg_ml') .' .." name="gg_picasa_album" id="gg_picasa_album" class="lcweb-chosen" autocomplete="off">';
						  
						  foreach( $albums as $id => $name ) {
							  ($id == $sel_album) ? $sel = 'selected="selected"' : $sel = '';
							  $code .= '<option value="'.$id.'" '.$sel.'>'.$name.'</option>'; 
						  }
			  
				  $code .= '	
					  </select>
					</td>     
					<td><span class="info">'. __('Choose the album to use as image source', 'gg_ml') .'</span></td>
				  </tr>
				</table>';
				break;	
			
			
			case 'g_drive' :
				include_once(GG_DIR .'/classes/source_helpers/gdrive_integration.php');
				$gdrive = new gg_gdrive_integration($this->gall_params['connect_id']);

				$sel_album = get_post_meta($gid, 'gg_gdrive_album', true);
				$albums = $gdrive->list_albums();

				if(!$albums) {return '<strong>'. __('Connection error', 'gg_ml') .'</strong>';}
				if(!is_array($albums)) {return '<strong>'. $albums .'</strong>';}
				
				$code .= '
				<h4>'. __('Images Source', 'gg_ml') .'</h4>
				<table class="widefat lcwp_table lcwp_metabox_table" style="border: none;">
				  <tr class="gg_imgpckr_cat_sel_wrap">
					<td class="lcwp_label_td">'. __('Choose an Album', 'gg_ml') .'</td>
					<td class="lcwp_field_td">
					  <select data-placeholder="'. __('Select an album', 'gg_ml') .' .." name="gg_gdrive_album" id="gg_gdrive_album" class="lcweb-chosen" autocomplete="off">';
						  
						  foreach( $albums as $id => $name ) {
							  ($id == $sel_album) ? $sel = 'selected="selected"' : $sel = '';
							  $code .= '<option value="'.$id.'" '.$sel.'>'.$name.'</option>'; 
						  }
			  
				  $code .= '	
					  </select>
					</td>     
					<td><span class="info">'. __('Choose the album to use as image source', 'gg_ml') .'</span></td>
				  </tr>
				</table>';
				break;
			
			
			case 'dropbox' :
				include_once(GG_DIR .'/classes/source_helpers/dropbox_functions.php');
				
				$sel_album = get_post_meta($gid, 'gg_dropbox_album', true);
				$conect_data = gg_get_conn_hub_data($gid, $this->gall_params['connect_id']);
				$access_token = gg_get_arr_key($conect_data, 'token');
				
				// retrieve albums
				$albums = gg_dropbox_list_albums($gid, $access_token);
				$code .= '
				<h4>'. __('Images Source', 'gg_ml') .'</h4>
				<table class="widefat lcwp_table lcwp_metabox_table" style="border: none;">
				  <tr class="gg_imgpckr_cat_sel_wrap">
					<td class="lcwp_label_td">'. __('Choose an Album', 'gg_ml') .'</td>
					<td class="lcwp_field_td">
					  <select data-placeholder="'. __('Select an album', 'gg_ml') .' .." name="gg_dropbox_album" id="gg_dropbox_album" class="lcweb-chosen" tabindex="2">';
						  
						  foreach( $albums as $name ) {
							  ($name == $sel_album) ? $sel = 'selected="selected"' : $sel = '';
							  $code .= '<option value="'.$name.'" '.$sel.'>'.$name.'</option>'; 
						  }
			  
				  $code .= '	
					  </select>
					</td>     
					<td><span class="info">'. __('Choose the album to use as images source', 'gg_ml') .'</span></td>
				  </tr>
				</table>';
				break;
			
			
			case 'twitter' :
				if(!preg_match("/\@[a-z0-9_]+/i", $this->gall_params['username']) && !preg_match("/\#[a-z0-9_]+/i", $this->gall_params['username'])) {
					return '<strong>'. __('Please use a valid username or hashtag', 'gg_ml') .'</strong>';	
				}
				break;	
			
			
			case 'tumblr' :
				if(!filter_var($this->gall_params['username'], FILTER_VALIDATE_URL)) {return '<strong>'. __('Invalid URL - please insert a valid blog URL', 'gg_ml') .'</strong>';}
				break;	
				
				
			case '500px' :
				$username = gg_500px_username($this->gall_params['username']);
				if(empty($username)) {return '<strong>'. __('Invalid URL - please insert a valid user URL', 'gg_ml') .'</strong>';}
				break;		
				
			
			case 'ngg' :
				$ngg_galls = gg_get_ngg_galleries();
				$sel_gall = get_post_meta($gid, 'gg_ngg_gallery', true);
			
				$code .= '
				<h4>'. __('Images Source', 'gg_ml') .'</h4>
				<table class="widefat lcwp_table lcwp_metabox_table" style="border: none;">
				  <tr class="gg_imgpckr_cat_sel_wrap">
					<td class="lcwp_label_td">'. __('Choose Gallery', 'gg_ml') .'</td>
					<td class="lcwp_field_td">
					  <select data-placeholder="'. __('Select a gallery', 'gg_ml') .' .." name="gg_ngg_gallery" id="gg_ngg_gallery" class="lcweb-chosen" tabindex="2">';
						  
						  foreach($ngg_galls as $gall) {
							  ($gall['gid'] == $sel_gall) ? $sel = 'selected="selected"' : $sel = '';
							  $code .= '<option value="'.$gall['gid'].'" '.$sel.'>'.$gall['title'].'</option>'; 
						  }
			  
				  $code .= '	
					  </select>
					</td>     
					<td><span class="info">'. __('Choose the nextGEN gallery to use as image source', 'gg_ml') .'</span></td>
				  </tr>
				</table>';
				break;		
				
			
			case 'rss' :
				if(!filter_var($this->gall_params['username'], FILTER_VALIDATE_URL)) {return '<strong>'. __('Invalid URL - please insert a valid feed URL', 'gg_ml') .'</strong>';}
				break;
		}
		/////////////
		
		
		if($this->src == 'wp' || $this->src == 'g_drive') {
			$btn_part = '
			<input type="button" value="'. __('Add to Gallery', 'gg_ml') .'" id="gg_add_img" class="button-secondary" />
			<h4>'. __('Choose images', 'gg_ml') .' <span id="gg_total_img_num"></span> <span class="gg_TB gg_upload_img add-new-h2">'. __('Manage Images', 'gg_ml') .'</span>
			
			  <span class="gg_img_search_btn" title="search"></span>
			  <input type="text" placeholder="'. __('search', 'gg_ml') .' .." class="gg_img_search" autocomplete="off" />
			  <input type="button" class="button-secondary gg_sel_all_btn" value="'. __('Select all', 'gg_ml') .'"/> 
			</h4>';
		}
		else {
			$btn_part = '
			<input type="button" value="'. __('Add to Gallery', 'gg_ml') .'" id="gg_add_img" class="button-secondary" />
			<h4>
			  '. __('Choose images', 'gg_ml') .' <span id="gg_total_img_num"></span>
			  <input type="button" class="button-secondary gg_sel_all_btn" value="'. __('Select all', 'gg_ml') .'"/> 
			</h4>';	
		}
		
		
		$img_pick_vis = (isset($autopop) && $autopop) ? 'style="display: none;"' : ''; 
		$code .= '
		  <table class="widefat lcwp_table lcwp_metabox_table" style="border: none;">
		  <tr id="gg_img_picker_area" '.$img_pick_vis.'>
			<td class="lcwp_label_td" colspan="3">
			  '. $btn_part .'
			  <div id="gg_img_picker"></div>	
			</td>
		</tr>
		</table>';	
	
	
		$this->save_params();
		return $code.'</div>';
	}	
	
}
