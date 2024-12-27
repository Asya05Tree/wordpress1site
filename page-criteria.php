<?php
/*
Template Name: Критерії
*/

get_header();
?>

<div id="inagro-container">
    <div id="search-container">
        <input type="text" id="search-input" placeholder="Пошук інструкцій...">
        <button id="search-button" aria-label="Пошук"><i class="fas fa-search"></i></button>
    </div>
    <div id="search-error"></div>
    
    <div id="content-container">       
        <div id="criteria-container">
            <div id="categories-list"></div>
        </div>
        
        <div id="instructions-container">
            <div class="slider-controls">
                <button id="prev-instruction" class="slider-button" disabled>Назад</button>
                <button id="next-instruction" class="slider-button" disabled>Вперед</button>
            </div>
            <div id="instruction-content"></div>
        </div>
    </div>
</div>

<?php
wp_enqueue_script('inagro-ajax');
get_footer();
?>