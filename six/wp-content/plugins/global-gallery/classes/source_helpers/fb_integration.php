<?php
/**
 * Interactions with Facebook API v5 - PHP SDK
 * NOTE: Designed to be used with PHP v5.4 and up
 * 
 * @author Luca Montanari
 */
 
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
  throw new Exception('Facebook SDK requires PHP version 5.4 or higher');
} 
 
 
class gg_facebook_integration {
	private $client; // client object
	private $app_token; // default app token
	
	private $app_id = '328245323937836';
	private $app_secret = 'fc667c61baec6c55e2354a21006aef94';
	private $redirect_uri = 'http://www.lcweb.it';
	private $scope;	

	public $connect_data = ''; // array containing connection data
	
	
	/* get data from connection ID - or set it manually */
	public function __construct($connect_id, $connect_data = array()) {
		include_once(GG_DIR .'/classes/facebook_sdk-v5.1.2/Facebook/autoload.php');
		
		$this->client = new Facebook\Facebook([
			'app_id'     => $this->app_id,
			'app_secret' => $this->app_secret,
			'default_graph_version' => 'v2.6',
		]);
		
		$this->app_token = $this->client->getApp()->getAccessToken();


		if(empty($connect_id)) {
			$this->connect_data = $connect_data;	
		} 
		else {
			include_once(GG_DIR .'/functions.php');
			$this->connect_data = gg_get_conn_hub_data(false, $connect_id);
		}
		
		return true;	
	}
	
	
	
	/* first check - let user accept the app and get token */
	public function accept_app() {
		$permissions = array(); //['email', 'user_posts']; // optional
		$callback = $this->redirect_uri;
		$helper = new Facebook\FacebookRedirectLoginHelper($callback);
		$loginUrl = $helper->getLoginUrl($permissions);
		
		return $helper->getLoginUrl($permissions);
	}

	
	///////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	/* GET FB PAGE ID */
	public function page_url_to_id($url) {
		if(strpos($url, 'facebook.com/') === false) {
			return false;	
		}
		
		// manage URL to get last part
		$pos = strpos($url, '?'); 
		if(strpos($url, '?')) {$url = substr($url, 0, $pos);}
		$url_arr = explode('/', untrailingslashit($url));
		
		
		
		// old FB structure reporting a 
		if(strpos($url, 'pages/')) { return end($url_arr); }
		
		
		else {
			$page_username = end($url_arr);
			
			try {
				$page_data = $this->client->get('/'.$page_username, $this->app_token);
			} catch(Exception $e) {
				return false;
			}
			
			$graphObject = $page_data->getGraphObject();
			return $graphObject->getProperty('id');
		}
	}
	


	/* GET ALBUMS */
	public function get_albums() {
		if(isset($this->connect_data['fb_src_switch']) && $this->connect_data['fb_src_switch'] == 'page') {
			$instruction = '/'. $this->connect_data['fb_page_id'] .'/albums';
		}
		else {return false;}

		try {
			$response = $this->client->get($instruction, $this->app_token);
			$graphArray = $response->getGraphEdge()->asArray();	
			
			
			if(!is_array($graphArray)) {return false;}
			$albums = array();
			
			foreach($graphArray as $album) {
				$albums[] = array(
					'id'	=> $album['id'],
					'name' 	=> $album['name']
				);	
			}
			
			return $albums;
		} 
		catch(Exception $e) {
			return false;
		}
	}
	
	
	
	/* GET ALBUM IMAGES COUNT */
	public function album_images_count($album_id) {
		try {
			$response = $this->client->get('/'.$album_id.'?fields=count', $this->app_token);
			$data = $response->getGraphObject()->asArray();
			return (int)$data['count'];
		} 
		catch(Exception $e) {
			return false;
		}	
	}
	
	
	
	/* GET ALBUM IMAGES */
	public function album_images($album_id, $limit = 15, $offset = 0) {
		if($this->connect_data['fb_src_switch'] == 'page') {
			$instruction = '/'.$album_id.'/photos?fields=name,images,from&limit='.$limit.'&offset='.$offset;
		}
		else {return false;}
		
		try {
			$response = $this->client->get($instruction, $this->app_token);
			return $response->getGraphEdge()->asArray();
		} 
		catch(Exception $e) {
			return false;
		}

		
		
		$data = array();
		
		// cycle 10 times to get 1000 images max
		for($a=0; $a<10; $a++) {
			$offset = 100 * $a;
			$request = new FacebookRequest(
				$this->token,
				'GET',
				'/'.$album_id.'/photos?fields=name,images&limit=100&offset='.$offset // limit to 1000 - could be reduced by FB
			);
			$response = $request->execute();
			$graphObject = $response->getGraphObject()->asArray();	

			if(!isset($graphObject['data']) || !is_array($graphObject['data']) || count($graphObject['data']) == 0) {break;}
			else {$data = array_merge($data, $graphObject['data']);}
		}

		$images = array();
		foreach($data as $img) {
			$img_arr = $img->images;
			$name = (isset($img->name)) ? $img->name : '';
			
			$images[] = array(
				'caption' => $name,
				'url' => $img->images[0]->source
			);
		}
		
		return $images;
		
	}
}




/*

// include required files form Facebook SDK
$basepath = GG_DIR .'/classes/facebook_sdk-v4-5.1.2/Facebook'; 
include_once($basepath. '/autoload.php' );
include_once($basepath. '/HttpClients/FacebookCurl.php' );
include_once($basepath. '/HttpClients/FacebookCurlHttpClient.php' );
include_once($basepath. '/Entities/AccessToken.php' );

include_once($basepath. '/FacebookSession.php' );
include_once($basepath. '/FacebookRedirectLoginHelper.php' );
include_once($basepath. '/FacebookRequest.php' );
include_once($basepath. '/FacebookResponse.php' );
include_once($basepath. '/FacebookSDKException.php' );
include_once($basepath. '/FacebookRequestException.php' );
include_once($basepath. '/FacebookOtherException.php' );
include_once($basepath. '/FacebookAuthorizationException.php' );
include_once($basepath. '/GraphObject.php' );
include_once($basepath. '/GraphSessionInfo.php' );

use Facebook\FacebookHttpable;
use Facebook\FacebookCurl;
use Facebook\FacebookCurlHttpClient;
use Facebook\AccessToken;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;


class gg_fb_utilities {
	public $token;
	
	// setup token
	function __construct() {
		FacebookSession::setDefaultApplication('328245323937836', 'fc667c61baec6c55e2354a21006aef94');
		$this->token = FacebookSession::newAppSession();	
	}
	
	
	// get page ID from username
	function page_url_to_id($url) {
		$pos = strpos($url, '?'); 
		if(strpos($url, '?')) {$url = substr($url, 0, $pos);}
		$url_arr = explode('/', untrailingslashit($url));
		
		if(strpos($url, 'pages/')) { return end($url_arr); }
		else {
			$page_username = end($url_arr);

			$request = new FacebookRequest(
				$this->token,
				'GET',
				'/'.$page_username
			);
			$response = $request->execute();
			$graphObject = $response->getGraphObject();
			return $graphObject->getProperty('id');
		}
	}
	
	
	// get page albums
	function page_albums($page_url) {
		$page_id = $this->page_url_to_id($page_url);
		if(!$page_id || !is_numeric($page_id)) {die( __('Connection Error - check the Facebook page URL', 'gg_ml') );}
		
		$request = new FacebookRequest(
			$this->token,
			'GET',
			'/'.$page_id.'/albums?fields=id,name&limit=100' // oct 2014 - limit results to avoid FB limits
		);
		$response = $request->execute();
		$graphObject = $response->getGraphObject()->asArray();	
		
		$data = $graphObject['data'];
		$albums = array();
		if(!is_array($data)) {return $albums;}
		
		foreach($data as $album) {
			$albums[] = array(
				'aid'	=> $album->id,
				'name' 	=> $album->name
			);	
		}
		return $albums;
	}
	
	
	public function album_images($album_id) {
		$data = array();
		
		// cycle 10 times to get 1000 images max
		for($a=0; $a<10; $a++) {
			$offset = 100 * $a;
			$request = new FacebookRequest(
				$this->token,
				'GET',
				'/'.$album_id.'/photos?fields=name,images&limit=100&offset='.$offset // limit to 1000 - could be reduced by FB
			);
			$response = $request->execute();
			$graphObject = $response->getGraphObject()->asArray();	

			if(!isset($graphObject['data']) || !is_array($graphObject['data']) || count($graphObject['data']) == 0) {break;}
			else {$data = array_merge($data, $graphObject['data']);}
		}

		$images = array();
		foreach($data as $img) {
			$img_arr = $img->images;
			$name = (isset($img->name)) ? $img->name : '';
			
			$images[] = array(
				'caption' => $name,
				'url' => $img->images[0]->source
			);
		}
		
		return $images;
	}
}
*/

