<?php
/*
Plugin Name: One Time Password (OTP)
Description: One-time password (OTP) systems provide a mechanism for logging on to a network or service using a unique password that can only be used once.
Author: Najaf Cards
Version: 1.0.0
*/



if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// enque ajax call in script
add_action('wp_head', 'myplugin_ajaxurl');

function myplugin_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

// calling sidebar menu
include_once dirname(__FILE__) . '/admin/admin-menu.php';
include_once dirname(__FILE__) . '/admin/pages/configure.php';
include_once dirname(__FILE__) . '/admin/pages/otp_welcome.php';
include_once dirname(__FILE__) . '/functions/functionality.php';
include_once dirname(__FILE__) . '/functions/otp_db.php';

// including admin styles and JS files
function otp_enque_scripts()
{
    // styles
    wp_enqueue_style('main-css', plugin_dir_url(__FILE__) . 'assets/css/main.css', array(), time());

    // javascipt
    wp_enqueue_script('main-js', plugin_dir_url(__FILE__) . 'assets/js/main.js', array(), time());
}
add_action('admin_enqueue_scripts', 'otp_enque_scripts');

// including front styles and JS files
function otp_front_enque_scripts()
{
    // styles
    wp_enqueue_style('main-css', plugin_dir_url(__FILE__) . 'assets/css/front-styles.css', array(), time());
    
    // javascipt
    wp_enqueue_script('main-js', plugin_dir_url(__FILE__) . 'assets/js/front-main.js', array('jquery'), time());
}
add_action('wp_enqueue_scripts', 'otp_front_enque_scripts');

// configuration plugin link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'otp_configuration_link' );
function otp_configuration_link( array $links ) {
    $url = get_admin_url() . "admin.php?page=configure";
    $config_link = '<a href="' . $url . '">' . __('Configure', 'textdomain') . '</a>';
    $links[] = $config_link;
    return $links;
}

