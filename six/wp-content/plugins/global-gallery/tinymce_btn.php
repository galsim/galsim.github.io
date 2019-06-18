<?php
// implement tinymce button

add_action('media_buttons', 'gg_editor_btn', 20);
add_action('admin_footer', 'gg_editor_btn_content');


//action to add a custom button to the content editor
function gg_editor_btn() {
	$img = GG_URL . '/img/gg_logo_small.png';
  
	//append the icon
	echo '
	<a class="thickbox" id="gg_editor_btn" title="Global Gallery" style="cursor: pointer;" >
	  <img src="'.$img.'" />
	</a>';
}


function gg_editor_btn_content() {
	if(strpos($_SERVER['REQUEST_URI'], 'post.php') || strpos($_SERVER['REQUEST_URI'], 'post-new.php')) :
?>

    <div id="gg_popup_container" style="display:none;">
      <?php 
	  // get galleries
	  $args = array(
		  'post_type' => 'gg_galleries',
		  'numberposts' => -1,
		  'post_status' => 'publish'
	  );
	  $galleries = get_posts( $args );
	  
	  // get collections
	  $collections = get_terms('gg_collections', 'hide_empty=0');
	 
	 
	  // OVERLAY MANAGER ADD-ON - variable containing dropdown
	  if(defined('GGOM_DIR')) {
		$ggom_block = '
		<tr>
		  <td>'. __('Custom Overlay', 'mg_ml') .'</td>
		  <td colspan="2">
			  <select data-placeholder="'. __('Select an overlay', 'mg_ml') .'.." name="gg_custom_overlay" class="lcweb-chosen gg_custom_overlay" style="width: 370px;" autocomplete="off">
				  <option value="">('. __('default one', 'mg_ml') .')</option>';
		
			 $overlays = get_terms('ggom_overlays', 'hide_empty=0');
			 foreach($overlays as $ol) {
				$ggom_block .= '<option value="'.$ol->term_id.'">'.$ol->name.'</option>'; 
			 }
		
		$ggom_block .= '</select></td></tr>';  
	  }
	  else {$ggom_block = '';}
	  ////////////////////////////////////////////////////////////
	  
	  
	  if(!is_array($galleries)) {echo '<span>' . __('No galleries found', 'gg_ml') . ' ..</span>';}
	  else {
	  ?>
      <div id="gg_sc_tabs">
      	<ul class="tabNavigation" id="gg_sc_tabs_wrap">
            <li><a href="#gg_sc_gall"><?php _e('Galleries', 'gg_ml') ?></a></li>
            <li><a href="#gg_sc_coll"><?php _e('Collections', 'gg_ml') ?></a></li>
            <li><a href="#gg_sc_slider"><?php _e('Slider', 'gg_ml') ?></a></li>
            <li><a href="#gg_sc_carousel"><?php _e('Carousel', 'gg_ml') ?></a></li>
        </ul>    
      
      	<div id="gg_sc_gall">
            <table class="lcwp_form lcwp_table lcwp_tinymce_table" cellspacing="0">
              <tr>
                <td style="width: 35%;"><?php _e('Gallery','gg_ml') ?></td>
                <td colspan="2">
                  <select id="gg_gallery_choose" data-placeholder="<?php _e('Select a gallery', 'gg_ml') ?> .." name="gg_gallery" class="lcweb-chosen" style="width: 370px;">
                    <?php 
                    foreach ( $galleries as $gallery ) {
                        echo '<option value="'.$gallery->ID.'">'.$gallery->post_title.'</option>';
                    }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><?php _e('Random Display?', 'gg_ml') ?></td>
                <td style="width: 30%;" class="lcwp_form">
                    <input type="checkbox" name="gg_random" value="1" class="gg_popup_ip" id="gg_random" />
                </td>
                <td><span class="info"><?php _e('Display images randomly', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td><?php _e('Use Watermark?', 'gg_ml') ?></td>
                <td style="width: 30%;" class="lcwp_form">
                    <input type="checkbox" name="gg_watermark" value="1" class="gg_popup_ip" id="gg_watermark" />
                </td>
                <td><span class="info"><?php _e('Apply watermark to images (if available)', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td><?php _e('Pagination System', 'gg_ml') ?></td>
                <td class="lcwp_form" colspan="2">
                  <select id="gg_gall_pagination" data-placeholder="<?php _e('Select an option', 'gg_ml') ?> .." name="gg_gall_pagination" class="lcweb-chosen" style="width: 370px;">
                    <option value=""><?php _e('Auto - follow global settings', 'gg_ml') ?></option>
					<option value="standard"><?php _e('Standard', 'gg_ml') ?></option>
                    <option value="inf_scroll"><?php _e('Infinite scroll', 'gg_ml') ?></option>
                  </select>
                </td>
              </tr>    
              <?php echo $ggom_block; ?>
              <tr class="tbl_last">
                <td colspan="2">
                    <input type="button" value="Insert Gallery" name="gg_insert_gallery" id="gg_insert_gallery" class="button-primary" />
                </td>    
              </tr>
            </table>   
        </div>    

        <div id="gg_sc_coll">
            <table class="lcwp_form lcwp_table lcwp_tinymce_table" cellspacing="0">
              <tr>
                <td style="width: 35%;"><?php _e('Collection', 'gg_ml') ?></td>
                <td colspan="2">
                    <select id="gg_collection_choose" data-placeholder="<?php _e('Select a collection', 'gg_ml') ?> .." name="gg_collection" class="lcweb-chosen" tabindex="2" style="width: 370px;">
                    <?php 
                    foreach ( $collections as $collection ) {
                        echo '<option value="'.$collection->term_id.'">'.$collection->name.'</option>';
                    }
                    ?>
                  </select>
                </td>
              </tr>
              
              <tr>
                <td><?php _e('Allow Filters?', 'gg_ml') ?></td>
                <td style="width: 30%;" class="lcwp_form">
                    <input type="checkbox" name="gg_coll_filter" value="1" class="gg_popup_ip" id="gg_coll_filter" />
                </td>
                <td><span class="info"><?php _e('Allow galleries filtering by category', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td><?php _e('Random Display', 'gg_ml') ?></td>
                <td style="width: 30%;" class="lcwp_form">
                    <input type="checkbox" name="gg_coll_random" value="1" class="gg_popup_ip" id="gg_coll_random" />
                </td>
                <td><span class="info"><?php _e('Display galleries randomly', 'gg_ml') ?></span></td>
              </tr> 
              <?php echo $ggom_block; ?>  
              <tr class="tbl_last">
                <td colspan="2">
                    <input type="button" value="Insert Collection" name="gg_insert_collection" id="gg_insert_collection" class="button-primary" />
                </td>    
              </tr>
            </table> 
      	</div>
        
        <div id="gg_sc_slider">
        	<table class="lcwp_form lcwp_table lcwp_tinymce_table" cellspacing="0">
              <tr>
                <td style="width: 35%;"><?php _e('Images source', 'gg_ml') ?></td>
                <td colspan="2">
                    <select id="gg_slider_gallery" data-placeholder="<?php _e('Select a gallery', 'gg_ml') ?> .." name="gg_slider_gallery" class="lcweb-chosen" tabindex="2" style="width: 370px;">
                    <?php 
                    foreach ( $galleries as $gallery ) {
                        echo '<option value="'.$gallery->ID.'">'.$gallery->post_title.'</option>';
                    }
                    ?>
                  </select>
                </td>
              </tr> 
              <tr>
                <td><?php _e('Slider Width', 'gg_ml') ?></td>
                <td colspan="2" class="lcwp_form">
                    <input type="text" name="gg_slider_w" value="" id="gg_slider_w" style="width: 50px; text-align: center;" maxlength="4" />
                    <select name="gg_slider_w_type"  id="gg_slider_w_type" style="width: 50px; margin: -3px 0 0 -5px;">
                    	<option value="%">%</option>
                        <option value="px">px</option>
                    </select>
                </td>
              </tr>
              <tr>
                <td><?php _e('Slider Height', 'gg_ml') ?></td>
                <td class="lcwp_form">
                    <input type="text" name="gg_slider_h" value="" id="gg_slider_h" style="width: 50px; text-align: center;" maxlength="4" />
                    <select name="gg_slider_h_type"  id="gg_slider_h_type" style="width: 50px; margin: -3px 0 0 -5px;">
                    	<option value="%">%</option>
                        <option value="px">px</option>
                    </select>
                </td>
                <td id="gg_slider_h_type_note"><span class="info"><?php _e('Proportionally to the width', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td><?php _e('Random Display', 'gg_ml') ?></td>
                <td style="width: 30%;" class="lcwp_form">
                    <input type="checkbox" name="gg_slider_random" value="1" class="gg_popup_ip" id="gg_slider_random" />
                </td>
                <td><span class="info"><?php _e('Display images randomly', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td><?php _e('Use Watermark', 'gg_ml') ?></td>
                <td style="width: 30%;" class="lcwp_form">
                    <input type="checkbox" name="gg_slider_watermark" value="1" class="gg_popup_ip" id="gg_slider_watermark" />
                </td>
                <td><span class="info"><?php _e('Apply watermark to images (if available)', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td style="width: 35%;"><?php _e('Autoplay slider?', 'gg_ml') ?></td>
                <td>
                  <select id="gg_slider_autop" data-placeholder="<?php _e('Select an option', 'gg_ml') ?> .." name="gg_slider_autop" class="lcweb-chosen" autocomplete="off" style="width: 125px;">
                      <option value="auto">(<?php _e('as default', 'gg_ml') ?>)</option>
                      <option value="1"><?php _e('Yes', 'gg_ml') ?></option>
                      <option value="0"><?php _e('No', 'gg_ml') ?></option>
                  </select>
                </td>
                <td><span class="info"><?php _e('Overrides global autoplay setting', 'gg_ml') ?></span></td>
              </tr>   
              <tr class="tbl_last">
                <td colspan="2">
                    <input type="button" value="<?php _e('Insert Slider', 'gg_ml') ?>" name="gg_insert_slider" id="gg_insert_slider" class="button-primary" />
                </td>    
              </tr>
            </table>   
        </div> 
        
        <div id="gg_sc_carousel">
        	<table class="lcwp_form lcwp_table lcwp_tinymce_table" cellspacing="0">
              <tr>
                <td style="width: 35%;"><?php _e('Images source', 'gg_ml') ?></td>
                <td colspan="2">
                    <select id="gg_car_gallery" data-placeholder="<?php _e('Select a gallery', 'gg_ml') ?> .." name="gg_car_gallery" class="lcweb-chosen" tabindex="2" style="width: 370px;">
                    <?php 
                    foreach ( $galleries as $gallery ) {
                        echo '<option value="'.$gallery->ID.'">'.$gallery->post_title.'</option>';
                    }
                    ?>
                  </select>
                </td>
              </tr> 
              <tr>
                <td><?php _e('Images Height', 'gg_ml') ?></td>
                <td class="lcwp_form">
                    <input type="text" name="gg_car_h" value="200" id="gg_car_h" style="width: 50px; text-align: center;" maxlength="4" /> px
                </td>
                <td><span class="info"></span></td>
              </tr>
              <tr>
                <td><?php _e('Images Per Time', 'gg_ml') ?></td>
                <td class="lcwp_form">
                    <select id="gg_car_per_time" data-placeholder="<?php _e('Select an option', 'gg_ml') ?> .." name="gg_car_per_time" class="lcweb-chosen" autocomplete="off" style="width: 60px;">
                      <?php
                      for($a=1; $a<=10; $a++) {
						$sel = ($a == 3) ? 'selected="selected"' : '';  
						echo '<option value="'.$a.'" '.$sel.'>'.$a.'</option>';  
					  }
					  ?>
                  </select>
                </td>
                <td><span class="info"><?php _e('Choose how many images to show per time', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td><?php _e('Rows', 'gg_ml') ?></td>
                <td class="lcwp_form">
                    <select id="gg_car_rows" data-placeholder="<?php _e('Select an option', 'gg_ml') ?> .." name="gg_car_rows" class="lcweb-chosen" autocomplete="off" style="width: 60px;">
                      <?php
                      for($a=1; $a<5; $a++) {
						echo '<option value="'.$a.'">'.$a.'</option>';  
					  }
					  ?>
                  </select>
                </td>
                <td><span class="info"><?php _e('Choose how many image rows to use', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td><?php _e('Multiple Scroll?', 'gg_ml') ?></td>
                <td style="width: 30%;" class="lcwp_form">
                    <input type="checkbox" name="gg_car_multiscroll" value="1" class="gg_popup_ip" id="gg_car_multiscroll" />
                </td>
                <td><span class="info"><?php _e('Slides multiple images per time', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td><?php _e('Center mode?', 'gg_ml') ?></td>
                <td style="width: 30%;" class="lcwp_form">
                    <input type="checkbox" name="gg_car_center_mode" value="1" class="gg_popup_ip" id="gg_car_center_mode" />
                </td>
                <td><span class="info"><?php _e('Enables center display mode', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td><?php _e('Random Display', 'gg_ml') ?></td>
                <td style="width: 30%;" class="lcwp_form">
                    <input type="checkbox" name="gg_car_random" value="1" class="gg_popup_ip" id="gg_car_random" />
                </td>
                <td><span class="info"><?php _e('Display images randomly', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td><?php _e('Use Watermark', 'gg_ml') ?></td>
                <td style="width: 30%;" class="lcwp_form">
                    <input type="checkbox" name="gg_car_watermark" value="1" class="gg_popup_ip" id="gg_car_watermark" />
                </td>
                <td><span class="info"><?php _e('Apply watermark to images (if available)', 'gg_ml') ?></span></td>
              </tr>
              <tr>
                <td style="width: 35%;"><?php _e('Autoplay slideshow?', 'gg_ml') ?></td>
                <td>
                  <select id="gg_car_autop" data-placeholder="<?php _e('Select an option', 'gg_ml') ?> .." name="gg_car_autop" class="lcweb-chosen" autocomplete="off" style="width: 115px;">
                      <option value="auto">(<?php _e('as default', 'gg_ml') ?>)</option>
                      <option value="1"><?php _e('Yes', 'gg_ml') ?></option>
                      <option value="0"><?php _e('No', 'gg_ml') ?></option>
                  </select>
                </td>
                <td><span class="info"><?php _e('Overrides global autoplay setting', 'gg_ml') ?></span></td>
              </tr> 
              <?php echo $ggom_block; ?>  
              <tr class="tbl_last">
                <td colspan="2">
                    <input type="button" value="<?php _e('Insert Carousel', 'gg_ml') ?>" name="gg_insert_carousel" id="gg_insert_carousel" class="button-primary" />
                </td>    
              </tr>
            </table>   
        </div>     
      </div> <!-- tabs wrapper -->  
      <?php } ?>
    </div>
	
    <?php // SCRIPTS ?>
    <script src="<?php echo GG_URL; ?>/js/functions.js" type="text/javascript"></script>
    <script src="<?php echo GG_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo GG_URL; ?>/js/lc-switch/lc_switch.min.js" type="text/javascript"></script>
<?php
	endif;
	return true;
}
