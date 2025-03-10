<?php
if (!defined('ABSPATH')) {
    exit;
}

// Attach Form Data to WooCommerce Order
function wcfbo_add_order_meta($order_id)
{
    error_log('wcfbo_add_order_meta');
    global $wpdb;
    $table_name = $wpdb->prefix . 'wcfbo_temp_data';

    if (isset($_COOKIE['wcfbo_session'])) {
        $session_key = sanitize_text_field($_COOKIE['wcfbo_session']);
        // $session_key = "wcfbo_67c41b5e626995.48674569";

        // Fetch the form data from database
        $form_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE session_key = %s", $session_key), ARRAY_A);

        if (!empty($form_data)) {
            // Attach form data to the order meta
            foreach ($form_data as $key => $value) {
                if (!in_array($key, ['id', 'session_key', 'created_at'])) {
                    update_post_meta($order_id, '_wcfbo_' . $key, sanitize_text_field($value));
                }
            }

            // Delete form data from database after successful order placement
            $wpdb->delete($table_name, ['session_key' => $session_key]);

            // Remove the session cookie
            setcookie('wcfbo_session', '', time() - 3600, '/');
        }
    }
}
add_action('woocommerce_thankyou', 'wcfbo_add_order_meta', 1, 1);

// Display Form Data in Admin Order Page
function wcfbo_display_custom_order_data($order)
{
    echo '<div style="margin-top: 20px; padding: 15px; background: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">';
    echo '<h4 style="margin: 0 0 15px; font-size: 16px; font-weight: 600; border-bottom: 2px solid #007cba; padding-bottom: 5px;">Customer Details</h4>';

    $fields = [
        'first_name' => 'First Name',
        'last_name'  => 'Last Name',
        'dob'        => 'Date of Birth',
        'tob'        => 'Time of Birth'
    ];

    foreach ($fields as $key => $label) {
        $value = get_post_meta($order->get_id(), '_wcfbo_' . $key, true);
        if ($value) {
            echo '<div style="margin-bottom: 8px; display: flex; align-items: center;">
                    <strong style="width: 150px; font-weight: 600; color: #333;">' . esc_html($label) . ':</strong> 
                    <span style="color: #555;">' . esc_html($value) . '</span>
                  </div>';
        }
    }

    echo '</div>';
}

add_action('woocommerce_admin_order_data_after_billing_address', 'wcfbo_display_custom_order_data');
