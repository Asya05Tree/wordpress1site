<?php
add_action('acf/init', 'my_acf_init');

function my_acf_init() {
    if (function_exists('acfe_add_local_field_group')):

        acfe_add_local_field_group(array(
            'key' => 'group_criteria_files',
            'title' => 'Критерії - файли',
            'fields' => array(
                array(
                    'key' => 'field_pdf_file',
                    'label' => 'PDF файл',
                    'name' => 'pdf_file',
                    'type' => 'file',
                    'return_format' => 'url',
                    'mime_types' => 'pdf',
                ),
                array(
                    'key' => 'field_docx_file',
                    'label' => 'DOCX файл',
                    'name' => 'docx_file',
                    'type' => 'file',
                    'return_format' => 'url',
                    'mime_types' => 'docx',
                ),
                array(
                    'key' => 'field_doc_file',
                    'label' => 'DOC файл',
                    'name' => 'doc_file',
                    'type' => 'file',
                    'return_format' => 'url',
                    'mime_types' => 'doc',
                ),
                array(
                    'key' => 'field_criteria_image',
                    'label' => 'Зображення критерію',
                    'name' => 'criteria_image',
                    'type' => 'image',
                    'return_format' => 'url',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'criteria',
                    ),
                ),
            ),
        ));

    endif;
}