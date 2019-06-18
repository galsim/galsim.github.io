<?php
/*	GALLERY IMAGES FETCHER
	USES BUILDER HUB CLASS TO SETUP PARAMETERS - DIRECTLY RETURNS IMAGES ARRAY
*/

include_once('gg_builder_sources_hub.php');
class gg_img_fetcher extends gg_builder_hub {
	
	/* INHERITED 
	public $gid; // (int) gallery ID
	public $src; // (string) images source type
	public $gall_params = array(); // (array) associative array containing gallery arguments to show images (eg. username, psw)
	*/
	
	
	public $page = 1; // image picker page
	public $per_page = 15; // how many images to show per page
	public $search = ''; // how many images to show per page
	public $extra = array(); // additional parameters to fetch images
	
	private $tot_images; // how many images that source contains
	public $get = array(); // variable containing data to return
	

	/* CONSTRUCT - recall parameters and eventually query gallery type - then fetches images 
	 *
	 * @param (string) $search - string used to match images performing searches (where available)
	 * @param (string/array) $extra - extra value or associative array of values to fetch images in specific sources
	 *
	 * @return (array) associative array containing fetch result
	 	array('img' => $to_show, 'pag' => $page, 'tot_pag' =>$tot_pag, 'more' => $more, 'tot' => $img_num)		
	*/
	public function __construct($gid, $type, $page = 1, $per_page = 15, $search = '', $extra = array()) {
		parent::__construct($gid, $type);
		
		// setup vars
		$this->page = $page;
		$this->per_page = $per_page;
		$this->search = $search;
		$this->extra = $extra;
		
		
		$images = $this->fetch_by_type();
		$img_num = (empty($this->tot_images)) ? count($images) : $this->tot_images;
		

		// calculate total pages
		$tot_pag = ceil($img_num / $per_page);
		
		// can show more?
		$shown = $per_page * $page;
		$more = ($shown >= $img_num) ? false : true; 
		
		// images array offset
		if(!in_array($this->src, array('wp', 'wp_cat', 'ngg', 'flickr'))) {
			$to_show = array();
			$offset = $per_page * ($page - 1);
			for($a=$offset; $a <= ($offset + $per_page); $a++) {
				$index = $a -1;
				if(isset($images[$index])) { $to_show[] = $images[$index]; }	
			}
		}
		else {
			$to_show = $images;
		}
		
		$this->get = array('img' => $to_show, 'pag' => $page, 'tot_pag' =>$tot_pag, 'more' => $more, 'tot' => $img_num);
		return true;
	}
	
	
	
	/* physically fetch images */
	private function fetch_by_type() {
		include_once(GG_DIR .'/functions.php');		
		switch($this->src) {
			default : return array(); break;
			  
			
			// Wordpress global images
			case 'wp' :
				$query_images_args = array(
					'post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 
					'offset' => (($this->page - 1) * $this->per_page),
					'posts_per_page' => $this->per_page
				);
				
				if(!empty($this->search)) {
					$query_images_args['s'] = $this->search;
				}
				
				$query_images = new WP_Query($query_images_args);
				$images = array();
			
				foreach($query_images->posts as $image) { 
					if(trim($image->guid) != '') {
						$images[] = array(
							'id'	=> $image->ID,
							'path'	=> trim(gg_img_id_to_path($image->ID)),
							'url' 	=> trim($image->guid),
							'author'=> '',  
							'title'	=> $image->post_title,
							'descr'	=> $image->post_content
						);
					}
				}
				
				$this->tot_images = $query_images->found_posts;
				return $images;
				break;
			
			
			
			// Wordpress category images
			case 'wp_cat' :
				$query_images_args = array(
					'post_type' => 'post', 'post_status' => 'publish', 'meta_key' => '_thumbnail_id', 
					'offset' => (($this->page - 1) * $this->per_page),
					'posts_per_page' => $this->per_page,
					'cat' => $this->extra 
				);
				
				$query_images = new WP_Query($query_images_args);
				$images = array();
				
				foreach($query_images->posts as $post) {
					$img_id = (int)get_post_thumbnail_id( $post->ID );	
					if(is_int($img_id) && !isset($images[$img_id])) {  // avoid duplicates
						$image = get_post($img_id);
						
						if(isset($image->ID)) {
							$images[] = array(
								'id'	=> $image->ID,
								'path'	=> trim(gg_img_id_to_path($img_id)),
								'url' 	=> trim($image->guid),
								'author'=> '', 
								'title'	=> $image->post_title,
								'descr'	=> $image->post_content
							);
						}
					}
				}

				$this->tot_images = $query_images->found_posts;
				return $images;
				break;
			
			
			
			// Custom post type's taxonomy images
			case 'cpt_tax' :
				$cpt_arr = explode('|||', $this->extra['cpt_tax']);
				$term = $this->extra['term'];
				
				$query_images_args = array(
					'post_type' => $cpt_arr[0], 
					'post_status' => 'publish', 
					'meta_key' => '_thumbnail_id', 
					'offset' => (($this->page - 1) * $this->per_page),
					'posts_per_page' => $this->per_page
				);
				
				if($term) {
					$query_images_args['tax_query'] = array(
						array(
							'taxonomy' => $cpt_arr[1],
							'field' => 'id',
							'terms' => $term,
							'include_children' => true
						)
					);	
				} else {
					$query_images_args['taxonomy'] = $cpt_arr[1];
				}
					 
					 
				$query_images = new WP_Query($query_images_args);
				$images = array();
				
				foreach($query_images->posts as $post) {
					$img_id = (int)get_post_thumbnail_id( $post->ID );	
					if(is_int($img_id) && !isset($images[$img_id])) {  // avoid duplicates
						$image = get_post($img_id);
						
						if(isset($image->ID)) {
							$images[] = array(
								'id'	=> $image->ID,
								'path'	=> gg_img_id_to_path($img_id),
								'url' 	=> $image->guid,
								'author'=> '', 
								'title'	=> $image->post_title,
								'descr'	=> $image->post_content
							);
						}
					}
				}

				$this->tot_images = $query_images->found_posts;
				return $images;
				break;
				
			
			
			// GG album images
			case 'gg_album' :
				$path = get_option('gg_albums_basepath', GGA_DIR) . '/'. $this->extra;
				if(!file_exists($path)) {return array();}
				
				$raw_images = scandir($path);
				unset($raw_images[0], $raw_images[1]);
				natsort($raw_images);
				
				$images = array();
				foreach($raw_images as $img_url) {
					// select only images
					$ext = strtolower(gg_stringToExt($img_url));
					if(in_array($ext, array('.png', '.jpg', '.jpeg', '.gif')) !== false) {
						$title = gg_stringToFilename($img_url);
						
						if(empty($this->search) || strpos(strtolower($title), strtolower($this->search)) !== false) {
							
							// try to get IPTC image info
							@getimagesize($path.'/'.$img_url, $info);
							if(isset($info) && isset($info['APP13'])) {
								$iptc = iptcparse($info['APP13']);
								
								$title = (get_option('gga_img_title_src') == 'iptc' && isset($iptc['2#005']) && !empty($iptc['2#005'][0])) ? $iptc['2#005'][0] : $title;
								$descr = (isset($iptc['2#120']) && !empty($iptc['2#120'][0])) ? $iptc['2#120'][0] : '';
								
								$author = (isset($iptc['2#080']) && !empty($iptc['2#080'][0])) ? $iptc['2#080'][0] : '';
								if(empty($author)) {
									$author = (isset($iptc['2#116']) && !empty($iptc['2#116'][0])) ? $iptc['2#116'][0] : '';
								}
							}
							else {
								$descr = '';
								$author = '';	
							}
			
							$images[] = array(
								'path'	=> $this->extra.'/'.$img_url, 
								'url' 	=> get_option('gg_albums_baseurl', GGA_URL) . '/'.$this->extra.'/'.$img_url, 
								'author'=> $author,
								'title'	=> $title,
								'descr'	=> $descr
							);	
						}
					}
				}

				return $images;
				break;
			
			
			
			// Flickr images
			case 'flickr' : 
				$subj_url 	= get_post_meta($this->gid, 'gg_username', true);
				$subj 		= gg_flickr_subj($subj_url);
				$subj_id 	= gg_flickr_subj_id($subj_url);
				
				switch($subj) {
					case 'set' : 
						$api_url = 'https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=98d15fe4ecf8fc21d95b4a7b5cac7227&photoset_id='.urlencode($subj_id).'&extras=url_m%2C+url_h%2C+url_o%2Cdescription&format=json&nojsoncallback=1&media=photos&page='. $this->page .'&per_page='. $this->per_page;
						break;
						
					case 'photostream' : 
						$api_url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=98d15fe4ecf8fc21d95b4a7b5cac7227&user_id='.urlencode($subj_id).'&privacy_filter=public+photos&extras=url_m%2C+url_h%2C+url_o%2C+owner_name%2Cdescription&page='. $this->page .'&per_page='. $this->per_page .'&media=photos&format=json&nojsoncallback=1';
						break;
						
					case 'tag' :
						$api_url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=98d15fe4ecf8fc21d95b4a7b5cac7227&tags='.urlencode($subj_id).'&privacy_filter=public+photos&extras=url_m%2C+url_h%2C+url_o%2C+owner_name%2Cdescription&page='. $this->page .'&per_page='. $this->per_page .'&media=photos&format=json&nojsoncallback=1';
						break;
						
					default : $api_url = ''; break;	
				}
				
				$json = gg_curl_get_contents($api_url);
				
				if($json === false ) {die( __('Error connecting to Flickr', 'gg_ml').' ..');}
				$data = json_decode($json, true);
			
				if(!is_array($data) || !$subj_id) {die( __('Connection Error - check your URL', 'gg_ml') );}
				if($data['stat'] != 'ok') {die( __('Invalid data - check your URL', 'gg_ml') );}
				
				// images array basing on subject
				if($subj == 'set') {$img_data = $data['photoset']['photo'];}
				else {$img_data = $data['photos']['photo'];}
				
				if(!is_array($img_data)) {return array();}
				$images = array();
				
				foreach($img_data as $image) {
					if		(isset($image['url_o'])) {$img_url = $image['url_o'];}
					elseif	(isset($image['url_h'])) {$img_url = $image['url_h'];} 
					else 							 {$img_url = $image['url_m'];}
			
					// owner depending on source
					if($subj == 'set') {$owner = $data['photoset']['ownername'];}
					else {$owner = $image['ownername'];}
			
					$images[] = array(
						'url' 	=> $img_url, 
						'author'=> $owner,
						'title'	=> gg_clean_emoticons($image['title']),
						'descr'	=> gg_clean_emoticons($image['description']['_content'])
					);
				}
				
				// total images
				if($subj == 'set') {$total = $data['photoset']['total'];}
				else {$total = $data['photos']['total'];}
				
				$this->tot_images = $total;
				return $images;
				break;
			
			
			
			// Pinterest board images
			case 'pinterest':
				$feed_url =  untrailingslashit( str_replace('http://', 'https://', strtolower(get_post_meta($this->gid, 'gg_username', true))) ) . '.rss';
				$feed = (string)gg_curl_get_contents($feed_url);
				
				$pos = strpos($feed, '<?xml version="1.0" encoding="utf-8"?>');
				if($pos === false) {die( __('Connection Error - check your board URL', 'gg_ml') );}
				if(!function_exists('simplexml_load_string')) {die( __("Your server doesn't support SimpleXML", 'gg_ml').'  ..');}
				
				$xml = simplexml_load_string($feed);
				
				$images = array();
				foreach ($xml->channel->item as $image) {
					$img_url = gg_string_to_url($image->description);
					$full_img_url = str_replace(array('/192x/', '/236x/'), '/550x/', $img_url);
					
					$pos = strpos($full_img_url, '.pinterest.com');
					if($pos) {
						$new_url = 'http://media-cache-ec' . substr($full_img_url, $pos - 1);
					} else {
						$new_url = $full_img_url;	
					}
					
					$images[] = array(
						'url' 	=> $new_url, 
						'author'=> '',
						'title'	=> gg_clean_emoticons($image->title), 
						'descr'	=> gg_clean_emoticons(strip_tags($image->description))
					);
				}
				
				return $images;
				break;
			
			
			
			// Facebook Page images
			case 'fb' :
				include_once(GG_DIR . '/classes/source_helpers/fb_integration.php');
				$fb = new gg_facebook_integration( get_post_meta($this->gid, 'gg_connect_id', true) );
	
				$offset = (($this->page - 1) * $this->per_page);
				$images_query = $fb->album_images($this->extra, $this->per_page, $offset);
				
				$this->tot_images = $fb->album_images_count($this->extra);
				if(!is_array($images_query) || $this->tot_images === false) {die( __('Error connecting to Facebook', 'gg_ml').' ..');}

				$images = array();
				foreach($images_query as $image) {
					$images[] = array(
						'url' 	=> $image['images'][0]['source'],
						'author'=> $image['from']['name'],
						'title'	=> '',
						'descr'	=> (isset($image['name'])) ? gg_clean_emoticons($image['name']) : ''
					);
				}
				
				return $images;
				break;
			
			
			
			// Instagram images
			case 'instagram' :
				$fetched_data = array();
				$token = urlencode( get_post_meta($this->gid, 'gg_psw', true));
				
				/*
				$subj = get_post_meta($this->gid, 'gg_username', true);
				
				// switch between user and hashtag
				if(strpos($subj, '#') === false) {
					$user_id = gg_instagram_user_id($subj , get_post_meta($this->gid, 'gg_psw', true) );
					$api_url = 'https://api.instagram.com/v1/users/'. $user_id .'/media/recent/?count=1000&access_token='.$token;	
				}
				else {
					$api_url = 'https://api.instagram.com/v1/tags/'. str_replace('#', '', $subj) .'/media/recent/?count=1000&access_token='.$token;	
				}
				*/
				
				$api_url = 'https://api.instagram.com/v1/users/self/media/recent/?access_token='. $token .'&count=100';
				
				// first call - 33 images
				$json = gg_curl_get_contents($api_url);
				if($json === false ) {die( __('Error connecting to Instagram', 'gg_ml').' ..');}
				$data = json_decode($json, true);

				if($data['meta']['code'] == 400) {die( __('Connection Error - Check your token', 'gg_ml') );}
				$fetched_data = $data['data'];


				// try getting next images with two further curl calls
				for($a=1; $a<=2; $a++) {
					if(isset($data['pagination']) && isset($data['pagination']['next_url']) && !empty($data['pagination']['next_url'])) {
						$next_pag_url = $data['pagination']['next_url'] . '&access_token='.$token;
						$json = gg_curl_get_contents($next_pag_url);
						$data = json_decode($json, true);
						
						$fetched_data = array_merge($fetched_data, $data['data']);	
					}
				}

				// retrieve images
				$images = array();
				foreach($fetched_data as $image) {
					$descr = (isset($image['caption']['text'])) ? $image['caption']['text'] : '';
					$img_url = (is_array($image['images']['standard_resolution'])) ? $image['images']['standard_resolution']['url'] : $image['images']['standard_resolution'];
			
					$images[] = array(
						'url' 	=> (string)$img_url, 
						'author'=> $image['user']['full_name'],
						'title'	=> '',
						'descr'	=> gg_clean_emoticons($descr)
					);
				}
				
				return $images;
				break;
			
			
			// Google+ album images
			case 'picasa' :
				include_once(GG_DIR .'/classes/source_helpers/gplus_integration.php');
				$gplus = new gg_gplus_integration( get_post_meta($this->gid, 'gg_connect_id', true) );
				
				// retrieve images
				$data = $gplus->get_images($this->extra);
				if(!is_object($data)) {return array();}
				
				$images = array();
				foreach($data as $img) {
					
					// add /s2048 to get full-res image
					$url = $img->content['src'];
					$pos = strrpos($url, '/');
					$full_url = substr_replace($url, '/s2048/', $pos, strlen('/'));
					
					$images[] = array(
						'url' 	=> $full_url, 
						'author'=> '',
						'title'	=> str_replace(array('.jpg', '.JPG', '.png', '.PNG', '.gif', '.GIF'), '', (string)$img->title),
						'descr'	=> (string)$img->summary 
					);	
				}
			
				if(count($images) != 0) {$images = array_reverse($images);}
				return $images;
				break;
			
			
			
			// Google Drive album images
			case 'g_drive' :
				include_once(GG_DIR .'/classes/source_helpers/gdrive_integration.php');
				$gdrive = new gg_gdrive_integration( get_post_meta($this->gid, 'gg_connect_id', true) );
				$images = $gdrive->get_images($this->extra, $this->search);

				if($images === false) {die('<strong>'. __('Connection error', 'gg_ml') .'</strong>');}
				if(!is_array($images)) {die('<strong>'. $images .'</strong>');}
				
				return $images;
				break;
			
			
			
			// dropbox images
			case 'twitter' :
				include_once(GG_DIR .'/classes/source_helpers/twitter_oauth.php');
				$subj = get_post_meta($this->gid, 'gg_username', true);

				// searching in users
				if(strpos($subj, '@') !== false) {
					$subj = str_replace('@', '', $subj);
					$string = 'statuses/user_timeline.json?screen_name='. urlencode($subj) .'&include_entities=true&exclude_replies=true&include_rts=false&contributor_details=false&count=200';
					$tw = new gg_twitter_oauth($string);

					if(isset($tw->errors)) {
						die('<strong>'. __('Profile not found', 'gg_ml') .' ..</strong>');	
					}
					if(!is_array($tw->data) || !count($tw->data)) {
						die('<strong>'. __('No images found', 'gg_ml') .' ..</strong>');	
					}
					
					
					$images = array();
					foreach($tw->data as $elem) {
						if(!isset($elem->entities->media) || !is_array($elem->entities->media) || !count($elem->entities->media) || !isset($elem->entities->media[0]->media_url)) {continue;}
						
						$url = $elem->entities->media[0]->media_url;
						$images[$url] = array(
							'url' 	=> $url, 
							'author'=> $elem->user->name,
							'title'	=> '',
							'descr'	=> $elem->text
						);
					}
				}
				
				// hashtags
				else {
					$string = 'search/tweets.json?q='. urlencode($subj) .'&include_entities=true&result_type=mixed&count=100';
					$tw = new gg_twitter_oauth($string);

					if(!isset($tw->data) || !count($tw->data->statuses)) {
						die('<strong>'. __('No images found', 'gg_ml') .' ..</strong>');	
					}
					$fetched_images = $tw->data->statuses;

					// try getting next images with two further curl calls
					for($a=1; $a<=2; $a++) {
						if(isset($tw->data->search_metadata->next_results) && $tw->data->search_metadata->next_results) {
							$string = 'search/tweets.json'. $tw->data->search_metadata->next_results;
							$tw = new gg_twitter_oauth($string);
			
							$fetched_images = array_merge($fetched_images, $tw->data->statuses);
						}
					}

					$images = array();
					foreach($fetched_images as $elem) {
						if(!isset($elem->entities->media) || !is_array($elem->entities->media) || !count($elem->entities->media)) {continue;}
						
						$url = $elem->entities->media[0]->media_url;
						$images[$url] = array(
							'url' 	=> $url, 
							'author'=> $elem->user->name,
							'title'	=> '',
							'descr'	=> $elem->text
						);
					}
				}
				
				return array_values($images); // remove keys
				break;
			
			
			
			// dropbox images
			case 'dropbox' :
				include_once(GG_DIR .'/classes/source_helpers/dropbox_functions.php');
				return gg_dropbox_images($this->gid, $this->extra);
				break;
				
			
			
			// tumblr images
			case 'tumblr' :
				// get clean domain
				$normalized = strtolower(untrailingslashit(get_post_meta($this->gid, 'gg_username', true)));
				$domain = str_replace(array('http://', 'https://', 'www.'), '', $normalized);
				
				$images = array();
				for($a=0; $a <= 2; $a++) {
					$api_url = 'http://api.tumblr.com/v2/blog/'.$domain.'/posts?api_key=pcCK9NCjhSoA0Yv9TGoXI0vH6YzLRiqKPul9iC6OQ6Pr69l2MV&offset='. ($a * 20) .'&limit=20';
					
					$json = gg_curl_get_contents($api_url);
					if($json === false ) {die( __('Error connecting to Tumblr', 'gg_ml').' ..');}
					
					$data = json_decode($json, true);
					if(isset($data['meta']['status']) && ($data['meta']['status'] == 401 || $data['meta']['status'] == 404)) {die( __('Connection Error - Check your blog URL', 'gg_ml') );}
					
					// retrieve images - loop to get also multi-image posts
					$author = (isset($data['response']['blog']['title'])) ? $data['response']['blog']['title'] : '';
					
					foreach($data['response']['posts'] as $post) {
						if(isset($post['photos'])) {
							// title
							$title = (isset($post['summary'])) ? $post['summary'] : '';
							
							// find main description
							if(isset($post['caption']) && !empty($post['caption'])) {
								$descr = $post['caption'];
							} else {
								$descr = (isset($post['excerpt'])) ? $post['excerpt'] : '';	
							}
	
							foreach ($post['photos'] as $img) {
								if(!empty($img['caption'])){ $descr = gg_clean_emoticons($img['caption']);}
								
								$images[] = array(
									'url' 	=> $img['original_size']['url'], 
									'author'=> $author,
									'title'	=> $title,
									'descr'	=> strip_tags(gg_clean_emoticons($descr), '<a>')
								);	
							}
						}
					}
				}
				
				return $images;
				break;
			
			
			
			// 500px images
			case '500px' :
				$username = gg_500px_username(get_post_meta($this->gid, 'gg_username', true));
				$api_url = 'https://api.500px.com/v1/photos?consumer_key=WfKdStAzpMvU5VLMXKJfboyCUJ0acvIgw60EeYTg&image_size=4&rpp=100&feature=user&username='.urlencode($username);
				
				$json = gg_curl_get_contents($api_url);
				if($json === false ) {die( __('Error connecting to 500px', 'gg_ml').' ..');}
				
				$data = json_decode($json, true);
				if(isset($data['status']) && $data['status'] == 400) {die( __('Connection Error - Check your User URL', 'gg_ml') );}
				
				// retrieve images
				$images = array();
				foreach ($data['photos'] as $image) {
					(isset($image['caption']['text'])) ? $descr = $image['caption']['text'] : $descr = '';
					$images[] = array(
						'url' 	=> $image['image_url'], 
						'author'=> $image['user']['fullname'],
						'title'	=> gg_clean_emoticons($image['name']),
						'descr'	=> gg_clean_emoticons($image['description'])
					);
				}
				
				return $images;
				break;
			
			
			
			// nextGEN gallery images
			case 'ngg' :
				global $wpdb;
				$table_name = $wpdb->prefix ."ngg_pictures";
				
				// get ngg gallery basepath
				$base = gg_get_ngg_galleries($this->extra); 
				if(!$base) {die( __('Gallery does not exist. Check in nextGen Gallery panel', 'gg_ml') );}
			
				// search part
				$search_q = (empty($this->search)) ? '' : "AND alttext LIKE '%". addslashes($this->search) ."%'";
				
				//get total
				$wpdb->query($wpdb->prepare("SELECT pid FROM ". $table_name ." WHERE galleryid = %d ".$search_q, $this->extra));
				$tot = $wpdb->num_rows;
				
				// get images
				$query = $wpdb->get_results("
					SELECT filename, description, alttext FROM ". $table_name ." 
					WHERE galleryid = '". (int)$this->extra ."' ".$search_q."
					ORDER BY pid DESC
					LIMIT ". (int)(($this->page - 1) * $this->per_page) .", ". (int)$this->per_page ."", 
					ARRAY_A);
				$images = array();
				
				if(is_array($query)) {
					foreach ($query as $img) {
						$images[] = array(
							'url' 	=> WP_CONTENT_URL .'/'. $base .'/'. $img['filename'], 
							'path' 	=> $base .'/'. $img['filename'],
							'author'=> '',
							'title'	=> (isset($img['alttext'])) ? $img['alttext'] : '', 
							'descr'	=> (isset($img['description'])) ? $img['description'] : ''
						);
					}
				}

				$this->tot_images = $tot;
				return $images;
				break;
			
			
			
			// rss feed images
			case 'rss' :
				if(!function_exists('simplexml_load_string')) {die( __("Your server doesn't support SimpleXML", 'gg_ml').'  ..');}
				
				$url = get_post_meta($this->gid, 'gg_username', true);
				$feed = gg_curl_get_contents($url, 'g_feed_api');
				if($feed === false ) {die( __('Error retrieving the feed', 'gg_ml').' ..');}
				
				// check to catch media:content easier
				if(strpos($feed, 'media:content') !== false) {
					$feed = str_replace('media:content', 'ggimage', $feed);	
				}
				
				$xml = simplexml_load_string($feed);
				$images = array();
				foreach ($xml->channel->item as $item) {
					if(isset($item->ggimage)) {
						$img_url = $item->ggimage->attributes()->url;
					} else {
						$img_url = gg_string_to_url($item->description);
					}
					
					// check url catched to avoid bad values
					if(!filter_var($img_url, FILTER_VALIDATE_URL)) {
						preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $img_url, $matched);	
						if(is_array($matched) && isset($matched[0]) && is_array($matched[0])) {$img_url = $matched[0][0];}
					}
					
					if(!empty($img_url)) {
						$images[] = array(
							'url' 	=> $img_url, 
							'author'=> '',
							'title'	=> gg_clean_emoticons($item->title), 
							'descr'	=> substr(gg_clean_emoticons(strip_tags($item->description)), 0, 300) // only first 300 chars
						);
					}
				}
				
				return $images;
				break;
		}
	}
	
}

