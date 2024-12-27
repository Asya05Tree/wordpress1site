jQuery(document).ready(function($) {
    console.log('AJAX URL:', ajax_object.ajax_url);
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
            action: 'get_categories',
        },
        success: function(response) {
            console.log('Отримано відповідь від сервера:', response);
            if (response.success) {
                console.log('Дані успішно отримано');
            } else {
                console.error('Помилка отримання даних:', response.data);
                $('#categories-data').html('Помилка отримання даних');
            }
        },
        error: function(xhr, status, error) {
            console.error('Помилка запиту AJAX:', status, error);
            console.error('Текст відповіді:', xhr.responseText);
            $('#categories-data').html('Не вдалося отримати дані');
        }
    });
});