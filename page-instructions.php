<?php
/*
Template Name: Інструкція
*/

get_header();

$instruction_id = get_query_var('instruction_id') ? intval(get_query_var('instruction_id')) : 0;

// Отримання даних інструкції з бази даних
global $wpdb;
$instruction = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}instructions WHERE id = %d",
    $instruction_id
));

if ($instruction) :
?>

<div class="full-instruction-container">
    <div class="full-instruction">
        <h2><?php echo esc_html($instruction->name); ?></h2>
        <div class="instruction-content">
            <?php echo wp_kses_post($instruction->content); ?>
        </div>
        <div class="instruction-files">
            <a href="<?php echo esc_url(content_url('uploads/pdf-files-instructions/' . basename($instruction->file_url))); ?>" target="_blank">Скачати PDF</a>
            <div class="qr-code-container">
                <?php 
                $qr_code_url = content_url('uploads/qr-codes/' . basename($instruction->qr_code_url));
                echo '<img src="' . esc_url($qr_code_url) . '" alt="QR Code" />';
                ?>
            </div>
        </div>
    </div>
</div>

<?php
else :
    echo '<p>Інструкцію не знайдено.</p>';
endif;

get_footer();
?>