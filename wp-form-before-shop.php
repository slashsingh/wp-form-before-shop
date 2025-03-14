<?php

/**
 * Plugin Name: WooCommerce Form Before Order
 * Description: Adds a form before order placement and stores data with WooCommerce orders.
 * Version: 1.0.0
 * Author: Vishal Singh
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/form-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/order-meta.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/form-builder.php';
include_once plugin_dir_path(__FILE__) . 'includes/functions.php';

// Create Database Table on Plugin Activation
function wcfbo_create_db_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wcfbo_temp_data';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_key VARCHAR(255) NOT NULL,
        first_name VARCHAR(100),
        last_name VARCHAR(100),
        dob VARCHAR(20),
        tob VARCHAR(10),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'wcfbo_create_db_table');


// Enqueue Scripts and Styles
function wcfbo_enqueue_scripts()
{
    wp_enqueue_style('wcfbo-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('wcfbo-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'wcfbo_enqueue_scripts');

// Shortcode to Display Form
function wcfbo_display_form()
{
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/form-template.php';
    return ob_get_clean();
}

add_shortcode('wcfbo_form', 'wcfbo_display_form');
