<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <section class="product-types">
            <div class="product-box tomato-powder"
                style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/tomato-poroshok.jpg');">
                <h3>Томатний порошок</h3>
                <p>Зручність використання та довготривале зберігання - переваги нашого томатного порошку.</p>
            </div>
            <div class="product-box tomato-paste"
                style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/tomatna-pasta.jpg');">
                <h3>Томатна паста</h3>
                <p>Насичений смак та аромат справжніх томатів у кожній банці нашої томатної пасти.</p>
            </div>
        </section>
        <section class="company-sections">
            <div class="section-container">
                <div class="section-image"
                    style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/growing.jpg');">
                </div>
                <div class="section-content">
                    <h3>ВИРОЩУВАННЯ</h3>
                    <p>Наші томати вирощуються з любов'ю та турботою на найкращих полях, з використанням екологічно
                        чистих методів.</p>
                    <a href="#" class="learn-more">Дізнатися більше</a>
                </div>
            </div>

            <div class="section-container reverse">
                <div class="section-image"
                    style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/production.jpg');">
                </div>
                <div class="section-content">
                    <h3>ВИРОБНИЦТВО</h3>
                    <p>Сучасні технології та суворий контроль якості забезпечують найвищу якість нашої продукції.</p>
                    <a href="#" class="learn-more">Дізнатися більше</a>
                </div>
            </div>

            <div class="section-container">
                <div class="section-image"
                    style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/sustainability.jpg');">
                </div>
                <div class="section-content">
                    <h3>СТАЛИЙ РОЗВИТОК</h3>
                    <p>Ми дбаємо про навколишнє середовище та постійно впроваджуємо екологічно чисті практики у нашому
                        виробництві.</p>
                    <a href="#" class="learn-more">Дізнатися більше</a>
                </div>
            </div>
        </section>
    </main>
</div>

<?php get_footer(); ?>