jQuery(document).ready(function($) {
    $('#test-ajax-button').on('click', function() {
        $.ajax({
            url: ajax_test_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'test_ajax',
                nonce: ajax_test_obj.nonce
            },
            success: function(response) {
                if(response.success) {
                    $('#ajax-response').html('<p>' + response.message + '</p>');
                } else {
                    $('#ajax-response').html('<p>Помилка AJAX</p>');
                }
            },
            error: function() {
                $('#ajax-response').html('<p>Помилка AJAX запиту</p>');
            }
        });
    });
});