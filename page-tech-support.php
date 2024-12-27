<?php
/*
Template Name: Тех. підтримка
*/

get_header();
?>

<div class="content-area">
    <main id="main" class="site-main" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>

            <div class="entry-content">
                <form id="tech-support-form" class="tech-support-form">
                    <div class="form-group">
                        <label for="support-title">Заголовок</label>
                        <input type="text" id="support-title" name="support-title" required>
                    </div>
                    <div class="form-group">
                        <label for="support-description">Опис проблеми чи ідеї</label>
                        <textarea id="support-description" name="support-description" required></textarea>
                    </div>
                    <button type="submit" class="submit-button">Відправити</button>
                </form>

                <div id="support-message" class="support-message"></div>

                <?php if (!is_user_logged_in()) : ?>
                    <div class="login-message">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/sinyor-pomidor.png" alt="Sinyor Pomidor" title="Sinyor Pomidor" class="pomidor-image">
                        <p>Увійдіть в акаунт, щоб відправити</p>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    </main>
</div>

<script>
jQuery(document).ready(function($) {
    $('#tech-support-form').on('submit', function(e) {
        e.preventDefault();
        
        if (!<?php echo is_user_logged_in() ? 'true' : 'false'; ?>) {
            $('#support-message').html('<p class="error">Будь ласка, увійдіть в акаунт, щоб відправити повідомлення.</p>');
            return;
        }

        var title = $('#support-title').val();
        var description = $('#support-description').val();

        if (title.trim() === '' || description.trim() === '') {
            $('#support-message').html('<div class="error-message"><img src="<?php echo get_template_directory_uri(); ?>/images/middle-pomidor.png" alt="Middle Pomidor" title="Middle Pomidor" class="pomidor-image"><p>Введіть коректні дані</p></div>');
            return;
        }

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'submit_tech_support',
                title: title,
                description: description
            },
            success: function(response) {
                $('#support-message').html('<p class="success">Ваше повідомлення успішно відправлено!</p>');
                $('#tech-support-form')[0].reset();
            },
            error: function() {
                $('#support-message').html('<p class="error">Виникла помилка при відправці. Спробуйте пізніше.</p>');
            }
        });

        $('#support-message').html('<p class="success">Ваше повідомлення успішно відправлено!</p>');
        $('#tech-support-form')[0].reset();
    });
});
</script>

<?php get_footer(); ?>