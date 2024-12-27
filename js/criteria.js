jQuery(document).ready(function ($) {
    function initCriteria() {
        $('.parent-criteria').each(function () {
            var $item = $(this);
            var $title = $item.find('> h3');
            var $content = $item.find('> .criteria-content');
            var $expandButton = $item.find('> .criteria-content > .parent-content > .expand-parent');
            var $parentDetails = $item.find('> .criteria-content > .parent-content > .parent-details');
            var $childrenItems = $item.find('> .criteria-content > .child-criteria');

            $parentDetails.hide();

            if ($childrenItems.length > 0) {
                $childrenItems.hide();
                $expandButton.show();
            } else {
                $expandButton.hide();
                $parentDetails.show();
            }

            $title.click(function (e) {
                e.preventDefault();
                $content.slideToggle(300);
                $item.toggleClass('expanded');
                $title.find('.toggle-icon').html($item.hasClass('expanded') ? '&#9650;' : '&#9660;');
            });

            $expandButton.click(function (e) {
                e.preventDefault();
                $parentDetails.slideToggle(300);
                $(this).toggleClass('expanded');
            });

            $childrenItems.each(function () {
                var $childItem = $(this);
                var $childTitle = $childItem.find('> h4');
                var $childContent = $childItem.find('> .child-content');

                $childTitle.click(function (e) {
                    e.preventDefault();
                    $childContent.slideToggle(300);
                    $childItem.toggleClass('expanded');
                });
            });
        });
    }

    initCriteria();

    $('.filter-button').click(function () {
        var letter = $(this).data('letter');
        $('.filter-button').removeClass('active');
        $(this).addClass('active');

        if (letter === 'all') {
            $('.parent-criteria').show();
        } else {
            $('.parent-criteria').each(function () {
                var title = $(this).data('title');
                if (title.charAt(0).toUpperCase() === letter) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });

    $('.download-btn').click(function (e) {
        e.preventDefault();
        var fileUrl = $(this).data('file');
        var link = document.createElement('a');
        link.href = fileUrl;
        link.download = fileUrl.split('/').pop();
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    $('.share-btn').click(function () {
        var title = $(this).data('title');
        if (navigator.share) {
            navigator.share({
                title: title,
                url: window.location.href
            }).then(() => {
                console.log('Thanks for sharing!');
            })
            .catch(console.error);
        } else {
            alert('Sharing is not supported in your browser');
        }
    });

    $('.toggle-download').click(function () {
        $(this).toggleClass('active');
        $('.criteria-item.expanded, .parent-criteria.expanded').find('.file-preview').toggle();
    });
    
    $('.toggle-description').click(function () {
        $(this).toggleClass('active');
        $('.criteria-item.expanded, .parent-criteria.expanded').find('.description').toggle();
    });

    $('.floating-button:contains("Опис")').click(function() {
        $(this).toggleClass('active');
        $('.criteria-item.expanded, .parent-criteria.expanded').find('.description').toggle();
    });

    $('.floating-button.toggle-download').click(function() {
        $(this).toggleClass('active');
        $('.criteria-item.expanded, .parent-criteria.expanded').find('.file-preview').toggle();
    });
});