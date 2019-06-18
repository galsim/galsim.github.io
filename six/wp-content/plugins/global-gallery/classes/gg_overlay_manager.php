<?php
// create and manage items overlay - with overlay manager add-on integration
class gg_overlay_manager {
	private $preview_mode = false;
	private $title_under = false;
	private $overlay;
	
	// image overlay 
	public $ol_txt_part = '<div class="gg_main_overlay"><span class="gg_img_title">%GG-TITLE-OL%</span></div>';
	public $ol_code = '';
	
	// image effect attribute
	public $img_fx_attr = '';
	
	// txt visibility trick - classes
	public $txt_vis_class = false;
	
	// standard overlay - wrapper classes
	public $ol_wrap_class = '';
	
	
	// handle grid global vars
	function __construct($ol_to_use, $title_under, $subject = 'gall', $preview_mode = false) {
		
		$this->preview_mode = $preview_mode;
		$this->title_under = ($title_under == 1) ? true : false;

		// set default secondary overlay code
		$this->ol_code = '<div class="gg_sec_overlay gg_'. get_option('gg_sec_overlay', 'tl') .'_pos"><span></span></div>';	

		// get the add-on code
		if(!defined('GGOM_DIR') || $ol_to_use == 'default' || !filter_var($ol_to_use, FILTER_VALIDATE_INT)) {
			if(defined('GGOM_DIR')) {
				$global_ol = get_option('gg_'.$subject.'_default_overlay');
				$overlay = (empty($global_ol)) ? 'default' : (int)$global_ol;
			}
			else {$overlay = 'default';}
		} 
		else {
			$overlay = (!defined('GGOM_DIR')) ? 'default' : (int)$ol_to_use;	
		}
		$this->overlay = $overlay;
		
		if($overlay != 'default') {
			$this->txt_under_code = '<div class="mg_title_under">%GG-TITLE-OL%</div>';
			$this->get_om_code($overlay);
		}
		else {
			// setup default overlay's secondary overlay and effect
			$this->ol_code = $this->standard_gg_ol($title_under);
			$this->img_fx_attr = 'ggom_fx="'. get_option('gg_thumb_fx', '') .'" ggom_timing="300"';
		}
	}
	
	
	// setup standard galleries overlay
	private function standard_gg_ol($title_under) {
		$type = get_option('gg_overlay_type', 'both');
		$ol = (!$type || $type == 'primary') ? '' : '<div class="gg_sec_overlay gg_'. get_option('gg_sec_overlay', 'tl') .'_pos"><span></span></div>';
		
		// set on-image textual part
		if(!$type || $title_under) {$this->ol_txt_part = '';}
		
		// overlays wrap classes
		$this->ol_wrap_class = (!get_option('gg_overlay_type', 'both')) ? 'gg_hidden_ol' : 'gg_'. get_option('gg_overlay_type', 'both') .'_ol';  
		$this->ol_wrap_class .= ' gg_ol_'. get_option('gg_main_overlay', 'full') .'_mode gg_main_ol_'.get_option('gg_main_ol_behav', 'show_on_h');
		
		// set 
		return $ol;
	}
	
	
	// get the add-on overlay code
	private function get_om_code($overlay_id) {
			
		if(function_exists('ggom_ol_frontend_code')) {
			$code = ggom_ol_frontend_code($overlay_id, $this->title_under);	

			$this->ol_code = $code['graphic'];
			$this->img_fx_attr = $code['img_fx_elem'];
			$this->txt_vis_class = $code['txt_vis_class'];
			
			if($this->title_under) {
				$this->txt_under_code = $code['txt'];
			} else {
				$this->ol_txt_part = $code['txt'];	
			}
		} 
	}
	
	
	// get the image overlay code
	public function get_img_ol($title, $descr, $img_url) {
		$img_data = array('title' => $title, 'descr' => $descr, 'img_url' => $img_url);
		
		// if not txt under - execute the text code	
		$txt_part = (!$this->title_under) ? $this->man_txt_part($img_data, $this->ol_txt_part) : '';
		return $this->ol_code . $txt_part;
	}
	
	
	// get the image overlay code
	public function get_txt_under($img_data) {
		return '<div class="gg_title_under">'. $this->man_txt_part($img_data, $this->txt_under_code) .'</div>';
	}
	
	
	// manage textual part of the overlay (both for normal and text under
	//// $raw_txt = overlay text with placeholders
	private function man_txt_part($img_data, $raw_txt) {
		$txt = apply_filters('ggom_txt_management', $raw_txt, $img_data, $this->preview_mode);	
		
		//if add-on is not installed - insert title for basic overlay
		if(strpos($txt, '%GG-TITLE-OL%') !== false) {
			$txt = str_replace('%GG-TITLE-OL%', $img_data['title'], $txt);	
		}
		
		return $txt;
	}
	
}
