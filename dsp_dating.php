<?php
/*
  Plugin Name: WP Dating 
  Plugin URI: http://www.wpdating.com/
  Description: WordPress Dating Plugin by Digital Product Labs.
  Version: 6.4.3
  Author: WP Dating
  Author URI: http://www.wpdating.com/
 */
//error_reporting(E_ALL);

if ( ! function_exists( 'get_plugins' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$isValidLicense      = get_option( '_license_validated' );
$pluginInfo          = get_plugin_data( __FILE__ );
$licenseModuleSwitch = true;
//$pluginData = dsp_get_plugin_info();
//var_dump($pluginData);die;
$host  = substr( $_SERVER['HTTP_HOST'], 0, 5 );
$local = in_array( $host, array( 'local', '192.1', '127.0' ) ) ? true : false;
if ( ! $local ) {
	error_reporting( 0 );
}

ini_set( 'max_execution_time', 300 ); //300 seconds = 5 minutes
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
// Hook for adding admin menus
//add_action('admin_menu', 'dsp_add_pages');
! defined( 'WP_DSP_ABSPATH' ) ? define( 'WP_DSP_ABSPATH', plugin_dir_path( __FILE__ ) ) : null;
! defined( 'WP_DSP_FILE' ) ? define( 'WP_DSP_FILE', plugin_dir_path( __FILE__ ) ) : null;
! defined( 'PO_PATH' ) ? define( 'PO_PATH', ABSPATH . '/wp-content/uploads/po/' ) : null;
! defined( 'LICENSE_SECRET_KEY' ) ? define( 'LICENSE_SECRET_KEY', '55801d9cde4620.07155322' ) : null;
! defined( 'LICENSE_SERVER_URL' ) ? define( 'LICENSE_SERVER_URL', 'http://www.wpdating.com' ) : null;
! defined( 'WPDATE_URL' ) ? define( 'WPDATE_URL', plugin_dir_url( __FILE__ ) ) : null;
! defined( 'PLUGIN_VERSION' ) ? define( 'PLUGIN_VERSION', '5.0' ) : null;

//global variable for current template path
$currentTemplatePath = '';
$prevLangId          = 1;
//url to members http://.../members
//------------check if mobile folder exist otherwise redirect to desktop version------------------
$dspMbDir = WP_DSP_ABSPATH . "mobile";

global $wpdb;
$DSP_GENERAL_SETTINGS_TABLE = $wpdb->prefix . 'dsp_general_settings';

// check condition if table exists
if ( $wpdb->get_var( "show tables like '$DSP_GENERAL_SETTINGS_TABLE'" ) != $DSP_GENERAL_SETTINGS_TABLE ) { // table does not exist then //default		value is no
	$mobileStatus = "N";
} else { // if table exist then we will fetch the valuew from table
	$mobileStatus = $wpdb->get_var( "SELECT setting_status FROM $DSP_GENERAL_SETTINGS_TABLE where setting_name = 'mobile'" );
	//echo $mobileStatus.' status';
}

if ( file_exists( $dspMbDir ) && is_dir( $dspMbDir ) && $mobileStatus == 'Y' ) { // mobile folder exist also check what is mobile status
	include_once( 'mobile/dsp_check_mobile.php' );
	$wptouch_plugin_obj = new WPtouchPlugin();
}

/**
 *  This function is used to get plugin data
 *
 * @param   [user_id] [currently loggen user id]
 * @param   [type] [Feed type]
 */
include_once( WP_DSP_ABSPATH . 'files/includes/table_names.php' );
include_once( WP_DSP_ABSPATH . 'functions.php' );
include_once( WP_DSP_ABSPATH . 'dating_search_box.php' );
include_once( WP_DSP_ABSPATH . 'class/class-license-checker.php' );
include_once( WP_DSP_ABSPATH . 'files/includes/general.php' );
include_once( WP_DSP_ABSPATH . 'external-lib/po-parser/PoParserUsed.php' );
include_once( WP_DSP_ABSPATH . 'email-template/email-template.php' );
include_once( WP_DSP_ABSPATH . 'tgm/tgm.php' );
include_once( WP_DSP_ABSPATH . 'wpdating-facebook/wpdating-facebook.php' );
include_once( WP_DSP_ABSPATH . 'wpdating-gallery/wpdating-gallery.php' );
include_once( WP_DSP_ABSPATH . 'wpdating-paypal/wpdating-paypal.php' );
include_once( WP_DSP_ABSPATH . 'wpdating-upload-crop/wpdating-upload-crop.php' );
include_once( WP_DSP_ABSPATH . 'wpdating-google-inapp/wpdating_google_inapp.php' );
include_once( WP_DSP_ABSPATH . 'wpdating-option/wpdating_option.php' );

register_activation_hook( __FILE__, 'dsp_install_third_party_plugins' );
register_activation_hook( __FILE__, 'dsp_check_compatibility' );
register_activation_hook( __FILE__, 'WCM_Setup_Demo_on_activation' );
register_deactivation_hook( __FILE__, 'WCM_Setup_Demo_on_deactivation' );
register_uninstall_hook( __FILE__, 'WCM_Setup_Demo_on_uninstall' );
register_activation_hook( __FILE__, 'plugin_activate' );


//add_action('plugins_loaded','create_dsp_tables');
register_activation_hook( __FILE__, 'create_dsp_tables' );
add_action( 'wp_loaded', 'dsp_flush_rules' );
add_action( 'wp_head', 'custom_css' );
add_filter( 'rewrite_rules_array', 'dsp_insert_rewrite_rules' ); // set rewrite rule for plugin
add_action( 'init', 'wpdating_load_plugin_textdomain' );

function wpdating_load_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'wpdating' );
	load_textdomain( 'wpdating', PO_PATH . '/wpdating-' . $locale . '.mo' );
}

/**
 *  Add new news feed into database
 *
 * @param   [user_id] [currently loggen user id]
 * @param   [type] [Feed type]
 */

if ( ! function_exists( 'dsp_get_member_page_name' ) ) {
	function dsp_get_member_page_name() {
		$pageIds = get_all_page_ids();
		foreach ( $pageIds as $pId ) {
			$page    = get_page( $pId );
			$content = $page->post_content;
			if ( stristr( $content, 'filepath="profile_header.php"' ) ) {
				update_option( 'members_page_id', $page->ID );
				update_option( 'members_page_name', $page->post_name );

				return strtolower( $page->post_name );
			}
		}


	}
}

! defined( 'ROOT_LINK' ) ? define( 'ROOT_LINK', get_bloginfo( 'url' ) . "/" . dsp_get_member_page_name() . "/" ) : null;


if ( ! function_exists( 'dsp_get_plugin_info' ) ) {
	function dsp_get_plugin_info() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$pluginData = get_plugin_data( __FILE__ );

		return $pluginData;
	}
}


if ( ! function_exists( 'create_dsp_tables' ) ) {
	function create_dsp_tables() {
		if ( function_exists( 'createtables' ) ) {
			createtables();
		}
		if ( function_exists( 'createdirectories' ) ) {
			createdirectories(); //Create Media Directories and Set Permissions
		}
	}
}

function plugin_activate() {
	include_once( 'dsp_dating_activate.php' );

}

function dsp_user_profile() {
	include_once( WP_DSP_ABSPATH . 'profile_header.php' );
}

function dsp_check_compatibility() {
	if ( version_compare( phpversion(), '5.4', '<' ) ) {
		wp_die( 'Php version should be atleast 5.4 to activate the WPDating plugin.' );
	}
}

/**
 * This function lists and installs third party plugins required for Dsp dating plugin
 *
 * @return  null
 */
if ( ! function_exists( 'dsp_install_third_party_plugins' ) ) {
	function dsp_install_third_party_plugins() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$current_url      = 'http' . ( isset( $_SERVER['HTTPS'] ) ? 's' : '' ) . '://' . "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
		$plugin_directory = str_replace( 'dsp_dating', '', plugin_dir_path( __FILE__ ) );
		$plugin_zip_path  = plugin_dir_path( __FILE__ ) . '/extra/third_party_plugins/';
		$plugins_array    = array( 'slider-wd.zip' => 'slider-wd/slider-wd.php' );

		foreach ( $plugins_array as $plugin => $plugin_file ) {
			$installed = file_exists( $plugin_directory . $plugin_file );
			$activated = is_plugin_active( $plugin_file );
			//var_dump($activated);
			//var_dump($installed);die;

			if ( ! $installed ) {
				$zip = new ZipArchive;
				$res = $zip->open( $plugin_zip_path . $plugin );
				//var_dump($res);die;

				if ( $res === true ) {
					$zip->extractTo( $plugin_directory );
					$zip->close();
					echo 'Plugin extracted!';

				} else {
					$zip->close();
					echo 'doh!';
				}
			}
			if ( ! $activated ) {
				$plugin_folder     = '';
				$plugin_data       = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_file );
				$installed_plugins = get_plugins();
				$plugins_cache     = wp_cache_get( 'plugins', 'plugins' );

//                file_put_contents('testtesttest.txt',print_r($plugin_data,true),FILE_APPEND);
//                file_put_contents('testtesttest.txt',print_r($installed_plugins,true),FILE_APPEND);
//                file_put_contents('testtesttest.txt',print_r($plugins_cache,true),FILE_APPEND);

				$installed_plugins[ $plugin_file ] = $plugin_data;
				uasort( $installed_plugins, '_sort_uname_callback' );
				$cache_plugins[ $plugin_folder ] = $installed_plugins;
				wp_cache_set( 'plugins', $cache_plugins, 'plugins' );

				$result = activate_plugin( $plugin_file );
				if ( $result == null ) {
					echo '3rd party plugin activated!';
				} else {
					echo 'Plugin could not activated!';
				}
			}
		}
	}
}

function WCM_Setup_Demo_on_activation() {
	if ( ! get_page_by_title( 'Members' ) ) {
		// Create post object
		$new_post = array(
			'post_title'    => 'Members',
			'post_content'  => '[include filepath="profile_header.php"]',
			'post_status'   => 'publish',
			'post_date'     => date( 'Y-m-d H:i:s' ),
			'post_author'   => 'admin',
			'post_type'     => 'page',
			'post_category' => array( 0 )
		);
		$pageName = 'members';
		$post_id  = wp_insert_post( $new_post );
		delete_option( $pageName . '_page_title' );
		add_option( $pageName . '_page_title', 'Members', '', 'yes' );
		delete_option( $pageName . '_page_name' );
		add_option( $pageName . '_page_name', 'Members', '', 'yes' );
		delete_option( $pageName . '_page_id' );
		add_option( $pageName . '_page_id', $post_id, '', 'yes' );
	}
	if ( file_exists( WP_DSP_ABSPATH . "/gifts" ) ) {
		if ( file_exists( ABSPATH . "/wp-content/uploads/dsp_media/gifts/" ) ) {
			rcopy( WP_DSP_ABSPATH . "/gifts/", ABSPATH . "/wp-content/uploads/dsp_media/gifts/" );
		} else {
			createPath( ABSPATH . "/wp-content/uploads/dsp_media/gifts/" );
			rcopy( WP_DSP_ABSPATH . "/gifts/", ABSPATH . "/wp-content/uploads/dsp_media/gifts/" );
		}
	}
}

function WCM_Setup_Demo_on_deactivation() {
//if(get_page_by_title('Members')){
//$page = get_page_by_title( 'Members' );
//wp_delete_post($page->ID,true);
//}
}

function WCM_Setup_Demo_on_uninstall() {

}

function dspGetPageName() {
	$memberPage = get_option( 'members_page_name' ); // get member page name
	if ( $memberPage ) {
		return $memberPage;
	} else {
		return "members";
	}
}

function dsp_insert_rewrite_rules( $rules ) {
	global $wp_rewrite;
	$slug                                = dspGetPageName();
	$newrules                            = array();
	$newrules[ '(' . $slug . ')/(.+)$' ] = 'index.php?pagename=$matches[1]/&pid=$matches[2]';

	return $newrules + $rules;
}

function dsp_flush_rules() {
	$rules = get_option( 'rewrite_rules' );
	$slug  = dspGetPageName();
	//print_r($rules);die;
	if ( ! isset( $rules[ '(' . $slug . ')/(.+)$' ] ) ) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
}

//Version 2 of get values from url for zipcode search without login
function get2( $string ) {
	$count = 2;
	$root  = get_bloginfo( 'url' );
	$Url   = explode( '/', $root );
	unset( $Url[0], $Url[1] );
	$length = sizeof( $Url );
	$url    = explode( '/', $_SERVER["REQUEST_URI"] );
	unset( $url[0], $url[1] );
	if ( $length > 1 ) { // check the level of member page if page is on 3 level like www.abc.com/dating/members
		$count ++;
	} else if ( $length == 0 ) {// if page is on 0 level like www.abc.com/ home page is member page
		$count --;
	}
	// if $length==1 then page is on 2 level like www.abc.com/members
	$i2 = $count;
	$i3 = $count + 1;
	$i4 = $count + 2;
	$i5 = $count + 3;
	$i6 = $count + 4;
	if ( get_userid( $url[ $i2 ] ) != "" ) {
		if ( ! is_user_logged_in() ) {
			if ( '' != $url[ $i3 ] ) {
				if ( $url[ $i3 ] == 'my_profile' || $url[ $i3 ] == 'partner_profile' ) {
					$get = array(
						'pgurl'         => 'view_member',
						'guest_pageurl' => 'view_mem_profile',
						'mem_id'        => get_userid( $url[ $i2 ] ),
						'view'          => $url[ $i3 ]
					);
				} else {
					$get = array(
						'pgurl'         => 'view_member',
						'guest_pageurl' => 'view_mem_' . $url[ $i3 ],
						'mem_id'        => get_userid( $url[ $i2 ] )
					);
					if ( $length > 1 ) { //
						$j = 5;
					} else {
						$j = 4;
					}
					for ( $i = $j; $i < count( $url ); $i = $i + $i2 ) {
						$get[ $url[ $i ] ] = $url[ $i + 1 ];
					}
				}
			} else {
				$get = array(
					'pgurl'         => 'view_member',
					'guest_pageurl' => 'view_mem_profile',
					'mem_id'        => get_userid( $url[ $i2 ] )
				);
			}
			//  print_r($get);
		} else {
			//  echo '<br>url-3'.$url[$i3];
			if ( '' != $url[ $i3 ] ) {
				if ( $url[ $i3 ] == 'my_profile' || $url[ $i3 ] == 'partner_profile' ) {
					if ( $url[ $i4 ] == 'Action' ) {
						$get = array(
							'pid'       => 3,
							'mem_id'    => get_userid( $url[ $i2 ] ),
							'pagetitle' => "view_profile",
							'view'      => $url[ $i3 ],
							'Action'    => $url[ $i5 ],
							$url[ $i6 ] => $url[ $i7 ]
						);
					} else {
						$get = array(
							'pid'       => 3,
							'mem_id'    => get_userid( $url[ $i2 ] ),
							'pagetitle' => "view_profile",
							'view'      => $url[ $i3 ]
						);
					}
				} else if ( $url[ $i3 ] == 'Action' ) {
					$get = array(
						'pid'       => 3,
						'mem_id'    => get_userid( $url[ $i2 ] ),
						'pagetitle' => "view_profile",
						'Action'    => $url[ $i4 ],
						$url[ $i5 ] => $url[ $i6 ]
					);
				} else {
					$get = array(
						'pid'       => 3,
						'mem_id'    => get_userid( $url[ $i2 ] ),
						'pagetitle' => "view_" . $url[ $i3 ]
					);
					if ( $length > 1 ) { //
						$j = 5;
					} else {
						$j = 4;
					}
					for ( $i = $j; $i < count( $url ); $i = $i + $i2 ) {
						$get[ $url[ $i ] ] = $url[ $i + 1 ];
					}
				}
				// print_r($get);
			} else {
				$get = array( 'pid' => 3, 'mem_id' => get_userid( $url[ $i2 ] ), 'pagetitle' => "view_profile" );
			}
		}
	} else {
		if ( is_user_logged_in() || stristr( $url[4], 'zipcode' ) ) {
			// echo 'page-id=='.dsp_get_pageid($url[$i2]).' url2=='.$url[$i2];
			if ( dsp_get_pageid( $url[ $i2 ] ) != "" ) {
				// array_splice($url,0,0,'pid');
				$get['pid'] = dsp_get_pageid( $url[ $i2 ] );
				// echo 'call=='.dsp_get_pageid($url[$i2]);
				if ( dsp_get_pageid( $url[ $i2 ] ) == 14 ) {
					// print_r($_REQUEST);
					if ( isset( $_REQUEST['mode'] ) && isset( $_REQUEST['delmessage'] ) ) {
						$get = array(
							'pid'              => 14,
							'message_template' => $url[ $i3 ],
							'mode'             => $_REQUEST['mode'],
							'delmessage'       => $_REQUEST['delmessage']
						);
					} else {
						$get['message_template'] = $url[ $i3 ];
					}
				} else if ( dsp_get_pageid( $url[ $i2 ] ) == 15 ) {
					$get['pagetitle'] = 'chat';
				} else if ( dsp_get_pageid( $url[ $i2 ] ) == 10 ) {
					$get['pagetitle'] = 'online_mem';
				} else if ( dsp_get_pageid( $url[ $i2 ] ) == 8 || dsp_get_pageid( $url[ $i2 ] ) == 7 ) {

				} else {
					//echo $url[]
					$get['pagetitle'] = isset( $url[ $i3 ] ) ? $url[ $i3 ] : null;
					// echo $get['pagetitle'].' -->'.$url[$i3].' -->'.$i3;
				}
				if ( $length > 1 ) { //
					$j = 5;
				} else {
					$j = 4;
				}
				if ( dsp_get_pageid( $url[ $i2 ] ) == 8 || dsp_get_pageid( $url[ $i2 ] ) == 7 ) {
					if ( $length > 1 ) { //
						$j = 4;
					} else {
						$j = 3;
					}
				}
				// echo 'id==='.dsp_get_pageid($url[$i2]).'  =='.$url[$i3];
				if ( dsp_get_pageid( $url[ $i2 ] ) == 13 && $url[ $i3 ] == 'blogs' ) {
					$get['subpage'] = $url[ $i4 ];
					if ( $length > 1 ) { //
						$j = 6;
					} else {
						$j = 5;
					}
				} else if ( dsp_get_pageid( $url[ $i2 ] ) == 13 ) {  // check if page is trending save it's request with get
					if ( isset( $_REQUEST['profile_filter'] ) || isset( $_REQUEST['gender_filter'] ) ) {
						$get = array(
							'pid'            => 13,
							'pagetitle'      => $url[ $i3 ],
							'gender_filter'  => $_REQUEST['gender_filter'],
							'profile_filter' => $_REQUEST['profile_filter']
						);
					} else {
						$get = array( 'pid' => 13, 'pagetitle' => $url[ $i3 ] );
					}
				}
				for ( $i = $j; $i < count( $url ); $i = $i + 2 ) {
					$get[ $url[ $i ] ] = $url[ $i + 1 ];
				}


				// print_r($get);
			} else {

				//   print_r($get);

				if ( $length > 1 ) { //
					$k = 1;
					$m = 1;
				} else {
					$k = 0;
					$m = 0;
				}
				array_splice( $url, $k, 0, 'pgurl' );
				//print_r($url);
				//echo '<br>========';
				for ( $i = $m; $i < count( $url ); $i = $i + 2 ) {
					$get[ $url[ $i ] ] = isset( $url[ $i + 1 ] ) ? $url[ $i + 1 ] : '';
				}
			}
		}
	}

	return isset( $get[ $string ] ) ? $get[ $string ] : '';
}


function get( $string ) {
	$count = 2;
	$root  = get_bloginfo( 'url' );
	$Url   = explode( '/', $root );
	unset( $Url[0], $Url[1] );
	$length = sizeof( $Url );
	$url    = explode( '/', $_SERVER["REQUEST_URI"] );
	unset( $url[0], $url[1] );
	if ( $length > 1 ) { // check the level of member page if page is on 3 level like www.abc.com/dating/members
		$count ++;
	} else if ( $length == 0 ) {// if page is on 0 level like www.abc.com/ home page is member page
		$count --;
	}
	// if $length==1 then page is on 2 level like www.abc.com/members
	$i2 = $count;
	$i3 = $count + 1;
	$i4 = $count + 2;
	$i5 = $count + 3;
	$i6 = $count + 4;
	if ( get_userid( $url[ $i2 ] ) != "" ) {
		if ( ! is_user_logged_in() ) {
			if ( '' != $url[ $i3 ] ) {
				if ( $url[ $i3 ] == 'my_profile' || $url[ $i3 ] == 'partner_profile' ) {
					$get = array(
						'pgurl'         => 'view_member',
						'guest_pageurl' => 'view_mem_profile',
						'mem_id'        => get_userid( $url[ $i2 ] ),
						'view'          => $url[ $i3 ]
					);
				} else {
					$get = array(
						'pgurl'         => 'view_member',
						'guest_pageurl' => 'view_mem_' . $url[ $i3 ],
						'mem_id'        => get_userid( $url[ $i2 ] )
					);
					if ( $length > 1 ) { //
						$j = 5;
					} else {
						$j = 4;
					}
					for ( $i = $j; $i < count( $url ); $i = $i + $i2 ) {
						$get[ $url[ $i ] ] = $url[ $i + 1 ];
					}
				}
			} else {
				$get = array(
					'pgurl'         => 'view_member',
					'guest_pageurl' => 'view_mem_profile',
					'mem_id'        => get_userid( $url[ $i2 ] )
				);
			}
			//  print_r($get);
		} else {
			//  echo '<br>url-3'.$url[$i3];
			if ( '' != $url[ $i3 ] ) {
				if ( $url[ $i3 ] == 'my_profile' || $url[ $i3 ] == 'partner_profile' ) {
					if ( $url[ $i4 ] == 'Action' ) {
						$get = array(
							'pid'       => 3,
							'mem_id'    => get_userid( $url[ $i2 ] ),
							'pagetitle' => "view_profile",
							'view'      => $url[ $i3 ],
							'Action'    => $url[ $i5 ],
							$url[ $i6 ] => $url[ $i7 ]
						);
					} else {
						$get = array(
							'pid'       => 3,
							'mem_id'    => get_userid( $url[ $i2 ] ),
							'pagetitle' => "view_profile",
							'view'      => $url[ $i3 ]
						);
					}
				} else if ( $url[ $i3 ] == 'Action' ) {
					$get = array(
						'pid'       => 3,
						'mem_id'    => get_userid( $url[ $i2 ] ),
						'pagetitle' => "view_profile",
						'Action'    => $url[ $i4 ],
						$url[ $i5 ] => $url[ $i6 ]
					);
				} else {
					$get = array(
						'pid'       => 3,
						'mem_id'    => get_userid( $url[ $i2 ] ),
						'pagetitle' => "view_" . $url[ $i3 ]
					);
					if ( $length > 1 ) { //
						$j = 5;
					} else {
						$j = 4;
					}
					for ( $i = $j; $i < count( $url ); $i = $i + $i2 ) {
						$get[ $url[ $i ] ] = $url[ $i + 1 ];
					}
				}
				// print_r($get);
			} else {
				$get = array( 'pid' => 3, 'mem_id' => get_userid( $url[ $i2 ] ), 'pagetitle' => "view_profile" );
			}
		}
	} else {
		if ( ! is_user_logged_in() ) {
			if ( $length > 1 ) { //
				$k = 1;
				$m = 1;
			} else {
				$k = 0;
				$m = 0;
			}
			array_splice( $url, $k, 0, 'pgurl' );
			//print_r($url);
			//echo '<br>========';
			for ( $i = $m; $i < count( $url ); $i = $i + 2 ) {
				$get[ $url[ $i ] ] = isset( $url[ $i + 1 ] ) ? $url[ $i + 1 ] : '';
			}
			// print_r($get);
		} else {
			// echo 'page-id=='.dsp_get_pageid($url[$i2]).' url2=='.$url[$i2];
			if ( dsp_get_pageid( $url[ $i2 ] ) != "" ) {
				// array_splice($url,0,0,'pid');
				$get['pid'] = dsp_get_pageid( $url[ $i2 ] );
				// echo 'call=='.dsp_get_pageid($url[$i2]);
				if ( dsp_get_pageid( $url[ $i2 ] ) == 14 ) {
					// print_r($_REQUEST);
					if ( isset( $_REQUEST['mode'] ) && isset( $_REQUEST['delmessage'] ) ) {
						$get = array(
							'pid'              => 14,
							'message_template' => $url[ $i3 ],
							'mode'             => $_REQUEST['mode'],
							'delmessage'       => $_REQUEST['delmessage']
						);
					} else {
						$get['message_template'] = $url[ $i3 ];
					}
				} else if ( dsp_get_pageid( $url[ $i2 ] ) == 15 ) {
					$get['pagetitle'] = 'chat';
				} else if ( dsp_get_pageid( $url[ $i2 ] ) == 10 ) {
					$get['pagetitle'] = 'online_mem';
				} else if ( dsp_get_pageid( $url[ $i2 ] ) == 8 || dsp_get_pageid( $url[ $i2 ] ) == 7 ) {

				} else {
					//echo $url[]
					$get['pagetitle'] = isset( $url[ $i3 ] ) ? $url[ $i3 ] : null;
					// echo $get['pagetitle'].' -->'.$url[$i3].' -->'.$i3;
				}
				if ( $length > 1 ) { //
					$j = 5;
				} else {
					$j = 4;
				}
				if ( dsp_get_pageid( $url[ $i2 ] ) == 8 || dsp_get_pageid( $url[ $i2 ] ) == 7 ) {
					if ( $length > 1 ) { //
						$j = 4;
					} else {
						$j = 3;
					}
				}
				// echo 'id==='.dsp_get_pageid($url[$i2]).'  =='.$url[$i3];
				if ( dsp_get_pageid( $url[ $i2 ] ) == 13 && $url[ $i3 ] == 'blogs' ) {
					$get['subpage'] = $url[ $i4 ];
					if ( $length > 1 ) { //
						$j = 6;
					} else {
						$j = 5;
					}
				} else if ( dsp_get_pageid( $url[ $i2 ] ) == 13 ) {  // check if page is trending save it's request with get
					if ( isset( $_REQUEST['profile_filter'] ) || isset( $_REQUEST['gender_filter'] ) ) {
						$get = array(
							'pid'            => 13,
							'pagetitle'      => $url[ $i3 ],
							'gender_filter'  => $_REQUEST['gender_filter'],
							'profile_filter' => $_REQUEST['profile_filter']
						);
					} else {
						$get = array( 'pid' => 13, 'pagetitle' => $url[ $i3 ] );
					}
				}
				for ( $i = $j; $i < count( $url ); $i = $i + 2 ) {
					$get[ $url[ $i ] ] = $url[ $i + 1 ];
				}
				//   print_r($get);
			}
		}
	}

	return isset( $get[ $string ] ) ? $get[ $string ] : '';
}

function get_username( $user_id ) {
	$user_info = get_userdata( $user_id );

	return $user_info->user_login;
}

function get_userid( $username ) {
	$user = get_user_by( 'login', $username );
	$id   = ( isset( $user ) && ! empty( $user ) ) ? $user->ID : '';

	return $id; // prints the id of the user
}

function dsp_get_pageid( $name ) {
	$page_array = array(
		'home'           => 1,
		'edit'           => 2,
		'view'           => 3,
		'media'          => 4,
		'search'         => 5,
		'setting'        => 6,
		'add_favorites'  => 7,
		'add_friend'     => 8,
		'print'          => 9,
		'online_members' => 10,
		'geography'      => 11,
		'extras'         => 13,
		'email'          => 14,
		'help'           => 16,
		'chat'           => 15,
		'stories'        => 17
	);
	$name       = ( isset( $name ) && ! empty( $name ) ) ? $name : 'home';

	return array_key_exists( $name, $page_array ) ? $page_array[ $name ] : 'home';
}

function custom_css() {
	global $wpdb;
	//$wp_userID = get_current_user_id();
	$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
	// $dsp_user_profiles =  $wpdb->prefix . DSP_USER_PROFILES_TABLE;
	$check_button_color         = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'button_color'" );
	$check_non_active_tab_color = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'non_active_tab_color'" );
	$check_tab_color            = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'tab_color'" );
	$check_pagination_color     = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'pagination_color'" );
	$check_title_color          = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'title_color'" );
	?>
    <style>
    button, input[type="submit"], input[type="button"], input[type="reset"], .btn-reply {
        background: #<?php echo $check_button_color->setting_value; ?>;
    }

    .dsp-line {
        background: #<?php echo $check_non_active_tab_color->setting_value; ?>;
    }

    .heading-text {
        background: #<?php echo $check_non_active_tab_color->setting_value; ?>;
    }

    .tab-box a.activeLink {
        background-color: #<?php echo $check_non_active_tab_color->setting_value; ?>;
    }

    .wpse_pagination .disabled {
        background: #<?php echo $check_pagination_color->setting_value; ?>;
    }

    .age-text {
        color: #<?php echo $check_non_active_tab_color->setting_value; ?>;
    }

    #dsp_plugin .profle-detail ul.quick-star-details li {
        color: #<?php echo $check_non_active_tab_color->setting_value; ?>;
    }

    .right-link span {
        color: #<?php echo $check_non_active_tab_color->setting_value; ?>;
    }

    .dsp_tab1 {
        border-right: 2px solid #<?php echo $check_non_active_tab_color->setting_value; ?>;
    }

    .line {
        background: #<?php echo $check_tab_color->setting_value; ?>;
    }

    .dsp_tab1-active {
        background-color: #<?php echo $check_tab_color->setting_value; ?>;
        border-right: 2px solid #<?php echo $check_tab_color->setting_value; ?>;
    }

    .btn-link {
        background: #<?php echo $check_button_color->setting_value; ?>;
    }

    .heading-submenu {
        color: #<?php echo $check_title_color->setting_value; ?>;
        border-bottom: 2px solid #<?php echo $check_title_color->setting_value; ?>;
    }

    .linkright-view-profile-page span {
        color: #<?php echo $check_title_color->setting_value; ?>;
    }
    </style><?php
}

add_action( 'wp_loaded', 'dsp_session_language_initialize' );

function dsp_session_language_initialize( $user_login, $user = '', $lang_id = '' ) {
	//session_start();
	global $wpdb;
	$dsp_session_language      = $wpdb->prefix . "session_language";
	$dsp_language_detail_table = $wpdb->prefix . "dsp_language_details";
	$user_id                   = get_current_user_id();
	$session_id                = session_id();
	if ( isset( $user ) && ! empty( $user ) ) {
		$user_id = $user->ID;
	}

	if ( ! empty( $lang_id ) ) {
		if ( $user_id == 0 ) {
			//echo "SELECT ID from $dsp_session_language WHERE user_id = '0'";die;
			$default_id = $wpdb->get_var( "SELECT ID from $dsp_session_language WHERE `user_id` = 0" );
			if ( empty( $default_id ) ) {
				$wpdb->query( "INSERT INTO  $dsp_session_language (`user_id`,`session_id`,`language_id`)
								   VALUES ('0','$session_id','$lang_id')" );
			} else {
				$wpdb->query( "UPDATE $dsp_session_language SET language_id='$lang_id',session_id = '$session_id' WHERE ID = '$default_id'" );
			}

		} else {
			$wpdb->query( "UPDATE $dsp_session_language SET language_id='$lang_id' WHERE user_id = '$user_id'" );
		}
		unset( $_SESSION['default_lang'] );
		$_SESSION['default_lang'] = $lang_id;
	} else {
		//$lanugage_id = $wpdb->get_var("select language_id from $dsp_session_language where session_id='$session_id'");
		$lanugage_id         = $wpdb->get_var( "SELECT language_id FROM $dsp_session_language WHERE user_id='" . $user_id . "'" );
		$default_language_id = $wpdb->get_row( "SELECT * FROM $dsp_language_detail_table WHERE display_status = '1'" );
		if ( $lanugage_id == "" ) {
			$wpdb->query( "INSERT INTO $dsp_session_language(ID,user_id,session_id, language_id) VALUES('','" . $user_id . "','$session_id','$default_language_id->language_id')" );

		}

	}

}

add_action( 'wp_loaded', 'dsp_change_language' );
function dsp_change_language() {
	global $wpdb;
	$dsp_session_language      = $wpdb->prefix . "session_language";
	$dsp_language_detail_table = $wpdb->prefix . "dsp_language_details";
	$user_id                   = get_current_user_id();
	if ( isset( $_REQUEST['lid'] ) && $user_id != 0 ) {
		if ( $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_session_language WHERE user_id='$user_id' " ) != 0 ) {
			$wpdb->query( "UPDATE $dsp_session_language SET language_id ='" . $_REQUEST['lid'] . "' WHERE user_id='" . $user_id . "'" );
		} else if ( $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_session_language WHERE session_id='$session_id' " ) != 0 ) {
			$wpdb->query( "UPDATE $dsp_session_language SET language_id ='" . $_REQUEST['lid'] . "' , user_id=$user_id WHERE session_id='" . $session_id . "'" );
		} else {
			$wpdb->query( "INSERT INTO $dsp_session_language(ID,user_id,session_id, language_id)VALUES('','" . $user_id . "','$session_id','" . $_REQUEST['lid'] . "')" );
		}
	}
}

// paypal standard subscription / recurring ipn listener
add_action( 'wp_loaded', 'dsp_paypal_ipn_listener' );
if ( ! function_exists( 'dsp_paypal_ipn_listener' ) ) {
	function dsp_paypal_ipn_listener() {
		global $wpdb;
		$dsp_paypal_recurring_table = $wpdb->prefix . "dsp_paypal_recurring";
		if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'https://www.paypal.com/ipn' ) !== false && ( isset( $_POST['txn_type'] ) ) && ( isset( $_GET['recurring'] ) && $_GET['recurring'] == 'true' ) ) {
			//file_put_contents('new_values.txt',print_r($_POST,true),FILE_APPEND);
			$user_id_pos             = strpos( $_POST['item_number'], '-' );
			$user_id                 = substr( $_POST['item_number'], 0, $user_id_pos );
			$membership_id           = substr( $_POST['item_number'], $user_id_pos + 1 );
			$dsp_default_paypal_keys = array(
				'item_name',
				'item_number',
				'subscr_id',
				'first_name',
				'last_name',
				'residence_country',
				'payer_email',
				'payer_id',
				'business',
				'receiver_email',
				'txn_type',
				'mc_currency'
			);
			$dsp_paypal_keys         = array();
			$insert_array            = array();

			if ( $_POST['txn_type'] == 'subscr_signup' ) {
				$dsp_specific_keys = array( 'recurring', 'test_ipn', 'subscr_date', 'amount3', 'period3' );
			} elseif ( $_POST['txn_type'] == 'subscr_payment' ) {
				$dsp_specific_keys = array( 'payment_date', 'payment_status', 'txn_id', 'mc_fee', 'mc_gross' );
			} elseif ( $_POST['txn_type'] == 'subscr_cancel' ) {
				$dsp_specific_keys = array( 'recurring', 'test_ipn', 'subscr_date', 'amount3', 'period3' );
			}

			$dsp_paypal_keys = array_merge( $dsp_default_paypal_keys, $dsp_specific_keys );
			foreach ( $dsp_paypal_keys as $key ) {
				$insert_array[ $key ] = $_POST[ $key ];
			}

			$insert_array['user_id']       = $user_id;
			$insert_array['membership_id'] = $membership_id;
			$insert_array['timestamp']     = time();

			if ( $_POST['txn_type'] == 'subscr_signup' ) {
				$insert_array['status'] = 'active';
			} elseif ( $_POST['txn_type'] == 'subscr_cancel' ) {
				$subscr_id = $_POST['subscr_id'];
				$wpdb->query( "UPDATE $dsp_paypal_recurring_table SET status = 'cancelled' WHERE subscr_id = '$subscr_id' AND txn_type = 'subscr_signup'" );
			}

			$wpdb->insert( $dsp_paypal_recurring_table, $insert_array );

			// grant or renew membership
			if ( $_POST['txn_type'] == 'subscr_payment' && $_POST['payment_status'] == 'Completed' ) {
				$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
				$dsp_payments_table    = $wpdb->prefix . DSP_PAYMENTS_TABLE;

				//file_put_contents('new_values.txt',print_r($user_id,true),FILE_APPEND);
				//file_put_contents('new_values.txt',print_r($membership_id,true),FILE_APPEND);

				$membership_price = $_POST['mc_gross'];
				$payment_date     = date( "Y-m-d" );
				$membership_info  = $wpdb->get_row( "SELECT * FROM $dsp_memberships_table where membership_id='$membership_id'" );

				$check_already_user_exists = $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'" );
				if ( $check_already_user_exists <= 0 ) {
					$wpdb->query( "INSERT INTO $dsp_payments_table SET pay_user_id = '$user_id',pay_plan_id = '$membership_id',pay_plan_amount ='$membership_price',pay_plan_days='$membership_info->no_of_days',pay_plan_name='$membership_info->name',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $membership_info->no_of_days DAY),payment_status=1" );
				} else {
					$wpdb->query( "UPDATE $dsp_payments_table SET pay_plan_id = '$membership_id',pay_plan_amount ='$membership_price',pay_plan_days='$membership_info->no_of_days',pay_plan_name='$membership_info->name',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $membership_info->no_of_days DAY),payment_status=1  WHERE pay_user_id = '$user_id'" );
				}

				if ( dsp_issetGivenEmailSetting( $user_id, 'payment_successful' ) ) {
					$email_template         = $wpdb->get_row( "SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='16'" );
					$reciver_details        = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID = '$user_id'" );
					$reciver_name           = $reciver_details->display_name;
					$receiver_email_address = $reciver_details->user_email;
					$siteurl                = get_option( 'siteurl' );
					$email_subject          = $email_template->subject;
					$email_message          = $email_template->email_body;
					$email_message          = str_replace( "<#RECEIVER_NAME#>", $reciver_name, $email_message );
					$email_message          = str_replace( "<#DOMAIN_NAME#>", $siteurl, $email_message );
					$MemberEmailMessage     = $email_message;
					$to                     = $receiver_email_address;
					$subject                = $email_subject;
					$message                = $MemberEmailMessage;
					$admin_email            = get_option( 'admin_email' );
					$from                   = $admin_email;
					$headers                = "From: $from";
					// wp_mail( $to, $subject, $message, $headers );
					$wpdating_email = Wpdating_email_template::get_instance();
					$result         = $wpdating_email->send_mail( $to, $subject, $message );
				}
			}
		}
	}
}

// add users currrent_ip,country,city meta after user login
function dsp_register_current_values( $user_login ) {
	global $wpdb;
	$user    = $user_login;
	$content = wp_remote_get( "http://ipinfo.io/" . $_SERVER['REMOTE_ADDR'] . "/json" );
	if (
		isset( $content ) &&
		! empty( $content ) &&
		empty( $content->errors )

	) {
		try {
			$content  = $content['body'];
			$detail[] = json_decode( $content, true );
			if ( array_key_exists( 'country', $detail[0] ) ) {
				$country_abbr  = $detail[0]['country'];
				$country_array = countryArray();
				$country       = $country_array[ $country_abbr ];
				$city          = $detail[0]['city'];
				$pos           = $detail[0]['loc'];
				$position      = dsp_extractlatlong( $pos );
				! empty( $position ) ? do_action( 'dsp_savePosition', $position ) : '';
				$dsp_country_table = $wpdb->prefix . "dsp_country";
				$dsp_city_table    = $wpdb->prefix . "dsp_city";
				$country_id        = $wpdb->get_results( $wpdb->prepare( "select country_id from $dsp_country_table where name=%s", $country ) );
				$city_id           = $wpdb->get_results( $wpdb->prepare( "select * from $dsp_city_table where country_id=%s and name like '%%%s%%'", $country_id[0]->country_id, $city ) );
				update_user_meta( $user->ID, 'user_current_ip', $_SERVER['REMOTE_ADDR'] );
				update_user_meta( $user->ID, 'user_current_country', $country );
				update_user_meta( $user->ID, 'user_current_country_id', $country_id[0]->country_id );
				update_user_meta( $user->ID, 'user_current_city', $city );
				update_user_meta( $user->ID, 'user_current_city_id', $city_id[0]->city_id );
			}
		} catch ( Exception $e ) {
			echo $e->getMessage();
		}
	}
}

add_action( 'wp_login', 'dsp_register_current_values', 10, 1 );

function countryArray() {
	return array(
		'AF' => 'Afghanistan',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua And Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia And Herzegovina',
		'BW' => 'Botswana',
		'BV' => 'Bouvet Island',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory',
		'BN' => 'Brunei',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'Cocos (Keeling) Islands',
		'CO' => 'Columbia',
		'KM' => 'Comoros',
		'CG' => 'Congo',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'CI' => 'Cote D\'Ivorie (Ivory Coast)',
		'HR' => 'Croatia (Hrvatska)',
		'CU' => 'Cuba',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'CD' => 'Democratic Republic Of Congo (Zaire)',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'TP' => 'East Timor',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FK' => 'Falkland Islands (Malvinas)',
		'FO' => 'Faroe Islands',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'FX' => 'France, Metropolitan',
		'GF' => 'French Guinea',
		'PF' => 'French Polynesia',
		'TF' => 'French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard And McDonald Islands',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Laos',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macau',
		'MK' => 'Macedonia',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar (Burma)',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'AN' => 'Netherlands Antilles',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'KP' => 'North Korea',
		'MP' => 'Northern Mariana Islands',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'RE' => 'Reunion',
		'RO' => 'Romania',
		'RU' => 'Russia',
		'RW' => 'Rwanda',
		'SH' => 'Saint Helena',
		'KN' => 'Saint Kitts And Nevis',
		'LC' => 'Saint Lucia',
		'PM' => 'Saint Pierre And Miquelon',
		'VC' => 'Saint Vincent And The Grenadines',
		'SM' => 'San Marino',
		'ST' => 'Sao Tome And Principe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovak Republic',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia And South Sandwich Islands',
		'KR' => 'South Korea',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard And Jan Mayen',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syria',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad And Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks And Caicos Islands',
		'TV' => 'Tuvalu',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'UK' => 'United Kingdom',
		'US' => 'United States',
		'UM' => 'United States Minor Outlying Islands',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VA' => 'Vatican City (Holy See)',
		'VE' => 'Venezuela',
		'VN' => 'Vietnam',
		'VG' => 'Virgin Islands (British)',
		'VI' => 'Virgin Islands (US)',
		'WF' => 'Wallis And Futuna Islands',
		'EH' => 'Western Sahara',
		'WS' => 'Western Samoa',
		'YE' => 'Yemen',
		'YU' => 'Yugoslavia',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);
}

include_once 'dsp_sc_login.php';
include_once 'dsp_sc_register.php';
include_once 'dsp_sc_search.php';
