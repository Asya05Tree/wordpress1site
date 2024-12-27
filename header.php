<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<div id="page" class="site">
		<header id="masthead" class="site-header">
			<div class="header-container">
				<div>
					<?php
					$custom_logo_id = get_theme_mod('custom_logo');
					$logo = wp_get_attachment_image_src($custom_logo_id, 'full');
					if ($logo):
						?>
						<img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
							class="custom-logo">
					<?php else: ?>
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png"
							alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="custom-logo" title="InagroLogo">
					<?php endif; ?>
					<h1 class="site-title">
						<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
							<?php bloginfo('name'); ?>
						</a>
					</h1>
				</div>
				<nav id="site-navigation" class="main-navigation">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id' => 'primary-menu',
						)
					);
					?>
					<div class="accordion-menu">
						<button class="accordion">Пошук інструкцій</button>
						<div class="panel">
						<!-- <ul>
							<li><a href="<?php //echo get_permalink(get_page_by_path('page-qr-search')); ?>">Пошук за
									QR</a></li>
							<li><a href="<?php //echo get_permalink(get_page_by_path('search-categories')); ?>">Пошук
									за категоріями</a></li>
							<li><a href="<?php //echo get_permalink(get_page_by_path('search-all')); ?>">Пошук за
									категоріями, підкатегоріями або назвою інструкції</a></li>
						</ul> -->
						</div>
					</div>
					<a href="<?php echo get_permalink(get_page_by_path('tech-support')); ?>">Тех. підтримка</a>
				</nav>
				<div class="language-switcher">
					<?php
					if (function_exists('google_language_translator')) {
						echo do_shortcode('[google-translator]');
					}
					?>
				</div>
			</div>
			<div class="header-image">
				<div class="welcome-box">
					<p><?php esc_html_e('МИ ВИРОЩУЄМО ТОМАТНУ ПАСТУ', 'your-theme-textdomain'); ?></p>
					<a href="<?php echo esc_url(home_url('/qr-search')); ?>" class="button">
						<?php esc_html_e('ПРО НАС', 'your-theme-textdomain'); ?>
					</a>
				</div>
			</div>

		</header>

		<div id="content" class="site-content"></div>