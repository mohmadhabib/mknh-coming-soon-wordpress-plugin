<?php

/*
Plugin Name: MKNH Coming Soon
Plugin URI: https://www.mknh.net/plugins/mknh-coming-soon
Description: Light and easy to use bootstrap based coming soon plugin Based on https://github.com/timcreative/coming-sssoon-wordpress-plugin.
Version: 1.0.1
Author: Mohammad Habib
Author URI: https://mknh.net
Copyright: 2021
License: GPL3
*/
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Plugin constants
define( 'MKNH_VERSION', '1.0.1' );
define( 'MKNH_MAIN_FILE', __FILE__ );
define( 'MKNH-CS', 'mknh-cs' );

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'mknh_cs_activate');

function mknh_cs_activate(){
    $mknh_cs_options = array(
        'title'=>'Coming Soon',
        'heading'=>'We Are Coming Soon',
        'abouttext'=>'Just Wait, We are Making the Best Website Ever for our Loyal Visitors'
    );

    $mknh_cs_design_options = array(
        'bg'=>'image',
        'bgimg'=>plugins_url( 'lib/images/default.jpg', __FILE__ ),
        'fb'=>1,
        'tw'=>1,
        'email'=>1,
        'social'=>1
    );

    $mknh_cs_options_settings = array(
        'radioinput'=>'enabled',
        'credits'=>1
    );

    $mknh_cs_mailing_options = array(
        'enable'=>0
    );

    update_option('mknh_cs_options',$mknh_cs_options);
    update_option('mknh_cs_design_options',$mknh_cs_design_options);
    update_option('mknh_cs_options_settings',$mknh_cs_options_settings);
    update_option('mknh_cs_mailing_options',$mknh_cs_mailing_options);
}
require_once ('lib/mknh-cs-options.php' );
require_once ('lib/mknh-cs-front-view.php' );

add_action('admin_menu','mknh_cs_menu');
function mknh_cs_menu() {
    add_menu_page('MKNH CS Settings', 'MKNH CS Settings', 'administrator', 'mknh-settings-soon', 'pluginSettingsPage', 'dashicons-menu-alt3');
}
function pluginSettingsPage() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-form');
    mknh_cs_admin_settings();
}

function mknh_cs_enable() {

    if(is_admin()){
        return;
    }

    $status = get_option('mknh_cs_options_settings');
    //$options = get_option('mknh_cs_options');
    if ($status['radioinput'] === 'disabled'){
        return;
    }

    if (!current_user_can('edit_posts') && !in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ))) {
        $protocol = "HTTP/1.0";
        if ("HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"]) {
            $protocol = "HTTP/1.1";
        }
        header("$protocol 503 Service Unavailable", true, 503);
        header("Retry-After: 3600");
        wp_enqueue_script('jquery');
        mknh_cs_front_view();

        exit();
    }
}
add_action('init', 'mknh_cs_enable');

add_action('plugins_loaded', 'mknh_cs_send_mail');
function mknh_cs_send_mail()
{
    if (isset($_POST['mknh_cs_email'])) {
        $email = $_POST['mknh_cs_email'];
        $message = get_bloginfo('name') . ' (' . get_bloginfo('url') . ') is coming soon. Please keep an eye on this space.';
        $subject = get_bloginfo('name') . ' is coming soon';
        wp_mail($email, $subject, $message);
    }
}

add_action('plugins_loaded', 'mc_nl_mail');
function mc_nl_mail()
{
    if (isset($_POST['nl_mail'])) {
        $email_address = $_POST['nl_mail'];
        include_once("lib/MailChimp.php");
        $mc_api = get_option('mknh_cs_mailing_options');
        $mc_api_key = $mc_api['mailchimp_api'];

        $mc_list = $mc_api['mclist'];

        $MailChimp = new MailChimp($mc_api_key);

        $MailChimp->post('lists/'.$mc_list.'/members', array(
            'email_address'     => $email_address,
            'status'            => 'subscribed'
        ));
    }
}

add_action( 'admin_enqueue_scripts', 'wp_enqueue_color_picker' );
function wp_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker');
    //wp_enqueue_script( 'wp-color-picker-script-handle', plugins_url('wp-color-picker-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

/*
* This Function to Add the Settings Link Under The Plugin in (Plugins Page)
*/
function mknh_coming_soon_settings_link($links) { 
    $settings_link = '<a href="options-general.php?page=mknh-settings-soon">Settings</a>'; 
    array_unshift($links, $settings_link); 
    return $links; 
  }
  $plugin = plugin_basename(__FILE__); 
  add_filter("plugin_action_links_$plugin", 'mknh_coming_soon_settings_link' );

/*
* Upload the Photos On Plugin Activation
*/
