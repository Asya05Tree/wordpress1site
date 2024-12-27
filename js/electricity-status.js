jQuery(document).ready(function($) {
    function updateElectricityStatus() {
        $.ajax({
            url: electricityAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'electricity_status'
            },
            success: function(response) {
                $('.tomato-icon').removeClass('light-on light-off').addClass(response.iconClass);
            }
        });
    }

    updateElectricityStatus();
    setInterval(updateElectricityStatus, 600);
});