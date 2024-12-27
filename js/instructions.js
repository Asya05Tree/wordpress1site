$(document).ready(function() {
    const $instructionItems = $('.instruction-item');
    const $prevButton = $('.prev-button');
    const $nextButton = $('.next-button');
    const $sliderNumbers = $('.slider-numbers');
    let currentIndex = 0;

    function updateSlider() {
        $instructionItems.removeClass('active');
        $instructionItems.eq(currentIndex).addClass('active');

        $prevButton.prop('disabled', currentIndex === 0);
        $nextButton.prop('disabled', currentIndex === $instructionItems.length - 1);

        updateSliderNumbers();
    }

    function updateSliderNumbers() {
        $sliderNumbers.empty();
        for (let i = Math.max(0, currentIndex - 2); i < Math.min($instructionItems.length, currentIndex + 3); i++) {
            const $number = $('<span>').text(i + 1).addClass('slider-number');
            if (i === currentIndex) {
                $number.addClass('active');
            }
            $sliderNumbers.append($number);
        }
    }

    $prevButton.click(function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });

    $nextButton.click(function() {
        if (currentIndex < $instructionItems.length - 1) {
            currentIndex++;
            updateSlider();
        }
    });

    updateSlider();
});