$(document).ready(function() {
    $('.filter-button').click(function() {
        var letter = $(this).data('letter');
        $('.filter-button').removeClass('active');
        $(this).addClass('active');

        if (letter === 'all') {
            $('.category-item').show();
        } else {
            $('.category-item').each(function() {
                var title = $(this).data('title');
                if (title.charAt(0).toUpperCase() === letter) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });
});