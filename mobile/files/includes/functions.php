<?php
/*

  Copyright (C) www.wpdating.com - All Rights Reserved!

  Author - MyAllenMedia, LLC

  WordPress Dating Plugin

  contact@wpdating.com

 */

//build menus

if (is_admin()) {

    add_action('admin_menu', 'dsp_add_pages');
}

// Function to Create Menu and submenu in Admin:
// action function for above hook

function dsp_add_pages() {

    $optionpage_top_level = "DSP Admin";

    // userlevel=8 restrict users to "Administrators" only 
    // Add a new submenu under Options:
    // add_options_page('Test Options', 'Test Options', 'administrator', 'testoptions', 'dsp_options_page');
    // Add a new top-level menu (ill-advised): add_menu_page(page_title, menu_title, capability, handle, [function], [icon_url])

    add_menu_page($optionpage_top_level, $optionpage_top_level, 'administrator', 'dsp-admin-sub-page1', 'dsp_settings_page');

    // Add a submenu to the custom top-level menu: add_submenu_page(parent, page_title, menu_title, capability required, file/handle, [function])

    add_submenu_page('dsp-admin-sub-page1', 'DSP Settings', 'Settings', 'administrator', 'dsp-admin-sub-page1', 'dsp_settings_page');

    // Add a second submenu to the custom top-level menu:

    add_submenu_page('dsp-admin-sub-page1', 'DSP Media', 'Media', 'administrator', 'dsp-admin-sub-page2', 'dsp_media_page');



    // Add a second submenu to the custom top-level menu:

    add_submenu_page('dsp-admin-sub-page1', 'DSP Tools', 'Tools', 'administrator', 'dsp-admin-sub-page3', 'dsp_tools_page');



    // Add a second submenu to the custom top-level menu:

    add_submenu_page('dsp-admin-sub-page1', 'DSP Reports', 'Reports', 'administrator', 'dsp-admin-sub-page4', 'dsp_reports_page');



    // Add a second submenu to the custom top-level menu:
    // add_submenu_page(dsp-admin-sub-page1, 'DSP Styles', 'Styles', '8', 'dsp-admin-sub-page5', 'dsp_styles_page');
}

// dsp_settings_page() displays the page content for the Settings submenu
// of the DSP ADMIN Toplevel menu

function dsp_settings_page() {

    include( WP_DSP_ABSPATH . 'files/includes/dsp_settings_header.php');
}

//dsp_media_page() displays the page content for the Media submenu

function dsp_media_page() {

    include( WP_DSP_ABSPATH . 'files/includes/dsp_media_header.php');
}

//dsp_tools_page() displays the page content for the Tools submenu

function dsp_tools_page() {

    include( WP_DSP_ABSPATH . 'files/includes/dsp_tools_header.php');
}

// mt_toplevel_page() displays the page content for the custom Test Toplevel menu

function dsp_home_page() {

    echo "<h2>DSP Admin</h2>";
}

function dsp_reports_page() {

    include( WP_DSP_ABSPATH . 'files/includes/dsp_reports_header.php');
}

// mt_sublevel_page2() displays the page content for the second submenu
// of the DSP ADMIN Toplevel menu
//function dsp_media_page() {
//   echo "<h2>Media</h2>";
//}
//  include function.js file in plugin pages 

function plugin_admin_head_js() {

    $jsfile = plugins_url('dsp_dating/js/functions.js');
    ?>

    <script type='text/javascript' src='<?php echo $jsfile ?>'></script>

    <?php
}

//  include dsp_styles.css file in plugin pages 

function plugin_admin_head_css() {

    $style = plugins_url('dsp_dating/css/dsp_styles.css');
    ?>

    <link rel="stylesheet" href="<?php echo $style ?>" type="text/css" media="screen" />

    <?php
}

/**

 * -------------------------Created Quick Search Widget--------------------------------------------------

 */
function quickSearchwidgetform() {

    include(WP_DSP_ABSPATH . 'wp_search_wiget_form.php');
}

function widget_dspquicksearch($args) {

    extract($args);

    echo $before_widget;

    echo $before_title;
    ?>Quick Search<?php
    echo $after_title;

    quickSearchwidgetform();

    echo $after_widget;
}

function language_code($code) {

    global $wpdb;

    $dsp_language_table = $wpdb->prefix . "dsp_language";

    //echo $dsp_payments_query="SELECT * FROM $dsp_language_table WHERE code_name = '$code'";

    $exist_user_name = mysql_fetch_array(mysql_query("SELECT text_name FROM $dsp_language_table WHERE code_name = '$code'"));

    $text_name = $exist_user_name['text_name'];

    //echo $text_name = $wpdb->get_row($dsp_payments_query);

    return $text_name;
}

;

function dspquicksearch_init() {

    wp_register_sidebar_widget(
        'dsp_quick_search_widget', // your unique widget id
        'Quick Search Widget', // widget name
        'widget_dspquicksearch', // callback function
        array(// options

        'description' => 'A Quick Search widget that displays dsp dating quick Search form.'
        )
    );
}

add_action("plugins_loaded", "dspquicksearch_init");

/**

 * -------------------------Created Quick Search Widget--------------------------------------------------

 */
add_action('admin_head', 'plugin_admin_head_js');

add_action('admin_head', 'plugin_admin_head_css');

function checkstatus() {

    global $wpdb;
    $current_user = wp_get_current_user();


    $ip = $_SERVER['REMOTE_ADDR'];



    $row = $wpdb->get_var("SELECT ip_status FROM " . $wpdb->prefix . "dsp_blacklist_members WHERE ip_address = '" . $ip . "' LIMIT 0,1");



    if ($row == 1) {

        wp_redirect(get_option('siteurl') . '/wp-login.php?disabled=true');

        wp_logout();

        $setMessage = 1;
    }
}

add_action('init', 'checkstatus');

function display_message() {



    if (isset($_GET['disabled'])) {

        $message = '<div id="login_error">	<strong>ERROR</strong>: Admin disabled your account.<br>

</div>';

        return $message;
    }
}

add_filter('login_message', 'display_message');

//create the shortcode [include] that accepts a filepath and query string
//this function was modified from a post on www.amberpanther.com you can find it at the link below:
//http://www.amberpanther.com/knowledge-base/using-the-wordpress-shortcode-api-to-include-an-external-file-in-the-post-content/
//BEGIN amberpanther.com code

function include_file($atts) {

    //if filepath was specified

    extract(
        shortcode_atts(
            array(
        'filepath' => 'NULL'
            ), $atts
        )
    );

    //BEGIN modified portion of code to accept query strings
    //check for query string of variables after file path

    if (strpos($filepath, "?")) {

        $query_string_pos = strpos($filepath, "?");

        //create global variable for query string so we can access it in our included files if we need it
        //also parse it out from the clean file name which we will store in a new variable for including

        global $query_string;

        $query_string = substr($filepath, $query_string_pos + 1);

        $clean_file_path = substr($filepath, 0, $query_string_pos);

        //if there isn't a query string
    } else {

        $clean_file_path = $filepath;
    }

    //END modified portion of code
    //check if the filepath was specified and if the file exists

    if ($filepath != 'NULL' && file_exists(WP_DSP_ABSPATH . "/" . $clean_file_path)) {

        //turn on output buffering to capture script output

        ob_start();

        //include the specified file

        include(WP_DSP_ABSPATH . $clean_file_path);

        //assign the file output to $content variable and clean buffer

        $content = ob_get_clean();

        //return the $content
        //return is important for the output to appear at the correct position
        //in the content

        return $content;
    }
}

//register the Shortcode handler

add_shortcode('include', 'include_file');

//END amberpanther.com code
//shortcode with sample query string:
//[include filepath="profile_header.php"]
?>