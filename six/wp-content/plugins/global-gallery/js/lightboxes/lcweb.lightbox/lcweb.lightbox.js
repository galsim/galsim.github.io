/* ------------------------------------------------------------------------
	Class: LCweb Lightbox
	Author: Luca Montanari (http://www.lcweb.it)
	Version: 2.02
------------------------------------------------------------------------- */

(function($) {
	$.lcweb_lightbox = {version: '1.32'};

	$.fn.lcweb_lightbox = function(lcl_settings) {
		
		lcl_settings = jQuery.extend({
			hook: 'rel', // attribute to group the elements 
			
			url_src: 'href', // attribute containing the title
			title_src: 'title', // attribute containing the title
			author_src: 'lcl-author', // attribute containing the author
			descr_src: 'alt', // attribute containing the description
			
			animation_time: 250, // animation timing in millisecods / 1000 = 1sec
			slideshow_time: 6000, // interval time of the slideshow in milliseconds / 1000 = 1sec
			autoplay: false, /* autoplay slideshow / true/false */
			
			ol_opacity: 0.70, // overlay opacity / value between 0 and 1
			ol_color: '#000', // background color of the overlay
			ol_pattern: 'oblique_dots', // overlay patterns - insert the pattern name or false
			border_w: 5, // width of the lightbox border in pixels 
			border_col: '#555', // color of the lightbox border   
			padding: 4, // width of the lightbox padding in pixels
			radius: 12, // corner radius of the lightbox in pixels (not for IE < 9) 
			
			style: 'light', // light / dark
			data_position: 'over', // over / under / lside / rside
				
			show_title: true, // true/false
			show_descr: true, // true/false
			show_author: true, // true/false
			
			thumb_nav: true, // enable the thumbnail navigation for image galleries
			thumbs_maker_url: false, // script baseurl to create thumbnails (use src=%URL% w=%W% h=%H%)
			thumb_w: 120, // width of the thumbs for the standard lightbox
			thumb_h: 85, // height of the thumbs for the standard lightbox
			
			fullscreen: true, // Allow the user to expand a resized image. true/false
			fs_img_behaviour: 'smart', // resize mode of the fullscreen image - none/smart/fit/fill
			fs_only: 'always', // open directly in fullscreen (only for image galleries) - none/mobile/always
			
			socials: true, // bool
			fb_share_fix: 'http://www.lcweb.it/lcis_fb_img_fix.php', // script to get rid of the facebook block on facebook CDN images
			touchSwipe: true, // bool / Allow touchSwipe interactions for mobile
			modal: false, // enable modal mode (no closing on overlay click)
			
			max_width: '70%', // insert a percent value or an int for pixel value
			max_height: '90%', // insert a percent value or an int for pixel value

			open: false, // open the lightbox immediately 
			from_index: 0, // start index for automatic openings
			manual_hook: false, // hook value for automatic openings
			
			base_code: 
			'<div id="lcl_wrapper">'+
				'<div id="lcl_standard" class="lcl_standard_closed">'+
					'<div id="lcl_standard_cmd">'+
						'<table><tr>'+
						  '<td>'+
							'<div class="lcl_prev"></div>'+
							'<div class="lcl_autoplay lcl_pause"></div>'+
							'<div class="lcl_next"></div>'+
						  '</td>'+
						  '<td>'+
						  	'<div class="lcl_close"></div>'+
							'<div class="lcl_fullscreen"></div>'+
							'<div class="lcl_socials"></div>'+
						  '</td>'+
						'</tr></table>'+
					'</div>'+
					'<div id="lcl_standard_elem"></div>'+
					'<div id="lcl_standard_txt">'+
						'<h3>'+
							'<span id="lcl_standard_title"></span>'+
							'<span id="lcl_standard_author"></span>'+
						'</h3>'+
						'<div id="lcl_standard_descr"></div>'+
					'</div>'+
				'</div>'+
				'<div id="lcl_fullscreen" style="display: none;">'+
					'<div id="lcl_fs_cmd">'+
						'<section>'+
							'<div class="lcl_prev"><span></span></div>'+
							'<div class="lcl_autoplay lcl_pause"><span></span></div>'+
							'<div class="lcl_next"><span></span></div>'+
						'</section>'+	
						'<div class="lcl_close"></div>'+
						'<div class="lcl_socials"></div>'+
						'<div class="lcl_toggle_info"></div>'+
					'</div>'+
					'<div id="lcl_fs_elem"></div>'+
					'<div id="lcl_fs_txt">'+
						'<h3>'+
							'<span id="lcl_fs_title"></span>'+
							'<span id="lcl_fs_author"></span>'+
						'</h3>'+
						'<div id="lcl_fs_descr"></div>'+
					'</div>'+
				'</div>'+
				'<div id="lcl_thumb_nav" style="display: none;"></div>'+
				'<div id="lcl_loader" style="display: none;"></div>'+
				'<div id="lcl_overlay" style="display: none;"></div>'+
			'</div>'
		}, lcl_settings);
				
		// Global variables accessible only by the plugin
		var lcl_open, lcl_fullscreen_open, lcl_fs_txt = true, lcl_is_active = false, lcl_slideshow, lcl_txt_exists, lcl_txt_h, lcl_managed_img_h, lcl_count_txt_under, lcl_html_style, lcl_body_style,
		
		// elements variables
		lcl_elem_obj = this, lcl_tot_elements, lcl_elem_index, lcl_elem_url, lcl_elem_title, lcl_elem_author, lcl_elem_descr, lcl_curr_elem_w, lcl_curr_elem_h, lcl_is_img_gallery,

		// thumbnail navigator variables 
		lcl_thumb_per_page, lcl_curr_thumb_pag, lcl_thumb_pag_w, lcl_scrolling_thumb = false;

		// Window sizes
		lcl_window_h = $(window).height(); lcl_window_w = $(window).width();


		/**
		* Initialize and open the lightbox
		*/
		$.lcweb_lightbox.init = function() {
			settings = lcl_settings;

			lcl_elem_url = $.makeArray();
			lcl_elem_title = $.makeArray();
			lcl_elem_author = $.makeArray();
			lcl_elem_descr = $.makeArray();
			
			// find the hook to group images
			if(!settings.open) { var hook_val = $(this).attr(settings.hook); }
			else { var hook_val = settings.manual_hook; }

			// get the images and put the data into arrays
			jQuery.map(lcl_elem_obj, function(n, i){
				if( (!hook_val || typeof(hook_val) == 'undefined') || $(n).attr(settings.hook) == hook_val ) {
					lcl_elem_url.push( $(n).attr(settings.url_src) );
					lcl_elem_title.push( $(n).attr(settings.title_src) );
					lcl_elem_author.push( $(n).attr(settings.author_src) );
					lcl_elem_descr.push( $(n).attr(settings.descr_src) );
				}
            });
			
			// collection infos
			lcl_tot_elements = lcl_elem_url.length;
			
			if(!settings.open) {
				lcl_elem_index = jQuery.inArray( $(this).attr(settings.url_src), lcl_elem_url);
			}
			else {lcl_elem_index = settings.from_index;}
			
			// nav buttons manage
			if(lcl_elem_index == 0) { 
				$('#lcl_standard_cmd .lcl_prev, #lcl_fullscreen_cmd .lcl_prev').addClass('lcl_nav_disabled'); 
			}
			else if(lcl_elem_index == (lcl_tot_elements - 1)) { 
				$('#lcl_standard_cmd .lcl_next, #lcl_fullscreen_cmd .lcl_next').addClass('lcl_nav_disabled'); 
			}
			
			if(lcl_tot_elements == 1) {
				$('#lcl_standard_cmd .lcl_prev, #lcl_fullscreen_cmd .lcl_prev, #lcl_standard_cmd .lcl_next, #lcl_fullscreen_cmd .lcl_next').hide(); 	
			}

			// disable thumbs nav if there's only one image
			if(lcl_tot_elements == 1) {
				settings.thumb_nav = false;
			}

			// build the lightbox layout and show overlay
			_build_layout(); 
			_first_load();

			// open
			$.lcweb_lightbox.open();
			return false;
		}
	
	
		/**
		* Open the lightbox
		*/	
		$.lcweb_lightbox.open = function(custom) {
			var settings = lcl_settings;
			lcl_is_active = true;
			
			if(typeof(custom) != 'undefined') {
				// CUSTOM OPENING - TODO
			}
			else {	
				// wait content to be loaded and show
				if( _elem_type() == 'image'){
					$('<img />').bind("load",function(){ 
						lcl_curr_elem_w = this.width;
						lcl_curr_elem_h = this.height;
	
						if(!lcl_fullscreen_open) { _adjust_and_show(); }
						else { _manage_fs_img(); }
					}).attr('src', lcl_elem_url[lcl_elem_index]);
					
					_populate_lightbox();
					
					// Preload next and prev images
					if(lcl_tot_elements > 1 && lcl_elem_index < lcl_tot_elements - 1 && _elem_type(lcl_elem_url[lcl_elem_index + 1]) == 'image') {
						$('<img />').bind("load").attr('src', lcl_elem_url[lcl_elem_index + 1]);
					}
					if(lcl_tot_elements > 1 && lcl_elem_index > 0 && _elem_type(lcl_elem_url[lcl_elem_index - 1]) == 'image') {
						$('<img />').bind("load").attr('src', lcl_elem_url[lcl_elem_index - 1]);
					}
				}

				else { 
					// TODO get element sizes
					_adjust_and_show();
				}
				
				lcl_open = true;
			}
			
			setTimeout(function() {
				lcl_is_active = false;
			}, settings.animation_time);
		}
		
		
		/**
		* Switch to fullscreen layout
		*/	
		$.lcweb_lightbox.open_fullscreen = function() {
			if(!_is_images_gallery()) { return false;}
			var settings = lcl_settings;

			$('#lcl_standard').fadeOut();
			$('#lcl_loader').fadeIn();
			
			if(settings.thumb_nav && _is_images_gallery()) {
				$('#lcl_thumb_nav > div').css('opacity', 1);
				$('#lcl_thumb_nav').fadeIn(400);
			} else {
				$('#lcl_thumb_nav').css('opacity', 0).hide();
			}
			
			lcl_fullscreen_open = true;

			// check the description box visibility
			if($('#lcl_standard_txt').is(':hidden')) { $('#lcl_fs_txt, .lcl_toggle_info').hide(); } 
			
			// delay and process
			setTimeout(function() {
				$('#lcl_loader').fadeOut();
				$('#lcl_fullscreen').fadeIn(function() {
					$.lcweb_lightbox.open();
				});
			}, 300);
		}
		
		
		/**
		* Change the lightbox page
		*/	
		$.lcweb_lightbox.change_page = function(direction) {
			if( !lcl_is_active ) {
				lcl_is_active = true;

				if(direction == 'prev') {
					lcl_elem_index--;
					if (lcl_elem_index < 0) { lcl_elem_index = lcl_tot_elements - 1; }
				}
				else if(direction == 'next'){
					lcl_elem_index++;
					if (lcl_elem_index > (lcl_tot_elements - 1)) { lcl_elem_index = 0; }
				}
				else{
					lcl_elem_index = parseInt(direction); // for jump-to-content
				};
				
				if(!lcl_fullscreen_open) {
					$('#lcl_standard > *').clearQueue().fadeTo((settings.animation_time - 30), 0);
					
					setTimeout(function() {
						$('#lcl_standard_elem').css('width', $('#lcl_standard_elem').width()).css('height', $('#lcl_standard_elem').height()).addClass('lcl_pag_anim');
						
						$('#lcl_standard').removeClass('lcl_no_loader');
						$('#lcl_standard_elem').empty();
						$.lcweb_lightbox.open();
						
					}, (settings.animation_time + 30)); 
				}
				else {
					$('#lcl_fs_elem').removeClass('lcl_no_loader');
					$('#lcl_fs_elem > img, #lcl_fill_fs_img').clearQueue().fadeOut((settings.animation_time - 30));
					$('#lcl_fs_txt').clearQueue().fadeTo((settings.animation_time - 30), 0); 	
					
					setTimeout(function() {
						$('#lcl_fs_elem').empty();
						$.lcweb_lightbox.open();
						
					}, (settings.animation_time + 30));
					
					_manage_thumb_nav();
				}
			}
		}

		
		/**
		* Close the lightbox
		*/	
		$.lcweb_lightbox.close = function() {
			var settings = lcl_settings;
			lcl_open = false;
			lcl_fullscreen_open = false;
			
			$('#lcl_wrapper *').stop();
			
			// restore html/body inline CSS
			if(typeof(lcl_html_style) != 'undefined') {jQuery('html').attr('style', lcl_html_style);}
			else {jQuery('html').removeAttr('style');}
			
			if(typeof(lcl_body_style) != 'undefined') {jQuery('body').attr('style', lcl_body_style);}
			else {jQuery('body').removeAttr('style');}
			
			
			$('#lcl_wrapper').fadeOut(function() {
				$(this).remove();
			});
		}
		
		
		/**
		* Reload lightbox elements
		*/	
		$.lcweb_lightbox.reload = function(selector) {
			$(selector).unbind('click.lcweb_lightbox').bind('click.lcweb_lightbox', $.lcweb_lightbox.init );
			lcl_elem_obj = $(selector);
			return false;
		}
		
		
		/**
		* Start the image slideshow
		*/
		$.lcweb_lightbox.start_slideshow = function(){
			var settings = lcl_settings;
			
			clearInterval(lcl_slideshow);
			lcl_slideshow = setInterval(function() {
				$.lcweb_lightbox.change_page('next');
			}, (settings.slideshow_time + settings.animation_time));
			
			$('.lcl_autoplay').removeClass('lcl_play').addClass('lcl_pause');
		}


		/**
		* Stop slideshow
		*/
		$.lcweb_lightbox.stop_slideshow = function(){
			$('.lcl_autoplay').removeClass('lcl_pause').addClass('lcl_play');
			clearInterval(lcl_slideshow);
			lcl_slideshow = false;
		}
	

		////////////////////////////////////////////////
		
		// show the overlay and the loader for the first use
		function _first_load() {
			// set slideshow variable
			if(_is_images_gallery()) {lcl_slideshow = true;}
			else {lcl_slideshow = false;}
			
			$('#lcl_overlay, #lcl_loader').fadeIn();
			
			// backup html/body inline CSS
			lcl_html_style = jQuery('html').attr('style');
			lcl_body_style = jQuery('body').attr('style');
			
			// avoid page scrolling
			jQuery('html').css('overflow', 'hidden');
			jQuery('body').css('overflow', 'visible');
			
			lcl_window_h = $(window).height(); 
			lcl_window_w = $(window).width();
		}
	
		
		// build the lightbox layout
		function _build_layout() {
			if( $('#lcl_wrapper').length > 0 ) { $('#lcl_wrapper').remove(); }
			$('body').append( settings.base_code );

			// set maximum elastic width
			$('#lcl_standard').css('max-width', settings.max_width).css('max-height', settings.max_height);		
				
			// show/hide navigation buttons if only one image
			if(lcl_tot_elements > 1) {
				$('.lcl_prev, .lcl_autoplay, .lcl_next').show();
				$('#lcl_fs_txt').css('top', 65);
			} else {
				$('.lcl_prev, .lcl_autoplay, .lcl_next').hide();
				$('#lcl_fs_txt').css('top', 20);	
			}

			// data position
			var data_layout_class = 'lcl_data_' + settings.data_position;
			$('#lcl_standard').addClass( data_layout_class );
			
			// commands
			if(!settings.fullscreen) { $('#lcl_standard_cmd .lcl_fullscreen').hide(); }
			if(!settings.socials) { $('#lcl_standard_cmd .lcl_socials, #lcl_fs_cmd .lcl_socials').hide(); }
			
			if(settings.autoplay) { $('#lcl_standard_cmd .lcl_autoplay, #lcl_fs_cmd .lcl_autoplay').removeClass('lcl_play').addClass('lcl_pause'); }
			else { $('#lcl_standard_cmd .lcl_autoplay, #lcl_fs_cmd .lcl_autoplay').removeClass('lcl_pause').addClass('lcl_play'); }
			
			// standard command - left/right positio in relation with padding
			$('#lcl_standard_cmd').css('left', settings.padding).css('right', settings.padding);
			
			// style
			$('#lcl_loader, #lcl_standard, #lcl_fullscreen, #lcl_thumb_nav').addClass('lcl_' + settings.style);
			
			// overlay
			$('#lcl_overlay').css('background-color', settings.ol_color).fadeTo(0, settings.ol_opacity);
			if(settings.ol_pattern) { $('#lcl_overlay').addClass('lcl_pattern_' + settings.ol_pattern); }
			
			// standard lightbox - colors / padding / borders
			$('#lcl_standard')
				.css('padding', settings.padding)
				.css('border-width', settings.border_w)
				.css('border-color', settings.border_col)
				.css('border-radius', settings.radius);
			
			// data visibility
			if( !settings.show_title ) { $('#lcl_standard_title, #lcl_fs_title').hide(); }
			if( !settings.show_descr ) { $('#lcl_standard_descr, #lcl_fs_descr').hide(); }
			if( !settings.show_author ) { $('#lcl_standard_author, #lcl_fs_author').hide(); }
			
			// touch devices corrections
			if( _is_touch_device() ) {
				$('#lcl_wrapper').bind('touchstart.lcweb_lightbox', function() {
					$('#lcl_standard_cmd table div, #lcl_fs_cmd div, #lcl_thumb_nav > div').css('opacity', 1);
				});
				$('#lcl_wrapper').bind('touchend.lcweb_lightbox', function() {
					$('#lcl_standard_cmd table div, #lcl_fs_cmd div, #lcl_thumb_nav > div').css('opacity', 0.4);
				});
			}
		}


		// populate the lightbox with the proper content
		function _populate_lightbox() {
			var curr_elem_type = _elem_type();

			switch(curr_elem_type) {
			  case 'image' :
				  $('#lcl_standard_elem').append('<img src="'+ lcl_elem_url[lcl_elem_index] +'" />'); 
				  $('#lcl_fs_elem').html('<img src="'+ lcl_elem_url[lcl_elem_index] +'" />'); 
				  break;
			}
			
			// title
			if( settings.show_title && $.trim(lcl_elem_title[lcl_elem_index]) != '' ) { $('#lcl_standard_title, #lcl_fs_title').html( lcl_elem_title[lcl_elem_index] ); }
			else { $('#lcl_standard_title, #lcl_fs_title').empty(); }
			
			// description
			if( settings.show_descr && $.trim(lcl_elem_descr[lcl_elem_index]) != '' ) { $('#lcl_standard_descr, #lcl_fs_descr').html( lcl_elem_descr[lcl_elem_index] ); }
			else { $('#lcl_standard_descr, #lcl_fs_descr').empty(); }
			
			// author
			if( settings.show_author && $.trim(lcl_elem_author[lcl_elem_index]) != '') { $('#lcl_standard_author, #lcl_fs_author').html('by ' + lcl_elem_author[lcl_elem_index] ); }
			else { $('#lcl_standard_author, #lcl_fs_author').empty(); }	
			
			// if everything is hidden or doesn't exist any txt, hide the txt container
			if( 
				(!settings.show_title && !settings.show_descr && !settings.show_author) || 
				($.trim(lcl_elem_title[lcl_elem_index]) == '' && $.trim(lcl_elem_descr[lcl_elem_index]) == '' && $.trim(lcl_elem_author[lcl_elem_index]) == '')	
			) {
				$('#lcl_standard_txt, #lcl_fs_txt').hide();	
				$('#lcl_fs_txt').addClass('lcl_fs_txt_hidden');
				$('.lcl_toggle_info').fadeOut('fast');
				lcl_txt_exists = false;
			}
			else { 
				$('#lcl_standard_txt').show();	
				$('#lcl_fs_txt').removeClass('lcl_fs_txt_hidden');
				
				(lcl_fs_txt) ? $('#lcl_fs_txt').show() : $('#lcl_fs_txt').hide();
				$('.lcl_toggle_info').fadeIn('fast');
				
				lcl_txt_exists = true; 
			}
			
			// if text over - adjust position with padding
			if((settings.data_position == 'over' || settings.data_position == 'under') && lcl_txt_exists) {
				jQuery('#lcl_standard_txt').css('left', settings.padding).css('right', settings.padding).css('bottom', settings.padding);
			}
			
			// if social enabled, prepare and append
			if(settings.socials) {
				var sanitized_title = encodeURIComponent( $.trim( lcl_elem_title[lcl_elem_index])).replace(/'/gi,"\\'");
				var sanitized_descr = encodeURIComponent( $.trim( lcl_elem_descr[lcl_elem_index])).replace(/'/gi,"\\'");
				
				var social_code = _social_code(
					encodeURIComponent(lcl_elem_url[lcl_elem_index]), 
					sanitized_title, 
					sanitized_descr, 
					encodeURIComponent(window.location.href)
				);
				var socials_opened = ($('#lcl_standard_cmd .lcl_social_box').is(':visible') || $('#lcl_fs_cmd .lcl_social_box').is(':visible')) ? true : false;
				
				if(!lcl_fullscreen_open) { 
					if( $('#lcl_standard_cmd .lcl_social_box').length > 0  ) { $('#lcl_standard_cmd .lcl_social_box').remove(); }
					$('#lcl_standard_cmd td:last-child').append(social_code); 
					if(socials_opened) { $('#lcl_standard_cmd .lcl_social_box').show(); }
				}
				else { 
					if( $('#lcl_fs_cmd .lcl_social_box').length > 0  ) { $('#lcl_fs_cmd .lcl_social_box').remove(); }
					$('#lcl_fs_cmd').append(social_code); 
					if(socials_opened) { $('#lcl_fs_cmd .lcl_social_box').show(); }
				}	
			}
		}
		
		
		// position the lightbox always on the page center
		function _adjust_and_show() {
			var settings = lcl_settings;

			// thumb nav
			_manage_thumb_nav();
			
			// max sizes according with global settigs
			var add_space = (settings.border_w * 2) + (settings.padding * 2)
			var max_height_px = (lcl_window_h * (parseInt(settings.max_height) / 100)) - parseInt($('#lcl_wrapper').css('padding-bottom')) - parseInt($('#lcl_wrapper').css('padding-top')) - add_space;
			var max_width_px = (lcl_window_w * (parseInt(settings.max_width) / 100)) - parseInt($('#lcl_wrapper').css('padding-left')) - parseInt($('#lcl_wrapper').css('padding-right')) - add_space;
			
			
			// buttons position if tiny image
			if(Math.min(lcl_curr_elem_w, max_width_px) < 280) {
				$('#lcl_wrapper').addClass('lcl_external_cmd');
			} else {
				$('#lcl_wrapper').removeClass('lcl_external_cmd');		
			}

			// manage image height if taller than max
			if(lcl_curr_elem_h > max_height_px) {
				lcl_managed_img_h = true;
				var true_img_h = lcl_curr_elem_h;
				
				// check max width - if is resized
				if(lcl_curr_elem_w > max_width_px) {
					true_img_h = Math.round((lcl_curr_elem_h * max_width_px) / lcl_curr_elem_w);	
				}
				
				// check then against max height
				if(true_img_h > max_height_px) {
					true_img_h = max_height_px;
				}
				
				$('#lcl_standard_elem > img').css('max-height', true_img_h);
			}	
			else {
				lcl_managed_img_h = false;
				$('#lcl_standard_elem > img').css('max-height','none');
				var true_img_h = lcl_curr_elem_h;
			}
			
			// adjust if text under image
			if(settings.data_position == 'under' && lcl_txt_exists) {
				$('#lcl_standard_txt').css('width', $('#lcl_standard_elem > img').width());
				var txt_under_h = _manage_txt_under(max_height_px, true_img_h);
				$('#lcl_standard_txt').css('width', 'auto');
				
				if(lcl_managed_img_h && lcl_count_txt_under) {true_img_h = true_img_h - txt_under_h};
			}

			// if paginating - animate #lcl_standard_elem to be shaped accordingly with new image
			if( !$('#lcl_standard').hasClass('lcl_standard_closed') && $('#lcl_standard_elem').hasClass('lcl_pag_anim')) {
				var new_w = (!lcl_managed_img_h) ? lcl_curr_elem_w : Math.round((lcl_curr_elem_w * true_img_h) / lcl_curr_elem_h);	

				// if txt under - add bottom-padding
				var btm_margin = (typeof(txt_under_h) != 'undefined' && lcl_txt_exists) ? txt_under_h : 0;
				$('#lcl_standard_elem').clearQueue().animate({'width' : new_w, 'height' : true_img_h, marginBottom: btm_margin}, settings.animation_time);

				setTimeout(function() {
					$('#lcl_standard_elem').css('width', '100%').css('height', '100%');
				}, settings.animation_time + 10);
			}
			else {
				// on resize - adjust text height 
				if(!$('#lcl_standard').hasClass('lcl_standard_closed')) {
					$('#lcl_standard_elem').css('width', '100%').css('height', '100%').css('margin-bottom', txt_under_h);	
				}
			}

			///////////////  
			
			// if is first opening
			if( $('#lcl_standard').hasClass('lcl_standard_closed') ) {
		
				// direct fullscreen switch
				if(
					(settings.fullscreen && settings.fs_only == 'always') || 
					(settings.fullscreen && settings.fs_only == 'mobile' && 
					_is_mobile() && navigator.appVersion.indexOf("MSIE") == -1 && 
					(navigator.appVersion.indexOf("WebKit") != -1 && (lcl_window_w < 650 || lcl_window_h < 650))) 
				) {
					// slideshow start
					if(settings.autoplay && typeof(lcl_slideshow) != 'undefined' && lcl_slideshow !== false) {  
						$.lcweb_lightbox.start_slideshow();
					}
					
					$.lcweb_lightbox.open_fullscreen();
					return true;
				}
				else {
					$('#lcl_standard_cmd, #lcl_standard_elem, #lcl_standard_txt, #lcl_thumb_nav').css('opacity', 1);
					
					// if text under - apply bottom padding
					if(typeof(txt_under_h) != 'undefined') {$('#lcl_standard_elem').css('margin-bottom', txt_under_h);}
				}
				
				setTimeout(function() {
					$('#lcl_loader').fadeOut();
					
					// old IE fallback
					if( navigator.appVersion.indexOf("MSIE 8.") != -1 || navigator.appVersion.indexOf("MSIE 9.") != -1 ) {
						$('#lcl_standard').fadeTo(400, 1);
					}
					
					$('#lcl_standard').removeClass('lcl_standard_closed').addClass('lcl_no_loader');
					if(settings.thumb_nav && _is_images_gallery()) { $('#lcl_thumb_nav').fadeIn(400); }
					
					// slideshow start
					if(settings.autoplay && typeof(lcl_slideshow) != 'undefined' && lcl_slideshow !== false) {  
						$.lcweb_lightbox.start_slideshow();
					}
				}, 150);

				if(_elem_type() == 'image') { _lcl_touchSwipe(); }
			}
			else {	
				setTimeout(function() { // show after lightbox sizing
					$('#lcl_standard_elem').removeClass('lcl_pag_anim').css('opacity', 1);
					$('#lcl_standard_cmd, #lcl_thumb_nav, #lcl_standard_elem > img').fadeTo(300, 1);
					
					if(lcl_txt_exists) {$('#lcl_standard_txt').fadeTo(300, 1);}
					$('#lcl_standard').addClass('lcl_no_loader');
					
					if(_elem_type() == 'image') { _lcl_touchSwipe(); }
				}, settings.animation_time);
			}
		}
		
		
		// calculate text under size and related image's max-height
		function _manage_txt_under(max_height_px, true_img_h, second_check) {
			var settings = lcl_settings;
			$('#lcl_standard_txt').css('width', lcl_curr_elem_w);
			
			var txt_under_h = (typeof(second_check) != 'undefined') ? second_check : Math.ceil( $('#lcl_standard_txt').outerHeight());
			var tu_overflow = max_height_px - (true_img_h + txt_under_h);

			// if image's height + text are taller than max_h
			if(tu_overflow < 0) {
				tu_overflow = Math.ceil(tu_overflow * -1);
				var new_h = true_img_h - tu_overflow;
				
				// check a further time analyzing with new text width
				if(typeof(second_check) == 'undefined') {
					var new_w = Math.floor((lcl_curr_elem_w * new_h) / lcl_curr_elem_h);
					$('#lcl_standard_txt').css('width', new_w);
					
					var txt_under_h = Math.ceil( $('#lcl_standard_txt').outerHeight());
					return _manage_txt_under(max_height_px, true_img_h, txt_under_h);	
				}
				else {
					$('#lcl_standard_elem > img').css('max-height', new_h);
					
					lcl_managed_img_h = true;
					lcl_count_txt_under = true;
					return txt_under_h;
				}
			}
			else {
				lcl_count_txt_under = false;
				return txt_under_h;
			}	
		}
		
		
		// manage the fullscreen image
		function _manage_fs_img() {
			var settings = lcl_settings;
			var $subj = $('#lcl_fs_elem > img');

			// never crop - only downscale if is bigger
			if(settings.fs_img_behaviour == 'none') {
				$subj.css('max-width', '100%').css('max-height', '100%');
			}
			
			// fit the screen width or height
			else if(settings.fs_img_behaviour == 'fit') {
				var w_ratio = lcl_window_w / lcl_curr_elem_w;
				var h_ratio = lcl_window_h / lcl_curr_elem_h;
				
				if(w_ratio > h_ratio) {
					$subj.css('width', 'auto').css('height', '100%');		
				} else {
					$subj.css('width', '100%').css('height', 'auto');		
				}
			}
			
			// always fill the screen
			else if(settings.fs_img_behaviour == 'always' || settings.fs_img_behaviour == 'fill') {
				if(_is_old_IE()) {
					$('#lcl_fs_elem').addClass('lcl_fill_fs_img_old_ie');
				} else {			
					$('#lcl_fs_elem').addClass('lcl_fill_fs_img');	
				}
			}
			
			// smart resize&crop - only if ratio and sizes are similar
			else if(settings.fs_img_behaviour == 'smart') {
				var ratio_diff = (lcl_window_w / lcl_window_h) - (lcl_curr_elem_w / lcl_curr_elem_h)
				var w_diff = lcl_window_w - lcl_curr_elem_w; 
				var h_diff = lcl_window_h - lcl_curr_elem_h; 
	
				if( (ratio_diff <= 1.15 && ratio_diff >= -1.15) && (w_diff <= 350 && h_diff <= 350) ) { // fill
					$subj.css('max-width', 'none').css('max-height', 'none');
					
					if(_is_old_IE()) {
						$('#lcl_fs_elem').addClass('lcl_fill_fs_img_old_ie');
					} else {			
						$('#lcl_fs_elem').addClass('lcl_fill_fs_img');	
					}
				}
				else { // standard centering (as 'none')
					$('#lcl_fs_elem').removeClass('lcl_fill_fs_img lcl_fill_fs_img_old_ie');
					$subj.css('max-width', '100%').css('max-height', '100%');
				}
			}
			
			setTimeout(function() {
				$('#lcl_fs_elem').addClass('lcl_no_loader');
				$subj.fadeIn(settings.animation_time);
				if(!$('#lcl_fs_txt').hasClass('lcl_fs_txt_hidden')) { $('#lcl_fs_txt').fadeTo(settings.animation_time, 1); }
			}, 50);
			
			_lcl_touchSwipe();
		}
		
		
		// create and append the thumb navigator to the lightbox
		function _manage_thumb_nav() {
			var settings = lcl_settings;
			if(!settings.thumb_nav || !_is_images_gallery()) {return false;}

			var thumb_nav_btn_w = 46 ;
			lcl_thumb_per_page = Math.floor( ($('#lcl_wrapper').width() - thumb_nav_btn_w) / (settings.thumb_w + 9) );

			if(lcl_thumb_per_page > 1 && lcl_window_h > 550) {
				$('#lcl_thumb_nav').css('visibility', 'visible').removeClass('lcl_tn_disabled');
				
				var nav_width = thumb_nav_btn_w + ( (settings.thumb_w + 7) * lcl_thumb_per_page);
				var nav_margin = Math.floor((lcl_window_w - nav_width) / 2);
				
				// find the current page
				lcl_curr_thumb_pag = Math.ceil((lcl_elem_index + 1) / lcl_thumb_per_page);
				
				// vars for pagination
				var tot_pages = Math.ceil( lcl_tot_elements / lcl_thumb_per_page );
				lcl_thumb_pag_w = (settings.thumb_w + 7/*fixed margin*/) * lcl_thumb_per_page;
				
				// populate
				if( $('#lcl_thumb_nav li').length == 0 ) {
					$('#lcl_thumb_nav').html(
						'<div id="lcl_thumb_prev"><div></div></div>'+
						'<div id="lcl_thumb_container"><ul></ul></div>'+
						'<div id="lcl_thumb_next"><div></div></div>'
					);
					
					// add image thumbs
					lclt_pag = 1;
					a = 1;
					$.each(lcl_elem_url, function(k, img_url) {
						var sel = (k == lcl_elem_index) ? 'lcl_curr_thumb' : ''; 
						
						// thumbs maker integration
						if(settings.thumbs_maker_url) {
							 var base = settings.thumbs_maker_url;
							 img_url = base.replace('%URL%', encodeURIComponent(img_url)).replace('%W%', settings.thumb_w).replace('%H%', settings.thumb_h);
						}
						
						$('#lcl_thumb_container ul').append(
						'<li id="lclt_'+k+'" class="lcl_tp_'+lclt_pag+' '+sel+'" style="width: '+settings.thumb_w+'px; height: '+settings.thumb_h+'px;"><img src="'+img_url+'" /></li>');
						
						// thumb preload and size
						_thumb_nav_preloader(img_url, k);
						
						if( a == lcl_thumb_per_page ) { 
							a = 1;
							lclt_pag = lclt_pag + 1;
						}
						a = a + 1;
					});
				}
				else {
					$('#lcl_thumb_container li').attr('class', 'lcl_no_loader');
					
					lclt_pag = 1;
					a = 1;
					$.each(lcl_elem_url, function(k, img_url) {
						if(k == lcl_elem_index) { $('#lclt_'+k).addClass('lcl_curr_thumb'); } 
						else { $('#lclt_'+k).removeClass('lcl_curr_thumb'); }
						
						$('#lclt_'+k).addClass('lcl_tp_'+lclt_pag);
						
						if( a == lcl_thumb_per_page ) { 
							a = 1;
							lclt_pag = lclt_pag + 1;
						}
						a = a + 1;
					});
				}
				
				// calculate the offset
				if(tot_pages == 1) { 
					var cont_offset = Math.ceil( (lcl_tot_elements * (settings.thumb_w + 7/*fixed margin*/) - lcl_thumb_pag_w) / 2);
					$('#lcl_thumb_prev, #lcl_thumb_next').css('visibility', 'hidden');
				}
				else {
					if(lcl_curr_thumb_pag == tot_pages) { var cont_offset = lcl_tot_elements * (settings.thumb_w + 7/*fixed margin*/) - lcl_thumb_pag_w; }
					else { var cont_offset = (settings.thumb_w + 7/*fixed margin*/) * (lcl_thumb_per_page * (lcl_curr_thumb_pag - 1)); }
					
					$('#lcl_thumb_prev, #lcl_thumb_next').css('visibility', 'visible');
				}
				
				// thumb cmd management
				$('#lcl_thumb_prev div, #lcl_thumb_next div').removeClass('disabled');
				if(cont_offset == 0) { $('#lcl_thumb_prev div').addClass('disabled'); }
				if(lcl_curr_thumb_pag == tot_pages) { $('#lcl_thumb_next div').addClass('disabled'); }
				
				// adjust the thumb container
				$('#lcl_thumb_nav').css('width', nav_width).css('height', settings.thumb_h).css('left', nav_margin);
				
				var nav_margin = Math.floor( (settings.thumb_h - $('#lcl_thumb_prev div').outerHeight()) / 2);
				$('#lcl_thumb_prev div, #lcl_thumb_next div').css('margin-top', nav_margin).css('margin-bottom', nav_margin);
				
				$('#lcl_thumb_container').css('width', (nav_width - thumb_nav_btn_w));
				$('#lcl_thumb_container > ul').css('width', (lcl_thumb_pag_w * tot_pages));
				
				// thumb container positioning
				if( $('#lcl_thumb_nav').is(':hidden') ) { 
					$('#lcl_thumb_container > ul').clearQueue().animate({'marginLeft': (cont_offset * -1)}, 250); 
				}
				else { 
					var curr_cont_margin = parseInt( $('#lcl_thumb_container > ul').css('margin-left') );
					if( (cont_offset * -1) != curr_cont_margin) { 
						$('#lcl_thumb_container > ul')
							.css('margin-left', curr_cont_margin)
							.clearQueue().animate({'marginLeft': (cont_offset * -1)}, settings.animation_time); 
					}
					else { $('#lcl_thumb_container > ul').css('margin-left', (cont_offset * -1)); }
				}
			}
			else { 
				$('#lcl_thumb_nav').css('visibility', 'hidden').addClass('lcl_tn_disabled'); 
				$('#lcl_wrapper').removeAttr('style'); // reset wrapper padding
			}
			
			// manage wrapper padding-bottom
			if(settings.thumb_nav && !$('#lcl_thumb_nav').hasClass('lcl_tn_disabled')) {
				var wrap_btm_padd = settings.thumb_h + 26; // bottom space / top space
				$('#lcl_wrapper').css('padding-bottom', wrap_btm_padd);
			}	
		}


		
		
		// thumb navigator img preloader
		function _thumb_nav_preloader(img_url, img_index) {
			$('<img />').bind("load",function(){ 
				var img_w = this.width;
				var img_h = this.height;
				
				$('#lclt_'+img_index).addClass('lcl_no_loader');
				
				// in case of thumbnail maker
				if(img_w == settings.thumb_w && img_h == settings.thumb_h) {
					$('#lclt_'+img_index+' img').fadeIn('fast');	
				}
				else {
					if(img_w > img_h) {
						var ratio = img_w / img_h;
						var new_img_w = Math.ceil(settings.thumb_h * ratio);	
						var margin_left = Math.ceil( (new_img_w * 1.2 - settings.thumb_w) / 2 ) * -1;
						
						$('#lclt_'+img_index+' img')
							.css('width', (new_img_w * 1.2))
							.css('height', (settings.thumb_h  * 1.2))
							.css('margin-left', margin_left)
							.fadeIn('fast');
					}
					else {
						var ratio = img_h / img_w;
						var new_img_h = Math.ceil(settings.thumb_w * ratio);	
						var margin_top = Math.ceil( (new_img_h * 1.2 - settings.thumb_h) / 2 ) * -1;
						$('#lclt_'+img_index+' img')
							.css('width', (settings.thumb_w * 1.2))
							.css('height', (new_img_h  * 1.2))
							.css('margin-top', margin_top)
							.fadeIn('fast');
					}
				}
			}).attr('src', img_url);
		}
		
		
		// thumb navigator scroll
		function _thumb_nav_scroll(direction) {
			if(!lcl_scrolling_thumb) {
				$('#lcl_thumb_prev div, #lcl_thumb_next div').removeClass('disabled');
				
				settings = lcl_settings;
				var offset = settings.thumb_w + 7 /*fixed margin*/;
				var curr_margin = parseInt( $('#lcl_thumb_container > ul').css('margin-left') );
				var max_margin = ((lcl_tot_elements * offset - lcl_thumb_pag_w) * -1);
				
				// previous
				if(direction == 'prev' && curr_margin < 0) {
					lcl_scrolling_thumb = true;
					var new_margin = curr_margin + offset;
					$('#lcl_thumb_container > ul').clearQueue().animate({'marginLeft' : new_margin}, 200, function() {
						if(new_margin == 0) { $('#lcl_thumb_prev div').addClass('disabled'); }
						lcl_scrolling_thumb = false;
					});		
				}
				
				// next
				if(direction == 'next' && curr_margin > max_margin) {
					lcl_scrolling_thumb = true;
					var new_margin = curr_margin - offset;
					$('#lcl_thumb_container > ul').clearQueue().animate({'marginLeft': new_margin}, 200, function() {
						if(new_margin == max_margin) { $('#lcl_thumb_next div').addClass('disabled'); }
						lcl_scrolling_thumb = false;	
					});	
				}
				
				// custom swipe
				if( typeof direction == 'number' ) {
					if(
						(direction < 0 && curr_margin > max_margin) || 
						(direction > 0 && curr_margin < 0)
					) {
						var to_move = Math.ceil( direction / offset );
						var new_margin = curr_margin + (to_move * offset); 
						
						// checks to avoid wrong results
						if(new_margin > 0) { new_margin = 0;}
						else if ( new_margin < max_margin) { new_margin = max_margin; }
						
						lcl_scrolling_thumb = true;
						$('#lcl_thumb_container > ul').clearQueue().animate({'marginLeft': new_margin}, 200, function() {
							if(new_margin == 0) { $('#lcl_thumb_prev div').addClass('disabled'); }
							if(new_margin == max_margin) { $('#lcl_thumb_next div').addClass('disabled'); }
							lcl_scrolling_thumb = false;	
						});	
					}
				}
			}
		}
		
		
		// socials code setup
		function _social_code(url, title, descr, page_url) {
			var settings = lcl_settings;

			var code = 
			'<ul class="lcl_social_box">'+
				'<li class="lcl_fb"><a onClick="window.open(\'https://www.facebook.com/dialog/feed?app_id=425190344259188&display=popup&name='+title+'&description='+descr+'&nbsp;&picture='+settings.fb_share_fix+'?u='+url+'&link='+page_url+'&redirect_uri=http://www.lcweb.it/lcis_redirect.php\',\'sharer\',\'toolbar=0,status=0,width=590,height=325\');" href="javascript: void(0)"></a>'+
				'</li>'+
				'<li class="lcl_twit"><a onClick="window.open(\'https://twitter.com/share?text=Check%20out%20%22'+title+'%22%20@&url='+page_url+'\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');" href="javascript: void(0)"></a>'+
				'</li>'+
				'<li class="lcl_pint"><a onClick="window.open(\'http://pinterest.com/pin/create/button/?url='+page_url+'&media='+url+'&description='+title+'\',\'sharer\',\'toolbar=0,status=0,width=575,height=330\');" href="javascript: void(0)"></a>'+
				'</li>'+
			'</ul>';
						

			return code;	
		}
		
		
		// check for images gallery (for thumb navigator)
		function _is_images_gallery() {
			if( typeof(lcl_is_img_gallery) == 'undefined') {
				var only_img = true;
				
				$.each(lcl_elem_url, function(k, v) {
					if( _elem_type(v) != 'image' ) {only_img = false;}
				});
				
				if(!only_img) {lcl_is_img_gallery = false;}
				else {lcl_is_img_gallery = true;}
			}
			
			return lcl_is_img_gallery;
		}	
	

		// get the current element type
		function _elem_type(elem_src) {
			if(typeof(elem_src) == 'undefined') { var elem_src = lcl_elem_url[lcl_elem_index]; }
			
			if (elem_src.match(/youtube\.com\/watch/i) || elem_src.match(/youtu\.be/i)) {
				return 'youtube';
			}else if (elem_src.match(/vimeo\.com/i)) {
				return 'vimeo';
			}else if(elem_src.match(/\b.mov\b/i)){ 
				return 'quicktime';
			}else if(elem_src.match(/\b.swf\b/i)){
				return 'flash';
			}else if(elem_src.match(/\biframe=true\b/i)){
				return 'iframe';
			}else if(elem_src.match(/\bajax=true\b/i)){
				return 'ajax';
			}else if(elem_src.substr(0,1) == '#'){
				return 'inline';
			}else{
				return 'image';
			};	
		}
		
		
		// check if the browser is IE8 or older
		function _is_old_IE() {
			if( navigator.appVersion.indexOf("MSIE 7.") != -1 || navigator.appVersion.indexOf("MSIE 8.") != -1 ) {return true;}
			else {return false;}	
		}
		
		
		// check for touch device
		function _is_touch_device() {
			return !!('ontouchstart' in window);
		}
		

		// check if is a mobile browser
		function _is_mobile() {
			if( /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent) ) 
			{ return true;}
			else { return false; }
		}
		
		
		// event for touch devices that are not webkit - against double-tap need
		var lcl_ontouchstart = (!("ontouchstart" in document.documentElement) || navigator.userAgent.match(/(iPad|iPhone|iPod)/g)) ? '' : ' touchstart'; 

		////////////////////////////////////////////////
		
		// touchswipe for image elements
		function _lcl_touchSwipe() {
			if(!_is_old_IE() && lcl_tot_elements > 1) {
				var settings = lcl_settings;
				
				if(settings.touchSwipe && $.fn.swipe){
					// standard lightbox
					if(!lcl_fullscreen_open) {
						var treshold = Math.round( $('#lcl_standard_elem > img').width() / 2 );
						$("#lcl_standard_elem > img").swipe( {
							swipeStatus:function(event, phase, direction, distance, duration, fingerCount) {
								
								if(phase == 'end' && direction == 'left') {
									$.lcweb_lightbox.change_page('next');
									$.lcweb_lightbox.stop_slideshow();
								}
								if(phase == 'end' && direction == 'right') {
									$.lcweb_lightbox.change_page('prev');
									$.lcweb_lightbox.stop_slideshow();
								}
							},
							triggerOnTouchEnd: false,
							maxTimeThreshold: 1000,
							threshold: treshold
						});
					}
					
					// fullscreen 
					else {
						var fs_treshold = Math.round( $('#lcl_fs_elem').width() * 0.35 );
						$("#lcl_fs_elem").swipe( {
							swipeStatus:function(event, phase, direction, distance, duration, fingerCount) {
								
								if(phase == 'end' && direction == 'left') {
									$.lcweb_lightbox.change_page('next');
									$.lcweb_lightbox.stop_slideshow();
								}
								if(phase == 'end' && direction == 'right') {
									$.lcweb_lightbox.change_page('prev');
									$.lcweb_lightbox.stop_slideshow();
								}
							},
							triggerOnTouchEnd: false,
							maxTimeThreshold: 1000,
							threshold: fs_treshold
						});
					}
					
					// thumb navigator
					if($('#lcl_thumb_container li').length > 1 && Math.ceil( lcl_tot_elements / lcl_thumb_per_page ) > 1) {
						$("#lcl_thumb_container").swipe( {
							swipeStatus:function(event, phase, direction, distance, duration, fingerCount) {
								
								if(phase == 'end' && direction == 'left') {
									var offset = distance * -1;
									_thumb_nav_scroll( offset );
								}
								if(phase == 'end' && direction == 'right') {
									var offset = distance;
									_thumb_nav_scroll( offset );
								}
							},
							triggerOnTouchEnd: false,
							threshold: null
						});	
					}
				}
			}
			
			return false;
		}

		
		////////////////////////////////////////////////

		function lcl_debouncer($,cf,of, interval){
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
		lcl_debouncer($,'lcl_smartresize', 'resize', 49);

		// lightbox center on resize
		$(window).lcl_smartresize(function() {
			if(lcl_open) { 
				lcl_window_w = $(window).width();
				lcl_window_h = $(window).height();	
				
				if(!lcl_fullscreen_open) { _adjust_and_show(); } 
				else { _manage_fs_img();}
				
				_manage_thumb_nav();
			}
		});

		////////////////////////////////////////////////
		
		// if not direct open - add trigger
		/*if(!lcl_settings.open) {
			$('body').delegate(lcl_elem_obj, 'click', function() {
				$.lcweb_lightbox.init();
			});
		}*/
		
		
		// open fullscreen
		$('#lcl_standard_cmd .lcl_fullscreen').unbind('click.lcweb_lightbox');
		$('body').delegate('#lcl_standard_cmd .lcl_fullscreen', 'click'+lcl_ontouchstart, function() {
			$.lcweb_lightbox.open_fullscreen();
		});
		
		
		// go to previous element
		$('.lcl_prev').unbind('click.lcweb_lightbox');
		$('body').delegate('.lcl_prev', 'click'+lcl_ontouchstart, function() {
			$.lcweb_lightbox.change_page('prev');
			$.lcweb_lightbox.stop_slideshow();
			return false;
		});
	
		// go to next element
		$('.lcl_next, #lcl_standard_elem > img, #lcl_fs_elem > img').unbind('click.lcweb_lightbox');
		$('body').delegate('.lcl_next, #lcl_standard_elem > img, #lcl_fs_elem > img', 'click'+lcl_ontouchstart, function() {		
			if(lcl_tot_elements > 1) {
				$.lcweb_lightbox.change_page('next');
				$.lcweb_lightbox.stop_slideshow();
			}
			return false;
		});
		
		// close the lightbox
		$('.lcl_close').unbind('click.lcweb_lightbox');
		$('body').delegate('.lcl_close', 'click', function() {	
			$.lcweb_lightbox.stop_slideshow();
			$.lcweb_lightbox.close();
			return false;
		});
		
		if( !lcl_settings.modal ) {
			$('#lcl_overlay').unbind('click.lcweb_lightbox');
			$('body').delegate('#lcl_overlay', 'click'+lcl_ontouchstart, function() {		
				$.lcweb_lightbox.stop_slideshow();
				$.lcweb_lightbox.close();
				return false;
			});	
		}
		
		
		// start slideshow	
		$('.lcl_play').unbind('click.lcweb_lightbox');
		$('body').delegate('.lcl_play', 'click'+lcl_ontouchstart, function() {	
			$.lcweb_lightbox.start_slideshow();
			return false;
		});
		
		// stop slideshow	
		$('.lcl_pause').unbind('click.lcweb_lightbox');
		$('body').delegate('.lcl_pause', 'click'+lcl_ontouchstart, function() {	
			$.lcweb_lightbox.stop_slideshow();
			return false;
		});
		
		
		// jump to image - thumb navigation	
		$('#lcl_thumb_container li').unbind('click.lcweb_lightbox');
		$('body').delegate('#lcl_thumb_container li', 'click'+lcl_ontouchstart, function() {		
			var img_index = $(this).attr('id').substr(5);
			$.lcweb_lightbox.change_page(img_index);
			$.lcweb_lightbox.stop_slideshow();
		});
		
		// thumb nav controls	
		$('#lcl_thumb_prev, #lcl_thumb_next').unbind('click.lcweb_lightbox');
		$('body').delegate('#lcl_thumb_prev', 'click'+lcl_ontouchstart, function() {		
			if( !$(this).hasClass('disabled') ) { _thumb_nav_scroll('prev'); }
			return false;
		});
		$('body').delegate('#lcl_thumb_next', 'click'+lcl_ontouchstart, function() {		
			if( !$(this).hasClass('disabled') ) { _thumb_nav_scroll('next'); }
			return false;
		});
		
		
		// socials toggle visibility
		$('.lcl_socials').unbind('click.lcweb_lightbox');
		$('body').delegate('#lcl_standard_cmd .lcl_socials', 'click'+lcl_ontouchstart, function() {		
			var $subj = $('#lcl_standard .lcl_social_box');
			if( !$subj.hasClass('lcl_is_acting') ) {
				
				$subj.addClass('lcl_is_acting');
				$('#lcl_standard .lcl_social_box').slideToggle('fast', function() {
					$subj.removeClass('lcl_is_acting');
				});
			}
			return false;
		});
		$('body').delegate('#lcl_fs_cmd .lcl_socials', 'click'+lcl_ontouchstart, function() {	
			var $subj_fs = $('#lcl_fullscreen .lcl_social_box');
			if( !$subj_fs.hasClass('lcl_is_acting') ) {
				
				$subj_fs.addClass('lcl_is_acting');
				$('#lcl_fullscreen .lcl_social_box').slideToggle('fast', function() {
					$subj_fs.removeClass('lcl_is_acting');
				});
			}
			return false;
		});
		
		
		// fullscreen data toggle	
		$('#lcl_fs_cmd .lcl_toggle_info').unbind('click.lcweb_lightbox');
		$('body').delegate('#lcl_fs_cmd .lcl_toggle_info', 'click'+lcl_ontouchstart, function() {	
			if(!lcl_is_active) {
				lcl_is_active = true;
				
				if(!$('#lcl_fs_txt').hasClass('lcl_fs_txt_hidden')) { $('#lcl_fs_txt').fadeOut('fast').addClass('lcl_fs_txt_hidden'); lcl_fs_txt = false; }
				else { $('#lcl_fs_txt').fadeIn(250).removeClass('lcl_fs_txt_hidden'); lcl_fs_txt = true; }
				
				setTimeout(function() {
					lcl_is_active = false;
				}, 250);
			}
		});
		
		
		// Keyboard events
		$(document).unbind('keydown.lcweb_lightbox').bind('keydown.lcweb_lightbox',function(e){
			if(lcl_open == true && !lcl_is_active && lcl_tot_elements > 1) {
				// prev
				if (e.keyCode == 37) {
					$.lcweb_lightbox.change_page('prev');
					$.lcweb_lightbox.stop_slideshow();
				}
				
				// next 
				if (e.keyCode == 39) {
					$.lcweb_lightbox.change_page('next');
					$.lcweb_lightbox.stop_slideshow();
				}
				
				// close
				if (e.keyCode == 27) {
					$.lcweb_lightbox.close();	
					$.lcweb_lightbox.stop_slideshow();
				}
			}
		});
		
		
		////////////////////////////////////////////
		
		// auto launch
		if( lcl_settings.open ) { $.lcweb_lightbox.init(); return false; }
		else {
			return 
				this.unbind('click.lcweb_lightbox')
				.bind('click.lcweb_lightbox', $.lcweb_lightbox.init ); //Use unbind method is to avoid click conflict when the plugin is called more than once
		}
	};
			
})(jQuery);