<?php

///////////////////////////////////
// ADMIN IMPLEMENTATION


function gg_wp_gall_metabox() {
	// add a meta box for affected post types
	include_once(GG_DIR . '/functions.php');
	foreach(gg_affected_wp_gall_ct() as $type){
		add_meta_box('gg_wp_gall_settings', __('Global Gallery', 'gg_ml'), 'gg_wp_gall_settings', $type, 'normal', 'default');
	}  
}
add_action('admin_init', 'gg_wp_gall_metabox');


// create metabox
function gg_wp_gall_settings() {
	include_once(GG_DIR . '/functions.php');
	global $post;
	
	$use_it = get_post_meta($post->ID, 'gg_affect_wp_gall', true);
	$layout = get_post_meta($post->ID, 'gg_layout', true);
	$thumb_w = get_post_meta($post->ID, 'gg_thumb_w', true);
	$thumb_h = get_post_meta($post->ID, 'gg_thumb_h', true);	
	$masonry_cols = get_post_meta($post->ID, 'gg_masonry_cols', true);
	$ps_height = get_post_meta($post->ID, 'gg_photostring_h', true);
	$paginate = get_post_meta($post->ID, 'gg_paginate', true);
	$per_page = get_post_meta($post->ID, 'gg_per_page', true);
	$pag_system = get_post_meta($post->ID, 'gg_pag_system', true);
	$use_slider = get_post_meta($post->ID, 'gg_use_slider', true);
	$watermark = get_post_meta($post->ID, 'gg_watermark', true);
	$custom_ol = get_post_meta($post->ID, 'gg_custom_overlay', true);
	
	// default values
	if(!$layout || $layout == 'default') {
		$thumb_w = get_option('gg_thumb_w');
		$thumb_h = get_option('gg_thumb_h');
		$masonry_cols = get_option('gg_masonry_cols');
		$ps_height = get_option('gg_photostring_h');
	}
	
	if(!$paginate || $paginate == 'default') {
		$per_page = get_option('gg_img_per_page');	
	}
	
	// switches
	$standard_show =	($layout != 'standard') ? 'style="display: none;"' : '';
	$masonry_show =		($layout != 'masonry') 	? 'style="display: none;"' : '';
	$ps_show = 			($layout != 'string') 	? 'style="display: none;"' : '';
	$per_page_show = 	($paginate != '1') 		? 'style="display: none;"' : '';
	$slider_show = 		($use_slider != '1') 	? 'style="display: none;"' : '';
	?>
    <div class="lcwp_mainbox_meta">
        <table class="widefat lcwp_table lcwp_metabox_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Use the plugin with wordpress galleries in this page?", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e('Select an option', 'gg_ml') ?> .." name="gg_affect_wp_gall" id="gg_affect_wp_gall" class="lcweb-chosen" autocomplete="off" tabindex="2">
                  <option value="default"><?php _e('As default', 'gg_ml') ?></option>
                  <option value="1" <?php if($use_it == '1') echo 'selected="selected"';?>><?php _e('Yes', 'gg_ml') ?></option>
                  <option value="0" <?php if($use_it == '0') echo 'selected="selected"';?>><?php _e('No', 'gg_ml') ?></option>
                </select>
            </td>    
          </tr>
			
          <tbody id="gg_wp_gall_opts" <?php if($use_it != '1') {echo 'style="display: none;"';} ?>>
            <tr><td class="lcwp_field_td" colspan="2">
          	<div>
				<label><?php _e('Gallery Layout', 'gg_ml') ?></label> <br/>
                <select data-placeholder="<?php _e('Select a layout', 'gg_ml') ?> .." name="gg_layout" id="gg_layout" class="lcweb-chosen" autocomplete="off" tabindex="2" style="width: 122px;">
                    <option value="default">Default</option>  
                    <option value="standard" <?php if($layout == 'standard') {echo 'selected="selected"';} ?>>Standard</option>  
                    <option value="masonry" <?php if($layout == 'masonry') {echo 'selected="selected"';} ?>>Masonry</option>
                    <option value="string" <?php if($layout == 'string') {echo 'selected="selected"';} ?>>PhotoString</option>  
                </select>
            </div>
            <div id="gg_tt_sizes" <?php echo $standard_show; ?>>
            	<label><?php _e('Thumbnail Sizes', 'gg_ml') ?></label> <br/>
                <input type="text" name="gg_thumb_w" value="<?php echo $thumb_w ?>" maxlength="4" style="width: 45px; margin-right: 3px; text-align: center;" /> x 
            	<input type="text" name="gg_thumb_h" value="<?php echo $thumb_h ?>" maxlength="4" style="width: 45px; margin-left: 3px; text-align: center;" /> px
            </div>     
            <div id="gg_masonry_cols" <?php echo $masonry_show; ?>>
            	<label><?php _e('Image Columns', 'gg_ml') ?></label> <br/>
                <select data-placeholder="<?php _e('Select an option', 'gg_ml') ?> .." name="gg_masonry_cols" class="lcweb-chosen" style="width: 122px; min-width: 0px;">
                	<?php
					for($a=1; $a<=20; $a++) {
						($a == (int)$masonry_cols) ? $sel = 'selected="selected"' : $sel = '';	
						echo '<option value="'.$a.'" '.$sel.'>'.$a.'</option>';
					}
					?>
                </select>
            </div>
            <div id="gg_ps_height" <?php echo $ps_show; ?>>
            	<label><?php _e('Thumbnails Height', 'gg_ml') ?></label> <br/>
                <input type="text" name="gg_photostring_h" value="<?php echo $ps_height ?>" maxlength="4" style="width: 45px; margin-right: 2px; text-align: center;" /> px
            </div>
            </td>
            </tr>
            <tr><td class="lcwp_field_td" colspan="2">
          	<div>
            	<label><?php _e('Use pagination?', 'gg_ml') ?></label> <br/>
                <select data-placeholder="<?php _e('Select an option', 'gg_ml') ?> .." name="gg_paginate" id="gg_paginate" class="lcweb-chosen" style="width: 122px;">
                    <option value="default"><?php _e('As Default', 'gg_ml') ?></option>  
                    <option value="1" <?php if($paginate == '1') {echo 'selected="selected"';} ?>><?php _e('Yes', 'gg_ml') ?></option>  
                    <option value="0" <?php if($paginate == '0') {echo 'selected="selected"';} ?>><?php _e('No', 'gg_ml') ?></option>  
                </select>
            </div>     
            <div id="gg_per_page" <?php echo $per_page_show; ?>>
            	<label><?php _e('Images per page', 'gg_ml') ?></label> <br/>
                <input type="text" name="gg_per_page" value="<?php echo $per_page ?>" maxlength="4" style="width: 45px; margin-right: 76px; text-align: center;" />
            </div>
            </td>
            </tr>
            <tr><td class="lcwp_field_td" colspan="2">
				<label><?php _e('Pagination System', 'gg_ml') ?></label> <br/>
                <select data-placeholder="<?php _e('Select an option', 'gg_ml') ?> .." name="gg_pag_system" id="gg_pag_system" class="lcweb-chosen" autocomplete="off" style="width: 122px;">
                    <option value=""><?php _e('auto - follow global settings', 'gg_ml') ?></option>
					<option value="standard" <?php if($pag_system == 'standard') {echo 'selected="selected"';} ?>><?php _e('standard', 'gg_ml') ?></option>
                    <option value="inf_scroll" <?php if($pag_system == 'inf_scroll') {echo 'selected="selected"';} ?>><?php _e('infinite scroll', 'gg_ml') ?></option>
                </select>
            </tr>
            <tr><td class="lcwp_field_td" colspan="2">
          	<div>
				<label><?php _e('Display as slider', 'gg_ml') ?></label> <br/>
                <select data-placeholder="<?php _e('Select an option', 'gg_ml') ?> .." name="gg_use_slider" id="gg_use_slider" class="lcweb-chosen" autocomplete="off" style="width: 122px;">
                    <option value="0"><?php _e('no') ?></option>  
                    <option value="1" <?php if($use_slider == 1) {echo 'selected="selected"';} ?>><?php _e('yes') ?></option>  
                </select>
            </div>
            <div class="gg_slider_sizes" <?php echo $slider_show; ?>>
            	<label><?php _e('Slider width', 'gg_ml') ?></label> <br/>
                <input type="text" name="gg_slider_w" value="<?php echo get_post_meta($post->ID, 'gg_slider_w', true) ?>" style="width: 50px; text-align: center;" maxlength="4" /> 
            	<select name="gg_slider_w_type" style="width: 50px; margin: -3px 0 0 -5px;" autocomplete="off">
                    <option value="%">%</option>
                    <option value="px" <?php if(get_post_meta($post->ID, 'gg_slider_w_type', true) == 'px') {echo 'selected="selected"';} ?>>px</option>
                </select>
            </div>     
            <div class="gg_slider_sizes" <?php echo $slider_show; ?>>
            	<label><?php _e('Slider height', 'gg_ml') ?></label> <br/>
                <input type="text" name="gg_slider_h" value="<?php echo get_post_meta($post->ID, 'gg_slider_h', true) ?>" style="width: 50px; text-align: center;" maxlength="4" /> 
            	<select name="gg_slider_h_type" style="width: 50px; margin: -3px 0 0 -5px;" autocomplete="off">
                    <option value="%">%</option>
                    <option value="px" <?php if(get_post_meta($post->ID, 'gg_slider_h_type', true) == 'px') {echo 'selected="selected"';} ?>>px</option>
                </select>
            </div>
            </td>
            </tr>
            
            <?php 
			///// OVERLAY MANAGER ADD-ON ///////////
			////////////////////////////////////////
			if(defined('GGOM_DIR')) : ?>
            <tr>
      		<tr><td class="lcwp_field_td" colspan="2">
              <div>
              	<label><?php _e('Custom Overlay', 'gg_ml') ?></label> <br/>
            	<select data-placeholder="<?php _e('Select an overlay', 'gg_ml') ?> .." name="gg_custom_overlay" class="lcweb-chosen" style="width: 122px; min-width: 0px;">
					<option value="">(<?php _e('default one', 'gg_ml') ?>)</option>
					
					<?php
					$overlays = get_terms('ggom_overlays', 'hide_empty=0');
					foreach($overlays as $ol) {
						  $sel = ($ol->term_id == $custom_ol) ? 'selected="selected"' : '';
						  echo '<option value="'.$ol->term_id.'" '.$sel.'>'.$ol->name.'</option>'; 
					}
					?>
              	</select>
              </div>
            </td>
            </tr> 
            <?php endif; ?>
            
            <tr>
              <td colspan="2">
			  	<?php _e("Use watermark?", 'gg_ml'); ?> 
                <?php ($watermark == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="gg_watermark" style="margin-left: 17px;" <?php echo $sel; ?> />
              </td>   
            </tr>
          </tbody>
        </table>  
        
        <input type="hidden" name="lcwp_nonce" value="<?php echo wp_create_nonce('lcwp') ?>" />
    </div>
    
    <?php // SCRIPTS ?>
    <script src="<?php echo GG_URL; ?>/js/functions.js" type="text/javascript"></script>
    <script src="<?php echo GG_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo GG_URL; ?>/js/lc-switch/lc_switch.min.js" type="text/javascript"></script>
    
    <script type="text/javascript">
	jQuery(document).ready(function($) {
		// settings toggle
		jQuery(document).delegate('#gg_affect_wp_gall', 'change', function() {
			var use_it = jQuery(this).val();
			
			if(use_it == '1') { jQuery('#gg_wp_gall_opts').fadeIn(); }
			else { jQuery('#gg_wp_gall_opts').fadeOut(); }		
		});
		
		jQuery(document).delegate('#gg_layout', 'change', function() {
			var layout = jQuery(this).val();
			
			if(layout == 'standard') {
				jQuery('#gg_tt_sizes').fadeIn();
				jQuery('#gg_masonry_cols, #gg_ps_height').hide();
			}
			else if (layout == 'masonry') {
				jQuery('#gg_masonry_cols').fadeIn();
				jQuery('#gg_tt_sizes, #gg_ps_height').hide();
			}
			else if (layout == 'string') {
				jQuery('#gg_ps_height').fadeIn();
				jQuery('#gg_tt_sizes, #gg_masonry_cols').hide();
			}
			else { jQuery('#gg_tt_sizes, #gg_masonry_cols, #gg_ps_height').fadeOut(); }
		});
		
		jQuery(document).delegate('#gg_paginate', 'change', function() {
			var paginate = jQuery(this).val();
			
			if(paginate == '1') { jQuery('#gg_per_page').fadeIn(); }
			else { jQuery('#gg_per_page').fadeOut(); }		
		});
		
		jQuery(document).delegate('#gg_use_slider', 'change', function() {
			var use_slider = jQuery(this).val();
			
			if(use_slider == '1') { jQuery('.gg_slider_sizes').fadeIn(); }
			else { jQuery('.gg_slider_sizes').fadeOut(); }		
		});
	});
	</script>
    <?php
}


// save metabox
function gg_wp_gall_meta_save($post_id) {
	if(isset($_POST['gg_affect_wp_gall'])) {
		// authentication checks
		if (!wp_verify_nonce($_POST['lcwp_nonce'], 'lcwp')) return $post_id;

		// check user permissions
		if ($_POST['post_type'] == 'page') {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		
		include_once(GG_DIR.'/functions.php');
		include_once(GG_DIR.'/classes/simple_form_validator.php');
				
		$validator = new simple_fv;
		$indexes = array();
		
		$indexes[] = array('index'=>'gg_affect_wp_gall', 'label'=>'Affect WP galleries');
		
		$indexes[] = array('index'=>'gg_layout', 'label'=>'gallery Layout');
		$indexes[] = array('index'=>'gg_thumb_w', 'label'=>'Thumbs Width');
		$indexes[] = array('index'=>'gg_thumb_h', 'label'=>'Thumbs Height');
		$indexes[] = array('index'=>'gg_masonry_cols', 'label'=>'Masonry Columns');
		$indexes[] = array('index'=>'gg_photostring_h', 'label'=>'PhotoString Height');

		$indexes[] = array('index'=>'gg_paginate', 'label'=>'gallery pagination');
		$indexes[] = array('index'=>'gg_per_page', 'label'=>'images per page');
		$indexes[] = array('index'=>'gg_pag_system', 'label'=>'pagination system');
		
		$indexes[] = array('index'=>'gg_use_slider', 'label'=>'display as slider');
		$indexes[] = array('index'=>'gg_slider_w', 'label'=>'slider width');
		$indexes[] = array('index'=>'gg_slider_w_type', 'label'=>'slider width type');
		$indexes[] = array('index'=>'gg_slider_h', 'label'=>'slider height');
		$indexes[] = array('index'=>'gg_slider_h_type', 'label'=>'slider height type');
		
		$indexes[] = array('index'=>'gg_watermark', 'label'=>'use watermark');
		$indexes[] = array('index'=>'gg_custom_overlay', 'label'=>'custom overlay');
		
		$validator->formHandle($indexes);
		$fdata = $validator->form_val;
		$error = $validator->getErrors();

		// clean data
		foreach($fdata as $key=>$val) {
			if(!is_array($val)) {
				$fdata[$key] = stripslashes($val);
			}
			else {
				$fdata[$key] = array();
				foreach($val as $arr_val) {$fdata[$key][] = stripslashes($arr_val);}
			}
		}

		// save data
		foreach($fdata as $key=>$val) {
			update_post_meta($post_id, $key, $fdata[$key]); 
		}
	}

    return $post_id;
}
add_action('save_post','gg_wp_gall_meta_save');


/**************************************************************/


///////////////////////////////////
// FRONTEND IMPLEMENTATION

function gg_wp_gallery_manag_frontend($foo, $atts) {
	include_once(GG_DIR . '/functions.php');
	global $post;
	
	extract( shortcode_atts( array(
		'ids' => '',
		'orderby' => ''
	), $atts ) );
	
	$raw_use_it = get_post_meta($post->ID, 'gg_affect_wp_gall', true);
	$use_it = gg_check_default_val($post->ID, 'gg_affect_wp_gall', $raw_use_it);
	$random = ($orderby == 'rand') ? '1' : 0; 
	$wm = (get_post_meta($post->ID, 'gg_watermark', true)) ? '1' : '0';

	if($use_it && !empty($ids)) {
		gg_wp_gall_images($post->ID, $ids); // get and cache
		
		if(!get_post_meta($post->ID, 'gg_use_slider', true)) {
			$pag_system = get_post_meta($post->ID, 'gg_pag_system', true);
			$overlay = (defined('GGOM_DIR')) ? get_post_meta($post->ID, 'gg_custom_overlay', true) : '';
			
			$code = do_shortcode('[g-gallery gid="'.$post->ID.'" random="'.$random.'" watermark="'.$wm.'" pagination="'.$pag_system.'" overlay="'.$overlay.'" wp_gall_hash="'.'-'.md5($ids).'"]');
		}
		else {
			$w = (int)get_post_meta($post->ID, 'gg_slider_w', true) . get_post_meta($post->ID, 'gg_slider_w_type', true);
			$h = (int)get_post_meta($post->ID, 'gg_slider_h', true) . get_post_meta($post->ID, 'gg_slider_h_type', true);
			
			$code = do_shortcode('[g-slider gid="'.$post->ID.'" width="'.$w.'" height="'.$h.'" random="'.$random.'" watermark="'.$wm.'" wp_gall_hash="'.'-'.md5($ids).'"]');
		}
		return $code;
	}
	else {return '';}
}
add_filter('post_gallery', 'gg_wp_gallery_manag_frontend', 999, 2);

