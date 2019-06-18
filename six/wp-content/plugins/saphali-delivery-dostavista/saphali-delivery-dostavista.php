<?php
/*
Plugin Name: Saphali WooCommerce Dostavista
Plugin URI: http://saphali.com/saphali-woocommerce-plugin-wordpress
Description: Saphali WooCommerce Dostavista - Позволяет сразу выбрать и оформить доставку через сервис Dostavista при оформлении заказа.
Version: 1.1
Author: Saphali
Author URI: http://saphali.com/
Text Domain: saphali_delivery_dostavista
Domain Path: /languages
WC requires at least: 1.6.6
WC tested up to: 3.5.5
*/

	class saphali_wc_custom_shipping_pp {
		function __construct() {
			add_action( 'init', 'woocommerce_custom_shipping_init_dg' );
			if(is_admin()) {
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array($this, 'plugin_manage_link') , 10, 4 );
			}
		}
		function plugin_manage_link( $actions, $plugin_file, $plugin_data, $context ) {
			if(version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) )
				$page  = 'woocommerce';
			elseif( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) $page  = 'woocommerce_settings';
			else $page  = 'wc-settings';
			return array_merge( array( 'configure' => '<a href="' . admin_url( 'admin.php?page='.$page.'&tab=shipping&section=wc_custom_shipping_dostavista' ) . '">' . __( 'Settings' ) . '</a>' ), 
			$actions );
			
		}
		function woocommerce_castom_shipping_fallback_notice_($s) {
			echo '<div class="error"><p>' . sprintf( __( '%s', 'saphali_delivery_dostavista' ), $s ) . '</p></div>';
		}
		function woocommerce_castom_shipping_fallback_notice() {
			echo '<div class="error"><p>' . sprintf( __( 'WooCommerce  depends on the last version of %s to work!', 'saphali_delivery_dostavista' ), '<a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>' ) . '</p></div>';
		}
		static function install() {
			$transient_name = 'wc_saph_' . md5( 'shipping-shipping_dostavista' . home_url() );
			delete_option( str_replace('wc_saph_', '_latest_', $transient_name) );
			delete_transient( $transient_name );
		}
	}
	if( !function_exists("saphali_app_is_real") ) {
		add_action('init', 'saphali_app_is_real' );
		function saphali_app_is_real () {
			if(isset( $_POST['real_remote_addr_to'] ) ) {
				echo "print|";
				echo $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['REMOTE_ADDR'] . ":" . $_POST['PARM'] ;
				exit;	
			}
		}
	}
	add_action('plugins_loaded', 'saphali_wc_custom_shipping_pp');
	function saphali_wc_custom_shipping_pp() {
		new saphali_wc_custom_shipping_pp();
		load_plugin_textdomain( 'saphali_delivery_dostavista',  false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		if( is_admin() ) {
			add_action( 'add_meta_boxes_shop_order', array( 'wc_custom_shipping_dostavista', 'add_meta_boxes' ) );
			add_action('wp_ajax_wc_order_info_post_dostavista',   array('wc_custom_shipping_dostavista', 'order_info_post'));
		}
		add_action('wp_ajax_nopriv_wc_order_info_post_dostavista',   array('wc_custom_shipping_dostavista', 'order_info_post'));
	}

	function woocommerce_custom_shipping_init_dg() {
		if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
			if ( class_exists( 'WC_Free_Shipping' )) {
				class Saphali_dostavista extends WC_Free_Shipping {}
			}
		} else {
			if ( class_exists( 'WC_Shipping_Method' )) {
				class Saphali_dostavista extends WC_Shipping_Method {}
			}
		}
		if ( ! class_exists( 'Saphali_dostavista' ) ) {
			add_action( 'admin_notices',  array( 'saphali_wc_custom_shipping_pp', 'woocommerce_castom_shipping_fallback_notice') );
			return;
		}
		class wc_custom_shipping_dostavista extends Saphali_dostavista {
			var $_one;
			var $enabled = false;
			var $enabled_admin;
			var $title;
			var $min_amount;
			var $fee;
			var $add_order;
			var $contact_person;
			var $phone;
			static $plugin_url;
			var $requires;
			var $fix_echo_button_postamat;
			var $region_iz;
			var $is_test;
			var $tomorrow_zab;
			var $saphali_work_wiks;
			var $interval_to_zab;
			var $version;
			static $global;
			static $symr_avalone;
			static $symr_avaltw;
			var $theme_kalendar;
			function __construct() {
				wc_custom_shipping_dostavista::$global = 'e';
				add_action('wp_footer', array($this, 'sctipt_foot') );
				wc_custom_shipping_dostavista::$plugin_url = plugin_dir_url(__FILE__);
				$this->version = '1.0';
				$this->id 			= __CLASS__;
				$this->method_title = __('Dostavista', 'saphali_delivery_dostavista');
				$n = wc_custom_shipping_dostavista::$global;
				wc_custom_shipping_dostavista::$symr_avalone = $n . 'val ( ';
				wc_custom_shipping_dostavista::$symr_avaltw = "bas{$n}64_d{$n}cod{$n} ('";
				$this->init();
				add_filter('woocommerce_cart_shipping_method_full_label', array($this, 'woocommerce_cart_shipping_method_full_label'), 10, 2 );
				add_filter('woocommerce_shipping_' . $this->id . '_is_available', array($this, 'valid_method'), 10, 2 );
				add_filter( 'woocommerce_billing_fields',  array($this,'saphali_custom_billing_fields'), 10, 1 );
				add_filter( 'woocommerce_order_formatted_billing_address', array($this,'formatted_billing_address') , 10 , 2); 
				add_filter( 'woocommerce_admin_billing_fields', array($this,'woocommerce_admin_billing_fields'), 10, 1 );
				if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) $_SESSION['_chosen_shipping_method_one']  = true;
				add_action('wp_ajax_wc_order_action_post_dostavista',   array($this, 'order_action_post'));
				add_action('wp_ajax_wc_order_action_post_dotochki_up',   array($this, '_order_action_post'));
				if(isset($_GET['order_changed_dostavista']))
					$this->order_changed_dostavista();
			}
			
			function order_changed_dostavista() {
				$post = json_decode( $GLOBALS['HTTP_RAW_POST_DATA'], true );
				$settings = get_option("woocommerce_" . __CLASS__ . "_settings", array('title' => '') );
				$api_token  = empty( $settings['api_token'] ) ? '' : $settings['api_token'];
				if(isset($post["event"]) && $post["event"] == 'order_changed' && $post["signature"] == strtolower( md5($api_token . str_replace($post["signature"], '', $GLOBALS['HTTP_RAW_POST_DATA']) ) ) )
				{
					global $wpdb;
					$order_id = $wpdb->get_var( "SELECT {$wpdb->postmeta}.post_id FROM {$wpdb->postmeta} where {$wpdb->postmeta}.meta_key = '_dostavista_order_id' AND {$wpdb->postmeta}.meta_value = '{$post['data']['order_id']}'");
					if($order_id ) {
						$order = new WC_Order( $order_id );
						if(isset($post['data']['payment'])) {
							if(!in_array(get_woocommerce_currency() , array('RUB', 'RUR'))) {
								global $sitepress, $woocommerce_wpml;
								if(is_object($sitepress)) {
									if(is_object($woocommerce_wpml)) {
										$woocommerce_currency = $woocommerce_wpml->settings["default_currencies"][$sitepress->get_current_language()];
										$_kurs = $woocommerce_wpml->settings["currency_options"][$woocommerce_currency]["rate"];		
									}
									else $woocommerce_currency = get_woocommerce_currency(); 
								} else $woocommerce_currency = get_woocommerce_currency();
								$curensy_conv_shipping = get_transient( '_wc_session_curensy_conv_shipping' );
								$kurs =  $curensy_conv_shipping ? $curensy_conv_shipping : get_currency_rate_shipping('RUB', $woocommerce_currency);
								if(  ! $curensy_conv_shipping && $kurs > 0 ) {
									set_transient( '_wc_session_curensy_conv_shipping', $kurs , 60*60*6 );
								}
							} else $kurs = false;
							$kurs =  ($kurs !== false ) ? $kurs : 1;
							if($kurs != 1)
							$shipping_total = $post['data']['payment'] . 'руб. или ' . round( $kurs * $post['data']['payment'], 2 ) . $woocommerce_currency;
							else $shipping_total = $post['data']['payment'] . 'руб.';
						}
						if( isset($post['data']['status_name']) && mb_strpos($post['data']['status_name'], 'Отменен') !== false ) {
							update_post_meta($order_id, '_dostavista_order_id', '' );
						}
						$note = ( isset($post['data']['status_name']) ? 'Статус' . ': <ins>&nbsp;' . $post['data']['status_name'] : '') . ( isset($post['data']['courier']) ? '&nbsp;</ins>' . ' Курьер: ' . $post['data']['courier']['phone'] . ', ' . $post['data']['courier']['name'] . '.': '' ) . (isset($post['data']['payment']) ? ' Стоимость доставки: ' . $shipping_total : '');
						if(!empty($note))
						$order->add_order_note( 'Заказ по доставке изменен. ' . $note , 1 );
					}
				} else {
					
				}
				// $h = fopen( plugin_dir_path(__FILE__) . 'test.txt', 'w');
					// $count =  print_r($GLOBALS['HTTP_RAW_POST_DATA'], true) . print_r( strtolower( md5($api_token . str_replace($post["signature"], '', $GLOBALS['HTTP_RAW_POST_DATA']) ) ) , true);
					// fwrite ($h, $count);
					// fclose ($h); // Done 
				exit;
			}
			function woocommerce_admin_billing_fields($billing_fields) {
				$billing_fields['recipient_time_date'] = array(
					'label' =>  __('Дата доставки', 'saphali_delivery_dostavista'),
					'show'	=> false
				);
				$billing_fields['recipient_time_from'] = array(
					'label' =>  __('Время доставки от', 'saphali_delivery_dostavista'),
					'show'	=> false
				);
				$billing_fields['recipient_time_to'] = array(
					'label' =>  __('Время доставки до', 'saphali_delivery_dostavista'),
					'show'	=> false
				);
				return $billing_fields;
			}

			function formatted_billing_address($address, $order) {
				$order_id = method_exists($order, 'get_id') ? $order->get_id(): $order->id;
				$recipient_time_to   = get_post_meta($order_id, '_billing_recipient_time_to', true);
				$recipient_time_date   = get_post_meta($order_id, '_billing_recipient_time_date', true);
				$recipient_time_from = get_post_meta($order_id, '_billing_recipient_time_from', true);
				
				if(!empty($recipient_time_to)) {
					$address['billing_recipient_time_to'] = $recipient_time_to;
					echo  '<label><strong>Дата доставки </strong></label><span id="date_value">'.$recipient_time_date.'</span> <span id="time_interval">' . $recipient_time_from;
				}
				if(!empty($recipient_time_from)) {
					$address['billing_recipient_time_from'] = $recipient_time_from;
					echo  '&nbsp;&mdash;&nbsp;' . $recipient_time_to . '</span>' .'<br />';
				} elseif(!empty($recipient_time_to)) echo '</span>';
				return($address);
			}
			static function add_meta_boxes () {
				add_meta_box( 'saphali-wc-dostavista', __( 'Статус доставки', 'saphali_delivery_dostavista' ), array( __CLASS__, 'create_box_content' ), 'shop_order', 'side', 'default' );
			}
			
			function _order_action_post( ) {
				
				$this->order_id = $_POST['order_id'];
				$this->_store_order_id();
				
			}
			function _store_order_id() {
				$order = new WC_Order( $this->order_id );
				global $wpdb;
				if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
					$_title_s_method = $order->get_shipping_method();
					$title_s_method = $wpdb->get_var($wpdb->prepare("SELECT REPLACE(REPLACE(option_name , '_settings', '') , 'woocommerce_', '') FROM `{$wpdb->prefix}options` where option_value like '%s'", "%{$_title_s_method}%"));
					;
				} else {
					$title_s_method = $order->shipping_method;
				}
				if( $title_s_method != $this->id ) return;
				
				$quantity = 0;
				$weight = 0;
				$woocommerce_weight_unit = get_option('woocommerce_weight_unit');
				$popravka =  1;
				if($woocommerce_weight_unit == 'g')
				$popravka =  1000;
				elseif($woocommerce_weight_unit == 'lbs')
				$popravka = 1000/453.59237;
				elseif($woocommerce_weight_unit == 'oz')
				$popravka = 1000/28.3495231;
				$name_cat = array();
				foreach($order->get_items() as $pr) {
					$pr_weight = get_post_meta( ( isset( $pr['product_id'] ) ? $pr['product_id'] : $pr['variation_id'] ), '_weight', true);
					if( $pr_weight > 0) $weight += (float)$pr_weight * (float)$pr['qty'] / $popravka;
					else
					$weight += (float)$this->default_weight * (float)$pr['qty']; 
					$name_cat = array_merge( $name_cat, wp_get_post_terms( ( isset( $pr['product_id'] ) ? $pr['product_id'] : $pr['variation_id'] ), 'product_cat', array( "fields" => "names" )) );
				}
				
				$name_cat = array_unique($name_cat);
				$_name_cat = implode( ', ', $name_cat );
				
				$weight = round( $weight, 3);

				$_del_data = $this->comp_get_post('billing_recipient_time_date', $this->order_id);
				$_activ_r_n = $this->comp_get_post('billing_recipient_time_from', $this->order_id);
				$_activ_r_m = $this->comp_get_post('billing_recipient_time_to', $this->order_id);
				
				$cont_name = $this->comp_get_post('billing_last_name', $this->order_id) . ' ' . $this->comp_get_post('billing_first_name', $this->order_id);
				$address_to = $this->comp_get_post('billing_city', $this->order_id) . ", " . $this->comp_get_post('billing_address_1', $this->order_id) . " " . $this->comp_get_post('billing_address_2', $this->order_id);
				$address_to = trim($address_to);
				$address_to = trim($address_to, ',');
				$tz = get_option('timezone_string');
				$tz = $tz ? $tz : 'Europe/Moscow';
				date_default_timezone_set($tz);
				$a = explode(':', $this->actual);
				if(sizeof($a) > 1) {
					$a1 = $a[0];
					$a2 = (int) $a[1];
				}
				
				$t = explode(':', $this->tomorrow_zab);
				$time = time();
				$H =(int) date( "H", $time );
				$m = ( date( "i", $time ));
				$t1 = $m+1;
				$bool_del_data = true;
				if(!empty($_del_data)) {
					$d = explode('-', $_del_data);
					$bool_del_data = isset($d[2]) &&  ( (int) date( "d", $time ) < $d[2] ) ? false : true;
				}
				if( ( $H < $a1 || ($H == $a1 && $t1 < $a2 ) ) && $bool_del_data ) {
					$H = $H + ($t1/60);
					if(!empty($_activ_r_n)) {
						$_a = explode(':', $_activ_r_n);
						$_am = explode(':', $_activ_r_m);
						if($_a[0] < ($t[0]+2) ) {
							if($t[0] >= ($H + 1) ) {
								$_a_ = $t[0]+2;
								if( ($_am[0] - $_a_) < 1)
									$r_m = $_a_ + 1;
								else 
									$r_m = $_am[0];
							} else {
								$_a_ = $H+3;
								if( ($_am[0] - $_a_) < 1)
									$r_m = $_a_ + 1;
								else 
									$r_m = $_am[0];
							}
						} else {
							$r_m = $_am[0];
							if($this->interval_to_zab !== '') {
								if( $t[0] >= ($H + 1) ) {
									if( ($t[0]+2) < $_a[0] ) {
										$_a_ = $_a[0];
										if( ($t[0]+3) < $_a[0] ) {
											$t1 = '00';
										}
									} elseif( ($t[0]+3) < $r_m ) {
										$_a_ = $r_m - 1;
									} else {
										$_a_ = $t[0]+2;
										$r_m = $_a_ + 1;
									}
								} else {
									if( ($H+3) < $_a[0] ) {
										$_a_ = $_a[0];
										if( ($H+4) < $_a[0] ) {
											$t1 = '00';
										}
									} elseif( ($H+4) < $r_m ) {
										$_a_ = $r_m - 1;
									} else {
										$_a_ = $H+3;
										$r_m = $_a_ + 1;
									}
								}
							} else {
								$_a_ = $r_m - 1;
							}
							$t1 = '00';
						}
					} else {
						if( $t[0] >= ($H + 1) ) {
							$_a_ = $t[0]+2;
							$r_m = $_a_ + 1;
						} else {
							$_a_ = $H + 3;
							$r_m = $_a_ + 1;
						}
					}
					if( ( $_a_ - $H )  > 2) {
						if($H < $t[0])
						$from = $t[0];
						else
						$from = $H + 1;
					} else {
						if( ($H+1) < $t[0]) {
							$from = $t[0];
							$_a_ = $t[0] + 2;
							$r_m = $_a_+ 1;
						} else {
							$from = $H + 1;
							$_a_ = $H + 3;
							$r_m = $_a_+ 1;
						}
					}
					if($this->interval_to_zab === '') {
						$to = $_a_ - 1;
					} else {
						if(($this->interval_to_zab + 1 + $H) >=  ($from + 1) && ($this->interval_to_zab + 1+ $H) <=  ($_a_ - 1) ) {
							$to = $this->interval_to_zab + 1 + $H;
						} 
						elseif ( ( $_a_ - $H )  > 2 && $H < $t[0]) {
							if(($this->interval_to_zab + $t[0]) >=  ($from + 1) && ($this->interval_to_zab + $t[0]) <=  ($_a_ - 1) ) {
								$to = $this->interval_to_zab + $t[0];
							}
						} elseif( !(( $_a_ - $H )  > 2 ) && ($H+1) < $t[0] ) {
							if(($this->interval_to_zab + $t[0]) >=  ($from + 1) && ($this->interval_to_zab + $t[0]) <=  ($_a_ - 1) ) {
								$to = $this->interval_to_zab  + $t[0];
							}
						} elseif((floor(($_a_ - 1)) - floor($from)) >= 1)
							$to = $_a_ - 1;
						else $to = $from + 1;
					}
					$from = floor($from);
					$t2 = $t1;
					if($from - 1 <= (int) date( "H", $time ) ) {
						$t1 = $m+1;
					}
					
					$to =   floor($to);
					$_a_ =  floor($_a_);
					$r_m =  floor($r_m);
					
					if( ($to - $from) < 2 && ($_a_ - $to) < 2 && $from - 1 <= (int) date( "H", $time ) ) {
						$t2 = $t1;
					} else {
						$t2 = '00';
					}
					
					if(($to - $from) <= 1) {
						$tss = $t1;
					} else $tss = $t2;
					
					if($t1 < 10 && $t1 !== '00') $t1 = '0'.$t1;
					if($tss < 10 && $tss !== '00') $tss = '0'.$tss;
					if($t2 < 10 && $t2 !== '00') $t2 = '0'.$t2;
					
					if($from < 10 && $from !== '00') $from = '0'.$from;
					if($to < 10 && $to !== '00') $to = '0'.$to;
					if($_a_ < 10 && $_a_ !== '00') $_a_ = '0'.$_a_;
					if($r_m < 10 && $r_m !== '00') $r_m = '0'.$r_m;
					
					$sender_time_from = date( "Y-m-d {$from}:{$t1}:00", $time  );
					$sender_time_to = date( "Y-m-d {$to}:{$tss}:00", $time );
					$recipient_time_from = date( "Y-m-d {$_a_}:{$t2}:00", $time );
					$recipient_time_to = date( "Y-m-d {$r_m}:{$t2}:00", $time );
				} else {
					$H = $H + ($t1/60);
					if(!empty($_activ_r_n)) {
						$_a = explode(':', $_activ_r_n);
						$_am = explode(':', $_activ_r_m);
						if($_a[0] < ($t[0]+2) ) {
							$_a_ = $t[0]+2;
							if( ($_am[0] - $_a_) < 1)
								$r_m = $_a_ + 1;
							else 
								$r_m = $_am[0];
							
						} else {
							$r_m = $_am[0];
							if($this->interval_to_zab !== '') {
								if( ($t[0]+2) < $_a[0] ) {
									$_a_ = $_a[0];
								} elseif( ($t[0]+3) < $r_m ) {
									$_a_ = $r_m - 1;
								} else {
									$_a_ = $t[0]+2;
									$r_m = $_a_ + 1;
								}
							} else {
								$_a_ = $r_m - 1;
							}
							$t1 = '00';
						}
					} else {
						$_a_ = $t[0]+2;
						$r_m = $_a_ + 1;
					}
					
					$from = $t[0];
					
					if($this->interval_to_zab === '') {
						$to = $_a_ - 1;
					} else {
						if(($this->interval_to_zab + $from) >=  ($from + 1) && ($this->interval_to_zab + $from) <=  ($_a_ - 1) ) {
							$to = $this->interval_to_zab + $from ;
						} elseif( (floor(($_a_ - 1)) - floor($from)) >= 1)
						$to = $_a_ - 1;
						else $to = $from+1;
					}
					if($from == $t[0]) {
						$ts = $t[1];
						if($this->interval_to_zab === '') {
							$tss = $t1;
						} else {
							$tss = $ts;
						}
					} else $ts = $t1;
					$to =   floor($to);
					$_a_ =  floor($_a_);
					$r_m =  floor($r_m);
					
					$t2 = $t1;
					if($t1 === '00' && ($to - $from) < 2  && ($ts+0) > 0) {
						if( ($t[1] + 0) > 0 )
							$t1 = $t[1];
						else 
							$t1 = '00';
					}
					if($t2 === '00' && ($to - $from) < 2 && ($_a_ - $to) < 2  && ($ts+0) > 0) {
						if( ($t[1] + 0) > 0 )
							$t2 = $t1;
						else 
							$t2 = '00';
					}
					if($ts < 10 && $ts !== '00') $ts = '0'.$ts;
					if($tss < 10 && $tss !== '00') $tss = '0'.$tss;
					if($t1 < 10 && $t1 !== '00') $t1 = '0'.$t1;
					if($t2 < 10 && $t2 !== '00') $t2 = '0'.$t2;
					
					if($from < 10 && $from !== '00') $from = '0'.$from;
					if($to < 10 && $to !== '00') $to = '0'.$to;
					if($_a_ < 10 && $_a_ !== '00') $_a_ = '0'.$_a_;
					if($r_m < 10 && $r_m !== '00') $r_m = '0'.$r_m;
					
					$sender_time_from = date( "Y-m-d {$from}:{$ts}:00", $time + 24 * 3600  );
					$sender_time_to = date( "Y-m-d {$to}:{$tss}:00", $time + 24 * 3600 );
					if($bool_del_data) {
						$recipient_time_from = date( "Y-m-d {$_a_}:{$t2}:00", $time + 24 * 3600 );
						$recipient_time_to = date( "Y-m-d {$r_m}:{$t2}:00", $time + 24 * 3600 );
					} else {
						if( ( $d[2] - (int) date( "d", $time + 24 * 3600 ) ) > 0  ) {
							$to += 1;
							$sender_time_to = date( "Y-m-d {$to}:{$tss}:00", $time + 24 * 3600 );
						}
						$recipient_time_from = date( "Y-m-d {$_a_}:{$t2}:00", $time + 24 * 3600 );
						$recipient_time_to = date( "Y-m-d {$r_m}:{$t2}:00", $time + 24 * 3600 );
						
						$recipient_time_from = str_replace( date( "Y-m-d", $time + 24 * 3600 ), $_del_data, $recipient_time_from);
						$recipient_time_to = str_replace( date( "Y-m-d", $time + 24 * 3600 ), $_del_data, $recipient_time_to);
					}
				}
				$order_numb = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} where {$wpdb->postmeta}.post_id = {$this->order_id} AND {$wpdb->postmeta}.meta_key = '_order_number'");
				$order_numb = empty($order_numb) ? $this->order_id : $order_numb;
				if(!in_array(get_woocommerce_currency() , array('RUB', 'RUR'))) {
					global $sitepress, $woocommerce_wpml;
					if(is_object($sitepress)) {
						if(is_object($woocommerce_wpml)) {
							$woocommerce_currency = $woocommerce_wpml->settings["default_currencies"][$sitepress->get_current_language()];
							$_kurs = $woocommerce_wpml->settings["currency_options"][$woocommerce_currency]["rate"];		
						}
						else $woocommerce_currency = get_woocommerce_currency(); 
					} else $woocommerce_currency = get_woocommerce_currency();
					$curensy_conv_shipping = get_transient( '_wc_session_curensy_conv_shipping' );
					$kurs =  $curensy_conv_shipping ? $curensy_conv_shipping : get_currency_rate_shipping('RUB', $woocommerce_currency);
					if(  ! $curensy_conv_shipping && $kurs > 0 ) {
						set_transient( '_wc_session_curensy_conv_shipping', $kurs , 60*60*6 );
					}
				} else $kurs = false;
				$kurs =  ($kurs !== false ) ? $kurs : 1;
				$param = array(
					'client_id' => $this->api_id,
					'token' => $this->api_token,
					'matter' => $_name_cat,
					'insurance' => (int)( round ( ( $order->order_total - $order->order_shipping ) / $kurs, 2) > 50000 ? 50000 : round ( ( $order->order_total - $order->order_shipping ) / $kurs, 2) ),
					'point' => array( 
						'0' => 
						array(
							'required_time_start' => $sender_time_from,
							'required_time' => $sender_time_to,
							'contact_person' => $this->contact_person, // нет
							'phone' => $this->phone,
							'address' => $this->region_iz,
							'note' => $this->description, // нет
							), 
						'1' => 
						array(
							'required_time_start' => $recipient_time_from,
							'required_time' => $recipient_time_to,
							'contact_person' => $cont_name,
							'phone' => $this->comp_get_post('billing_phone', $this->order_id),
							'address' => rtrim( $address_to, ', '),
							'weight' => $weight, // нет
							'taking' => round($order->order_shipping / $kurs, 2) // нет
							)
						)
				);
				$param['point']['1']['client_order_id'] = $order_numb;
				date_default_timezone_set('UTC');
				
				$m = '';
				$req = array('address', 'phone', 'required_time', 'required_time_start');
				foreach($param['point']['0'] as $k => $v) {
					if(in_array($k, $req) && empty($v)) {
						$m .= '(0)Ошибка (Dostavista). Параметр <strong>' . $k . '</strong> обязательный. <br /> ';
					}
				}
				foreach($param['point']['1'] as $k => $v) {
					if(in_array($k, $req) && empty($v)) {
						$m .= '(1)Ошибка (Dostavista). Параметр <strong>' . $k . '</strong> обязательный. <br /> ';
					}
				}
				if(!empty($m))
					if(isset($_POST['api_action'])) {
						print( json_encode( array( 'success' => false , "data" => array( "message" => 'Ошибка (Dostavista). ' . $m ) ) ) );
						exit;
					} else {
						print($m);
						exit;
					}
				
				
				$url = $this->is_test ? 'https://robotapitest.dostavista.ru/bapi/order' : 'https://dostavista.ru/bapi/order';
					
					
					$response = self::request(
						$param,
						$url
					);
					
					if(isset($response['error_code'])) {
						$result['result']  = 0;
						$result['data']  = 'Код ошибки: ' . implode( ', ', $response['error_code'] ) . '. ' . implode( ', ', $response['error_message']) . print_r ($param, true) ;
						if(isset($_POST['api_action']))
						die(json_encode( $result ));
						else
						die( $result['data'] . ' <br />');
					} else {
						
						$shipping_total = round( $kurs * $response["payment"], 2 );
						
					}
				
				
				if( $response['result'] ) {
					if(isset( $response["order_id"] )) {
						if($kurs != 1) {
							$result["payment"] = $result["payment"] . 'руб. или ' . $shipping_total . $woocommerce_currency ;
						} else 	$result["payment"] = $result["payment"] . 'руб.';
						update_post_meta( $this->order_id, '_dostavista_order_id', $response["order_id"] );
						$result = $response;
					} else {
						$m = implode('<br />', $result);
						$result['result']  = 0;
						$result['data']  = 'Нет номера заказа в системе сервиса Dostavista.';
						if(isset($_POST['api_action']))
						die(json_encode( $result ));
						else
						die( $result['data'] . ' <br />' . $m);
					}
				} else {
					$m = implode('<br />', $result);
					$result['result']  = 0;
					$result['data']  = 'Нет номера заказа в системе сервиса Dostavista.';
					if(isset($_POST['api_action']))
					die(json_encode( $result ));
					else
					die( $result['data'] . ' <br />' . $m);
				}
				$tz = get_option('timezone_string');
				$tz = $tz ? $tz : 'Europe/Moscow';
				date_default_timezone_set($tz);
				update_post_meta( $this->order_id, '_billing_recipient_time_from', date('H:i', strtotime($recipient_time_from) ) );
				update_post_meta( $this->order_id, '_billing_recipient_time_to', date('H:i', strtotime($recipient_time_to) ) );
				date_default_timezone_set('UTC');
				if( isset($_POST['api_action']) ) {
					$result['time_interval'] = date('H:i', strtotime($recipient_time_from) ) . '&nbsp;&mdash;&nbsp;' . date('H:i', strtotime($recipient_time_to) );
					die( json_encode($result) );
				}
			}
			function order_action_post () {
				
				if($_POST['api_action'] == 'delete') {
					$o_id = $_POST['shop_id'];
					unset( $_POST['api_action'],  $_POST['shop_id'] );
					$url = $this->is_test ? 'https://robotapitest.dostavista.ru/bapi/cancel-order' : 'https://dostavista.ru/bapi/cancel-order';
					
					
				
					if($_POST['order_id']) {
						
						$response = self::request(
							$_POST,
							$url
						);
						header("Content-Type: application/json");
						$result = $response;
						if(!$result['result']) {
							$result['data'] = 'Код ошибки: ' . implode (', ', $result['error_code']) . '. ' . implode (', ', $result['error_message']);
							update_post_meta($o_id, '_dostavista_order_id', '');
						} else {
							update_post_meta($o_id, '_dostavista_order_id', '');
						}
					} else {
						$result['result']  = 0;
						$result['data']  = 'Нет номера заказа в системе сервиса Dostavista.';
					}
					
					$_result = $result;
				} elseif($_POST['api_action'] == 'view') {
					if($_POST['order_id']) {
						$url = $this->is_test ? 'https://robotapitest.dostavista.ru/bapi/order/%s' : 'https://dostavista.ru/bapi/order/%s';
						$post = $_POST;
						unset($post['order_id'], $post['api_action']);
						$response = wp_remote_get( sprintf( $url, $_POST['order_id']) . '/?' . http_build_query($post) , array(
							'timeout' => 45,
							'httpversion' => '1.1',
							'blocking' => true,
							'headers' => array(),
							'body' => array(),
							'cookies' => array(),
							'sslverify' => false
						));
						if( !is_object($response) && $response["response"]["code"] == 200 && $response["response"]["message"] == "OK") {
							header("Content-Type: application/json");
							$result = json_decode( $response["body"], true);
							if(!$result['result']) {
								$result['data'] = 'Код ошибки: ' . implode (', ', $result['error_code']) . '. ' . implode (', ', $result['error_message']);
							} else {
								if(!in_array(get_woocommerce_currency() , array('RUB', 'RUR'))) {
									global $sitepress, $woocommerce_wpml;
									if(is_object($sitepress)) {
										if(is_object($woocommerce_wpml)) {
											$woocommerce_currency = $woocommerce_wpml->settings["default_currencies"][$sitepress->get_current_language()];
											$_kurs = $woocommerce_wpml->settings["currency_options"][$woocommerce_currency]["rate"];		
										}
										else $woocommerce_currency = get_woocommerce_currency(); 
									} else $woocommerce_currency = get_woocommerce_currency();
									$curensy_conv_shipping = get_transient( '_wc_session_curensy_conv_shipping' );
									$kurs =  $curensy_conv_shipping ? $curensy_conv_shipping : get_currency_rate_shipping('RUB', $woocommerce_currency);
									if(  ! $curensy_conv_shipping && $kurs > 0 ) {
										set_transient( '_wc_session_curensy_conv_shipping', $kurs , 60*60*6 );
									}
								} else $kurs = false;
								$kurs =  ($kurs !== false ) ? $kurs : 1;
								$shipping_total = round( $kurs * $result['order']['cost'], 2 );
								if($kurs != 1) {
									$result['order']['cost'] = $result['order']['cost'] . 'руб. или ' . $shipping_total . $woocommerce_currency ;
								}
								$st = array(
								0 => ' (заказ принят системой, доступен для просмотра [модерации] диспетчерами)',
								1 => ' (заказ опубликован в системе для просмотра курьерами)',
								2 => ' (курьер назначен)',
								3 => '',
								10 => '',
								16 => ' (оператор ожидает уточнения информации от клиента)'
								);
								$result['html'] = 'Статус: <strong>' .  $result['order']['status_name'] . '</strong>'. ( isset($st[ $result['order']['status'] ]) ? $st[ $result['order']['status'] ]: $result['order']['status']) .  ',<br /> Стоимость: ' . $result['order']['cost']. ',<br /> Создан: ' . $result['order']['created'] . ',<br /> ' . (empty($result['order']['require_car']) ? "можно пешком": "только на машине" ) . ',<br /> Телефон курьера: ' . $result['order']['phone'] . ',<br /> Имя курьера: ' . $result['order']['name']; // . '<br /> Инструкции клиенту:<br /><em>' . $result['order']['points']['1']['instructions'] . '</em>'
							}
						}
					} else {
						$result['result']  = 0;
						$result['data']  = 'Нет номера заказа в системе сервиса Dostavista.';
					}
					
					$_result  = $result;
					/* if( $result['success'] ) {
						$_result['success'] = $result['success'];
						$keys = array( "id" => "ID Заказа",
						 "from_name" => "Имя отправителя",
						 "from_phone" => "Телефон отправителя",
						 "from_address" => "Адрес отправителя",
						 "sender_time_from" => "Время забора с",
						 "sender_time_to" => "Время забора по",
						 "comment_from" => "Комментарий отправителя",
						 "sender_sum" => "Оплата доставки отправителем",
						 "to_name" => "Имя получателя",
						 "to_phone" => "Телефон получателя",
						 "to_address" => "Адрес получателя",
						 "recipient_time_from" => "Время доставки с",
						 "recipient_time_to" => "Время доставки по",
						 "comment_to" => "Комментарий получателя",
						 "receiver_sum" => "Оплата доставки получателем",
						 "create_day" => "Время создания заказа",
						 "weight" => "Вес",
						 "evaluate" => "Оценка стоимости груза",
						 "total" => "Цена заявки",
						 "status" => "Статус",
						 "item_cost" => "Сумма кассового сопровождения"
						);
						$html = '<table>';
						foreach($result["data"] as $key => $v) {
							if( in_array( $key, array_keys($keys) ) ) {
								if ($key == 'weight' ) $v = round($v, 3) . ' кг';
								$html .= '<tr><th>' . $keys[$key] . '</th><td>' . $v . '</td></tr>';
							} elseif(	'items' == $key ) {
								foreach($v as $__v) {
									$__v['weight'] = round( $__v['weight'], 3);
									$text = "<em>{$__v['name']}</em> <strong>x</strong> {$__v['quantity']} (вес: {$__v['weight']} кг, цена: {$__v['price_to_take']} р.) <br />";
								}
								
								$html .= '<tr><th>Позиции</th><td>' . $text . '</td></tr>';
							}
						}
						$html .= '</table>';
						$_result['html'] = $html; 
					} else {
						$_result = $result; 
					}*/
				}
				die(json_encode($_result));
			}
			static function create_box_content ($order) {
				global $post;
				$order =  new WC_Order( $post->ID );
				$settings = get_option("woocommerce_" . __CLASS__ . "_settings", array('title' => '') );
				$api_id  = empty( $settings['api_id'] ) ? '' : $settings['api_id'];
				$api_token  = empty( $settings['api_token'] ) ? '' : $settings['api_token'];
				$title = $settings['title'];
				$order_id = method_exists($order, 'get_id') ? $order->get_id(): $order->id;
				if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
					$pickup_location_array = get_post_meta($order_id, '_dostavista_order_id', true);	
					$is_true = ( $order->get_shipping_method() == $title  ) ;
				} else {
					$pickup_location_array = get_post_meta($order_id, '_dostavista_order_id', true);	
					$is_true = ( $order->shipping_method == __CLASS__  ); 
				}
				
				if(!$is_true) { echo '<style>div#saphali-wc-dostavista.postbox {display: none; } </style>'; return;}
				?>
				<style>
				.address > span#date_value {
					display: inline-block;
					font-style: italic;
					margin-right: 8px;
				}
				</style>
				<ul class="woocommerce-dotochki">
				<?php if( $pickup_location_array )  { ?>
				<li> <a href="#" class="button order-status-dostavista"><?php  _e('Удалить заказ в Dostavista', 'saphali_delivery_dostavista'); ?></a><br /> Причина: <select name="pr" id='pr'>
				<option value=""></option>
				<option value="1">Не нашли курьера</option>
				<option value="3">Пропала необходимость</option>
				<option value="4">Добавление адреса в другой заказ</option>
				</select>  </li>
				<li> <a href="#" class="button order-status-dostavista_info"><?php  _e('Информация по заказу', 'saphali_delivery_dostavista'); ?></a> </li>
				<?php 
				}  else { ?>
				<li style="display: none;"> <a href="#"  class="button order-status-dostavista"><?php  _e('Удалить заказ в Dostavista', 'saphali_delivery_dostavista'); ?></a> <br />Причина: <select name="pr" id='pr'>
				<option value=""></option>
				<option value="1">Не нашли курьера</option>
				<option value="3">Пропала необходимость</option>
				<option value="4">Добавление адреса в другой заказ</option>
				</select></li>
				<li style="display: none;"> <a href="#" class="button order-status-dostavista_info"><?php  _e('Информация по заказу', 'saphali_delivery_dostavista'); ?></a> </li>
				<?php 
				}
				
				if( !$pickup_location_array ) { ?>
				<li> <a href="#" class="button order-status-dostavista_create"><?php  _e('Создать заказ в Dostavista', 'saphali_delivery_dostavista'); ?></a> </li>
				<?php } else { ?>
				<li style="display: none;"> <a href="#" class="button order-status-dostavista_create"><?php  _e('Создать заказ в Dostavista', 'saphali_delivery_dostavista'); ?></a> </li>
				<?php } ?> 
				</ul>
				<div class="dotochki-info"></div>
				<style>
				.dotochki-info .data th, .data td {
					border-bottom: 1px solid;
				}
				.dotochki-info .data th {
					border-right: 1px solid;
				}
				#order_data .order_data_column ._billing_recipient_time_date_field {
					clear: both;
					width: 100%;
				}
				</style>
				<script>
				jQuery( document.body ).on( 'wc-init-datepickers', function() {
					jQuery( '#_billing_recipient_time_date' ).datepicker({
						dateFormat: 'yy-mm-dd',
						numberOfMonths: 2,
						showButtonPanel: true,
						minDate: 0,
						closeText: 'Закрыть',
						prevText: '&#x3C;Пред',
						nextText: 'След&#x3E;',
						currentText: 'Сегодня',
						monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
						'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
						monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
						'Июл','Авг','Сен','Окт','Ноя','Дек'],
						dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
						dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
						dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
						weekHeader: 'Нед',
						firstDay: 1,
					});
				});
				
				jQuery('body').delegate('a.order-status-dostavista', 'click', function(event) {
					event.preventDefault();
					var t = confirm('Вы уверены, что хотите удалить заказ в Dostavista?');
					if(!t) return;
					jQuery(".dotochki-info").html('');
					var _this = jQuery(this);
					_this.parent().parent().parent().find('.data').remove();
					_this.parent().parent().parent().block({'message': 'обработка запроса', css: {'opacity': ".9", 'background': '#fff'}});
					jQuery.ajax({
						type: 'post',
						url: "<?php echo admin_url( 'admin-ajax.php' ); ?>?action=wc_order_action_post_dostavista",
						crossDomain: true,
						dataType: "json",
						data:{ 'client_id': '<?php echo $api_id; ?>', 'token': '<?php echo $api_token; ?>' , 'order_id': '<?php echo $pickup_location_array; ?>', 'substatus_id': jQuery("select#pr").val(), api_action: 'delete', shop_id: '<?php echo $order_id; ?>' },
						success: function (data) {
							if( typeof data != 'object' ){
								jQuery(".dotochki-info").html('<div class="data">' + 'Нет соединения с сервером пунктов выдачи!<br />' + "</div>");
								_this.parent().parent().parent().unblock();
							} else {
								var html = '';
								if( data.result ) {
									jQuery("a.order-status-dostavista").parent().hide();
									jQuery("a.order-status-dostavista_info").parent().hide();
									jQuery("a.order-status-dostavista_create").parent().show();
									html = 'Заказ в Dostavista удален!<br />';
								} else {
									html = data.data;
								}
								jQuery(".dotochki-info").html('<div class="data">' + html + "</div>");
								_this.parent().parent().parent().unblock();
							}
						},
						error: function (data) {
							if( typeof data != 'object' ){
								jQuery(".dotochki-info").html('<div class="data">' + 'Нет соединения с сервером!<br />' + "</div>");
							} else {
								console.log(data);
								var html = 'Ошибка при соединении с сервером!<br />';
								jQuery(".dotochki-info").html('<div class="data">' + html + "</div>");
								_this.parent().parent().parent().unblock();
							}
						}
					});
				});
				jQuery('body').delegate('a.order-status-dostavista_info', 'click', function(event) {
					event.preventDefault();
					var _this = jQuery(this);
					_this.parent().parent().parent().find('.data').remove();
					_this.parent().parent().parent().block({'message': 'обработка запроса', css: {'opacity': ".9", 'background': '#fff'}});
					jQuery.ajax({
						type: 'post',
						url: "<?php echo admin_url( 'admin-ajax.php' ); ?>?action=wc_order_action_post_dostavista",
						crossDomain: true,
						dataType: "json",
						data:{ 'client_id': '<?php echo $api_id; ?>', 'token': '<?php echo $api_token; ?>' , 'order_id': '<?php echo $pickup_location_array; ?>', 'show-points': 1, 'api_action': 'view' },
						success: function (data) {
							if( typeof data != 'object' ){
								jQuery(".dotochki-info").html('<div class="data">' + 'Нет соединения с сервером пунктов выдачи!<br />' + "</div>");
								_this.parent().parent().parent().unblock();
							} else {
								if( data.result ) {
									jQuery(".dotochki-info").html('<div class="data">' + data.html + "</div>");
								} else {
									if(typeof data.data != 'undefined')
									jQuery(".dotochki-info").html('<div class="data">' +  data.data + "</div>");
								}
								_this.parent().parent().parent().unblock();
							}
						},
						error: function (data) {
							if( typeof data != 'object' ){
								jQuery(".dotochki-info").html('<div class="data">' + 'Нет соединения с сервером!<br />' + "</div>");
							} else {
								console.log(data);
								var html = 'Ошибка при соединении с сервером!<br />';
								jQuery(".dotochki-info").html('<div class="data">' + html + "</div>");
								_this.parent().parent().parent().unblock();
							}
						}
					});
				});
				
				jQuery('body').delegate('a.order-status-dostavista_create', 'click', function(event) {
					event.preventDefault();
					var t = confirm('Вы уверены, что хотите создать заказ в Dostavista?');
					if(!t) return;
					var _this = jQuery(this);
					_this.parent().parent().parent().find('.data').remove();
					_this.parent().parent().parent().block({'message': 'обработка запроса', css: {'opacity': ".9", 'background': '#fff'}});
					jQuery.ajax({
						type: 'post',
						url: "<?php echo admin_url( 'admin-ajax.php' ); ?>?action=wc_order_action_post_dotochki_up",
						crossDomain: true,
						dataType: "json",
						data:{ 'client_id': '<?php echo $api_id; ?>', 'token': '<?php echo $api_token; ?>', 'order_id': '<?php echo $order_id; ?>', 'api_action': 'create' },
						success: function (data) {
							if( typeof data != 'object' ){
								jQuery('a.order-status-dostavista_create').after('<div class="data">' + 'Нет соединения с сервером пунктов выдачи!<br />' + "</div>");
								_this.parent().parent().parent().unblock();
							} else {
								if( data.result ) {
									var html = '';
									if(typeof data.order_id != 'undefined' ) {
										html = 'Заказ на Dostavista успешно создан. <br /> ID заказа в ситеме Dostavista: ' + data.order_id + '. Время доставки клиенту: ' + data.time_interval + '. <br />' + "Стоимость доставки: " + data.payment;
										jQuery("a.order-status-dostavista").parent().show();
										jQuery("a.order-status-dostavista_info").parent().show();
										jQuery("a.order-status-dostavista_create").parent().hide();
										jQuery("span#time_interval").html(data.time_interval);
									} else {
										html = 'Ошибка создания заказа.';
										if(typeof data.order_id != 'undefined')
										html += ' ' + data.data;
									}
									if(typeof data.order_id != 'undefined' )
									jQuery('a.order-status-dostavista_create').parent().after('<div class="data">' + html + "</div>");
									else 
									jQuery('a.order-status-dostavista_create').after('<div class="data">' + html + "</div>");
									
								} else {
									jQuery('a.order-status-dostavista_create').after('<div class="data">' + data.data + "</div>");
								}
								_this.parent().parent().parent().unblock();
							}
						},
						error: function (data) {
							if( typeof data != 'object' ){
								jQuery('a.order-status-dostavista_create').after('<div class="data">' + 'Нет соединения с сервером!<br />' + "</div>");
							} else {
								console.log(data);
								var html = 'Ошибка при соединении с сервером!<br />';
								jQuery('a.order-status-dostavista_create').after('<div class="data">' + html + "</div>");
								_this.parent().parent().parent().unblock();
							}
						}
					});
				});
				</script>
				
				<?php 
			}
			function range_text($text) {
				$n = ($text%10==1 && $text%100!=11) ? 0 : (($text%10>=2 && $text%10<=4 && ($text%100<10 || $text%100>=20)) ? 1 : 2);
				if($n === 0) return $text . ' час';
				elseif($n === 1) return $text . ' часа';
				else return $text . ' часов';
			}
			
			
			function save(){
				if( isset($_GET['section']) && $_GET['section'] ==  __CLASS__ ) {
					if(isset($_POST['save']) ) {
						$post = get_option("woocommerce_" . $this->id . "_settings" );
						$ar_wiks = array('a'=>0, 'b'=> 0,'c'=>0, 'd'=> 0, 'e'=> 0, 'f'=>0, 'g'=>0);
						$key_wiks = array('a'=>0, 'b'=> 1,'c'=>2, 'd'=> 3, 'e'=> 4, 'f'=>5, 'g'=>6);
						foreach($ar_wiks as $key => $value) {
							if( empty($_POST['wiks'][$key]) ) $_POST["woocommerce_" . $this->id . '_saphali_work_wiks'][ $key_wiks[$key ] ] = $value;
							else $_POST["woocommerce_" . $this->id . '_saphali_work_wiks'][ $key_wiks[$key ] ] = $_POST['wiks'][$key];
						}
						$_POST["woocommerce_" . $this->id . '_enabled'] = isset($_POST["woocommerce_" . $this->id . '_enabled']) ? 'yes' : 'no';
						$_POST["woocommerce_" . $this->id . '_is_test'] = isset($_POST["woocommerce_" . $this->id . '_is_test']) ? 'yes' : 'no';
						foreach($_POST as $k => $v) {
							if ( in_array( str_replace("woocommerce_" . $this->id . '_', '', $k), array( 'theme_kalendar','sender_time_interval_to_zab','sender_time_from_tomorrow_zab','saphali_work_wiks' ) ) )
							$post[str_replace("woocommerce_" . $this->id . '_', '', $k)] = $v;
						}
						update_option("woocommerce_" . $this->id . "_settings", $post );
					}
				}
			}
			public function admin_options() {
				$this->save();
				$this->init_settings();
				$this->action		= isset($this->settings['action']) ? $this->settings['action']: '';
				$this->saphali_work_wiks = isset( $this->settings['saphali_work_wiks']) ?  $this->settings['saphali_work_wiks'] : array(1,1,1,1,1,1,1);
				$this->interval_to_zab		= isset($this->settings['sender_time_interval_to_zab']) ? $this->settings['sender_time_interval_to_zab'] : '';
				$this->tomorrow_zab		= isset($this->settings['sender_time_from_tomorrow_zab']) ? $this->settings['sender_time_from_tomorrow_zab'] : '';
				$this->theme_kalendar		= isset($this->settings['theme_kalendar']) ? $this->settings['theme_kalendar'] : '';
    	?>
		<div class="">
			<h3><?php  echo $this->method_title; ?></h3>
			<table class="form-table">
				<?php
					$this->generate_settings_html();
				?>
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label for="woocommerce_<?php echo __CLASS__; ?>_sender_time_from_tomorrow_zab">Время забора груза доступно с </label>
					</th>
					<td class="forminp" style="">
						<fieldset style="float: left; display: block; width: 30%;">
							<legend class="screen-reader"><span>Время забора груза доступно с </span></legend>
							<select style="" id="woocommerce_<?php echo __CLASS__; ?>_sender_time_from_tomorrow_zab" name="woocommerce_<?php echo __CLASS__; ?>_sender_time_from_tomorrow_zab" class="select ">
							<?php 
							foreach ( range(6, 24) as $r) {
								echo '<option '. ($this->tomorrow_zab == $r . ':00'? 'selected="selected" ': '') .'value="'. $r . ':00">'. $r . ':00</option>';
								echo '<option '. ($this->tomorrow_zab == $r . ':30'? 'selected="selected" ': '') .'value="'. $r . ':30">'. $r . ':30</option>';					}
							?>
							</select>
							<p class="description"><strong>Опция определяет, когда забор осуществляется на следующий день, или на сегодняшний день, к примеру, когда заказ сделан очень рано (напр., в 2 ночи был заказ, и Вы одобрили в 4, но забор нужно назначить с 8 утра) </strong></p>
						</fieldset>
					
						<fieldset style="float:left;display: block;width: 50%;">
							<legend class="screen-reader"><span>Интервал времени забора</span></legend>
							<select style="" id="woocommerce_<?php echo __CLASS__; ?>_sender_time_interval_to_zab" name="woocommerce_<?php echo __CLASS__; ?>_sender_time_interval_to_zab" class="select ">
							<?php 
							$i=0;
							echo '<option '. ($this->interval_to_zab === ''  ? 'selected="selected" ': '') .'value="">Не указывать</option>';
							foreach ( array_map( array($this, 'range_text'), range(1, 24)) as $r) {
								$i++;
								$sender_time_from[$i] = $r;
								echo '<option '. ($this->interval_to_zab == $i && $this->interval_to_zab !== '' ? 'selected="selected" ': '') .'value="'. $i . '">'. $r . '</option>';
							}
							?>
							</select>
							<p class="description"><strong>интервал будет равняться указанному значению, но не меньше часа от времени забора и не больше чем 1 час до времени доставки клиенту. Если выберите "Не указывать", то будет меньше на 1 час от времени доставки клиенту</strong></p>
						</fieldset>
					</td>
				</tr>
				<tr class="visible_product" valign="top">
					<th width="200px"><label for="format_time">Доступные дни недели для доставки</label></th>
					<td>
						<label for="wiks2">Понедельник <input type="checkbox" <?php checked($this->saphali_work_wiks[1], 1); ?> name="wiks[b]" value="1" id="wiks2" /></label><br />
						<label for="wiks3">Вторник &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php checked($this->saphali_work_wiks[2], 1); ?> name="wiks[c]" value="1" id="wiks3" /></label><br />
						<label for="wiks4">Среда  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php checked($this->saphali_work_wiks[3], 1); ?> name="wiks[d]" value="1" id="wiks4" /></label><br />
						<label for="wiks5">Четверг &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" <?php checked($this->saphali_work_wiks[4], 1); ?> name="wiks[e]" value="1" id="wiks5" /></label><br />
						<label for="wiks6">Пятница &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php checked($this->saphali_work_wiks[5], 1); ?> name="wiks[f]" value="1" id="wiks6" /></label><br />
						<label for="wiks7">Суббота  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php checked($this->saphali_work_wiks[6], 1); ?> name="wiks[g]" value="1" id="wiks7" /></label><br />
						<label for="wiks1">Воскресенье &nbsp;<input type="checkbox" <?php checked($this->saphali_work_wiks[0], 1); ?> name="wiks[a]" value="1" id="wiks1" /></label>
					</td>
				</tr>
				<tr class="visible_product" valign="top">
					<th width="200px"><label for="format_time">Внешний вид календаря</label></th>
					<td>

						<select id="woocommerce_<?php echo __CLASS__; ?>_theme_kalendar" name="woocommerce_<?php echo __CLASS__; ?>_theme_kalendar">
						<?php 
							if ($handle = opendir(plugin_dir_path(__FILE__) . 'css')) {
								while( false !== ($_theme = readdir($handle)) )
								{
									if(strpos($_theme, '.') !== false || $_theme == 'images') continue;
								?>
								<option <?php selected($_theme, $this->theme_kalendar); ?> value="<?php echo $_theme; ?>" style="background-image:url(<?php echo  wc_custom_shipping_dostavista::$plugin_url; ?>css/<?php echo $_theme; ?>/images/<?php echo $_theme; ?>.png);background-repeat: no-repeat;height: 80px;margin: 5px 0;cursor: pointer;text-indent: 90px;" > <?php echo ucfirst($_theme); ?></option>
								<?php
								}
							}
						?>
						</select>
						<div class="ui-widget-content_bg"></div>
					</td>
				</tr>
				<tr class="visible_product" valign="top">
					<th width="200px"><label for="format_time">Callback URL</label></th>
					<td>
					<?php echo home_url('/?order_changed_dostavista=1'); ?>
					</td>
				</tr>
			</table>
			<style>
			.ui-widget-content_bg {
				background-repeat: no-repeat;height: 80px;margin: 5px 0;width:100px;
			}
			</style>
			<script>
			// jQuery('select.sender_time').parent().parent().parent().css({"border-style": 'solid solid none', "border-width": '1px 1px medium', "border-color": '#b4c9e0'});
			jQuery('select.sender_time').parent().parent().parent().find('td, th').css({"padding": '5px'});
			jQuery('select#woocommerce_<?php echo $this->id; ?>_action').parent().parent().parent().css({"border-style": 'none solid solid', "border-width": '0 1px 1px', "border-color": '#b4c9e0'});
			jQuery( "#woocommerce_<?php echo __CLASS__; ?>_theme_kalendar" ).change(function() {
				var file = "url(<?php echo wc_custom_shipping_dostavista::$plugin_url; ?>css/"+ jQuery( this ).val() + "/images/"+ jQuery( this ).val() + ".png)";
				jQuery(".ui-widget-content_bg").css({'background-image': file});
			});
			jQuery( "#woocommerce_<?php echo __CLASS__; ?>_theme_kalendar" ).trigger('change');
			</script>
		</div>
			<?php
			}
			function sctipt_foot() {
				if( is_checkout() && !isset( $_GET['key'] ) ) {
					if ( $this->enabled == "no"  ) return false;
					?>
					<link href="<?php echo  wc_custom_shipping_dostavista::$plugin_url; ?>css/<?php echo $this->theme_kalendar; ?>/jquery-ui.min.css" rel="stylesheet" />
					
					<script src="<?php echo  wc_custom_shipping_dostavista::$plugin_url; ?>js/jquery-ui-1.11.4.js"></script>
					
					<script src="<?php echo  wc_custom_shipping_dostavista::$plugin_url; ?>js/i18n/jquery.ui.datepicker-ru.js"></script>

					<?php
					$tz = get_option('timezone_string');
					$tz = $tz ? $tz : 'Europe/Moscow';
					date_default_timezone_set($tz);
					$a = explode(':', $this->actual);
					if(sizeof($a) > 1) {
						$a1 = $a[0];
						$a2 = (int) $a[1];
					}
					$t = explode(':', $this->tomorrow_zab);
					$time = time();
					$H =(int) date( "H", $time );
					$m = ( date( "i", $time ));
					$t1 = $m+1;
					if( $H < $a1 || ($H == $a1 && $t1 < $a2 ) ) {
						$day = 0;
					} else $day = 1;
					?>
					<script type="text/javascript">
					var tmp_select_time_from = jQuery('#billing_recipient_time_from').html();
					var tmp_select_time_to = jQuery('#billing_recipient_time_to').html();
					var tmp_imput_time_data = '';
					<?php 
					$H = $t[0]+2;
					foreach ( range( $H, 21) as $r) {
						if($r == $H)
						$recipient_time_from[$r . ':' . $t[1] ] = $r . ':' . $t[1];
						else
						$recipient_time_from[$r . ':00'] = $r . ':00';
					}
					foreach ( range( $H+1, 22) as $r) {
						if( $r == ($H+1) )
						$recipient_time_to[$r . ':' . $t[1] ] = $r . ':' . $t[1];
						else
						$recipient_time_to[$r . ':00'] = $r . ':00';
					}	
					?>
					var select_time_from = '<?php
						foreach($recipient_time_from as $from)
							echo "<option value=\"{$from}\">{$from}</option>";
					?>';
					var select_time_to = '<?php
						foreach($recipient_time_to as $to)
							echo "<option value=\"{$to}\">{$to}</option>";
					?>';
					var day_actual = <?php echo date('d'); ?>;
					jQuery(document).ready(function($){
						var array_no_date =[];
						$.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );
						$("#billing_recipient_time_date").val('').datepicker({dateFormat: 'yy-mm-dd', minDate:<?php echo $day; ?>, monthNamesShort: [ "Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря" ],
							onClose:function(dateStr,inst){
								var monthValue = inst.selectedMonth+1;
								var dayValue = inst.selectedDay;
								var yearValue = inst.selectedYear;
								if( day_actual == dayValue ) {
									jQuery('#billing_recipient_time_from').html(tmp_select_time_from);
									jQuery('#billing_recipient_time_to').html(tmp_select_time_to);
								} else {
									jQuery('#billing_recipient_time_from').html(select_time_from);
									jQuery('#billing_recipient_time_to').html(select_time_to);
								}
							},
							beforeShowDay: function(date){
								var wik = [<?php echo implode(',', $this->saphali_work_wiks); ?>];
								day_check = wik[ date.getDay() ];
								
								if(day_check != 1 ) return [false];
								var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
								return [ array_no_date.indexOf(string) == -1 ];
							}
						});
						jQuery('body').delegate('#billing_phone, #billing_city', 'change', function(){
							jQuery( 'body' ).trigger( 'update_checkout' );
						});
					});
					</script>
					<?php
				}
				?>
				<script type="text/javascript">
					var saphali_is_hipping_vista = ["<?php echo $this->id ?>"];
					jQuery(document).ready(function($){
						$('body').bind('updated_checkout', function() {
							var m = (
								typeof $("input.shipping_method:checked").val() != 'undefined' ? $("input.shipping_method:checked").val() : (typeof $("select.shipping_method").val() != 'undefined' ? $("select.shipping_method").val() : ( $("input.shipping_method[type=\"hidden\"]").length == 1 ? $("input.shipping_method[type=\"hidden\"]").val() :  ( $("input#shipping_method[type=\"hidden\"]").length == 1 ? $("input#shipping_method[type=\"hidden\"]").val() : '' ) )
							)
							);
							if( $.inArray(m, saphali_is_hipping_vista) > -1 ) {
								jQuery('#billing_recipient_time_from option[value="0"], #billing_recipient_time_to option[value="0"]').remove();
								$("#billing_recipient_time_from_field, #billing_recipient_time_to_field, #billing_recipient_time_date_field").show('slow');
								if(tmp_imput_time_data !== null)
								$("#billing_recipient_time_date").val(tmp_imput_time_data);
								tmp_imput_time_data = null;
							} else {
								$("#billing_recipient_time_from_field, #billing_recipient_time_to_field, #billing_recipient_time_date_field").hide('slow');
								if( jQuery('#billing_recipient_time_from option[value="0"], #billing_recipient_time_to option[value="0"]').length == 0)
								jQuery('#billing_recipient_time_from option:first, #billing_recipient_time_to option:first').before('<option value="0" selected="selected" >Выбрать</option>');
								if(tmp_imput_time_data === null || !tmp_imput_time_data)
								tmp_imput_time_data = $("#billing_recipient_time_date").val();
								$("#billing_recipient_time_date").val('');
							}
						});
						$("#billing_recipient_time_from, #billing_recipient_time_to").change(function() {
							var t = $("#billing_recipient_time_to").val();
							var f = $("#billing_recipient_time_from").val();
							t = parseInt(t.replace(':00', ''));
							f = parseInt(f.replace(':00', ''));
							if( (t - f) < 1 ) {
								$("#billing_recipient_time_to").val( (f + 1) + ':00' );
							}
						});
						$("#billing_phone").blur(function() {
							var t = $(this).val();
							t = t.replace(/[^0-9]/gim,'');
							$(this).val(t);
						});
					});
				</script>
				<?php 
			}
			function woocommerce_cart_shipping_method_full_label($full_label, $method) {
				if( $this->id ==  $method->id && $method->cost <= 0) {
					$full_label = $this->title ;
				}
				return $full_label;
			}
			function init_form_fields(){
				global $woocommerce;

				// Backwards compat
				$sender_time_from_actual = array();
				foreach ( range(6, 24) as $r) {
					$sender_time_from_actual[$r . ':00'] = $r . ':00';
					$sender_time_from_actual[$r . ':30'] = $r . ':30';
				}
				if(version_compare( WOOCOMMERCE_VERSION, '2.0', '<' )) {
				
					$this->form_fields = array(
					'enabled' => array(
									'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
									'type' 			=> 'checkbox',
									'label' 		=> __( 'Enable Dostavista', 'saphali_delivery_dostavista' ),
									'default' 		=> 'yes'
								),
					'title' => array(
									'title' 		=> __( 'Method Title', 'woocommerce' ),
									'type' 			=> 'text',
									'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
									'default'		=> __( 'Доставка через сервис Dostavista', 'saphali_delivery_dostavista' )
								),
					'min_amount' => array(
									'title' 		=> __( 'Minimum Order Amount', 'woocommerce' ),
									'type' 			=> 'text',
									'description' 	=> __('Users will need to spend this amount to get free shipping. Leave blank to disable.', 'woocommerce'),
									'default' 		=> ''
								),
					'fee' => array(
									'title' 		=> __( 'Delivery Fee', 'woocommerce' ),
									'type' 			=> 'number',
									'custom_attributes' => array(
										'step'	=> 'any',
										'min'	=> '0'
									),
									'description' 	=> 'Добавочная стоимость при выборе этого метода доставки.',
									'default'		=> '',
									'desc_tip'      => true,
									'placeholder'	=> '0.00'
								),
					'requires_coupon' => array(
									'title' 		=> __( 'Coupon', 'woocommerce' ),
									'type' 			=> 'checkbox',
									'label' 		=> __( 'Free shipping requires a free shipping coupon', 'woocommerce' ),
									'description' 	=> __('Users will need to enter a valid free shipping coupon code to use this method. If a coupon is used, the minimum order amount will be ignored.', 'woocommerce'),
									'default' 		=> 'no'
								),
					'description' => array(
									'title' 		=> __( 'Примечание для курьера', 'saphali_delivery_dostavista' ),
									'type' 			=> 'textarea',
									'description' 	=> __('Доп. информация: номер офиса или квартиры, как пройти.', 'saphali_delivery_dostavista'),
									'default' 		=> ''
								),
					'api_id' => array(
									'title' 		=> __( 'Идентификатор магазина в системе Dostavista', 'saphali_delivery_dostavista' ),
									'type' 			=> 'text',
									'description' 	=> __('Идентификатор магазина в нашей базе — отображается в url личного кабинета.', 'saphali_delivery_dostavista'),
									'default' 		=> ''
								),
					'api_token' => array(
									'title' 		=> __( 'Секретный код для работы с API в системе Dostavista', 'saphali_delivery_dostavista' ),
									'type' 			=> 'password',
									'description' 	=> __('Первоначально выдается администратором, потом может быть изменен самостоятельно через ЛК.', 'saphali_delivery_dostavista'),
									'default' 		=> ''
								),
					'is_test' => array(
						'title' 		=> __( 'Test mode', 'saphali_delivery_dostavista' ),
						'type' 			=> 'checkbox',
						'label' 		=> __( 'Включить тестовый режим', 'saphali_delivery_dostavista' ),
						'default' 		=> 'yes'
					),
					'require_car' => array(
									'title' 		=> __( 'Тип доставки по умолчанию', 'saphali_delivery_dostavista' ),
									'type' 			=> 'select',
									'description' 	=> __('Зависимость типа от веса: Пеший - ≤15 кг; Легковой а/м - ≤200 кг; Грузовой а/м - без ограничений'),
									'options' 	=> array('0' => 'пешая доставка', '1' => 'легковым автомобилем', '2' => 'грузовым автомобилем' ),
									'default' 		=> '0'
								),
					'raszet' => array(
									'title' 		=> __( 'Задействовать расчет стоимости доставки в системе Dostavista', 'saphali_delivery_dostavista' ),
									'type' 			=> 'checkbox',
									'description' 	=> __('Позволяет рассчитать стоимость доставки'),
									'default' 		=> 'no'
								),
					'region_iz' => array(
									'title' 		=> __( 'Адрес откуда везутся товары заказа', 'saphali_delivery_dostavista' ),
									'type' 			=> 'text',
									'description' 	=> __( 'Адрес забора в общепринятом формате “город, улица, дом”. <br /> Пример:<span style="color:green;"> Москва, Солянка, 13к1, стр.6</span> <br /> Крайне не желательно загромождать адрес словами, полностью идентифицирующими элементы адресной структуры (“город”, “район”, “улица”, “дом”) <br /> Пример <strong>неправильного</strong> написания адреса: <span style="color:red;">город: Москва, район: Таганский, улица: Солянка, дом: 13, корпус: 1, строение: 6</span>', 'saphali_delivery_dostavista' ),
									'default' 		=> ''
								),
					'sender_time_from_actual' => array(
						'title' 		=> __( 'Актуальность времени забора', 'saphali_delivery_dostavista' ),
						'type' 			=> 'select',
						'options' 	=> $sender_time_from_actual,
						'class' 		=> 'sender_time',
						'default' 		=> 1,
						'description' 	=> __('Укажите, до какого времени необходимо совершить действие (<strong>запрос на создание заказа в сервисе доставки</strong>), чтобы он обрабатывался (формировался заказ в курьерской службе) в этот же день. Если действие будет позже указанного времени, то заказы будут обрабатываться на следующий день.', 'saphali_delivery_dostavista'),
					),
					'contact_person' => array(
									'title' 		=> __( 'Имя контактного лица (для связи с курьером)', 'saphali_delivery_dostavista' ),
									'type' 			=> 'text',
									'description' 	=> __( '', 'saphali_delivery_dostavista' ),
									'default' 		=> ''
								),
					'phone' => array(
									'title' 		=> __( 'Телефон для связи с курьером', 'saphali_delivery_dostavista' ),
									'type' 			=> 'text',
									'description' 	=> __( '', 'saphali_delivery_dostavista' ),
									'default' 		=> ''
								)
					);
					if($this->raszet) {
						$this->form_fields['default_weight'] = array(
							'title' 		=> __( 'Масса по умолчанию в на 1 позицию товара, кг', 'woocommerce' ),
							'type' 			=> 'text',
							'label' 		=> __( 'Масса по умолчанию, кг', 'saphali_delivery_dostavista' ),
							'default' 		=> '0.1'
						);
					}
				} else {
				if ( $this->requires_coupon && $this->min_amount )
					$default_requires = 'either';
				elseif ( $this->requires_coupon )
					$default_requires = 'coupon';
				elseif ( $this->min_amount )
					$default_requires = 'min_amount';
				else
					$default_requires = '';
				$this->form_fields = array(
					'enabled' => array(
									'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
									'type' 			=> 'checkbox',
									'label' 		=> __( 'Enable Dostavista', 'saphali_delivery_dostavista' ),
									'default' 		=> 'no'
								),
					'enabled_admin' => array(
									'title' 		=> __( 'Включить только для админа', 'saphali_delivery_dostavista' ),
									'type' 			=> 'checkbox',
									'label' 		=> __( 'Enable Dostavista', 'saphali_delivery_dostavista' ) . " (admin)",
									'default' 		=> 'no'
								),
					'title' => array(
									'title' 		=> __( 'Method Title', 'saphali_delivery_dostavista' ),
									'type' 			=> 'text',
									'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
									'default'		=> __( 'Доставка через сервис Dostavista', 'saphali_delivery_dostavista' ),
									'desc_tip'      => true,
								),			
					'requires' => array(
									'title' 		=> __( 'Dostavista (опции задействования)', 'saphali_delivery_dostavista' ),
									'type' 			=> 'select',
									'default' 		=> $default_requires,
									'options'		=> array(
										'' 				=> __( 'N/A', 'woocommerce' ),
										'min_amount' 	=> __( 'A minimum order amount (defined below)', 'woocommerce' ),
									)
								),
					'min_amount' => array(
									'title' 		=> __( 'Minimum Order Amount', 'woocommerce' ),
									'type' 			=> 'number',
									'custom_attributes' => array(
										'step'	=> 'any',
										'min'	=> '0'
									),
									'description' 	=> __( 'Покупателям необходимо потратить именно столько, чтобы активировать данный метод доставки. Оставьте пустым, чтобы опция была неактивной.', 'woocommerce' ),
									'default' 		=> '0',
									'desc_tip'      => true,
									'placeholder'	=> '0.00'
								),
					'fee' => array(
									'title' 		=> __( 'Delivery Fee', 'woocommerce' ),
									'type' 			=> 'number',
									'custom_attributes' => array(
										'step'	=> 'any',
										'min'	=> '0'
									),
									'description' 	=> 'Добавочная стоимость при выборе этого метода доставки.',
									'default'		=> '',
									'desc_tip'      => true,
									'placeholder'	=> '0.00'
								),
					'description' => array(
									'title' 		=> __( 'Примечание для курьера', 'saphali_delivery_dostavista' ),
									'type' 			=> 'textarea',
									'description' 	=> __('Доп. информация: номер офиса или квартиры, как пройти.', 'saphali_delivery_dostavista'),
									'default' 		=> ''
								),
					'api_id' => array(
									'title' 		=> __( 'Идентификатор магазина в системе Dostavista', 'saphali_delivery_dostavista' ),
									'type' 			=> 'text',
									'description' 	=> __('Идентификатор магазина в нашей базе — отображается в url личного кабинета.', 'saphali_delivery_dostavista'),
									'default' 		=> ''
								),
					'api_token' => array(
									'title' 		=> __( 'Секретный код для работы с API в системе Dostavista', 'saphali_delivery_dostavista' ),
									'type' 			=> 'password',
									'description' 	=> __('Первоначально выдается администратором, потом может быть изменен самостоятельно через ЛК.', 'saphali_delivery_dostavista'),
									'default' 		=> ''
								),
					'is_test' => array(
						'title' 		=> __( 'Test mode', 'saphali_delivery_dostavista' ),
						'type' 			=> 'checkbox',
						'label' 		=> __( 'Включить тестовый режим', 'saphali_delivery_dostavista' ),
						'default' 		=> 'yes'
					),
					'require_car' => array(
									'title' 		=> __( 'Тип доставки по умолчанию', 'saphali_delivery_dostavista' ),
									'type' 			=> 'select',
									'description' 	=> __('Зависимость типа от веса: Пеший - ≤15 кг; Легковой а/м - ≤200 кг; Грузовой а/м - без ограничений'),
									'options' 	=> array('0' => 'пешая доставка', '1' => 'легковым автомобилем', '2' => 'грузовым автомобилем' ),
									'default' 		=> '0'
								),
					'raszet' => array(
									'title' 		=> __( 'Задействовать расчет стоимости доставки в системе Dostavista', 'saphali_delivery_dostavista' ),
									'type' 			=> 'checkbox',
									'description' 	=> __('Позволяет рассчитать стоимость доставки'),
									'default' 		=> 'no'
								),
					'region_iz' => array(
									'title' 		=> __( 'Адрес откуда везутся товары заказа', 'saphali_delivery_dostavista' ),
									'type' 			=> 'text',
									'description' 	=> __( 'Адрес забора в общепринятом формате “город, улица, дом”. <br /> Пример:<span style="color:green;"> Москва, Солянка, 13к1, стр.6</span> <br /> Крайне не желательно загромождать адрес словами, полностью идентифицирующими элементы адресной структуры (“город”, “район”, “улица”, “дом”) <br /> Пример <strong>неправильного</strong> написания адреса: <span style="color:red;">город: Москва, район: Таганский, улица: Солянка, дом: 13, корпус: 1, строение: 6</span>', 'saphali_delivery_dostavista' ),
									'default' 		=> ''
								),
					'sender_time_from_actual' => array(
						'title' 		=> __( 'Актуальность времени забора', 'saphali_delivery_dostavista' ),
						'type' 			=> 'select',
						'options' 	=> $sender_time_from_actual,
						'class' 		=> 'sender_time',
						'default' 		=> 1,
						'description' 	=> __('Укажите, до какого времени необходимо совершить действие (<strong>запрос на создание заказа в сервисе доставки</strong>), чтобы он обрабатывался (формировался заказ в курьерской службе) в этот же день. Если действие будет позже указанного времени, то заказы будут обрабатываться на следующий день.', 'saphali_delivery_dostavista'),
					),
					'contact_person' => array(
									'title' 		=> __( 'Имя контактного лица (для связи с курьером)', 'saphali_delivery_dostavista' ),
									'type' 			=> 'text',
									'description' 	=> __( '', 'saphali_delivery_dostavista' ),
									'default' 		=> ''
								),
					'phone' => array(
									'title' 		=> __( 'Телефон (для связи с курьером)', 'saphali_delivery_dostavista' ),
									'type' 			=> 'text',
									'description' 	=> __( '', 'saphali_delivery_dostavista' ),
									'default' 		=> ''
								)
					 );
					 if($this->raszet) {
						$this->form_fields['default_weight'] = array(
							'title' 		=> __( 'Масса по умолчанию в на 1 позицию товара, кг', 'woocommerce' ),
							'type' 			=> 'text',
							'label' 		=> __( 'Масса по умолчанию, кг', 'saphali_delivery_dostavista' ),
							'default' 		=> '0.1'
						);
					}
				}
			}
			function saphali_custom_billing_fields( $fields ) {
				$tz = get_option('timezone_string');
				$tz = $tz ? $tz : 'Europe/Moscow';
				date_default_timezone_set($tz);
				$a = explode(':', $this->actual);
				if(sizeof($a) > 1) {
					$a1 = $a[0];
					$a2 = (int) $a[1];
				}
				$t = explode(':', $this->tomorrow_zab);
				$time = time();
				$time = $time + 60*20;
				$H =(int) date( "H", $time );
				$m = ( date( "i", $time ));
				$t1 = $m+1;
				$time = $time - 60*20;
				if( $H < $a1 || ($H == $a1 && $t1 < $a2 ) ) {
					$H =(int) date( "H", $time + (4 * 3600) );
					$is_today = '';
					
					foreach ( range( $H, 21) as $r) {
						$recipient_time_from[$r . ':00'] = $r . ':00';
					}
					foreach ( range( $H+1, 22) as $r) {
						$recipient_time_to[$r . ':00'] = $r . ':00';
					}
				} else {
					$H = $t[0]+2;
					$is_today = ''; //'<small>' . __(' (доставка со следующего дня)', 'saphali_delivery_dostavista') . '</small>';
					foreach ( range( $H, 21) as $r) {
						if($r == $H)
						$recipient_time_from[$r . ':' . $t[1] ] = $r . ':' . $t[1];
						else
						$recipient_time_from[$r . ':00'] = $r . ':00';
					}
					foreach ( range( $H+1, 22) as $r) {
						if( $r == ($H+1) )
						$recipient_time_to[$r . ':' . $t[1] ] = $r . ':' . $t[1];
						else
						$recipient_time_to[$r . ':00'] = $r . ':00';
					}			
				}
				
				date_default_timezone_set('UTC');
				
				$fields['billing_recipient_time_date'] = array(
					'label'     => __('Дата доставки', 'saphali_delivery_dostavista') . $is_today,
					'required'  => false,
					'class'     => array('form-row-wide', 'recipient_date_from'),
					'clear'     => true,
					'type'     => 'text'
				 );
				$fields['billing_recipient_time_from'] = array(
					'label'     => __('Время доставки от', 'saphali_delivery_dostavista'),
					'required'  => false,
					'class'     => array('form-row-first', 'recipient_time_from'),
					'clear'     => false,
					'type'     => 'select',
					'options'     => $recipient_time_from
				 );
				$fields['billing_recipient_time_to'] = array(
					'label'     => __('Время доставки до', 'saphali_delivery_dostavista'),
					'required'  => false,
					'class'     => array('form-row-last', 'recipient_time_to'),
					'clear'     => true,
					'type'     => 'select',
					'options'     => $recipient_time_to
				 );
				
				 return $fields;
			}
			function init() {
			global $woocommerce;

				$this->init_settings(); 
				// Define user set variables
				$this->enabled		= isset($this->settings['enabled']) ? $this->settings['enabled'] : '';
				$this->enabled_admin		= isset($this->settings['enabled_admin']) ? $this->settings['enabled_admin'] : '';
				$this->title 		= isset($this->settings['title']) ? $this->settings['title'] : '';
				if($this->enabled_admin == 'yes' && !is_super_admin() ) $this->enabled = 'no';
				$this->min_amount 	= isset($this->settings['min_amount']) ? $this->settings['min_amount'] : '';
				if(!version_compare( WOOCOMMERCE_VERSION, '2.0', '<' )) {
					@$this->availability = $this->settings['availability'];
					@$this->countries 	= $this->settings['countries'];
				}
				$this->fee			= isset($this->settings['fee']) ? $this->settings['fee'] : '';
				@$this->description  = isset($this->settings['description']) ? $this->settings['description'] : '';
				@$this->api_id  = empty( $this->settings['api_id'] ) ? '' : $this->settings['api_id'];
				@$this->is_test		= $this->settings['is_test'] == 'yes';
				@$this->actual		= $this->settings['sender_time_from_actual'];
				@$this->theme_kalendar = isset($this->settings['theme_kalendar']) ? $this->settings['theme_kalendar']: 'redmond';
				@$this->saphali_work_wiks = isset( $this->settings['saphali_work_wiks']) ?  $this->settings['saphali_work_wiks'] : array(1,1,1,1,1,1,1);
				@$this->tomorrow_zab		= $this->settings['sender_time_from_tomorrow_zab'];
				@$this->interval_to_zab		= $this->settings['sender_time_interval_to_zab'];
				@$this->api_token  = empty( $this->settings['api_token'] ) ? '' : $this->settings['api_token'];
				@$this->phone  = empty( $this->settings['phone'] ) ? '' : $this->settings['phone'];
				@$this->contact_person  = empty( $this->settings['contact_person'] ) ? '' : $this->settings['contact_person'];
				@$this->default_weight  = isset( $this->settings['default_weight'] ) ? $this->settings['default_weight'] : 0.1;
				@$this->raszet  = isset( $this->settings['raszet'] ) && $this->settings['raszet'] == 'yes' ? true : false;
				@$this->quantity_actual  = ( isset( $this->settings['quantity_actual'] ) && $this->settings['quantity_actual'] == 'yes' ) ? true : false;
				@$this->api_action  = ( isset( $this->settings['api_action'] ) && $this->settings['api_action'] == 'yes' ) ? true : false;
				@$this->region_iz  = isset( $this->settings['region_iz'] ) ? $this->settings['region_iz'] : '';
				@$this->fix_echo_button_postamat  = ($this->settings['fix_echo_button_postamat'] == 'no' || empty($this->settings['fix_echo_button_postamat'])) ? true : false;
				if(version_compare( WOOCOMMERCE_VERSION, '2.0', '<' )) $this->requires_coupon 	= $this->settings['requires_coupon'];
				else  $this->requires_coupon 	= isset($this->settings['requires']) ? $this->settings['requires'] : '';
						// Load the form fields.
				$this->init_form_fields();

				// Load the settings.
				
				// Actions
				
				if ( $this->is_availables( ) ) {
					add_action( 'woocommerce_review_order_after_shipping',  array( $this, 'review_order_pickup_location' ) );
					add_action( 'woocommerce_checkout_update_order_meta',   array( $this, 'checkout_update_order_meta' ), 10, 2 );
					add_action( 'woocommerce_thankyou',                     array( $this, 'order_pickup_location' ), 20 );
					add_action( 'woocommerce_view_order',                   array( $this, 'order_pickup_location' ), 20 );
				}
				add_action( 'woocommerce_after_checkout_validation',    array( $this, 'checkout_validation' ) );
				
				include_once( plugin_dir_path(__FILE__) . 'saphali-plugins.php');
				
			}
			
			function checkout_validation( $posted ) {
				global $woocommerce;
				// $this->add_order = false;
				if(is_array($posted['shipping_method']) && isset($posted['shipping_method'][0]) ) $posted['shipping_method'] = $posted['shipping_method'][0]; 
				if ( $posted['shipping_method'] == $this->id ) {
					
				}
			}
			private static function request( $param, $path, $t_rq = 'POST', $return = true ) {
				$headers = array( "Content-Type: application/x-www-form-urlencoded" );
	
				$ch      = curl_init();

				curl_setopt( $ch, CURLOPT_URL, $path );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, $return );
				
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
				curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
				if( !empty($param) ) {
					if($t_rq == 'POST')
						curl_setopt( $ch, CURLOPT_POST, 1 );
					
					curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($param, '', '&') );
					curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $t_rq );
				} elseif( in_array( $t_rq, array('PUT', 'DELETE') ) ) {
					curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $t_rq );
				} else {curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "GET" );}
				curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
				
				$_response = curl_exec( $ch );
				if ( $_response === false ) {
					$info = curl_getinfo( $ch );
					curl_close( $ch );

					return ( 'error occured during curl exec. Additioanl info: ' . var_export( $info, true ) );
				} else {
					curl_close( $ch );
				}

				return json_decode( $_response, true );

			}
			function calculate_shipping( $package = array() ) {
				global $woocommerce;
				if( !isset($package['destination']["country"]) ) $package['destination']["country"] = 'RU';
				$shipping_total = 0;
				if( !is_cart() && $package['destination']["country"] == 'RU' && !empty($_POST) && mb_strlen( $package ["destination"]["city"] ) > 2 ) {
					if(!in_array(get_woocommerce_currency() , array('RUB', 'RUR'))) {
						global $sitepress, $woocommerce_wpml;
						if(is_object($sitepress)) {
							if(is_object($woocommerce_wpml)) {
								$woocommerce_currency = $woocommerce_wpml->settings["default_currencies"][$sitepress->get_current_language()];
								$_kurs = $woocommerce_wpml->settings["currency_options"][$woocommerce_currency]["rate"];		
							}
							else $woocommerce_currency = get_woocommerce_currency(); 
						} else $woocommerce_currency = get_woocommerce_currency();
						$curensy_conv_shipping = get_transient( '_wc_session_curensy_conv_shipping' );
						$kurs =  $curensy_conv_shipping ? $curensy_conv_shipping : get_currency_rate_shipping('RUB', $woocommerce_currency);
						if(  ! $curensy_conv_shipping && $kurs > 0 ) {
							set_transient( '_wc_session_curensy_conv_shipping', $kurs , 60*60*6 );
						}
					} else $kurs = false;
					$kurs =  ($kurs !== false ) ? $kurs : 1;
					$shipping_total = 0;
					$fee = ( trim( $this->fee ) == '' ) ? 0 : $this->fee;
					$weight = 0;
					$popravka = 1;
					$name_cat = array();
					$_name_cat = '';
					
					$woocommerce_weight_unit = get_option('woocommerce_weight_unit');
					
					if($woocommerce_weight_unit == 'g')
					$popravka =  1000;
					elseif($woocommerce_weight_unit == 'lbs')
					$popravka = 1000/453.59237;
					elseif($woocommerce_weight_unit == 'oz')
					$popravka = 1000/28.3495231;
					foreach($woocommerce->cart->cart_contents as $pr) {
						if(method_exists( $pr["data"], 'get_weight' )) {
							$data_weight = $pr["data"]->get_weight();
						} else $data_weight = isset($pr["data"]->weight) ? $pr["data"]->weight : 0;
						if( $data_weight > 0) $weight += $data_weight * (float)$pr['quantity'] / $popravka;
						else
						$weight += (float)$this->default_weight * (float)$pr['quantity']; 
						$name_cat = array_merge( $name_cat, wp_get_post_terms( ( isset( $pr['product_id'] ) ? $pr['product_id'] : $pr['variation_id'] ), 'product_cat', array( "fields" => "names" )) );
					}
					
					$name_cat = array_unique($name_cat);
					$_name_cat = implode( ', ', $name_cat );
					
					$weight = round( $weight, 3);
					
					$shipping_total = $this->fee;
					$post_data['billing_first_name'] = $post_data['billing_phone'] = $post_data['billing_last_name'] = '';
					if( !isset($_POST['billing_first_name']) && isset($_POST['post_data']) ) {
						parse_str($_POST['post_data'], $rg);
						if(isset($rg['billing_first_name']))
							$post_data['billing_first_name'] = $rg['billing_first_name'];
					} else {
						$post_data['billing_first_name'] = $_POST['billing_first_name'];
					}
					if( !isset($_POST['billing_last_name']) && isset($_POST['post_data']) ) {
						parse_str($_POST['post_data'], $rg);
						if(isset($rg['billing_last_name']))
							$post_data['billing_last_name'] = $rg['billing_last_name'];
					} else {
						$post_data['billing_last_name'] = $_POST['billing_last_name'];
					}
					if( !isset($_POST['billing_phone']) && isset($_POST['post_data']) ) {
						parse_str($_POST['post_data'], $rg);
						if(isset($rg['billing_phone']))
							$post_data['billing_phone'] = $rg['billing_phone'];
					} else {
						$post_data['billing_phone'] = $_POST['billing_phone'];
					}
					$contact_person = $post_data["billing_first_name"] . ' ' . $post_data["billing_last_name"];
					$contact_person = trim( $contact_person );
					$this->order_id = 0;
			$_del_data = $this->comp_get_post('billing_recipient_time_date', $this->order_id);
			$_activ_r_n = $this->comp_get_post('billing_recipient_time_from', $this->order_id);
			$_activ_r_m = $this->comp_get_post('billing_recipient_time_to', $this->order_id);
			$tz = get_option('timezone_string');
			$tz = $tz ? $tz : 'Europe/Moscow';
			date_default_timezone_set($tz);
			$a = explode(':', $this->actual);
			if(sizeof($a) > 1) {
				$a1 = $a[0];
				$a2 = (int) $a[1];
			}
			
			$t = explode(':', $this->tomorrow_zab);
			$time = time();
			$H =(int) date( "H", $time );
			$m = ( date( "i", $time ));
			$t1 = $m+1;
			$bool_del_data = true;
			if(!empty($_del_data)) {
				$d = explode('-', $_del_data);
				$bool_del_data = isset($d[2]) &&  ( (int) date( "d", $time ) < $d[2] ) ? false : true;
			}
			if( ( $H < $a1 || ($H == $a1 && $t1 < $a2 ) ) && $bool_del_data ) {
				$H = $H + ($t1/60);
				if(!empty($_activ_r_n)) {
					$_a = explode(':', $_activ_r_n);
					$_am = explode(':', $_activ_r_m);
					if($_a[0] < ($t[0]+2) ) {
						if($t[0] >= ($H + 1) ) {
							$_a_ = $t[0]+2;
							if( ($_am[0] - $_a_) < 1)
								$r_m = $_a_ + 1;
							else 
								$r_m = $_am[0];
						} else {
							$_a_ = $H+3;
							if( ($_am[0] - $_a_) < 1)
								$r_m = $_a_ + 1;
							else 
								$r_m = $_am[0];
						}
					} else {
						$r_m = $_am[0];
						if($this->interval_to_zab !== '') {
							if( $t[0] >= ($H + 1) ) {
								if( ($t[0]+2) < $_a[0] ) {
									$_a_ = $_a[0];
									if( ($t[0]+3) < $_a[0] ) {
										$t1 = '00';
									}
								} elseif( ($t[0]+3) < $r_m ) {
									$_a_ = $r_m - 1;
								} else {
									$_a_ = $t[0]+2;
									$r_m = $_a_ + 1;
								}
							} else {
								if( ($H+3) < $_a[0] ) {
									$_a_ = $_a[0];
									if( ($H+4) < $_a[0] ) {
										$t1 = '00';
									}
								} elseif( ($H+4) < $r_m ) {
									$_a_ = $r_m - 1;
								} else {
									$_a_ = $H+3;
									$r_m = $_a_ + 1;
								}
							}
						} else {
							$_a_ = $r_m - 1;
						}
						$t1 = '00';
					}
				} else {
					if( $t[0] >= ($H + 1) ) {
						$_a_ = $t[0]+2;
						$r_m = $_a_ + 1;
					} else {
						$_a_ = $H + 3;
						$r_m = $_a_ + 1;
					}
				}
				if( ( $_a_ - $H )  > 2) {
					if($H < $t[0])
					$from = $t[0];
					else
					$from = $H + 1;
				} else {
					if( ($H+1) < $t[0]) {
						$from = $t[0];
						$_a_ = $t[0] + 2;
						$r_m = $_a_+ 1;
					} else {
						$from = $H + 1;
						$_a_ = $H + 3;
						$r_m = $_a_+ 1;
					}
				}
				if($this->interval_to_zab === '') {
					$to = $_a_ - 1;
				} else {
					if(($this->interval_to_zab + 1 + $H) >=  ($from + 1) && ($this->interval_to_zab + 1+ $H) <=  ($_a_ - 1) ) {
						$to = $this->interval_to_zab + 1 + $H;
					} 
					elseif ( ( $_a_ - $H )  > 2 && $H < $t[0]) {
						if(($this->interval_to_zab + $t[0]) >=  ($from + 1) && ($this->interval_to_zab + $t[0]) <=  ($_a_ - 1) ) {
							$to = $this->interval_to_zab + $t[0];
						}
					} elseif( !(( $_a_ - $H )  > 2 ) && ($H+1) < $t[0] ) {
						if(($this->interval_to_zab + $t[0]) >=  ($from + 1) && ($this->interval_to_zab + $t[0]) <=  ($_a_ - 1) ) {
							$to = $this->interval_to_zab  + $t[0];
						}
					} elseif((floor(($_a_ - 1)) - floor($from)) >= 1)
						$to = $_a_ - 1;
					else $to = $from + 1;
				}
				$from = floor($from);
				$t2 = $t1;
				if($from - 1 <= (int) date( "H", $time ) ) {
					$t1 = $m+1;
				}
				
				$to =   floor($to);
				$_a_ =  floor($_a_);
				$r_m =  floor($r_m);
				
				if( ($to - $from) < 2 && ($_a_ - $to) < 2 && $from - 1 <= (int) date( "H", $time ) ) {
					$t2 = $t1;
				} else {
					$t2 = '00';
				}
				
				if(($to - $from) <= 1) {
					$tss = $t1;
				} else $tss = $t2;
				
				if($t1 < 10 && $t1 !== '00') $t1 = '0'.$t1;
				if($tss < 10 && $tss !== '00') $tss = '0'.$tss;
				if($t2 < 10 && $t2 !== '00') $t2 = '0'.$t2;
				
				if($from < 10 && $from !== '00') $from = '0'.$from;
				if($to < 10 && $to !== '00') $to = '0'.$to;
				if($_a_ < 10 && $_a_ !== '00') $_a_ = '0'.$_a_;
				if($r_m < 10 && $r_m !== '00') $r_m = '0'.$r_m;
				
				$sender_time_from = date( "Y-m-d {$from}:{$t1}:00", $time  );
				$sender_time_to = date( "Y-m-d {$to}:{$tss}:00", $time );
				$recipient_time_from = date( "Y-m-d {$_a_}:{$t2}:00", $time );
				$recipient_time_to = date( "Y-m-d {$r_m}:{$t2}:00", $time );
			} else {
				$H = $H + ($t1/60);
				if(!empty($_activ_r_n)) {
					$_a = explode(':', $_activ_r_n);
					$_am = explode(':', $_activ_r_m);
					if($_a[0] < ($t[0]+2) ) {
						$_a_ = $t[0]+2;
						if( ($_am[0] - $_a_) < 1)
							$r_m = $_a_ + 1;
						else 
							$r_m = $_am[0];
						
					} else {
						$r_m = $_am[0];
						if($this->interval_to_zab !== '') {
							if( ($t[0]+2) < $_a[0] ) {
								$_a_ = $_a[0];
							} elseif( ($t[0]+3) < $r_m ) {
								$_a_ = $r_m - 1;
							} else {
								$_a_ = $t[0]+2;
								$r_m = $_a_ + 1;
							}
						} else {
							$_a_ = $r_m - 1;
						}
						$t1 = '00';
					}
				} else {
					$_a_ = $t[0]+2;
					$r_m = $_a_ + 1;
				}
				
				$from = $t[0];
				
				if($this->interval_to_zab === '') {
					$to = $_a_ - 1;
				} else {
					if(($this->interval_to_zab + $from) >=  ($from + 1) && ($this->interval_to_zab + $from) <=  ($_a_ - 1) ) {
						$to = $this->interval_to_zab + $from ;
					} elseif( (floor(($_a_ - 1)) - floor($from)) >= 1)
					$to = $_a_ - 1;
					else $to = $from+1;
				}
				if($from == $t[0]) {
					$ts = $t[1];
					if($this->interval_to_zab === '') {
						$tss = $t1;
					} else {
						$tss = $ts;
					}
				} else $ts = $t1;
				$to =   floor($to);
				$_a_ =  floor($_a_);
				$r_m =  floor($r_m);
				
				$t2 = $t1;
				if($t1 === '00' && ($to - $from) < 2  && ($ts+0) > 0) {
					if( ($t[1] + 0) > 0 )
						$t1 = $t[1];
					else 
						$t1 = '00';
				}
				if($t2 === '00' && ($to - $from) < 2 && ($_a_ - $to) < 2  && ($ts+0) > 0) {
					if( ($t[1] + 0) > 0 )
						$t2 = $t1;
					else 
						$t2 = '00';
				}
				if($ts < 10 && $ts !== '00') $ts = '0'.$ts;
				if($tss < 10 && $tss !== '00') $tss = '0'.$tss;
				if($t1 < 10 && $t1 !== '00') $t1 = '0'.$t1;
				if($t2 < 10 && $t2 !== '00') $t2 = '0'.$t2;
				
				if($from < 10 && $from !== '00') $from = '0'.$from;
				if($to < 10 && $to !== '00') $to = '0'.$to;
				if($_a_ < 10 && $_a_ !== '00') $_a_ = '0'.$_a_;
				if($r_m < 10 && $r_m !== '00') $r_m = '0'.$r_m;
				
				$sender_time_from = date( "Y-m-d {$from}:{$ts}:00", $time + 24 * 3600  );
				$sender_time_to = date( "Y-m-d {$to}:{$tss}:00", $time + 24 * 3600 );
				if($bool_del_data) {
					$recipient_time_from = date( "Y-m-d {$_a_}:{$t2}:00", $time + 24 * 3600 );
					$recipient_time_to = date( "Y-m-d {$r_m}:{$t2}:00", $time + 24 * 3600 );
				} else {
					if( ( $d[2] - (int) date( "d", $time + 24 * 3600 ) ) > 0  ) {
						$to += 1;
						$sender_time_to = date( "Y-m-d {$to}:{$tss}:00", $time + 24 * 3600 );
					}
					$recipient_time_from = date( "Y-m-d {$_a_}:{$t2}:00", $time + 24 * 3600 );
					$recipient_time_to = date( "Y-m-d {$r_m}:{$t2}:00", $time + 24 * 3600 );
					
					$recipient_time_from = str_replace( date( "Y-m-d", $time + 24 * 3600 ), $_del_data, $recipient_time_from);
					$recipient_time_to = str_replace( date( "Y-m-d", $time + 24 * 3600 ), $_del_data, $recipient_time_to);
				}
			}

					$param = array(
						'client_id' => $this->api_id,
						'token' => $this->api_token,
						'matter' => $_name_cat,
						'insurance' => (int)( ($package['contents_cost'] / $kurs) > 50000 ? 50000 : ($package['contents_cost'] / $kurs) ),
						'point' => array( 
							'0' => 
							array(
								'required_time_start' => $sender_time_from,
								'required_time' => $sender_time_to,
								'contact_person' => $this->contact_person, // нет
								'phone' => $this->phone,
								'address' => $this->region_iz,
								'note' => $this->description, // нет
								), 
							'1' => 
							array(
								'required_time_start' => $recipient_time_from,
								'required_time' => $recipient_time_to,
								'contact_person' => $contact_person,
								'phone' => $post_data["billing_phone"],
								'address' => rtrim( $package['destination']['city'] . ', ' . $package['destination']['address'] . ', ' . $package['destination']['address_2'], ', '),
								'weight' => $weight // нет
								)
							)
					);
					$m = '';
					$req = array('address', 'phone', 'required_time', 'required_time_start');
					foreach($param['point']['0'] as $k => $v) {
						if(in_array($k, $req) && empty($v)) {
							$m .= '(0)Ошибка (Dostavista). Параметр <strong>' . $k . '</strong> обязательный. <br /> ';
						}
					}
					foreach($param['point']['1'] as $k => $v) {
						if(in_array($k, $req) && empty($v)) {
							$m .= '(1)Ошибка (Dostavista). Параметр <strong>' . $k . '</strong> обязательный. <br /> ';
						}
					}
					if(!empty($m)) {
						if(isset($_POST['api_action'])) {
							if(!isset($_POST['block_dv']))
							$this->comp_woocomerce_mess_error('Ошибка (Dostavista). ' . $m . '<input type="hidden" name="block_dv" value="true" />');
						} else {
							if(!isset($_POST['block_dv']))
							$this->comp_woocomerce_mess_error($m . '<input type="hidden" name="block_dv" value="true" />');
						}
						$rate = array(
							'id' 		=> $this->id,
							'label' 	=> $this->title,
							'cost' 		=> $shipping_total
						);

						$this->add_rate($rate);
						return;
					}
					// $param['point']['1']['client_order_id']
					 
					$url = $this->is_test ? 'https://robotapitest.dostavista.ru/bapi/calculate' : 'https://dostavista.ru/bapi/calculate';
					
					$response = self::request($param,$url);
					
					if( isset($response['error_code'])) {
						$_param = $param;
						unset($_param['token']);
						$validation_errors = '';
						if(isset($response['validation_errors'])) {
							foreach($response['validation_errors']['point'] as $val ) 
								foreach($val as $_val ) {
									$validation_errors[] = implode( ', ', $_val );
								}
							$validation_errors = array_unique($validation_errors);
						}
						$this->comp_woocomerce_mess_error('Код ошибки: ' . implode( ', ', $response['error_code'] ) . '. ' . implode( ', ', $validation_errors) . '. ' . implode( ', ', $response['error_message']) ); //. var_export (($response), true) comp_woocomerce_mess
					} else {
						$param['point']['1']['taking'] = round( $shipping_total / $kurs ) + $response["payment"];
						
						$response = self::request(
							$param,
							$url
						);
						
						if(isset($response['error_code'])) {
							$_param = $param;
							unset($_param['token']);
							$this->comp_woocomerce_mess_error('Код ошибки: ' . implode( ', ', $response['error_code'] ) . '. ' . implode( ', ', $response['error_message']) . print_r ($_param, true) ); //comp_woocomerce_mess
						} else {
							$shipping_total =  $shipping_total + round( $kurs * $response["payment"], 2 );
						}
					}
				}

				$rate = array(
					'id' 		=> $this->id,
					'label' 	=> $this->title,
					'cost' 		=> $shipping_total
				);
				
				$this->add_rate($rate);
			}
			
			public function woocommerce_order_add_shipping( $id, $item_id, $args ) {
				if(is_object($args)) {
							if(method_exists($args, 'get_method_id')) {
						$method_id = $args->get_method_id();
					} else {
						$method_id = $args->id;
					}
					if ( $method_id == $this->id ) {
					if( isset($_POST['dostavista_cost']) && $_POST['dostavista_cost'] > 0 ) {
						$dostavista_cost = round( $_POST['dostavista_cost'], 2);
						if ( !version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
							update_metadata( 'order_item', $item_id, 'cost', wc_format_decimal( $this->fee + $dostavista_cost )  ) ;
						} else {
							if(function_exists('update_metadata'))
							update_metadata( 'order_item', $item_id, 'cost', woocommerce_format_total( $this->fee + $dostavista_cost )  ) ;
						}
					}
				}
			}
			}
			function comp_woocomerce_mess_error ($m) {
				if( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
					global $woocommerce;
					$woocommerce->add_error( $m );
				} else {
					wc_add_notice( $m, 'error' );
				}
			}
			function comp_woocomerce_mess ($m) {
				if( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
					global $woocommerce;
					$woocommerce->add_message( $m );
				} else {
					wc_add_notice( $m );
				}
			}
			public function checkout_update_order_meta( $order_id, $posted ) {
				if(is_array($posted['shipping_method']) && isset($posted['shipping_method'][0]) ) $posted['shipping_method'] = $posted['shipping_method'][0]; 
				if ( $posted['shipping_method'] == $this->id ) {

					//var_dump($this->add_order , $this->api_action); exit;
					
				}
			}
			function toProcess($key, $order_id) {
				if($this->api_action) {
					global $wpdb;
					$s = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} where {$wpdb->postmeta}.post_id = $order_id AND {$wpdb->postmeta}.meta_key = '_order_number'");
					$weight = $quantity = 0;
					//$s = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} where {$wpdb->postmeta}.meta_value = $order_number AND {$wpdb->postmeta}.meta_key = '_order_number'");
					foreach($woocommerce->cart->cart_contents as $pr) {
						if(isset( $pr["data"]->weight ) && $pr["data"]->weight > 0) $weight += $pr["data"]->weight * $pr['quantity'];
						else
						$weight += $this->default_weight * $pr['quantity']; 
						$quantity += $pr['quantity'];
					}
					if(!$this->quantity_actual)
					$quantity = 1;
					$h_f = date_i18n( "H", time() );
					$h_t = $h_f+4;
					
					if($h_t > 14 && $h_t < 18) {
						$h_f = "14:00";
						$h_t = "18:00";
						$d = date_i18n( "Y.m.d", time() );
					} elseif( $h_t > 18) {
						$h_f = "10:00";
						$h_t = "14:00";
						$d = date_i18n( "Y.m.d", time() + (24 * 3600) );
					} else {
						$d = date_i18n( "Y.m.d", time() );
						$h_f = "10:00";
						$h_t = "14:00";
					}
					
					$h_f = "10:00";
					$h_t = "18:00";
					$cont_name = $this->comp_get_post('billing_last_name', $order_id) . ' ' . $this->comp_get_post('billing_first_name', $order_id);
					$post = array 
					(
					  "partner_id" => $this->api_id,
					  "key" => $this->api_token,
					  "usluga" => "ДОСТАВКА",
					  "order_number" => empty($s) ? $order_id : $s,
					  "sposob_dostavki" => "ПВЗ",
					  "ves_kg" => $weight,
					  "date_dost" => ( empty($_POST['dostavista_date']) ? $d : str_replace('-', '.', $_POST['dostavista_date']) ),
					  "delivery_time_from" => $h_f,
					  "delivery_time_to" => $h_t,
					  "region_iz" => $this->region_iz,
					  "punkt_vivoz" => esc_attr($_POST["dostavista_point_code"]),
					  "cont_name" => trim($cont_name),
					  "cont_tel" => $this->comp_get_post('billing_phone', $order_id),
					  "mesta" => $quantity,
					  "comment" => 'Заказ №' . ( empty($s) ? $order_id : $s ),
					  "ves_kg" => $_POST['dostavista_weight'],
					  "nal_plat" => $woocommerce->cart->total,
					  "ocen_sum" => $woocommerce->cart->total
					);
					$request = wp_remote_post( 'https://api.dostavka.guru/client/in_up_2_0.php', array(
						'method' => 'POST',
						'timeout' => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking' => true,
						'headers' => array(),
						'body' => $post,
						'cookies' => array(),
						'sslverify' => false
					));
					/* $response = self::request(
						$post,
						'https://api.dostavka.guru/client/in_up_2_0.php'
					); */
					if( $request["response"]["code"] == 200 &&
					$request["response"]["message"] == "OK" ) {
						if( strpos( $request["body"], 'Error') !== false ) {
							$this->comp_woocomerce_mess_error( $request["body"] . print_r($post, true) );
						} else {
							if(class_exists('SimpleXMLElement'))
							$data = (array) new SimpleXMLElement( str_replace( ' >', '>', $request['body'] ) );
							
							$order = (array)$data["order"];
							$order['barcodes'] = (array)$order['barcodes'];
							$_order = wc_get_order( $order_id );
							$_order->add_order_note( sprintf(__("Статус доставки на данный момент: %s. \n Штрихкоды: %s."), $order['location_status'], implode(', ', (is_array($order['barcodes']['code']) ? $order['barcodes']['code'] : $order['barcodes'] ) ) ) , 1 );
							$this->comp_woocomerce_mess( sprintf(__("Статус доставки на данный момент: %s. \n Штрихкоды: %s."), $order['location_status'], implode(', ', (is_array($order['barcodes']['code']) ? $order['barcodes']['code'] : $order['barcodes'] ) ) ) );
						}
					} else {
						$this->comp_woocomerce_mess_error( __( '<span class="req">Ошибка запроса при отправке данных заказа.', 'saphali-post-russia2' ) );
					}
				}
			}
			function comp_get_post($key, $order_id) {
					$value_fild = get_post_meta( $order_id, '_' . $key, true );
				if(!$value_fild) {
					$user_id = get_post_meta( $order_id, '_customer_user' , true );
					$value_fild = get_user_meta($user_id, $key, true);
				}
				return $value_fild;
			}
			function order_pickup_location($order_id) {
				$order = new WC_Order( $order_id );
				if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
					$is_true = ( $order->get_shipping_method() == $this->title ) ;
				} else {
					$is_true = ($order->shipping_method == $this->id ); 
				}
				if ( $is_true ) {
					if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
						$pickup_location = maybe_unserialize( $order->order_custom_fields['_dostavista_info'][0] );
						$pickup_location_array = maybe_unserialize( $order->order_custom_fields['_dostavista_order_id'][0] );
					} else {
						$pickup_location =  get_post_meta($order->id, '_dostavista_info', true);
						$pickup_location_array = get_post_meta($order->id, '_dostavista_order_id', true);			
					}
					
					if(!empty($pickup_location_array)) {
						echo '<div>';
						echo '<h3>'.__( 'Доставка через сервис Dostavista:','saphali_delivery_dostavista' ).'</h3>';
						
						echo '<div><strong> Номер пункта выдачи: </strong>'.$pickup_location_array .'</div>';
						echo '<div> <strong>Адрес доставки:</strong> '.$pickup_location .'</div>';
						echo '</div>';
					}
				}
			}
			
			public function valid_method( $is_available, $package ) {
				if( !isset($package['destination']["country"]) ) $package['destination']["country"] = 'RU';
				if($package['destination']["country"] != 'RU') return false;
				if( $is_available && !empty($package ["destination"]["city"]) ) {
					if( mb_strlen( $package ["destination"]["city"] ) <= 2 ||  mb_stripos( $this->region_iz , preg_replace("/^\d{1,}/", '', $package ["destination"]["city"]) ) === false ) return false;
				}
				return $is_available;
			}
			public function is_availables( $package = array() ) {
				if ( $this->enabled == "no"  ) return false;
			if(version_compare( WOOCOMMERCE_VERSION, '2.0', '<' )) {
				global $woocommerce;
				$is_available 		= false;
				$has_coupon 		= false;
				$has_met_min_amount = false;
				if ( $this->requires_coupon == 'yes' && $this->min_amount )
					$default_requires = 'either';
				elseif ( $this->requires_coupon=='yes' )
					$default_requires = 'coupon';
				elseif ( $this->min_amount )
					$default_requires = 'min_amount';
				else
					$default_requires = '';
				if ( in_array( $default_requires, array( 'coupon', 'either' ) ) ) {

					if ( $woocommerce->cart->applied_coupons ) {
						foreach ($woocommerce->cart->applied_coupons as $code) {
							$coupon = new WC_Coupon( $code );

							if ( $coupon->is_valid() && $coupon->enable_free_shipping() )
								$has_coupon = true;
						}
					}
				}

				if ( in_array( $default_requires, array( 'min_amount', 'either' ) ) ) {

					if ( isset( $woocommerce->cart->cart_contents_total ) ) {

						if ( $woocommerce->cart->prices_include_tax )
							$total = $woocommerce->cart->tax_total + $woocommerce->cart->cart_contents_total;
						else
							$total = $woocommerce->cart->cart_contents_total;

						if ( $total >= $this->min_amount )
							$has_met_min_amount = true;
					}
				}
				switch ( $default_requires ) {
					case 'min_amount' :
						if ( $has_met_min_amount ) $is_available = true;
					break;
					case 'coupon' :
						if ( $has_coupon ) $is_available = true;
					break;
					case 'either' :
						if ( $has_met_min_amount && $has_coupon ) $is_available = true;
					break;
					case 'both' :
						if ( $has_met_min_amount || $has_coupon ) $is_available = true;
					break;
					default :
						$is_available = true;
					break;
				}
				
			} else {
				global $woocommerce;
				$is_available 		= false;
				$has_coupon 		= false;
				$has_met_min_amount = false;

				if ( in_array( $this->requires_coupon, array( 'coupon', 'either', 'both' ) ) ) {

					if ( $woocommerce->cart->applied_coupons ) {
						foreach ($woocommerce->cart->applied_coupons as $code) {
							$coupon = new WC_Coupon( $code );

							if ( $coupon->is_valid() && $coupon->enable_free_shipping() )
								$has_coupon = true;
						}
					}
				}

				if ( in_array( $this->requires_coupon, array( 'min_amount', 'either', 'both' ) ) ) {

					if ( isset( $woocommerce->cart->cart_contents_total ) ) {

						if ( $woocommerce->cart->prices_include_tax )
							$total = $woocommerce->cart->tax_total + $woocommerce->cart->cart_contents_total;
						else
							$total = $woocommerce->cart->cart_contents_total;

						if ( $total >= $this->min_amount )
							$has_met_min_amount = true;
							
					}
				}

				switch ( $this->requires_coupon ) {
					case 'min_amount' :
						if ( $has_met_min_amount ) $is_available = true;
					break;
					case 'coupon' :
						if ( $has_coupon ) $is_available = true;
					break;
					case 'both' :
						if ( $has_met_min_amount && $has_coupon ) $is_available = true;
					break;
					case 'either' :
						if ( $has_met_min_amount || $has_coupon ) $is_available = true;
					break;
					default :
						$is_available = true;
					break;
				}
				
			}
				if(!$is_available) $this->enabled = "no";
				return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', $is_available, $package );
			}
			
			public function review_order_pickup_location() {
				global $woocommerce;
				if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) $chosen_shipping_method = $_SESSION['_chosen_shipping_method']; 
				elseif( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) $chosen_shipping_method = $woocommerce->session->chosen_shipping_method;
				else $chosen_shipping_method = $woocommerce->session->chosen_shipping_methods[0];
				
				if(  isset($_POST['shipping_method'] ) && is_array( $_POST['shipping_method'] ) && isset($_POST['shipping_method'][0]) ) $posted['shipping_method'] = $_POST['shipping_method'][0];
				if(isset($posted['shipping_method']) && is_null($chosen_shipping_method) ) $chosen_shipping_method = $posted['shipping_method'];
				
				if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) $this->_one = $_SESSION['_chosen_shipping_method_one']; else $this->_one = true;
				if ( $chosen_shipping_method == $this->id  && $this->_one ) {
						
				}
			}

			public function footer(  ) {
				//if($this->fix_echo_button_postamat) return;
				
			}
			public function add_dostavista( $methods ) {
				// since the gateway is always constructed, we'll pass it in to the register filter so it doesn't have to be re-instantiated
				$methods[] = $this;
				return $methods;
			}
			public function order_info_post( ) {
				// since the gateway is always constructed, we'll pass it in to the register filter so it doesn't have to be re-instantiated
				$url = $this->is_test ? 'https://robotapitest.dostavista.ru/bapi/order/%s' : 'https://dostavista.ru/bapi/order/%s';
				
				$response = self::request(
					$_POST,
					sprintf( $url, $_POST['order_id'] )
				);
				header("Content-Type: application/json");
				echo $response["body"];
				exit;
			}
		}

		new wc_custom_shipping_dostavista();
	}

if(!function_exists('get_currency_rate_shipping')) {
function get_currency_rate_shipping($_from, $_to) {
		$url = 'http://www.cbr.ru/scripts/XML_daily.asp';
		$response = wp_remote_get( $url, array(
					'timeout' => 45,
					'httpversion' => '1.1',
					'blocking' => true,
					'headers' => array(),
					'body' => null,
					'cookies' => array(),
					'sslverify' => false
		));
		if( !is_object($response) && $response["response"]["code"] == 200 && $response["response"]["message"] == "OK") {
			if(class_exists('SimpleXMLElement'))
				$data = (array) new SimpleXMLElement( str_replace( ' >', '>', $response['body'] ) );
				foreach($data["Valute"] as $valute){
					$valute = (array)$valute;
					$valute['Value'] = str_replace(',', '.', $valute['Value']);
					if($valute['Value'] > 0 && $valute["CharCode"] == $_from ) {
						$from = $valute['Value']/$valute['Nominal'];
					} elseif($valute['Value'] > 0 && $valute["CharCode"] == $_to ) {
						$to = $valute['Value']/$valute['Nominal'];
					}
				}
				if(in_array($_from, array('RUB', 'RUR'))) {
					$from = 1;
				}
				if(in_array($_to, array('RUB', 'RUR'))) {
					$to = 1;
				}
				$rate = round($from/$to, 6);
		}
		
		if ($rate <= 0) return false;
		return $rate;
	}
}
register_activation_hook( __FILE__, array('saphali_wc_custom_shipping_pp', 'install') );


?>