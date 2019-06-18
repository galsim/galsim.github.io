<?php
// loader class in footer
function gg_loader_class() {
	?>
    <script type="text/javascript">
    if(	navigator.appVersion.indexOf("MSIE 8.") != -1 || navigator.appVersion.indexOf("MSIE 9.") != -1 ) {
		document.body.className += ' gg_old_loader';
	} else {
		document.body.className += ' gg_new_loader';
	}
	</script>
    <?php	
}
add_action('wp_footer', 'gg_loader_class', 1);




// TEMP LOADER, RIGHT CLICK, FLAGS - in head 
function gg_head_js_elements() {
    // linked images function ?>
	<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(){
            jQuery(document).delegate('.gg_linked_img', 'click', function() {
                var link = jQuery(this).attr('gg-link');
                window.open(link ,'<?php echo get_option('gg_link_target', '_top') ?>');
            });
        });
	</script>
	
	<?php
	// if prevent right click
	if(get_option('gg_disable_rclick')) {
		?>
        <script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('body').delegate('.gg_gallery_wrap *, .gg_galleria_slider_wrap *, #lcl_wrapper *', "contextmenu", function(e) {
                e.preventDefault();
            });
		});
		</script>
        <?php	
	}
	
	// galleries / collections flags ?>
	<script type="text/javascript">
	gg_masonry_min_w = <?php echo (int)get_option('gg_masonry_min_width', 150) ?>;
	gg_phosostr_min_w = <?php echo get_option('gg_photostring_min_width', 120) ?>; 
	gg_coll_min_w = <?php echo (int)get_option('gg_coll_thumb_min_w', 200) ?>;
	
	gg_use_deeplink =  <?php echo (get_option('gg_disable_dl') == '1') ? 'false' : 'true'; ?>;
	gg_back_to_gall_scroll = <?php echo (get_option('gg_coll_back_to_scroll') == '1') ? 'true' : 'false'; ?>;
    </script>
	
	<?php
	$fx = get_option('gg_slider_fx', 'fadeslide');
	$fx_time = get_option('gg_slider_fx_time', 400);
	$crop = get_option('gg_slider_crop', 'true');
	$delayed_fx = (get_option('gg_delayed_fx')) ? 'false' : 'true';
	?>
	<script type="text/javascript">
	// global vars
	gg_galleria_toggle_info = <?php echo (get_option('gg_slider_tgl_info')) ? 'true' : 'false'; ?>;
	gg_galleria_fx = '<?php echo $fx ?>';
	gg_galleria_fx_time = <?php echo $fx_time ?>; 
	gg_galleria_img_crop = <?php echo ($crop=='true' || $crop=='false') ? $crop : '"'.$crop.'"' ?>;
	gg_galleria_autoplay = <?php echo (get_option('gg_slider_autoplay')) ? 'true' : 'false'; ?>;
	gg_galleria_interval = <?php echo get_option('gg_slider_interval', 3000) ?>;
	gg_delayed_fx = <?php echo $delayed_fx ?>;
	</script>
    <?php
}
add_action('wp_head', 'gg_head_js_elements', 999);


// SLIDER INIT - in footer 
function gg_footer_js_elements() {
	?>
	<script type="text/javascript">
	// Load Galleria's LCweb theme
	if(typeof(Galleria) != 'undefined') {
		Galleria.loadTheme('<?php echo GG_URL ?>/js/jquery.galleria/themes/ggallery/galleria.ggallery.js');
	}
	</script>
	<?php
}
add_action('wp_footer', 'gg_footer_js_elements', 999);




// right click - CSS code for iphone in head
function gg_head_elements() {
	if(get_option('gg_disable_rclick')) {
		?>
        <style type="text/css">
		.gg_gallery_wrap *, .gg_galleria_slider_wrap *, #lcl_wrapper * {
			-webkit-touch-callout: none; 
			-webkit-user-select: none;
		}
		</style>
        <?php	
	}
}
add_action('wp_head', 'gg_head_elements', 999);
