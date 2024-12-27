<?php
check_ajax_referer('inagro_ajax_nonce', 'nonce');

global $wpdb;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'get_categories':
            $categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}categories");
            wp_send_json_success($categories);
            break;

        case 'get_subcategories':
            $category_id = intval($_POST['category_id']);
            $subcategories = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}subcategories WHERE category_id = %d",
                    $category_id
                )
            );
            wp_send_json_success($subcategories);
            break;

        case 'get_instructions':
            $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
            $subcategory_id = isset($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : null;

            if ($subcategory_id) {
                $instructions = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT i.* FROM {$wpdb->prefix}instructions i
                        JOIN {$wpdb->prefix}subcategory_instruction si ON i.id = si.instruction_id
                        WHERE si.subcategory_id = %d",
                        $subcategory_id
                    )
                );
            } elseif ($category_id) {
                $subcategories = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT id FROM {$wpdb->prefix}subcategories WHERE category_id = %d",
                        $category_id
                    )
                );

                if (empty($subcategories)) {
                    $instructions = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT i.* FROM {$wpdb->prefix}instructions i
                            JOIN {$wpdb->prefix}category_instruction ci ON i.id = ci.instruction_id
                            WHERE ci.category_id = %d",
                            $category_id
                        )
                    );
                } else {
                    $instructions = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT DISTINCT i.* FROM {$wpdb->prefix}instructions i
                            JOIN {$wpdb->prefix}subcategory_instruction si ON i.id = si.instruction_id
                            JOIN {$wpdb->prefix}subcategories s ON si.subcategory_id = s.id
                            WHERE s.category_id = %d",
                            $category_id
                        )
                    );
                }
            } else {
                $instructions = array();
            }

            foreach ($instructions as &$instruction) {
                $instruction->file_url = content_url('uploads/pdf-files-instructions/' . basename($instruction->file_url));
            }

            wp_send_json_success($instructions);
            break;

        case 'search_instructions':
            $term = strtolower($_POST['term']);
            $instructions = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}instructions WHERE LOWER(name) LIKE %s OR LOWER(content) LIKE %s",
                    '%' . $wpdb->esc_like($term) . '%',
                    '%' . $wpdb->esc_like($term) . '%'
                )
            );

            foreach ($instructions as &$instruction) {
                $instruction->file_url = content_url('uploads/pdf-files-instructions/' . basename($instruction->file_url));
                $instruction->qr_code_url = content_url('uploads/qr-codes/' . basename($instruction->qr_code_url));
            }

            wp_send_json_success($instructions);
            break;
        case 'generate_qr_code':
            $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
            $instruction_id = isset($_POST['instruction_id']) ? intval($_POST['instruction_id']) : 0;

            if (empty($url) || empty($instruction_id)) {
                wp_send_json_error('URL або ID інструкції не вказано');
            }

            $qr_code_url = generate_and_save_qr_code($url, $instruction_id);

            if ($qr_code_url) {
                $qr_code_html = '<img src="' . esc_url($qr_code_url) . '" alt="QR Code" />';
                wp_send_json_success($qr_code_html);
            } else {
                wp_send_json_error('Не вдалося згенерувати QR-код');
            }
            break;

        default:
            wp_send_json_error('Invalid action');
            break;
    }
}

wp_die();