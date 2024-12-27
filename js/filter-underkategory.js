$(document).ready(function() {
    $('.filter-button').click(function() {
        var letter = $(this).data('letter');
        $('.filter-button').removeClass('active');
        $(this).addClass('active');

        if (letter === 'all') {
            $('.subcategory-item').show();
        } else {
            $('.subcategory-item').each(function() {
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