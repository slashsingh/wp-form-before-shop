<?php
if (!defined('ABSPATH')) {
    exit;
}

// Handle Form Submission
function wcfbo_handle_form_submission()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wcfbo_form_submission'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcfbo_temp_data';

        // Generate a unique session key
        $session_key = uniqid('wcfbo_', true);
        setcookie('wcfbo_session', $session_key, time() + 3600, '/'); // Store in a cookie

        // Retrieve saved form fields
        $form_fields = json_decode(get_option('wcfbo_form_fields', '[]'), true);

        // Sanitize inputs
        $data = array('session_key' => $session_key);
        foreach ($form_fields as $field) {
            $field_name = sanitize_text_field($field['label']);
            if (isset($_POST[$field_name])) {
                $data[$field_name] = sanitize_text_field($_POST[$field_name]);
            }
        }

        // Insert into database
        $wpdb->insert($table_name, $data);

        // Redirect to shop
        wp_redirect(get_permalink(wc_get_page_id('shop')));
        exit;
    }
}
add_action('template_redirect', 'wcfbo_handle_form_submission');
