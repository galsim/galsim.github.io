(function($) {
	var gg_gallery_w 		= []; // galleries width wrapper
	var gg_img_margin 		= []; // gallery images margin 
	var gg_img_margin_l 	= []; // left margin
	var gg_img_border 		= [];
	var gg_gallery_pag 		= [];
	
	var gg_first_init 		= []; // flag for initial gallery management
	var gg_new_images 		= []; // flag for new images added
	var gg_is_paginating	= [];
	var gg_gall_is_showing 	= []; // showing animation debouncer
	var gg_shown_gall 		= []; // shown gallery flag
	
	var gg_debounce_resize	= []; // reesize trigger debounce for every gallery 
	var coll_ajax_obj 		= []; // where to store ajax objects to abort ajax calls
	
	// photostring manag - global vars
	var gg_temp_w 			= [];
	var gg_row_img 			= [];
	var gg_row_img_w 		= []; 
	
	// CSS3 loader code
	gg_loader = 
	'<div class="gg_loader">'+
		'<div class="ggl_1"></div><div class="ggl_2"></div><div class="ggl_3"></div><div class="ggl_4"></div>'+
	'</div>';
	
	
	jQuery(document).ready(function() {
		gg_galleries_init();
		gg_get_cg_deeplink();
		
		// if old IE, hide secondary overlay
		if(gg_is_old_IE()) {jQuery('.gg_sec_overlay').hide();}
	});
	
	
	// initialize the galleries
	gg_galleries_init = function(gid, after_resize) {
		// if need to initialize a specific gallery
		if(typeof(gid) != 'undefined' && gid) {
			if(typeof(after_resize) == 'undefined') {
				gg_first_init[gid] = 1;
				gg_new_images[gid] = 1;
				gg_is_paginating[gid] = 0;
			}
			
			gg_gallery_process(gid, after_resize);
		}
		
		// execute every gallery in the page
		else {
			jQuery('.gg_gallery_wrap').not(':empty').each(function() {
				var gg_gid = jQuery(this).attr('id');
				
				if(typeof(after_resize) == 'undefined') {
					gg_first_init[gg_gid] = 1;
					gg_new_images[gg_gid] = 1;
					gg_is_paginating[gg_gid] = 0;
				}
	
				gg_gallery_process(gg_gid, after_resize);
			}); 
		}
	}
	
	
	// store galleries info 
	gg_gallery_info = function(gid, after_resize) {
		var coll_sel = (jQuery('#'+gid).hasClass('gg_collection_wrap')) ? '.gg_coll_container' : '';
		gg_gallery_w[gid] = (coll_sel) ? jQuery('#'+gid+' .gg_coll_container').width() : jQuery('#'+gid).width(); 

		if(typeof(after_resize) != 'undefined') {return true;} // only get size if resize event has been triggered
		
		gg_img_border[gid] = parseInt( jQuery('#'+gid+' '+coll_sel+' .gg_img').first().css('border-right-width'));
		gg_img_margin[gid] = parseInt( jQuery('#'+gid+' '+coll_sel+' .gg_img').first().css('margin-right')); 
		gg_img_margin_l[gid] = parseInt( jQuery('#'+gid+' '+coll_sel+' .gg_img').first().css('margin-left')); 
		
		// exceptions for isotope elements
		if(jQuery('#'+gid).hasClass('gg_masonry_gallery') || jQuery('#'+gid).hasClass('gg_collection_wrap')) {
			gg_img_border[gid] = parseInt( jQuery('#'+gid+' '+coll_sel+' .gg_img_inner').first().css('border-right-width'));
			gg_img_margin[gid] = parseInt( jQuery('#'+gid+' '+coll_sel+' .gg_img').first().css('padding-right')); 
			gg_img_margin_l[gid] = parseInt( jQuery('#'+gid+' '+coll_sel+' .gg_img').first().css('padding-left')); 	
		}
	}
	
	
	// process single gallery
	gg_gallery_process = function(gid, after_resize) {	
		if(typeof(gid) == 'undefined') {return false;}	
		
		gg_gallery_info(gid, after_resize);

		
		if( jQuery('#'+gid).hasClass('gg_standard_gallery') ) {
			gg_man_standard_gallery(gid);	
		}
		else if( jQuery('#'+gid).hasClass('gg_masonry_gallery') ) {
			gg_man_masonry_gallery(gid);
		}
		else if( jQuery('#'+gid).hasClass('gg_string_gallery') ) {
			gg_man_string_gallery(gid);	
		}	
		else if( jQuery('#'+gid).hasClass('gg_collection_wrap') ) {
			gg_man_collection(gid);	
		}	
		
		
		// OVERLAY MANAGER ADD-ON //
		if(typeof(ggom_hub) == "function") {
			ggom_hub(gid);
		}
		////////////////////////////
	}
	
	
	/*** manage standard gallery ***/
	gg_man_standard_gallery = function(gid) {
		if(gg_new_images[gid]) {
			jQuery('#'+gid+' .gg_img .gg_main_thumb').lcweb_lazyload({
				allLoaded: function(url_arr, width_arr, height_arr) {
					jQuery('#'+gid+' .gg_loader').fadeOut('fast');
					gg_img_fx_setup(gid, width_arr, height_arr);
					
					layout_standard_gall(gid, true);
					gg_new_images[gid] = 0;
				}
			});
		}
		else {
			layout_standard_gall(gid);
		}
		
		gg_check_primary_ol(gid);	
	}
	
	var layout_standard_gall = function(gid, has_new_img) {
		var img_w = jQuery('#'+gid+' .gg_img').first().outerWidth(false);
		var row_w = img_w;
		var img_per_row = 1;
		
		// cycle to find out how many columns to have
		while(row_w <= gg_gallery_w[gid]) {
			row_w = row_w + img_w + gg_img_margin[gid];
			img_per_row++;
		}
		img_per_row = img_per_row - 1;

		var b = 1;
		jQuery('#'+gid+' .gg_img').each(function(i) {
			jQuery(this).addClass(gid+'-'+i);
			
			if(b == img_per_row) { 
				jQuery('.'+gid+'-'+i).addClass('gg_lor'); 
				b = 1;
			}
			else { 
				jQuery('.'+gid+'-'+i).removeClass('gg_lor');
				b++; 
			}
			
			//////
			
			if(typeof(has_new_img) != 'undefined') {
				var $to_display = jQuery('#'+gid+' .gg_img').not('.gg_shown');
				if(i == 0) {gg_gallery_slideDown(gid, $to_display.length);}
				if(i == (jQuery('#'+gid+' .gg_img').length - 1)) {$to_display.gg_display_images();}		
			}
		});
	}
	


	/*** manage masonry gallery ***/
	gg_man_masonry_gallery = function(gid) {
		var cols = parseInt(jQuery('#'+gid).attr('col-num')); 
		var margin = gg_img_margin[gid] + gg_img_margin_l[gid];
		var col_w = (gg_gallery_w[gid] / cols) - (margin * (cols - 1));
		
		// find out right column number
		while(col_w < gg_masonry_min_w) {
			if(cols <= 1) {
				cols = 1;
				return false; 
			}
			
			cols--;
			col_w = (gg_gallery_w[gid] / cols) - (margin * (cols - 1));	
		}
		
		jQuery('#'+gid+' .gg_img').each(function(i) {
			var img_class = gid+'-'+i;
			jQuery(this).css('width', Math.floor(gg_gallery_w[gid] / cols)).addClass(img_class);
		});	
		
		gg_check_primary_ol(gid);
		

		if(gg_new_images[gid]) {
			jQuery('#'+gid+' .gg_img .gg_main_thumb').lcweb_lazyload({
				allLoaded: function(url_arr, width_arr, height_arr) {
					jQuery('#'+gid+' .gg_loader').fadeOut('fast');
					gg_img_fx_setup(gid, width_arr, height_arr);
					
					jQuery('#'+gid+' .gg_container').isotope({
						percentPosition: true,
						isResizeBound: false,
						masonry: {
							columnWidth: 1
						},
						containerClass: 'gg_isotope',	
						itemClass : 'gg_isotope-item',
						itemSelector: '.gg_img',
						transitionDuration: 0
					});
					
					setTimeout(function() { // litle delay to allow masonry placement
						var $to_display = jQuery('#'+gid+' .gg_img').not('.gg_shown');
						
						gg_gallery_slideDown(gid, $to_display.length);
						$to_display.gg_display_images();	
						
						gg_new_images[gid] = 0;
					}, 300);
				}
			});
		}
		else {
			setTimeout(function() {
				if(typeof(jQuery.Isotope) != 'undefined' && typeof(jQuery.Isotope.prototype.reLayout) != 'undefined') { // old Isotope
					jQuery('#'+gid+' .gg_container').isotope('reLayout');
				} else { // new
					jQuery('#'+gid+' .gg_container').isotope('layout');
				}
			}, 100);
		}
	}
	
	
	
	/*** manage photostring gallery ***/
	gg_man_string_gallery = function(gid) {
		if(gg_new_images[gid]) {
			jQuery('#'+gid+' .gg_img .gg_main_thumb').lcweb_lazyload({
				allLoaded: function(url_arr, width_arr, height_arr) {
					
					gg_img_fx_setup(gid, width_arr, height_arr);
					layout_photostr_gall(gid);
					
					jQuery('#'+gid+' .gg_loader').fadeOut('fast');		
						
					var $to_display = jQuery('#'+gid+' .gg_img').not('.gg_shown');
					gg_gallery_slideDown(gid, $to_display.length);
					$to_display.gg_display_images();

					
					gg_new_images[gid] = 0;
				}
			});
		}
		else {
			layout_photostr_gall(gid);
		}
		
		gg_check_primary_ol(gid);
	}
	
	var layout_photostr_gall = function(gid) {
		gg_temp_w[gid] 		= 0;
		gg_row_img[gid] 	= jQuery.makeArray();
		gg_row_img_w[gid] 	= jQuery.makeArray();
		
		jQuery('#'+gid+' .gg_img').removeClass('gg_lor');
		
		jQuery('#'+gid+' .gg_main_thumb').each(function(i, v) {
			var $img_obj = jQuery(this).parents('.gg_img');
			var img_class = gid+'-'+i;
			var w_to_match = 0;

		 	$img_obj.addClass(img_class);
			var img_w = jQuery(this).width() + (gg_img_border[gid] * 2); 
			
			gg_row_img[gid].push('.'+img_class);
			gg_row_img_w[gid].push(img_w);
			
			gg_temp_w[gid] = gg_temp_w[gid] + img_w;
			w_to_match =  gg_temp_w[gid] + ((gg_row_img[gid].length - 1) * gg_img_margin[gid]);
			w_to_match = w_to_match + 1; // sometimes browsers have bad behavior also using perfect width fit
	
			// if you're lucky and size is perfect
			if(gg_gallery_w[gid] == w_to_match) { 
				$img_obj.addClass('gg_lor');
				
				gg_row_img[gid] 	= jQuery.makeArray();
				gg_row_img_w[gid] 	= jQuery.makeArray();
				gg_temp_w[gid] 		= 0;
			}
			
			// adjust img sizes		
			else if(gg_gallery_w[gid] < w_to_match) {
				$img_obj.addClass('gg_lor');

				var to_shrink = w_to_match - gg_gallery_w[gid];
				photostr_row_img_shrink(gid, to_shrink);  
				
				gg_row_img[gid] 	= jQuery.makeArray();
				gg_row_img_w[gid] 	= jQuery.makeArray();
				gg_temp_w[gid] 		= 0;
			}
		});
	}
	
	
	var photostr_row_img_shrink = function(gid, to_shrink) {
		var remaining_shrink = to_shrink;
		var new_row_w = 0;
		
		// only ine image - set to 100% width
		if(gg_row_img[gid].length == 1) {
			jQuery(gg_row_img[gid][0]).width('100%');
			return true;
		}
		
		// calculate
		for(a=0; a < gg_row_img[gid].length; a++) {
			var proport_shrink = Math.ceil( remaining_shrink / (gg_row_img[gid].length - a));
			
			// if new width is higher than minimum width or is last element
			if((gg_row_img_w[gid][a] - proport_shrink) >= gg_phosostr_min_w || a == (gg_row_img[gid].length - 1)) {
				jQuery(gg_row_img[gid][a]).width( gg_row_img_w[gid][a] - proport_shrink - (gg_img_border[gid] * 2));	
				
				new_row_w = new_row_w + (gg_row_img_w[gid][a] - proport_shrink);
				remaining_shrink = remaining_shrink - proport_shrink;
			}
			else {
				var new_w = gg_phosostr_min_w - (gg_img_border[gid] * 2);
				
				// if min width is bigger than image's width
				if(gg_row_img_w[gid][a] < new_w) {
					new_w = gg_row_img_w[gid][a] + (gg_img_border[gid] * 2);
					jQuery(gg_row_img[gid][a]).width( new_w );
					
					new_row_w = new_row_w + new_w;
					//remaining_shrink = remaining_shrink - (new_w - gg_row_img_w[gid][a]);	
				}
				else {
					jQuery(gg_row_img[gid][a]).width( new_w );	
				
					new_row_w = new_row_w + new_w;
					remaining_shrink = remaining_shrink - (gg_row_img_w[gid][a] - gg_phosostr_min_w);
				}
			}
			

			// last element - check overall width and adjust
			if(a == (gg_row_img[gid].length - 1)) {
				var diff = (gg_gallery_w[gid] - 1) - (new_row_w + (gg_img_margin[gid] * (gg_row_img[gid].length - 1))); // sometimes browsers have bad behavior also using perfect width fit
				if(diff > 0) {
					jQuery(gg_row_img[gid][a]).width( jQuery(gg_row_img[gid][a]) - diff);	
				} 
				else if(diff < 0) {
					jQuery(gg_row_img[gid][a]).width( jQuery(gg_row_img[gid][a]) + diff);
				}
			}
		}
	}
	
	
	
	/*** manage collection ***/
	gg_man_collection = function(cid) {
		var cols = parseInt(jQuery('#'+cid).attr('col-num')); 
		var margin = gg_img_margin[cid] + gg_img_margin_l[cid];
		var col_w = (gg_gallery_w[cid] / cols) - (margin * (cols - 1));
		
		// find out right column number
		while(col_w < gg_coll_min_w) {
			if(cols <= 1) {
				cols = 1;
				return false; 
			}
			
			cols--;
			col_w = (gg_gallery_w[cid] / cols) - (margin * (cols - 1));	
		}
		
		jQuery('#'+cid+' .gg_coll_img_wrap').each(function(i) {
			jQuery(this).css('width', ((100 / cols).toFixed(1) - 0.1) + '%');
		});	
		
		gg_check_primary_ol(cid);
		
		if(!gg_shown_gall[cid]) {
			jQuery('#'+cid+' .gg_coll_img .gg_main_thumb').lcweb_lazyload({
				allLoaded: function(url_arr, width_arr, height_arr) {
					jQuery('#'+cid+' .gg_loader').fadeOut('fast');
					gg_img_fx_setup(cid, width_arr, height_arr);
					
					
					jQuery('#'+cid+' .gg_coll_img').each(function(i) {
						var img_class = cid+'-'+i;
						jQuery(this).addClass(img_class);
					});
					
					jQuery('#'+cid+' .gg_coll_container').isotope({
						layoutMode : 'fitRows',
						percentPosition: true,
						isResizeBound: false,
						containerClass: 'gg_isotope',	
						itemClass : 'gg_isotope-item',
						itemSelector: '.gg_coll_img_wrap',
						transitionDuration: '0.6s'
					});
					
					setTimeout(function() { // litle delay to allow masonry placement
						var $to_display = jQuery('#'+cid+' .gg_coll_img_wrap').not('.gg_shown');
						
						gg_gallery_slideDown(cid, $to_display.length);
						$to_display.gg_display_images();
							
						gg_new_images[cid] = 0;
					}, 300);
				}
			});
		}
		else {
			setTimeout(function() {
				if(typeof(jQuery.Isotope) != 'undefined' && typeof(jQuery.Isotope.prototype.reLayout) != 'undefined') { // old Isotope
					jQuery('#'+cid+' .gg_container').isotope('reLayout');
				} else { // new
					jQuery('#'+cid+' .gg_container').isotope('layout');
				}
			}, 300);
		}	
	}
	
	
	////////////////////////////////////////////////////////////////
	

	// load a collection gallery - click trigger
	jQuery(document).ready(function() {
		jQuery('body').delegate('.gg_coll_img:not(.gg_linked_img)', 'click', function() {
			var cid = jQuery(this).parents('.gg_collection_wrap').attr('id');
			var gdata = jQuery(this).attr('gall-data');
			var gid = jQuery(this).attr('rel');
			
			if(typeof(coll_ajax_obj[cid]) == 'undefined' || !coll_ajax_obj[cid]) {
				gg_set_deeplink('coll-gall', gid);
				gg_load_coll_gallery(cid, gdata);
			}
		});
	});
	
	// load collection's gallery 
	gg_load_coll_gallery = function(cid, gdata) {
		var curr_url = jQuery(location).attr('href');
		
		if( jQuery('#'+cid+' .gg_coll_gallery_container .gg_gallery_wrap').length > 0) {
			jQuery('#'+cid+' .gg_coll_gallery_container .gg_gallery_wrap').remove();	
			jQuery('#'+cid+' .gg_coll_gallery_container').append('<div class="gg_gallery_wrap">'+ gg_loader +'</div>');
		}
		jQuery('#'+cid+' .gg_coll_gallery_container .gg_gallery_wrap').addClass('gg_coll_ajax_wait');
	
		jQuery('#'+cid+' > table').animate({'left' : '-100%'}, 700, function() {
			jQuery('#'+cid+' .gg_coll_table_first_cell').css('opacity', 0);	
		});
		
		// scroll to the top of the collection - if is lower of the gallery top
		var coll_top_pos = jQuery('#'+cid).offset().top;
		if( jQuery(window).scrollTop() > coll_top_pos ) {
			jQuery('html, body').animate({'scrollTop': coll_top_pos - 15}, 600);
		}
		
		var data = {
			gg_type: 'gg_load_coll_gallery',
			cid: cid,
			gdata: gdata
		};
		
		coll_ajax_obj[cid] = jQuery.post(curr_url, data, function(response) {
			jQuery('#'+cid+' .gg_coll_gallery_container .gg_gallery_wrap').remove();
			jQuery('#'+cid+' .gg_coll_gallery_container').removeClass('gg_main_loader').append(response);
			jQuery('#'+cid+' .gg_coll_gall_title').not(':first').remove();
			
			gg_coll_gall_title_layout(cid);
			coll_ajax_obj[cid] = null;
			
			var gid = jQuery('#'+cid+' .gg_coll_gallery_container').find('.gg_gallery_wrap').attr('id');
			gg_galleries_init(gid);
		});	
	}
	
	
	// collections title - mobile check
	gg_coll_gall_title_layout = function(cid) {
		jQuery('#'+cid+' .gg_coll_gall_title').each(function() {
            var wrap_w = jQuery(this).parents('.gg_coll_table_cell').width();
			var elem_w = jQuery(this).parent().find('.gg_coll_go_back').outerWidth(true) + jQuery(this).outerWidth();
			
			if(elem_w > wrap_w) {jQuery(this).addClass('gg_narrow_coll');}
			else {jQuery(this).removeClass('gg_narrow_coll');}
        });	
	}
	
	
	// back to collection
	jQuery(document).ready(function() {
		jQuery('body').delegate('.gg_coll_go_back', 'click', function() {
			var cid = jQuery(this).parents('.gg_collection_wrap').attr('id');
			
			// if is performing ajax - abort
			if(typeof(coll_ajax_obj[cid]) != 'undefined' && coll_ajax_obj[cid]) {
				coll_ajax_obj[cid].abort();
				coll_ajax_obj[cid] = null;	
			}
			
			// scroll to the top of the collection - if is lower of the gallery top
			var coll_top_pos = jQuery('#'+cid).offset().top;
			if( jQuery(window).scrollTop() > coll_top_pos ) {
				jQuery('html, body').animate({'scrollTop': coll_top_pos - 15}, 600);
			}
			
			// go back
			jQuery('#'+cid+' .gg_coll_table_first_cell').css('opacity', 1);	
				
			jQuery('#'+cid+' > table').animate({'left' : 0}, 700, function() {	
				jQuery('#'+cid+' .gg_coll_gallery_container *').not('.gg_coll_go_back').fadeOut(300, function() {
					jQuery(this).remove();
					jQuery('#'+cid+' .gg_coll_gallery_container .gg_gallery_wrap, #'+cid+' .gg_coll_gallery_container .gg_coll_gall_title').remove();
					jQuery('#'+cid+' .gg_coll_gallery_container').append('<div class="gg_gallery_wrap"></div>');
				});
			});
			
			gg_clear_deeplink();
		});
	});
	
	
	// manual collections filter - handlers
	jQuery(document).ready(function() {
		jQuery('body').delegate('.gg_filter a', 'click', function(e) {
			e.preventDefault();
			
			var cid = jQuery(this).parents('.gg_filter').attr('id').substr(4);
			var sel = jQuery(this).attr('rel');
			var cont_id = '#' + jQuery(this).parents('.gg_collection_wrap').attr('id');
	
			jQuery('#ggf_'+cid+' a').removeClass('gg_cats_selected');
			jQuery(this).addClass('gg_cats_selected');	
	
			gg_coll_manual_filter(cid, sel, cont_id);
			
			// if is there a dropdown filter - select option 
			if( jQuery('#ggmf_'+cid).length > 0 ) {
				jQuery('#ggmf_'+cid+' option').removeAttr('selected');
				
				if(jQuery(this).attr('rel') !== '*') {
					jQuery('#ggmf_'+cid+' option[value='+ jQuery(this).attr('rel') +']').attr('selected', 'selected');
				}
			}
		});
		
		jQuery('body').delegate('.gg_mobile_filter_dd', 'change', function(e) {
			var cid = jQuery(this).parents('.gg_mobile_filter').attr('id').substr(5);
			var sel = jQuery(this).val();
			var cont_id = '#' + jQuery(this).parents('.gg_collection_wrap').attr('id');
			
			gg_coll_manual_filter(cid, sel, cont_id);
			
			// select related desktop filter's button
			var btn_to_sel = (jQuery(this).val() == '*') ? '.ggf_all' : '.ggf_id_'+sel
			jQuery('#ggf_'+cid+' a').removeClass('gg_cats_selected');
			jQuery('#ggf_'+cid+' '+btn_to_sel).addClass('gg_cats_selected');
		});
	});
	
	
	// manual collections filter - perform
	var gg_coll_manual_filter = function(cid, sel, cont_id) {
		
		// set deeplink
		if ( sel !== '*' ) { gg_set_deeplink('cat', sel); }
		else { gg_clear_deeplink(); }

		if ( sel !== '*' ) { sel = '.ggc_' + sel;}
		jQuery(cont_id + ' .gg_coll_container').isotope({ filter: sel });
	};
	
	
	/////////////////////////////////////////////////
	// show gallery/collection images
	jQuery.fn.gg_display_images = function(index) {
		this.each(function(i, v) {
			var $subj = jQuery(this);
			var delay = (typeof(gg_delayed_fx) != 'undefined' && gg_delayed_fx) ? 170 : 0;
			var true_index = (typeof(index) == 'undefined') ? i : index;
			
			setTimeout(function() {
				if( navigator.appVersion.indexOf("MSIE 8.") != -1 || navigator.appVersion.indexOf("MSIE 9.") != -1 ) {
					$subj.fadeTo(450, 1);	
				}
				$subj.addClass('gg_shown');
			}, (delay * true_index));
		});
	};
	
	
	// remove loaders and slide down gallery
	gg_gallery_slideDown = function(gid, img_num, is_collection) {
		if(typeof(gg_gall_is_showing[gid]) == 'undefined' || !gg_gall_is_showing[gid]) {
			var fx_time = img_num * 200;
			var $subj = (typeof(is_collection) == 'undefined') ? jQuery('#'+gid+' .gg_container') : jQuery('#'+gid+' .gg_coll_container');
	
			$subj.animate({"min-height": 80}, 300, 'linear').animate({"max-height": 9999}, 6500, 'linear');		
			gg_gall_is_showing[gid] = setTimeout(function() {
				if( // fix for old safari
					navigator.appVersion.indexOf("Safari") == -1 || 
					(navigator.appVersion.indexOf("Safari") != -1 && navigator.appVersion.indexOf("Version/5.") == -1 && navigator.appVersion.indexOf("Version/4.") == -1)
				) {
					$subj.css('min-height', 'none');
				}
				
				$subj.stop().css('max-height', 'none');
				gg_gall_is_showing[gid] = false;
			}, fx_time);
				
			
			if(gg_new_images[gid]) {
				setTimeout(function() {
					gg_new_images[gid] = 0;
					jQuery('#'+gid+' .gg_paginate > div').fadeTo(150, 1);
				}, 500);	
			}
			
			gg_shown_gall[gid] = true;
		}
	};
	

	/////////////////////////////////////
	// collections deeplinking
	
	// get collection filters dl
	function gg_get_cf_deeplink(browser_history) {
		var hash = location.hash;
		if(hash == '' || hash == '#gg') {return false;}
			
		if( jQuery('.gg_filter').length > 0) {
			jQuery('.gg_gallery_wrap').each(function() {
				var cid = jQuery(this).attr('id');
				var val = hash.substring(hash.indexOf('#gg_cf')+7, hash.length)

				// check the cat existence
				if(hash.indexOf('#gg_cf') !== -1) {
					if( jQuery('#'+cid+' .gg_filter a[rel=' + val + ']').length > 0 ) {
						var sel = '.ggc_' + jQuery('#'+cid+' .gg_filter a[rel=' + val + ']').attr('rel');
		
						// filter
						jQuery('#'+cid+' .gg_coll_container').isotope({ filter: sel });
						
						// set the selected
						jQuery('#'+cid+' .gg_filter a').removeClass('gg_cats_selected');
						jQuery('#'+cid+' .gg_filter a[rel=' + val + ']').addClass('gg_cats_selected');	
					}
				}
			});
		}
	}
	
	
	// get collection galleries - deeplink
	function gg_get_cg_deeplink(browser_history) { // coll selection
		var hash = location.hash;
		if(hash == '' || hash == '#gg') {return false;}
		
		if(hash.indexOf('#gg_cg') !== -1) {
			var gid = hash.substring(hash.indexOf('#gg_cg')+7, hash.length)
			
			// check the item existence
			if( jQuery('.gg_coll_img[rel=' + gid + ']').length > 0 ) {
				var cid = jQuery('.gg_coll_img[rel=' + gid + ']').first().parents('.gg_gallery_wrap').attr('id');
				var gdata = jQuery('.gg_coll_img[rel=' + gid + ']').first().attr('gall-data');
				
				gg_load_coll_gallery(cid, gdata);
			}
		}
	}
	
	
	function gg_set_deeplink(subj, val) {
		if( gg_use_deeplink ) {
			gg_clear_deeplink();
	
			var gg_hash = (subj == 'cat') ? 'gg_cf' : 'gg_cg';  
			location.hash = gg_hash + '_' + val;
		}
	}
	
	
	function gg_clear_deeplink() {
		if( gg_use_deeplink ) {
			var curr_hash = location.hash;

			// find if a mg hash exists
			if(curr_hash.indexOf('#gg_cg') !== false || curr_hash.indexOf('#gg_cf') !== false) {
				location.hash = 'gg';
			}
		}
	}


	
	//////////////////////////////////////
	// pagination
	
	jQuery(document).ready(function() {
		// standard pagination - next
		jQuery('body').delegate('.gg_next_page', 'click', function() {
			var gid = jQuery(this).parents('.gg_gallery_wrap').attr('id');
			
			if( !jQuery(this).hasClass('gg_pag_disabled') && gg_is_paginating[gid] == 0 ){
				var curr_page = parseInt( jQuery(this).parents('.gg_standard_pag').find('span').text() );
				gg_standard_pagination(gid, curr_page, 'next');
			}
		});
		
		// standard pagination - prev
		jQuery('body').delegate('.gg_prev_page', 'click', function() {
			var gid = jQuery(this).parents('.gg_gallery_wrap').attr('id');
			
			if( !jQuery(this).hasClass('gg_pag_disabled') && gg_is_paginating[gid] == 0 ){
				var curr_page = parseInt( jQuery(this).parents('.gg_standard_pag').find('span').text() );
				gg_standard_pagination(gid, curr_page, 'prev');
			}
		});	
	});
	
	// standard pagination - do pagination
	gg_standard_pagination = function(gid, curr_page, action) {
		if(gg_is_paginating[gid] == 0) {
			gg_is_paginating[gid] = 1;

			var curr_url = jQuery(location).attr('href');
			var images = gg_temp_data[gid];
			
			var next_pag = (action == 'next') ?  curr_page + 1 : curr_page - 1;
			if(next_pag < 1) {next_pag = 1;}
			jQuery('#'+gid+' .gg_paginate').find('span').text(next_pag);
			
			// smooth change effect
			var curr_h = jQuery('#'+gid+' .gg_container').height();
			var smooth_timing = Math.round( (curr_h / 30) * 25);
			if(smooth_timing < 220) {smooth_timing = 220;}

			if(typeof(gg_gall_is_showing[gid]) != 'undefined') {
				clearTimeout(gg_gall_is_showing[gid]);
				gg_gall_is_showing[gid] = false;
			}
			
			jQuery('#'+gid+' .gg_container').css('max-height', curr_h).stop().animate({"max-height": 150}, smooth_timing);
			var is_closing = true
			setTimeout(function() {	is_closing = false; }, smooth_timing);
			
			// hide images
			jQuery('#'+gid+' .gg_img').addClass('gg_old_page');
			if( navigator.appVersion.indexOf("MSIE 8.") != -1 || navigator.appVersion.indexOf("MSIE 9.") != -1 ) {
				jQuery('#'+gid+' .gg_img').fadeTo(200, 0);
			}
			
			// show loader
			setTimeout(function() {	
				jQuery('#'+gid+' .gg_loader').fadeIn('fast');
			}, 200);
			
			
			// destroy the old isotope layout
			setTimeout(function() {	
				if( jQuery('#'+gid).hasClass('gg_masonry_gallery')) { 
					jQuery('#'+gid+' .gg_container').isotope('destroy'); 
				}
			}, (smooth_timing - 10));

			// scroll to the top of the gallery
			if($(window).scrollTop() > (jQuery("#"+gid).offset().top - 20)) {
				jQuery('html,body').animate({scrollTop: (jQuery("#"+gid).offset().top - 20)}, smooth_timing);
			}
		
			// get new data
			var data = {
				gid: jQuery("#"+gid).attr('rel'),
				gg_type: 'gg_pagination',
				gg_shown: jQuery('#'+gid+' .gg_main_thumb').first().attr('id'),
				gg_page: next_pag,
				gg_ol: jQuery('#'+gid).attr('gg_ol'),
				gg_images: images
			};

			jQuery.post(curr_url, data, function(response) {
				var wait = setInterval(function() {
					if(!is_closing) {
						clearInterval(wait);
						
						jQuery('#'+gid+' .gg_paginate .gg_loader').remove();
						jQuery('#'+gid+' .gg_standard_pag').fadeTo(200, 1);
						
						resp = jQuery.parseJSON(response);
						jQuery('#'+gid+' .gg_container').html(resp.html);
						
						// if old IE, hide secondary overlay
						if(gg_is_old_IE()) {jQuery('.gg_sec_overlay').hide();}
						
						gg_new_images[gid] = 1;
						gg_gallery_process(gid);
						gg_is_paginating[gid] = 0;
						
						if(resp.more != '1') { jQuery('#'+gid+' .gg_paginate').find('.gg_next_page').addClass('gg_pag_disabled'); }
						else { jQuery('#'+gid+' .gg_paginate').find('.gg_next_page').removeClass('gg_pag_disabled'); }
						
						if(next_pag == 1) { jQuery('#'+gid+' .gg_paginate').find('.gg_prev_page').addClass('gg_pag_disabled'); }
						else { jQuery('#'+gid+' .gg_paginate').find('.gg_prev_page').removeClass('gg_pag_disabled'); }
					}
				}, 10);
			});
		}
	}
	
	
	// infinite scroll
	jQuery(document).ready(function() {
		jQuery('body').delegate('.gg_infinite_scroll', 'click', function() {
			var gid = jQuery(this).parents('.gg_gallery_wrap').attr('id');
			var curr_url = jQuery(location).attr('href');
			
			var shown = jQuery.makeArray();
			var images = gg_temp_data[gid];
			
			jQuery('#'+gid+' .gg_container').css('max-height', jQuery('#'+gid+' .gg_container').height());
			
			// hide nav and append loader
			if( jQuery('#'+gid+' .gg_paginate .gg_loader').length != 0 ) {jQuery('#'+gid+' .gg_paginate .gg_loader').remove();}
			jQuery('#'+gid+' .gg_infinite_scroll').fadeTo(200, 0);
			setTimeout(function() {	
				jQuery('#'+gid+' .gg_paginate').prepend(gg_loader);
			}, 200);

			// set the page to show
			if(typeof(gg_gallery_pag[gid]) == 'undefined') { 
				var next_pag = 2;
				gg_gallery_pag[gid] = next_pag; 
			} else {
				var next_pag = gg_gallery_pag[gid] + 1;
				gg_gallery_pag[gid] = next_pag; 	
			}

			var data = {
				gid: jQuery("#"+gid).attr('rel'),
				gg_type: 'gg_pagination',
				gg_shown: jQuery('#'+gid+' .gg_main_thumb').first().attr('id'),
				gg_page: next_pag,
				gg_ol: jQuery('#'+gid).attr('gg_ol'),
				gg_images: images
			};
			jQuery.post(curr_url, data, function(response) {
				resp = jQuery.parseJSON(response);
				
				if( jQuery('#'+gid).hasClass('gg_string_gallery') ) {
					jQuery('#'+gid+' .gg_container .gg_string_clear_both').remove();
					jQuery('#'+gid+' .gg_container').append(resp.html);
					jQuery('#'+gid+' .gg_container').append('<div class="gg_string_clear_both" style="clear: both;"></div>');
				}
				else {
					jQuery('#'+gid+' .gg_container').append(resp.html);	
				}
				
				if( jQuery('#'+gid).hasClass('gg_masonry_gallery')) {
					jQuery('#'+gid+' .gg_container').isotope('reloadItems');
				}
				
				// if old IE, hide secondary overlay
				if(gg_is_old_IE()) {jQuery('.gg_sec_overlay').hide();}
	
				gg_new_images[gid] = 1;
				gg_gallery_process(gid);
				
				if(resp.more != '1') { jQuery('#'+gid+' .gg_paginate').remove(); }
				else {
					jQuery('#'+gid+' .gg_paginate .gg_loader').remove();
					jQuery('#'+gid+' .gg_infinite_scroll').fadeTo(200, 1);	
				}
			});
		});
	});
	
	
	//  primary overlay check - if no title or too small, hide
	gg_check_primary_ol = function(gid, respect_delay) {
		var check_delay = (typeof(respect_delay) == 'undefined') ? 0 : 150;
				
		jQuery('#'+gid+' .gg_img').each(function(i, e) {
			var $ol_subj = jQuery(this);
			
			setTimeout(function() {
				var ol_title = $ol_subj.find('.gg_img_title').html();
				
				if( $ol_subj.width() < 100 || jQuery.trim(ol_title) == '') { 
					$ol_subj.find('.gg_main_overlay').hide(); 
				}
				else { $ol_subj.find('.gg_main_overlay').show();  }
			}, (check_delay * (i + 1)) ); 
		});	
	}
	
	//////////////////////////////////////
	
	
	// images effects
	gg_img_fx_setup = function(gid, width_arr, height_arr) {
		var fx_timing = jQuery('#'+gid).attr('ggom_timing'); 
		
		if( typeof(jQuery('#'+gid).attr('ggom_fx')) != 'undefined' && jQuery('#'+gid).attr('ggom_fx').indexOf('grayscale') != -1) {
			
			// create and append grayscale image
			jQuery('#'+gid+' .gg_main_thumb').each(function(i, v) {
				if( jQuery(this).parents('.gg_img').find('.gg_fx_canvas.gg_grayscale_fx ').length == 0 ) {
					var img = new Image();
					img.onload = function(e) {
						Pixastic.process(img, "desaturate", {average : false});
					}
					
					jQuery(img).addClass('gg_photo gg_grayscale_fx gg_fx_canvas');
					jQuery(this).before(img);
					
					if(navigator.appVersion.indexOf("MSIE 9.") != -1 || navigator.appVersion.indexOf("MSIE 10.") != -1) {	
						if(jQuery(this).parents('.gg_img').hasClass('gg_car_item')) {
							jQuery(this).parents('.gg_img').find('.gg_fx_canvas').css('width', width_arr[i]);
						}
						else {
							jQuery(this).parents('.gg_img').find('.gg_fx_canvas').css('max-width', width_arr[i]).css('max-height', height_arr[i]);
							
							if( jQuery(this).parents('.gg_gallery_wrap').hasClass('gg_collection_wrap') ) {
								jQuery(this).parents('.gg_img').find('.gg_fx_canvas').css('min-width', width_arr[i]).css('min-height', height_arr[i]);	
							}
						}
					}
					
					img.src = jQuery(this).attr('src');			
				}
			});
			
			// mouse hover opacity
			jQuery('#'+gid).delegate('.gg_img','mouseenter touchstart', function(e) {
				if(!gg_is_old_IE()) {
					jQuery(this).find('.gg_grayscale_fx').stop().animate({opacity: 0}, fx_timing);
				} else {
					jQuery(this).find('.gg_grayscale_fx').stop().fadeOut(fx_timing);	
				}
			}).
			delegate('.gg_img','mouseleave touchend', function(e) {
				if(!gg_is_old_IE()) {
					jQuery(this).find('.gg_grayscale_fx').stop().animate({opacity: 1}, fx_timing);
				} else {
					jQuery(this).find('.gg_grayscale_fx').stop().fadeIn(fx_timing);	
				}
			});
		}
		
		if( typeof(jQuery('#'+gid).attr('ggom_fx')) != 'undefined' && jQuery('#'+gid).attr('ggom_fx').indexOf('blur') != -1 ) {
			
			// create and append blurred image
			jQuery('#'+gid+' .gg_main_thumb').each(function(i, v) {
				if( jQuery(this).parents('.gg_img').find('.gg_fx_canvas.gg_blur_fx ').length == 0 ) {
					var img = new Image();
					img.onload = function() {
						Pixastic.process(img, "blurfast", {amount:0.2});
					}
	
					jQuery(img).addClass('gg_photo gg_blur_fx gg_fx_canvas').attr('style', 'opacity: 0; filter: alpha(opacity=0);');
					jQuery(this).parents('.gg_img').find('.gg_main_img_wrap').prepend(img);

					if(navigator.appVersion.indexOf("MSIE 9.") != -1 || navigator.appVersion.indexOf("MSIE 10.") != -1) {	
						if(jQuery(this).parents('.gg_img').hasClass('gg_car_item')) {
							jQuery(this).parents('.gg_img').find('.gg_fx_canvas').css('width', width_arr[i]);
						}
						else {
							jQuery(this).parents('.gg_img').find('.gg_fx_canvas').css('max-width', width_arr[i]).css('max-height', height_arr[i]);
							
							if( jQuery(this).parents('.gg_gallery_wrap').hasClass('gg_collection_wrap') ) {
								jQuery(this).parents('.gg_img').find('.gg_fx_canvas').css('min-width', width_arr[i]).css('min-height', height_arr[i]);	
							}
						}
					}
					
					img.src = jQuery(this).attr('src');
				}
			});
			
			
			// mouse hover opacity
			jQuery('#'+gid).delegate('.gg_img','mouseenter touchstart', function(e) {
				if(!gg_is_old_IE()) {
					jQuery(this).find('.gg_blur_fx').stop().animate({opacity: 1}, fx_timing);
				} else {
					jQuery(this).find('.gg_blur_fx').stop().fadeIn(fx_timing);	
				}
			}).
			delegate('.gg_img','mouseleave touchend', function(e) {
				if(!gg_is_old_IE()) {
					jQuery(this).find('.gg_blur_fx').stop().animate({opacity: 0}, fx_timing);
				} else {
					jQuery(this).find('.gg_blur_fx').stop().fadeOut(fx_timing);	
				}
			});	
		}
	}
	
	
	/////////////////////////////////////

	
	// touch devices hover effects
	if( gg_is_touch_device() ) {
		jQuery('.gg_img').bind('touchstart', function() { jQuery(this).addClass('gg_touch_on'); });
		jQuery('.gg_img').bind('touchend', function() { jQuery(this).removeClass('gg_touch_on'); });
	}
	
	// check for touch device
	function gg_is_touch_device() {
		return !!('ontouchstart' in window);
	}
	
	
	/////////////////////////////////////
	// galleria slider functions
	
	// manage the slider initial appearance
	gg_galleria_show = function(sid) {
		setTimeout(function() {
			if( jQuery(sid+' .galleria-stage').length > 0) {
				jQuery(sid).removeClass('gg_show_loader');
				jQuery(sid+' .galleria-container').fadeTo(200, 1);	
			} else {
				gg_galleria_show(sid);	
			}
		}, 50);
	}
	
	
	// manage the slider proportions on resize
	gg_galleria_height = function(sid) {
		if( jQuery(sid).hasClass('gg_galleria_responsive')) {
			return parseFloat( jQuery(sid).attr('asp-ratio') );
		} else {
			return jQuery(sid).height();	
		}
	}
	
	
	// Initialize Galleria
	gg_galleria_init = function(sid) {
		// autoplay flag
		var spec_autop = jQuery(sid).attr('gg-autoplay');
		var sl_autoplay = ((gg_galleria_autoplay && spec_autop != '0') || (spec_autop == '1')) ? true : false;

		// init
		Galleria.run(sid, {
			theme: 'ggallery', 
			height: gg_galleria_height(sid),
			fullscreenDoubleTap: false,
			wait: true,
			debug: false,
			extend: function() {
				var gg_slider_gall = this;
				jQuery(sid+' .galleria-loader').append(gg_loader);
				
				if(sl_autoplay) {
					jQuery(sid+' .galleria-gg-play').addClass('galleria-gg-pause')
					gg_slider_gall.play(gg_galleria_interval);	
				}
				
				// play-pause
				jQuery(sid+' .galleria-gg-play').click(function() {
					jQuery(this).toggleClass('galleria-gg-pause');
					gg_slider_gall.playToggle(gg_galleria_interval);
				});
				
				// pause slider on lightbox click
				jQuery(sid+' .galleria-gg-lightbox').click(function() {
					// get the slider offset
					jQuery(sid+' .galleria-thumbnails > div').each(function(k, v) {
                       if( jQuery(this).hasClass('active') ) {gg_active_index = k;} 
                    });
					
					jQuery(sid+' .galleria-gg-play').removeClass('galleria-gg-pause');
					gg_slider_gall.pause();
				});
				
				// thumbs navigator toggle
				jQuery(sid+' .galleria-gg-toggle-thumb').click(function() {
					var $gg_slider_wrap = jQuery(this).parents('.gg_galleria_slider_wrap');
					var thumb_h = jQuery(this).parents('.gg_galleria_slider_wrap').find('.galleria-thumbnails-container').height();
					
					if( $gg_slider_wrap.hasClass('galleria-gg-show-thumbs') || $gg_slider_wrap.hasClass('gg_galleria_slider_show_thumbs') ) {
						$gg_slider_wrap.stop().animate({'padding-bottom' : '15px'}, 400);
						$gg_slider_wrap.find('.galleria-thumbnails-container').stop().animate({'bottom' : '20px', 'opacity' : 0}, 400);
						
						$gg_slider_wrap.removeClass('galleria-gg-show-thumbs');
						if( $gg_slider_wrap.hasClass('gg_galleria_slider_show_thumbs') ) {
							$gg_slider_wrap.removeClass('gg_galleria_slider_show_thumbs')
						}
					} 
					else {
						$gg_slider_wrap.stop().animate({'padding-bottom' : (thumb_h + 2 + 12)}, 400);
						$gg_slider_wrap.find('.galleria-thumbnails-container').stop().animate({'bottom' : '-'+ (thumb_h + 2 + 10) +'px', 'opacity' : 1}, 400);	
						
						$gg_slider_wrap.addClass('galleria-gg-show-thumbs');
					}
				});
			}
		});
	}
	
	
	/////////////////////////////////////
	// Slick carousel functions
	
	/* preload visible images */
	gg_carousel_preload = function(gid, autoplay) {
		jQuery('#gg_car_'+gid).prepend(gg_loader);
		
		// apply effects
		if( !jQuery('#gg_car_'+gid+' .gg_grayscale_fx').length && !jQuery('#gg_car_'+gid+' .gg_blur_fx').length ) {
			jQuery('#gg_car_'+gid+' img').lcweb_lazyload({
				allLoaded: function(url_arr, width_arr, height_arr) {
					var true_h =  jQuery('#gg_car_'+gid+' .gg_img_inner').height();
					
					// old IE fix - find true width related to height
					if(navigator.appVersion.indexOf("MSIE 9.") != -1 || navigator.appVersion.indexOf("MSIE 8.") != -1) {
						jQuery.each(width_arr, function(i, v) {
							width_arr[i] = (width_arr[i] * true_h) / height_arr[i];
							height_arr[i] = true_h;
						});	
					}
					
					gg_img_fx_setup('gg_car_'+gid, width_arr, height_arr);
				}
			});
			var wait_for_fx = true;
		}
		else {var wait_for_fx = false;}
		
		var shown_first = (wait_for_fx) ? '' : '.slick-active';
		jQuery('#gg_car_'+gid+' '+ shown_first +' img').lcweb_lazyload({
			allLoaded: function(url_arr, width_arr, height_arr) {
				jQuery('#gg_car_'+gid+' .gg_loader').fadeOut(200, function() {
					jQuery(this).remove();
				});
				jQuery('#gg_car_'+gid).removeClass('gg_car_preload');
				
				if(autoplay) {
					jQuery('#gg_car_'+gid).slick('slickPlay');	
				}
				
				
				// wait and show
				var delay = (wait_for_fx) ? 1200 : 320;
				setTimeout(function() {
					gg_car_center_images(gid);
				}, delay);
			}
		});
	}
	
	
	var gg_car_center_images = function(subj_id) {
		var subj_sel = (typeof(subj_id) == 'undefined') ? '' : '#gg_car_'+subj_id;
		
		jQuery(subj_sel + ' .gg_img.gg_car_item').each(function(i,v) {
			var $img = jQuery(this);
			var $elements = $img.find('.gg_main_img_wrap > *');

			var wrap_w = jQuery(this).width();
			var wrap_h = jQuery(this).height(); 
			
			
			jQuery('<img />').bind("load",function(){ 
				var ratio = Math.max(wrap_w / this.width, wrap_h / this.height);
				var new_w = this.width * ratio;
				var new_h = this.height * ratio;
				
				var margin_top = Math.ceil( (wrap_h - new_h) / 2);
				var margin_left = Math.ceil( (wrap_w - new_w) / 2);
				
				if(margin_top > 0) {margin_top = 0;}
				if(margin_left > 0) {margin_left = 0;}
				
				$elements.css('width', new_w).css('height', new_h);
				
				// mark to be shown
				$img.addClass('gg_car_img_ready'); 	
				
			}).attr('src',  $img.find('.gg_main_thumb').attr('src'));

        });
	}
	
	
	jQuery(document).ready(function(e) {
		
		/* pause on hover fix */
        jQuery(document.body).delegate('.gg_car_pause_on_h','mouseenter touchstart', function(e) {			
			jQuery(this).slick('slickPause');
		}).
		delegate('.gg_car_pause_on_h','mouseleave touchend', function(e) {
			jQuery(this).slick('slickPlay');
		});	
		
		/* pause on lightbox open */
		jQuery('.gg_carousel_wrap').delegate('.gg_img:not(.gg_linked_img)', 'click tap', function(e) {			
			var $subj = jQuery(this);
			setTimeout(function() {
				$subj.parents('.gg_carousel_wrap').slick('slickPause');
			}, 150);
		});
    });	
	
	

	/////////////////////////////////////
	// debouncers
	
	gg_debouncer = function($,cf,of, interval){
		var debounce = function (func, threshold, execAsap) {
			var timeout;
			
			return function debounced () {
				var obj = this, args = arguments;
				function delayed () {
					if (!execAsap) {func.apply(obj, args);}
					timeout = null;
				}
			
				if (timeout) {clearTimeout(timeout);}
				else if (execAsap) {func.apply(obj, args);}
				
				timeout = setTimeout(delayed, threshold || interval);
			};
		};
		jQuery.fn[cf] = function(fn){ return fn ? this.bind(of, debounce(fn)) : this.trigger(cf); };
	};
	
	
	// bind resize to trigger only once event
	gg_debouncer(jQuery,'gg_smartresize', 'resize', 49);
	jQuery(window).gg_smartresize(function() {
		
		// resize galleria slider
		jQuery('.gg_galleria_responsive').each(function() {	
			var slider_w = jQuery(this).width();
			var gg_asp_ratio = parseFloat(jQuery(this).attr('asp-ratio'));
			var new_h = Math.ceil( slider_w * gg_asp_ratio );
			jQuery(this).css('height', new_h);
		});
	});
	
	
	// bind scroll to keep "back to gallery" button visible
	gg_debouncer(jQuery,'gg_smartscroll', 'scroll', 50);
	jQuery(window).gg_smartscroll(function() {
		gg_keep_back_to_gall_visible();
	});
	
	var gg_keep_back_to_gall_visible = function() {
		if( jQuery('.gg_coll_back_to_new_style').length > 0 && typeof(gg_back_to_gall_scroll) != 'undefined' && gg_back_to_gall_scroll) {
			jQuery('.gg_coll_gallery_container .gg_gallery_wrap').each(function(i, v) {
         		var gall_h = jQuery(this).height();
				var $btn = jQuery(this).parents('.gg_coll_gallery_container').find('.gg_coll_go_back');
				
				if(gall_h > jQuery(window).height()) {
					
					var offset = jQuery(this).offset();
					if( jQuery(window).scrollTop() > offset.top && jQuery(window).scrollTop() < (offset.top + gall_h - 60)) {
						var top = Math.round( jQuery(window).scrollTop() - offset.top) + 55;
						if(top < 0) {top = 0;}
						
						$btn.css('top', top);	
					}
					else {$btn.css('top', 0);}
				}
				else {$btn.css('top', 0);}
			       
            });
		}
	}
	
	
	// persistent check for galleries collections size change 
	jQuery(document).ready(function() {
		setInterval(function() {
			jQuery('.gg_gallery_wrap').each(function() {
				var gid = jQuery(this).attr('id');
				if(typeof(gg_shown_gall[gid]) == 'undefined') {return true;} // only for shown galleries

				var new_w = (jQuery(this).hasClass('gg_collection_wrap')) ? jQuery('#'+gid+' .gg_coll_container').width() : jQuery('#'+gid).width();
				
				if(typeof(gg_gallery_w[gid]) == 'undefined') {
					gg_gallery_w[gid] = new_w;	
					return true;
				}
				
				// trigger only if size is different
				if(gg_gallery_w[gid] != new_w) {
					persistent_resize_debounce(gid);
					gg_gallery_w[gid] = new_w;
				}
			});
		}, 200);
	});
	
	var persistent_resize_debounce = function(gall_id) {
		if(typeof(gg_debounce_resize[gall_id]) != 'undefined') {clearTimeout(gg_debounce_resize[gall_id]);}
		
		gg_debounce_resize[gall_id] = setTimeout(function() {	
			jQuery('#'+gall_id).trigger('gg_resize_gallery', [gall_id]);	
		}, 50);
	}
	
	// standard GG operations
	jQuery(document).delegate('.gg_gallery_wrap', 'gg_resize_gallery', function(evt, gall_id) {
		
		// collection galleries title check 	
		if(jQuery(this).hasClass('gg_collection_wrap') && jQuery(this).find('.gg_coll_gallery_container .gg_container').length) {
			gg_coll_gall_title_layout(gall_id); 
		} 
		
		
		// whether to trigger only carousel resizing
		if(jQuery(this).hasClass('gg_carousel_wrap')) {
			 gg_car_center_images(gall_id); // carousel images sizing	
		}
		else {
			gg_galleries_init(gall_id, true); // rebuilt galleries on resize	
		}
	});
	
	
	
	/////////////////////////////////////////////////////
	// check if the browser is IE8 or older
	function gg_is_old_IE() {
		if( navigator.appVersion.indexOf("MSIE 7.") != -1 || navigator.appVersion.indexOf("MSIE 8.") != -1 ) {return true;}
		else {return false;}	
	}
})(jQuery);


/////////////////////////////////////
// Image preloader v1.01
(function($) {	
	$.fn.lcweb_lazyload = function(lzl_callbacks) {
		lzl_callbacks = jQuery.extend({
			oneLoaded: function() {},
			allLoaded: function() {}
		}, lzl_callbacks);

		var lzl_loaded = 0, 
			lzl_url_array = [], 
			lzl_width_array = [], 
			lzl_height_array = [], 
			lzl_img_obj = this;
		
		var check_complete = function() {
			if(lzl_url_array.length == lzl_loaded) {
				lzl_callbacks.allLoaded.call(this, lzl_url_array, lzl_width_array, lzl_height_array); 
			}


		}

		var lzl_load = function() {
			jQuery.map(lzl_img_obj, function(n, i){
                lzl_url_array.push( $(n).attr('src') );
            });
			
			jQuery.each(lzl_url_array, function(i, v) {
				if( jQuery.trim(v) == '' ) {console.log('empty img url - ' + (i+1) );}
				
				$('<img />').bind("load.lcweb_lazyload",function(){ 
					if(this.width == 0 || this.height == 0) {
						setTimeout(function() {
							lzl_width_array[i] = this.width;
							lzl_height_array[i] = this.height;
							
							lzl_loaded++;
							check_complete();
						}, 70);
					}
					else {
						lzl_width_array[i] = this.width;
						lzl_height_array[i] = this.height;
						
						lzl_loaded++;
						check_complete();
					}
				}).attr('src',  v);
			});
		}
		
		return lzl_load();
	}; 
	
})(jQuery);