<?php

// Підключення ACF полів
require_once get_template_directory() . '/acfe-fields.php';

// Налаштування теми
function canape_setup() {
    load_theme_textdomain('canape', get_template_directory() . '/languages');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(765, 380, true);
    add_image_size('canape-hero-thumbnail', 1180, 530, true);
    add_image_size('canape-testimonial-thumbnail', 90, 90, true);

    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'canape'),
        'social'  => esc_html__('Social Menu', 'canape'),
    ));

    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ));

    add_theme_support('post-formats', array('quote', 'link'));
}
add_action('after_setup_theme', 'canape_setup');

// Встановлення ширини контенту
function canape_content_width() {
    $GLOBALS['content_width'] = apply_filters('canape_content_width', 620);

    if (is_page_template('page-templates/full-width-page.php') || is_attachment()) {
        $GLOBALS['content_width'] = 765;
    }
}
add_action('after_setup_theme', 'canape_content_width', 0);

// Реєстрація сайдбарів
function canape_widgets_init() {
    $sidebar_args = array(
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    );

    $sidebars = array(
        'sidebar-1' => esc_html__('Main Sidebar', 'canape'),
        'sidebar-2' => esc_html__('First Footer Widget Area', 'canape'),
        'sidebar-3' => esc_html__('Second Footer Widget Area', 'canape'),
        'sidebar-4' => esc_html__('First Front Page Widget Area', 'canape'),
        'sidebar-5' => esc_html__('Second Front Page Widget Area', 'canape'),
        'sidebar-6' => esc_html__('Third Front Page Widget Area', 'canape'),
    );

    foreach ($sidebars as $id => $name) {
        register_sidebar(array_merge($sidebar_args, array('id' => $id, 'name' => $name)));
    }
}
add_action('widgets_init', 'canape_widgets_init');

// Підключення шрифтів
function canape_fonts_url() {
    $fonts_url = '';
    $font_families = array();

    $font_families[] = 'Playfair Display:400,400italic,700,700italic';
    $font_families[] = 'Noticia Text:400,400italic,700,700italic';
    $font_families[] = 'Montserrat:400,700';

    $query_args = array(
        'family' => urlencode(implode('|', $font_families)),
        'subset' => urlencode('latin,latin-ext'),
    );

    $fonts_url = add_query_arg($query_args, "https://fonts.googleapis.com/css");

    return $fonts_url;
}

// Підключення скриптів і стилів
function canape_scripts() {
    wp_enqueue_style('canape-fonts', canape_fonts_url(), array(), null);
    wp_enqueue_style('genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.3.1');
    wp_enqueue_style('canape-style', get_stylesheet_uri());
    wp_enqueue_style('my-inagro-styles', get_template_directory_uri() . '/css/myInagroCss.css', array('canape-style'), '1.1');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');

    wp_enqueue_script('canape-script', get_template_directory_uri() . '/js/canape.js', array('jquery'), '20150825', true);
    wp_enqueue_script('canape-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true);
    wp_enqueue_script('canape-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true);
    wp_localize_script('criteria-script', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    if (is_front_page() && !is_home() && get_theme_mod('canape_front_testimonials', 1) && canape_has_testimonials()) {
        wp_enqueue_script('canape-flexslider', get_template_directory_uri() . '/js/canape-flexslider.js', array('jquery', 'flexslider'), '20170914', true);
        wp_enqueue_script('flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', array('jquery'), '20170914', true);
        wp_enqueue_style('flexslider-styles', get_template_directory_uri() . '/css/flexslider.css', array(), '20170914');
    }
}
add_action('wp_enqueue_scripts', 'canape_scripts');

// Підключення оновлювача теми (тільки для адмін панелі)
if (is_admin()) {
    include dirname(__FILE__) . '/inc/updater.php';
}

// Inagro AJAX функціональність
function enqueue_custom_scripts() {
    wp_enqueue_script('inagro-ajax', get_template_directory_uri() . '/js/inagro-ajax.js', array('jquery'), '1.0', true);
    wp_localize_script('inagro-ajax', 'inagro_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('inagro_ajax_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// AJAX дії
add_action('wp_ajax_get_categories', 'inagro_get_categories');
add_action('wp_ajax_nopriv_get_categories', 'inagro_get_categories');
add_action('wp_ajax_get_subcategories', 'inagro_get_subcategories');
add_action('wp_ajax_nopriv_get_subcategories', 'inagro_get_subcategories');
add_action('wp_ajax_get_instructions', 'inagro_get_instructions');
add_action('wp_ajax_nopriv_get_instructions', 'inagro_get_instructions');
add_action('wp_ajax_search_instructions', 'search_instructions');
add_action('wp_ajax_nopriv_search_instructions', 'search_instructions');
add_action('wp_ajax_generate_qr_code', 'ajax_generate_qr_code');
add_action('wp_ajax_nopriv_generate_qr_code', 'ajax_generate_qr_code');

// Підключення файлу з AJAX функціями
function inagro_get_categories() {
    require_once('inagro-ajax.php');
}

function inagro_get_subcategories() {
    require_once('inagro-ajax.php');
}

function inagro_get_instructions() {
    require_once('inagro-ajax.php');
}

// Генерація QR-коду
function generate_qr_code_for_instruction($instruction_id) {
    global $wpdb;
    $instruction = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}instructions WHERE id = %d",
        $instruction_id
    ));

    if ($instruction) {
        $pdf_url = content_url('uploads/pdf-files-instructions/' . basename($instruction->file_url));
        generate_and_save_qr_code($pdf_url, $instruction_id);
    }
}
add_action('save_instruction', 'generate_qr_code_for_instruction');

function ajax_generate_qr_code() {
    check_ajax_referer('inagro_ajax_nonce', 'nonce');

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
    $instruction_id = isset($_POST['instruction_id']) ? intval($_POST['instruction_id']) : 0;
    
    console.log("URL: " . $url);
    console.log("Instruction ID: " . $instruction_id);
    
    if (empty($url) || empty($instruction_id)) {
        console.log("URL або ID інструкції не вказано");
        wp_send_json_error('URL або ID інструкції не вказано');
    }

    $qr_code_url = generate_and_save_qr_code($url, $instruction_id);
    
    if ($qr_code_url) {
        $qr_code_html = '<img src="' . esc_url($qr_code_url) . '" alt="QR Code" />';
        console.log("QR Code URL: " . $qr_code_url);
        wp_send_json_success($qr_code_html);
    } else {
        console.log("Не вдалося згенерувати QR-код");
        wp_send_json_error('Не вдалося згенерувати QR-код');
    }
}

function generate_and_save_qr_code($pdf_url, $instruction_id) {
    console.log("Початок generate_and_save_qr_code");
    console.log("PDF URL: " . $pdf_url);
    console.log("Instruction ID: " . $instruction_id);

    if (!function_exists('dynamicqr_generate_qr_code')) {
        console.log("Функція dynamicqr_generate_qr_code не існує");
        return false;
    }
    $qr_code = dynamicqr_generate_qr_code($pdf_url);

    if (!$qr_code) {
        console.log("Не вдалося згенерувати QR-код");
        return false;
    }

    $upload_dir = wp_upload_dir();
    $qr_code_dir = $upload_dir['basedir'] . '/qr-codes';
    console.log("QR Code Directory: " . $qr_code_dir);
    if (!file_exists($qr_code_dir)) {
        wp_mkdir_p($qr_code_dir);
        console.log("Створено директорію для QR-кодів");
    }

    $filename = 'qr-code-' . $instruction_id . '.png';
    $file_path = $qr_code_dir . '/' . $filename;
    console.log("File Path: " . $file_path);
    $result = file_put_contents($file_path, $qr_code);
    console.log("Результат збереження файлу: " . ($result !== false ? "Успішно" : "Помилка"));

    $qr_code_url = $upload_dir['baseurl'] . '/qr-codes/' . $filename;
    console.log("QR Code URL: " . $qr_code_url);

    global $wpdb;
    $update_result = $wpdb->update(
        $wpdb->prefix . 'instructions',
        array('qr_code_url' => $qr_code_url),
        array('id' => $instruction_id),
        array('%s'),
        array('%d')
    );
    console.log("Результат оновлення бази даних (instructions): " . $update_result);

    $insert_result = $wpdb->insert(
        $wpdb->prefix . 'sos_dqc_qrcodes',
        array(
            'url' => $pdf_url,
            'qrcode_url' => $qr_code_url,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        ),
        array('%s', '%s', '%s', '%s')
    );
    console.log("Результат вставки в базу даних (sos_dqc_qrcodes): " . $insert_result);

    console.log("Завершення generate_and_save_qr_code");
    return $qr_code_url;
}

// Створення сторінок інструкцій
function create_instruction_pages() {
    add_rewrite_rule(
        'page-instructions/?([0-9]+)?/?$',
        'index.php?pagename=page-instructions&instruction_id=$matches[1]',
        'top'
    );
}
add_action('init', 'create_instruction_pages');

function add_query_vars($query_vars) {
    $query_vars[] = 'instruction_id';
    return $query_vars;
}
add_filter('query_vars', 'add_query_vars');

function load_instruction_template($template) {
    if (get_query_var('pagename') === 'page-instructions') {
        $new_template = locate_template(array('page-instructions.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'load_instruction_template', 99);