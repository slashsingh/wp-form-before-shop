<?php
function wcfbo_render_form_fields()
{
    $form_fields = json_decode(get_option('wcfbo_form_fields', '[]'), true);

    if (!empty($form_fields)) {
        foreach ($form_fields as $field) {
            $required = $field['required'] ? 'required' : '';
            $placeholder = !empty($field['placeholder']) ? 'placeholder="' . esc_attr($field['placeholder']) . '"' : '';

            echo '<div class="form-group">';
            switch ($field['type']) {
                case 'text':
                    echo '<label>' . esc_html($field['label']) . ': <input type="text" name="' . esc_attr($field['label']) . '" ' . $placeholder . ' ' . $required . '></label>';
                    break;
                case 'number':
                    echo '<label>' . esc_html($field['label']) . ': <input type="number" name="' . esc_attr($field['label']) . '" ' . $placeholder . ' ' . $required . '></label>';
                    break;
                case 'datepicker':
                    echo '<label>' . esc_html($field['label']) . ': <input type="date" name="' . esc_attr($field['label']) . '" ' . $required . '></label>';
                    break;
                case 'dropdown':
                    echo '<label>' . esc_html($field['label']) . ': <select name="' . esc_attr($field['label']) . '" ' . $required . '>';
                    foreach ($field['options'] as $option) {
                        echo '<option value="' . esc_attr($option) . '">' . esc_html($option) . '</option>';
                    }
                    echo '</select></label>';
                    break;
                case 'radio':
                    echo '<label>' . esc_html($field['label']) . ': ';
                    foreach ($field['options'] as $option) {
                        echo '<input type="radio" name="' . esc_attr($field['label']) . '" value="' . esc_attr($option) . '" ' . $required . '> ' . esc_html($option) . '<br>';
                    }
                    echo '</label>';
                    break;
            }
            echo '</div>';
        }
    }
}
