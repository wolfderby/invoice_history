<?php
/*
* Plugin Name: Invoice History
* Description: Pulls acf invoice into a report
* Version: 1.0.0
* Author: Brian Buck
* Author URI: http://github.com/wolfderby
* Text Domain: my-custom-admin-page
*/

//require(dirname(__FILE__) . '/wp-load.php');
/*require('../wp-load.php');*/
//echo dirname(__FILE__);
$path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);
//echo $path;
//echo '<br />' . $path . 'wp-load.php';
$wpLoadFile = $path . 'wp-load.php';
if (!defined('ABSPATH')) {
    /** Set up WordPress environment */
    require_once($wpLoadFile);
  }

// Remove the admin bar from the front end
//add_filter( 'show_admin_bar', '__return_false' );

//require('../wp-load.php');


function invoice_history_menu()
{
    add_menu_page(
        __('Invoice History', 'my-textdomain'),
        __('Invoice History', 'my-textdomain'),
        'manage_options',
        'invoice_history_js',
        'invoice_history_contents',
        'dashicons-money',
        3
    );
}

add_action('admin_menu', 'invoice_history_menu');

function invoice_history_contents()
{
?>
    <h1>
        <?php esc_html_e('Welcome to your Invoice History.', 'invoice_history_js-plugin-textdomain'); ?>
    </h1>
<?php

// include('invoice_history_js.php');
include('invoice_history_simple.php');

}

function register_invoice_history_scripts()
{
    wp_register_style('invoice_history_js-plugin', plugins_url('ddd/css/plugin.css'));
    wp_register_script('invoice_history_js-plugin', plugins_url('ddd/js/plugin.js'));
}

add_action('admin_enqueue_scripts', 'register_invoice_history_scripts');

function load_invoice_history_scripts($hook)
{
    // Load only on ?page=invoice_history_js
    if ($hook != 'toplevel_page_invoice_history_js') {
        return;
    }
    // Load style & scripts.
    wp_enqueue_style('invoice_history_js-plugin');
    wp_enqueue_script('invoice_history_js-plugin');
}

add_action('admin_enqueue_scripts', 'load_invoice_history_scripts');
if ( ! function_exists( 'invoice_history_hook' ) ) {
    require_once 'invoice_history_db.php';
}

register_activation_hook(__FILE__,'invoice_history_hook');