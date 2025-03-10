<?php

// ...existing code...

function wcfbo_enqueue_admin_scripts($hook)
{
    if ($hook !== 'toplevel_page_wcfbo_form_builder') {
        return;
    }
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-droppable');
    wp_enqueue_script('wcfbo-admin-script', plugin_dir_url(__FILE__) . 'js/form-builder.js', array('jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable'), null, true);
    wp_localize_script('wcfbo-admin-script', 'wcfbo', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wcfbo_save_form'),
        'form_fields' => get_option('wcfbo_form_fields', '[]')
    ));
    wp_enqueue_style('wcfbo-admin-style', plugin_dir_url(__FILE__) . 'css/form-builder.css');
}
add_action('admin_enqueue_scripts', 'wcfbo_enqueue_admin_scripts');

// ...existing code...

// Add menu page
function wcfbo_add_admin_menu()
{
    add_menu_page(
        'Form Builder',
        'Form Builder',
        'manage_options',
        'wcfbo_form_builder',
        'wcfbo_render_admin_page',
        'dashicons-editor-table',
        20
    );
}
add_action('admin_menu', 'wcfbo_add_admin_menu');

// Render Admin Page
// ...existing code...

function wcfbo_render_admin_page()
{
    $form_fields = get_option('wcfbo_form_fields', '[]');
?>
    <div class="wrap">
        <h1>Form Builder</h1>

        <div id="wcfbo-form-builder">
            <h3>Drag Fields to Add</h3>
            <ul id="wcfbo-field-types" class="draggable-fields">
                <li data-type="text">Text</li>
                <li data-type="number">Number</li>
                <li data-type="datepicker">Datepicker</li>
                <li data-type="dropdown">Dropdown</li>
                <li data-type="radio">Radio Buttons</li>
            </ul>

            <h3>Form Fields</h3>
            <ul id="wcfbo-fields" class="sortable droppable"></ul>

            <button type="button" id="wcfbo-save-form" class="button button-primary">Save Form</button>
        </div>

        <h2>Preview</h2>
        <div id="wcfbo-preview"></div>
    </div>
<?php
}

// ...existing code...

// Handle form save
function wcfbo_save_form()
{
    check_ajax_referer('wcfbo_save_form', 'security');
    if (isset($_POST['fields'])) {
        $fields = json_decode(stripslashes($_POST['fields']), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            update_option('wcfbo_form_fields', json_encode($fields));
            wp_send_json_success('Form saved!');
        } else {
            wp_send_json_error('Invalid JSON data.');
        }
    }
    wp_die();
}
add_action('wp_ajax_wcfbo_save_form', 'wcfbo_save_form');
