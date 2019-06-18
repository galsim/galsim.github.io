jQuery(document).ready(function($) {
	var textarea_cursor_pos = 9999;
	
	// tinymce btn
	gg_H = 615;
	gg_W = 580; 
	
	
	// cursor position - for textarea management
	$.fn.gg_getCursorPosition = function() {
        var el = $(this).get(0);
        var pos = 0;
        if('selectionStart' in el) {
            pos = el.selectionStart;
        } else if('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
	
	
	jQuery('body').delegate('#gg_editor_btn', "click", function () {
		textarea_cursor_pos = jQuery('#wp-content-editor-container > textarea').gg_getCursorPosition();
		
		setTimeout(function() {
			tb_show( 'Global Gallery', '#TB_inline?height='+gg_H+'&width='+gg_W+'&inlineId=gg_popup_container' );
			jQuery("#gg_sc_tabs").tabs();
			
			jQuery('#TB_window').css("height", gg_H);
			jQuery('#TB_window').css("width", gg_W);	
			jQuery('#TB_window, #TB_ajaxContent').css('overflow', 'visible');
			
			jQuery('#TB_window').addClass('gg_tinymce_lb_wrap').css('z-index', 999999); //.removeAttr('style');
			//jQuery('#TB_ajaxContent').addClass('gg_tinymce_lb').removeAttr('style');
			jQuery('#TB_overlay').css('z-index', 999998);
			
			jQuery('#TB_window').css("top", ((jQuery(window).height() - gg_H) / 4) + 'px');
			jQuery('#TB_window').css("left", ((jQuery(window).width() - gg_W) / 4) + 'px');
			jQuery('#TB_window').css("margin-top", ((jQuery(window).height() - gg_H) / 4) + 'px');
			jQuery('#TB_window').css("margin-left", ((jQuery(window).width() - gg_W) / 4) + 'px');	
			
			jQuery('.gg_popup_ip').lc_switch('YES', 'NO');
		}, 10);
	});
	
	// resize and center
	jQuery(window).resize(function() {
		if(jQuery('#gg_sc_tabs').is(':visible')) {
			var $gg_sc_selector = jQuery('#gg_sc_tabs').parents('#TB_window');
			
			$gg_sc_selector.css("height", gg_H).css("width", gg_W);	
			
			$gg_sc_selector.css("top", ((jQuery(window).height() - gg_H) / 4) + 'px');
			$gg_sc_selector.css("left", ((jQuery(window).width() - gg_W) / 4) + 'px');
			$gg_sc_selector.css("margin-top", ((jQuery(window).height() - gg_H) / 4) + 'px');
			$gg_sc_selector.css("margin-left", ((jQuery(window).width() - gg_W) / 4) + 'px');
		}
	});
	
	// slider height % info toggle
	jQuery('body').delegate('#gg_slider_h_type', "change", function () {
		if( jQuery(this).val() == '%') {
			jQuery('#gg_slider_h_type_note span').fadeTo(200, 1);	
		} else {
			jQuery('#gg_slider_h_type_note span').fadeTo(200, 0);	
		}
	});
	
	// add gallery shortcode to the editor
	jQuery('body').delegate('#gg_insert_gallery', "click", function () {
		var gid = jQuery('#gg_gallery_choose').val();
		var sc = '[g-gallery gid="'+gid+'"';
		
		if( jQuery('#gg_random').is(':checked') ) {
			sc = sc + ' random="1"';
		}

		if( jQuery('#gg_watermark').is(':checked') ) {
			sc = sc + ' watermark="1"';
		}
		
		if( jQuery('#gg_gall_pagination').val() ) {
			sc = sc + ' pagination="'+ jQuery('#gg_gall_pagination').val() +'"';
		}
		
		// overlay add-on
		if( jQuery('#gg_sc_gall .gg_custom_overlay').size() > 0  && jQuery('#gg_sc_gall .gg_custom_overlay').val() ) {
			sc = sc + ' overlay="'+ jQuery('#gg_sc_gall .gg_custom_overlay').val() +'"';	
		}
		
		sc = sc + ']';
		gg_sc_add_to_editor(sc);
	});
	
	
	// add collection shortcode to the editor
	jQuery('body').delegate('#gg_insert_collection', "click", function () {
		if( jQuery('#gg_collection_choose option').size() > 0 ) {
			var cid = jQuery('#gg_collection_choose').val();
			var sc = '[g-collection cid="'+cid+'"';
			
			// filters
			if( jQuery('#gg_coll_filter').is(':checked') ) {
				sc = sc + ' filter="1"';
			}

			// randomize
			if( jQuery('#gg_coll_random').is(':checked') ) {
				sc = sc + ' random="1"';
			}
			
			// overlay add-on
			if( jQuery('#gg_sc_coll .gg_custom_overlay').size() > 0  && jQuery('#gg_sc_coll .gg_custom_overlay').val() ) {
				sc = sc + ' overlay="'+ jQuery('#gg_sc_coll .gg_custom_overlay').val() +'"';	
			}

			sc = sc + ']';
			gg_sc_add_to_editor(sc);
		}
	});
	
	
	// add slider shortcode to the editor
	jQuery('body').delegate('#gg_insert_slider', "click", function () {
		var gid = jQuery('#gg_slider_gallery').val();
		var sc = '[g-slider gid="'+gid+'"';
		
		var sl_w = parseInt(jQuery('#gg_slider_w').val());
		var sl_w_t = jQuery('#gg_slider_w_type').val();
		sl_w = (isNaN(sl_w) || sl_w == 0) ? 100+sl_w_t : sl_w+sl_w_t;
		sc = sc + ' width="'+sl_w+'"';
		
		var sl_h = parseInt(jQuery('#gg_slider_h').val());
		var sl_h_t = jQuery('#gg_slider_h_type').val();
		sl_h = (isNaN(sl_h) || sl_h == 0) ? 55+sl_h_t : sl_h+sl_h_t;
		sc = sc + ' height="'+sl_h+'"';
		
		if( jQuery('#gg_slider_random').is(':checked') ) {
			sc = sc + ' random="1"';	
		}
		
		if( jQuery('#gg_slider_watermark').is(':checked') ) {
			sc = sc + ' watermark="1"';
		}
		
		if( jQuery('#gg_slider_autop').val() != 'auto' ) {
			sc = sc + ' autoplay="'+ jQuery('#gg_slider_autop').val() +'"';
		}
		
		sc = sc + ']';
		gg_sc_add_to_editor(sc);
	});
	
	// add carousel shortcode to the editor
	jQuery('body').delegate('#gg_insert_carousel', "click", function () {
		var gid = jQuery('#gg_car_gallery').val();
		var sc = '[g-carousel gid="'+gid+'" height="'+ parseInt(jQuery('#gg_car_h').val()) +'" per_time="'+ jQuery('#gg_car_per_time').val() +'"';
		
		if( jQuery('#gg_car_rows').val() > 1 ) {
			sc = sc + ' rows="'+ jQuery('#gg_car_rows').val() +'"';	
		}
		
		if( jQuery('#gg_car_multiscroll').is(':checked') ) {
			sc = sc + ' multiscroll="1"';	
		}
		
		if( jQuery('#gg_car_center_mode').is(':checked') ) {
			sc = sc + ' center="1"';	
		}
		
		if( jQuery('#gg_car_random').is(':checked') ) {
			sc = sc + ' random="1"';	
		}
		
		if( jQuery('#gg_car_watermark').is(':checked') ) {
			sc = sc + ' watermark="1"';
		}
		
		if( jQuery('#gg_car_autop').val() != 'auto' ) {
			sc = sc + ' autoplay="'+ jQuery('#gg_car_autop').val() +'"';
		}
		
		// overlay add-on
		if( jQuery('#gg_sc_carousel .gg_custom_overlay').size() > 0  && jQuery('#gg_sc_carousel .gg_custom_overlay').val() ) {
			sc = sc + ' overlay="'+ jQuery('#gg_sc_carousel .gg_custom_overlay').val() +'"';	
		}
		
		sc = sc + ']';
		gg_sc_add_to_editor(sc);
	});
	
	// add the shortcode in the editor
	gg_sc_add_to_editor = function(sc) {
		if(typeof(gg_inserting_sc) != 'undefined') {clearTimeout(gg_inserting_sc);}
		
		gg_inserting_sc = setTimeout(function() {
			if( jQuery('#wp-content-editor-container > textarea').is(':visible') ) {
				var content = jQuery('#wp-content-editor-container > textarea').val()
				var newContent = content.substr(0, textarea_cursor_pos) + sc + content.substr(textarea_cursor_pos);
				
				jQuery('#wp-content-editor-container > textarea').val(newContent);				
				textarea_cursor_pos = 9999;
			}
			else {
				tinyMCE.activeEditor.execCommand('mceInsertContent', 0, sc);
			}
			
			// closes Thickbox
			tb_remove();
		}, 100);
	}
	
	/////////////////////////////////////////////////////
	
	// switch theme menu pages
	jQuery('.lcwp_opt_menu').click(function() {
		curr_opt = jQuery('.curr_item').attr('id').substr(5);
		var opt_id = jQuery(this).attr('id').substr(5);
		
		if(!jQuery('#form_'+opt_id).is(':visible')) {
			// remove curr
			jQuery('.curr_item').removeClass('curr_item');
			jQuery('#form_'+curr_opt).hide();
			
			// show selected
			jQuery(this).addClass('curr_item');
			jQuery('#form_'+opt_id).show();	
		}
	});
	
	
	// sliders
	gg_slider_opt = function() {
		var a = 0; 
		$('.lcwp_slider').each(function(idx, elm) {
			var sid = 'slider'+a;
			jQuery(this).attr('id', sid);	
		
			svalue = parseInt(jQuery("#"+sid).next('input').val());
			minv = parseInt(jQuery("#"+sid).attr('min'));
			maxv = parseInt(jQuery("#"+sid).attr('max'));
			stepv = parseInt(jQuery("#"+sid).attr('step'));
			
			jQuery('#' + sid).slider({
				range: "min",
				value: svalue,
				min: minv,
				max: maxv,
				step: stepv,
				slide: function(event, ui) {
					jQuery('#' + sid).next().val(ui.value);
				}
			});
			jQuery('#'+sid).next('input').change(function() {
				var val = parseInt(jQuery(this).val());
				var minv = parseInt(jQuery("#"+sid).attr('min'));
				var maxv = parseInt(jQuery("#"+sid).attr('max'));
				
				if(val <= maxv && val >= minv) {
					jQuery('#'+sid).slider('option', 'value', val);
				}
				else {
					if(val <= maxv) {jQuery('#'+sid).next('input').val(minv);}
					else {jQuery('#'+sid).next('input').val(maxv);}
				}
			});
			
			a = a + 1;
		});
	}
	gg_slider_opt();
	
	
	// custom checks
	jQuery('.ip-checkbox').lc_switch('YES', 'NO');
	
	// chosen
	jQuery('.lcweb-chosen').each(function() {
		var w = jQuery(this).css('width');
		jQuery(this).chosen({width: w}); 
	});
	jQuery(".lcweb-chosen-deselect").chosen({allow_single_deselect:true});
	

	// colorpicker
	gg_colpick = function () {
		jQuery('.lcwp_colpick input').each(function() {
			var curr_col = jQuery(this).val().replace('#', '');
			jQuery(this).colpick({
				layout:'rgbhex',
				submit:0,
				color: curr_col,
				onChange:function(hsb,hex,rgb, el, fromSetColor) {
					if(!fromSetColor){ 
						jQuery(el).val('#' + hex);
						jQuery(el).parents('.lcwp_colpick').find('.lcwp_colblock').css('background-color','#'+hex);
					}
				}
			}).keyup(function(){
				jQuery(this).colpickSetColor(this.value);
				jQuery(this).parents('.lcwp_colpick').find('.lcwp_colblock').css('background-color', this.value);
			});  
		});
	}
	gg_colpick();
	

	jQuery(window).resize(function() {
		if(jQuery('#lcwp_tinymce_table').is(':visible')) {
			jQuery('#lcwp_tinymce_table').parent().parent().css("height", gg_H);
			jQuery('#lcwp_tinymce_table').parent().parent().css("width", gg_W);	
			
			jQuery('#lcwp_tinymce_table').parent().parent().css("top", ((jQuery(window).height() - gg_H) / 4) + 'px');
			jQuery('#lcwp_tinymce_table').parent().parent().css("left", ((jQuery(window).width() - gg_W) / 4) + 'px');
			jQuery('#lcwp_tinymce_table').parent().parent().css("margin-top", ((jQuery(window).height() - gg_H) / 4) + 'px');
			jQuery('#lcwp_tinymce_table').parent().parent().css("margin-left", ((jQuery(window).width() - gg_W) / 4) + 'px');
		}
	});
	
	
	// get the multiple select value
	window.lcwp_get_mul_select = function(field) {
		var val = []; 
		jQuery(field + ' :selected').each(function(i, selected){ 
			val[i] = jQuery(selected).val(); 
		});	
		
		return val;
	};
	
	
	window.UrlExists = function(url) {
	  var http = new XMLHttpRequest();
	  http.open('HEAD', url, false);
	  http.send();
	  return http.status!=404;
	}
	
	// Remove Uploaded Image
	$(document).delegate('.lcwp_del_ul_img', 'click', function(event) { 
        $lcwp_ul_block = $(this).parents('tr');
		
		$lcwp_ul_block.find('.lcwp_upload_input').val('');
		$lcwp_ul_block.find('.lcwp_upload_imgwrap').html('<div class="no_image"></div>'); 
	});
	
});